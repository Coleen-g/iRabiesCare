<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;

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

        // Create a linked patient record for the user. If optional fields were provided use them,
        // otherwise create a minimal patient record with the user's name so the dashboard has data.
        Patient::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $data['name'],
                'contact' => $request->input('contact'),
                'dob' => $request->input('dob'),
                'gender' => $request->input('gender'),
                'address' => $request->input('address'),
            ]
        );

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

        return redirect('/user/dashboard');
    }
}
