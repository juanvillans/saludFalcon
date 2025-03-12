<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    protected $fillable = 
    [    
        'ci',
        'name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'specialty_id',
        'medical_license',
        'status',
        'photo',
        'search',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'search',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // public function services()
    // {
    //     return $this->hasMany(Service::class);
    // }
    

}
