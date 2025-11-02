<?php

namespace App\Http\Requests\Planning;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request pour la requête de comptage (start_date / end_date)
 */
class PlanningCountRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'start_date' => 'required|date|before_or_equal:end_date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ];
  }

  public function dates(): array
  {
    $start = now()->parse($this->input('start_date'))->startOfDay();
    $end = now()->parse($this->input('end_date'))->endOfDay();
    return ['start' => $start, 'end' => $end];
  }
}
