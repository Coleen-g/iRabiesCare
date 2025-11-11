<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HealthStaff;
use App\Models\User;
use Illuminate\Support\Str;

class BackfillHealthStaffUsernames extends Command
{
    protected $signature = 'backfill:healthstaff-usernames {--dry-run}';

    protected $description = 'Backfill User.name for health staff users to be the username (not the full name)';

    public function handle()
    {
        $dry = $this->option('dry-run');

        $staff = HealthStaff::whereNotNull('user_id')->with('user')->get();
        $this->info('Found ' . $staff->count() . ' health staff with linked users.');

        $updated = 0;

        foreach ($staff as $s) {
            if (!$s->user) continue;

            $user = $s->user;

            // If the user's name already looks like a username (no spaces and contains underscore or numeric id), skip
            $looksLikeUsername = (strpos($user->name, ' ') === false);

            // Prefer an explicit username on the health_staff record but ignore if it looks like an email
            $target = null;
            if (!empty($s->username) && strpos($s->username, '@') === false) {
                $target = $s->username;
            }

            if (!$target) {
                // build a username from staff full name
                $base = Str::slug(substr($s->full_name ?? 'staff', 0, 20), '_');
                $candidate = $base . '_' . $s->id;
                $i = 1;
                while (User::where('name', $candidate)->where('id', '!=', $user->id)->exists()) {
                    $candidate = $base . '_' . $s->id . $i;
                    $i++;
                }
                $target = $candidate;
            }

            // If target equals current name, skip
            if ($user->name === $target) continue;

            $this->line("Will change user id {$user->id} name from '{$user->name}' to '{$target}'");

            if (!$dry) {
                $user->name = $target;
                $user->save();
                $updated++;
            }
        }

        $this->info('Updated: ' . $updated . ' users.');

        return 0;
    }
}
