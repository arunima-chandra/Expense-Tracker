<?php

namespace App\Notifications;

use App\Models\ImportantDate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DueDateReminderNotification extends Notification
{
    use Queueable;

    public ImportantDate $importantDate;

    public function __construct(ImportantDate $importantDate)
    {
        $this->importantDate = $importantDate;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $amountText = $this->importantDate->amount !== null
            ? ' (₹' . number_format((float) $this->importantDate->amount, 2) . ')'
            : '';

        return [
            'important_date_id' => $this->importantDate->id,
            'title' => $this->importantDate->title,
            'type' => $this->importantDate->type,
            'amount' => $this->importantDate->amount,
            'reminder_method' => $this->importantDate->reminder_method,
            'is_recurring' => $this->importantDate->is_recurring,
            'due_day' => $this->importantDate->due_day,
            'due_date' => $this->importantDate->due_date?->toDateString(),
            'message' => "Reminder: {$this->importantDate->title}{$amountText} is due today.",
        ];
    }
}
