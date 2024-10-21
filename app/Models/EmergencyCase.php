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
        'cases'
    ];

    public function patient(){

        return $this->belongsTo(Patient::class);
    }
}
