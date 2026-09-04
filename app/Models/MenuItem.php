<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class MenuItem extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'category_id',
        'description', 'ingredients',
        'price', 'cost_price', 'vat_percent',
        'final_price', 'discount_type', 'discount_value', 'discount_amount',
        'preparation_time', 'spice_level',
        'is_featured', 'is_vegetarian', 'is_gluten_free', 'is_halal',
        'status',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
        'is_featured' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_halal' => 'boolean',
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('image');
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('image');
    }

    public function getImageThumbUrlAttribute()
    {
        return $this->getFirstMediaUrl('image', 'thumb');
    }

    public function getCurrentPriceAttribute()
    {
        return $this->final_price ?? $this->price ?? 0;
    }

    public function getOriginalPriceAttribute()
    {
        return $this->price ?? 0;
    }

    /**
     * Backward-compatible: old views reference $dish->menu_id
     */
    public function getMenuIdAttribute()
    {
        return $this->category_id;
    }

    // ── Relationships ──────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Backward-compatible alias: old views reference $dish->menu
     */
    public function menu()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Financial Helpers ──────────────────────────────────────────

    public function calculateDiscountAmount()
    {
        if (! $this->discount_type || ! $this->discount_value) {
            $this->discount_amount = 0;

            return 0;
        }

        $basePrice = $this->price ?? 0;

        if ($this->discount_type === 'percentage') {
            $this->discount_amount = ($basePrice * $this->discount_value) / 100;
        } else {
            $this->discount_amount = $this->discount_value;
        }

        return $this->discount_amount;
    }

    public function calculateFinalPrice()
    {
        $basePrice = $this->price ?? 0;
        $discountAmount = $this->calculateDiscountAmount();
        $this->final_price = $basePrice - $discountAmount;

        return $this->final_price;
    }

    public function getDiscountedPrice()
    {
        // For consistency: just return the stored final_price or price
        // The discount is already applied when final_price was calculated
        return $this->final_price ?? $this->price ?? 0;
    }
}
