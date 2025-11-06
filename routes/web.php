<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\VaccinationController;
use App\Http\Controllers\UserController;

// Show welcome page at root
Route::get('/', function () {
    return view('welcome');
});

// Login page (named so auth middleware can redirect to route('login'))
Route::get('/login', function () {
    return view('auth.login-form');
})->name('login');

// Register page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Forgot password: show form
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Send reset link and redirect to a "check your email" confirmation page
Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    // Attempt to send the reset link. For security we will always redirect
    // to the confirmation page so the response doesn't reveal whether the
    // email exists in the system. Preserve the provided email in the session
    // so the check-email page can prefill the resend form.
    $status = \Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );

    // Redirect to a friendly page that instructs the user to check their mail.
    return redirect()->route('password.check_email')
                     ->with('status', __($status))
                     ->withInput($request->only('email'));
})->middleware('throttle:3,1')->name('password.email');

// Check-your-email confirmation page
Route::get('/forgot-password/sent', function () {
    return view('auth.check-email');
})->name('password.check_email');

// Show reset form (accepts optional ?email=... from the reset link so the form can prefill the email)
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request()->query('email'),
    ]);
})->name('password.reset');

// Handle reset
Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = $password;
            $user->save();
        }
    );

    if ($status == \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
        return redirect()->route('login')->with('status', __($status));
    }

    return back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

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

    // Gather counts for dashboard
    // Exclude any patients that are actually admin/health_staff users (keeps dashboard aligned with admin listing)
    $patientsCount = \App\Models\Patient::whereDoesntHave('user', function($q){
        $q->whereIn('role', ['admin','health_staff']);
    })->count();
    $casesCount = \App\Models\CaseModel::count();
    $vaccinationsCount = \App\Models\Vaccination::count();

    // Today's stats: registrations (patients created today), cases (date_reported), vaccinations (date_given)
    $today = now()->toDateString();
    $todayRegistrations = \App\Models\Patient::whereDoesntHave('user', function($q){
        $q->whereIn('role', ['admin','health_staff']);
    })->whereDate('created_at', $today)->count();
    // Strict: only count cases where date_reported matches today (no fallback)
    $todayCases = \App\Models\CaseModel::whereDate('date_reported', $today)->count();
    // Strict: only count vaccinations where date_given matches today (no fallback)
    $todayVaccinations = \App\Models\Vaccination::whereDate('date_given', $today)->count();

    return view('admin.dashboard', compact(
        'patientsCount',
        'casesCount',
        'vaccinationsCount',
        'todayRegistrations',
        'todayCases',
        'todayVaccinations'
    ));
})->middleware('auth');

// Health staff dashboard
Route::get('/health/dashboard', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'health_staff') {
        abort(403, 'Forbidden');
    }

    return view('health_staff.dashboard');
})->middleware('auth');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('patients', PatientController::class)->only(['index','show','create','store','edit','update','destroy']);
    // AJAX endpoint for patient autocomplete


    Route::get('patients/search', [\App\Http\Controllers\Admin\PatientController::class, 'search'])->name('patients.search');
    Route::post('patients/{patient}/generate', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'generateForPatient'])->name('patients.generate');
    Route::post('patients/{patient}/regenerate', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'regeneratePassword'])->name('patients.regenerate');
    Route::resource('cases', CaseController::class)->parameters(['cases' => 'case'])->only(['index','create','store','edit','update','destroy']);
    Route::resource('vaccinations', VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Admin messaging (send messages to users or health staff)
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)->only(['index','create','store','show']);

    // Admin UI for generating user accounts for patients
    Route::get('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'preview'])->name('generate-users.preview');
    Route::post('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'generate'])->name('generate-users');
    Route::get('generate-users/download', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'downloadCsv'])->name('generate-users.download');

    // Reports and settings views remain simple for now
    Route::get('reports', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.reports'); })->name('reports');
    Route::get('settings', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.settings'); })->name('settings');

    // Vaccination schedule management (admin)
    Route::get('users/{user}/vaccination-schedule', [\App\Http\Controllers\Admin\VaccinationScheduleController::class, 'edit'])->name('vaccination-schedule.edit');
    Route::put('users/{user}/vaccination-schedule', [\App\Http\Controllers\Admin\VaccinationScheduleController::class, 'update'])->name('vaccination-schedule.update');
});

// Health staff routes (mirror admin routes but scoped to health_staff)
Route::middleware(['auth'])->prefix('health_staff')->name('health_staff.')->group(function () {
    // Dashboard route (scoped to assigned patients and staff happenings)
    Route::get('dashboard', function () {
        $user = Auth::user();
        if (!$user || $user->role !== 'health_staff') {
            abort(403, 'Forbidden');
        }

        $userId = $user->id;

        // Count of patients assigned to this health staff
        $myPatientsCount = \App\Models\Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();

        // Cases related to patients assigned to this staff
        $activeCasesCount = \App\Models\CaseModel::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->where('status', 'Active')->count();

        // Vaccinations given to patients assigned to this staff
        $completedVaccinationsCount = \App\Models\Vaccination::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();

        $today = now()->toDateString();

        // Today's appointments: not implemented as a model; reuse 0 for clarity
        $todayAppointments = 0;

        // Today's vaccinations given to assigned patients
        $todayVaccinations = \App\Models\Vaccination::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereDate('date_given', $today)->count();

        // Today's new cases for assigned patients (date_reported)
        $todayNewCases = \App\Models\CaseModel::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereDate('date_reported', $today)->count();

        // Recent cases for the assigned patients (limit 5)
        $recentCases = \App\Models\CaseModel::with('patient')
            ->whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })->latest()->limit(5)->get();

        return view('health_staff.dashboard', compact(
            'myPatientsCount',
            'activeCasesCount',
            'completedVaccinationsCount',
            'todayAppointments',
            'todayVaccinations',
            'todayNewCases',
            'recentCases'
        ));
    })->name('dashboard');

    // Independent health_staff controllers and routes
    Route::resource('patients', \App\Http\Controllers\HealthStaff\PatientController::class)->only(['index','show','create','store','edit','update','destroy']);
    Route::get('patients/search', [\App\Http\Controllers\HealthStaff\PatientController::class, 'search'])->name('patients.search');

    Route::resource('cases', \App\Http\Controllers\HealthStaff\CaseController::class)->parameters(['cases' => 'case'])->only(['index','show','create','store','edit','update','destroy']);
    Route::resource('vaccinations', \App\Http\Controllers\HealthStaff\VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Notifications for health staff (list + mark-as-read)
    Route::get('notifications', [\App\Http\Controllers\HealthStaff\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\HealthStaff\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Messages (health staff inbox) - reuse User\MessageController for listing and viewing
    Route::get('messages', [\App\Http\Controllers\User\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [\App\Http\Controllers\User\MessageController::class, 'show'])->name('messages.show');

    // Vaccination schedule management (health_staff) - allow staff to edit schedules for patients they manage
    Route::get('users/{user}/vaccination-schedule', [\App\Http\Controllers\HealthStaff\VaccinationScheduleController::class, 'edit'])->name('vaccination-schedule.edit');
    Route::put('users/{user}/vaccination-schedule', [\App\Http\Controllers\HealthStaff\VaccinationScheduleController::class, 'update'])->name('vaccination-schedule.update');
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

    // User notifications
    Route::get('notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // User messages (inbox)
    Route::get('messages', [\App\Http\Controllers\User\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [\App\Http\Controllers\User\MessageController::class, 'show'])->name('messages.show');
});
