<?php

namespace App\Models;

use App\Http\Resources\PatientResource;
use Carbon\Carbon;
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
        'current_status_case',
        'departure_date',
        'departure_hour',
        'reason',
        'diagnosis',
        'treatment',
        'destiny',
        'bed_number',
        'last_message_id',

    ];

    public function getFormattedEntryDateAttribute(){

        return Carbon::parse($this->entry_date)->format('d M Y');
    }

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
        return $this->hasMany(Evolution::class)->orderBy('id','desc');
    }

    public function lastMessage(){
        return $this->belongsTo(Messages::class,'last_message_id','id');
    }

    public function messages(){
        return $this->hasMany(Messages::class)->orderBy('id','desc');
    }

    public function statusCase(){
        return $this->belongsTo(StatusCase::class,'current_status_case','id');
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
