<?php

namespace App\Http\Resources;

use App\Models\Court;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pista = Court::find($this->court_id);
        $monitor = User::find($this->user_id);
        return [
            'id' => $this->id,
            'fechaIni' => $this->dateIni,
            'fechaEnd' => $this->dateEnd,
            'dia' => $this->day,
            'hora' => $this->hour,
            'activity_id' => $this->activity_id,
            'monitor_nombre' => $monitor->name,
            'monitor_apellido' => $monitor->lastname,
            'numPista' => $pista->courtNum,
            'zone_type_name' => $pista->zoneType->name,
            'court_type_name' => $pista->courtType->name,
            'activity' => new ActivityResource($this->whenLoaded('activity')), // N:1
            'clientes' => UserResource::collection($this->whenLoaded('users_nm')), // N:M
        ];
    }
}