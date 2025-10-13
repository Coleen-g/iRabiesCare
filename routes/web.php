<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\VaccinationController;

// Redirect root to the login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login page (named so auth middleware can redirect to route('login'))
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Auth routes (JSON responses)
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// Example admin-only route
Route::get('/admin-only', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'admin') {
        return response()->json(['message' => 'Forbidden'], 403);
    }
    return response()->json(['message' => 'Welcome, admin', 'user' => $user]);
})->middleware('auth');

// Admin dashboard (browser view)
Route::get('/admin/dashboard', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'admin') {
        abort(403, 'Forbidden');
    }
    return view('admin.dashboard');
})->middleware('auth');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('patients', PatientController::class)->only(['index','create','store','edit','update','destroy']);
    Route::resource('cases', CaseController::class)->parameters(['cases' => 'case'])->only(['index','create','store','edit','update','destroy']);
    Route::resource('vaccinations', VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Reports and settings views remain simple for now
    Route::get('reports', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.reports'); })->name('reports');
    Route::get('settings', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.settings'); })->name('settings');
});
