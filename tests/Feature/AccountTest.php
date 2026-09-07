<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/accounts', [
            'name' => 'Main Checking',
            'type' => 'bank',
        ]);

        $response->assertRedirect('/accounts');

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'name' => 'Main Checking',
            'type' => 'bank',
        ]);
    }

    public function test_account_creation_requires_name_and_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/accounts', []);

        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_user_id_from_request_is_ignored(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->post('/accounts', [
            'name' => 'Sneaky Account',
            'type' => 'cash',
            'user_id' => $user2->id,
        ]);

        $response->assertRedirect('/accounts');

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user1->id,
            'name' => 'Sneaky Account',
            'type' => 'cash',
        ]);

        $this->assertDatabaseMissing('accounts', [
            'user_id' => $user2->id,
            'name' => 'Sneaky Account',
        ]);
    }

    public function test_authenticated_user_can_update_own_account(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Original Name',
            'type' => 'bank',
        ]);

        $response = $this->actingAs($user)->put("/accounts/{$account->id}", [
            'name' => 'Updated Name',
            'type' => 'credit_card',
        ]);

        $response->assertRedirect('/accounts');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Name',
            'type' => 'credit_card',
        ]);
    }

    public function test_user_cannot_update_another_users_account(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $account = Account::create([
            'user_id' => $user1->id,
            'name' => 'User1 Account',
            'type' => 'bank',
        ]);

        $response = $this->actingAs($user2)->put("/accounts/{$account->id}", [
            'name' => 'Hacked Name',
            'type' => 'cash',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'User1 Account',
            'type' => 'bank',
        ]);
    }

    public function test_authenticated_user_can_delete_own_account_and_transactions(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Account To Delete',
            'type' => 'bank',
        ]);

        $transaction = $account->transactions()->create([
            'type' => 'income',
            'amount' => 100,
            'category' => 'Gift',
            'date' => '2026-09-01',
        ]);

        $response = $this->actingAs($user)->delete("/accounts/{$account->id}");

        $response->assertRedirect('/accounts');

        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_user_cannot_delete_another_users_account(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $account = Account::create([
            'user_id' => $user1->id,
            'name' => 'User1 Account',
            'type' => 'bank',
        ]);

        $response = $this->actingAs($user2)->delete("/accounts/{$account->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('accounts', ['id' => $account->id]);
    }

    public function test_accounts_page_renders_edit_and_delete_buttons_and_forms(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Wallet Cash',
            'type' => 'cash',
        ]);

        $response = $this->actingAs($user)->get('/accounts');

        $response->assertOk();
        $response->assertSee('Edit');
        $response->assertSee('Delete');
        $response->assertSee('action="/accounts/' . $account->id . '"', false);
        $response->assertSee('Are you sure? This will also delete all its transactions.');
    }

    public function test_dashboard_page_renders_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Overview');
        $response->assertSee('Account Balance History');
        $response->assertSee('Total Balance Distribution');
    }

    public function test_dashboard_data_endpoint_returns_scoped_accounts_and_running_balances(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $acc1 = Account::create([
            'user_id' => $user1->id,
            'name' => 'City Bank',
            'type' => 'bank',
        ]);

        $acc1->transactions()->create([
            'type' => 'income',
            'amount' => 1000,
            'category' => 'Salary',
            'date' => '2026-09-01',
        ]);

        $acc1->transactions()->create([
            'type' => 'expense',
            'amount' => 200,
            'category' => 'Bills',
            'date' => '2026-09-02',
        ]);

        $acc2 = Account::create([
            'user_id' => $user1->id,
            'name' => 'Savings',
            'type' => 'bank',
        ]);

        $acc2->transactions()->create([
            'type' => 'income',
            'amount' => 500,
            'category' => 'Deposit',
            'date' => '2026-09-01',
        ]);

        // User 2's account (should NOT appear in User 1's data)
        $accOther = Account::create([
            'user_id' => $user2->id,
            'name' => 'Secret Stash',
            'type' => 'cash',
        ]);
        $accOther->transactions()->create([
            'type' => 'income',
            'amount' => 9999,
            'category' => 'Hidden',
            'date' => '2026-09-01',
        ]);

        $response = $this->actingAs($user1)->getJson('/dashboard-data');

        $response->assertOk();
        $response->assertJsonStructure([
            'accounts' => [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'current_balance',
                    'balance_history' => [
                        '*' => ['id', 'date', 'type', 'category', 'amount', 'balance']
                    ],
                    'transactions'
                ]
            ],
            'total_balance'
        ]);

        $data = $response->json();
        $this->assertCount(2, $data['accounts']);
        $this->assertEquals(1300, $data['total_balance']);

        // Check City Bank
        $cityBank = collect($data['accounts'])->firstWhere('id', $acc1->id);
        $this->assertNotNull($cityBank);
        $this->assertEquals(800, $cityBank['current_balance']);
        $this->assertCount(2, $cityBank['balance_history']);
        $this->assertEquals(1000, $cityBank['balance_history'][0]['balance']);
        $this->assertEquals(800, $cityBank['balance_history'][1]['balance']);

        // User 2's account must NOT be present
        $this->assertNull(collect($data['accounts'])->firstWhere('id', $accOther->id));
    }
}
