<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'user_id' => 3, 
            'specialty_id' => 1, 
            'title' => 'Consulta General',
            'availability' => json_encode([
                'mon' => [ 
                    [
                        'start' => '8:00',
                        'end' => '16:00',
                        'appointments' => [
                            [
                                'start_appo' => '8:00', 
                            ]
                            
                            ]
                     ] 
                ] 
            
            ]),
            'duration_per_appointment' => 60,
            'duration_options' => json_encode([
                [ "value" => 15, "label" => "15 minutos" ],
                [ "value" => 30, "label" => "30 minutos" ],
                [ "value" => 45, "label" => "45 minutos" ],
                [ "value" => 60, "label" => "1 hora" ],

                [ "value" => 90, "label" => "1,5 horas" ],
                [ "value" => 120, "label" => "2 horas" ],
                [ "value" => 999999, "label" => "Personalizar..." ]
            ]),
            'adjusted_availability' => json_encode([]),
            'programming_slot' => json_encode([
                
                'available_now_check' => true,

                'interval_date' => [
                    'start_now_check' => false,
                    'custom_start_date' => '2024-09-09T04:00:00.000Z',

                    'end_never_check' => false,
                    'custom_end_date' => '2024-09-09T04:00:00.000Z'
                ],
            ]),
            'booked_appointment_settings' => json_encode([

                'time_between_appointment' => null,
                'allow_max_appointment_per_day' => false,
                'max_appointment_per_day' => 4,
            ]), 
            'description' => 'Esto es una jodida descripcion',
            'fields' => json_encode([
                [ 'name' => 'Nombre', 'required' => true, 'default' => true ],
                [ 'name' => 'Apellido', 'required' => true, 'default' => true ],
                [ 'name' => 'Correo', 'required' => true, 'default' => false ],
                [ 'name' => 'Cédula', 'required' => true, 'default' => true ],
                [ 'name' => 'Teléfono', 'required' => true, 'default' => true ],
                ]),
        ]);

        Service::create([
            'user_id' => 3, 
            'specialty_id' => 1, 
            'title' => 'Consulta especial',
            'availability' => json_encode([
                'mon' => [ 
                    [
                        'start' => '8:00',
                        'end' => '16:00',
                        'appointments' => [
                            [
                                'start_appo' => '8:00', 
                            ]
                            
                            ]
                     ] 
                ] 
            
            ]),
            'duration_per_appointment' => 60,
            'duration_options' => json_encode([
                [ "value" => 15, "label" => "15 minutos" ],
                [ "value" => 30, "label" => "30 minutos" ],
                [ "value" => 45, "label" => "45 minutos" ],
                [ "value" => 60, "label" => "1 hora" ],

                [ "value" => 90, "label" => "1,5 horas" ],
                [ "value" => 120, "label" => "2 horas" ],
                [ "value" => 999999, "label" => "Personalizar..." ]
            ]),
            'adjusted_availability' => json_encode([]),
            'programming_slot' => json_encode([
                
                'available_now_check' => true,

                'interval_date' => [
                    'start_now_check' => false,
                    'custom_start_date' => '2024-09-09T04:00:00.000Z',

                    'end_never_check' => false,
                    'custom_end_date' => '2024-09-09T04:00:00.000Z'
                ],
            ]), 
            'booked_appointment_settings' => json_encode([

                'time_between_appointment' => null,
                'allow_max_appointment_per_day' => false,
                'max_appointment_per_day' => 4,
            ]), 
            'description' => 'Esto es una jodida descripcion 2',
            'fields' => json_encode([
                [ 'name' => 'Nombre', 'required' => true, 'default' => true ],
                [ 'name' => 'Apellido', 'required' => true, 'default' => true ],
                [ 'name' => 'Correo', 'required' => true, 'default' => false ],
                [ 'name' => 'Cédula', 'required' => true, 'default' => true ],
                [ 'name' => 'Teléfono', 'required' => true, 'default' => true ],
                ]),
        ]);

        
    }
}
