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

            ["name" => "Adolescentología" , "status" => true],
            ["name" => "Anestesiología" , "status" => true],
            ["name" => "Cardiología" , "status" => true],
            ["name" => "Cirugía Cardiovascular" , "status" => true],
            ["name" => "Cirugía General" , "status" => true],
            ["name" => "Cirugía Plástica" , "status" => true],
            ["name" => "Cirugía Torácica" , "status" => true],
            ["name" => "Cuidados Paliativos" , "status" => true],
            ["name" => "Dermatología" , "status" => true],
            ["name" => "Endocrinología" , "status" => true],
            ["name" => "Fisioterapia y Rehabilitación" , "status" => true],
            ["name" => "Foniatría" , "status" => true],
            ["name" => "Genética Médica" , "status" => true],
            ["name" => "Gastroenterología" , "status" => true],
            ["name" => "Ginecología y Obstetricia" , "status" => true],
            ["name" => "Infectología" , "status" => true],
            ["name" => "Inmunología" , "status" => true],
            ["name" => "Medicina de Emergencias" , "status" => true],
            ["name" => "Medicina Estética" , "status" => true],
            ["name" => "Medicina Familiar" , "status" => true],
            ["name" => "Medicina General" , "status" => true],
            ["name" => "Medicina Interna" , "status" => true],
            ["name" => "Medicina del Deporte" , "status" => true],
            ["name" => "Medicina del Trabajo" , "status" => true],
            ["name" => "Neumología" , "status" => true],
            ["name" => "Neurocirugía" , "status" => true],
            ["name" => "Nefrología" , "status" => true],
            ["name" => "Oftalmología" , "status" => true],
            ["name" => "Oncología" , "status" => true],
            ["name" => "Otorrinolaringología" , "status" => true],
            ["name" => "Patología" , "status" => true],
            ["name" => "Pediatría" , "status" => true],
            ["name" => "Psiquiatría" , "status" => true],
            ["name" => "Radiodiagnóstico" , "status" => true],
            ["name" => "Radiología" , "status" => true],
            ["name" => "Reumatología" , "status" => true],
            ["name" => "Salud Mental" , "status" => true],
            ["name" => "Terapia Ocupacional" , "status" => true],
            ["name" => "Traumatología" , "status" => true],
            ["name" => "UCI" , "status" => true],
            ["name" => "Urología" , "status" => true],
            ["name" => "No aplica" , "status" => true],

        ]);
    }
}
