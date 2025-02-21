<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_case_id',
        'evolution',
        'status',
    ];

    public function emergencyCase(){
        return $this->belongsTo(EmergencyCase::class);
    }


}
