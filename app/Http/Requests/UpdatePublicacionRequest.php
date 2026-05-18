<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha'       => 'sometimes|required|date',
            'comentarios' => 'sometimes|required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.date'      => 'El formato de fecha no es válido.',
            'comentarios.max' => 'Los comentarios no deben superar los 2000 caracteres.',
        ];
    }
}
