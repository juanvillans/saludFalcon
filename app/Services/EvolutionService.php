<?php  

namespace App\Services;

use App\Models\Evolution;
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
            'diagnosis' => null,
            'treatment' => null,
            'destiny' => $case->destiny ?? null,
            'is_interconsult' => false,
            'departure_date' => $case->departure_date,
            'departure_hour' => $case->departure_hour,
        ]);

    }

}
