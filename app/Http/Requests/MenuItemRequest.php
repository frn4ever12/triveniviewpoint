<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $menuItemId = $this->route('menu_item')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_items', 'name')->ignore($menuItemId),
            ],
            'description' => 'nullable|string|max:1000',
            'price' => ['required', 'numeric', 'min:0'],
            'final_price' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => 'nullable|in:amount,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'preparation_time' => 'nullable|string|max:50',
            'is_vegetarian' => 'nullable|boolean',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_featured' => 'nullable|boolean',
            'status' => ['required', new Enum(\App\Enums\CommonStatusEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Item name is required.',
            'name.unique' => 'Item name already exists.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'discount_type.in' => 'Discount type must be either amount or percentage.',
            'discount_value.numeric' => 'Discount value must be a valid number.',
            'discount_value.min' => 'Discount value must be at least 0.',
            'image.image' => 'Image must be a valid image file.',
            'image.max' => 'Image size must not exceed 2MB.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',
        ];
    }
}
