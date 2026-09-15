<?php

namespace App\View\Composers;

use App\Services\WebsiteSettingService;
use Illuminate\View\View;

class WebsiteSettingComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('websiteSettings', WebsiteSettingService::getSettings());
    }
}
