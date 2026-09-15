<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ensure user is authenticated
    }

    public function rules(): array
    {
        $rules = [
            'order_type' => 'nullable|in:dine_in,delivery,pickup',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.size' => 'nullable|numeric|min:0.5|max:1',
        ];
    
        if ($this->input('order_type') === 'dine_in') {
            $rules['table_id'] = 'required|exists:tables,id';
        } else {
            $rules['customer_name'] = 'nullable|string|max:100';
            $rules['customer_phone'] = 'nullable|string|max:20';
            
            if ($this->input('order_type') === 'delivery') {
                $rules['delivery_address'] = 'nullable|string|max:500';
            }
        }
    
        return $rules;
    }
}