<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Locality;

class StateController extends Controller
{
    // Method to fetch all states
    public function index()
    {
        $states = State::all();
        return response()->json($states);
    }

    // Method to fetch localities based on state ID
    public function getLocalities(Request $request, State $state)
    {
        $localities = Locality::where('state_id', $state->id)->get();
        return response()->json($localities);
    }
}
