<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Fetch the old user data before updating
        $oldUser = $user->load('role', 'facility');

        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);
        $request->user()->fill($request->validated());

        // Clear email verification if the email has changed
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Fetch the new user data after updating
        $newUser = $user->refresh();

        // Convert the user and its related data to JSON
        $oldData = $oldUser->toJson();
        $newData = $newUser->toJson();

        // Create log
        $user->createLog($user->id, null, 'None', 'user-update', $oldData, $newData);

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Get the user ID
        $userId = $user->id;

        // Fetch the user data before deletion
        $oldUser = $user->load('role', 'facility');

        Auth::logout();

        $user->delete();

        // Convert the user and its related data to JSON
        $oldData = $oldUser->toJson();
        // Since the user is deleted, there's no new data

        // Log the deletion
        $user->createLog(null, null, 'None', 'user-delete', $oldData, $oldData);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
