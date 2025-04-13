<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\CalendarService;

class AppointmentController extends Controller
{

    private $params = [];
    private $calendarService;

    public function __construct(){
        $this->calendarService = new CalendarService;
    }


    public function showCalendar(Request $request, Calendar $calendar){


        $this->params = [
            'start_date' => $request->input('start_date') ?? null,
            'end_date' => $request->input('end_date') ?? null,

        ];

        $calendar->load('user.specialty', 'appointments');

        $structure = $this->calendarService->getDinamicStructureCalendar($this->params, $calendar);


        return inertia('BookAppointment', [
            
            'calendar' => $structure,
            'data' => new CalendarResource($calendar),
        ]);

    }

}
