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
        $cases = EmergencyCase::query()
        ->when($params['search'] ?? null, function($query, $search){
            
            $query->whereHas('patient', function ($query) use ($search){
                
                $query->where('search','like','%' . $search . '%');
            });
        })
        ->with('patient')
        ->paginate($params['rows'] ?? 25, ['*'], 'page', $params['page']);
        
        return new CaseCollection($cases);
    }

    public function createCase($data)
    {
        $patientID = $data['patient_id'];
        
        if($patientID == null)
            $patientID = $this->createPatient($data);

        $lastCase = $data['cases'][0];

        EmergencyCase::updateOrCreate([
            'patient_id' => $patientID
        ],
        [
            'cases' => json_encode($data['cases']),
            'current_status' => $lastCase['status'],
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
            'search' => $this->generateSearch($data)
        ]);

        return 0;
    }

    public function getPatientByCI($param){
        
        
        $patient = Patient::where('ci',$param['patient_ci'])->with('emergencyCase')->first();
        
        
        if(isset($patient->id))
            return new PatientResource($patient);
        
        return null;
    }

   private function createPatient($data){

        $newPatient = Patient::create([
            'ci' => $data['patient_ci'],
            'name' => $data['patient_name'],
            'last_name' => $data['patient_last_name'],
            'phone_number' => $data['patient_phone_number'] ?? null,
            'sex' => $data['patient_sex'],
            'date_birth' => $data['patient_date_birth'],
            'search' => $this->generateSearch($data)      
        ]);


        return $newPatient->id;
   }

    private function generateSearch($data)
    {
        $search = $data['patient_ci'] . " "
                 .$data['patient_name'] . " "
                 .$data['patient_last_name'] . " "
                 .$data['patient_phone_number'] . " ";
        
        return $search;
    }
    

}
