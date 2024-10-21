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
            
            $query->where('search','like','%' . $search . '%');
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
        
        EmergencyCase::updateOrCreate([
            'patient_id' => $patientID
        ],
        [
            'cases' => json_encode($data['cases']),
            'current_status' => $data['current_status'],
        ]);

        return 0;

    }


    public function updateCase($data, $user)
    {

        $user->update([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "search" => $this->generateSearch($data),
        ]);

        method_exists($user, 'revokeRoles') ? $user->revokeRoles(): null;
        
        $user->assignRole($data['role_name']);

        if($user->hasRole('doctor'))
            $this->assignSpecialties($user,$data);

        return 0;

    }

    public function getPatientByCI($param){
        
        
        $patient = Patient::where('ci',$param['ci'])->with('emergencyCase')->first();
        
        
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
        $search = $data['ci'] . " "
                 .$data['name'] . " "
                 .$data['last_name'] . " "
                 .$data['phone_number'] . " ";
        
        return $search;
    }
    

}
