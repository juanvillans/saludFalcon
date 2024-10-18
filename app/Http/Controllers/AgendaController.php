<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\AgendaService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{   
    private AgendaService $agendaService;
    private $params = [];

    public function __construct()
    {
        $this->agendaService = new AgendaService;
    }

    public function index()
    {
        $specialties = $this->agendaService->getSpecialties();
        
        return inertia('Dashboard/Agenda',[
            'data' => [
                'specialties' => $specialties,
            ]
        ]);
    }

    public function service(Request $request, Service $service)
    {
        $dataToCreateService = $this->agendaService->getDataToCreateService();   
        $dataOfService = $this->agendaService->getServiceDetails($service);   
        $calendar = $this->agendaService->getCalendar($service, $request->all());
        
        return inertia('Dashboard/Citas',[
            
            'data' => [
                'dataToCreateService' => $dataToCreateService,
            ],
            'formDatabase' => $dataOfService,
            'calendar' => $calendar,
            
        ]);

    }

    public function createService(Request $request){

        $dataToCreateService = $this->agendaService->getDataToCreateService();   
        $calendar = $this->agendaService->getCalendar(null, $request->all());

        return inertia('Dashboard/Citas',[
            'data' => [
                'dataToCreateService' => $dataToCreateService,
            ],
            'formDatabase' => null,
            'calendar' => $calendar,
        ]);
    }

    public function storeService(CreateServiceRequest $serviceData){

        DB::beginTransaction();

        try {

            $serviceCreated = $this->agendaService->storeService($serviceData->all());
            
            DB::commit();
            
            return redirect('/admin/agenda/cita/'.$serviceCreated->id)->with(['message' => 'Cita creada con éxito']);
        
        } catch (\Throwable $e) {
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }


    }

    public function updateService(UpdateServiceRequest $request, Service $service){
        
        DB::beginTransaction();

        try 
        {
            $data = $request->all();

            $this->agendaService->updateService($data, $service);

            DB::commit();

            return redirect()->back()->with(['message' => 'Cita actualizada con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }
    }

    public function destroyService(Service $service){

        DB::beginTransaction();

        try 
        {

            $this->agendaService->deleteService($service);

            DB::commit();

            return redirect()->back()->with(['message' => 'Cita eliminada con éxito']);

        }
        catch (\Throwable $e)
        {   
            
            DB::rollback();
            
            return redirect()->back()->withErrors(['data' => $e->getMessage()]);
        }

    }

    
}
