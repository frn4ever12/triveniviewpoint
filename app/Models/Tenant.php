<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'pan_no',
        'domain',
        'logo',
        'status',
        'trial_ends_at',
        'vat_percent',
        'service_charge_percent',
        'default_payment_method',
        'auto_print_receipt',
        'receipt_footer',
        'enable_kot',
        'enable_table_reservation',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'auto_print_receipt' => 'boolean',
        'enable_kot' => 'boolean',
        'enable_table_reservation' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    public function getLogoUrlAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription && $this->subscription->isActive();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getEnabledModulesAttribute()
    {
        if ($this->subscription && $this->subscription->plan) {
            return $this->subscription->plan->modules ?? [];
        }
        return [];
    }

    public function hasModule(string $module): bool
    {
        return in_array($module, $this->enabled_modules);
    }
}
