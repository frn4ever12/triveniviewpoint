<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WebsiteSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'tenant_id',
        'site_name',
        'tagline',
        'contact_email',
        'contact_phone',
        'address',
        'location',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'copyright'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Global scope for tenant isolation (only if tenant_id column exists)
        static::addGlobalScope('tenant', function ($query) {
            if (!Schema::hasColumn('website_settings', 'tenant_id')) {
                return;
            }
            
            $tenantId = null;
            if (auth()->check() && auth()->user()->tenant_id) {
                $tenantId = auth()->user()->tenant_id;
            } elseif (session('current_tenant_id')) {
                $tenantId = session('current_tenant_id');
            }
            
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        });
    }

    /**
     * Get the first website setting for the current tenant or create a new one
     */
    public static function getSettings()
    {
        try {
            // Priority: auth user > session tenant > default (no tenant)
            $tenantId = null;
            
            if (auth()->check() && auth()->user()->tenant_id) {
                $tenantId = auth()->user()->tenant_id;
            } elseif (session('current_tenant_id')) {
                $tenantId = session('current_tenant_id');
            }
            
            // If tenant_id column doesn't exist (production not migrated yet), return first record
            if (!Schema::hasColumn('website_settings', 'tenant_id')) {
                return static::first() ?? static::create([]);
            }
            
            // If no tenant context, return default settings without tenant_id
            if (!$tenantId) {
                return static::withoutGlobalScopes()->whereNull('tenant_id')->first() ?? static::create(['tenant_id' => null]);
            }
            
            return static::withoutGlobalScopes()->where('tenant_id', $tenantId)->first() ?? static::create(['tenant_id' => $tenantId]);
        } catch (\Exception $e) {
            \Log::error('WebsiteSetting::getSettings failed: ' . $e->getMessage());
            // Return first record as fallback
            return static::first() ?? static::create([]);
        }
    }

    /**
     * Register media collections for file uploads
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('favicon')
            ->acceptsMimeTypes(['image/x-icon', 'image/png', 'image/gif'])
            ->singleFile();
    }

    /**
     * Register media conversions for optimized images
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('logo');

        $this->addMediaConversion('favicon')
            ->width(32)
            ->height(32)
            ->performOnCollections('favicon');
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }

    /**
     * Get the favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        return $this->getFirstMediaUrl('favicon');
    }

    /**
     * Get logo media
     */
    public function getLogoMedia()
    {
        return $this->getFirstMedia('logo');
    }

    /**
     * Get favicon media
     */
    public function getFaviconMedia()
    {
        return $this->getFirstMedia('favicon');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
