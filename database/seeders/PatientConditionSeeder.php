<?php

namespace Database\Seeders;

use App\Models\PatientCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatientCondition::create(
            ['name' => 'Leve',
             'description' => 'Síntomas o condiciones que pueden esperar o manejarse sin urgencia.'
            ]
        );

        PatientCondition::create(
            [
                'name' => 'Moderado',
                'description' => 'Condición que necesita atención pronta pero no es una amenaza inmediata para la vida.'    
            ]
        );

        PatientCondition::create(
            [
                'name' => 'Crítico',
                'description' => 'Situación de vida o muerte que requiere atención inmediata.'
            ]
        );
    }
}
