<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EmergencyCaseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RequestUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => ['guest']], function () {
    
    // Welcome login
    Route::get('/', [AppController::class, 'loginForm'])->name('login');

    
    // Post Login
    Route::post('/admin/login', [UserController::class, 'login']);
    
    // Register new user request 
    Route::get('/registrarse', [RequestUserController::class, 'create'])->name('requestUser.create');
    // Post register new user request 
    Route::post('/registrarse', [RequestUserController::class, 'store'])->name('requestUser.store');
    
    // Forgot Password
    Route::post('/olvidar-contraseña', [UserController::class, 'forgotPassword'])->name('forgotPassword');
    Route::get('/recuperar-contraseña/{token}', [UserController::class, 'checkRecoverToken'])->name('recoverPassword');
    Route::post('/recuperar-contraseña/{token}', [UserController::class, 'recoverPassword'])->name('recoverPassword.post');

});

Route::get('/admin/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');  

Route::middleware(['auth'])->prefix('admin')->group(function () 
{
    // Dashboard
    Route::get('/', [AppController::class, 'admin'])->name('admin');

    // My Profile
    Route::get('/perfil/{userID}', [UserController::class, 'myProfile'])->name('profile');
    Route::post('/perfil/{user}', [UserController::class, 'update'])->name('profileUpdate');
    Route::delete('/perfil/{user}', [UserController::class, 'destroy'])->name('profileDelete');
    Route::post('/perfil/picture/{user}', [UserController::class, 'updateProfilePicture'])->name('profilePictureUpdate');
    
    // Cases paginate from my profile
    Route::get('/perfil/json/{userID}', [UserController::class, 'myProfilePaginate'])->name('perfil');


   
    // Change password
    Route::get('/cambiar-contraseña', [UserController::class, 'changePasswordIndex'])->name('change-password-index');
    // Post change password
    Route::post('/cambiar-contraseña', [UserController::class, 'changePassword'])->name('change-password');

    // Users CRUD
    Route::get('/usuarios', [UserController::class, 'index'])->middleware('role_or_permission:admin|read-users');
    Route::post('/usuarios', [UserController::class, 'store'])->middleware('role_or_permission:admin|create-users');

    
    // Accept user requests 
    Route::post('/usuarios/solicitudes/aceptar/{requestID}', [RequestUserController::class, 'accept'])->name('requestUser.accept');
    // Reject user requests 
    Route::post('/usuarios/solicitudes/rechazar/{requestID}', [RequestUserController::class, 'reject'])->name('requestUser.reject');


    // Cases 
    Route::get('/casos',[EmergencyCaseController::class,'index'])->middleware('permission:read-cases');
    Route::post('/casos',[EmergencyCaseController::class,'store'])->middleware('permission:create-cases');
    Route::get('/casos/detalle-caso/{case}',[EmergencyCaseController::class,'caseDetail'])->middleware('permission:read-cases')->name('caseDetail');
    Route::post('/casos/detalle-caso/{case}/evolution',[EmergencyCaseController::class,'addEvolution'])->middleware('permission:create-cases');
    Route::post('/casos/detalle-caso/{case}/interconsult',[EmergencyCaseController::class,'addInterConsult'])->middleware('permission:create-cases');



    // Searchers
    Route::get('/historial-medico',[EmergencyCaseController::class,'searchPatient']);
    Route::get('/historial-medico/doctor',[UserController::class,'searchDoctor']);
    Route::get('/general-data',[AppController::class,'generalData']);

    // Update Patient Data
    Route::put('/historial-medico/detalle-paciente/{patient}',[EmergencyCaseController::class,'updatePatient']);


    // Agenda
    Route::get('/agenda', [CalendarController::class, 'index'])->name('agenda');


    
});

