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
            ['name' => 'Estable',
             'description' => 'Síntomas o condiciones que pueden esperar o manejarse sin urgencia.'
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
