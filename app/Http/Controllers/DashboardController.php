<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Role;
use App\Models\UserStatus;

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
        $userStatuses = UserStatus::all(); // Fetch all user statuses from the database
        $facilities = Facility::all(); // Fetch all facilities from the database
        return response()->json(compact('users', 'roles', 'facilities', 'userStatuses'));
    }
}
