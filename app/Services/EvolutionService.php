<?php  

namespace App\Services;

use App\Models\Evolution;
use App\Models\User;
use Exception;

class EvolutionService{

    protected $STATUS_CHARGED = 4;

    public function createEvolutionFromCase($case){

        Evolution::create([
                'emergency_case_id' => $case->id,
                'user_id' => $case->user_id,
                'area_id' => $case->area_id,
                'patient_condition_id' => $case->current_patient_condition_id,
                'status_id' => $case->current_status,
                'evolution' => 'Sin descripción',
                'diagnosis' => $case->diagnosis,
                'treatment' => $case->treatment,
                'destiny' => $case->destiny ?? null,
                'is_interconsult' => false,
                'departure_date' => $case->departure_date ?? null,
                'departure_hour' => $case->departure_hour ?? null,

            ]);

        return 0;

    }

    public function createEvolutionFromCaseButDischarge($case){
            
        Evolution::create([
            'emergency_case_id' => $case->id,
            'user_id' => $case->user_id,
            'status_id' => $this->STATUS_CHARGED,
            'area_id' => $case->area_id,
            'patient_condition_id' => $case->current_patient_condition_id,
            'evolution' => 'Sin descripción',
            'diagnosis' => $case->diagnosis,
            'treatment' => $case->treatment,
            'destiny' => $case->destiny ?? null,
            'is_interconsult' => false,
        ]);

        Evolution::create([
            'emergency_case_id' => $case->id,
            'user_id' => $case->user_id,
            'area_id' => $case->area_id,
            'patient_condition_id' => $case->current_patient_condition_id,
            'status_id' => $case->current_status,
            'evolution' => 'Sin descripción',
            'diagnosis' => null,
            'treatment' => null,
            'destiny' => $case->destiny ?? null,
            'is_interconsult' => false,
            'departure_date' => $case->departure_date,
            'departure_hour' => $case->departure_hour,
        ]);

    }

    public function addEvolution($case, $data){

        $areaID = $this->evalIsSameArea($case, $data);

        $newEvolution = Evolution::create([
            'emergency_case_id' => $case->id,
            'user_id' => auth()->user()->id,
            'area_id' => $areaID,
            'evolution' => $data['evolution'],
            'patient_condition_id' => $data['patient_condition_id'],
            'status_id' => $data['status_id'],
            'diagnosis' => $data['diagnosis'],
            'treatment' => $data['treatment'],
            'destiny' => $data['destiny'] ?? null,
            'is_interconsult' => false,
            'departure_date' => $data['departure_date'] ?? null,
            'departure_hour' => $data['departure_hour'] ?? null,
            ]);

        $case->update([
            'current_patient_condition_id' => $newEvolution->patient_condition_id,
            'area_id' => $newEvolution->area_id,
            'current_status' => $newEvolution->status_id,
            'departure_date' => $newEvolution->departure_date,
            'departure_hour' => $newEvolution->departure_hour,
            'diagnosis' => $newEvolution->diagnosis,
            'treatment' => $newEvolution->treatment,
            'destiny' => $newEvolution->destiny,
        ]);

        return 0;

    }


    public function evalIsSameArea($case, $data){

        if($data['area_id'] != '')  
            return $data['area_id'];
        else
            return $case->area_id;
    }   
}
