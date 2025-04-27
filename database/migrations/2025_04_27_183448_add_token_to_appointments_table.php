<?php

use App\Models\Appointment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('token', 36)->nullable()->after('id');
        });

        Appointment::chunkById(200, function ($appointments) {
            foreach ($appointments as $appointment) {
                DB::table('appointments')
                    ->where('id', $appointment->id)
                    ->update(['token' => Str::uuid()->toString()]);
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('token', 36)->nullable(false)->unique()->change();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['token']);
            $table->dropColumn('token');
        });
    }
};