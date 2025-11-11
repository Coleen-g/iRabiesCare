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
            if (!Schema::hasColumn('patients', 'user_username')) {
                $table->string('user_username')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('patients', 'user_password_encrypted')) {
                $table->text('user_password_encrypted')->nullable()->after('user_username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'user_password_encrypted')) {
                $table->dropColumn('user_password_encrypted');
            }
            if (Schema::hasColumn('patients', 'user_username')) {
                $table->dropColumn('user_username');
            }
        });
    }
};
