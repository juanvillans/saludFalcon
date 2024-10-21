<?php

namespace App\Models;

use App\Http\Resources\PatientResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'cases',
        'current_status',
    ];

    public function patient(){

        return $this->belongsTo(Patient::class);
    }
}
