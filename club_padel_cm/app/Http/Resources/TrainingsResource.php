<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fechaIni' => $this->dateIni,
            'fechaEnd' => $this->dateEnd,
            'dia' => $this->day,
            'hora' => $this->hour,
            'activity' => new ActivityResource($this->whenLoaded('activity')), // N:1
            'pista' => new CourtResource($this->whenLoaded('court')), // N:1
            'clientes' => UserResource::collection($this->whenLoaded('users_nm')), // N:M
        ];
    }
}
