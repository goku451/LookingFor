<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'nombre_reportante'  => $this->nombre_reportante,
            'codigo_reportante'  => $this->codigo_reportante,
            'nivel'              => $this->nivel,
            'grado'              => $this->grado,
            'seccion'            => $this->seccion,
            'correo'             => $this->correo,
            'telefono'           => $this->telefono,
            'nombre_objeto'      => $this->nombre_objeto,
            'tipo'               => $this->tipo,
            'fecha'              => $this->fecha?->format('Y-m-d'),
            'hora'               => $this->hora,
            'lugar'              => $this->lugar,
            'descripcion'        => $this->descripcion,
            'imagen'             => $this->imagen ? asset('storage/' . $this->imagen) : null,
            'user'               => new UserResource($this->whenLoaded('user')),
            'created_at'         => $this->created_at?->toDateTimeString(),
            'updated_at'         => $this->updated_at?->toDateTimeString(),
        ];
    }
}
