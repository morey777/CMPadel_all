<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'telefono' => $this->phone,
            'email' => $this->email,
            'dni' => $this->dni,
            'trainings' => TrainingsResource::collection ($this->whenLoaded('trainings')), // 1:N
        ];
    }
}
