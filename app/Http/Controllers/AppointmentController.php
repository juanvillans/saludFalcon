<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    private AppointmentService $appointmentService;
    private $params = [];

    public function __construct(){
        $this->appointmentService = new AppointmentService;
    }

    public function index(){

        return inertia('Reservar');
    }

    public function store(CreateAppointmentRequest $appointmentData){

        DB::beginTransaction();

        try {

            $this->appointmentService->store($appointmentData->all());
            
            DB::commit();
            
            return redirect()->back()->with(['message' => 'Cita agendada con éxito']);
        
        } catch (\Throwable $e) {
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }


    }

}
