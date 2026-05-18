<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'nombre'   => $this->nombre,
            'codigo'   => $this->codigo,
            'email'    => $this->email,
            'telefono' => $this->telefono,
            'nivel'    => $this->nivel,
            'grado'    => $this->grado,
            'seccion'  => $this->seccion,
            'foto'     => $this->foto ? asset('storage/' . $this->foto) : null,
            'role'     => [
                'id'     => $this->role?->id,
                'nombre' => $this->role?->nombre,
                'slug'   => $this->role?->slug,
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
