<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaiterCall extends Model
{
    protected $fillable = [
        'tenant_id',
        'table_id',
        'attended_by',
        'status',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function attendedBy()
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForTable($query, $tableId)
    {
        return $query->where('table_id', $tableId);
    }
}
