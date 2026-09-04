<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CommonStatusEnum;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $aboutId = $this->route('about')?->id;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('abouts', 'title')->ignore($aboutId),
            ],
            'description' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                new Enum(CommonStatusEnum::class),
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,gif,webp',
                'max:2048', // 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.unique' => 'This title already exists.',
            'description.required' => 'Description is required.',
            'status.required' => 'Status is required.',
            'image.image' => 'Image must be a valid image file.',
            'image.mimes' => 'Image must be a JPEG, PNG, GIF, or WEBP file.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
