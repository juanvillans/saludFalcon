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
        'municipality_id',
        'parish_id',
        'address',
        'age',
        'email',
        'search',
    ];



    public function toSearchableArray()
    {   

        return [
            'search' => $this->search,
            'name' => $this->name,
            'ci' => $this->ci ,
            'last_name' => $this->last_name ,
            'phone_number' => $this->phone_number ,
            'sex' => $this->sex ,
            'date_birth' => $this->date_birth ,
        ];
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class);
    }

    public function parish(){
        return $this->belongsTo(Parish::class);
    }

    public function emergencyCase(){
        return $this->hasMany(EmergencyCase::class);
    }
}
