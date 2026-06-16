<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Court;

class ZoneType extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relaciones entre tablas:
    public function courts()
    {
        return $this->hasMany(Court::class); // 1:N 
    }
}
