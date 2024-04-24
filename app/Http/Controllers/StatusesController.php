<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function updateUserStatus(Request $request, $userId)
    {
        try {
            // Get the current user ID
            $updatedBy = $request->user()->id;

            // Retrieve the created user with its related facility and role
            $user = User::with('role', 'facility', 'userStatus')->findOrFail($userId);

            // Convert the user and its related data to JSON
            $oldData = $user->toJson();

            // Validate the request data
            $request->validate([
                'user_status_id' => 'required|numeric|exists:user_statuses,id',
            ]);

            // Update the user status
            $user->update(['user_status_id' => $request->user_status_id]);

            // Refresh the user to get the latest data from the database
            $user->refresh();

            // Convert the user and its related data to JSON
            $newData = $user->toJson();

            // Create log
            $user->createLog($updatedBy, null, 'None', 'user-update', $oldData, $newData);

            return response()->json(['message' => 'User status updated successfully']);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating user status: ' . $e->getMessage());

            // Return a generic error response
            return response()->json(['error' => 'An error occurred while updating user status. Please try again later.'], 500);
        }
    }

}
