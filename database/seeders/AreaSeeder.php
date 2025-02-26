<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            'name' => 'Quirófano', 
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => 'Cardiovascular',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => '5to Piso',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => '6to Piso',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => 'Nefrología',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => 'Psiquiatría',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => 'Red de salud',
            'division_id' => 1,            
        ]);

        Area::create([
            'name' => 'UCE',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Sala de Shock',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Pabellón',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Tratamiento',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Sala de Yeso',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Observación',
            'division_id' => 2,            
        ]);

        Area::create([
            'name' => 'Aislamiento',
            'division_id' => 2,            
        ]);
        
    }
}
