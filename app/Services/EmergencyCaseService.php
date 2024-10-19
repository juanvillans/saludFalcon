<?php  

namespace App\Services;

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

        return $cases;
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
            'cases' => json_encode($data['cases'])
        ]);

        return 0;

    }


    public function updateUser($data, $user)
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

    public function deleteUser($usuario)
    {   
        $authUserId = auth()->id();
        $usuario->id == $authUserId ? throw new Exception("No puedes eliminar tu propio usuario", 401) : null;

        $usuario->services()->delete();
        $usuario->specialties()->detach();
        $usuario->roles()->detach();

        $usuario->delete();

        return 0;
    }

   private function createPatient($data){
        $newPatient = Patient::create([
            'ci' => $data['ci'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'phone_number' => $data['phone_number'],
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
