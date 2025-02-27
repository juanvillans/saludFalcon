<?php  

namespace App\Services;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Mail\RequestUserResponse;
use App\Models\RequestUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            "medical_license" => $data['medical_license'],
            "search" => $this->generateSearch($data),
        ]);

        return $newRequest;

    }

    public function accept($requestID){
    
        $request = RequestUser::find($requestID);
        $dataToCreate = $request->toArray();
        $dataToCreate['role_name'] = 'Doctor';
        
        $userService = new UserService;
        $userService->createUser($dataToCreate);

        $request->delete();

        // Mail::to($dataToCreate['email'])->send(new RequestUserResponse());  
        // Enviar Correo
        return 0;

    }

    public function reject($requestID){
        RequestUser::find($requestID)->delete();
        return 0;
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
