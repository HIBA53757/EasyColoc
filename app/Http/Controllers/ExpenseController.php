<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

      
        $colocation->expenses()->create([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'payer_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Dépense ajoutée et soldes mis à jour !');
    }

   
    public static function getBalances(Colocation $colocation)
    {
        $balances = [];
        $members = $colocation->memberships;

      
        foreach ($members as $membership) {
            $balances[$membership->user_id] = [
                'user' => $membership->user,
                'balance' => 0,
                'is_left' => $membership->left_at !== null
            ];
        }

 
        foreach ($colocation->expenses as $expense) {
            $amount = $expense->amount;

            $activeMembersAtThatTime = $colocation->memberships; 
            $count = $activeMembersAtThatTime->count();

            if ($count > 0) {
                $share = $amount / $count;

                foreach ($activeMembersAtThatTime as $m) {
                    if (isset($balances[$m->user_id])) {
                        $balances[$m->user_id]['balance'] -= $share;
                    }
                }

                if (isset($balances[$expense->payer_id])) {
                    $balances[$expense->payer_id]['balance'] += $amount;
                }
            }
        }

        return $balances;
    }
}