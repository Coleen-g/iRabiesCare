<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('patients', 'age')) {
                $table->integer('age');
            }
            if (!Schema::hasColumn('patients', 'gender')) {
                $table->string('gender');
            }
            if (!Schema::hasColumn('patients', 'address')) {
                $table->string('address');
            }
            if (!Schema::hasColumn('patients', 'contact_number')) {
                $table->string('contact_number')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $columns = ['name', 'age', 'gender', 'address', 'contact_number'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('patients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
