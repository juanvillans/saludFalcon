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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string("ci",30)->unique();
            $table->string("name",50);
            $table->string("last_name",50);
            $table->string("phone_number",30)->nullable();
            $table->string('sex')->default('Masculino')->nullable();
            $table->date('date_birth')->nullable();
            $table->foreignId('municipality_id')->nullable();
            $table->foreignId('parish_id')->nullable();
            $table->string('address')->nullable();
            $table->string('search')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
