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
        Schema::create('emergency_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('user_id');
            $table->foreignId('area_id');
            $table->date('entry_date');
            $table->string('entry_hour');
            $table->string('current_status');
            $table->date('departure_date')->nullable();
            $table->string('departure_hour')->nullable();
            $table->text('reason')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_cases');
    }
};
