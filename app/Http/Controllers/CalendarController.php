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
        ];     
        
        $calendars = $this->calendarService->getCalendars($this->params);

        return inertia('Dashboard/Agenda',[
            'data' => [
                'calendars' => $calendars,
            ]
        ]);
    }
}
