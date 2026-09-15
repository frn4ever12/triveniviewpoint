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
        
    }
}
