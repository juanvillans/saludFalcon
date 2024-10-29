<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Patient extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'ci',
        'name',
        'last_name',
        'phone_number',
        'sex',
        'date_birth',
        'search',
    ];

    public function emergencyCase(){
        return $this->hasOne(EmergencyCase::class);
    }
}
