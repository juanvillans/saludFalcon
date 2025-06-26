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
                'status_id' => $case->current_status_case,
                'evolution' => 'Sin descripción',
                'diagnosis' => $case->diagnosis,
                'treatment' => $case->treatment,
                'destiny' => $case->destiny ?? null,
                'is_interconsult' => false,
                'departure_date' => $case->departure_date ?? null,
                'departure_hour' => $case->departure_hour ?? null,
                'bed_number' => $case->bed_number,

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
            'bed_number' => $case->bed_number,
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


        $data['area_id'] = $data['area_id'] ?? $case->area_id;
        $newEvolution = Evolution::create($data);

        $updateData = [
        'current_patient_condition_id' => $newEvolution->patient_condition_id,
        'area_id' => $newEvolution->area_id,
        'current_status' => $newEvolution->status_id,
        'departure_date' => $newEvolution->departure_date,
        'departure_hour' => $newEvolution->departure_hour,
        'diagnosis' => $newEvolution->diagnosis,
        'treatment' => $newEvolution->treatment,
        'destiny' => $newEvolution->destiny,
        'bed_number' => $newEvolution->bed_number ?? $case->bed_number,
    ];

        $case->update($updateData);

        return $newEvolution;

    }

    public function addInterConsult($case, $data){

        $evolution = $this->addEvolution($case, $data);
        $evolution->is_interconsult = true;
        $evolution->save();

        return 0;
    }

}
