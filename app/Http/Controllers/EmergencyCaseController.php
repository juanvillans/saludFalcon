<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseRequest;
use App\Http\Requests\EvolutionRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\CaseResource;
use App\Http\Resources\PatientResource;
use App\Models\Area;
use App\Models\EmergencyCase;
use App\Models\Municipality;
use App\Models\Patient;
use App\Models\PatientCondition;
use App\Models\StatusCase;
use App\Services\EmergencyCaseService;
use App\Services\EvolutionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmergencyCaseController extends Controller
{
    private $params = [];
    private EmergencyCaseService $emergencyCaseService;


    public function __construct()
    {
        $this->emergencyCaseService = new EmergencyCaseService;
        $this->evolutionService = new EvolutionService;

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
        
        $areas = Area::get();
        $muncipalities = Municipality::with('parishes')->get();
        $statutes = StatusCase::get();
        $conditions = PatientCondition::get();
        
        return inertia('Dashboard/Cases',[
            'data' => $emergencyCases,
            'patient' => $patient ?? null,
            'areas' => $areas,
            'municipalities' => $muncipalities,
            'statutes' => $statutes,
            'conditions' => $conditions,
            'filters' => ['status' => $request->input('status') ?? ''],
        ]);

    }

   
   
    public function store(CaseRequest $request)
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
            Log::info('Error: ' . $e->getMessage() . ' --- Linea: ' . $e->getLine());
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function caseDetail(Request $request, EmergencyCase $case){

        $case->load('patient.municipality','patient.parish','user.specialty','area', 'evolutions.user' , 'evolutions.area', 'evolutions.condition', 'evolutions.status','statusCase','condition');
        
        $nroEvolutions = $case->evolutions->filter(function ($evolution) {
            return $evolution->is_interconsult == false;
        })->count();

        
        $nroInter = $case->evolutions->filter(function ($evolution) {
            return $evolution->is_interconsult == true;
        })->count();
        
        return inertia('Dashboard/CaseDetail',[
            'caseDetail' => new CaseResource($case),
            'nroEvol' => $nroEvolutions,
            'nroInter' => $nroInter,
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

    public function searchPatient(Request $request){
        
        $this->params = [
            'ci' => $request->input('ci') ?? null,
        ];

        $patient = $this->emergencyCaseService->getPatientByCI($this->params);
        return response()->json(['patient' => $patient]);
    }

    public function addEvolution(EvolutionRequest $request, EmergencyCase $case){

         DB::beginTransaction();

        try 
        {
            $data = $request->all();

            // $this->evolutionService->addEvolution($case,$data);

            DB::commit();

            return redirect()->back()->with(['message' => 'Evolución añadida']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }


        return 'OSKERS';
    }

}
