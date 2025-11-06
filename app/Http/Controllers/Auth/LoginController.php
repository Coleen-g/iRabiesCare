<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email'],
            'password' => ['required'],
        ]);

        // Build credentials array: only use username (matching 'name' column)
        if (!empty($data['username'])) {
            $credentials = ['name' => $data['username'], 'password' => $data['password']];
        } else {
            \Log::info('Login attempt', [
                'credentials' => [],
                'request_ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Username required'], 422);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Log successful login and role for debugging
            \Log::info('Login successful', ['user_id' => $user->id, 'role' => $user->role]);

            // Ensure the user has a Patient record linked. Create a minimal record if missing.
            if (!$user->patient) {
                $patient = \App\Models\Patient::create([
                    'name' => $user->name,
                    'contact' => $user->email,
                    'user_id' => $user->id,
                ]);
            }

            // If the client expects JSON, return JSON. Otherwise redirect browser users.
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                ], 200);
            }

            // Browser flow: redirect by role
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role === 'health_staff') {
                return redirect('/health_staff/dashboard');
            }

            return redirect('/user/dashboard');
        }

        \Log::warning('Login failed', [
            'credentials' => $credentials,
            'request_ip' => $request->ip(),
        ]);

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Logged out']);
        }

        return redirect()->route('login');
    }
}