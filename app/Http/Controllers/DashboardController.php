<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Admin-Dashboard');
    }

    public function data()
    {
        $users = User::with('role')->get(); // Get the user using the relationship User and Role models
        $roles = Role::all(); // Fetch all roles from the database
        return response()->json(compact('users', 'roles'));
    }

    public function updateUserRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['role_id' => $request->role_id]);
        return response()->json(['message' => 'User role updated successfully']);
    }}
