<?php

namespace App\Http\Requests;

use App\Enums\CommonStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:16'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'role' => ['required', Rule::exists('roles', 'name')],
            'branch' => ['required', Rule::in(['kathmandu', 'pokhara', 'butwal', 'chitwan'])],
            'password' => $isUpdate
                ? ['nullable', 'string', 'min:8', 'confirmed']
                : ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', new Enum(CommonStatusEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'phone.required' => 'Phone is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists.',
        ];
    }
}
