<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Wallet;
use App\Models\Investment;
use App\Models\SalaryLevel;
use App\Models\SalaryClaim;
use App\Models\Transaction;
use App\Services\SalaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SalarySystemTest extends TestCase
{
    use RefreshDatabase;

    // Note: We use database transactions or in-memory SQLite in testing environment
    protected SalaryService $salaryService;
    protected User $leader;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salaryService = new SalaryService();

        // Seed default salary levels if not present
        if (SalaryLevel::count() === 0) {
            $levels = [
                ['level_number' => 1, 'name' => 'Level 1', 'required_directs' => 5, 'min_investment' => 50.00, 'monthly_salary' => 20.00, 'is_active' => true],
                ['level_number' => 2, 'name' => 'Level 2', 'required_directs' => 10, 'min_investment' => 50.00, 'monthly_salary' => 30.00, 'is_active' => true],
                ['level_number' => 3, 'name' => 'Level 3', 'required_directs' => 20, 'min_investment' => 50.00, 'monthly_salary' => 50.00, 'is_active' => true],
                ['level_number' => 4, 'name' => 'Level 4', 'required_directs' => 50, 'min_investment' => 50.00, 'monthly_salary' => 100.00, 'is_active' => true],
                ['level_number' => 5, 'name' => 'Level 5', 'required_directs' => 100, 'min_investment' => 50.00, 'monthly_salary' => 300.00, 'is_active' => true],
            ];
            foreach ($levels as $l) {
                SalaryLevel::create($l);
            }
        }

        $this->plan = Plan::firstOrCreate(
            ['name' => 'Starter'],
            ['min_amount' => 25, 'max_amount' => 100, 'min_roi' => 1.5, 'max_roi' => 3.0, 'status' => 'active']
        );

        $this->leader = User::create([
            'name' => 'Team Leader',
            'username' => 'leader_' . Str::random(5),
            'email' => 'leader_' . Str::random(5) . '@test.com',
            'phone' => '+1' . rand(1000000000, 9999999999),
            'password' => Hash::make('password123'),
            'referral_code' => Str::random(10),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        Wallet::create(['user_id' => $this->leader->id]);
    }

    /**
     * Helper to create direct members with specific active investment amounts.
     */
    protected function createDirects(int $count, float $investmentAmount = 50.00): array
    {
        $directs = [];
        for ($i = 0; $i < $count; $i++) {
            $user = User::create([
                'name' => "Direct Member $i",
                'username' => 'direct_' . Str::random(6),
                'email' => 'direct_' . Str::random(6) . '@test.com',
                'phone' => '+1' . rand(1000000000, 9999999999),
                'password' => Hash::make('password123'),
                'referral_code' => Str::random(10),
                'referred_by' => $this->leader->id,
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
            Wallet::create(['user_id' => $user->id]);

            if ($investmentAmount > 0) {
                Investment::create([
                    'user_id' => $user->id,
                    'plan_id' => $this->plan->id,
                    'amount' => $investmentAmount,
                    'daily_roi_percent' => 2.0,
                    'status' => 'active',
                ]);
            }
            $directs[] = $user;
        }
        return $directs;
    }

    /** Test 1: 0 qualifying directs */
    public function test_zero_qualifying_directs_gives_no_salary()
    {
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(0, $status['qualifying_directs']);
        $this->assertNull($status['qualified_level']);
        $this->assertEquals(0.00, $status['salary_amount']);
        $this->assertFalse($status['is_eligible']);
        $this->assertFalse($status['can_claim']);
    }

    /** Test 2: 4 qualifying directs (just below Level 1 requirement of 5) */
    public function test_four_qualifying_directs_is_not_eligible_for_level_1()
    {
        $this->createDirects(4, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(4, $status['qualifying_directs']);
        $this->assertNull($status['qualified_level']);
        $this->assertFalse($status['is_eligible']);
        $this->assertEquals(1, $status['needed_for_next_level']);
    }

    /** Test 3: Exactly 5 qualifying directs qualifies for Level 1 ($20/mo) */
    public function test_exactly_five_qualifying_directs_qualifies_for_level_1()
    {
        $this->createDirects(5, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(5, $status['qualifying_directs']);
        $this->assertNotNull($status['qualified_level']);
        $this->assertEquals(1, $status['qualified_level']->level_number);
        $this->assertEquals(20.00, $status['salary_amount']);
        $this->assertTrue($status['is_eligible']);
        $this->assertTrue($status['can_claim']);
    }

    /** Test 4: Exactly 10 qualifying directs qualifies for Level 2 ($30/mo, NOT cumulative) */
    public function test_exactly_ten_qualifying_directs_qualifies_for_level_2()
    {
        $this->createDirects(10, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(10, $status['qualifying_directs']);
        $this->assertEquals(2, $status['qualified_level']->level_number);
        $this->assertEquals(30.00, $status['salary_amount']); // Not $20+$30
    }

    /** Test 5: Exactly 20 qualifying directs qualifies for Level 3 ($50/mo) */
    public function test_exactly_twenty_qualifying_directs_qualifies_for_level_3()
    {
        $this->createDirects(20, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(20, $status['qualifying_directs']);
        $this->assertEquals(3, $status['qualified_level']->level_number);
        $this->assertEquals(50.00, $status['salary_amount']);
    }

    /** Test 6: Exactly 50 qualifying directs qualifies for Level 4 ($100/mo) */
    public function test_exactly_fifty_qualifying_directs_qualifies_for_level_4()
    {
        $this->createDirects(50, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(50, $status['qualifying_directs']);
        $this->assertEquals(4, $status['qualified_level']->level_number);
        $this->assertEquals(100.00, $status['salary_amount']);
    }

    /** Test 7: Exactly 100 qualifying directs qualifies for Level 5 ($300/mo) */
    public function test_exactly_one_hundred_qualifying_directs_qualifies_for_level_5()
    {
        $this->createDirects(100, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(100, $status['qualifying_directs']);
        $this->assertEquals(5, $status['qualified_level']->level_number);
        $this->assertEquals(300.00, $status['salary_amount']); // Non-cumulative!
    }

    /** Test 8: More than 100 qualifying directs still maintains Level 5 */
    public function test_more_than_100_qualifying_directs_caps_at_highest_active_level()
    {
        $this->createDirects(120, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(120, $status['qualifying_directs']);
        $this->assertEquals(5, $status['qualified_level']->level_number);
        $this->assertEquals(300.00, $status['salary_amount']);
    }

    /** Test 9: Direct member with less than $50 investment does NOT qualify */
    public function test_direct_with_less_than_50_dollar_does_not_qualify()
    {
        // 5 directs, but all invested only $49.99
        $this->createDirects(5, 49.99);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(0, $status['qualifying_directs']);
        $this->assertNull($status['qualified_level']);
        $this->assertFalse($status['is_eligible']);
    }

    /** Test 10: Direct member with exactly $50 qualifies */
    public function test_direct_with_exactly_50_dollars_qualifies()
    {
        $this->createDirects(5, 50.00);
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(5, $status['qualifying_directs']);
        $this->assertEquals(1, $status['qualified_level']->level_number);
    }

    /** Test 11: Admin changes salary amount dynamically */
    public function test_admin_changes_salary_amount_dynamically()
    {
        $this->createDirects(5, 50.00);
        $level1 = SalaryLevel::where('level_number', 1)->first();
        $level1->monthly_salary = 25.00; // Change from $20 to $25
        $level1->save();

        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(25.00, $status['salary_amount']);
    }

    /** Test 12: Admin changes required directs dynamically */
    public function test_admin_changes_required_directs_dynamically()
    {
        $this->createDirects(5, 50.00);
        $level1 = SalaryLevel::where('level_number', 1)->first();
        $level1->required_directs = 6; // Raise requirement from 5 to 6
        $level1->save();

        $status = $this->salaryService->calculateEligibility($this->leader);
        // User with 5 directs now does not qualify because requirement changed to 6
        $this->assertNull($status['qualified_level']);
        $this->assertFalse($status['is_eligible']);
    }

    /** Test 13: Admin disables a salary level */
    public function test_admin_disables_salary_level()
    {
        $this->createDirects(5, 50.00);
        $level1 = SalaryLevel::where('level_number', 1)->first();
        $level1->is_active = false;
        $level1->save();

        $status = $this->salaryService->calculateEligibility($this->leader);
        // Level 1 disabled, user doesn't qualify for Level 2 (10 directs)
        $this->assertNull($status['qualified_level']);
        $this->assertFalse($status['is_eligible']);
    }

    /** Test 14: User qualification changes dynamically as downline adds investment */
    public function test_user_qualification_updates_when_downline_invests()
    {
        $directs = $this->createDirects(5, 0.00); // 5 directs with no investment
        $status = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(0, $status['qualifying_directs']);

        // Now direct members activate investments >= $50
        foreach ($directs as $d) {
            Investment::create([
                'user_id' => $d->id,
                'plan_id' => $this->plan->id,
                'amount' => 50.00,
                'daily_roi_percent' => 2.0,
                'status' => 'active',
            ]);
        }

        $newStatus = $this->salaryService->calculateEligibility($this->leader);
        $this->assertEquals(5, $newStatus['qualifying_directs']);
        $this->assertEquals(1, $newStatus['qualified_level']->level_number);
    }

    /** Test 15: Salary claim period logic */
    public function test_salary_claim_period_logic()
    {
        $this->createDirects(5, 50.00);
        $period = '2026-09';
        $status = $this->salaryService->calculateEligibility($this->leader, $period);
        $this->assertEquals('2026-09', $status['current_period']);
        $this->assertTrue($status['can_claim']);
    }

    /** Test 16: Duplicate claim protection handles double submission */
    public function test_duplicate_claim_protection_blocks_second_claim_for_same_period()
    {
        $this->createDirects(5, 50.00);
        $period = '2026-09';

        // 1st claim succeeds
        $result1 = $this->salaryService->claimSalary($this->leader, $period);
        $this->assertTrue($result1['success']);
        $this->assertNotNull($result1['claim']);

        // 2nd claim for same period must fail
        $result2 = $this->salaryService->claimSalary($this->leader, $period);
        $this->assertFalse($result2['success']);
        $this->assertStringContainsString('already been claimed', $result2['message']);
    }

    /** Test 17: Transaction record creation on claim */
    public function test_salary_claim_creates_verifiable_transaction()
    {
        $this->createDirects(5, 50.00);
        $period = '2026-09';

        $result = $this->salaryService->claimSalary($this->leader, $period);
        $this->assertTrue($result['success']);

        $tx = Transaction::where('user_id', $this->leader->id)
            ->where('type', 'salary')
            ->first();

        $this->assertNotNull($tx);
        $this->assertEquals(20.00, $tx->amount);
        $this->assertEquals('completed', $tx->status);
    }

    /** Test 18: Wallet balance credited properly */
    public function test_salary_claim_credits_wallet()
    {
        $this->createDirects(5, 50.00);
        $initialBalance = $this->leader->wallet->salary_balance ?? $this->leader->wallet->referral_balance;

        $result = $this->salaryService->claimSalary($this->leader, '2026-09');
        $this->assertTrue($result['success']);

        $this->leader->wallet->refresh();
        $newBalance = $this->leader->wallet->salary_balance ?? $this->leader->wallet->referral_balance;

        $this->assertEquals($initialBalance + 20.00, $newBalance);
    }

    /** Test 19: Non-eligible user cannot claim salary */
    public function test_non_eligible_user_cannot_claim()
    {
        $result = $this->salaryService->claimSalary($this->leader, '2026-09');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('does not currently qualify', $result['message']);
    }

    /** Test 20: Validation on admin salary level create/update */
    public function test_salary_level_validation()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'level_number' => 'not-a-number',
            'required_directs' => -5,
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test_' . Str::random(4),
            'email' => 'admin_test_' . Str::random(4) . '@test.com',
            'phone' => '+1' . rand(1000000000, 9999999999),
            'password' => Hash::make('password123'),
            'referral_code' => Str::random(10),
            'is_admin' => true,
        ]);
        $this->actingAs($admin);

        $controller = new \App\Http\Controllers\AdminController();
        $controller->storeSalaryLevel($request);
    }

    /** Test 21: User Salary Dashboard Route loads with correct eligibility */
    public function test_user_salary_dashboard_route()
    {
        $this->createDirects(5, 50.00);
        $this->actingAs($this->leader);

        $response = $this->get(route('dashboard.salary'));
        $response->assertStatus(200);
        $response->assertViewHas('eligibility');
        $response->assertViewHas('levels');
    }

    /** Test 22: User Salary Claim Route executes atomic claim */
    public function test_user_salary_claim_post_route()
    {
        $this->createDirects(5, 50.00);
        $this->actingAs($this->leader);

        $response = $this->post(route('dashboard.salary.claim'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->leader->wallet->refresh();
        $this->assertEquals(20.00, $this->leader->wallet->salary_balance);
    }

    /** Test 23: Duplicate claim via POST route in same month is blocked */
    public function test_user_salary_claim_duplicate_blocked_via_post()
    {
        $this->createDirects(5, 50.00);
        $this->actingAs($this->leader);

        // First claim
        $this->post(route('dashboard.salary.claim'));

        // Second claim attempt in same period
        $response = $this->post(route('dashboard.salary.claim'));
        $response->assertSessionHasErrors('salary_claim');
    }

    /** Test 24: Withdrawal from salary_balance works and updates wallet */
    public function test_withdrawal_from_salary_balance()
    {
        $this->actingAs($this->leader);
        $this->leader->wallet->salary_balance = 100.00;
        $this->leader->wallet->save();

        $controller = new \App\Http\Controllers\WithdrawalController();
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'amount' => 50.00,
            'wallet_address' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
            'balance_type' => 'salary_balance',
        ]);

        $response = $controller->store($request);
        $this->leader->wallet->refresh();
        $this->assertEquals(50.00, $this->leader->wallet->salary_balance);

        $withdrawal = \App\Models\Withdrawal::where('user_id', $this->leader->id)
            ->where('wallet_type', 'salary_balance')
            ->first();
        $this->assertNotNull($withdrawal);
        $this->assertEquals(50.00, $withdrawal->amount);
    }
}
