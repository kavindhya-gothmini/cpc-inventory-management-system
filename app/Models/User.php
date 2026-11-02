<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'epf_number',
        'name',
        'email',
        'phone',
        'role',
        'profile_picture',
        'password',
        'employee_id',
        'branch_id',
        'is_locked',
        'failed_login_attempts',
        'locked_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_locked' => 'boolean',
            'failed_login_attempts' => 'integer',
            'locked_at' => 'datetime',
        ];
    }

    /**
     * The employee linked to this login account.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'id'
        );
    }

    /**
     * The branch assigned directly to this user account.
     *
     * Used for location-specific administrator access.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class,
            'branch_id',
            'id'
        );
    }

    /**
     * Determine whether the account is locked.
     */
    public function isLocked(): bool
    {
        return $this->is_locked === true;
    }

    /**
     * Lock the account.
     */
    public function lockAccount(): void
    {
        $this->update([
            'is_locked' => true,
            'locked_at' => now(),
        ]);
    }

    /**
     * Unlock the account and reset failed login attempts.
     */
    public function unlockAccount(): void
    {
        $this->update([
            'is_locked' => false,
            'failed_login_attempts' => 0,
            'locked_at' => null,
        ]);
    }
}