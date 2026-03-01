<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Membership;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ColocationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        $reputation = 0; 

        $activeMembership = $user->memberships()
            ->whereNull('left_at')
            ->with('colocation.expenses')
            ->first();
        
        $totalGlobalMonth = 0;
        $myBalance = 0;
        $recentExpenses = collect();

        if ($activeMembership && $activeMembership->colocation) {
            $colocation = $activeMembership->colocation;

            $totalGlobalMonth = $colocation->expenses()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount');

            $balances = ExpenseController::getBalances($colocation);
            $myBalance = $balances[$user->id]['balance'] ?? 0;

            $recentExpenses = $colocation->expenses()
                ->with(['payer'])
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();
        }

        return view('dashboard', compact('totalGlobalMonth', 'myBalance', 'recentExpenses', 'reputation'));
    }

    public function list()
    {
        $user = auth()->user();

        $active = $user->memberships()
            ->whereNull('left_at')
            ->with('colocation.memberships')
            ->first();

        $history = $user->memberships()
            ->whereNotNull('left_at')
            ->with('colocation')
            ->get();

        $canCreate = !$active;

        $pendingInvitations = Invitation::where('email', $user->email)
            ->where('status', 'pending')
            ->with('colocation')
            ->get();

        return view('colocation', compact('active', 'history', 'canCreate', 'pendingInvitations'));
    }

    public function show($id)
    {
        $colocation = Colocation::with(['expenses.payer', 'memberships.user'])->findOrFail($id);

        $userMembership = auth()->user()->memberships()->where('colocation_id', $id)->first();

        $isHistory = ($colocation->status === 'cancelled') || ($userMembership && $userMembership->left_at !== null);
        
        $balances = ExpenseController::getBalances($colocation);

        return view('colocationDetail', compact('colocation', 'balances', 'isHistory'));
    }

    public function create()
    {
        return view('createColocation');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $colocation = Colocation::create([
            'name' => $request->name,
            'owner_id' => auth()->id(),
            'status' => 'active' 
        ]);

        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
        ]);

        return redirect()->route('Colocation')->with('success', 'Colocation créée avec succès');
    }

    public function invite(Request $request, Colocation $colocation)
    {
        $this->authorize('invite', $colocation);
        $request->validate(['email' => 'required|email']);

        $invitedUser = User::where('email', $request->email)->first();
        if ($invitedUser && $invitedUser->memberships()->whereNull('left_at')->exists()) {
            return back()->with('error', "Cet utilisateur a déjà une colocation active.");
        }

        $invitation = Invitation::updateOrCreate(
            ['email' => $request->email, 'colocation_id' => $colocation->id],
            ['token' => Str::random(64), 'status' => 'pending']
        );

        Mail::to($request->email)->send(new ColocationInvitationMail($invitation));

        return back()->with('success', "Invitation envoyer");
    }

    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        $user = auth()->user();

        if ($user->email !== $invitation->email) {
            return redirect()->route('Colocation')->with('error', "Ce lien ne vous est pas destiné.");
        }

        if ($user->memberships()->whereNull('left_at')->exists()) {
            return redirect()->route('Colocation')->with('error', "Vous avez déjà une colocation active.");
        }

        $user->memberships()->create([
            'colocation_id' => $invitation->colocation_id,
            'role' => 'member',
        ]);

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('colocation.show', $invitation->colocation_id)
                         ->with('success', "Bienvenue dans la colocation !");
    }

    public function refuseInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $invitation->update(['status' => 'refused']);
        return redirect()->route('Colocation')->with('success', "Invitation refusée.");
    }

    public function destroy(Colocation $colocation)
    {
        $this->authorize('delete', $colocation);

        $colocation->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        $colocation->memberships()->whereNull('left_at')->update([
            'left_at' => now()
        ]);

        return redirect()->route('Colocation')
            ->with('success', "La colocation annuler");
    }

   public function leave(Colocation $colocation)
{
    $this->authorize('leave', $colocation);
    $user = auth()->user();

    $membership = $user->memberships()
        ->where('colocation_id', $colocation->id)
        ->whereNull('left_at')
        ->first();

    if ($membership) {
      
        $colocation->expenses()
            ->where('user_id', $user->id)
            ->update(['user_id' => $colocation->owner_id]);

        $membership->update(['left_at' => now()]);

        return redirect()->route('Colocation')
            ->with('success', "quiter succed");
    }

    return back()->with('error', "Impossible de quitter cette");
}
}