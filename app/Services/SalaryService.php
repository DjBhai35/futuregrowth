<?php

namespace App\Services;

use App\Models\User;
use App\Models\SalaryLevel;
use App\Models\SalaryClaim;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaryService
{
    /**
     * Get all active salary levels from the database.
     */
    public function getActiveLevels()
    {
        return SalaryLevel::active()->orderBy('required_directs', 'asc')->get();
    }

    /**
     * Get all salary levels (including inactive) for admin management.
     */
    public function getAllLevels()
    {
        return SalaryLevel::orderBy('level_number', 'asc')->get();
    }

    /**
     * Get direct members for a given user.
     */
    public function getDirectMembers(User $user)
    {
        return User::where('referred_by', $user->id)
            ->with(['wallet', 'investments'])
            ->get();
    }

    /**
     * Determine if a specific direct member qualifies for salary.
     * Direct member qualifies if they have at least one active investment of at least $minInvestment,
     * OR total active investments >= $minInvestment.
     */
    public function isMemberQualifying(User $member, float $minInvestment = 50.00): bool
    {
        $activeInvestmentsTotal = Investment::where('user_id', $member->id)
            ->where('status', 'active')
            ->sum('amount');

        return (float) $activeInvestmentsTotal >= $minInvestment;
    }

    /**
     * Get list of qualifying direct members and their investment sum.
     */
    public function getQualifyingDirectMembers(User $user, float $minInvestment = 50.00)
    {
        $directs = $this->getDirectMembers($user);

        return $directs->filter(function ($member) use ($minInvestment) {
            $totalActive = $member->investments->where('status', 'active')->sum('amount');
            $member->active_investment_sum = (float) $totalActive;
            return $totalActive >= $minInvestment;
        })->values();
    }

    /**
     * Count how many qualifying direct members a user has.
     */
    public function countQualifyingDirects(User $user, float $minInvestment = 50.00): int
    {
        // Direct members with active investments totaling at least minInvestment
        return User::where('referred_by', $user->id)
            ->whereHas('investments', function ($query) use ($minInvestment) {
                $query->where('status', 'active');
            })
            ->get()
            ->filter(function ($member) use ($minInvestment) {
                return $this->isMemberQualifying($member, $minInvestment);
            })
            ->count();
    }

    /**
     * Calculate a user's full salary eligibility status.
     * Evaluates active salary levels from the database.
     * Returns non-cumulative highest qualified level.
     */
    public function calculateEligibility(User $user, ?string $period = null): array
    {
        $currentPeriod = $period ?: now()->format('Y-m');
        $levels = $this->getActiveLevels();
        $totalDirects = User::where('referred_by', $user->id)->count();

        // Use the baseline min investment from active levels, or default 50
        $baselineMinInv = $levels->first() ? (float) $levels->first()->min_investment : 50.00;
        $qualifyingDirectsCount = $this->countQualifyingDirects($user, $baselineMinInv);

        $qualifiedLevel = null;
        $nextLevel = null;

        // Iterate through active levels ordered ascending by required directs
        foreach ($levels as $level) {
            // Re-evaluate specifically against this level's required min investment
            $levelQualifyingCount = $this->countQualifyingDirects($user, (float) $level->min_investment);

            if ($levelQualifyingCount >= $level->required_directs) {
                // Non-cumulative: replace with higher level as loop progresses
                $qualifiedLevel = $level;
            } elseif ($nextLevel === null) {
                $nextLevel = $level;
            }
        }

        // Check if user already claimed for this monthly period
        $existingClaim = SalaryClaim::where('user_id', $user->id)
            ->where('claim_period', $currentPeriod)
            ->first();

        $lastClaim = SalaryClaim::where('user_id', $user->id)
            ->latest('claimed_at')
            ->first();

        $totalClaimed = (float) SalaryClaim::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        $isEligible = $qualifiedLevel !== null && (float) $qualifiedLevel->monthly_salary > 0;
        $canClaim = $isEligible && !$existingClaim;

        $neededForNext = null;
        if ($nextLevel) {
            $neededForNext = max(0, $nextLevel->required_directs - $qualifyingDirectsCount);
        }

        return [
            'user_id' => $user->id,
            'total_directs' => $totalDirects,
            'qualifying_directs' => $qualifyingDirectsCount,
            'qualified_level' => $qualifiedLevel,
            'salary_amount' => $qualifiedLevel ? (float) $qualifiedLevel->monthly_salary : 0.00,
            'is_eligible' => $isEligible,
            'can_claim' => $canClaim,
            'has_claimed_current_period' => (bool) $existingClaim,
            'current_period' => $currentPeriod,
            'existing_claim' => $existingClaim,
            'last_claim' => $lastClaim,
            'total_claimed' => $totalClaimed,
            'next_level' => $nextLevel,
            'needed_for_next_level' => $neededForNext,
            'next_eligible_date' => $existingClaim ? Carbon::parse($currentPeriod . '-01')->addMonth()->startOfMonth() : now(),
        ];
    }

    /**
     * Execute atomic salary claim for a user for the specified or current period.
     * Enforces duplicate protection, concurrency lock, and wallet/ledger trace.
     */
    public function claimSalary(User $user, ?string $period = null): array
    {
        $currentPeriod = $period ?: now()->format('Y-m');

        return DB::transaction(function () use ($user, $currentPeriod) {
            // Lock user record for update to prevent race conditions across parallel requests
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

            // Strict double-check inside locked transaction
            $alreadyClaimed = SalaryClaim::where('user_id', $lockedUser->id)
                ->where('claim_period', $currentPeriod)
                ->lockForUpdate()
                ->first();

            if ($alreadyClaimed) {
                return [
                    'success' => false,
                    'message' => "Salary for period {$currentPeriod} has already been claimed on " . $alreadyClaimed->claimed_at->format('M d, Y H:i'),
                    'claim' => $alreadyClaimed,
                ];
            }

            // Recalculate qualification strictly on the server
            $status = $this->calculateEligibility($lockedUser, $currentPeriod);

            if (!$status['is_eligible'] || !$status['qualified_level']) {
                return [
                    'success' => false,
                    'message' => 'User does not currently qualify for any active salary tier.',
                    'claim' => null,
                ];
            }

            $level = $status['qualified_level'];
            $amount = (float) $level->monthly_salary;

            if ($amount <= 0) {
                return [
                    'success' => false,
                    'message' => 'Qualified salary amount must be greater than zero.',
                    'claim' => null,
                ];
            }

            // 1. Create the SalaryClaim record
            $claim = SalaryClaim::create([
                'user_id' => $lockedUser->id,
                'salary_level_id' => $level->id,
                'level_number' => $level->level_number,
                'required_directs' => $level->required_directs,
                'qualifying_directs' => $status['qualifying_directs'],
                'amount' => $amount,
                'claim_period' => $currentPeriod,
                'claimed_at' => now(),
                'status' => 'completed',
                'admin_notes' => 'User claimed salary for Level ' . $level->level_number . ' (Period: ' . $currentPeriod . ')',
            ]);

            // 2. Credit the user's wallet
            $wallet = $lockedUser->wallet;
            // Credit to dedicated salary_balance if available, or referral_balance as fallback
            if (\Illuminate\Support\Facades\Schema::hasColumn('wallets', 'salary_balance')) {
                $wallet->salary_balance += $amount;
                $walletType = 'salary_balance';
            } else {
                $wallet->referral_balance += $amount;
                $walletType = 'referral_balance';
            }
            $wallet->save();

            // 3. Create traceable financial transaction
            $transaction = Transaction::create([
                'user_id' => $lockedUser->id,
                'type' => 'salary',
                'amount' => $amount,
                'wallet_type' => $walletType,
                'status' => 'completed',
                'description' => "Monthly Leadership Salary: Level {$level->level_number} ({$level->name}) for period {$currentPeriod}",
                'reference_id' => $claim->id,
            ]);

            // Link transaction to claim
            $claim->transaction_id = $transaction->id;
            $claim->save();

            // 4. Record audit log
            ActivityLog::create([
                'user_id' => Auth::id() ?: $lockedUser->id,
                'action' => "Processed monthly salary claim of \${$amount} (Level {$level->level_number}) for user {$lockedUser->name} [Period: {$currentPeriod}]",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return [
                'success' => true,
                'message' => "Successfully claimed \${$amount} monthly salary for Level {$level->level_number} ({$currentPeriod}).",
                'claim' => $claim,
                'transaction' => $transaction,
                'amount' => $amount,
            ];
        });
    }

    /**
     * Get platform-wide salary metrics for Admin Dashboard.
     */
    public function getAdminMetrics(): array
    {
        $totalSalaryPaid = (float) SalaryClaim::where('status', 'completed')->sum('amount');
        $currentMonthPeriod = now()->format('Y-m');
        $currentMonthPaid = (float) SalaryClaim::where('status', 'completed')
            ->where('claim_period', $currentMonthPeriod)
            ->sum('amount');

        $currentMonthClaimants = SalaryClaim::where('claim_period', $currentMonthPeriod)
            ->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');

        $activeLevelsCount = SalaryLevel::where('is_active', true)->count();

        // Level distribution
        $levelDistribution = [];
        $levels = SalaryLevel::orderBy('level_number', 'asc')->get();
        foreach ($levels as $lvl) {
            $claimsCount = SalaryClaim::where('salary_level_id', $lvl->id)->count();
            $levelDistribution[] = [
                'level' => $lvl,
                'claims_count' => $claimsCount,
            ];
        }

        return [
            'total_salary_paid' => $totalSalaryPaid,
            'current_month_paid' => $currentMonthPaid,
            'current_month_claimants' => $currentMonthClaimants,
            'active_levels_count' => $activeLevelsCount,
            'level_distribution' => $levelDistribution,
            'current_period' => $currentMonthPeriod,
        ];
    }
}
