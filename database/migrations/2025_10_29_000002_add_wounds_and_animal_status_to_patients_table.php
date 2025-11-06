<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'wounds_location')) {
                $table->string('wounds_location')->nullable()->after('exposure_date');
            }
            if (!Schema::hasColumn('patients', 'animal_status')) {
                $table->string('animal_status')->nullable()->after('wounds_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'animal_status')) {
                $table->dropColumn('animal_status');
            }
            if (Schema::hasColumn('patients', 'wounds_location')) {
                $table->dropColumn('wounds_location');
            }
        });
    }
};
