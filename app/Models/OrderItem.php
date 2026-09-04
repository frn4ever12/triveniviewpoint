<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'kot_id',
        'is_kitchen_item',
        'kot_printed_at',
        'quantity',
        'unit_price',
        'total',
        'size',
        'status',
        'notes',
        'modifiers',
    ];

    protected $casts = [
        'modifiers' => 'array',
        'kot_printed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    /** Backward-compatible: old views reference \$item->dish */
    public function dish()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function kot()
    {
        return $this->belongsTo(Kot::class);
    }
}
