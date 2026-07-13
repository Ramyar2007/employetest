<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'task' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1', 'max:999999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'That employee no longer exists.',
            'amount.min' => 'Amount must be at least 1 IQD.',
        ];
    }
}
