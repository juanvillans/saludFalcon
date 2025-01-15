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
        Schema::create('request_users', function (Blueprint $table) {
            $table->id();
            $table->string('ci');
            $table->string('name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->foreignId('specialty_id');
            $table->string('search');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_users');
    }
};
