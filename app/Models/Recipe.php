<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_item_id',
        'tenant_id',
        'recipe_cost',
        'selling_price',
        'food_cost_percent',
        'gross_profit',
        'gross_margin_percent',
        'preparation_time',
        'instructions',
        'status',
    ];

    protected $casts = [
        'recipe_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'food_cost_percent' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'gross_margin_percent' => 'decimal:2',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function calculateRecipeCost()
    {
        $totalCost = 0;
        foreach ($this->items as $item) {
            $totalCost += $item->calculateTotalCost();
        }
        $this->recipe_cost = $totalCost;
        $this->save();

        return $totalCost;
    }

    public function calculateFoodCostPercent()
    {
        if ($this->selling_price > 0) {
            $this->food_cost_percent = ($this->recipe_cost / $this->selling_price) * 100;
            $this->gross_profit = $this->selling_price - $this->recipe_cost;
            $this->gross_margin_percent = ($this->gross_profit / $this->selling_price) * 100;
            $this->save();
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
