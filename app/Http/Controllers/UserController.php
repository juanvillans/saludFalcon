<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Specialty;
use App\Models\User;
use App\Services\LoginService;
use App\Services\SpecialtyService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $specialties = $specialtyService->getSpecialties([]);

        return inertia('Dashboard/Usuarios',[

            'data' => [
                'users' => $users,
                'specialties' => $specialties,
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

    public function update(UserUpdateRequest $request, User $usuario)
    {
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->userService->updateUser($data, $usuario);

            DB::commit();

            return redirect('/admin/usuarios');

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect('/admin/usuarios')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy(User $usuario)
    {

        DB::beginTransaction();

        try 
        {
            $this->userService->deleteUser($usuario);

            DB::commit();

            return redirect('/admin/usuarios')->with(['message' => 'Usuario eliminado con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect('/admin/usuarios')->withErrors(['data' => $e->getMessage()]);
        }       

    }

    public function login(LoginRequest $request)
    {

            $dataUser = ['ci' => $request->ci, 'password' => $request->password];

            if(!$this->loginService->tryLoginOrFail($dataUser))
    			return redirect('/')->withErrors(['data' => 'Datos incorrectos, intente nuevamente']);


            return Inertia::location('/admin');


    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('landpage');
    }

    // public function changePassword(UpdatePasswordRequest $request)
    // {   
    //     $data = [
    //         'oldPassword' => $request->oldPassword,
    //         'newPassword' => $request->newPassword,
    //         'confirmPassword' => $request->confirmPassword
    //     ];

    //     try {
    //         $this->loginService->tryChangePassword($data);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Contraseña cambiada',
    //         ], 200);
            
    //     } catch (GeneralExceptions $e) {
            
    //         if ($e->getCustomCode() == 401) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $e->getMessage()
    //             ], 401);
    //         }
            
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function username()
    {
        return 'ci';
    }

    public function failLogin()
    {
        return 'No tiene los permisos para ingresar a esta url';
    }
}
