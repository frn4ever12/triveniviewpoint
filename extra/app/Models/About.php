<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CommonStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class About extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title',  'description', 'status'];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('image');
    }
    

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('image');
    }

    

    public function getImageThumbUrlAttribute()
    {
        return $this->getFirstMediaUrl('image', 'thumb');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

   
}
