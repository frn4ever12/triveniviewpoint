<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'customer_email',
        'subtotal',
        'vat_percent',
        'vat_amount',
        'service_charge',
        'discount_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'paid_amount',
        'tender_amount',
        'change_amount',
        'is_non_chargeable',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'is_non_chargeable' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Global scope for tenant isolation
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
