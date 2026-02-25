<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ColocationController extends Controller
{ use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function create(){
        $this->authorize('create', Colocation::class);

    return view('colocations.create');
    }
}
