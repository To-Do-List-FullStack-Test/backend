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
            'full_name.required' => 'Le nom complet est obligatoire.',
            'full_name.min' => 'Le nom doit contenir au moins :min caractères.',
            'full_name.max' => 'Le nom ne peut pas dépasser :max caractères.',
            'full_name.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',

            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',


            'address.min' => 'L\'adresse doit contenir au moins :min caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser :max caractères.',

            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format: jpeg, jpg, png ou gif.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            'image.dimensions' => 'L\'image doit avoir une taille entre 100x100 et 1000x1000 pixels.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
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
