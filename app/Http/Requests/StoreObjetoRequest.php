<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObjetoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:200',
            'tipo'        => 'required|in:Personal,Material de Estudio,Tecnológico',
            'fecha'       => 'required|date',
            'hora'        => 'required|date_format:H:i',
            'lugar'       => 'required|string|max:250',
            'descripcion' => 'required|string|max:1000',
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'      => 'El nombre del objeto es obligatorio.',
            'tipo.required'        => 'El tipo de objeto es obligatorio.',
            'tipo.in'              => 'Tipo de objeto inválido. Debe ser: Personal, Material de Estudio o Tecnológico.',
            'fecha.required'       => 'La fecha es obligatoria.',
            'fecha.date'           => 'El formato de fecha no es válido.',
            'hora.required'        => 'La hora es obligatoria.',
            'hora.date_format'     => 'El formato de hora debe ser HH:MM.',
            'lugar.required'       => 'El lugar es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'imagen.image'         => 'El archivo debe ser una imagen.',
            'imagen.max'           => 'La imagen no debe superar los 5MB.',
        ];
    }
}
