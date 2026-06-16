<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Court;
use App\Models\Activity;

class Training extends Model
{
    protected $fillable = [
        'dateIni',
        'dateEnd',
        'day',
        'hour',
        'activity_id',
        'user_id',
        'court_id',
    ];

    // Relaciones entre tablas:
    public function user()
    {
        return $this->belongsTo(User::class); // N:1
    }

    public function users_nm()
    {
        return $this->belongsToMany(User::class)->withPivot('created_at', 'updated_at'); // N:M
    }

    public function court()
    {
        return $this->belongsTo(Court::class); // N:1
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class); // N:1
    }
}
