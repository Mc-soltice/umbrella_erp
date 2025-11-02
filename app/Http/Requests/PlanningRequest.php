<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanningRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $baseRules = [
      'site_id' => 'required|integer|exists:sites,id',
      'date' => 'required|date|after_or_equal:today',

      // Structure principale des shifts
      'shifts' => 'required|array',
      'shifts.MORNING' => 'required|array',
      'shifts.MORNING.agents' => 'required|array|min:1',
      'shifts.EVENING' => 'required|array',
      'shifts.EVENING.agents' => 'required|array|min:1',

      // Validation des agents par shift
      'shifts.MORNING.agents.*.agent_id' => 'required|integer|exists:agents,id',
      'shifts.MORNING.agents.*.status' => [
        'required',
        'string',
        Rule::in(array_column(AttendanceStatus::cases(), 'value'))
      ],
      'shifts.MORNING.agents.*.reason' => 'nullable|string|required_if:shifts.MORNING.agents.*.status,ABSENT',
      'shifts.MORNING.agents.*.remplacant_id' => 'nullable|integer|exists:agents,id|required_if:shifts.MORNING.agents.*.status,REPLACEMENT',

      'shifts.EVENING.agents.*.agent_id' => 'required|integer|exists:agents,id',
      'shifts.EVENING.agents.*.status' => [
        'required',
        'string',
        Rule::in(array_column(AttendanceStatus::cases(), 'value'))
      ],
      'shifts.EVENING.agents.*.reason' => 'nullable|string|required_if:shifts.EVENING.agents.*.status,ABSENT',
      'shifts.EVENING.agents.*.remplacant_id' => 'nullable|integer|exists:agents,id|required_if:shifts.EVENING.agents.*.status,REPLACEMENT',
    ];

    switch ($this->method()) {
      case 'POST':
        return $baseRules;

      case 'PATCH':
        return array_map(fn($rule) => str_replace('required', 'sometimes', $rule), $baseRules);

      default:
        return [];
    }
  }

  public function messages(): array
  {
    return [
      'site_id.required' => 'Le site est obligatoire.',
      'date.required' => 'La date du planning est obligatoire.',
      'shifts.MORNING.agents.*.agent_id.required' => "Chaque agent du shift du matin doit être défini.",
      'shifts.EVENING.agents.*.agent_id.required' => "Chaque agent du shift du soir doit être défini.",
      'shifts.*.*.status.in' => 'Le statut doit être WORKED, ABSENT, REST ou REPLACEMENT.',
    ];
  }

  public function validatedData(): array
  {
    $validated = $this->validated();

    return [
      'site_id' => (int) $validated['site_id'],
      'date' => $validated['date'],
      'shifts' => [
        'MORNING' => $validated['shifts']['MORNING']['agents'] ?? [],
        'EVENING' => $validated['shifts']['EVENING']['agents'] ?? [],
      ],
    ];
  }
}
