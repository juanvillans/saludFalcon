<?php

namespace Database\Seeders;

use App\Models\Parish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parishes = [
            // Municipio Acosta
            ['name' => 'Capadare', 'municipality_id' => 1],
            ['name' => 'La Pastora', 'municipality_id' => 1],
            ['name' => 'Libertador', 'municipality_id' => 1],

            // Municipio Bolívar
            ['name' => 'Aracua', 'municipality_id' => 2],
            ['name' => 'Pecaya', 'municipality_id' => 2],
            ['name' => 'Sucre', 'municipality_id' => 2],

            // Municipio Buchivacoa
            ['name' => 'Bariro', 'municipality_id' => 3],
            ['name' => 'Borojo', 'municipality_id' => 3],
            ['name' => 'Seque', 'municipality_id' => 3],
            ['name' => 'Zazarida', 'municipality_id' => 3],

            // Municipio Cacique Manuare
            ['name' => 'Cacique Manuare', 'municipality_id' => 4],

            // Municipio Carirubana
            ['name' => 'Carirubana', 'municipality_id' => 5],
            ['name' => 'Punta Cardón', 'municipality_id' => 5],
            ['name' => 'Santa Ana', 'municipality_id' => 5],

            // Municipio Colina
            ['name' => 'Acurigua', 'municipality_id' => 6],
            ['name' => 'Guiabacoa', 'municipality_id' => 6],
            ['name' => 'Macoruco', 'municipality_id' => 6],

            // Municipio Dabajuro
            ['name' => 'Dabajuro', 'municipality_id' => 7],


            // Municipio Democracia
            ['name' => 'Agua Clara', 'municipality_id' => 8],
            ['name' => 'Avaria', 'municipality_id' => 8],
            ['name' => 'Bruzual', 'municipality_id' => 8],
            ['name' => 'Piedra Grande', 'municipality_id' => 8],
            ['name' => 'Purureche', 'municipality_id' => 8],
            ['name' => 'Urumaco', 'municipality_id' => 8],

            

            // Municipio Federación
            ['name' => 'Agua Larga', 'municipality_id' => 9],
            ['name' => 'Independencia', 'municipality_id' => 9],
            ['name' => 'Maparadi', 'municipality_id' => 9],

            // Municipio Falcón
            ['name' => 'Adicora', 'municipality_id' => 10],
            ['name' => 'Baraived', 'municipality_id' => 10],
            ['name' => 'Buena Vista', 'municipality_id' => 10],
            ['name' => 'Jadacaquiva', 'municipality_id' => 10],
            ['name' => 'Moruy', 'municipality_id' => 10],

            // Municipio Jacura
            ['name' => 'Agua Linda', 'municipality_id' => 11],

            // Municipio Los Taques
            ['name' => 'Los Taques', 'municipality_id' => 12],


            // Municipio Mauroa
            ['name' => 'Casigua', 'municipality_id' => 13],
            ['name' => 'San Félix', 'municipality_id' => 13],

            // Municipio Miranda
            ['name' => 'San Antonio', 'municipality_id' => 14],
            ['name' => 'San Gabriel', 'municipality_id' => 14],
            ['name' => 'Santa Ana', 'municipality_id' => 14],
            ['name' => 'Guzmán Guillermo', 'municipality_id' => 14],
            ['name' => 'Mitare', 'municipality_id' => 14],
            ['name' => 'Sabaneta', 'municipality_id' => 14],    


            // Municipio Monseñor Iturriza
            ['name' => 'Boca de tocuyo', 'municipality_id' => 15],
            ['name' => 'Tocuyo de la costa', 'municipality_id' => 15],


            // Municipio Petit
            ['name' => 'Colina', 'municipality_id' => 16],
            ['name' => 'Curimagua', 'municipality_id' => 16],

            // Municipio Piritu
            ['name' => 'Piritu', 'municipality_id' => 17],

            // Municipio San Francisco
            ['name' => 'San Francisco', 'municipality_id' => 18],

            // Municipio Silva
            ['name' => 'Boca de Aroa', 'municipality_id' => 19],
            ['name' => 'Bolivar', 'municipality_id' => 19],

            // Municipio Unión
            ['name' => 'Unión', 'municipality_id' => 20],

            // Municipio Zamora
            ['name' => 'La Ciénaga', 'municipality_id' => 21],
            ['name' => 'La Soledad', 'municipality_id' => 21],
            ['name' => 'Pueblo Cumadero', 'municipality_id' => 21],
            ['name' => 'Tocopero', 'municipality_id' => 21],
        ];

        foreach ($parishes as $parish) {
            Parish::create($parish);
        }
    }
}
