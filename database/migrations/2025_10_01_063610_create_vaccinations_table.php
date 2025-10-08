<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('vaccinations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->string('vaccine_type');
        $table->integer('dose_number');
        $table->date('vaccination_date');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('vaccinations');
}

};
