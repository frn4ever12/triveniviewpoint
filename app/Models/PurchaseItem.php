<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_id',
        'unit_rate',
        'base_amount',
        'discount_percent',
        'discount_amount',
        'amount_after_discount',
        'vat_percent',
        'vat_amount',
        'total_amount',
        'add_to_inventory',
        'batch_number',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_rate' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'amount_after_discount' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'add_to_inventory' => 'boolean',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
