<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjetoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'tipo'        => $this->tipo,
            'fecha'       => $this->fecha?->format('Y-m-d'),
            'hora'        => $this->hora,
            'lugar'       => $this->lugar,
            'descripcion' => $this->descripcion,
            'imagen'      => $this->imagen ? asset('storage/' . $this->imagen) : null,
            'user'        => new UserResource($this->whenLoaded('user')),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
