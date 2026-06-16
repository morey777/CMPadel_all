<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->code,
            'nombre' => $this->name,
            'descripcion' => $this->description,
            'personasMax' => $this->peopleMax,
            'duracion' => $this->duration,
            'precio' => $this->price,
            'trainings' => TrainingResource::collection($this->whenLoaded('trainings')), // 1:N
        ];
    }
}
