<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\FacilityController;

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
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user/dashboard', function () {
    return Inertia::render('User-Dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');

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
    Route::get('/api/shipments', [ShipmentsController::class, 'index']);
    Route::post('/api/shipments', [ShipmentsController::class, 'store']);
    Route::put('/api/shipments/{id}', [ShipmentsController::class, 'update']);
    Route::delete('/api/shipments/{id}', [ShipmentsController::class, 'destroy']);
});

// Route for fetching facilities
Route::middleware('auth')->group(function () {
    Route::get('/api/facilities', [FacilityController::class, 'index']);
});

require __DIR__.'/auth.php';
