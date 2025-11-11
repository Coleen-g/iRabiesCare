<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('vaccinations')) {
            Schema::table('vaccinations', function (Blueprint $table) {
                if (! Schema::hasColumn('vaccinations', 'remarks')) {
                    $table->text('remarks')->nullable()->after('notes');
                }
            });

            // Copy existing notes data into remarks
            try {
                DB::statement('UPDATE vaccinations SET remarks = notes WHERE remarks IS NULL');
            } catch (\Throwable $e) {
                // If the update fails for some reason, log and continue. The new column still exists.
                \Log::error('Failed to copy notes to remarks in vaccinations table: ' . $e->getMessage());
            }

            // Drop the old notes column if present
            Schema::table('vaccinations', function (Blueprint $table) {
                if (Schema::hasColumn('vaccinations', 'notes')) {
                    try {
                        $table->dropColumn('notes');
                    } catch (\Throwable $e) {
                        // Some platforms may require doctrine/dbal to drop columns. If that happens,
                        // leave the notes column in place and log the issue for manual migration.
                        \Log::warning('Could not drop notes column from vaccinations table automatically: ' . $e->getMessage());
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vaccinations')) {
            // Re-create notes column if missing
            Schema::table('vaccinations', function (Blueprint $table) {
                if (! Schema::hasColumn('vaccinations', 'notes')) {
                    $table->text('notes')->nullable()->after('administered_by');
                }
            });

            // Copy remarks back into notes
            try {
                DB::statement('UPDATE vaccinations SET notes = remarks WHERE notes IS NULL');
            } catch (\Throwable $e) {
                \Log::error('Failed to copy remarks to notes in vaccinations table: ' . $e->getMessage());
            }

            // Drop remarks column if present
            Schema::table('vaccinations', function (Blueprint $table) {
                if (Schema::hasColumn('vaccinations', 'remarks')) {
                    try {
                        $table->dropColumn('remarks');
                    } catch (\Throwable $e) {
                        \Log::warning('Could not drop remarks column from vaccinations table automatically: ' . $e->getMessage());
                    }
                }
            });
        }
    }
};
