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


    public function showCalendar(Calendar $calendar){

        return inertia('BookAppointment', [
            
            'data' => new CalendarResource($calendar),
        ]);

    }

}
