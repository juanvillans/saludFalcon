<?php

namespace Database\Seeders;

use App\Services\EmergencyCaseService;
use DB;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([

            PatientConditionSeeder::class,
            StatusCaseSeeder::class,
            MunicipalitySeeder::class,
            ParishSeeder::class,
            RoleSeeder::class,
            SpecialtySeeder::class,
            UserSeeder::class,
            DivisionSeeder::class,
            AreaSeeder::class,
            EmergencyCaseSeeder::class,
        
        ]);
    }

    
}