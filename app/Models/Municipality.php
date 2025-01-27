<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';

    protected $fillable = [
        'name',
    ];

    public function patients(){
        return $this->hasMany(Patient::class);
    }

    public function parishes(){
        return $this->hasMany(Parish::class);
    }
}
