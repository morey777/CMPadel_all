<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Training;
use App\Models\Court;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'dni',
        'phone',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function name(): Attribute
    {
        return Attribute::make(
            get: function ($value) { return ucfirst($value); }, // Devuelve el nombre con la primera en mayúsculas
            // set: function ($value) { return strtolower($value); }  // Guarda el nombre en minúsculas
            set: function ($value) { return ucfirst($value); }
        );
    }

    public function lastname(): Attribute
    {
        return Attribute::make(
            get: function ($value) { return ucfirst($value); }, // Devuelve el apellido con la primera en mayúsculas
            // set: function ($value) { return strtolower($value); }  // Guarda el apellido en minúsculas
            set: function ($value) { return ucfirst($value); }
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: function ($value) { return strtolower($value); }, // Devuelve el email en minúsculas
            set: function ($value) { return strtolower($value); }  // Guarda el email en minúsculas
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones entre tablas:
    public function role()
    {
        return $this->belongsTo(Role::class); // N:1
    }

    public function trainings()
    {
        return $this->hasMany(Training::class); // 1:N
    }

    public function trainings_nm()
    {
        return $this->belongsToMany(Training::class)->withPivot('created_at', 'updated_at'); // N:M
    }

    public function courts()
    {
        return $this->belongsToMany(Court::class)->withPivot('day', 'hour', 'duration', 'price', 'created_at', 'updated_at'); // N:M
    }

    // Compruebo si ese user, es un admin
    public function isAdmin(): bool
    {
        return $this->role_id == Role::where('name', 'admin')->first()->id;
    }
}
