<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default admin (existing)
        $email = 'admin@irabies.local';
        $name = 'admin';
        $password = 'secret123';

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);
        }

        // Add requested admin user Coleen
        $coleenEmail = 'coleen@irabies.local';
        $coleenName = 'Coleen';
        $coleenPassword = 'admincoleen';

        if (!User::where('email', $coleenEmail)->exists()) {
            User::create([
                'name' => $coleenName,
                'email' => $coleenEmail,
                'password' => Hash::make($coleenPassword),
                'role' => 'admin',
            ]);
        }
    }
}
