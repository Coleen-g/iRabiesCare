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

        // Build credentials array: prefer username (matching 'name' column), fall back to email
        if (!empty($data['username'])) {
            $credentials = ['name' => $data['username'], 'password' => $data['password']];
        } elseif (!empty($data['email'])) {
            $credentials = ['email' => $data['email'], 'password' => $data['password']];
        } else {
            return response()->json(['message' => 'Username or email required'], 422);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // If the client expects JSON, return JSON. Otherwise redirect browser users.
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                ], 200);
            }

            // Browser flow: redirect admin to dashboard, others to home (or dashboard for now)
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/');
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
