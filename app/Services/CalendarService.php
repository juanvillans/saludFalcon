<?php  

namespace App\Services;

use App\Http\Resources\CalendarCollection;
use App\Models\Appointment;
use App\Models\Calendar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarService
{	


    public function getCalendars($params = null)
    {
        $calendars = Calendar::query()
        ->where('status',1)
        ->with('specialty')
        ->orderBy('id','desc')
        ->get();

        return new CalendarCollection($calendars);
    }

    public function createCalendar($data){
        
        $calendar = Calendar::create(
            [
                'specialty_id' => $data['specialty_id'],
                'description' => $data['description'],
                'status' => $data['status'],
                'duration_per_appointment' => $data['duration_per_appointment'],
                'duration_options' => $data['duration_options'],
                'availability' => $data['availability'],
                'adjusted_availability' => $data['adjusted_availability'],
                'programming_slot' => $data['programming_slot'],
                'booked_appointment_settings' => $data['booked_appointment_settings'],
                'fields' => $data['fields'],
            ]
            );
        
        return $calendar;
        
    }

    public function updateCalendar($data, $calendar){

        $calendar->update([
            'user_id' => $data['user_id'],
            'specialty_id' => $data['user_specialty_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'duration_per_appointment' => $data['duration_per_appointment'],
            'duration_options' => $data['duration_options'],
            'availability' => $data['availability'],
            'adjusted_availability' => $data['adjusted_availability'],
            'programming_slot' => $data['programming_slot'],
            'booked_appointment_settings' => $data['booked_appointment_settings'],
            'fields' => $data['fields'],
        ]);

        return $calendar;
        
    }

    public function deleteCalendar($calendar){
        
        Appointment::where('calendar_id', $calendar->id)
        ->update(['status' => 5]);

        $calendar->update(['status' => 2]);

        return 0;
        
    }


    /* ----------------------------- Functions to build calendar -------------------------- */
    public function getDinamicStructureCalendar($params, $calendar = null)
    {   
        $startWeek = now()->startOfWeek();
        $endWeek = $startWeek->copy()->endOfWeek();

        if ($params['start_date'] != null || $params['end_date'] != null) {

            $startWeek = Carbon::parse($params['start_date'])->startOfDay();
            $endWeek = Carbon::parse($params['end_date'])->endOfDay();
        }

        $today = now();

        return [

            'headerInfo' => [
                'today' => $today,
                'hour' => $today->format('H:i:s'),
                'month_year' => $this->getMonthYearFormat($startWeek, $endWeek),
            ],
       
            'weekDays' => $this->generateDinamicWeekDays($startWeek, $endWeek, $calendar)
        ];
    }
    
    
    public function getWeekRange($params)
    {
        $startWeek = isset($params['start_week']) 
            ? Carbon::parse($params['start_week'])->startOfWeek()
            : now()->startOfWeek();

        if (isset($params['to'])) {
            $startWeek = match ($params['to']) {
                'prev' => $startWeek->subWeek(),
                'next' => $startWeek->addWeek(),
                default => $startWeek,
            };
        }

        $endWeek = $startWeek->copy()->endOfWeek();

        return ['start_date' => $startWeek, 'end_date' => $endWeek];
    }
        
    protected function getMonthYearFormat(Carbon $start, Carbon $end): string
    {
        $start->locale('es');
        $end->locale('es');
        
        if ($start->month === $end->month) {
            return $start->isoFormat('MMMM YYYY');
        }
        return $start->isoFormat('MMMM').'-'.$end->isoFormat('MMMM YYYY');
    }

    protected function generateDinamicWeekDays(Carbon $startDate, Carbon $endDate, $calendar = null){
        
        $weekDays = [];
        $endDate = $endDate ?? $startDate->copy()->endOfDay();
        
        // Constante para los nombres de días
        $DAY_NAMES = [
            1 => 'mon', 2 => 'tue', 3 => 'wed',
            4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun'
        ];
        
        $allAppointments = $calendar 
            ? $calendar->appointments()
                ->whereBetween('day_reserved', [$startDate, $endDate])
                ->get()
                ->groupBy([
                    function ($item) {
                    return Carbon::parse($item->day_reserved)->format('Y-m-d');
                },
                'time_reserved'
                ])
            : collect();
        

        for ($currentDate = $startDate->copy(); $currentDate->lte($endDate); $currentDate->addDay()) {

            $dayOfWeek = $currentDate->dayOfWeekIso;
            $dayKey = $DAY_NAMES[$dayOfWeek] . '_' . $currentDate->format('Y-m-d');
            $dateString = $currentDate->toDateString();
            
            $dailyAppointments = $allAppointments->get($dateString, collect());
            
            $appointmentsObject = $dailyAppointments->isNotEmpty() 
                ? (object) $dailyAppointments->map->first()->all()
                : (object) [];
            
            $weekDays[$dayKey] = (object) [
                'appointments' => $appointmentsObject,
                'current_date' => $currentDate->copy(),
                'nro_appointments' => $dailyAppointments->count(),
            ];
        }
        
        return $weekDays;
    }

    public function getDaysAvailableOfMonth($params, $calendar)
    {
        if ($params['start_date'] == null) {
            return null;
        }
    
        $month = Carbon::parse($params['start_date']);
        $firstDay = $month->copy()->startOfMonth();
        $lastDay = $month->copy()->endOfMonth();
    
        // Obtener las fechas límite de programación
        $programmingSlot = $calendar->programming_slot ?? [];
        $startDate = $programmingSlot['interval_date']['start_now_check'] ?? false 
            ? Carbon::parse($calendar->created_at)->startOfDay()
            : Carbon::parse($programmingSlot['interval_date']['custom_start_date'] ?? null)->startOfDay();
        
        $endDate = $programmingSlot['interval_date']['end_never_check'] ?? false
            ? null // No hay fecha final
            : Carbon::parse($programmingSlot['interval_date']['custom_end_date'] ?? null)->endOfDay();
    
        $appointments = Appointment::whereBetween('day_reserved', [
            $firstDay->format('Y-m-d'), 
            $lastDay->format('Y-m-d')
        ])
        ->where('calendar_id', $calendar->id)
        ->get();
    
        $availability = [];
        $currentDay = $firstDay->copy();
        
        while ($currentDay->lte($lastDay)) {
            $dayOfMonth = $currentDay->day;
            $dayOfWeek = strtolower($currentDay->format('D')); 
            $dateStr = $currentDay->format('Y-m-d');
            $currentDate = $currentDay->copy()->startOfDay();
    
            // Verificar si la fecha está dentro del rango permitido
            $isWithinRange = $currentDate->gte($startDate) && 
                            ($endDate === null || $currentDate->lte($endDate));
    
            if (!$isWithinRange) {
                $availability[$dayOfMonth] = false;
                $currentDay->addDay();
                continue;
            }
    
            $adjustedDay = collect($calendar->adjusted_availability)->firstWhere('date', 'like', $dateStr ."%");
    
            if ($adjustedDay) {
                $shifts = $adjustedDay['shifts'] ?? [];
                $hasAvailability = !empty($shifts) && !$this->checkAppointments($dateStr, $shifts, $appointments);
            } else {
                $regularAvailability = $calendar->availability[$dayOfWeek] ?? [];
                $hasAvailability = !empty($regularAvailability) && !$this->checkAppointments($dateStr, $regularAvailability, $appointments);
            }
    
            $availability[$dayOfMonth] = $hasAvailability;
            $currentDay->addDay(); 
        }
    
        return $availability;
    }
    
    private function checkAppointments($date, $shifts, $appointments) {
        if (empty($shifts)) return false;
    
        $bookedHours = collect($shifts)
            ->flatMap(function ($shift) {
                return collect($shift['appointments'] ?? [])
                    ->pluck('start_appo')
                    ->toArray();
            })
            ->toArray();
    
        $date = Carbon::parse($date)->startOfDay();
    
        $hasAppointments = $appointments
            ->filter(function ($appointment) use ($date) {
                return Carbon::parse($appointment->day_reserved)->startOfDay()->eq($date);
            })
            ->whereIn('time_reserved', $bookedHours)
            ->isNotEmpty();
    
        return $hasAppointments;
    }


}
