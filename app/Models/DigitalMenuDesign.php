<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalMenuDesign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'font_family',
        'card_style',
        'show_categories',
        'show_search',
        'show_prices',
        'show_images',
        'layout',
        'custom_css',
    ];

    protected $casts = [
        'show_categories' => 'boolean',
        'show_search' => 'boolean',
        'show_prices' => 'boolean',
        'show_images' => 'boolean',
        'custom_css' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}
