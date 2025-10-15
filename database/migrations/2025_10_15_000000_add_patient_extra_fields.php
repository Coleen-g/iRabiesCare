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
            $table->date('exposure_date')->nullable();
            $table->string('exposure_type')->nullable();
            $table->string('animal')->nullable();
            $table->string('vaccination_status')->nullable();
            $table->date('last_dose_date')->nullable();
            $table->string('clinic')->nullable();
            $table->string('emergency_contact')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'exposure_date',
                'exposure_type',
                'animal',
                'vaccination_status',
                'last_dose_date',
                'clinic',
                'emergency_contact',
            ]);
        });
    }
};
