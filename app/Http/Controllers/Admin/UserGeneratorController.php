<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserGeneratorController extends Controller
{
    public function preview()
    {
        $patients = Patient::whereNull('user_id')->get();
        return view('admin.generate-users', ['patients' => $patients]);
    }

    public function generate(Request $request)
    {
        $role = $request->input('role', 'user');
        $patients = Patient::whereNull('user_id')->get();
        $results = [];

        foreach ($patients as $patient) {
            $base = Str::slug(substr($patient->name ?? 'patient', 0, 20), '_');
            $username = $base . '_' . $patient->id;
            $unique = $username;
            $i = 1;
            while (User::where('name', $unique)->exists()) {
                $unique = $username . $i;
                $i++;
            }

            $password = Str::random(12);

            $email = $patient->email;
            if (empty($email)) {
                $candidate = $unique . '@generated.local';
                $ci = 1;
                while (User::where('email', $candidate)->exists()) {
                    $candidate = $unique . '+' . $ci . '@generated.local';
                    $ci++;
                }
                $email = $candidate;
            }

            $user = User::create([
                'name' => $unique,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'plain_password_encrypted' => Crypt::encryptString($password),
            ]);

            $patient->user_id = $user->id;
            $patient->save();

            $results[] = [
                'patient_id' => $patient->id,
                'name' => $patient->name,
                'username' => $unique,
                'password' => $password,
                'email' => $email,
                'user_id' => $user->id,
            ];
        }

        return view('admin.generate-users', ['patients' => collect([]), 'results' => $results]);
    }

    public function generateForPatient(Request $request, Patient $patient)
    {
        if ($patient->user_id) {
            return redirect()->back()->with('success', 'Patient already has a user account.');
        }

        $role = $request->input('role', 'user');
        $base = Str::slug(substr($patient->name ?? 'patient', 0, 20), '_');
        $username = $base . '_' . $patient->id;
        $unique = $username;
        $i = 1;
        while (User::where('name', $unique)->exists()) {
            $unique = $username . $i;
            $i++;
        }

        $password = Str::random(12);

        $email = $patient->email;
        if (empty($email)) {
            $candidate = $unique . '@generated.local';
            $ci = 1;
            while (User::where('email', $candidate)->exists()) {
                $candidate = $unique . '+' . $ci . '@generated.local';
                $ci++;
            }
            $email = $candidate;
        }

        $user = User::create([
            'name' => $unique,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $patient->user_id = $user->id;
        $patient->save();

        // Append to CSV
        $out = storage_path('patient_new_users.csv');
        $exists = file_exists($out);
        $fp = fopen($out, 'a');
        if (!$exists) {
            fputcsv($fp, ['patient_id', 'name', 'username', 'password', 'email', 'user_id']);
        }
        fputcsv($fp, [$patient->id, $patient->name, $unique, $password, $email, $user->id]);
        fclose($fp);

        return redirect()->back()->with('success', 'Created user ' . $unique . ' for patient ' . $patient->id . '. CSV updated.');
    }

    public function downloadCsv()
    {
        $path = storage_path('patient_new_users.csv');
        if (!file_exists($path)) {
            return redirect()->back()->with('success', 'No CSV file available.');
        }
        return response()->download($path, 'patient_new_users.csv');
    }

    public function regeneratePassword(Request $request, Patient $patient)
    {
        if (!$patient->user) {
            return redirect()->back()->with('success', 'Patient has no linked user to regenerate.');
        }

        $user = $patient->user;
        $password = Str::random(12);

        $user->password = Hash::make($password);
        $user->plain_password_encrypted = Crypt::encryptString($password);
        $user->save();

        // Append to CSV
        $out = storage_path('patient_new_users.csv');
        $exists = file_exists($out);
        $fp = fopen($out, 'a');
        if (!$exists) {
            fputcsv($fp, ['patient_id', 'name', 'username', 'password', 'email', 'user_id']);
        }
        fputcsv($fp, [$patient->id, $patient->name, $user->name, $password, $user->email, $user->id]);
        fclose($fp);

        return redirect()->back()->with('success', 'Regenerated password for user ' . $user->name . '. CSV updated.');
    }
}
