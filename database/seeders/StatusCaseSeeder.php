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
            'name' => 'Egreso: Alta médica',
        ]);

        StatusCase::create([
            'name' => 'Egreso: Alta contraindicación',
        ]);

        StatusCase::create([
            'name' => 'Admitido',
        ]);

        StatusCase::create([
            'name' => 'Ingreso',
        ]);

        StatusCase::create([
            'name' => 'Defución',
        ]);
        
    }
}
