<?php

namespace Database\Seeders;

use App\Models\EmergencyCase;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmergencyCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Jose', 'Daniel', 'Juan', 'Marcos', 'Carlos'];
        $lastNames = ['Diaz', 'Rodriguez', 'Villasmil', 'Donquis', 'Tovar'];
        $sexs = ['Masculino', 'Femenino'];
        $doctor = User::where('id',3)->first();

        for ($i = 0; $i < 100; $i++) { 
            
            $name = $names[rand(0,4)];
            $lastName = $lastNames[rand(0,4)];

            $patient = Patient::create([
                'ci' => '30847'.$i,
                'name' => $name,
                'last_name' => $lastName,
                'phone_number' => "+58412".$i,
                'sex' => $sexs[rand(0,1)],
                'date_birth' => Carbon::createFromDate(2000, 12, 25),
                'municipality_id' => 14,
                'parish_id' => 37,
                'address' => 'San José',
                'search' => $name . ' ' . $lastName . ' 30847' . $i , 
            ]);


            EmergencyCase::create([

                'patient_id' => $patient->id,
                'user_id' => $doctor->id,
                'area_id' => 1,
                'entry_date' => Carbon::now(),
                'entry_hour' => Carbon::now()->format('H:i:s'),
                'current_status' => 1,
                'departure_date' => Carbon::now(),
                'departure_hour' => Carbon::now()->format('H:i:s'),
                'reason' => 'Diarrea',
                'diagnosis' => 'Efectivamente tiene diarrea',
                'treatment' => 'Café con bastante cambul',
            ]);
            
        }
    }
}


	
	