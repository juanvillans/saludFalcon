<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'duration_per_appointment',
        'duration_options',
        'availability',
        'adjusted_availability',
        'programming_slot',
        'booked_appointment_settings',
        'fields'
    ];

    protected $casts = [
        'status' => 'boolean',
        'duration_options' => 'array',
        'availability' => 'array',
        'adjusted_availability' => 'array',
        'programming_slot' => 'array',
        'booked_appointment_settings' => 'array',
        'fields' => 'array',
    ];

    public function user(){

        return $this->belongsTo(User::class);
    
    }

    public function getStatusNameAttribute()
    {
        return $this->status ? 'Available' : 'Not Available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', true);
    }

}
