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
            'start_week' => $request->input('startWeek') ?? null,
            'to' => $request->input('to') ?? null,

        ];

        $calendar->load('user.specialty');

        $structure = $this->calendarService->getStructureCalendar($this->params);


        return inertia('BookAppointment', [
            
            'calendar' => $structure,
            'data' => new CalendarResource($calendar),
        ]);

    }

}
