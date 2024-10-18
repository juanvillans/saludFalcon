<?php  

namespace App\Services;

use App\Http\Resources\AgendaCollection;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class AgendaService
{	
    public function getSpecialties()
    {   
        $userID = auth()->id();
        $user = User::find($userID);
        $role = $user->getRoleNames()->first();
        
        $specialties = Specialty::with('doctors')
        ->when($role == 'doctor', function($query) use ($userID){

            $query->whereHas('services',function($query) use ($userID) {
                $query->where('doctor_id',$userID);
            });
        })
        ->where('status',1)
        ->get();

        return new AgendaCollection($specialties);
        
    }


    public function getServiceDetails($service)
    {
        $service->load('user','specialty');

        return new ServiceResource($service);

    }

    public function getDataToCreateService(){
        
        $userService = new UserService;
        $params = $this->generateParamsAccordingToRoleUser();
        
        
        $doctors = $userService->getUsers($params);

        $response = [

            'doctors' => new UserCollection($doctors),
        ];

        return $response;
    }

    public function getCalendar($service, $params){
        
        $startOfWeek = Carbon::now()->startOfDay()->startOfWeek();
        if(isset($params['to']) && isset($params['startWeek']) ) {

            if($params['to'] == 'next'){
                $startOfWeek = Carbon::parse($params['startWeek'])->addWeek()->startOfWeek();
            }

            if($params['to'] == 'prev'){
                $startOfWeek = Carbon::parse($params['startWeek'])->subWeek()->startOfWeek();
            }

        }

        $calendar = [
            'headerInfo' => [
                'month_year' => Carbon::now()->format('F \d\e Y'),
                'today' => Carbon::now()->toISOString(),
            ],
            'calendar' => [
                'mon' => [
                    'current_date' => $startOfWeek->copy()->toISOString(),
                    'appointments' => []
                ],
                'tue' => [
                    'current_date' => $startOfWeek->copy()->addDays(1)->toISOString(),
                    'appointments' => []
                ],
                'wed' => [
                    'current_date' => $startOfWeek->copy()->addDays(2)->toISOString(),
                    'appointments' => []
                ],
                'thu' => [
                    'current_date' => $startOfWeek->copy()->addDays(3)->toISOString(),
                    'appointments' => []
                ],
                'fri' => [
                    'current_date' => $startOfWeek->copy()->addDays(4)->toISOString(),
                    'appointments' => []
                ],
                'sat' => [
                    'current_date' => $startOfWeek->copy()->addDays(5)->toISOString(),
                    'appointments' => []
                ],
                'sun' => [
                    'current_date' => $startOfWeek->copy()->addDays(6)->toISOString(),
                    'appointments' => []
                ],

            ]
        ];

        if($service == null)
            return $calendar;
        
        return $this->insertAppointments($service,$calendar);
    }

    public function storeService($serviceData){
        
        $newService = Service::create([
            'user_id' => $serviceData['doctor_id'],
            'specialty_id' => $serviceData['specialty_id'],
            'title' => $serviceData['title'],
            'duration_per_appointment' => $serviceData['duration_per_appointment'],
            'duration_options' => json_encode($serviceData['duration_options']),
            'availability' => json_encode($serviceData['availability']),
            'adjusted_availability' => json_encode($serviceData['adjusted_availability'] ?? []) ,
            'programming_slot' => json_encode($serviceData['programming_slot']),
            'booked_appointment_settings' => json_encode($serviceData['booked_appointment_settings']),
            'description' => $serviceData['description'],
            'fields' => json_encode($serviceData['fields']),
        ]);

        return $newService;
    }

    public function updateService($serviceData, $service){

        $service->update([
            'user_id' => $serviceData['doctor_id'],
            'specialty_id' => $serviceData['specialty_id'],
            'title' => $serviceData['title'],
            'duration_per_appointment' => $serviceData['duration_per_appointment'],
            'duration_options' => json_encode($serviceData['duration_options']),
            'availability' => json_encode($serviceData['availability']),
            'adjusted_availability' => json_encode($serviceData['adjusted_availability']),
            'programming_slot' => json_encode($serviceData['programming_slot']),
            'booked_appointment_settings' => json_encode($serviceData['booked_appointment_settings']),
            'description' => $serviceData['description'],
            'fields' => json_encode($serviceData['fields']),
        ]);

        return 0;
    }

    public function deleteService($service){
        
        $service->delete();

        return 0;
    }

    private function insertAppointments($service,$calendar){

        $startDate = Carbon::parse($calendar['calendar']['mon']['current_date']);
        $endDate = Carbon::parse($calendar['calendar']['sun']['current_date']);

            $service->load(['appointments' => function($query) use ($startDate, $endDate){
                
                $query->where('status','OPEN')
                      ->whereBetween('carbon_date',[$startDate, $endDate]);
            }]);

        foreach($service->appointments as $appointment){
            
            $day = strtolower(Carbon::parse($appointment->date)->format('D'));
            
            if($calendar['calendar'][$day]['current_date'] == $appointment->date){
                array_push($calendar['calendar'][$day]['appointments'],$appointment);
            }

        }

        return $calendar;

    }

    private function generateParamsAccordingToRoleUser(){

        $user = auth()->user();
        $role = $user->getRoleNames()->first();
        $params = null;

        if($role == 'admin'){
            $params = [
                'role' => 'doctor',
            ];
        }
        else{

            $params = [
                'role' => 'doctor',
                'userID' => $user->id,
            ];
        }

        return $params;
    }



    
    

}
