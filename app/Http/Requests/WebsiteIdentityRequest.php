<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteIdentityRequest extends FormRequest
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
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'favicon_path' => ['nullable', 'image', 'mimes:ico,png,jpg,gif', 'max:1024'],
            'copyright'=>['nullable','max:5']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'site_name.required' => 'Site name is required.',
            'site_name.max' => 'Site name may not be greater than 255 characters.',
            'tagline.max' => 'Tagline may not be greater than 255 characters.',
            'logo_path.image' => 'Logo must be an image file.',
            'logo_path.mimes' => 'Logo must be a file of type: jpeg, png, jpg, gif, webp.',
            'logo_path.max' => 'Logo may not be greater than 2MB.',
            'favicon_path.image' => 'Favicon must be an image file.',
            'favicon_path.mimes' => 'Favicon must be a file of type: ico, png, jpg, gif.',
            'favicon_path.max' => 'Favicon may not be greater than 1MB.',
        ];
    }
}
