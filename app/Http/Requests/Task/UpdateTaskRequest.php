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
            'title.required' => 'The task title is required.',
            'title.min' => 'The title must contain at least :min characters.',
            'title.max' => 'The title cannot exceed :max characters.',
            'title.regex' => 'The title contains unauthorized characters.',

            'description.max' => 'The description cannot exceed :max characters.',

            'status.in' => 'The selected status is not valid.',
            'priority.in' => 'The selected priority is not valid.',

            'due_date.date' => 'The due date is not valid.',
            'due_date.after_or_equal' => 'The due date cannot be in the past.',
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
