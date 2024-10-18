<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'last_name',
        'ci',
        'phone',
        'email',
        'other_fields',
        'start',
        'end',
        'date',
        'carbon_date',
        'status',
    ];


    public function service(){

        return $this->belongsTo(Service::class);
    }
}
