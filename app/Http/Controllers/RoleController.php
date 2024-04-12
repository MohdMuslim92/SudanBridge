<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Role name updated successfully.');
    }

    public function updateUserRole(Request $request, $userId)
    {
        // Get the current user ID
        $updatedBy = $request->user()->id;

        // Retrieve the created user with its related facility and role
        $user = User::with('role', 'facility')->findOrFail($userId);

        // Convert the user and its related data to JSON
        $oldData = $user->toJson();

        $user->update(['role_id' => $request->role_id]);

        // Refresh the user to get the latest data from the database
        $user->refresh();

        // Convert the user and its related data to JSON
        $newData = $user->toJson();

        // Create log
        $user->createLog($updatedBy, null, 'None', 'user-update', $oldData, $newData);

        return response()->json(['message' => 'User role updated successfully']);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
