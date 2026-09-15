<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WastageItem extends Model
{
    protected $fillable = [
        'wastage_id',
        'product_id',
        'quantity',
        'unit_id',
        'batch_number',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function wastage()
    {
        return $this->belongsTo(Wastage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
