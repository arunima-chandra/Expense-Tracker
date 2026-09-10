<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountCreated
{
    use Dispatchable, SerializesModels;

    public Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}