<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObjetoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'sometimes|required|string|max:200',
            'tipo'        => 'sometimes|required|in:Personal,Material de Estudio,Tecnológico',
            'fecha'       => 'sometimes|required|date',
            'hora'        => 'sometimes|required|date_format:H:i',
            'lugar'       => 'sometimes|required|string|max:250',
            'descripcion' => 'sometimes|required|string|max:1000',
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.in'          => 'Tipo de objeto inválido.',
            'hora.date_format' => 'El formato de hora debe ser HH:MM.',
            'imagen.max'       => 'La imagen no debe superar los 5MB.',
        ];
    }
}
