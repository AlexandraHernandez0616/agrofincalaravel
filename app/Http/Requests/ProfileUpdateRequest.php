<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id_usuario ?? $this->user()->getKey();

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuarios', 'documento')->ignore($userId, 'id_usuario'),
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuarios', 'username')->ignore($userId, 'id_usuario'),
            ],
        ];
    }

    /**
     * Custom attribute names for validation.
     */
    public function attributes(): array
    {
        return [
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'documento' => 'documento de identidad',
            'telefono' => 'teléfono de contacto',
            'username' => 'nombre de usuario',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'documento.unique' => 'Este número de documento ya está registrado por otro usuario.',
            'username.unique' => 'Este nombre de usuario ya se encuentra en uso.',
        ];
    }
}

