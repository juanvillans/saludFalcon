<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specialties')->insert([

            ["name" => "Medicina General" , "status" => true],
            ["name" => "Pediatría" , "status" => true],
            ["name" => "Ginecología y Obstetricia" , "status" => false],
            ["name" => "Cardiología" , "status" => false],
            ["name" => "Dermatología" , "status" => false],
            ["name" => "Endocrinología" , "status" => false],
            ["name" => "Gastroenterología" , "status" => false],
            ["name" => "Neumología" , "status" => false],
            ["name" => "Neurología" , "status" => false],
            ["name" => "Oncología" , "status" => false],
            ["name" => "Oftalmología" , "status" => false],
            ["name" => "Otorrinolaringología" , "status" => false],
            ["name" => "Psiquiatría" , "status" => false],
            ["name" => "Traumatología y Ortopedia" , "status" => false],
            ["name" => "Urología" , "status" => false],
            ["name" => "Reumatología" , "status" => false],
            ["name" => "Infectología" , "status" => false],
            ["name" => "Medicina Interna" , "status" => false],
            ["name" => "Anestesiología" , "status" => false],
            ["name" => "Cirugía General" , "status" => false],
            ["name" => "Cirugía Plástica" , "status" => false],
            ["name" => "Cirugía Cardiovascular" , "status" => false],
            ["name" => "Cirugía Torácica" , "status" => false],
            ["name" => "Radiología" , "status" => false],
            ["name" => "Patología" , "status" => false],
            ["name" => "Medicina de Emergencias" , "status" => false],
            ["name" => "Medicina Familiar" , "status" => false],
            ["name" => "Cuidados Paliativos" , "status" => false],
            ["name" => "Salud Mental" , "status" => false],
            ["name" => "Fisioterapia y Rehabilitación" , "status" => false],
            ["name" => "Nutrición y Dietética" , "status" => false],
            ["name" => "Medicina del Deporte" , "status" => false],
            ["name" => "Medicina Estética" , "status" => false],
            ["name" => "Terapia Ocupacional" , "status" => false],
            ["name" => "Genética Médica" , "status" => false],
            ["name" => "Medicina del Trabajo" , "status" => false]
        ]);
    }
}
