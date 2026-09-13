<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

        // Global scope for tenant isolation
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    /**
     * Get the first website setting for the current tenant or create a new one
     */
    public static function getSettings()
    {
        $tenantId = auth()->check() ? auth()->user()->tenant_id : null;
        return static::withoutGlobalScopes()->where('tenant_id', $tenantId)->first() ?? static::create(['tenant_id' => $tenantId]);
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
