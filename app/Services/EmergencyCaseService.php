<?php  

namespace App\Services;

use App\Http\Resources\CaseCollection;
use App\Http\Resources\PatientResource;
use App\Models\EmergencyCase;
use App\Models\Patient;
use Exception;

class EmergencyCaseService
{	
    public function getCases($params)
    {
        $cases = EmergencyCase::search(trim($params['search'] ?? ''))
        ->query(function ($query) use ($params) {
            $query->join('patients', 'emergency_cases.patient_id', 'patients.id')
                ->select([
                          
                          'emergency_cases.id', 'emergency_cases.cases', 'emergency_cases.current_status', 'emergency_cases.search',

                          'patients.id as patient_id','patients.name as patient_name', 'patients.last_name as patient_last_name', 'patients.ci as patient_ci', 
                          'patients.phone_number as patient_phone_number', 'patients.sex as patient_sex', 'patients.date_birth as patient_date_birth', 'patients.search as patient_search'
                        
                          ])
                ->when($params['status'],function($query) use ($params){
                    $query->where('current_status', $params['status']);
                })
                ->orderBy('emergency_cases.id', 'DESC');
        })
        ->paginate($params['per_page'] ?? 25);

        $cases->getCollection()->transform(function ($case) {
            $case->cases = json_decode($case->cases, true);
            return $case;
        });

        return new CaseCollection($cases);
    }

    public function createCase($data)
    {
        $patientID = $data['patient_id'];
        
        if($patientID == null)
            $patientID = $this->createPatient($data);

        $lastCase = $data['cases'][0] ?? null;

        EmergencyCase::updateOrCreate([
            'patient_id' => $patientID
        ],
        [
            'cases' => json_encode($data['cases']),
            'current_status' => $lastCase['status'] ?? null,
            'search' => $lastCase['diagnosis'] . ' ' . $lastCase['treatment'] . ' ' . $lastCase['doctor']['name'] . ' ' . $lastCase['doctor']['last_name'] 
        ]);

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
            // 'search' => $this->generateSearch($data)
        ]);

        return 0;
    }

    public function getPatientByCI($param){
        
        
        $patient = Patient::where('ci',$param['patient_ci'])->with('emergencyCase')->first();
        
        if(!isset($patient->id))
            return null;
        
        $patient->emergencyCase->cases = json_decode($patient->emergencyCase->cases,true);
    
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
            ]);


            return $newPatient->id;
    }

    private function validateIfRepeatCI($ci){
        
        $patient = Patient::where('ci',$ci)->first();
        
        if(isset($patient->id))
            throw new Exception("Esta cédula ya se encuentra registrada", 403);
        
        return 0;
    }
    
   private function validateCasesJSON($cases){
    
    }

}
