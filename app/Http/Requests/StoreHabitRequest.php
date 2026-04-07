<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:1',
            'description' => 'nullable|string|min:3',
            'difficulty' => 'required|in:xxs,xs,s,m,l,xl,xxl',
            'priority' => 'required|in:xxs,xs,s,m,l,xl,xxl',

            'frequency' => 'required|array',
            'frequency.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',

            'category_id' => 'nullable|integer|min:1|exists:categories,id',
        ];
    }

    public function failedAuthorization()
    {
        abort(403, 'Unauthorized');
    }
}
