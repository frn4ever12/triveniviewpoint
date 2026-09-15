<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message','status'
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];
}
