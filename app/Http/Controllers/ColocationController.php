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
 
public function index()
{
    $membership = auth()->user()
        ->memberships()
        ->whereNull('left_at')
        ->with('colocation')
        ->first();

   
    return view('Colocation', [
        'colocation' => $membership ? $membership->colocation : null
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








   
}
