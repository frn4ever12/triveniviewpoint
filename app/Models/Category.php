<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;

    protected $table = 'categories';

    protected $fillable = ['name', 'slug', 'is_featured', 'status'];

    protected $casts = [
        'status' => CommonStatusEnum::class,
        'is_featured' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    /** Backward-compatible: old views reference \$menu->dishes */
    public function dishes()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
