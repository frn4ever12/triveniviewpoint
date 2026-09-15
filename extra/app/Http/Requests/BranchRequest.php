<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

            $branchId = $this->route('branch')?->id;
    
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('branches', 'name')->ignore($branchId),
                ],
                'is_featured' => 'nullable|boolean',
                'status' => ['required',new Enum(\App\Enums\CommonStatusEnum::class)],
            ];
        }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required.',
            'name.unique' => 'Branch name already exists.',
          
        ];
    }

}