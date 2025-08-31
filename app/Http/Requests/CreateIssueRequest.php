<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The issue title is required.',
            'title.max' => 'The title must not exceed 255 characters.',
            'description.required' => 'Please provide a description for the issue.',
            'description.max' => 'The description must not exceed 5000 characters.',
            'status.required' => 'Please select a status.',
            'priority.required' => 'Please select a priority level.',
            'due_date.after' => 'The due date must be in the future.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'issue title',
            'description' => 'issue description',
            'status' => 'status',
            'priority' => 'priority',
            'due_date' => 'due date',
        ];
    }
}
