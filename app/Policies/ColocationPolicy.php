<?php

namespace App\Policies;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ColocationPolicy
{



 public function create(User $user): bool
{
    return !$user->hasActiveMembership();
}
   public function update(User $user, Colocation $colocation): bool
    {
        return $user->isOwnerOf($colocation->id);
    }

    public function delete(User $user, Colocation $colocation): bool
    {
        return $user->isOwnerOf($colocation->id);
    }

  public function invite(User $user, Colocation $colocation): bool
{
    $membership = $user->membership($colocation->id);
    
    return $membership && 
           $membership->role === 'owner' && 
           $membership->left_at === null;
}

    public function leave(User $user, Colocation $colocation): bool
    {return $user->id !== $colocation->owner_id && 
           $user->memberships()
                ->where('colocation_id', $colocation->id)
                ->whereNull('left_at')
                ->exists(); return $user->id !== $colocation->owner_id && $user->isMemberOf($colocation->id);
    } 
}
