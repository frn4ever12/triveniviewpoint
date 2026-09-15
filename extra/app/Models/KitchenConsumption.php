<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KitchenConsumption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'consumption_no',
        'tenant_id',
        'user_id',
        'consumption_date',
        'kitchen_department',
        'reason',
        'total_cost',
        'notes',
        'status',
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(KitchenConsumptionItem::class);
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

        static::creating(function ($consumption) {
            $date = now()->format('Ym');
            $lastConsumption = self::where('consumption_no', 'like', "KCN-{$date}%")
                ->orderBy('consumption_no', 'desc')
                ->first();
            $newNumber = $lastConsumption ? (int) substr($lastConsumption->consumption_no, -4) + 1 : 1;
            $consumption->consumption_no = "KCN-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
