<?php

namespace App\Policies;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ColocationPolicy
{

 public function create(User $user): bool
{
    return true;
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
        return $user->isOwnerOf($colocation->id);
    }

    public function leave(User $user, Colocation $colocation): bool
    {
        return true;
    } 
}
