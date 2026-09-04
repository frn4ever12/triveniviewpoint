<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }
}
