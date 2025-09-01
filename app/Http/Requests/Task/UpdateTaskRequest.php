<?php
namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        return auth()->check() && $task && $task->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-\'\.\,\!\?\(\)]+$/'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'status' => [
                'sometimes',
                Rule::in([Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED])
            ],
            'priority' => [
                'sometimes',
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
            'title.regex' => 'Le titre contient des caractères non autorisés.',
            'description.max' => 'La description ne peut pas dépasser :max caractères.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'priority.in' => 'La priorité sélectionnée n\'est pas valide.',
            'due_date.after_or_equal' => 'La date d\'échéance ne peut pas être dans le passé.',

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->title ? trim($this->title) : $this->title,
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}
