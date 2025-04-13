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
        Schema::create('appointments', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('calendar_id');
            $table->unsignedBigInteger('patient_id');
            $table->date('day_reserved');
            $table->string('time_reserved');
            $table->json('appointment_data');
            $table->timestamps();

            $table->foreign('calendar_id')
            ->references('id')
            ->on('calendars')
            ->onDelete('cascade');

            $table->foreign('patient_id')
            ->references('id')
            ->on('patients')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
