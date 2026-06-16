<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Training;

class Activity extends Model
{

    protected $fillable = [
        'code',
        'name',
        'description',
        'peopleMax',
        'duration',
        'price',
    ];

    // Relaciones entre tablas:
    public function trainings()
    {
        return $this->hasMany(Training::class); // 1:N 
    }
}
