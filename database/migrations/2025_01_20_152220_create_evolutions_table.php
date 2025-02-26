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
        Schema::create('evolutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emergency_case_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('patient_condition_id');
            $table->unsignedBigInteger('status_id');
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->string('destiny')->nullable();
            $table->boolean('is_interconsult')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('emergency_case_id')->references('id')->on('emergency_cases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('patient_condition_id')->references('id')->on('patient_conditions')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status_cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolutions');
    }
};
