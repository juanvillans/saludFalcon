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
            ['name' => 'Inconcluso']
        );

        PatientCondition::create(
            ['name' => 'Estable']
        );

        PatientCondition::create(
            ['name' => 'Inestable']
        );

        PatientCondition::create(
            ['name' => 'Crítico']
        );
    }
}
