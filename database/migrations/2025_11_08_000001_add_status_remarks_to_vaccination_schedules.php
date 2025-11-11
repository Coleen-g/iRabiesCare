<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vaccination_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('vaccination_schedules', 'schedule_1_status')) {
                $table->string('schedule_1_status')->nullable()->default('pending');
            }
            if (! Schema::hasColumn('vaccination_schedules', 'schedule_2_status')) {
                $table->string('schedule_2_status')->nullable()->default('pending');
            }
            if (! Schema::hasColumn('vaccination_schedules', 'schedule_3_status')) {
                $table->string('schedule_3_status')->nullable()->default('pending');
            }

            if (! Schema::hasColumn('vaccination_schedules', 'schedule_1_remarks')) {
                $table->text('schedule_1_remarks')->nullable();
            }
            if (! Schema::hasColumn('vaccination_schedules', 'schedule_2_remarks')) {
                $table->text('schedule_2_remarks')->nullable();
            }
            if (! Schema::hasColumn('vaccination_schedules', 'schedule_3_remarks')) {
                $table->text('schedule_3_remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('vaccination_schedules', function (Blueprint $table) {
            $table->dropColumn(['schedule_1_status','schedule_2_status','schedule_3_status','schedule_1_remarks','schedule_2_remarks','schedule_3_remarks']);
        });
    }
};
