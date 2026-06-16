<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ZoneType;
use App\Models\CourtType;
use App\Models\Training;

class Court extends Model
{   
    protected $fillable = [
        'courtNum',
        'zone_type_id',
        'court_type_id',
    ];

    // Relaciones entre tablas:
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('day', 'hour', 'duration', 'price', 'created_at', 'updated_at'); // N:M
    }
    
    public function zoneType()
    {
        return $this->belongsTo(ZoneType::class); // N:1
    }

    public function courtType()
    {
        return $this->belongsTo(CourtType::class); // N:1
    }

    public function trainings()
    {
        return $this->hasMany(Training::class); // 1:N 
    }
}
