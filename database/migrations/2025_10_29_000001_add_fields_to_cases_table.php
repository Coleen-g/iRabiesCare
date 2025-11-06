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
        // Add new columns to the cases table if they don't already exist
        Schema::table('cases', function (Blueprint $table) {
            if (!Schema::hasColumn('cases', 'exposure_date')) {
                $table->date('exposure_date')->nullable()->after('date_reported');
            }
            if (!Schema::hasColumn('cases', 'exposure_type')) {
                $table->string('exposure_type')->nullable()->after('exposure_date');
            }
            if (!Schema::hasColumn('cases', 'wounds_location')) {
                $table->string('wounds_location')->nullable()->after('exposure_type');
            }
            if (!Schema::hasColumn('cases', 'category')) {
                $table->string('category')->nullable()->after('wounds_location');
            }
            if (!Schema::hasColumn('cases', 'animal_species')) {
                $table->string('animal_species')->nullable()->after('category');
            }
            if (!Schema::hasColumn('cases', 'animal_status')) {
                $table->string('animal_status')->nullable()->after('animal_species');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (Schema::hasColumn('cases', 'animal_status')) {
                $table->dropColumn('animal_status');
            }
            if (Schema::hasColumn('cases', 'animal_species')) {
                $table->dropColumn('animal_species');
            }
            if (Schema::hasColumn('cases', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('cases', 'wounds_location')) {
                $table->dropColumn('wounds_location');
            }
            if (Schema::hasColumn('cases', 'exposure_type')) {
                $table->dropColumn('exposure_type');
            }
            if (Schema::hasColumn('cases', 'exposure_date')) {
                $table->dropColumn('exposure_date');
            }
        });
    }
};
