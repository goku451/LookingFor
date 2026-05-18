<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_reportante' => 'sometimes|required|string|max:200',
            'codigo_reportante' => 'nullable|string|max:20',
            'nivel'             => 'nullable|string|max:100',
            'grado'             => 'nullable|string|max:100',
            'seccion'           => 'nullable|string|max:10',
            'correo'            => 'nullable|email|max:100',
            'telefono'          => 'nullable|string|max:15',
            'nombre_objeto'     => 'sometimes|required|string|max:100',
            'tipo'              => 'sometimes|required|in:Personal,Material de Estudio,Tecnológico',
            'fecha'             => 'sometimes|required|date',
            'hora'              => 'sometimes|required|date_format:H:i',
            'lugar'             => 'sometimes|required|string|max:250',
            'descripcion'       => 'sometimes|required|string|max:1000',
            'imagen'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
