<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class ForgotPasswordController extends Controller
{
    public function sendResetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        // Generate a temporary password
        $temporaryPassword = Str::random(10);

    // Update user's password.
    // The User model casts 'password' => 'hashed' so assign the plain
    // password and let the model hash it. Also store an encrypted copy
    // in plain_password_encrypted for admin/regen features.
    $user->password = $temporaryPassword;
    $user->plain_password_encrypted = Crypt::encryptString($temporaryPassword);
    $user->save();

        // Also mirror username and encrypted password on linked patient record
        try {
            if ($user->patient) {
                $patient = $user->patient;
                $patient->user_username = $user->name;
                $patient->user_password_encrypted = Crypt::encryptString($temporaryPassword);
                $patient->save();
            }
        } catch (\Throwable $e) {
            // Log but do not fail the password reset if patient update fails
            \Log::warning('Failed to update patient credentials mirror: ' . $e->getMessage(), ['user_id' => $user->id]);
        }

        // Send email
        Mail::to($user->email)->send(new ForgotPasswordMail($user, $temporaryPassword));

        return back()->with('success', 'Temporary password has been sent to your email.');
    }
}
