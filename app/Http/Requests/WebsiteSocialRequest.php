<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteSocialRequest extends FormRequest
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
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'facebook_url.url' => 'Facebook URL must be a valid URL.',
            'facebook_url.max' => 'Facebook URL may not be greater than 255 characters.',
            'twitter_url.url' => 'Twitter URL must be a valid URL.',
            'twitter_url.max' => 'Twitter URL may not be greater than 255 characters.',
            'instagram_url.url' => 'Instagram URL must be a valid URL.',
            'instagram_url.max' => 'Instagram URL may not be greater than 255 characters.',
            'linkedin_url.url' => 'LinkedIn URL must be a valid URL.',
            'linkedin_url.max' => 'LinkedIn URL may not be greater than 255 characters.',
            'youtube_url.url' => 'YouTube URL must be a valid URL.',
            'youtube_url.max' => 'YouTube URL may not be greater than 255 characters.',
        ];
    }
}
