<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(User::class,'services','specialty_id','user_id')
        ->withPivot(
            'id',
            'user_id',
            'specialty_id',
            'title',
            'duration_per_appointment',
            'duration_options',
            'availability',
            'adjusted_availability',
            'programming_slot',
            'booked_appointment_settings',
            'description',
            'fields',
        );       
    }
}
