<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteContactRequest;
use App\Http\Requests\WebsiteIdentityRequest;
use App\Http\Requests\WebsiteSeoRequest;
use App\Http\Requests\WebsiteSocialRequest;
use App\Models\WebsiteSetting;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    /**
     * Display the website settings edit page.
     */
    public function edit()
    {
        $setting = WebsiteSettingService::getSettings();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return view($view, compact('setting'));
    }

    /**
     * Update site identity settings.
     */
    public function updateIdentity(WebsiteIdentityRequest $request)
    {
        $setting = WebsiteSetting::getSettings();
        
        $data = $request->validated();
        
        if ($request->hasFile('logo_path')) {
            $setting->clearMediaCollection('logo');
            
            $setting->addMediaFromRequest('logo_path')
                ->toMediaCollection('logo');
        }
        
        if ($request->hasFile('favicon_path')) {
            $setting->clearMediaCollection('favicon');
            
            $setting->addMediaFromRequest('favicon_path')
                ->toMediaCollection('favicon');
        }
        
        $setting->update($data);
        WebsiteSettingService::clearCache();
        
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return redirect()->route($redirectRoute)
            ->with('success', 'Site identity settings updated successfully!');
    }

    /**
     * Update contact information settings.
     */
    public function updateContact(WebsiteContactRequest $request)
    {
        $setting = WebsiteSetting::getSettings();
        $setting->update($request->validated());
        WebsiteSettingService::clearCache();
        
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return redirect()->route($redirectRoute)
            ->with('success', 'Contact information updated successfully!');
    }

    /**
     * Update social media settings.
     */
    public function updateSocial(WebsiteSocialRequest $request)
    {
        $setting = WebsiteSetting::getSettings();
        $setting->update($request->validated());
        WebsiteSettingService::clearCache();
        
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return redirect()->route($redirectRoute)
            ->with('success', 'Social media settings updated successfully!');
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(WebsiteSeoRequest $request)
    {
        $setting = WebsiteSetting::getSettings();
        $setting->update($request->validated());
        WebsiteSettingService::clearCache();
        
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return redirect()->route($redirectRoute)
            ->with('success', 'SEO settings updated successfully!');
    }

    /**
     * Update all settings at once (alternative method).
     */
    public function update(Request $request)
    {
        $setting = WebsiteSetting::getSettings();
        
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'favicon_path' => ['nullable', 'image', 'mimes:ico,png,jpg,gif', 'max:1024'],
        ]);
        
        if ($request->hasFile('logo_path')) {
            $setting->clearMediaCollection('logo');
            $setting->addMediaFromRequest('logo_path')
                ->toMediaCollection('logo');
        }
        
        if ($request->hasFile('favicon_path')) {
            $setting->clearMediaCollection('favicon');
            $setting->addMediaFromRequest('favicon_path')
                ->toMediaCollection('favicon');
        }
        
        $setting->update($data);
        WebsiteSettingService::clearCache();
        
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.website.edit' : 'admin.website.edit';
        return redirect()->route($redirectRoute)
            ->with('success', 'Website settings updated successfully!');
    }
}
