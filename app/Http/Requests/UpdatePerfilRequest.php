<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'nombre'   => 'sometimes|required|string|max:200',
            'codigo'   => 'nullable|string|max:20',
            'email'    => 'sometimes|required|email|max:100|unique:users,email,' . $userId,
            'telefono' => 'nullable|string|max:15',
            'nivel'    => 'nullable|string|max:100',
            'grado'    => 'nullable|string|max:100',
            'seccion'  => 'nullable|string|max:10',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required'  => 'El correo es obligatorio.',
            'email.email'     => 'El formato del correo no es válido.',
            'email.unique'    => 'Este correo ya está registrado.',
            'foto.image'      => 'El archivo debe ser una imagen.',
            'foto.max'        => 'La foto no debe superar los 5MB.',
        ];
    }
}
