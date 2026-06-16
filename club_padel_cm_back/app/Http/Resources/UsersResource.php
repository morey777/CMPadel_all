<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // UsersResource: es el M:N de court_user
        return [
            'dia' => $this->pivot->day,
            'hora' => $this->pivot->hour,
            'duracion' => $this->pivot->duration,
            'precio' => $this->pivot->price,
            'user_id' => $this->pivot->user_id,
        ];
    }
}