<?php

namespace App\Services;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;

class WebsiteSettingService
{
    /**
     * Get website settings with caching
     */
    public static function getSettings(): WebsiteSetting
    {
        return Cache::remember('website_settings', 3600, function () {
            return WebsiteSetting::getSettings();
        });
    }

    /**
     * Clear website settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('website_settings');
    }

    /**
     * Get a specific setting value
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::getSettings();
        return $settings->$key ?? $default;
    }

    /**
     * Update settings and clear cache
     */
    public static function update(array $data): WebsiteSetting
    {
        $settings = WebsiteSetting::getSettings();
        $settings->update($data);
        self::clearCache();
        return $settings;
    }

    /**
     * Get site name
     */
    public static function getSiteName(): string
    {
        return self::get('site_name', 'dmcrestro');
    }
    public static function getCopyright(): string
    {
        return self::get('copyright', '2025');
    }
    public static function getlocation(): string
    {
        return self::get('location', '');
    }

    /**
     * Get site tagline
     */
    public static function getTagline(): string
    {
        return self::get('tagline', '');
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl(): ?string
    {
        $settings = self::getSettings();
        return $settings->getFirstMediaUrl('logo');
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl(): ?string
    {
        $settings = self::getSettings();
        return $settings->getFirstMediaUrl('favicon');
    }

    /**
     * Get logo media object
     */
    public static function getLogoMedia()
    {
        $settings = self::getSettings();
        return $settings->getFirstMedia('logo');
    }

    /**
     * Get favicon media object
     */
    public static function getFaviconMedia()
    {
        $settings = self::getSettings();
        return $settings->getFirstMedia('favicon');
    }

    /**
     * Get contact email
     */
    public static function getContactEmail(): ?string
    {
        return self::get('contact_email');
    }

    /**
     * Get contact phone
     */
    public static function getContactPhone(): ?string
    {
        return self::get('contact_phone');
    }

    /**
     * Get business address
     */
    public static function getAddress(): ?string
    {
        return self::get('address');
    }

    /**
     * Get social media URLs
     */
    public static function getSocialUrls(): array
    {
        return [
            'facebook' => self::get('facebook_url'),
            'twitter' => self::get('twitter_url'),
            'instagram' => self::get('instagram_url'),
            'linkedin' => self::get('linkedin_url'),
            'youtube' => self::get('youtube_url'),
        ];
    }

    /**
     * Get SEO settings
     */
    public static function getSeoSettings(): array
    {
        return [
            'meta_title' => self::get('meta_title'),
            'meta_description' => self::get('meta_description'),
            'meta_keywords' => self::get('meta_keywords'),
        ];
    }
}
