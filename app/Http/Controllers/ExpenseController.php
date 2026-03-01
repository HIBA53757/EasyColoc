<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
   
    public static function getBalances(Colocation $colocation)
    {
        $colocation->load(['memberships.user', 'expenses']);
        
        $totalExpenses = $colocation->expenses->sum('amount');
        $activeMemberships = $colocation->memberships->whereNull('left_at');
        $count = max($activeMemberships->count(), 1);
        
        $sharePerPerson = $totalExpenses / $count;

        return $colocation->memberships->map(function ($membership) use ($sharePerPerson, $colocation) {
            $user = $membership->user;
        
            $paid = $colocation->expenses->where('payer_id', $user->id)->sum('amount');
            
            $isHistory = $membership->left_at !== null;
            $due = $isHistory ? 0 : $sharePerPerson;

            return [
                'user'    => $user,
                'paid'    => $paid,
                'due'     => $due,
                'balance' => $paid - $due,
                'is_left' => $isHistory
            ];
        });
    }

    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'payer_id' => auth()->id(), 
            'colocation_id' => $colocation->id,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Dépense ajoutée et soldes mis à jour !');
    }
}