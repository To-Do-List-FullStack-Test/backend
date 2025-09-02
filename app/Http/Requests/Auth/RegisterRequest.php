<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],
            'phone_number' => [
                'nullable',
                'string',
                
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
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
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
            'full_name.min' => 'Name must contain at least :min characters.',
            'full_name.max' => 'Name cannot exceed :max characters.',
            'full_name.regex' => 'Name can only contain letters, spaces, hyphens and apostrophes.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',


            'address.min' => 'Address must contain at least :min characters.',
            'address.max' => 'Address cannot exceed :max characters.',

            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be in format: jpeg, jpg, png or gif.',
            'image.max' => 'The image cannot exceed 2MB.',
            'image.dimensions' => 'The image must have a size between 100x100 and 1000x1000 pixels.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => trim($this->full_name),
            'email' => strtolower(trim($this->email)),
            'phone_number' => $this->phone_number ? trim($this->phone_number) : null,
            'address' => $this->address ? trim($this->address) : null,
        ]);
    }
}
