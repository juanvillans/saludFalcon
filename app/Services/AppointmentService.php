<?php  

namespace App\Services;

use App\Models\Activity;
use App\Models\Appointment;
use Carbon\Carbon;
use DB;

class AppointmentService
{	
    public function store($appointmentData){
        
        Appointment::create([
            'service_id' => $appointmentData['service_id'],
            'name' => $appointmentData['name'],
            'last_name' => $appointmentData['last_name'],
            'ci' => $appointmentData['ci'],
            'phone' => $appointmentData['phone'],
            'email' => $appointmentData['email'],
            'other_fields' => $appointmentData['other_fields'],
            'start' => $appointmentData['start'],
            'end' => $appointmentData['end'],
            'date' => $appointmentData['date'],
            'carbon_date' => Carbon::parse($appointmentData['date']),
            'status' => 'OPEN',
        ]);

        return 0;
    }

}
