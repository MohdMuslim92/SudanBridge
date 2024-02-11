<?php

namespace App\Http\Controllers;

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

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
