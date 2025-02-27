<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("ci",30)->unique();
            $table->string("name",50);
            $table->string("last_name",50);
            $table->string("email",100)->unique();
            $table->string("password",100);
            $table->string("phone_number",30);
            $table->foreignId('specialty_id');
            $table->string('medical_license',50)->unique();
            $table->string('search');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
