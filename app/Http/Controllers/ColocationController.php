<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Models\Membership;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ColocationController extends Controller
{ use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
 
public function index($id = null)
{
    $user = auth()->user();

    $allMemberships = $user->memberships()->with('colocation.memberships.user')->get();
    
    $active = $allMemberships->whereNull('left_at')->first();
    $history = $allMemberships->whereNotNull('left_at');

    if (!$id) {
        if ($active) {
            return redirect()->route('Colocation', ['id' => $active->colocation_id]);
        }
    
        if ($history->isEmpty()) {
            return view('Colocation', ['colocation' => null, 'history' => collect()]);
        }
    }

    $currentMembership = $allMemberships->where('colocation_id', $id)->first();

    if (!$currentMembership) {
        abort(403, "Vous ne faites pas partie de cette colocation.");
    }

    return view('Colocation', [
        'colocation' => $currentMembership->colocation,
        'history' => $history,
        'activeColocId' => $active ? $active->colocation_id : null
    ]);
}


    public function create()
    {
        $this->authorize('create', Colocation::class);

        return view('createColocation');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Colocation::class);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // create colo
        $colocation = Colocation::create([
            'name' => $request->name,
                'owner_id' => auth()->id(),
        ]);

        //crete membership as owner
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
        ]);

        return redirect()->route('Colocation')
            ->with('success', 'Colocation créée avec succès');
    }

    public function invite(Request $request, Colocation $colocation)
{
    $this->authorize('invite', $colocation);

    $request->validate([
        'email' => 'required|email'
    ]);

    
    return back()->with('success', "Invitation envoyée à {$request->email} !");
}








   
}
