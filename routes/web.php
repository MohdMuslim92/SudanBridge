<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');;

Route::get('/dashboard', function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        // Check the user's role ID
        if (Auth::user()->role_id !== 1) {
            // Redirect users with role ID other than 1 to another route
            return redirect()->route('user.dashboard');
        }
    } else {
        // If the user is not authenticated, redirect them to the login page
        return redirect()->route('login');
    }

    // If the user's role ID is 1, render the dashboard view
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user/dashboard', function () {
    return Inertia::render('User-Dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');

// Route to display the tracking page
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

// Route to display the About page
Route::get('/About', [AboutController::class, 'index'])->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard/data', [DashboardController::class, 'data']);
Route::post('/dashboard/update/{userId}', [DashboardController::class, 'updateUserRole']);
Route::post('/dashboard/updateFacility/{userId}', [DashboardController::class, 'updateFacility']);

Route::middleware('auth')->group(function () {
    // Routes for managing shipments
    Route::post('/api/shipments', [ShipmentsController::class, 'store']);
    Route::put('/api/shipments/{id}', [ShipmentsController::class, 'update']);
    Route::delete('/api/shipments/{id}', [ShipmentsController::class, 'destroy']);

    // Route to update shipment status
    Route::post('/api/shipments/{token}', [ShipmentsController::class, 'updateStatus']);

});

// Route to fetch shipments
Route::get('/api/shipments', [ShipmentsController::class, 'index']);
// Define a route to fetch shipment details by token
Route::get('/api/shipments/{token}', [ShipmentsController::class, 'getShipmentByToken']);

// Route to fetch statuses
Route::get('/api/statuses', [StatusesController::class, 'index']);

// Route to fetch cities
Route::get('/api/cities', [CitiesController::class, 'index']);

// Route for fetching facilities
Route::middleware('auth')->group(function () {
    Route::get('/api/facilities', [FacilityController::class, 'index']);
    Route::get('/api/location', [FacilityController::class, 'getLocation']);
});

require __DIR__.'/auth.php';
