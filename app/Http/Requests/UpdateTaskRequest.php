<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|min:1',
            'description' => 'sometimes|string|min:3',
            'difficulty' => 'sometimes|in:xxs,xs,s,m,l,xl,xxl',
            'priority' => 'sometimes|in:xxs,xs,s,m,l,xl,xxl',
            'deadline' => 'sometimes|nullable|date',
            'done' => 'sometimes|boolean',
            'streaks' => 'sometimes|integer|min:0',

            'frequency' => 'sometimes|array',
            'frequency.*' => 'in:OneTime,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',

            'category_id' => 'sometimes|integer|min:1|exists:categories,id',

        ];
    }
}
