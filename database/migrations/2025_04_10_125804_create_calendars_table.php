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
        Schema::create('calendars', function (Blueprint $table) {
           
            $table->id();

            $table->unsignedBigInteger('specialty_id');
            $table->foreign('specialty_id')
            ->references('id')
            ->on('specialties')
            ->onDelete('cascade');

            $table->string('description');
            $table->integer('status')->default(1); // 1 for Available, 0 for Not 
            $table->integer('duration_per_appointment');
            $table->json('duration_options');
            $table->json('availability');
            $table->json('adjusted_availability');
            $table->json('programming_slot');
            $table->json('booked_appointment_settings');
            $table->json('fields');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
