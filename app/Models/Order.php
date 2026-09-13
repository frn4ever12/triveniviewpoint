<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatusEnum;
use App\Enums\TableStatusEnum;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_no',
        'table_id',
        'waiter_id',
        'entry_user_id',
        'customer_name',
        'customer_phone',
        'customer_session_token',
        'delivery_address',
        'delivery_status',
        'no_of_guests',
        'paid_amount',
        'payment_status',
        'order_type',
        'order_source',
        'kot_group_id',
        'kot_sent_at',
        'status',
        'notes',
        'subtotal',
        'vat_percent',
        'vat_amount',
        'total_amount',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
        'payment_status' => 'string',
        'payment_method' => 'string',
        'order_type' => 'string',
        'paid_at' => 'datetime',
        'kot_sent_at' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class);
    }

    public function entryUser()
    {
        return $this->belongsTo(User::class, 'entry_user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function kots()
    {
        return $this->hasMany(Kot::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function markAsPaid($amount, $payment_method)
    {
        $this->update([
            'paid_amount' => $amount,
            'payment_status' => 'paid',
            'payment_method' => $payment_method,
            'paid_at' => now(),
        ]);
    }

    public function addPartialPayment($amount, $payment_method)
    {
        $this->update([
            'paid_amount' => $this->paid_amount + $amount,
            'payment_status' => $this->paid_amount >= $this->total_amount ? 'paid' : 'partial',
            'payment_method' => $payment_method,
            'paid_at' => $this->paid_at ?? now(),
        ]);
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