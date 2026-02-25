<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class ExpensePolicy
{

    public function create(User $user, $colocation): bool
    {
        return $user->membership($colocation->id) !== null;
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->isOwnerOf($expense->colocation_id);
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->membership($expense->colocation_id) !== null;
    }
}
