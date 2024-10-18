<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment::create([
            'service_id' => 1,
            'name' => "Otman",
            'last_name' => "Rodriguez",
            'ci' => "14027736",
            'phone' => "04125861379",
            'email' => "otmanrodriguez@gmail.com",
            'other_fields' => json_encode(['fotocopia del esternon a color' => 'aqui esta']),
            'start' => '8:00',
            'end' => '9:00',
            'date' => Carbon::now()->addWeek()->startOfWeek()->toISOString(),
            'carbon_date' => Carbon::now()->addWeek()->startOfWeek(),
            'status' => 'OPEN',
        ]);

        Appointment::create([
            'service_id' => 2,
            'name' => "Juan",
            'last_name' => "Diaz",
            'ci' => "30847627",
            'phone' => "04125800610",
            'email' => "juancho070902@gmail.com",
            'other_fields' => json_encode(['como esta la bola?' => 'medio girada']),
            'start' => '8:00',
            'end' => '9:00',
            'date' => Carbon::now()->addWeek()->startOfWeek()->toISOString(),
            'carbon_date' => Carbon::now()->addWeek()->startOfWeek(),
            'status' => 'OPEN',

        ]);
    }
}
