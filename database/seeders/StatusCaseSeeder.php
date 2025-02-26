<?php

namespace Database\Seeders;

use App\Models\StatusCase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        StatusCase::create([
            'name' => 'Egresado: Alta médica',
        ]);

        StatusCase::create([
            'name' => 'Egresado: Alta contraopinión',
        ]);

        StatusCase::create([
            'name' => 'Transferido',
        ]);

        StatusCase::create([
            'name' => 'Ingresado',
        ]);

        StatusCase::create([
            'name' => 'Fallecido',
        ]);

        StatusCase::create([
            'name' => 'Permanece en',
        ]);
        
    }
}
