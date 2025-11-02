<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sécurisé via middleware role: responsable
    }

    public function rules(): array
    {
        $method = $this->getMethod();
        $siteId = $this->route('site')?->id ?? null;

        switch ($method) {
            case 'POST': // Création
                return [
                    'name' => 'required|string|max:255',
                    'location' => 'required|string|max:255',
                    'responsable_id' => 'required|exists:users,id',
                ];

            case 'PUT':
            case 'PATCH': // Mise à jour
                return [
                    'name' => [
                        'sometimes',
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('sites', 'name')->ignore($siteId),
                    ],
                    'location' => 'sometimes|required|string|max:255',
                    'responsable_id' => 'sometimes|required|exists:users,id',
                ];

            default:
                return [];
        }
    }
}
