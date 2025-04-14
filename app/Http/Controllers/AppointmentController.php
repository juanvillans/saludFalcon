<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookAppointment;
use App\Http\Requests\BookAppointmentRequest;
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
    private $appointmentService;


    public function __construct(){
        $this->calendarService = new CalendarService;
        $this->appointmentService = new AppointmentService;
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

    public function bookAppointment(BookAppointmentRequest $request, Calendar $calendar){


        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->appointmentService->bookAppointment($data, $calendar);

            DB::commit();

            return redirect()->back()->with(['message' => 'Cita reservada con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }

    }

}
