<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    protected $fillable = [
        'recipe_id',
        'product_id',
        'quantity',
        'unit_id',
        'wastage_percent',
        'cost_per_unit',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'wastage_percent' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function calculateTotalCost()
    {
        $effectiveQuantity = $this->quantity * (1 + ($this->wastage_percent / 100));
        $this->total_cost = $effectiveQuantity * $this->cost_per_unit;
        $this->save();

        return $this->total_cost;
    }

    public function updateCostFromProduct()
    {
        if ($this->product) {
            $this->cost_per_unit = $this->product->average_cost ?? $this->product->purchase_cost ?? 0;
            $this->calculateTotalCost();
        }
    }
}
