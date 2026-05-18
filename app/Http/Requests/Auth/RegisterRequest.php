<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'   => 'required|string|max:200',
            'codigo'   => 'nullable|string|max:20',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|max:50|confirmed',
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
            'nombre.required'     => 'El nombre es obligatorio.',
            'email.required'      => 'El correo es obligatorio.',
            'email.email'         => 'El formato del correo no es válido.',
            'email.unique'        => 'Este correo ya está registrado.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'  => 'La confirmación de contraseña no coincide.',
            'foto.image'          => 'El archivo debe ser una imagen.',
            'foto.max'            => 'La foto no debe superar los 5MB.',
        ];
    }
}
