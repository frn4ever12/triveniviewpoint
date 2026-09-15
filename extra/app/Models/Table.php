<?php

namespace App\Models;
use App\Enums\TableStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Table extends Model 
{

    protected $fillable = [
        'tenant_id',
        'name',
        'capacity',
        'table_type',
        'location',
        'floor',
        'section',

        // Features
        'has_air_conditioning',
        'has_tv',
        'has_wifi',
        'is_smoking_allowed',
        'is_wheelchair_accessible',



        // Management
        'status',
        'reserved_until',


        // Notes & Maintenance
        'notes',
    ];
    

    protected $casts = [
        'status' => TableStatusEnum::class,
        'last_occupied_at' => 'datetime',
        'reserved_until' => 'datetime',
        'last_maintenance_date' => 'datetime',
        'next_maintenance_date' => 'datetime',
    ];

   

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', TableStatusEnum::AVAILABLE);
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

    public function orders(){
        return $this->hasMany(Order::class);
    }
    

   
}