<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\EmergencyCaseService;
use App\Http\Requests\GetEmergencyCasesRequest;

class WaitingRoomController extends Controller
{
    private EmergencyCaseService $emergencyCaseService;


    public function __construct()
    {
        $this->emergencyCaseService = new EmergencyCaseService;

    }

    public function index(GetEmergencyCasesRequest $request){

        $emergencyCases = $this->emergencyCaseService->getCases($request->validated());


       return inertia('WaitingRoom',[
        'data' => $emergencyCases,
        'filters' => array_filter($request->validatedParams(), function ($value) {
                return $value !== null;
            })
       ]);
    }
}
