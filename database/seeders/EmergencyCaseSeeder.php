<?php

namespace Database\Seeders;

use App\Models\EmergencyCase;
use App\Models\Patient;
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
        $doctors = [
            1 => ['name' => 'Juan', 'last_name' => 'Donquis'],
            2 => ['name' => 'Juan', 'last_name' => 'Villasmil'],
        ];

        for ($i = 0; $i < 100; $i++) { 
            
            $patient = Patient::create([
                'ci' => '30847'.$i,
                'name' => $names[rand(0,4)],
                'last_name' => $lastNames[rand(0,4)],
                'phone_number' => "+58412".$i,
                'sex' => $sexs[rand(0,1)],
                'date_birth' => Carbon::createFromDate(2000, 12, 25), 
            ]);

            $doctorID = rand(1,2);

            EmergencyCase::create([
                'patient_id' => $patient->id,
                'cases' => json_encode([
                        [
                            "area" => null,
                            "diagnosis" => "Diarrea",
                            "doctor" => [
                                "id" => $doctorID,
                                "last_name" => $doctors[$doctorID]['last_name'],
                                "name" => $doctors[$doctorID]['name']
                            ],
                            "end_date" => "2024-10-25",
                            "end_time" => "14:40",
                            "start_date" => "2024-10-25",
                            "start_time" => "14:40",
                            "status" => "Alta",
                            "treatment" => "Café con bastante cambul"
                        ]
                ]),
                'current_status' => "Alta",    
            ]);
        }
    }
}


	
	