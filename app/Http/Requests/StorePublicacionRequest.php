<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha'       => 'required|date',
            'comentarios' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required'       => 'La fecha es obligatoria.',
            'fecha.date'           => 'El formato de fecha no es válido.',
            'comentarios.required' => 'Los comentarios son obligatorios.',
            'comentarios.max'      => 'Los comentarios no deben superar los 2000 caracteres.',
        ];
    }
}
