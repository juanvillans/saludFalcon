<?php

use App\Http\Controllers\AppController;
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
    Route::get('/', [AppController::class, 'loginForm'])->name('login');
    Route::post('/admin/login', [UserController::class, 'login']);
    Route::get('/registrarse', [RequestUserController::class, 'create'])->name('requestUser.create');
    Route::post('/registrarse', [RequestUserController::class, 'store'])->name('requestUser.store');


});

Route::get('/admin/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');  

Route::middleware(['auth'])->prefix('admin')->group(function () 
{
    Route::get('/', [AppController::class, 'admin'])->name('admin');
    Route::get('/cambiar-contraseña', [UserController::class, 'changePasswordIndex'])->name('change-password-index');
    Route::post('/cambiar-contraseña', [UserController::class, 'changePassword'])->name('change-password');

    Route::resource('/usuarios', UserController::class)->middleware('role_or_permission:admin|read-users');
    Route::post('/usuarios/solicitudes/aceptar/{requestID}', [RequestUserController::class, 'accept'])->name('requestUser.accept');
    Route::post('/usuarios/solicitudes/rechazar/{requestID}', [RequestUserController::class, 'reject'])->name('requestUser.reject');



    Route::get('/pacientes',[EmergencyCaseController::class,'index']);


    Route::get('/historial-medico',[EmergencyCaseController::class,'index']);
    Route::post('/historial-medico',[EmergencyCaseController::class,'store']);

    Route::get('/historial-medico/detalle-paciente/{case}',[EmergencyCaseController::class,'caseDetail']);
    Route::put('/historial-medico/detalle-paciente/{patient}',[EmergencyCaseController::class,'updatePatient']);



    
});

