<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'calendar_id',
        'patient_id',
        'day_reserved',
        'time_reserved',
        'appointment_data',
        'status',
    ];
   
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected $casts = [
        'appointment_data' => 'array',
        'day_reserved' => 'date:Y-m-d'
    ];
}
