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
use Intervention\Image\Facades\Image;

class RequestUserService
{	
    private $NO_HANDLE_PHOTO = false;

    public function createRequest($data)
    {

        $fileName = $this->handlePhoto($data);

        $newRequest = RequestUser::create([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "specialty_id" => $data['specialty_id'],
            "phone_number" => $data['phone_number'],
            "medical_license" => $data['medical_license'],
            "photo" => $fileName,
            "search" => $this->generateSearch($data),
        ]);

        

        return $newRequest;

    }

    public function accept($requestID){
    
        $request = RequestUser::find($requestID);
        $dataToCreate = $request->toArray();
        // $this->movePhoto($dataToCreate);
        // $dataToCreate['role_name'] = 'Doctor';
        
        // $userService = new UserService;
        // $userService->createUser($dataToCreate, $this->NO_HANDLE_PHOTO);

        // $request->delete();

        Mail::to($dataToCreate['email'])->queue(new RequestUserResponse($dataToCreate));  
        
        return 0;

    }

    public function reject($requestID){
        $request = RequestUser::find($requestID);
        $this->deletePhoto($request->photo);
        $request->delete();
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
    
    private function handlePhoto($data){

    if (isset($data['photo']) && $data['photo']->isValid()) {
        $fileName = $data['ci'] . '-profile.webp';
        $image = Image::make($data['photo']);
        
        $image->resize(180, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $image->save(storage_path('app/public/requests/' . $fileName), 100); // 100 es el nivel de calidad (0-100)

        return $fileName;
    }

    throw new Exception("La imagen no es valida, intente con otra", 500);    

    }


    private function movePhoto($data) {

        if (isset($data['ci']) && isset($data['photo'])) {
            $fileName = $data['photo'];
            $sourcePath = storage_path('app/public/requests/' . $fileName);
            
            if (file_exists($sourcePath)) {
                $destinationPath = storage_path('app/public/users/' . $fileName);
                rename($sourcePath, $destinationPath); 
            
            } else {
                throw new Exception("La imagen no existe en la carpeta requests.", 404);
            }
    
            return $fileName;
        }
        
        throw new Exception("La imagen no es válida, intente con otra", 500);
    }

    private function deletePhoto($fileName) {
        $filePath = storage_path('app/public/requests/' . $fileName);
    
        if (file_exists($filePath)) {
            unlink($filePath);
            return 0;
        } else {
            throw new Exception("La imagen no existe en la carpeta requests.", 404);
        }
    }
    

}
