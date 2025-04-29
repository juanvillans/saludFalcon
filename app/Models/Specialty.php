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

    public function users(){

        return $this->hasMany(User::class);
    }

    public function calendar(){

        return $this->hasOne(Calendar::class);

    }
}
