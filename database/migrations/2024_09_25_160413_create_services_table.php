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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('specialty_id');
            $table->string('title',50);
            $table->integer('duration_per_appointment');
            $table->json('duration_options');
            $table->json('availability');
            $table->json('adjusted_availability');
            $table->json('programming_slot');
            $table->json('booked_appointment_settings');
            $table->string('description',255);
            $table->json('fields');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
