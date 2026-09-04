<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'tenant_id',
        'user_id',
        'transaction_type',
        'reference_type',
        'reference_id',
        'opening_balance',
        'stock_in',
        'stock_out',
        'closing_balance',
        'unit_id',
        'unit_cost',
        'total_value',
        'batch_number',
        'expiry_date',
        'manufacturing_date',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'stock_in' => 'decimal:2',
        'stock_out' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'expiry_date' => 'date',
        'manufacturing_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeForReference($query, $type, $id)
    {
        return $query->where('reference_type', $type)->where('reference_id', $id);
    }
}
