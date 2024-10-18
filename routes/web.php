<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SpecialtyController;
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

Route::get('/', [AppController::class, 'index'])->name('landpage');
Route::get('/reservar',[AppointmentController::class, 'index'])->name('reservar-cita');
Route::get('/admin/login', [AppController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [UserController::class, 'login']);
Route::get('/admin/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');  
Route::get('/cita/{service}',[AgendaController::class,'service'])->name('service');


Route::middleware(['auth'])->prefix('admin')->group(function () 
{
    Route::get('/', [AppController::class, 'admin'])->name('admin');
    Route::resource('/usuarios', UserController::class)->middleware('role:admin');
    
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/agenda/cita/{service}',[AgendaController::class,'service'])->name('service');
    Route::put('/agenda/cita/{service}', [AgendaController::class, 'updateService'])->name('update-agenda');
    Route::delete('/agenda/cita/{service}', [AgendaController::class, 'deleteService'])->name('delete-agenda');

    Route::get('/agenda/crear-cita',[AgendaController::class,'createService'])->name('crear-service-form');
    Route::post('/agenda/crear-cita',[AgendaController::class,'storeService'])->name('save-service');


    
    Route::get('/especialidades', [SpecialtyController::class,'index']);
    Route::put('/especialidades/{specialty}', [SpecialtyController::class,'setSpecialty']);




    
});

