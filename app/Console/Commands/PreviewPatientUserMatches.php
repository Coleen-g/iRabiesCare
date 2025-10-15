<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PreviewPatientUserMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preview:patient-user-matches {--out=storage/patient_user_matches.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preview candidate matches between patients and users and export to CSV';

    public function handle()
    {
        $out = $this->option('out') ?: storage_path('patient_user_matches.csv');

        $this->info("Scanning users and patients, this may take a moment...");

        $rows = [];

        DB::table('users')->orderBy('id')->chunk(100, function ($users) use (&$rows) {
            foreach ($users as $user) {
                // Exact email match candidates
                $matches = DB::table('patients')
                    ->whereNull('user_id')
                    ->where('contact', $user->email)
                    ->get();

                foreach ($matches as $p) {
                    $rows[] = [
                        'patient_id' => $p->id,
                        'patient_name' => $p->name,
                        'patient_contact' => $p->contact,
                        'match_type' => 'contact_email',
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                    ];
                }

                // Name-based fallback matches
                $matches2 = DB::table('patients')
                    ->whereNull('user_id')
                    ->where('name', $user->name)
                    ->get();

                foreach ($matches2 as $p) {
                    $rows[] = [
                        'patient_id' => $p->id,
                        'patient_name' => $p->name,
                        'patient_contact' => $p->contact,
                        'match_type' => 'name_exact',
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                    ];
                }
            }
        });

        if (empty($rows)) {
            $this->info('No candidate matches found.');
            return 0;
        }

        // Write CSV header
        $fp = fopen(base_path($out), 'w');
        fputcsv($fp, array_keys($rows[0]));
        foreach ($rows as $r) fputcsv($fp, $r);
        fclose($fp);

        $this->info('Wrote ' . count($rows) . " candidate matches to {$out}");
        $this->line('Inspect the CSV and run the backfill migration or apply updates once you confirm.');

        return 0;
    }
}
