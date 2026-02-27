<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{

protected $fillable = [
        'email',
        'colocation_id',
        'token',
        'status'
    ];
   public function colocation() {
    return $this->belongsTo(Colocation::class);
}
}
