<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is the owner of a specific colocation.
     * Required by ColocationPolicy.
     */
    public function isOwnerOf($colocationId): bool
    {
        return $this->memberships()
            ->where('colocation_id', $colocationId)
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->exists();
    }

    public function membership($colocationId)
    {
        return $this->memberships()
            ->where('colocation_id', $colocationId)
            ->whereNull('left_at')
            ->first();
    }

    public function hasActiveMembership(): bool
    {
        return $this->memberships()
            ->whereNull('left_at')
            ->exists();
    }

    // Relations

   public function memberships()
{
    return $this->hasMany(Membership::class);
}

public function colocations()
{
    return $this->belongsToMany(Colocation::class, 'memberships')
                ->withPivot('role', 'left_at')
                ->withTimestamps()
                ->using(Membership::class); 
}
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_global';
    }

    public function isMemberOf($colocationId): bool
{
    return $this->memberships()
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->exists();
}
}