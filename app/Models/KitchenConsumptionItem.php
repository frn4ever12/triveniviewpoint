<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenConsumptionItem extends Model
{
    protected $fillable = [
        'kitchen_consumption_id',
        'product_id',
        'quantity',
        'unit_id',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function kitchenConsumption()
    {
        return $this->belongsTo(KitchenConsumption::class);
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
