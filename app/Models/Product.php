<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'unit_id',
        'purchase_unit_id',
        'consumption_unit_id',
        'conversion_ratio',
        'description',
        'group',
        'item_type',
        'default_price',
        'purchase_cost',
        'average_cost',
        'opening_stock_quantity',
        'opening_stock_rate',
        'multiple_unit',
        'minimum_stock',
        'reorder_level',
        'maximum_stock',
        'supplier_id',
        'storage_location',
        'status',
        'tenant_id',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
        'average_cost' => 'decimal:2',
        'opening_stock_quantity' => 'decimal:2',
        'opening_stock_rate' => 'decimal:2',
        'conversion_ratio' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'multiple_unit' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    public function consumptionUnit()
    {
        return $this->belongsTo(Unit::class, 'consumption_unit_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function stockUsage()
    {
        return $this->hasOne(StockUsage::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getCurrentStockAttribute()
    {
        $stockIn = $this->inventoryTransactions()->sum('stock_in');
        $stockOut = $this->inventoryTransactions()->sum('stock_out');
        return $stockIn - $stockOut + $this->opening_stock_quantity;
    }

    public function scopeActive($query)
    {
        if (Schema::hasColumn('products', 'status')) {
            return $query->where('status', 'active');
        }
        return $query;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('reorder_level', '>=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->whereColumn('reorder_level', '<=', 0);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function updateAverageCost($newCost, $quantity)
    {
        $currentStock = $this->current_stock;
        $currentAverageCost = $this->average_cost ?? 0;

        if ($currentStock > 0) {
            $totalValue = ($currentStock * $currentAverageCost) + ($quantity * $newCost);
            $this->average_cost = $totalValue / ($currentStock + $quantity);
        } else {
            $this->average_cost = $newCost;
        }

        $this->save();
    }
}
