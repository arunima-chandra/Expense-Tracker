<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogAccountCreation implements ShouldQueue
{
    public function handle(AccountCreated $event): void
    {
        Log::info('Account created', [
            'account_id' => $event->account->id,
            'user_id' => $event->account->user_id,
        ]);
    }
}