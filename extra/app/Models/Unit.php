<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['tenant_id', 'name'];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
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
}
