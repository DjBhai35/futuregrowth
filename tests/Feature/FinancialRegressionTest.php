<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Wallet;
use App\Models\Investment;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\Setting;
use App\Services\ReferralService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FinancialRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plan = Plan::create([
            'name' => 'Starter',
            'min_amount' => 25,
            'max_amount' => 1000,
            'min_roi' => 2.0,
            'max_roi' => 2.0,
            'status' => 'active',
        ]);
    }

    private function makeUser(string $name, ?User $referrer = null): User
    {
        $user = User::create([
            'name' => $name,
            'username' => strtolower(Str::slug($name)) . '_' . Str::random(4),
            'email' => strtolower(Str::slug($name)) . '_' . Str::random(4) . '@test.com',
            'phone' => '+1' . rand(1000000000, 9999999999),
            'password' => Hash::make('secret123'),
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referrer ? $referrer->id : null,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        Wallet::create(['user_id' => $user->id]);
        return $user;
    }

    /**
     * Test 1: User Registration with referral code binds to referrer correctly
     */
    public function test_user_registration_binds_to_referrer(): void
    {
        $upline = $this->makeUser('Sponsor');

        $response = $this->post(route('register'), [
            'name' => 'Downline Member',
            'username' => 'new_member_1',
            'email' => 'new_member_1@test.com',
            'phone' => '+19876543210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'referral_code' => $upline->referral_code,
        ]);

        $response->assertRedirect(route('verification.notice'));

        $downline = User::where('email', 'new_member_1@test.com')->first();
        $this->assertNotNull($downline);
        $this->assertEquals($upline->id, $downline->referred_by);
        $this->assertNotNull($downline->wallet);
    }

    /**
     * Test 2: ReferralService distributes 20% Direct Reward + 10 Levels (5%, 4%, 3%, 3%, 2%, 2%, 1%, 1%, 1%, 1%)
     */
    public function test_referral_service_multilevel_distribution(): void
    {
        $referralService = new ReferralService();

        // Build 10-level hierarchy: U10 <- U9 <- ... <- U1 <- LeafUser
        $chain = [];
        $currentParent = null;
        for ($lvl = 10; $lvl >= 1; $lvl--) {
            $user = $this->makeUser("Upline Level $lvl", $currentParent);
            $chain[$lvl] = $user;
            $currentParent = $user;
        }

        // New investor directly sponsored by U1 ($chain[1])
        $investor = $this->makeUser('Investor', $chain[1]);

        $investmentAmount = 1000.00;
        $referralService->distributeCommission($investor, $investmentAmount);

        // Chain 1 is direct referrer: gets 20% direct reward ($200) + Level 1 bonus 5% ($50) = $250 total
        $chain[1]->wallet->refresh();
        $this->assertEquals(250.00, (float) $chain[1]->wallet->referral_balance);

        // Chain 2 (Level 2 upline): 4% ($40)
        $chain[2]->wallet->refresh();
        $this->assertEquals(40.00, (float) $chain[2]->wallet->referral_balance);

        // Chain 3 (Level 3 upline): 3% ($30)
        $chain[3]->wallet->refresh();
        $this->assertEquals(30.00, (float) $chain[3]->wallet->referral_balance);

        // Chain 4 (Level 4 upline): 3% ($30)
        $chain[4]->wallet->refresh();
        $this->assertEquals(30.00, (float) $chain[4]->wallet->referral_balance);

        // Chain 5 (Level 5 upline): 2% ($20)
        $chain[5]->wallet->refresh();
        $this->assertEquals(20.00, (float) $chain[5]->wallet->referral_balance);

        // Chain 6 (Level 6 upline): 2% ($20)
        $chain[6]->wallet->refresh();
        $this->assertEquals(20.00, (float) $chain[6]->wallet->referral_balance);

        // Chain 7 through 10 (Levels 7-10 upline): 1% each ($10)
        for ($lvl = 7; $lvl <= 10; $lvl++) {
            $chain[$lvl]->wallet->refresh();
            $this->assertEquals(10.00, (float) $chain[$lvl]->wallet->referral_balance);
        }
    }

    /**
     * Test 3: Investment creation and 300% (3X) Cap Enforcement on ROI
     */
    public function test_investment_and_roi_cap_enforcement(): void
    {
        $user = $this->makeUser('Investor 1');
        $user->wallet->deposit_balance = 500.00;
        $user->wallet->save();

        // Create an investment of $100
        $investment = Investment::create([
            'user_id' => $user->id,
            'plan_id' => $this->plan->id,
            'amount' => 100.00,
            'total_earned' => 0.00,
            'daily_roi_percent' => 2.0, // 2% daily = $2.00
            'status' => 'active',
        ]);

        // Max return is 3X = $300.00
        // Set earned to $299.00
        $investment->total_earned = 299.00;
        $investment->last_roi_at = now()->subHours(25);
        $investment->save();

        // Run dashboard index which triggers distributeUserROI
        $this->actingAs($user);
        $this->get(route('dashboard'));

        $investment->refresh();
        // Should only be credited $1.00 to hit exactly $300.00 cap, then marked completed
        $this->assertEquals(300.00, (float) $investment->total_earned);
        $this->assertEquals('completed', $investment->status);

        $user->wallet->refresh();
        $this->assertEquals(1.00, (float) $user->wallet->roi_balance);
    }

    /**
     * Test 4: Deposit creation, transaction creation, and admin approval flow
     */
    public function test_deposit_creation_and_admin_approval(): void
    {
        $user = $this->makeUser('Depositor');

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => 500.00,
            'payment_method' => 'USDT (TRC20)',
            'txid' => '0x123456789abcdef',
            'status' => 'pending',
        ]);

        $admin = $this->makeUser('Admin Super');
        $admin->is_admin = true;
        $admin->save();

        $this->actingAs($admin);
        $controller = new \App\Http\Controllers\AdminController();
        $response = $controller->approveDeposit($deposit->id);

        $deposit->refresh();
        $this->assertEquals('approved', $deposit->status);

        $user->wallet->refresh();
        $this->assertEquals(500.00, (float) $user->wallet->deposit_balance);

        $tx = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->first();
        $this->assertNotNull($tx);
        $this->assertEquals(500.00, (float) $tx->amount);
    }

    /**
     * Test 5: Withdrawal creation with 5% fee and admin rejection refunds wallet
     */
    public function test_withdrawal_creation_with_fee_and_rejection_refund(): void
    {
        $user = $this->makeUser('WithdrawUser');
        $user->wallet->roi_balance = 200.00;
        $user->wallet->save();

        $this->actingAs($user);
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'amount' => 100.00,
            'wallet_address' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
            'balance_type' => 'roi_balance',
        ]);

        $controller = new \App\Http\Controllers\WithdrawalController();
        $controller->store($request);

        $user->wallet->refresh();
        $this->assertEquals(100.00, (float) $user->wallet->roi_balance);

        $withdrawal = Withdrawal::where('user_id', $user->id)->first();
        $this->assertNotNull($withdrawal);
        $this->assertEquals(100.00, (float) $withdrawal->amount);
        $this->assertEquals(5.00, (float) $withdrawal->fee); // 5% fee
        $this->assertEquals(95.00, (float) $withdrawal->net_amount);

        // Admin rejects withdrawal -> must refund $100 back to roi_balance
        $admin = $this->makeUser('Admin Approver');
        $admin->is_admin = true;
        $admin->save();
        $this->actingAs($admin);

        $adminController = new \App\Http\Controllers\AdminController();
        $adminController->rejectWithdrawal($withdrawal->id);

        $withdrawal->refresh();
        $this->assertEquals('rejected', $withdrawal->status);

        $user->wallet->refresh();
        $this->assertEquals(200.00, (float) $user->wallet->roi_balance); // Refunded!
    }
}
