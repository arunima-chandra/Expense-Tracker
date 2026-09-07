<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'amount',
        'due_day',
        'due_date',
        'reminder_method',
        'is_recurring',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_day' => 'integer',
            'due_date' => 'date:Y-m-d',
            'is_recurring' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query to items due on a specific date (or today).
     */
    public function scopeDueOn($query, ?Carbon $date = null)
    {
        $date = $date ? $date->copy() : Carbon::today();
        $dayOfMonth = (int) $date->format('j');
        $isLastDayOfMonth = $date->isLastOfMonth();

        return $query->where(function ($q) use ($date, $dayOfMonth, $isLastDayOfMonth) {
            // Recurring monthly items matching day of month
            $q->where(function ($sub) use ($dayOfMonth, $isLastDayOfMonth) {
                $sub->where('is_recurring', true);
                if ($isLastDayOfMonth) {
                    $sub->where('due_day', '>=', $dayOfMonth);
                } else {
                    $sub->where('due_day', $dayOfMonth);
                }
            })
            // One-time specific date items
            ->orWhere(function ($sub) use ($date) {
                $sub->where('is_recurring', false)
                    ->whereDate('due_date', $date->toDateString());
            });
        });
    }

    /**
     * Scope query to items due today.
     */
    public function scopeDueToday($query)
    {
        return $this->scopeDueOn($query, Carbon::today());
    }

    /**
     * Scope query to items configured for notification reminders.
     */
    public function scopeForNotification($query)
    {
        return $query->whereIn('reminder_method', ['notification', 'both']);
    }

    /**
     * Scope query to items configured for alarm reminders.
     */
    public function scopeForAlarm($query)
    {
        return $query->whereIn('reminder_method', ['alarm', 'both']);
    }
}
