<?php

// app/Http/Requests/UserRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->getMethod();
        $userId = $this->route('user')?->id ?? null;

        switch ($method) {
            case 'POST': // Création d'utilisateur
                return [
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'location' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:30',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8|confirmed',
                    'role' => 'nullable|string',
                ];

            case 'PUT':
            case 'PATCH': // Mise à jour d'utilisateur
                return [
                    'first_name' => 'sometimes|required|string|max:100',
                    'last_name' => 'sometimes|required|string|max:100',
                    'location' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:30',
                    'email' => [
                        'sometimes',
                        'required',
                        'email',
                        Rule::unique('users', 'email')->ignore($userId),
                    ],
                    'password' => 'nullable|string|min:8|confirmed',
                    'is_locked' => 'nullable|boolean',
                    'role' => 'nullable|string',
                ];

            default: // Méthode non gérée
                return [];
        }
    }
}
