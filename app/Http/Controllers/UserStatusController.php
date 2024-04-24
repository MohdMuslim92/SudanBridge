<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserStatusController extends Controller
{
    public function update(Request $request, $userId)
    {
        // Validate the request data as needed

        // Find the user by ID
        $user = User::findOrFail($userId);

        // Update the user status (e.g., set it to 'pending')
        $user->status = 'pending'; // Adjust as needed based on your application logic

        // Save the updated user
        $user->save();

        // Optionally, you can add logging or other actions here

        // Return a response indicating success or failure
        return response()->json(['message' => 'User status updated successfully']);
    }
}
