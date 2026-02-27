<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = ['name', 'description',
    'owner_id',
    'status'];

    //relation:
    public function memberships()
{
    return $this->hasMany(Membership::class);
}
public function users()
{
    return $this->belongsToMany(User::class, 'memberships')
                ->withPivot('role', 'left_at')
                ->withTimestamps()
                ->using(Membership::class);
}

public function expenses()
{
    return $this->hasMany(Expense::class);
}
}
