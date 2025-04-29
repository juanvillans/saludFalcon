<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use App\Services\CalendarService;
use App\Services\SpecialtyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalendarController
{   
    private $params = [];
    private $calendarService;
    

    public function __construct()
    {
        $this->calendarService = new CalendarService;
    }

    public function index(Request $request)
    {   

        $this->params = [
            'see_all' => $request->input('see_all') ?? null,
            'specialty_id' => $request->input('specialty_id') ?? null,

        ];     
        
        $calendars = $this->calendarService->getCalendars($this->params);

        return inertia('Dashboard/Calendars',[
            'data' => [
                'calendars' => $calendars,
            ]
        ]);
    }

    public function create(Request $request){

        $this->params = [
            'start_week' => $request->input('startWeek') ?? null,
            'to' => $request->input('to') ?? null,
        ];

        $dateRange = $this->calendarService->getWeekRange($this->params);
        $structure = $this->calendarService->getDinamicStructureCalendar($dateRange);
        
        $specialtyService = new SpecialtyService;
        $specialtiesAvailable = $specialtyService->getSpecialtiesWithoutCalendar();

        
        return inertia('Dashboard/CreateCalendar',[
            'specialties' => $specialtiesAvailable, 
            'calendar' => $structure,
        ]);
    }

    public function store(CalendarRequest $request){
        
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->calendarService->createCalendar($data);

            DB::commit();

            return redirect('/admin/agenda')->with(['message' => 'Calendario creado con exito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            Log::info('Error: ' . $e->getMessage() . ' --- Linea: ' . $e->getLine());
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function update(CalendarRequest $request, Calendar $calendar){
        
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->calendarService->updateCalendar($data, $calendar);

            DB::commit();

            return redirect('/admin/agenda')->with(['message' => 'Calendario actualizado con exito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            Log::info('Error: ' . $e->getMessage() . ' --- Linea: ' . $e->getLine());
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function show(Request $request, Calendar $calendar){

        $this->params = [
            'start_week' => $request->input('startWeek') ?? null,
            'to' => $request->input('to') ?? null,

        ];

        $dateRange = $this->calendarService->getWeekRange($this->params);

        $calendar->load('user.specialty', 'appointments');

        $structure = $this->calendarService->getDinamicStructureCalendar($dateRange, $calendar);
        
        return inertia('Dashboard/CreateCalendar',[
            'calendar' => $structure,
            'data' => new CalendarResource($calendar),
        ]); 
    }

    public function destroy(Calendar $calendar){

        DB::beginTransaction();

        try 
        {

            $this->calendarService->deleteCalendar($calendar);

            DB::commit();

            return redirect('/admin/agenda')->with(['message' => 'Calendario eliminado con con exito']);

        }
        catch (\Exception $e)
        {   
            
            DB::rollback();
            Log::info('Error: ' . $e->getMessage() . ' --- Linea: ' . $e->getLine());
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }

    }
}
