<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthStaff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class HealthStaffController extends Controller
{
    protected function authorizeAccess()
    {
        $user = auth()->user();
        if (!$user || (!in_array($user->role, ['admin','health_staff']))) {
            abort(403, 'Forbidden');
        }
    }

    public function index()
    {
        $this->authorizeAccess();
        // Show only records that are meant to represent active health staff
        // and eager load linked user for quick access.
        $staff = HealthStaff::where('role', 'health_staff')
                    ->with('user')
                    ->latest()
                    ->paginate(20);
        return view('admin.health_staffs.index', compact('staff'));
    }

    public function create()
    {
        $this->authorizeAccess();
        return view('admin.health_staffs.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'employment_status' => 'nullable|string|max:100',
            'assigned_facility' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        // Force role to health_staff regardless of input
        $data['role'] = 'health_staff';

        // Optionally link to or create a User account if email or username provided
        $user = null;
        if (!empty($data['email']) || !empty($data['username'])) {
            // Prefer to find by email, then username
            if (!empty($data['email'])) {
                $user = User::where('email', $data['email'])->first();
            }
            if (!$user && !empty($data['username'])) {
                $user = User::where('name', $data['username'])->orWhere('email', $data['username'])->first();
            }

            if (!$user) {
                // Create a new user account for this health staff
                $plain = substr(bin2hex(random_bytes(4)),0,8); // short random password

                // Determine username (user.name) — prefer explicit username, otherwise create from full name
                $baseName = $data['username'] ?? (Str::slug(substr($data['full_name'] ?? 'health_staff', 0, 20), '_'));
                $uniqueName = $baseName;
                $i = 1;
                while (User::where('name', $uniqueName)->exists()) {
                    $uniqueName = $baseName . $i;
                    $i++;
                }

                // Determine email: only use provided email; do NOT use the username as the email
                $emailAddress = $data['email'] ?? null;
                if (empty($emailAddress)) {
                    // generate a safe placeholder email that won't collide
                    $candidate = $uniqueName . '@generated.local';
                    $ci = 1;
                    while (User::where('email', $candidate)->exists()) {
                        $candidate = $uniqueName . '+' . $ci . '@generated.local';
                        $ci++;
                    }
                    $emailAddress = $candidate;
                }

                // Ensure username is not identical to email. If it is, append a suffix.
                if ($uniqueName === $emailAddress) {
                    $uniqueName .= '_u';
                    $j = 1;
                    while (User::where('name', $uniqueName)->exists()) {
                        $uniqueName = $uniqueName . $j;
                        $j++;
                    }
                }

                $user = User::create([
                    'name' => $uniqueName,
                    'email' => $emailAddress,
                    'password' => Hash::make($plain),
                    'role' => 'health_staff',
                    // store encrypted plain password for admin use (uses existing column)
                    'plain_password_encrypted' => Crypt::encryptString($plain),
                ]);
            }

            if ($user) {
                $data['user_id'] = $user->id;
                // Store username in health_staff record if model supports it
                if (isset($data['username']) && !empty($data['username'])) {
                    $data['username'] = $user->name;
                } elseif (isset($health_staff) && isset($health_staff->username)) {
                    // if health_staff has username column but none provided, sync with created user
                    $data['username'] = $user->name;
                }
            }
        }

        HealthStaff::create($data);

        return redirect()->route('admin.health-staffs.index')->with('success', 'Health staff created.');
    }

    public function edit(HealthStaff $health_staff)
    {
        $this->authorizeAccess();
        return view('admin.health_staffs.edit', ['staff' => $health_staff]);
    }

    public function update(Request $request, HealthStaff $health_staff)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'employment_status' => 'nullable|string|max:100',
            'assigned_facility' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        // Force role to health_staff regardless of input
        $data['role'] = 'health_staff';

        // If the staff already has a linked user, update that user's email/name if changed
        if ($health_staff->user) {
            $u = $health_staff->user;
            $updateUser = [];
            if (!empty($data['email']) && $data['email'] !== $u->email) {
                $updateUser['email'] = $data['email'];
            }
            if (!empty($data['full_name']) && $data['full_name'] !== $u->name) {
                $updateUser['name'] = $data['full_name'];
            }
            if (!empty($updateUser)) {
                $u->update($updateUser);
            }
            // ensure user_id stays linked
            $data['user_id'] = $u->id;
        } else {
            // If no linked user but email/username provided, attempt to find or create user
            if (!empty($data['email']) || !empty($data['username'])) {
                $user = null;
                if (!empty($data['email'])) {
                    $user = User::where('email', $data['email'])->first();
                }
                if (!$user && !empty($data['username'])) {
                    $user = User::where('name', $data['username'])->orWhere('email', $data['username'])->first();
                }
                if (!$user) {
                    $plain = substr(bin2hex(random_bytes(4)),0,8);

                    // Create unique username
                    $baseName = $data['username'] ?? (Str::slug(substr($data['full_name'] ?? 'health_staff', 0, 20), '_'));
                    $uniqueName = $baseName;
                    $i = 1;
                    while (User::where('name', $uniqueName)->exists()) {
                        $uniqueName = $baseName . $i;
                        $i++;
                    }

                    // Determine email: use provided email only; do not use username as email
                    $emailAddress = $data['email'] ?? null;
                    if (empty($emailAddress)) {
                        $candidate = $uniqueName . '@gmail.com';
                        $ci = 1;
                        while (User::where('email', $candidate)->exists()) {
                            $candidate = $uniqueName . '+' . $ci . '@gmail.com';
                            $ci++;
                        }
                        $emailAddress = $candidate;
                    }

                    if ($uniqueName === $emailAddress) {
                        $uniqueName .= '_u';
                        $j = 1;
                        while (User::where('name', $uniqueName)->exists()) {
                            $uniqueName = $uniqueName . $j;
                            $j++;
                        }
                    }

                    $user = User::create([
                        'name' => $uniqueName,
                        'email' => $emailAddress,
                        'password' => Hash::make($plain),
                        'role' => 'health_staff',
                        'plain_password_encrypted' => Crypt::encryptString($plain),
                    ]);
                }
                if ($user) $data['user_id'] = $user->id;
            }
        }

        $health_staff->update($data);

        return redirect()->route('admin.health-staffs.index')->with('success', 'Health staff updated.');
    }

    public function show(HealthStaff $health_staff)
    {
        $this->authorizeAccess();
        return view('admin.health_staffs.show', ['staff' => $health_staff]);
    }

    public function destroy(HealthStaff $health_staff)
    {
        $this->authorizeAccess();
        $health_staff->delete();
        return redirect()->route('admin.health-staffs.index')->with('success', 'Health staff deleted.');
    }

    /**
     * Generate a linked User account for a health staff (single staff).
     */
    public function generateForStaff(Request $request, HealthStaff $health_staff)
    {
        $this->authorizeAccess();

        if ($health_staff->user_id) {
            return redirect()->back()->with('success', 'Health staff already has a user account.');
        }

        $base = Str::slug(substr($health_staff->full_name ?? 'staff', 0, 20), '_');
        $username = $base . '_' . $health_staff->id;
        $unique = $username;
        $i = 1;
        while (User::where('name', $unique)->exists()) {
            $unique = $username . $i;
            $i++;
        }

        $password = Str::random(12);

        $email = $health_staff->email;
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
            'role' => 'health_staff',
            'plain_password_encrypted' => Crypt::encryptString($password),
        ]);

        $health_staff->user_id = $user->id;
        // persist username if model has column
        if (isset($health_staff->username)) {
            $health_staff->username = $unique;
        }
        $health_staff->save();

        // Append to CSV for admin handoff
        $out = storage_path('staff_new_users.csv');
        $exists = file_exists($out);
        $fp = fopen($out, 'a');
        if (!$exists) {
            fputcsv($fp, ['staff_id', 'name', 'username', 'password', 'email', 'user_id']);
        }
        fputcsv($fp, [$health_staff->id, $health_staff->full_name, $unique, $password, $email, $user->id]);
        fclose($fp);

        return redirect()->back()->with('success', 'Created user ' . $unique . ' for staff ' . $health_staff->id . '. CSV updated.');
    }

    /**
     * Regenerate password for linked user account of a health staff.
     */
    public function regeneratePassword(Request $request, HealthStaff $health_staff)
    {
        $this->authorizeAccess();

        if (!$health_staff->user) {
            return redirect()->back()->with('success', 'Health staff has no linked user to regenerate.');
        }

        $user = $health_staff->user;
        $password = Str::random(12);

        $user->password = Hash::make($password);
        $user->plain_password_encrypted = Crypt::encryptString($password);
        $user->save();

        // Append to CSV
        $out = storage_path('staff_new_users.csv');
        $exists = file_exists($out);
        $fp = fopen($out, 'a');
        if (!$exists) {
            fputcsv($fp, ['staff_id', 'name', 'username', 'password', 'email', 'user_id']);
        }
        fputcsv($fp, [$health_staff->id, $health_staff->full_name, $user->name, $password, $user->email, $user->id]);
        fclose($fp);

        return redirect()->back()->with('success', 'Regenerated password for user ' . $user->name . '. CSV updated.');
    }
}
