<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::id();
        $post = Post::where('id', $this->route('post')->id)->where('user_id', $user)->first();
        return isset($post) ;
         
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'nullable|string|max:500|required_without:images',
            'type' => 'nullable|in:Question,History,Encouragement',
            'visibility' => 'nullable|in:public,private,friends',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|required_without:content',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required_without' => 'Content is required if no images are provided.',
            'images.*.required_without' => 'At least one image is required if content is not provided.',
        ];
    }

}
