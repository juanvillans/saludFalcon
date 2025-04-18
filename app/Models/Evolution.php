<?php

namespace App\Models;

use Carbon\Carbon;
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
        'evolution',    
        'diagnosis',
        'treatment',
        'destiny',
        'is_interconsult',
        'departure_date',
        'departure_hour',
     ];

     public function getFormattedCreatedAtAttribute(){
        
        return Carbon::parse($this->created_at)->format('d M Y h:i A');
    }

    public function getFormattedDepartureDateAttribute(){
        
        return Carbon::parse($this->departure_date)->format('d M Y');
    }

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
