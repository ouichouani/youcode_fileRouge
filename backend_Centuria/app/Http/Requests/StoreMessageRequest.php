<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'message' => ['required', 'string' , 'max:2000'],
            'receiver_id' => ['nullable', 'exists:users,id'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('receiver_id') && $this->has('group_id')) {
                $validator->errors()->add('receiver_id', 'You cannot provide both receiver_id and group_id.');
            }else if (!$this->has('receiver_id') && !$this->has('group_id')) {
                $validator->errors()->add('receiver_id', 'You must provide either receiver_id or group_id.');
            }
        });
    }
}
