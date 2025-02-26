<?php

namespace App\Models;

use App\Http\Resources\PatientResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class EmergencyCase extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        
        'patient_id',
        'current_patient_condition_id',
        'user_id',
        'area_id',
        'entry_date',
        'entry_hour',
        'current_status',
        'departure_date',
        'departure_hour',
        'reason',
        'diagnosis',
        'treatment',
        'destiny',

    ];

    public function area(){
        return $this->belongsTo(Area::class);
    }


    public function condition(){
        return $this->belongsTo(PatientCondition::class,'current_patient_condition_id','id');
    }


    public function patient(){

        return $this->belongsTo(Patient::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function evolutions(){
        return $this->hasMany(Evolution::class);
    }

    public function statusCase(){
        return $this->belongsTo(StatusCase::class,'current_status','id');
    }

    public function toSearchableArray()
    {   

        return [

            'treatment',
            'diagnosis',
            'reason',
            'users.search',
            'areas.name',
            'patients.search' => '',
        ];
    }
}
