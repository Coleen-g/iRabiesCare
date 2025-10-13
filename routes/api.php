<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\CaseRecordController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// CSRF (for SPA)
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

// Auth
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/me', [AuthenticatedSessionController::class, 'me']);

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('cases', CaseRecordController::class);
    Route::apiResource('vaccinations', VaccinationController::class);
});
