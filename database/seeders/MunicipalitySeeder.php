<?php

namespace Database\Seeders;

use App\Models\Municipality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $municipalities = [
            ['name' => 'Acosta'],
            ['name' => 'Bolívar'],
            ['name' => 'Buchivacoa'],
            ['name' => 'Cacique Manaure'],
            ['name' => 'Carirubana'],
            ['name' => 'Colina'],
            ['name' => 'Dabajuro'],
            ['name' => 'Democracia'],
            ['name' => 'Federación'],
            ['name' => 'Falcón'],
            ['name' => 'Jacura'],
            ['name' => 'Los Taques'],
            ['name' => 'Mauroa'],
            ['name' => 'Miranda'],
            ['name' => 'Monseñor Iturriza'],
            ['name' => 'Petit'],
            ['name' => 'Píritu'],
            ['name' => 'San Francisco'],
            ['name' => 'Silva'],
            ['name' => 'Union'],
            ['name' => 'Zamora'],
        ];

        foreach ($municipalities as $municipality) {
            Municipality::create($municipality);
        }
    }
}
