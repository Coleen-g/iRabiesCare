<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:40',
            'profile_photo' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $data['profile_photo_path'] = '/storage/' . $path;
        }

        $user->fill($data);
        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function password()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);
        // Return the normal Blade view for change-password
        return view('admin.change-password');
    }

    public function passwordUpdate(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!\Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
