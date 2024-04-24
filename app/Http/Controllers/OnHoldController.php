<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OnHoldController extends Controller
{
    /**
     * Display the on-hold page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('on-hold');
    }

    /**
     * Get the current user's status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStatus()
    {
        // Retrieve the user's status ID
        $userStatusId = Auth::user()->user_status_id;

        // Return the user's status ID as a JSON response
        return response()->json(['user_status_id' => $userStatusId]);
    }
}
