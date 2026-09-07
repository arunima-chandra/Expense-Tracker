<?php

namespace Tests\Feature;

use App\Models\ImportantDate;
use App\Models\User;
use App\Notifications\DueDateReminderNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ImportantDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_recurring_important_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/important-dates', [
            'title' => 'Electricity Bill',
            'type' => 'bill',
            'amount' => 1500.50,
            'is_recurring' => true,
            'due_day' => 15,
            'reminder_method' => 'both',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('title', 'Electricity Bill');
        $response->assertJsonPath('type', 'bill');
        $response->assertJsonPath('due_day', 15);
        $response->assertJsonPath('reminder_method', 'both');

        $this->assertDatabaseHas('important_dates', [
            'user_id' => $user->id,
            'title' => 'Electricity Bill',
            'type' => 'bill',
            'amount' => 1500.50,
            'is_recurring' => true,
            'due_day' => 15,
        ]);
    }

    public function test_authenticated_user_can_create_one_time_important_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/important-dates', [
            'title' => 'Car Insurance',
            'type' => 'other',
            'amount' => 12000,
            'is_recurring' => false,
            'due_date' => '2026-10-25',
            'reminder_method' => 'notification',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('important_dates', [
            'user_id' => $user->id,
            'title' => 'Car Insurance',
            'type' => 'other',
            'is_recurring' => false,
            'due_date' => '2026-10-25',
        ]);
    }

    public function test_validation_fails_for_invalid_recurring_setup(): void
    {
        $user = User::factory()->create();

        // Recurring without due_day
        $response = $this->actingAs($user)->postJson('/api/important-dates', [
            'title' => 'Rent',
            'type' => 'rent',
            'is_recurring' => true,
            'reminder_method' => 'notification',
        ]);

        $response->assertStatus(422);

        // One-time without due_date
        $response2 = $this->actingAs($user)->postJson('/api/important-dates', [
            'title' => 'Event',
            'type' => 'other',
            'is_recurring' => false,
            'reminder_method' => 'notification',
        ]);

        $response2->assertStatus(422);
    }

    public function test_user_cannot_access_or_update_another_users_important_date(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $date1 = ImportantDate::create([
            'user_id' => $user1->id,
            'title' => 'User1 Secret Bill',
            'type' => 'bill',
            'due_day' => 10,
            'is_recurring' => true,
            'reminder_method' => 'notification',
        ]);

        // Attempt access by user2
        $this->actingAs($user2)->getJson("/api/important-dates/{$date1->id}")
            ->assertForbidden();

        // Attempt update by user2
        $this->actingAs($user2)->putJson("/api/important-dates/{$date1->id}", [
            'title' => 'Hacked Title',
        ])->assertForbidden();

        // Attempt delete by user2
        $this->actingAs($user2)->deleteJson("/api/important-dates/{$date1->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('important_dates', [
            'id' => $date1->id,
            'title' => 'User1 Secret Bill',
        ]);
    }

    public function test_alarms_today_endpoint_returns_due_alarms(): void
    {
        $user = User::factory()->create();
        $todayDay = (int) date('j');

        // Due today with alarm
        $alarm1 = ImportantDate::create([
            'user_id' => $user->id,
            'title' => 'Gym Membership Due Today',
            'type' => 'bill',
            'is_recurring' => true,
            'due_day' => $todayDay,
            'reminder_method' => 'alarm',
        ]);

        // Due today but notification only (should NOT appear in alarms endpoint)
        ImportantDate::create([
            'user_id' => $user->id,
            'title' => 'Not an alarm',
            'type' => 'other',
            'is_recurring' => true,
            'due_day' => $todayDay,
            'reminder_method' => 'notification',
        ]);

        $response = $this->actingAs($user)->getJson('/api/important-dates/alarms/today');

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $alarm1->id);
    }

    public function test_send_due_date_reminders_command_sends_notifications(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $todayDay = (int) date('j');

        $item = ImportantDate::create([
            'user_id' => $user->id,
            'title' => 'House EMI',
            'type' => 'emi',
            'amount' => 25000,
            'is_recurring' => true,
            'due_day' => $todayDay,
            'reminder_method' => 'both',
        ]);

        $this->artisan('reminders:send')
            ->expectsOutputToContain('Processed 1 due items: 1 notification(s) sent, 1 alarm(s) flagged.')
            ->assertSuccessful();

        Notification::assertSentTo($user, DueDateReminderNotification::class, function ($notification) use ($item) {
            return $notification->importantDate->id === $item->id;
        });
    }
}
