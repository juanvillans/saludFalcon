<?php

use App\Enums\StatusCaseEnum;
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

            $table->foreignId('current_patient_condition_id')
                  ->nullable()
                  ->constrained('patient_conditions')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');


            $table->date('entry_date');
            $table->string('entry_hour');

            $table->unsignedBigInteger('current_status_case')->default(StatusCaseEnum::INGRESADO->value);
            $table->foreign('current_status_case')
                ->references('id')
                ->on('status_cases')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->string('destiny')->nullable();

            $table->date('departure_date')->nullable();
            $table->string('departure_hour')->nullable();
            $table->text('reason')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->integer('bed_number');

            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->foreign('last_message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade')
                ->onUpdate('cascade');


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
