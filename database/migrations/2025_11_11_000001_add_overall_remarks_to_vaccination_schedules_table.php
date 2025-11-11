<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('vaccination_schedules')) {
            Schema::table('vaccination_schedules', function (Blueprint $table) {
                if (! Schema::hasColumn('vaccination_schedules', 'overall_remarks')) {
                    $table->text('overall_remarks')->nullable()->after('schedule_3_remarks');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('vaccination_schedules')) {
            Schema::table('vaccination_schedules', function (Blueprint $table) {
                if (Schema::hasColumn('vaccination_schedules', 'overall_remarks')) {
                    $table->dropColumn('overall_remarks');
                }
            });
        }
    }
};
