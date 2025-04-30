<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookAppointment;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Appointment;
use App\Models\Calendar;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\CalendarService;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{

    private $params = [];
    private $calendarService;
    private $appointmentService;


    public function __construct(){
        $this->calendarService = new CalendarService;
        $this->appointmentService = new AppointmentService;
    }

    public function index(Request $request){

        
        $calendars = $this->calendarService->getCalendars();

        return inertia('Appointments',[
            'calendars' => $calendars,
        ]);

    }


    public function showCalendar(Request $request, Calendar $calendar){


        $this->params = [
            'start_date' => $request->input('start_date') ?? null,
            'end_date' => $request->input('end_date') ?? null,
            'calendar_month' => $request->input('calendar_month') ?? null,
        ];

        if($calendar->status != 1){
            
            return inertia('BookAppointment', [
                'calendar' => null,
                'data' => null,
            ]);
        }
            

        $calendar->load('specialty', 'appointments');


        $structure = $this->calendarService->getDinamicStructureCalendar($this->params, $calendar);

        $calendarMonth = $request->input('calendar_month') ?? null;
        $getCalendarMonth = ($calendarMonth !== "false" && $calendarMonth !== null);

        if($getCalendarMonth){
            $calendarMonth = $this->calendarService->getDaysAvailableOfMonth($this->params, $calendar);
            
            return inertia('BookAppointment', [
                'calendar_month' => $calendarMonth,    
                'calendar' => $structure,
                'data' => new CalendarResource($calendar),
            ]);
        }
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
            
            Log::info('Error agendando cita: ' . $e->getMessage());

            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }

    }

    public function cancelAppointmentFromDoctor(Appointment $appointment){

        DB::beginTransaction();

        try{

            $this->appointmentService->cancelAppointmentFromDoctor($appointment);
            DB::commit();

            return redirect()->back()->with(['message' => 'Cita cancelada con éxito']);
            

        }catch(\Exception $e){

            DB::rollback();

            Log::info('Error eliminando cita: ' . $e->getMessage());

            return redirect()->back()->withErrors(['data' => $e->getMessage()]);

        }

    }

    public function cancelAppointmentFromPatient($token){

        DB::beginTransaction();

        try{

            $response = $this->appointmentService->cancelAppointmentFromPatient($token);
            DB::commit();

            return 0;

            // return inertia('BookAppointment', [
            //     'data' => $response,
            // ]);

            

        }catch(\Exception $e){

            DB::rollback();

            Log::info('Error cancelando cita: ' . $e->getMessage());

            return redirect()->back()->withErrors(['data' => $e->getMessage()]);

        }
    }

    


}
