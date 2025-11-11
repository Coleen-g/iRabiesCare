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
        Schema::create('health_staffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('address')->nullable();

            // Work-related
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('license_number')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('assigned_facility')->nullable();

            // System-related
            $table->string('username')->nullable()->index();
            $table->string('role')->default('health_staff');
            $table->string('status')->default('Active');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_staffs');
    }
};
