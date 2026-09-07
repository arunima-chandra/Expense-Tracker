<?php

namespace App\Console\Commands;

use App\Models\ImportantDate;
use App\Notifications\DueDateReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDueDateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send {--days=0 : Number of days in advance to check (default 0 for today)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for due ImportantDates and send notifications or trigger alarm reminders';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $advanceDays = (int) $this->option('days');
        $targetDate = Carbon::today()->addDays($advanceDays);

        $this->info("Checking for important dates due on {$targetDate->toDateString()} (in {$advanceDays} day(s))...");

        $dueItems = ImportantDate::with('user')->dueOn($targetDate)->get();

        if ($dueItems->isEmpty()) {
            $this->info('No important dates due for the specified date.');
            return self::SUCCESS;
        }

        $notificationCount = 0;
        $alarmCount = 0;

        foreach ($dueItems as $item) {
            if (!$item->user) {
                continue;
            }

            // Notification handling
            if (in_array($item->reminder_method, ['notification', 'both'], true)) {
                $item->user->notify(new DueDateReminderNotification($item));
                $notificationCount++;

                Log::info('Due date notification sent', [
                    'user_id' => $item->user_id,
                    'important_date_id' => $item->id,
                    'title' => $item->title,
                    'due_date' => $targetDate->toDateString(),
                ]);
            }

            // Alarm handling (logged and available for frontend polling)
            if (in_array($item->reminder_method, ['alarm', 'both'], true)) {
                $alarmCount++;

                Log::info('Due date alarm triggered', [
                    'user_id' => $item->user_id,
                    'important_date_id' => $item->id,
                    'title' => $item->title,
                    'due_date' => $targetDate->toDateString(),
                ]);
            }
        }

        $this->info("Processed {$dueItems->count()} due items: {$notificationCount} notification(s) sent, {$alarmCount} alarm(s) flagged.");

        return self::SUCCESS;
    }
}
