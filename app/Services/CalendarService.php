<?php  

namespace App\Services;

use App\Http\Resources\CalendarCollection;
use App\Models\Calendar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarService
{	


    public function getCalendars($params)
    {
        $calendars = Calendar::query()
        ->where('status',1)
        ->when($params['see_all'] == null,function($query) use ($params){
            $query->where('user_id', auth()->user()->id);
        })
        ->when($params['see_all'] !== null, function($query) use ($params){
            
            if(auth()->user()->hasRole('admin') && isset($params['specialty_id'])){
                $query->whereHas('user',function($query2) use ($params){
                    $query2->where('specialty_id', $params['specialty_id']);
                });
            }
            else
                $query->where('user_id', auth()->user()->id);

        })
        ->with('user.specialty')
        ->orderBy('id','desc')
        ->get();

        return new CalendarCollection($calendars);
    }

    public function createCalendar($data){
        
        $calendar = Calendar::create(
            [
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
                ->groupBy(['day_reserved', 'time_reserved'])
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
            ];
        }
        
        return $weekDays;
    }


}
