<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Banner;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Tenant;
use Vinkla\Hashids\Facades\Hashids;

class HomeController extends Controller
{
    public function index()
    {
        // Show multi-tenant restaurant listing
        $tenants = Tenant::active()
            ->with(['subscription.plan'])
            ->whereHas('subscription', function($query) {
                $query->where('status', 'active');
            })
            ->latest()
            ->get();

        $about = About::where('status', 'active')->latest()->first();
        $banners = Banner::active()->with('media')->get();

        return view('welcome', compact('tenants', 'about', 'banners'));
    }

    public function tenant($slug)
    {
        // Show specific tenant's menu
        $tenant = Tenant::where('slug', $slug)
            ->where('status', 'active')
            ->whereHas('subscription', function($query) {
                $query->where('status', 'active');
            })
            ->firstOrFail();

        // Store tenant in session
        session(['current_tenant_id' => $tenant->id]);

        $about = About::where('status', 'active')->latest()->first();
        $categories = Category::where('status', 'active')
            ->where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        $menuItems = MenuItem::with('category', 'media')
            ->where('status', 'active')
            ->where('tenant_id', $tenant->id)
            ->get();

        $banners = Banner::active()->with('media')->get();

        return view('welcome', compact('tenant', 'about', 'menuItems', 'categories', 'banners'))
            ->with('menuCategories', $categories);
    }

    public function digitalmenu($slug = null)
    {
        $tenantId = session('current_tenant_id');
        
        if ($slug) {
            $tenant = Tenant::where('slug', $slug)->where('status', 'active')->firstOrFail();
            $tenantId = $tenant->id;
            session(['current_tenant_id' => $tenant->id]);
        }
        
        $categories = Category::where('status', 'active')
            ->when($tenantId, function($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })
            ->orderBy('name')
            ->get();
            
        $menuItems = MenuItem::with('category')
            ->where('status', 'active')
            ->when($tenantId, function($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })
            ->get();

        $dishesData = $menuItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => number_format($item->final_price ?? $item->price, 2),
                'image' => $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png'),
                'category' => $item->category->slug ?? 'uncategorized',
                'menu_category_name' => $item->category->name ?? 'Uncategorized',
                'menu_name' => $item->category->name ?? 'Other',
                'tags' => array_values(array_filter([
                    $item->is_vegetarian ? 'vegetarian' : null,
                    $item->is_featured ? 'popular' : null,
                ])),
                'discount_value' => $item->discount_value ?? null,
            ];
        });

        return view('frontend.welcome.digitalmenu', compact('menuItems', 'dishesData', 'categories'))
            ->with('menuCategories', $categories);
    }

    public function digitalmenuTable($slug, $table)
    {
        $tenant = Tenant::where('slug', $slug)->where('status', 'active')->firstOrFail();
        session(['current_tenant_id' => $tenant->id]);
        
        $categories = Category::where('status', 'active')
            ->where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();
            
        $menuItems = MenuItem::with('category')
            ->where('status', 'active')
            ->where('tenant_id', $tenant->id)
            ->get();

        $dishesData = $menuItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => number_format($item->final_price ?? $item->price, 2),
                'image' => $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png'),
                'category' => $item->category->slug ?? 'uncategorized',
                'menu_category_name' => $item->category->name ?? 'Uncategorized',
                'menu_name' => $item->category->name ?? 'Other',
                'tags' => array_values(array_filter([
                    $item->is_vegetarian ? 'vegetarian' : null,
                    $item->is_featured ? 'popular' : null,
                ])),
                'discount_value' => $item->discount_value ?? null,
            ];
        });

        $tableRecord = null;
        if ($table) {
            try {
                $tableId = Hashids::decode($table);
                if (empty($tableId)) {
                    abort(404);
                }
                $tableRecord = \App\Models\Table::where('id', $tableId[0])
                    ->where('tenant_id', $tenant->id)
                    ->firstOrFail();
            } catch (\Exception $e) {
                abort(404);
            }
        }

        return view('frontend.welcome.digitalmenu', compact('menuItems', 'dishesData', 'categories', 'tableRecord'))
            ->with('menuCategories', $categories);
    }
}
