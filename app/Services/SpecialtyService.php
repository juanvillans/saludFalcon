<?php  

namespace App\Services;

use App\Models\Specialty;

class SpecialtyService
{	
	private Specialty $specialtyModel;


    public function __construct()
    {
        $this->specialtyModel = new Specialty;
    }

    public function getSpecialties($params)
    {
        $specialties = Specialty::query()
        ->when($params['search']??null,function($query, $search){
            
            $query->where('name','like','%' . $search . '%');
        })
        ->when($params['status']??null,function($query, $status){
            
            $query->where('status', $status);
        })
        ->get();

        return $specialties;
    }


    public function setSpecialtyStatus($specialty)
    {
        $specialty->update(['status' => 1]);

        return 0;
    }

    public function getSpecialtiesWithoutCalendar(){

        $specialties = Specialty::doesntHave('calendar')->get();

        return $specialties;

    }
    

}
