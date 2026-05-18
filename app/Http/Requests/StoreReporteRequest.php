<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_reportante' => 'required|string|max:200',
            'codigo_reportante' => 'nullable|string|max:20',
            'nivel'             => 'nullable|string|max:100',
            'grado'             => 'nullable|string|max:100',
            'seccion'           => 'nullable|string|max:10',
            'correo'            => 'nullable|email|max:100',
            'telefono'          => 'nullable|string|max:15',
            'nombre_objeto'     => 'required|string|max:100',
            'tipo'              => 'required|in:Personal,Material de Estudio,Tecnológico',
            'fecha'             => 'required|date',
            'hora'              => 'required|date_format:H:i',
            'lugar'             => 'required|string|max:250',
            'descripcion'       => 'required|string|max:1000',
            'imagen'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_reportante.required' => 'El nombre del reportante es obligatorio.',
            'nombre_objeto.required'     => 'El nombre del objeto es obligatorio.',
            'tipo.in'                    => 'Tipo de objeto inválido.',
            'hora.date_format'           => 'El formato de hora debe ser HH:MM.',
        ];
    }
}
