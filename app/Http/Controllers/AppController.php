<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalendarResource;
use App\Models\Area;
use App\Models\Calendar;
use App\Models\Municipality;
use App\Models\PatientCondition;
use App\Models\Specialty;
use App\Models\StatusCase;
use Illuminate\Support\Facades\Request;
use Inertia\Response;
use App\Services\CalendarService;

class AppController 
{   
    

    public function index(): Response
    {
        return inertia('Index');
    }

    public function loginForm(): Response
    {
        return inertia('Login');
    }

    public function admin(): Response
    {
        return inertia('Dashboard/Index');
    }

    public function generalData(){

        $areas = Area::get();
        $municipalities = Municipality::with('parishes')->get();
        $statutes = StatusCase::get();
        $conditions = PatientCondition::get();
        $specialties = Specialty::get();

        return response()->json(compact('municipalities','statutes','conditions','areas', 'specialties'));
    }

    public function appointment(Calendar $calendar){

        return inertia('BookAppointment');

        return inertia('Dashboard/CreateCalendar',[
            
            'data' => new CalendarResource($calendar),
        ]); 
    }

   


   
}
