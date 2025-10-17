<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sécurisé via middleware role
    }

    public function rules(): array
    {
        $method = $this->getMethod();
        $agentId = $this->route('agent')?->id ?? null;

        switch ($method) {
            case 'POST': // Création
                return [
                    'first_name'    => 'required|string|max:255',
                    'last_name'     => 'required|string|max:255',
                    'location'      => 'required|string|max:255',
                    'phone'         => 'nullable|string|max:20',
                    'email'         => 'required|email|unique:agents,email',
                    'site_id'       => 'required|exists:sites,id',
                    'password'      => 'required|string|min:6|confirmed',
                ];

            case 'PUT':
            case 'PATCH': // Mise à jour
                return [
                    'first_name'    => 'sometimes|required|string|max:255',
                    'last_name'     => 'sometimes|required|string|max:255',
                    'location'      => 'sometimes|required|string|max:255',
                    'phone'         => 'nullable|string|max:20',
                    'email'         => [
                        'sometimes',
                        'required',
                        'email',
                        Rule::unique('agents', 'email')->ignore($agentId),
                    ],
                    'site_id'       => 'sometimes|required|exists:sites,id',
                    'password'      => 'nullable|string|min:6|confirmed',
                ];

            default:
                return [];
        }
    }
}
