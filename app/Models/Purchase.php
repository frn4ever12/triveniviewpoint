<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model 
{
    protected $fillable = [
        'title',
        'invoice_no',
        'purchase_date',
        'due_date',
        'purchase_date_bs',
        'due_date_bs',
        'vendor_id',
        'subtotal',
        'vat_percent',
        'vat_amount',
        'discount_percent',
        'discount_amount',
        'total_amount',
        'payment_status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'vendor_id');
    }
}
