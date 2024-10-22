<?php

namespace App\Http\Controllers;

use App\Services\EmergencyCaseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class EmergencyCaseController extends Controller
{
    private $params = [];
    private EmergencyCaseService $emergencyCaseService;


    public function __construct()
    {
        $this->emergencyCaseService = new EmergencyCaseService;
    }

    public function index(Request $request)
    {
        $this->params = [
            'search' => $request->input('search') ?? null,
            'page' => $request->input('page') ?? null,
            'rows' => $request->input('rows') ?? null,
            'patient_ci' => $request->input('ci') ?? null,
        ];

        $emergencyCases = $this->emergencyCaseService->getCases($this->params);
        $patient = $this->emergencyCaseService->getPatientByCI($this->params);

        return inertia('Dashboard/HistorialMedico',[
            'data' => $emergencyCases,
            'patient' => $patient,
        ]);

    }

   
   
    public function store(Request $request)
    {
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->emergencyCaseService->createCase($data);

            DB::commit();

            return redirect()->back()->with(['message' => 'Caso registrado con exito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function patientDetail(Request $request){
        return inertia('Dashboard/PatientDetail');
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function patient(Request $request){
        
        $this->params = [
            'ci' => $request->input('ci') ?? null,
        ];

        $patient = $this->emergencyCaseService->getPatientByCI($this->params);
        return inertia('Dashboard/HistorialMedico',[
            'patient' => $patient,
        ]);


    }

    public function destroy(string $id)
    {
        //
    }
}
