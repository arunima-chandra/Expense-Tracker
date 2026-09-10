<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'invite_code',
    ];

    /**
     * Get all users belonging to this company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all employee users belonging to this company.
     */
    public function employees()
    {
        return $this->hasMany(User::class)->where('role', 'employee');
    }

    /**
     * Get all admin users belonging to this company.
     */
    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    /**
     * Get all accounts for all users in this company.
     */
    public function accounts()
    {
        return $this->hasManyThrough(Account::class, User::class);
    }

    /**
     * Generate a unique 8-character alphanumeric invite code.
     */
    public static function generateUniqueInviteCode(): string
    {
        do {
            // Generate an uppercase 8-character alphanumeric code
            $code = strtoupper(Str::random(8));
        } while (static::where('invite_code', $code)->exists());

        return $code;
    }

    /**
     * Regenerate and save a new invite code for this company.
     */
    public function regenerateInviteCode(): string
    {
        $newCode = static::generateUniqueInviteCode();
        $this->update(['invite_code' => $newCode]);
        return $newCode;
    }
}
