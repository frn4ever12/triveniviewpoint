<?php

namespace App\Http\Requests;

use App\Enums\TableStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tableId = $this->route('table')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tables', 'name')->ignore($tableId),
            ],
           
            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],
            'table_type' => [
                'nullable',
                Rule::in(['regular','vip','outdoor','private']),
            ],
            'status' => ['required',new Enum(TableStatusEnum::class)],
            'location' => ['nullable','string','max:255'],
            'floor' => ['nullable','string','max:100'],
            'section' => ['nullable','string','max:100'],

            // Features
            'has_air_conditioning' => ['nullable','boolean'],
            'has_tv' => ['nullable','boolean'],
            'has_wifi' => ['nullable','boolean'],
            'is_smoking_allowed' => ['nullable','boolean'],
            'is_wheelchair_accessible' => ['nullable','boolean'],

           
            // Timestamps
            'reserved_until' => ['nullable','date'],

           

            // Notes
            'notes' => ['nullable','string'],
           
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Table name is required.',
            'name.unique' => 'Table name already exists.',
           
            'table_number.required' => 'Table number is required.',
            'table_number.unique' => 'Table number already exists.',
            'capacity.required' => 'Number of guests is required.',
            'capacity.min' => 'At least 1 guest is required.',
            'status.required' => 'Status is required.',
        ];
    }
}
