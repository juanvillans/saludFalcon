<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\DoctorCollection;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\EvolutionCollection;
use App\Http\Resources\UserResource;
use App\Models\Evolution;
use App\Models\RequestUser;
use App\Models\Specialty;
use App\Models\User;
use App\Services\LoginService;
use App\Services\SpecialtyService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    private LoginService $loginService;
    private UserService $userService;
    private $params = [];
    public function __construct()
    {
        $this->loginService = new LoginService;
        $this->userService = new UserService;
    }

    public function index(Request $request)
    {
        $this->params = [
            'search' => $request->input('search'),
            'role' => $request->input('role'),
        ];     
        $specialtyService = new SpecialtyService();

        $users = $this->userService->getUsers($this->params);
        $requests = null;
        if($request->has('requests'))
            $requests = RequestUser::with('specialty')->get(); 
        

        return inertia('Dashboard/Users',[

            'data' => [
                'users' => $users,
                'requests' => $requests,
            ]
        ]);
    }

    public function store(UserRequest $request)
    {   
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->userService->createUser($data);

            DB::commit();

            return redirect('/admin/usuarios')->with(['message' => 'Usuario creado con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect('/admin/usuarios')->withErrors(['data' => $e->getMessage()]);
        }


    }

    public function update(UserUpdateRequest $request, User $user)
    {
        DB::beginTransaction();

        try 
        {
            $data = $request->all();


            $this->userService->updateUser($data, $user);

            DB::commit();

            return redirect('/admin/perfil/'.$user->id);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect('/admin/usuarios')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {

        DB::beginTransaction();

        try 
        {
            $this->userService->deleteUser($user);

            DB::commit();

            return redirect('/admin/usuarios')->with(['message' => 'Usuario eliminado con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            Log::info($e->getMessage());            
            return redirect('/admin/perfil/'.$user->id)->withErrors(['data' => $e->getMessage()]);
        }       

    }

    public function login(LoginRequest $request)
    {

            $dataUser = ['ci' => $request->ci, 'password' => $request->password];

            if(!$this->loginService->tryLoginOrFail($dataUser))
    			return redirect('/')->withErrors(['data' => 'Datos incorrectos, intente nuevamente']);


            return Inertia::location('/admin/casos');


    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function changePasswordIndex(){
        
        return inertia('Dashboard/CambiarContraseña');
    }

    public function changePassword(ChangePasswordRequest $request)
    {   
        $data = [
            'currentPassword' => $request->current_password,
            'newPassword' => $request->new_password,
            'confirmPassword' => $request->confirm_password
        ];

        try {

            $this->userService->changePassword($data);

            return redirect()->back()->with(['message' => 'Contraseña actualizada con éxito']);
            
        } catch (\Throwable $e) {
            
            DB::rollback();
            
            return redirect('/admin/cambiar-contraseña')->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function username()
    {
        return 'ci';
    }

    public function failLogin()
    {
        return 'No tiene los permisos para ingresar a esta url';
    }

    public function searchDoctor(Request $request){

        $doctors = User::whereHas('roles',function ($query){
            $query->where('name','doctor');
        })
        ->where('status',1)
        ->whereRaw('LOWER(search) LIKE ?', ['%' . strtolower($request->search) . '%'])->get();

        $doctors = new DoctorCollection($doctors);    

        
        

        return response()->json(['doctors' => $doctors]);
    }

    public function myProfile(Request $request, $userID){

        
        $evolutions = $this->userService->getMyEvolutions($userID);
        $nroEvolutions = Evolution::where('user_id',$userID)->where('is_interconsult',0)->count();
        $nroInter = Evolution::where('user_id',$userID)->where('is_interconsult',1)->count();
        
        $user = User::where('id',$userID)->with('specialty','roles')->first();
    
        return inertia('Dashboard/Profile',[

            'data' => [
                'evolutions' => new EvolutionCollection($evolutions),
                'nroEvol' => $nroEvolutions,
                'nroInter' => $nroInter,
                'user' => new UserResource($user),
            ]
        ]);
    }

    public function updateProfilePicture(Request $request, User $user){
        
        $data = $request->all();
        $fileName = $this->userService->handleUpdatePhoto($data, $user);
        $user->update(['photo' => $fileName]);

        return redirect('/admin/perfil/'.$user->id);

    }

    public function myProfilePaginate(Request $request, $userID){
        $evolutions = $this->userService->getMyEvolutions($userID);
        
        return response()->json(['data' => new EvolutionCollection($evolutions)]);
    }

    
}
