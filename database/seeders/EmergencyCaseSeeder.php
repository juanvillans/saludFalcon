<?php

namespace Database\Seeders;

use App\Models\EmergencyCase;
use App\Models\Evolution;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmergencyCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Jose', 'Daniel', 'Juan', 'Marcos', 'Carlos'];
        $lastNames = ['Diaz', 'Rodriguez', 'Villasmil', 'Donquis', 'Tovar'];
        $sexs = ['Masculino', 'Femenino'];
        $doctor = User::where('id',3)->first();

       $cases = [
    [
        'reason' => 'Dolor abdominal intenso',
        'diagnosis' => 'Gastroenteritis aguda',
        'treatment' => 'Hidratación oral con SRO, dieta blanda y Loperamida 2mg cada 8h',
    ],
    [
        'reason' => 'Fiebre alta y tos con expectoración',
        'diagnosis' => 'Neumonía adquirida en la comunidad',
        'treatment' => 'Amoxicilina-Clavulánico 875/125mg cada 12h + Paracetamol 1g cada 8h',
    ],
    [
        'reason' => 'Diarrea y vómitos persistentes',
        'diagnosis' => 'Deshidratación moderada por infección intestinal',
        'treatment' => 'Sueroterapia IV (SSN 0.9%), Ondansetrón 4mg IV y reposo oral',
    ],
    [
        'reason' => 'Dificultad para respirar y sibilancias',
        'diagnosis' => 'Crisis asmática aguda',
        'treatment' => 'Salbutamol inhalado 4 puff + Prednisona 30mg VO + Oxígeno a 2L/min',
    ],
    [
        'reason' => 'Dolor torácico opresivo',
        'diagnosis' => 'Síndrome coronario agudo (SCA)',
        'treatment' => 'AAS 100mg, Clopidogrel 300mg, Nitroglicerina SL y derivación urgente a cardiología',
    ],
    [
        'reason' => 'Caída con dolor en brazo derecho',
        'diagnosis' => 'Fractura de radio distal no desplazada',
        'treatment' => 'Inmovilización con férula yeso, analgesia con Ibuprofeno 600mg cada 8h',
    ],
    [
        'reason' => 'Herida cortante en mano por cuchillo',
        'diagnosis' => 'Herida cortante en 3er dedo (2cm) sin daño tendinoso',
        'treatment' => 'Limpieza quirúrgica, sutura con nylon 4-0 y profilaxis antitetánica',
    ],
    [
        'reason' => 'Cefalea intensa y fotofobia',
        'diagnosis' => 'Migraña con aura',
        'treatment' => 'Sumatriptán 50mg VO + Naproxeno 550mg + reposo en oscuridad',
    ],
    [
        'reason' => 'Mareo y pérdida de conocimiento breve',
        'diagnosis' => 'Síncope vasovagal',
        'treatment' => 'Reposo con piernas elevadas, hidratación y monitorización de signos',
    ],
    [
        'reason' => 'Dolor lumbar agudo tras levantar peso',
        'diagnosis' => 'Lumbalgia mecánica aguda',
        'treatment' => 'Diclofenaco 75mg IM + relajante muscular (Tolperisona 150mg cada 12h)',
    ],
    [
        'reason' => 'Hemorragia nasal profusa',
        'diagnosis' => 'Epistaxis anterior por trauma digital',
        'treatment' => 'Compresión nasal, taponamiento anterior y ácido tranexámico tópico',
    ],
    [
        'reason' => 'Quemadura con agua caliente en antebrazo',
        'diagnosis' => 'Quemadura de 2do grado (5% SC)',
        'treatment' => 'Limpieza con suero fisiológico, Sulfadiazina de plata 1% y curación oclusiva',
    ],
    [
        'reason' => 'Erupción cutánea pruriginosa generalizada',
        'diagnosis' => 'Reacción alérgica por medicamento',
        'treatment' => 'Difenhidramina 25mg IM + Dexametasona 4mg IV + suspender fármaco causal',
    ],
    [
        'reason' => 'Convulsiones tónico-clónicas generalizadas',
        'diagnosis' => 'Crisis epiléptica',
        'treatment' => 'Diazepam 10mg IV, Lorazepam 2mg sublingual y monitorización neurológica',
    ],
    [
        'reason' => 'Dolor y ardor al orinar',
        'diagnosis' => 'Infección urinaria no complicada',
        'treatment' => 'Nitrofurantoína 100mg cada 12h por 5 días + aumento de ingesta hídrica',
    ],
];



        for ($i = 0; $i < 100; $i++) {

            $name = $names[rand(0,4)];
            $lastName = $lastNames[rand(0,4)];

            $patient = Patient::create([
                'ci' => '30847'.$i,
                'name' => $name,
                'last_name' => $lastName,
                'phone_number' => "+58412".$i,
                'sex' => $sexs[rand(0,1)],
                'date_birth' => Carbon::createFromDate(2000, 12, 25),
                'age' => 25,
                'municipality_id' => 14,
                'parish_id' => 37,
                'address' => 'San José',
                'search' => $name . ' ' . $lastName . ' 30847' . $i ,
            ]);

            $randomCase = $cases[array_rand($cases)];

            $emergencyModel = EmergencyCase::create([

                'patient_id' => $patient->id,
                'user_id' => $doctor->id,
                'area_id' => 1,
                'current_patient_condition_id' => 2,
                'entry_date' => Carbon::now()->yesterday(),
                'entry_hour' => Carbon::now()->yesterday()->format('H:i:s'),
                'current_status_case' => 4,
                'departure_date' => null,
                'departure_hour' => null,
                'reason' => $randomCase['reason'],
                'diagnosis' => $randomCase['diagnosis'],
                'treatment' => $randomCase['treatment'],
                'bed_number' => rand(1,9),
            ]);

            Evolution::create([

                'emergency_case_id' => $emergencyModel->id,
                'user_id' => $doctor->id,
                'area_id' => 1,
                'patient_condition_id' => 2,
                'evolution' => 'Sin descripción',
                'status_id' => 4,
                'diagnosis' => $randomCase['diagnosis'],
                'treatment' => $randomCase['treatment'],
                'destiny' => NULL,
                'is_interconsult' => false,
                'bed_number' => $emergencyModel->bed_number,

            ]);

        }
    }
}



