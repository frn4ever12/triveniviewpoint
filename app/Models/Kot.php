<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kot extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'kot_number',
        'status',
        'sent_at',
        'remarks',
    ];

    protected $casts = [
        'status' => 'string',
        'sent_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}