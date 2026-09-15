<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'pan_no',
        'website',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'vendor_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'supplier_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ? ($this->name.' ('.$this->company_name.')') : $this->name;
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
