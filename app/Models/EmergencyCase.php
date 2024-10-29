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
        'cases',
        'current_status',
        'search',
    ];

    public function patient(){

        return $this->belongsTo(Patient::class);
    }

    public function toSearchableArray()
    {   

        return [
            'cases' => '',
            'search' => '',
            'patients.search' => '',
            'patients.name' => '',
            'patients.ci' => '',
            'patients.last_name' => '',
            'patients.phone_number' => '',
            'patients.sex' => '',
            'patients.date_birth' => '',
        ];
    }
}
