<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class HealthStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // idempotent: do not create if username or email already exists
        $username = 'nurse';
        $email = 'nurse@clinic.local';

        $existing = User::where('username', $username)->orWhere('email', $email)->first();
        if ($existing) {
            $this->command->info("Health staff user '{$username}' already exists (id={$existing->id}).");
            return;
        }

        $passwordPlain = 'nurseone';

        $user = User::create([
            'name' => 'Nurse',
            'email' => $email,
            'username' => $username,
            'role' => 'health_staff',
            'password' => $passwordPlain,
            'plain_password_encrypted' => Crypt::encryptString($passwordPlain),
        ]);

        $this->command->info("Created health staff user '{$username}' (id={$user->id}).");
    }
}
