<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;

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
}
