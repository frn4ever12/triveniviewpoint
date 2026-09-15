<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'purchase_date_bs' => ['required'],
            'purchase_date' => ['nullable'],
            'due_date' => ['nullable'],
            'due_date_bs' => ['nullable'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],

            // Financial aggregates
            'subtotal' => ['nullable','numeric','min:0'],
            'vat_percent' => ['nullable','numeric','min:0','max:100'],
            'vat_amount' => ['nullable','numeric','min:0'],
            'discount_percent' => ['nullable','numeric','min:0','max:100'],
            'discount_amount' => ['nullable','numeric','min:0'],
            'total_amount' => ['nullable','numeric','min:0'],
            'payment_status' => ['nullable'],

            // Items (minimal)
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'max:255','exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit_rate' => ['required', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.vat_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.add_to_inventory' => ['nullable', 'boolean'],
            'items.*.batch_number' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'purchase_date.required' => 'Purchase date is required.',
            'title.required' => 'Purchase title is required.',
            'items.required' => 'At least one purchase item is required.',
            'items.*.product_id.required' => 'At least one product must be selected.',
            'items.*.quantity.required' => 'Each item must have a quantity.',
            'items.*.unit_rate.required' => 'Each item must have a rate.',
        ];
    }
}
