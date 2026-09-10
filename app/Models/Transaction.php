<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['account_id', 'type', 'amount', 'category', 'description', 'date'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Scope query to transactions belonging to a specific user through accounts.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('account', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Scope query to transactions belonging to all users of a specific company.
     */
    public function scopeForCompany($query, int $companyId)
    {
        return $query->whereHas('account.user', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });
    }

    /**
     * Scope query to transactions in a given year.
     */
    public function scopeInYear($query, int $year)
    {
        return $query->whereYear('date', $year);
    }

    /**
     * Scope query to transactions in a given year and month.
     */
    public function scopeInMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
