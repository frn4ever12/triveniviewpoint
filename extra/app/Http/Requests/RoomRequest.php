<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')->ignore($roomId),
            ],
            'room_type' => ['required', 'string', Rule::in(['standard', 'deluxe', 'suite', 'penthouse', 'family', 'single', 'double', 'twin', 'dormitory', 'other'])],
            'floor' => ['nullable', 'string', 'max:50'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'bed_count' => ['required', 'integer', 'min:0', 'max:20'],
            'bed_type' => ['nullable', 'string', Rule::in(['single', 'double', 'queen', 'king', 'twin', 'bunk', 'sofa', 'floor', 'other'])],

            'has_ac' => ['nullable', 'boolean'],
            'has_tv' => ['nullable', 'boolean'],
            'has_wifi' => ['nullable', 'boolean'],
            'has_minibar' => ['nullable', 'boolean'],
            'has_balcony' => ['nullable', 'boolean'],
            'is_smoking_allowed' => ['nullable', 'boolean'],
            'is_wheelchair_accessible' => ['nullable', 'boolean'],

            'status' => ['required', 'string', Rule::in(['available', 'occupied', 'maintenance', 'reserved'])],
            'description' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'room_number' => 'room number',
            'room_type' => 'room type',
            'price_per_night' => 'price per night',
            'bed_count' => 'bed count',
            'bed_type' => 'bed type',
            'has_ac' => 'air conditioning',
            'has_tv' => 'television',
            'has_wifi' => 'WiFi',
            'has_minibar' => 'minibar',
            'has_balcony' => 'balcony',
            'is_smoking_allowed' => 'smoking allowed',
            'is_wheelchair_accessible' => 'wheelchair accessible',
        ];
    }
}
