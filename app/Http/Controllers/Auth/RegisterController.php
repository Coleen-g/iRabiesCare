<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // If there are no users yet, make the first registered user an admin.
        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $isFirstUser ? 'admin' : 'user',
        ]);

        Auth::login($user);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
            ], 201);
        }

        // Browser flow
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/');
    }
}
