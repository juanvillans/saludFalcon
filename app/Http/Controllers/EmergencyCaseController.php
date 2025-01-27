<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\CaseResource;
use App\Http\Resources\PatientResource;
use App\Models\Area;
use App\Models\EmergencyCase;
use App\Models\Patient;
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
            'per_page' => $request->input('per_page') ?? null,
            'status' => $request->input('status') ?? null,
            'patient_ci' => $request->input('ci') ?? null,
        ];

        $emergencyCases = $this->emergencyCaseService->getCases($this->params);
        if($this->params['patient_ci'] != null)
            $patient = $this->emergencyCaseService->getPatientByCI($this->params);
        
        $areas = Area::where('division_id',2)->get();
        
        return inertia('Dashboard/Patient',[
            'data' => $emergencyCases,
            'patient' => $patient ?? null,
            'areas' => $areas,
            'filters' => ['status' => $request->input('status') ?? ''],
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

            return redirect()->back()->with(['message' => 'Operación realizada con exito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function caseDetail(Request $request, EmergencyCase $case){

        $case->load('patient.municipality','patient.parish','user.specialty','area', 'evolutions');
        
        return inertia('Dashboard/PatientDetail',[
            'case' => new CaseResource($case)
        ]);
    }

    public function updatePatient(PatientRequest $request, Patient $patient)
    {
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->emergencyCaseService->updatePatient($data, $patient);

            DB::commit();

            return redirect()->back()->with(['message' => 'Datos del paciente actualizados']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function patient(Request $request){
        
        $this->params = [
            'ci' => $request->input('ci') ?? null,
        ];

        $patient = $this->emergencyCaseService->getPatientByCI($this->params);
        return inertia('Dashboard/Patient',[
            'patient' => $patient,
        ]);


    }

}
