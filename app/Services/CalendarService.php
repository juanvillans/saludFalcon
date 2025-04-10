<?php  

namespace App\Services;

use App\Http\Resources\CalendarCollection;
use App\Models\Calendar;

class CalendarService
{	


    public function getCalendars($params)
    {
        $calendars = Calendar::query()
        ->where('status',1)
        ->when($params['see_all'] == null,function($query) use ($params){
            $query->where('user_id', auth()->user()->id);
        })
        ->with('user.specialty')
        ->orderBy('id','desc')
        ->get();

        return new CalendarCollection($calendars);
    }
/* 
    public function createUser($data, $photo = true)
    {   
        if($photo)
            $fileName = $this->handlePhoto($data);
        else
            $fileName = $data['photo'];

        $newUser = User::create([
            
            "ci" => $data['ci'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "password" => Hash::make($data['ci']),
            "phone_number" => $data['phone_number'],
            "medical_license" => $data['medical_license'],
            "specialty_id" => $data['specialty_id'],
            "photo" => $fileName,
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

        $this->changePhotoName($usuario,$number);

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
        $userID = $user->id;
        
        Auth::logout();
        
        DB::table('sessions')
                ->where('user_id', $userID)
                ->delete();

        return 0;
        
    }

    public function getMyEvolutions($userID, $params){


        $evolutions = Evolution::where('user_id',$userID)
        ->with('emergencyCase.patient', 'status', 'condition', 'area')
        ->when($params['status'],function($query) use ($params){
            $query->where('status_id', $params['status']);
        })
        ->when($params['condition'],function($query) use ($params){
            $query->where('patient_condition_id', $params['condition']);
        })
        ->when($params['area_id'],function($query) use ($params){
            $query->where('area_id', $params['area_id']);
        })
        ->when(isset($params['start_date']) && isset($params['end_date']),function($query) use ($params){
                    
            $startDateMillis = (int)$params['start_date']; 
            $endDateMillis = (int)$params['end_date']; 
        
            $startDateSeconds = $startDateMillis / 1000;
            $endDateSeconds = $endDateMillis / 1000;

            $startDate = Carbon::createFromTimestamp($startDateSeconds)->startOfDay();
            $endDate = Carbon::createFromTimestamp($endDateSeconds)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->when($params['case_id'],function($query) use ($params){
            $query->where('id', $params['case_id']);
        })
        ->when($params['search'],function($query) use ($params){

            $query->where(function($query) use ($params) {
                
                $query->whereHas('emergencyCase.patient', function($query2) use ($params) {
                    $query2->whereRaw('LOWER(search) LIKE ?', ['%' . strtolower($params['search']) . '%']);
                });

            });
        })
        ->orderBy('id','desc')
        ->paginate(25, ['*'], 'page', request()->get('page',1));

        return $evolutions;
    }
    
    private function generateSearch($data){
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
            $fileName = $data['ci'] . '-profile.webp    ';
            $image = Image::make($data['photo']);
            
            $image->resize(180, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $image->save(storage_path('app/public/users/' . $fileName), 100); // 100 es el nivel de calidad (0-100)
    
            return $fileName;
        }
    
        throw new Exception("La imagen no es valida, intente con otra", 500);    
    
        }
    public function handleUpdatePhoto($data, $user){
        
        if (isset($data['photo']) && $data['photo']->isValid()) {
            $fileName = $user->photo;
            $filePath = storage_path('app/public/users/' . $fileName);

        
            // Verifica si la imagen existe y la elimina
            if (file_exists($filePath)) {
                unlink($filePath); // Elimina el archivo existente
            }
            

            // Crea la nueva imagen
            $image = Image::make($data['photo']);
            
            $image->resize(180, null, function ($constraint) {
                $constraint->aspectRatio();

                $constraint->upsize();
            });

            
            $image->save($filePath, 100); // 100 es el nivel de calidad (0-100)
            
            return $fileName;
        }
        
        throw new Exception("La imagen no es válida, intente con otra", 500);
    }

    private function changePhotoName($user,$number){


        $fileName = $user->photo;
        $newFileName = $fileName . 'deleted-'. $number; 

        if (Storage::exists('public/users/' . $fileName)) {
            
            Storage::move('public/users/' . $fileName, 'public/users/' . $newFileName);
            $user->save();
            return 0;
        }

        return 0;
    }

    public function forgotPassword($ci){
       
        $user = User::where( 'ci', $ci )->first();

                
		if(!isset($user->id))
			  throw new Exception('Cedula incorrecta o invalida',400);

	   $token = Str::random(60);
       $expiresAt = Carbon::now()->addMinutes(20);

       DB::table('password_reset_tokens')->updateOrInsert(
        ['user_id' => $user->id,
         'email' => $user->email,
        ],
        ['token' => $token, 'created_at' => now(), 'expires_at' => $expiresAt]
    );
		

		$dataToSend = ['token' => $token, 'user' => $user];

		Mail::to($user->email)->queue(new PasswordResetTokenMail($dataToSend));

		return $user;
    }

    public function checkRecoverToken($token){
                    
        $record = DB::table('password_reset_tokens')
                    ->where('token', $token)
                    ->first();
    
        if(!isset($record->id))
            return ['message' => 'Token invalido', 'status' => false];

        if(Carbon::now()->gt($record->expires_at))
            return ['message' => 'Token expirado', 'status' => false];
        
        return ['message' => 'OK', 'status' => true];
    }

    public function recoverPassword($data, $token){
        
        $record = DB::table('password_reset_tokens')
                    ->where('token', $token)
                    ->first();

        if(!isset($record->id))
            throw new Exception('Token invalido, solicite uno nuevamente', 400);

        if(Carbon::now()->gt($record->expires_at))
            throw new Exception('Token expirado, solicite uno nuevamente', 400);

        if($data['new_password'] !== $data['confirm_password'])
            throw new Exception('Las contraseñas no coinciden', 400);

        $user = User::where('id', $record->user_id)->first();
        $user->password = Hash::make($data['new_password']);
        $user->save();

    }
    */

}
