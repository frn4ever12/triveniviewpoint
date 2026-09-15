<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SubscriptionPlan extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'trial_days',
        'max_users',
        'max_menu_items',
        'max_orders_per_month',
        'is_active',
        'is_popular',
        'features',
        'modules',
        'sort_order',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'features' => 'array',
        'modules' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('monthly_price');
    }

    public function hasFeature(string $featureCode): bool
    {
        return $this->planFeatures()->where('code', $featureCode)->where('is_enabled', true)->exists();
    }

    public function getFeatureValue(string $featureCode)
    {
        $feature = $this->planFeatures()->where('code', $featureCode)->first();
        return $feature ? $feature->value : null;
    }
}
