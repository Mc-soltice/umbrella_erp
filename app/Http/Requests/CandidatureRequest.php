<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sécurisé via middleware role
    }

    public function rules(): array
    {
        $method = $this->getMethod();
        $candidatureId = $this->route('candidature')?->id ?? null;

        switch ($method) {
            case 'POST': // Création
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name'  => 'required|string|max:255',
                    'location'   => 'required|string|max:255',
                    'phone'      => 'nullable|string|max:20',
                    'email'      => 'required|email|unique:candidatures,email',
                ];

            case 'PUT':
            case 'PATCH': // Mise à jour
                return [
                    'first_name' => 'sometimes|required|string|max:255',
                    'last_name'  => 'sometimes|required|string|max:255',
                    'location'   => 'sometimes|required|string|max:255',
                    'phone'      => 'sometimes|required|string|min:9|max:9',
                    'email'      => [
                        'sometimes',
                        'required',
                        'email',
                        Rule::unique('candidatures', 'email')->ignore($candidatureId),
                    ],
                ];

            default:
                return [];
        }
    }
}
