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

    public function emergencyCase(){
        return $this->belongsTo(EmergencyCase::class);
    }


}
