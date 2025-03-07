<?php  

namespace App\Services;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Evolution;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService
{	
    public function getUsers($params)
    {
        $users = User::query()
        ->where('status',1)
        ->when($params['search'] ?? null, function($query, $search){
            
            $query->where('search','like','%' . $search . '%');
        })
        ->when($params['role'] ?? null, function($query, $role) {
            $query->whereHas('roles',function($query) use ($role){
                
                $query->where('name',$role);
            
            });
        })
        ->when($params['userID'] ?? null, function($query, $userID){
            
            $query->where('id',$userID);
        })
        ->with('specialty', 'roles')
        ->orderBy('id','desc')
        ->get();

        return new UserCollection($users);
    }

    public function createUser($data)
    {
        $newUser = User::create([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "password" => Hash::make($data['ci']),
            "phone_number" => $data['phone_number'],
            "medical_license" => $data['medical_license'],
            "specialty_id" => $data['specialty_id'],
            "search" => $this->generateSearch($data),
        ]);

        $newUser->assignRole($data['role_name']);

        return 0;

    }

    public function updateUser($data, $user)
    {

        $user->update([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "specialty_id" => $data['specialty_id'],
            "medical_license" => $data['medical_license'],
            "search" => $this->generateSearch($data),
        ]);

        method_exists($user, 'revokeRoles') ? $user->revokeRoles(): null;
        
        $user->assignRole($data['role_name']);

        return 0;

    }

    public function deleteUser($usuario)
    {   
        $authUserId = auth()->id();
        $usuario->id == $authUserId ? throw new Exception("No puedes eliminar tu propio usuario", 401) : null;

        $usersDeleted = User::where('status',0)->count();
        $number = $usersDeleted + 1;

        $fields = $usuario->getAttributes();
        unset(
            $fields['id'],
            $fields['status'],
            $fields['search'],
            $fields['specialty_id'],
            $fields['name'],
            $fields['last_name'],
            );
        
        $usuario->update(array_map(function ($value) use ($number){

            return $value .'deleted-'.$number;

        }, $fields));

        $usuario->update(['status' => 0]);

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

    public function getMyEvolutions(){
        $user = auth()->user();

        $evolutions = Evolution::where('user_id',$user->id)
        ->with('emergencyCase', 'status', 'condition', 'area')
        ->orderBy('id','desc')
        ->get();

        return $evolutions;
    }
    
    private function generateSearch($data)
    {
        $search = $data['ci'] . " "
                 .$data['name'] . " "
                 .$data['last_name'] . " "
                 .$data['email'] . " "
                 .$data['phone_number'] . " "
                 .$data['medical_license'] . " "

                 ;
        
        return $search;
    }
    

}
