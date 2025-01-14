<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestUser extends Model
{
    use HasFactory;

    protected $filable = [
        'ci',
        'name',
        'last_name',
        'email',
        'phone_number',
        'specialties_requested',
        'search',
    ];
}
