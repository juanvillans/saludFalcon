<?php  

namespace App\Services;

use App\Models\EmergencyCase;
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
        // Create patient if no exists
        if($data['create_user']){

            // $this->createPatientIfNotExists($data['']);
        }

        // $newUser = User::create([
            
        //     "ci" => $data['ci'],
        //     "name" => $data['name'],
        //     "last_name" => $data['last_name'],
        //     "email" => $data['email'],
        //     "password" => Hash::make($data['ci']),
        //     "phone_number" => $data['phone_number'],
        //     "search" => $this->generateSearch($data),
        // ]);

        // $newUser->assignRole($data['role_name']);

        // if($newUser->hasRole('doctor'))
        //     $this->assignSpecialties($newUser,$data);

        // return 0;

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

    private function assignSpecialties($user, $data)
    {

        if(!isset($data['specialties_ids']) || count($data['specialties_ids']) == 0 )
            throw new Exception("El doctor debe tener alguna especialidad seleccionada", 401);
        
        $user->specialties()->sync($data['specialties_ids']);

        return 0;
            
    }

    private function generateSearch($data)
    {
        $search = $data['ci'] . " "
                 .$data['name'] . " "
                 .$data['last_name'] . " "
                 .$data['email'] . " "
                 .$data['phone_number'] . " ";
        
        return $search;
    }
    

}
