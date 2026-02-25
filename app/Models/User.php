<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
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

//relations:

public function memberships()
{
    return $this->hasMany(Membership::class);
}

public function expenses()
{
    return $this->hasMany(Expense::class,'payer_id');
}

public function reputationLogs()
{
    return $this->hasMany(ReputationLog::class);
}

public function isAdmin(): bool
{
    return $this->role === 'admin_global';
}

}
