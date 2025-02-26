<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolution extends Model
{
    use HasFactory;

    protected $fillable = [

        'emergency_case_id',
        'user_id',
        'area_id',
        'patient_condition_id',
        'status_id',
        'diagnosis',
        'treatment',
        'destiny',
        'is_interconsult',
     ];

    public function emergencyCase(){
        return $this->belongsTo(EmergencyCase::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }


    public function condition(){
        return $this->belongsTo(PatientCondition::class,'patient_condition_id','id');
    }

    public function status(){
        return $this->belongsTo(StatusCase::class,'status_id','id');
    }


}
