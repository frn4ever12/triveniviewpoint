<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'adjustment_no',
        'tenant_id',
        'user_id',
        'adjustment_date',
        'adjustment_type',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'adjustment_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adjustment) {
            $date = now()->format('Ym');
            $lastAdjustment = self::where('adjustment_no', 'like', "ADJ-{$date}%")
                ->orderBy('adjustment_no', 'desc')
                ->first();
            $newNumber = $lastAdjustment ? (int) substr($lastAdjustment->adjustment_no, -4) + 1 : 1;
            $adjustment->adjustment_no = "ADJ-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
