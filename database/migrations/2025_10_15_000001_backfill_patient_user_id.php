<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill patients.user_id by matching contact -> users.email or name -> users.name
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                // Match by contact (email) first, then by name
                DB::table('patients')
                    ->whereNull('user_id')
                    ->where(function ($q) use ($user) {
                        $q->where('contact', $user->email)
                          ->orWhere('name', $user->name);
                    })
                    ->update(['user_id' => $user->id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Undo: set user_id back to null for patients that reference an existing user
        DB::table('patients')
            ->whereNotNull('user_id')
            ->update(['user_id' => null]);
    }
};
