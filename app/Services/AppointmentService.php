<?php  

namespace App\Services;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentService
{	

    public function bookAppointment($data, $calendar){

        $patient = $this->searchPatient($data);


        $appointment = Appointment::create([
            'calendar_id' => $calendar->id,
            'patient_id' => $patient->id,
            'day_reserved' => Carbon::parse($data['day_reserved']),
            'time_reserved' => $data['time_reserved'],
            'appointment_data' => $data['appointment_data']
        ]);

    }

    private function searchPatient($data){
        
        $patientData = $data['appointment_data'];
        $patient = Patient::where('ci', $patientData['ci'])->first();
        
        if(isset($patient->id))
            return $patient;
        
        $patient = Patient::create([
            'ci' => $patientData['ci'], 
            'name' => $patientData['name'],
            'last_name' => $patientData['last_name'],
            'phone_number' => $patientData['phone_number'],
            'email' => $patientData['email'],
            'sex' => $patientData['sex'], 
            'date_birth' => $patientData['date_birth'],
            'age' => $this->calculateAge($patientData['date_birth']),
            'search' => $patientData['name'] . ' ' . $patientData['last_name'] . $patientData['ci'], 
        ]);

        return $patient;
}

function calculateAge($dateBirth): int
    {
        $date = Carbon::parse($dateBirth);
        $today = Carbon::now();
        
        return $today->diffInYears($date);
        
    }


}
