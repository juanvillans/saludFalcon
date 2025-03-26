<?php  

namespace App\Services;

use App\Http\Resources\CaseCollection;
use App\Http\Resources\PatientResource;
use App\Models\EmergencyCase;
use App\Models\Patient;
use App\Services\EvolutionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class EmergencyCaseService
{	

    protected $STATUS_MEDICAL_DISPATCH = 1;
    protected $STATUS_CONTRAMEDICAL_DISPATCH = 2;
    protected $STATUS_DECEASED = 5; 


    public function getCases($params)
    {
        $cases = EmergencyCase::with('patient.municipality','patient.parish','user.specialty','area','statusCase','condition')
                ->when($params['status'],function($query) use ($params){
                    $query->where('current_status', $params['status']);
                })
                ->when($params['condition'],function($query) use ($params){
                    $query->where('current_patient_condition_id', $params['condition']);
                })
                ->when($params['area_id'],function($query) use ($params){
                    $query->where('area_id', $params['area_id']);
                })
                ->when($params['case_id'],function($query) use ($params){
                    $query->where('id', $params['case_id']);
                })
                ->when(isset($params['start_date']) && isset($params['end_date']),function($query) use ($params){
                    
                    $startDateMillis = (int)$params['start_date']; 
                    $endDateMillis = (int)$params['end_date']; 
                
                    $startDateSeconds = $startDateMillis / 1000;
                    $endDateSeconds = $endDateMillis / 1000;

                    $startDate = Carbon::createFromTimestamp($startDateSeconds)->startOfDay();
                    $endDate = Carbon::createFromTimestamp($endDateSeconds)->endOfDay();

                    Log::info([$startDate, $endDate]);

                    $query->whereBetween('entry_date', [$startDate, $endDate]);
                })
                ->when($params['specialty_id'], function($query) use ($params){
                    $query->where(function($query) use ($params) {
                        $query->whereHas('user', function ($query2) use ($params){
                            $query2->where('specialty_id',$params['specialty_id']);
                        });
                    });

                })
                ->when($params['age'], function($query) use ($params){
                    $query->where(function($query) use ($params) {
                        $query->whereHas('patient', function ($query2) use ($params){
                            $query2->where('age',$params['age']);
                        });
                    });

                })
                ->when($params['search'],function($query) use ($params){

                    $query->where(function($query) use ($params) {
                        
                        $query->whereHas('patient', function($query2) use ($params) {
                            $query2->whereRaw('LOWER(search) LIKE ?', ['%' . strtolower($params['search']) . '%']);
                        });

                        $query->orWhereHas('user', function($query3) use ($params) {
                            $query3->whereRaw('LOWER(search) LIKE ?', ['%' . strtolower($params['search']) . '%']);
                        });

                    });
                })
                ->orderBy('id', 'DESC')
                ->paginate($params['per_page'] ?? 25);

        return new CaseCollection($cases);
    }

    public function createCase($data)
    {
        $patientID = $data['patient_id'];
        
        if($patientID == null)
            $patientID = $this->createPatient($data);


        $this->validateIfExistsOpenCase($patientID);

        $caseCreated = EmergencyCase::create([

            'patient_id' => $patientID,
            'current_patient_condition_id' => $data['current_patient_condition_id'],
            'user_id' => $data['user_id'],
            'area_id' => $data['area_id'],
            'entry_date' => $data['entry_date'],
            'entry_hour' => $data['entry_hour'],
            'current_status' => $data['current_status'],
            'departure_date' => $data['departure_date'] ?? null,
            'departure_hour' => $data['departure_hour'] ?? null,
            'reason' => $data['reason'],
            'diagnosis' => $data['diagnosis'],
            'treatment' => $data['treatment'],
            'destiny' => $data['destiny'],
        ]);

        $evolutionService = new EvolutionService;

        if($caseCreated->current_status == $this->STATUS_MEDICAL_DISPATCH || 
           $caseCreated->current_status == $this->STATUS_CONTRAMEDICAL_DISPATCH ||
           $caseCreated->current_status == $this->STATUS_DECEASED 
       )
            $evolutionService->createEvolutionFromCaseButDischarge($caseCreated);

        else{

            $evolutionService->createEvolutionFromCase($caseCreated);
        }


        return 0;

    }

    public function updatePatient($data, $patient){

        $patient->update([
            'ci' => $data['patient_ci'],
            'name' => $data['patient_name'],
            'last_name' => $data['patient_last_name'],
            'phone_number' => $data['patient_phone_number'],
            'sex' => $data['patient_sex'],
            'date_birth' => $data['patient_date_birth'],
            'age' => $this->calculateAge($data['patient_date_birth']),
            'search' => $data['patient_name'] . ' ' . $data['patient_last_name'] . $data['patient_ci'], 
        ]);

        return 0;
    }

    public function getPatientByCI($param){
        
        
        $patient = Patient::where('ci',$param['ci'])->with('municipality','parish')->first();
        
        if(!isset($patient->id))
            return null;
    
        return new PatientResource($patient);
        
    }

    private function createPatient($data){

            $this->validateIfRepeatCI($data['patient_ci']);

            $newPatient = Patient::create([
                'ci' => $data['patient_ci'],
                'name' => $data['patient_name'],
                'last_name' => $data['patient_last_name'],
                'phone_number' => $data['patient_phone_number'] ?? null,
                'sex' => $data['patient_sex'],
                'date_birth' => $data['patient_date_birth'],
                'municipality_id' => $data['municipality_id'],
                'parish_id' => $data['parish_id'],
                'address' => $data['patient_address'],
                'search' => $data['patient_name'] . ' ' . $data['patient_last_name'] . $data['patient_ci'], 
                'age' => $this->calculateAge($data['patient_date_birth']),

            ]);


            return $newPatient->id;
    }

    private function validateIfRepeatCI($ci){
        
        $patient = Patient::where('ci',$ci)->first();
        
        if(isset($patient->id))
            throw new Exception("Esta cédula ya se encuentra registrada", 403);
        
        return 0;
    }

    private function validateIfExistsOpenCase($patientID){

        $case = EmergencyCase::where('patient_id',$patientID)
        ->orderBy('id','desc')
        ->first();

        if(!isset($case->id))
            return 0;

        if($case->current_status != $this->STATUS_MEDICAL_DISPATCH && 
           $case->current_status != $this->STATUS_CONTRAMEDICAL_DISPATCH && 
           $case->current_status != $this->STATUS_DECEASED  
          )
        {
            throw new Exception("Este paciente tiene un caso sin cerrar: <a href='" . route('caseDetail', ['case' => $case->id]) ."' target='_blank'> Ver caso </a>" , 400);
            
        }
    }
    
    function calculateAge($dateBirth): int
    {
        $date = Carbon::parse($dateBirth);
        $today = Carbon::now();
        
        return $today->diffInYears($date);
        
    }

}
