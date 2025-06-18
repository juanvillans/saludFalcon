<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\getPatientRequest;
use App\Services\PatientService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index(getPatientRequest $request){
        try {

            $patientService = new PatientService;
            $patients = $patientService->getPatients($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'OK',
                'data' => $patients
            ]);

        } catch (Exception $e) {

            Log::error('Error en API, obtener pacientes: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->validated(),
            ]);

             $statusCode = method_exists($e, 'getStatusCode')
            ? $e->getStatusCode()
            : (is_int($e->getCode()) && $e->getCode() >= 400 && $e->getCode() < 600
                ? $e->getCode()
                : 500);

            return response()->json([
                'status' => false,
                'message' => 'No se ha podido obtener los datos del paciente, intente más tarde'
            ],$statusCode);

        }


    }

    public function store(CreatePatientRequest $request){


         try {

             $patientService = new PatientService;
             $patientService->create($request->validated());

             return response()->json([
                 'status' => true,
                 'message' => 'OK',
             ]);

         } catch (Exception $e) {

             Log::error('Error en API, crear paciente: ' . $e->getMessage(), [
                 'exception' => $e,
                 'request_data' => $request->validated(),
             ]);

              $statusCode = method_exists($e, 'getStatusCode')
             ? $e->getStatusCode()
             : (is_int($e->getCode()) && $e->getCode() >= 400 && $e->getCode() < 600
                 ? $e->getCode()
                 : 500);

             return response()->json([
                 'status' => false,
                 'message' => 'No se ha podido crear el paciente, intente más tarde'
             ],$statusCode);

         }
    }
}
