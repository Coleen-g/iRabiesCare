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

// AJAX endpoint: check if patient already exists (used by multi-step registration form)
Route::post('/register/check-exists', [RegisterController::class, 'checkExists']);

// Forgot password: show form
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Send temporary password via email (overrides default reset-link behavior)
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetPassword'])
    ->middleware('throttle:3,1')
    ->name('password.email');

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
})->middleware('auth')->name('admin.check');

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
})->middleware('auth')->name('admin.dashboard');

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
    // Health staff account generator (create/regenerate linked user accounts)
    Route::post('health-staffs/{health_staff}/generate', [\App\Http\Controllers\Admin\HealthStaffController::class, 'generateForStaff'])->name('health-staffs.generate');
    Route::post('health-staffs/{health_staff}/regenerate', [\App\Http\Controllers\Admin\HealthStaffController::class, 'regeneratePassword'])->name('health-staffs.regenerate');
    Route::resource('cases', CaseController::class)->parameters(['cases' => 'case'])->only(['index','create','store','edit','update','destroy']);
    Route::resource('vaccinations', VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Admin messaging (send messages to users or health staff)
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)->only(['index','create','store','show']);

    // Admin UI for generating user accounts for patients
    Route::get('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'preview'])->name('generate-users.preview');
    Route::post('generate-users', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'generate'])->name('generate-users');
    Route::get('generate-users/download', [\App\Http\Controllers\Admin\UserGeneratorController::class, 'downloadCsv'])->name('generate-users.download');





    // Reports page (lightweight view-only route)
    Route::get('reports', function () {
        $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403);
        // For now return the view without running heavy aggregations here.
        // Move aggregation logic into Admin\ReportsController later.
        return view('admin.reports');
    })->name('reports');
    Route::get('settings', function () { $user = Auth::user(); if (!$user || $user->role !== 'admin') abort(403); return view('admin.settings'); })->name('settings');

    // Profile management for admin (simple UI-based handlers)
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('profile.password');
    Route::put('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'passwordUpdate'])->name('profile.password.update');

    // Admin notifications - allow admin to view notifications (e.g., those sent by health staff)
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('notifications/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark_read');

    // Vaccination schedule management (admin)
    Route::get('users/{user}/vaccination-schedule', [\App\Http\Controllers\Admin\VaccinationScheduleController::class, 'edit'])->name('vaccination-schedule.edit');
    Route::put('users/{user}/vaccination-schedule', [\App\Http\Controllers\Admin\VaccinationScheduleController::class, 'update'])->name('vaccination-schedule.update');
    Route::post('admin/vaccination-schedules/updates', [\App\Http\Controllers\Admin\VaccinationScheduleController::class, 'updates'])->name('admin.vaccination-schedules.updates');

    // Health staff management (admin)
    Route::resource('health-staffs', \App\Http\Controllers\Admin\HealthStaffController::class)->parameters(['health-staffs' => 'health_staff']);
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
        // Match statuses used in the case form (open / in-progress) as active states
        $activeCasesCount = \App\Models\CaseModel::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereIn('status', ['open', 'in-progress'])->count();

        // Vaccinations given to patients assigned to this staff
        $completedVaccinationsCount = \App\Models\Vaccination::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();

        $today = now()->toDateString();

        // Today's appointments: not implemented as a model; keep 0 for now
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

    // JSON stats endpoint for dashboard cards (used by AJAX polling)
    Route::get('dashboard/stats', function () {
        $user = Auth::user();
        if (!$user || $user->role !== 'health_staff') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $userId = $user->id;

        $myPatientsCount = \App\Models\Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();

        // Active case states: align with form values
        $activeCasesCount = \App\Models\CaseModel::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereIn('status', ['open', 'in-progress'])->count();

        $completedVaccinationsCount = \App\Models\Vaccination::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();

        // Today's activity
        $today = now()->toDateString();
        $todayAppointments = 0; // placeholder
        $todayVaccinations = \App\Models\Vaccination::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereDate('date_given', $today)->count();

        $todayNewCases = \App\Models\CaseModel::whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->whereDate('date_reported', $today)->count();

        return response()->json([
            'myPatientsCount' => $myPatientsCount,
            'activeCasesCount' => $activeCasesCount,
            'completedVaccinationsCount' => $completedVaccinationsCount,
            'todayAppointments' => $todayAppointments,
            'todayVaccinations' => $todayVaccinations,
            'todayNewCases' => $todayNewCases,
        ]);
    })->name('dashboard.stats');

    // Independent health_staff controllers and routes
    Route::resource('patients', \App\Http\Controllers\HealthStaff\PatientController::class)->only(['index','show','create','store','edit','update','destroy']);
    Route::get('patients/search', [\App\Http\Controllers\HealthStaff\PatientController::class, 'search'])->name('patients.search');

    Route::resource('cases', \App\Http\Controllers\HealthStaff\CaseController::class)->parameters(['cases' => 'case'])->only(['index','show','create','store','edit','update','destroy']);
    Route::resource('vaccinations', \App\Http\Controllers\HealthStaff\VaccinationController::class)->parameters(['vaccinations' => 'vaccination'])->only(['index','create','store','edit','update','destroy']);

    // Notifications for health staff (list + mark-as-read)
    Route::get('notifications', [\App\Http\Controllers\HealthStaff\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\HealthStaff\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Vaccination schedule management (health_staff) - allow staff to edit schedules for patients they manage
    Route::get('users/{user}/vaccination-schedule', [\App\Http\Controllers\HealthStaff\VaccinationScheduleController::class, 'edit'])->name('vaccination-schedule.edit');
    Route::put('users/{user}/vaccination-schedule', [\App\Http\Controllers\HealthStaff\VaccinationScheduleController::class, 'update'])->name('vaccination-schedule.update');

    // Health staff messaging: allow staff to send messages to admin and users, and view inbox
    Route::get('messages', [\App\Http\Controllers\HealthStaff\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create', [\App\Http\Controllers\HealthStaff\MessageController::class, 'create'])->name('messages.create');
    Route::post('messages', [\App\Http\Controllers\HealthStaff\MessageController::class, 'store'])->name('messages.store');
    Route::get('messages/{message}', [\App\Http\Controllers\HealthStaff\MessageController::class, 'show'])->name('messages.show');
});

// User routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('cases', [UserController::class, 'cases'])->name('cases');
    Route::get('vaccinations', [UserController::class, 'vaccinations'])->name('vaccinations');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [UserController::class, 'update'])->name('profile.update');


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
