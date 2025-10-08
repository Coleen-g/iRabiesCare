<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\CaseRecordController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HealthStaffController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PatientDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -------------------
// CSRF Cookie (for SPA)
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

// -------------------
// Authentication
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/me', [AuthenticatedSessionController::class, 'me']);

// -------------------
// Role-based Dashboards
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware(['auth:sanctum', 'role:health_staff'])->group(function () {
    Route::get('/staff/dashboard', [HealthStaffController::class, 'dashboard']);
});

Route::middleware(['auth:sanctum', 'role:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientDashboardController::class, 'index']);
});

// -------------------
// API Resources (optional role-based protection)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('cases', CaseRecordController::class);
    Route::apiResource('vaccinations', VaccinationController::class);
});
