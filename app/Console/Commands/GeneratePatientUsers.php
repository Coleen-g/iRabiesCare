<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class GeneratePatientUsers extends Command
{
    protected $signature = 'generate:patient-users {--out=storage/patient_new_users.csv} {--role=user}';

    protected $description = 'Create user accounts for patients without user_id and export credentials to CSV';

    public function handle()
    {
        $out = $this->option('out');
        $role = $this->option('role');

        $patients = Patient::whereNull('user_id')->get();

        if ($patients->isEmpty()) {
            $this->info('No patients without user accounts found.');
            return 0;
        }

        $rows = [];
        $this->info('Creating user accounts for ' . $patients->count() . ' patients...');

        foreach ($patients as $patient) {
            // Generate a username: use the patient's name normalized + patient id
            $base = Str::slug(substr($patient->name ?? 'patient', 0, 20), '_');
            $username = $base . '_' . $patient->id;

            // Ensure uniqueness
            $unique = $username;
            $i = 1;
            while (User::where('name', $unique)->exists()) {
                $unique = $username . $i;
                $i++;
            }

            $password = Str::random(12);

            // Ensure email is non-null because users.email is non-nullable in the schema.
            // Use the patient's email when available; otherwise generate a placeholder
            // using the username so the DB constraint is satisfied.
            $email = $patient->email;
            if (empty($email)) {
                $candidate = $unique . '@generated.local';
                $candidateIndex = 1;
                while (User::where('email', $candidate)->exists()) {
                    $candidate = $unique . '+' . $candidateIndex . '@generated.local';
                    $candidateIndex++;
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

            // Link patient to user
            $patient->user_id = $user->id;
            $patient->save();

            $rows[] = [
                'patient_id' => $patient->id,
                'name' => $patient->name,
                'username' => $unique,
                'password' => $password,
                'email' => $user->email,
                'user_id' => $user->id,
            ];

            $this->line("Created user {$unique} for patient {$patient->id}");
        }

        // Write CSV
        $fp = fopen($out, 'w');
        fputcsv($fp, ['patient_id', 'name', 'username', 'password', 'email', 'user_id']);
        foreach ($rows as $r) {
            fputcsv($fp, $r);
        }
        fclose($fp);

        $this->info('Wrote credentials to: ' . $out);

        return 0;
    }
}
