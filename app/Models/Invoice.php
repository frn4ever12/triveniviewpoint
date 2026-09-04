<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
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

        static::creating(function ($invoice) {
            $date = now()->format('Ymd');
            $lastInvoice = self::where('invoice_number', 'like', "INV-{$date}%")
                ->orderBy('invoice_number', 'desc')
                ->first();
            $newNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -3) + 1 : 1;
            $invoice->invoice_number = "INV-{$date}-".str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
