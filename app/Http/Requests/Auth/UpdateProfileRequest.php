<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'full_name' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/'
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                "unique:users,email,{$userId}"
            ],
            'phone_number' => [
                'nullable',
                'string',
                'regex:/^(\+|00)?[1-9]\d{1,14}$/',
                'max:20'
            ],
            'address' => [
                'nullable',
                'string',
                'min:5',
                'max:500'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.regex' => 'Name can only contain letters, spaces, hyphens and apostrophes.',
            'email.unique' => 'This email address is already in use.',
            'phone_number.regex' => 'The phone number is not valid.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image cannot exceed 2MB.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
