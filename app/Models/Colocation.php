<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    //relation:
    public function members()
{
    return $this->hasMany(Membership::class);
}

public function expenses()
{
    return $this->hasMany(Expense::class);
}
}
