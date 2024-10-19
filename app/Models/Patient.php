<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci',
        'name',
        'last_name',
        'phone_number',
        'search'
    ];

    public function emergencyCase(){
        return $this->hasOne(EmergencyCase::class);
    }
}
