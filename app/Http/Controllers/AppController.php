<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Municipality;
use App\Models\PatientCondition;
use App\Models\Specialty;
use App\Models\StatusCase;
use Illuminate\Support\Facades\Request;
use Inertia\Response;

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


   
}
