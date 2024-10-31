<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\EmergencyCaseController;
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

});

Route::get('/admin/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');  

Route::middleware(['auth'])->prefix('admin')->group(function () 
{
    Route::get('/', [AppController::class, 'admin'])->name('admin');
    Route::get('/cambiar-contraseña', [UserController::class, 'changePasswordIndex'])->name('change-password-index');
    Route::post('/cambiar-contraseña', [UserController::class, 'changePassword'])->name('change-password');

    Route::resource('/usuarios', UserController::class)->middleware('role_or_permission:admin|read-users');

    Route::get('/historial-medico',[EmergencyCaseController::class,'index']);
    Route::post('/historial-medico',[EmergencyCaseController::class,'store']);

    Route::get('/historial-medico/detalle-paciente/{patient}',[EmergencyCaseController::class,'patientDetail']);
    Route::put('/historial-medico/detalle-paciente/{patient}',[EmergencyCaseController::class,'updatePatient']);



    
});

