<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $expenseId = $this->route('expense')?->id;

        return [
            // Core fields matching migration
            'expense_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('expenses')->ignore($expenseId)
            ],
            'label_id' => ['nullable', 'exists:labels,id'],
            'employee_id' => ['nullable', 'exists:users,id'],
            'supplier_id' => ['nullable', 'exists:vendors,id'],

            // Basic expense info
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'expense_date' => ['nullable'],
            'expense_date_bs' => ['nullable'],
            'payment_date' => ['nullable'],
            'payment_date_bs' => ['nullable'],

            // Financial fields
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],

            // Payment info
            'payment_method' => [
                'nullable',
                'string',
                'in:cash,bank_transfer,card,check,digital_wallet'
            ],
            'payment_reference' => ['nullable', 'string', 'max:255'],

            // Status
            'status' => [
                'required',
                'string',
                'in:pending,approved,rejected,paid,cancelled'
            ],

            // Notes
            'remarks' => ['nullable', 'string', 'max:1000'],

            // System field
            'entry_user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'expense_number.unique' => 'This expense number already exists.',
            'expense_date.required' => 'The expense date is required.',
            'expense_date.date' => 'The expense date must be a valid date.',
            'expense_date.before_or_equal' => 'The expense date cannot be in the future.',

            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be at least ₹0.01.',
            'amount.max' => 'The amount cannot exceed ₹999,999,999.99.',
            'total_amount.required' => 'The amount is required.',
            'total_amount.numeric' => 'The amount must be a valid number.',
            'total_amount.min' => 'The amount must be at least ₹0.01.',
            'total_amount.max' => 'The amount cannot exceed ₹999,999,999.99.',

            'tax_percent.numeric' => 'Tax percentage must be a valid number.',
            'tax_percent.min' => 'Tax percentage cannot be negative.',
            'tax_percent.max' => 'Tax percentage cannot exceed 100%.',

            'tax_amount.numeric' => 'Tax amount must be a valid number.',
            'tax_amount.min' => 'Tax amount cannot be negative.',

            'label_id.exists' => 'The selected expense label is invalid.',
            'employee_id.exists' => 'The selected employee is invalid.',

            'title.required' => 'The expense title is required.',
            'title.max' => 'The title cannot exceed 255 characters.',

            'description.max' => 'The description cannot exceed 5000 characters.',

            'payment_method.in' => 'Please select a valid payment method.',
            'payment_reference.max' => 'Payment reference cannot exceed 255 characters.',

            'status.required' => 'The status is required.',
            'status.in' => 'Please select a valid status.',

            'remarks.max' => 'Remarks cannot exceed 1000 characters.',
        ];
    }

    public function prepareForValidation()
    {
        // Clean and format numeric inputs
        if ($this->has('amount')) {
            $this->merge([
                'amount' => (float) str_replace(',', '', $this->amount)
            ]);
        }

        if ($this->has('tax_percent')) {
            $this->merge([
                'tax_percent' => (float) str_replace(',', '', $this->tax_percent)
            ]);
        }

        if ($this->has('tax_amount')) {
            $this->merge([
                'tax_amount' => (float) str_replace(',', '', $this->tax_amount)
            ]);
        }

        if ($this->filled('tax_percent') && !$this->filled('total_amount')) {
            $amount = (float) $this->amount;
            $taxPercent = (float) $this->tax_percent;
            $this->merge([
                'total_amount' =>(float) ($amount - (round(($amount * $taxPercent) / 100, 2)))
            ]);
        }
    }

    protected function passedValidation()
    {
        // Auto-calculate tax amount if not provided but tax percent is given
        if ($this->filled('tax_percent') && !$this->filled('tax_amount')) {
            $amount = (float) $this->amount;
            $taxPercent = (float) $this->tax_percent;
            $this->merge([
                'tax_amount' => round(($amount * $taxPercent) / 100, 2)
            ]);
        }
    }
}