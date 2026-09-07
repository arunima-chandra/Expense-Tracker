<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_calculates_income_expense_and_balance(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Main Account',
            'type' => 'bank',
        ]);

        $account->transactions()->create([
            'type' => 'income',
            'amount' => 50000,
            'category' => 'Salary',
            'date' => '2026-09-01',
        ]);

        $account->transactions()->create([
            'type' => 'expense',
            'amount' => 15000,
            'category' => 'Rent',
            'date' => '2026-09-05',
        ]);

        $account->transactions()->create([
            'type' => 'expense',
            'amount' => 5000,
            'category' => 'Groceries',
            'date' => '2026-09-10',
        ]);

        // Transaction in another month (August)
        $account->transactions()->create([
            'type' => 'income',
            'amount' => 40000,
            'category' => 'Salary',
            'date' => '2026-08-01',
        ]);

        $response = $this->actingAs($user)->getJson('/api/reports/monthly/2026/9');

        $response->assertOk();
        $response->assertJsonPath('year', 2026);
        $response->assertJsonPath('month', 9);
        $response->assertJsonPath('total_income', 50000);
        $response->assertJsonPath('total_expense', 20000);
        $response->assertJsonPath('net_balance', 30000);
        $response->assertJsonPath('transaction_count', 3);
        $response->assertJsonCount(3, 'category_breakdown');
    }

    public function test_yearly_report_returns_twelve_months_and_totals(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Main Account',
            'type' => 'bank',
        ]);

        // January
        $account->transactions()->create([
            'type' => 'income',
            'amount' => 10000,
            'category' => 'Bonus',
            'date' => '2026-01-15',
        ]);

        // March
        $account->transactions()->create([
            'type' => 'expense',
            'amount' => 2000,
            'category' => 'Shopping',
            'date' => '2026-03-10',
        ]);

        $response = $this->actingAs($user)->getJson('/api/reports/yearly/2026');

        $response->assertOk();
        $response->assertJsonPath('year', 2026);
        $response->assertJsonPath('total_income', 10000);
        $response->assertJsonPath('total_expense', 2000);
        $response->assertJsonPath('net_balance', 8000);
        $response->assertJsonCount(12, 'months');

        // Check January
        $response->assertJsonPath('months.0.month', 1);
        $response->assertJsonPath('months.0.income', 10000);
        $response->assertJsonPath('months.0.expense', 0);
        $response->assertJsonPath('months.0.balance', 10000);

        // Check March
        $response->assertJsonPath('months.2.month', 3);
        $response->assertJsonPath('months.2.income', 0);
        $response->assertJsonPath('months.2.expense', 2000);
        $response->assertJsonPath('months.2.balance', -2000);
    }

    public function test_reports_isolate_authenticated_user_data(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $account1 = Account::create(['user_id' => $user1->id, 'name' => 'U1 Acc', 'type' => 'bank']);
        $account2 = Account::create(['user_id' => $user2->id, 'name' => 'U2 Acc', 'type' => 'bank']);

        $account1->transactions()->create([
            'type' => 'income',
            'amount' => 1000,
            'date' => '2026-09-01',
        ]);

        $account2->transactions()->create([
            'type' => 'income',
            'amount' => 99999,
            'date' => '2026-09-01',
        ]);

        $response = $this->actingAs($user1)->getJson('/api/reports/monthly/2026/9');

        $response->assertOk();
        $response->assertJsonPath('total_income', 1000);
        $response->assertJsonPath('transaction_count', 1);
    }

    public function test_web_monthly_and_yearly_report_pages_render_successfully(): void
    {
        $user = User::factory()->create();
        $account = Account::create(['user_id' => $user->id, 'name' => 'Main Account', 'type' => 'bank']);
        $account->transactions()->create([
            'type' => 'expense',
            'amount' => 500,
            'category' => 'Food',
            'date' => '2026-09-05',
        ]);

        // Monthly web view
        $resMonthly = $this->actingAs($user)->get('/reports/2026/9');
        $resMonthly->assertOk();
        $resMonthly->assertSee('September 2026');
        $resMonthly->assertSee('Spending by Category');

        // Yearly web view
        $resYearly = $this->actingAs($user)->get('/reports/2026');
        $resYearly->assertOk();
        $resYearly->assertSee('2026 Annual Report');
        $resYearly->assertSee('Month-by-Month Breakdown');
    }
}
