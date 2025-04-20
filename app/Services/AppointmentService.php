<?php  

namespace App\Services;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Exception;

class AppointmentService
{	

    public function bookAppointment($data, $calendar){


        
        $patient = $this->searchPatient($data);

        $this->applyValidations($data,$calendar, $patient);


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

    public function calculateAge($dateBirth){

        $date = Carbon::parse($dateBirth);
        $today = Carbon::now();
        
        return $today->diffInYears($date);
        
    }

    private function applyValidations($data, $calendar, $patient){

        $this->isPastDate($data);
        
        $this->haveAnotherReservationActive($patient, $calendar);


    }

    private function isPastDate($data){

        if(Carbon::parse($data['day_reserved'])->lte(Carbon::yesterday()) )
            throw new Exception("El dia a reservar debe ser posterior al dia de ayer", 400);
        
        return 0;

    }

    private function haveAnotherReservationActive($patient, $calendar){

        $PENDING_STATUS = 1;

        $appointment = Appointment::where('patient_id', $patient->id)
        ->where('calendar_id', $calendar->id)
        ->where('status', $PENDING_STATUS)
        ->first();

        if(isset($appointment->id))
            throw new Exception("Usted ya tiene una cita reservada para el dia: " . $appointment->day_reserved, 400);
        
        return 0;
    }


}
