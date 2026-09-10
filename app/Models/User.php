<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mode',
        'role',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function importantDates()
    {
        return $this->hasMany(ImportantDate::class);
    }

    /**
     * Check if user is operating in Personal mode.
     */
    public function isPersonal(): bool
    {
        return $this->mode === 'personal' || empty($this->mode);
    }

    /**
     * Check if user is in Company mode.
     */
    public function isCompany(): bool
    {
        return $this->mode === 'company';
    }

    /**
     * Check if user is an Admin of their company.
     */
    public function isCompanyAdmin(): bool
    {
        return $this->isCompany() && $this->role === 'admin';
    }

    /**
     * Check if user is an Employee of their company.
     */
    public function isCompanyEmployee(): bool
    {
        return $this->isCompany() && $this->role === 'employee';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}