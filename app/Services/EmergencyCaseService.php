<?php  

namespace App\Services;

use App\Http\Resources\CaseCollection;
use App\Http\Resources\PatientResource;
use App\Models\EmergencyCase;
use App\Models\Patient;
use App\Services\EvolutionService;
use Exception;

class EmergencyCaseService
{	
    public function getCases($params)
    {
        $cases = EmergencyCase::with('patient.municipality','patient.parish','user.specialty','area', 'evolutions','statusCase','condition')
                ->when($params['status'],function($query) use ($params){
                    $query->where('current_status', $params['status']);
                })
                ->when($params['search'],function($query) use ($params){

                    $query->where(function($query) use ($params) {
                        $query->whereHas('patient', function($query2) use ($params) {
                            $query2->whereRaw('LOWER(search) LIKE ?', ['%' . strtolower($params['search']) . '%']);
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

        if($caseCreated->current_status == 1 || $caseCreated->current_status == 2)
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
            ]);


            return $newPatient->id;
    }

    private function validateIfRepeatCI($ci){
        
        $patient = Patient::where('ci',$ci)->first();
        
        if(isset($patient->id))
            throw new Exception("Esta cédula ya se encuentra registrada", 403);
        
        return 0;
    }
    
   

}
