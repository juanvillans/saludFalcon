<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUserRequest;
use App\Models\RequestUser;
use App\Services\RequestUserService;
use App\Services\SpecialtyService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RequestUserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialtyService = new SpecialtyService();
        $specialties = $specialtyService->getSpecialties(["status" => 1]);
        return inertia('Register',[

            'data' => [
                'specialties' => $specialties,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestUserRequest $request)
    {
        DB::beginTransaction();

        try 
        {
            $data = $request->all();
            $requestUserService = new RequestUserService();
            $newRequest = $requestUserService->createRequest($data);

            // Enviar al correo
            DB::commit();

            return redirect('/')->with(['message' => 'Solicitud enviada con éxito, la respuesta de la misma será enviada a su correo']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function accept($requestID){

        DB::beginTransaction();
        try 
        {
            $requestUserService = new RequestUserService();
            $requestUserService->accept($requestID);

            DB::commit();
            return redirect('/admin/usuarios?requests=true')->with(['message' => 'Solicitud aceptada con éxito']);
        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }

    }

    public function reject($requestID){

        DB::beginTransaction();
        try 
        {

            $requestUserService = new RequestUserService();
            $requestUserService->reject($requestID);

            return redirect('/admin/usuarios?requests=true')->with(['message' => 'Solicitud rechazada con éxito']);
        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestUser $requestUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestUser $requestUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestUser $requestUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestUser $requestUser)
    {
        //
    }
}
