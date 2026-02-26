<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
    'user_id',
    'colocation_id',
    'role',
    'left_at'
];

}
