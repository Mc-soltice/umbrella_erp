<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateCandidature extends FormRequest
{
  public function authorize(): bool
  {
    return true; // sécurisé via middleware role
  }

  public function rules(): array
  {

    return [
      'site_id' => 'required|exists:sites,id',
      'candidature_id' => 'required|exists:candidatures,id',
    ];
  }
}
