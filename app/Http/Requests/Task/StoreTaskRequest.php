<?php
namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-\'\.\,\!\?\(\)]+$/' // Lettres, chiffres, ponctuation de base
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'status' => [
                'nullable',
                Rule::in([Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED])
            ],
            'priority' => [
                'nullable',
                Rule::in([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH])
            ],
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today'
            ]
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la tâche est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins :min caractères.',
            'title.max' => 'Le titre ne peut pas dépasser :max caractères.',
            'title.regex' => 'Le titre contient des caractères non autorisés.',

            'description.max' => 'La description ne peut pas dépasser :max caractères.',

            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'priority.in' => 'La priorité sélectionnée n\'est pas valide.',

            'due_date.date' => 'La date d\'échéance n\'est pas valide.',
            'due_date.after_or_equal' => 'La date d\'échéance ne peut pas être dans le passé.',


        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
            'description' => $this->description ? trim($this->description) : null,
            'status' => $this->status ?: Task::STATUS_PENDING,
            'priority' => $this->priority ?: Task::PRIORITY_MEDIUM,
        ]);
    }
}
