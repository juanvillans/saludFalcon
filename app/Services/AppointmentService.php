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

        $this->isBetweenInterval($data, $calendar);

        $this->isMaximumDaysInAdvance($data, $calendar);

        $this->isMinimumHoursInAdvance($data, $calendar);

        $this->checkMaxAppointmentsPerDay($data, $calendar);

        $this->isDateAvailable($data, $calendar);


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

    private function isBetweenInterval($data, $calendar){
        
        $programminSlot = $calendar->programming_slot;

        if($programminSlot['interval_date']['start_now_check'] == false){
            
            $registerDate = Carbon::parse($data['day_reserved']);
            $customStartDate = Carbon::parse($programminSlot['interval_date']['custom_start_date']);

            if($registerDate->lte($customStartDate))
                throw new Exception("El dia a reservar debe ser posterior al dia: " . $customStartDate->format('Y-m-d'), 400);

        }

        if($programminSlot['interval_date']['end_now_check'] == false){
            
            $registerDate = Carbon::parse($data['day_reserved']);
            $customEndDate = Carbon::parse($programminSlot['interval_date']['custom_end_date']);

            if($registerDate->gte($customEndDate))
                throw new Exception("El dia a reservar debe ser anterior al dia: " . $customEndDate->format('Y-m-d'), 400);
                    
        }

        return 0;

    }

    private function isMaximumDaysInAdvance($data, $calendar){

        $programminSlot = $calendar->programming_slot;

        if($programminSlot['allow_max_reservation_time_before_appointment'] == false)
            return 0;

        $maxDays = $programminSlot['max_reservation_time_before_appointment'];
        $registerDate = Carbon::parse($data['day_reserved']);
        $today = Carbon::now();

        if ($today->diffInDays($registerDate, false) > $maxDays) {
            throw new Exception("No puede reservar una cita con mas de " . $maxDays . ' dias de diferencia', 400);
        }

        return 0;

    }

    private function isMinimumHoursInAdvance($data, $calendar){

        $programminSlot = $calendar->programming_slot;

        if($programminSlot['allow_min_reservation_time_before_appointment'] == false)
            return 0;

        $minHours = $programminSlot['min_reservation_time_before_appointment'];
        
        $registerDate = Carbon::parse($data['day_reserved'])
        ->setTimeFromTimeString($data['time_reserved']);

        $today = Carbon::now();

        if ($today->diffInHours($registerDate, false) < $minHours) {
            throw new Exception("No puede reservar una cita con menos de " . $minHours . ' horas de diferencia', 400);
            
        }

        return 0;

    }

    private function checkMaxAppointmentsPerDay($data, $calendar){
        
        $appointmentSettings = $calendar->booked_appointment_settings;
        $maxAppointmentsPerDay = $appointmentSettings['max_appointment_per_day'];
        $CANCELLED_BY_PATIENT = 4;
        $CANCELLED_BY_DOCTOR = 5;
        $registerDate = Carbon::parse($data['day_reserved'])->format('Y-m-d');

        $appointments = Appointment::where('calendar_id', $calendar->id)
        ->whereDate('day_reserved', $registerDate)
        ->where('status','!=',$CANCELLED_BY_PATIENT)
        ->where('status','!=',$CANCELLED_BY_DOCTOR)
        ->count();

        if($maxAppointmentsPerDay <= $appointments )
            throw new Exception("Este dia alcanzado el maximo numero de reservas, intente con otro dia", 400);
        
        return 0;

    }

    private function isDateAvailable($data, $calendar){
        
        $registerDate = Carbon::parse($data['day_reserved'])->format('Y-m-d');
        $CANCELLED_BY_PATIENT = 4;
        $CANCELLED_BY_DOCTOR = 5;

        $appointment = Appointment::where('calendar_id', $calendar->id)
        ->whereDate('day_reserved', $registerDate)
        ->where('time_reserved', $data['time_reserved'])
        ->where('status','!=',$CANCELLED_BY_PATIENT)
        ->where('status','!=',$CANCELLED_BY_DOCTOR)
        ->first();

        if(isset($appointment->id))
            throw new Exception("Este dia y hora ya tiene una cita reservada", 400);
            
        return 0;
    }


}
