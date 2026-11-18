<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
     return true;
    }

    public function rules(): array
    {
      return [
            'project_id' => ['required', 'exists:projects,id'],
            'order' => ['required', 'array'],
            'order.*' => [
                'integer',
                'distinct',
                Rule::exists('tasks', 'id')->where('project_id', $this->input('project_id')),
            ],
        ];
    }
}
