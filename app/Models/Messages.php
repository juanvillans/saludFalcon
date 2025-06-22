<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [

        'emergency_case_id',
        'user_id',
        'body',
     ];

     public function emergencyCase(){
        return $this->belongsTo(EmergencyCase::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
