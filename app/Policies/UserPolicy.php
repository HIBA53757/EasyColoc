<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

 public function ban(User $user): bool
    {
        return $user->role === 'admin_global';
    }

    public function delete(User $user): bool
    {
        return $user->role === 'admin_global';
    }

    public function view(User $user): bool
    {
        return true;
    }
}


