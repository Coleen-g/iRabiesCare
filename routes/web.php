<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\VaccinationController;
use App\Http\Controllers\UserController;

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

// Registration complete (shows patient id)
Route::get('/register/complete/{patient}', function (App\Models\Patient $patient) {
    return view('auth.register-complete', ['patient' => $patient]);
})->name('register.complete');

// Auth routes (JSON responses)
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

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
    // AJAX endpoint for patient autocomplete
    Route::get('patients/search', [\App\Http\Controllers\Admin\PatientController::class, 'search'])->name('patients.search');
    Route::post('patients/{patient}/generate', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'generateForPatient'])->name('patients.generate');
    Route::post('patients/{patient}/regenerate', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'regeneratePassword'])->name('patients.regenerate');
    Route::resource('cases', CaseController::class)->parameters(['cases' => 'case'])->only(['index','create','store','edit','update','destroy']);
    Route::resource('vaccinations', VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Admin UI for generating user accounts for patients
    Route::get('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'preview'])->name('generate-users.preview');
    Route::post('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'generate'])->name('generate-users');
    Route::get('generate-users/download', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'downloadCsv'])->name('generate-users.download');

    // Reports and settings views remain simple for now
    Route::get('reports', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.reports'); })->name('reports');
    Route::get('settings', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.settings'); })->name('settings');
});

// User routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('cases', [UserController::class, 'cases'])->name('cases');
    Route::get('vaccinations', [UserController::class, 'vaccinations'])->name('vaccinations');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    // User case submission
    Route::get('cases/create', [\App\Http\Controllers\UserCaseController::class, 'create'])->name('cases.create');
    Route::post('cases', [\App\Http\Controllers\UserCaseController::class, 'store'])->name('cases.store');
});
