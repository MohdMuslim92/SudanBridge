<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    public function index()
    {
        try {
            // Fetch statuses from the database
            $statuses = Status::all();

            // Return statuses as JSON response
            return response()->json($statuses);
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['error' => 'Failed to fetch statuses.'], 500);
        }
    }

}
