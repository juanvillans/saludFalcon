<?php  

namespace App\Services;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\RequestUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class RequestUserService
{	
    // public function getUsers($params)
    // {
    //     $users = User::query()
    //     ->when($params['search'] ?? null, function($query, $search){
            
    //         $query->where('search','like','%' . $search . '%');
    //     })
    //     ->when($params['role'] ?? null, function($query, $role) {
    //         $query->whereHas('roles',function($query) use ($role){
                
    //             $query->where('name',$role);
            
    //         });
    //     })
    //     ->when($params['userID'] ?? null, function($query, $userID){
            
    //         $query->where('id',$userID);
    //     })
    //     ->with('specialties', 'roles')
    //     ->get();

    //     return new UserCollection($users);
    // }

    public function createRequest($data)
    {
        $newRequest = RequestUser::create([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "specialty_id" => $data['specialty_id'],
            "phone_number" => $data['phone_number'],
            "search" => $this->generateSearch($data),
        ]);

        return $newRequest;

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

        $usuario->specialties()->detach();
        $usuario->roles()->detach();

        $usuario->delete();

        return 0;
    }

    public function changePassword($data){
        $user = auth()->user();

        if (!Hash::check($data['currentPassword'], $user->password))
            throw new Exception("La contraseña actual es incorrecta", 403);

        if ($data['newPassword'] != $data['confirmPassword'])
            throw new Exception("La nueva contraseña no coincide con la confirmación", 403);

        $user->password = Hash::make($data['newPassword']);
        $user->save();

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
