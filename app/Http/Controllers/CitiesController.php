<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CitiesController extends Controller
{
    public function index()
    {
        try {
            // Fetch cities from the database
            $cities = City::all();

            // Return cities as JSON response
            return response()->json($cities);
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['error' => 'Failed to fetch cities.'], 500);
        }
    }

}
