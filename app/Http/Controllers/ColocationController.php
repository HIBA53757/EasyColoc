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

    public function index($id = null)
    {
        $user = auth()->user();


        $pendingInvitations = Invitation::where('email', $user->email)
                                        ->where('status', 'pending')
                                        ->get();

        $allMemberships = $user->memberships()->with('colocation.memberships.user')->get();
        $active = $allMemberships->whereNull('left_at')->first();
        $history = $allMemberships->whereNotNull('left_at');

        
        if (!$id) {
            if ($active) {
                return redirect()->route('Colocation', ['id' => $active->colocation_id]);
            }
            

            return view('Colocation', [
                'colocation' => null,
                'history' => $history,
                'pendingInvitations' => $pendingInvitations,
                'activeColocId' => null
            ]);
        }

      
        $currentMembership = $allMemberships->where('colocation_id', $id)->first();

        if (!$currentMembership) {
            abort(403, "Vous ne faites pas partie de cette colocation.");
        }

        return view('Colocation', [
            'colocation' => $currentMembership->colocation,
            'history' => $history,
            'pendingInvitations' => $pendingInvitations,
            'activeColocId' => $active ? $active->colocation_id : null
        ]);
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
    if ($invitedUser && $invitedUser->hasActiveMembership()) {
        return back()->with('error', "Cet utilisateur a déjà une colocation active.");
    }

    $invitation = Invitation::updateOrCreate(
        ['email' => $request->email, 'colocation_id' => $colocation->id],
        ['token' => Str::random(64), 'status' => 'pending']
    );

    Mail::to($request->email)->send(new ColocationInvitationMail($invitation));

    return back()->with('success', "Invitation envoyée avec succès !");
}

    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        $user = auth()->user();

        if ($user->email !== $invitation->email) {
            return redirect()->route('Colocation')->with('error', "Ce lien ne vous est pas destiné.");
        }

        if ($user->hasActiveMembership()) {
            return redirect()->route('Colocation')->with('error', "Vous avez déjà une colocation active.");
        }

        $user->memberships()->create([
            'colocation_id' => $invitation->colocation_id,
            'role' => 'member',
        ]);

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('Colocation', ['id' => $invitation->colocation_id])
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

    $colocation->memberships()->delete();
    $colocation->delete();

    return redirect()->route('Colocation')->with('success', "La colocation a été définitivement supprimée.");
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
        $membership->update(['left_at' => now()]);
        return redirect()->route('Colocation')->with('success', "Vous avez quitté la colocation.");
    }

    return back()->with('error', "Impossible de quitter cette colocation.");
}
    
}