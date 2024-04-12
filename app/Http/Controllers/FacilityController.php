<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    /**
     * Display a listing of the facilities.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Fetch facilities from the database
            $facilities = Facility::all();

            // Return facilities as JSON response
            return response()->json($facilities);
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['error' => 'Failed to fetch facilities.'], 500);
        }
    }

    public function getLocation(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            // Get the user's facility ID
            $facilityId = $user->facility_id;

            // Fetch the facility model based on the facility ID
            $facility = Facility::find($facilityId);

            // Check if the facility exists
            if ($facility) {
                // Return the facility location
                return response()->json(['facility_location' => $facility->location]);
            } else {
                // Facility not found, return an error
                return response()->json(['error' => 'Facility not found'], 404);
            }
        } else {
            // User is not authenticated, return an error
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $facility->update(['name' => $request->name]);
        $facility->update(['location' => $request->location]);

        return redirect()->back()->with('success', 'Facility updated successfully.');
    }

    public function updateFacility(Request $request, $userId)
    {
        // Get the current user ID
        $updatedBy = $request->user()->id;

        // Retrieve the created user with its related facility and role
        $user = User::with('role', 'facility')->findOrFail($userId);

        // Convert the user and its related data to JSON
        $oldData = $user->toJson();
        $user->update(['facility_id' => $request->facility_id]);

        // Refresh the user to get the latest data from the database
        $user->refresh();

        // Convert the user and its related data to JSON
        $newData = $user->toJson();

        // Create log
        $user->createLog($updatedBy, null, 'None', 'user-update', $oldData, $newData);

        return response()->json(['message' => 'Facility updated successfully']);


    }


    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('roles.index')->with('success', 'Facility deleted successfully.');
    }

}
