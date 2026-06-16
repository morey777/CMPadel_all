<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // this->metodo_delCourt
            'numPista' => $this->courtNum,
            'tipo_zona' => new ZoneTypeResource($this->whenLoaded('zoneType')), // N:1
            'tipo_pista' => new CourtTypeResource($this->whenLoaded('courtType')), // N:1
            'usuarios' => UsersResource::collection($this->whenLoaded('users')), // N:M Colección
        ];
    }
}
