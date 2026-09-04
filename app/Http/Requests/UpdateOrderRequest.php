<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatusEnum;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'sometimes|in:' . implode(',', OrderStatusEnum::values()),
            'payment_status' => 'sometimes|in:pending,partial,paid,failed,refunded,cancelled',
            'notes' => 'sometimes|nullable|string|max:500',
        ];
    }
}