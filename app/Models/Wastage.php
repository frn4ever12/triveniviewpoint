<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wastage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wastage_no',
        'tenant_id',
        'user_id',
        'wastage_date',
        'reason',
        'total_cost',
        'notes',
        'status',
    ];

    protected $casts = [
        'wastage_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(WastageItem::class);
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

        static::creating(function ($wastage) {
            $date = now()->format('Ym');
            $lastWastage = self::where('wastage_no', 'like', "WST-{$date}%")
                ->orderBy('wastage_no', 'desc')
                ->first();
            $newNumber = $lastWastage ? (int) substr($lastWastage->wastage_no, -4) + 1 : 1;
            $wastage->wastage_no = "WST-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
