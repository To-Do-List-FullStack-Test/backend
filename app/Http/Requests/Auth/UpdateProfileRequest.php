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
            'full_name.required' => 'Le nom complet est obligatoire.',
            'full_name.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone_number.regex' => 'Le numéro de téléphone n\'est pas valide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
