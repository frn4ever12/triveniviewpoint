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
        'order_no',
        'table_id',
        'waiter_id',
        'entry_user_id',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'delivery_status',
        'no_of_guests',
        'paid_amount',
        'payment_status',
        'order_type',
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

        static::creating(function ($order) {
            $date = now()->format('Ym');
            $lastOrder = self::where('order_no', 'like', "ORD-{$date}%")
                ->orderBy('order_no', 'desc')
                ->first();
            $newNumber = $lastOrder ? (int) substr($lastOrder->order_no, -4) + 1 : 1;
            $order->order_no = "ORD-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}