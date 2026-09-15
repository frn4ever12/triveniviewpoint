<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'billing_cycle',
        'amount',
        'starts_at',
        'ends_at',
        'next_billing_at',
        'status',
        'payment_method',
        'payment_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'next_billing_at' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || $this->status === 'trialing';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trialing';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function daysRemaining(): int
    {
        if (!$this->ends_at) {
            return 0;
        }
        return max(0, Carbon::now()->diffInDays($this->ends_at, false));
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'trialing']);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere('ends_at', '<', now());
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
