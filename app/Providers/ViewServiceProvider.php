<?php

namespace App\Providers;

use App\Services\WebsiteSettingService;
use App\View\Composers\WebsiteSettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            View::share([
                'siteName' => WebsiteSettingService::getSiteName(),
                'copyright' => WebsiteSettingService::getCopyright(),
                'siteTagline' => WebsiteSettingService::getTagline(),
                'logoUrl' => WebsiteSettingService::getLogoUrl(),
                'faviconUrl' => WebsiteSettingService::getFaviconUrl(),
                'contactEmail' => WebsiteSettingService::getContactEmail(),
                'contactPhone' => WebsiteSettingService::getContactPhone(),
                'address' => WebsiteSettingService::getAddress(),
                'socialUrls' => WebsiteSettingService::getSocialUrls(),
                'seoSettings' => WebsiteSettingService::getSeoSettings(),
                'location' => WebsiteSettingService::getlocation(),
            ]);
        } catch (\Exception $e) {
            // Fallback to defaults if settings fail to load
            View::share([
                'siteName' => 'dmcrestro',
                'copyright' => date('Y'),
                'siteTagline' => '',
                'logoUrl' => null,
                'faviconUrl' => null,
                'contactEmail' => null,
                'contactPhone' => null,
                'address' => null,
                'socialUrls' => [],
                'seoSettings' => [],
                'location' => '',
            ]);
            \Log::error('Failed to load website settings: ' . $e->getMessage());
        }
    }
}
