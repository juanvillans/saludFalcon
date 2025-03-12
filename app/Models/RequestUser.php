<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci',
        'name',
        'last_name',
        'email',
        'phone_number',
        'specialty_id',
        'medical_license',
        'photo',    
        'search',
    ];

    public function specialty(){
        return $this->belongsTo(Specialty::class);
    }
}
