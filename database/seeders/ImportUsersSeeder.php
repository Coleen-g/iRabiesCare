<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ImportUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Edit the $users array below with the accounts you want to import.
        // If a password looks hashed (starts with $2y$ or $argon2), it will be stored as-is.
        // Otherwise the password will be hashed with Hash::make().
        // Example entries:
        // ['name' => 'jdoe', 'email' => 'jdoe@example.com', 'password' => 'plainpassword', 'role' => 'user']
        // ['name' => 'hasheduser', 'email' => 'h@example.com', 'password' => '$2y$10$...hashed...', 'role' => 'user']

        $users = [
            // Pre-filled users for local testing
            ['name' => 'admin', 'email' => 'admin@irabies.local', 'password' => 'secret123', 'role' => 'admin'],
            ['name' => 'johndoe', 'email' => 'user@irabies.local', 'password' => 'secret123', 'role' => 'user'],
        ];

        foreach ($users as $u) {
            if (empty($u['email']) || empty($u['name']) || empty($u['password'])) {
                continue;
            }

            $password = $u['password'];

            // Detect common hashed formats and preserve them
            $isHashed = str_starts_with($password, '$2y$') || str_starts_with($password, '$argon2');

            // Prepare password value to store (either hashed or preserved)
            $passwordToStore = $isHashed ? $password : Hash::make($password);

            $attrs = [
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => $u['role'] ?? 'user',
                'password' => $passwordToStore,
            ];

            // Create or update the user including the password so INSERT won't fail when password is non-nullable
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                $attrs
            );

            $this->command->info('Imported user: ' . $user->email . ' (role: ' . $user->role . ')');
        }
        $this->command->info('ImportUsersSeeder finished.');

        // Create a sample patient, case, and vaccination for the normal user so the user dashboard shows data.
        $normal = User::where('email', 'user@irabies.local')->first();
        if ($normal) {
            $patient = \App\Models\Patient::firstOrCreate(
                ['user_id' => $normal->id],
                [
                    'name' => 'John Doe',
                    'dob' => '1990-01-01',
                    'gender' => 'male',
                    'contact' => '09171234567',
                    'address' => '123 Main St',
                ]
            );

            // Create a sample case
            \App\Models\CaseModel::firstOrCreate([
                'patient_id' => $patient->id,
                'date_reported' => now()->toDateString(),
                'description' => 'Sample rabies exposure case',
            ], [
                'status' => 'open',
                'reported_by' => $normal->id,
            ]);

            // Create a sample vaccination
            \App\Models\Vaccination::firstOrCreate([
                'patient_id' => $patient->id,
                'date_given' => now()->toDateString(),
                'vaccine' => 'Rabies Vaccine',
            ], [
                'dose' => '1st',
                'administered_by' => 'Clinic Nurse',
                'notes' => 'Sample dose',
            ]);

            $this->command->info('Created sample patient/case/vaccination for user@irabies.local');
        }
    }
}
