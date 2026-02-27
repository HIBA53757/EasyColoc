<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot 
{
    protected $table = 'memberships';
    
    public $incrementing = true; 

    protected $fillable = ['user_id', 'colocation_id', 'role', 'left_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

