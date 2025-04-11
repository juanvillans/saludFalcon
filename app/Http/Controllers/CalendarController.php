<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Services\CalendarService;
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

        $structure = $this->calendarService->getStructureCalendar($this->params);
        
        return inertia('Dashboard/CreateCalendar',[
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
}
