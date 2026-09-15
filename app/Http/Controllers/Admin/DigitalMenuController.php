<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalMenuDesign;
use App\Models\Table;
use Illuminate\Http\Request;

class DigitalMenuController extends Controller
{
    public function index(){
        $tables=Table::get();
        return view('admin.digitalmenu.index',compact('tables'));
    }

    public function design()
    {
        $design = DigitalMenuDesign::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->first();

        if (!$design) {
            $design = DigitalMenuDesign::create([
                'tenant_id' => auth()->user()->tenant_id,
            ]);
        }

        return view('admin.digitalmenu.design', compact('design'));
    }

    public function updateDesign(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'font_family' => 'required|string',
            'card_style' => 'required|string|in:modern,classic,minimal',
            'layout' => 'required|string|in:grid,list',
            'show_categories' => 'boolean',
            'show_search' => 'boolean',
            'show_prices' => 'boolean',
            'show_images' => 'boolean',
        ]);

        $design = DigitalMenuDesign::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->firstOrFail();

        $design->update([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color' => $request->accent_color,
            'background_color' => $request->background_color,
            'text_color' => $request->text_color,
            'font_family' => $request->font_family,
            'card_style' => $request->card_style,
            'show_categories' => $request->show_categories ?? true,
            'show_search' => $request->show_search ?? true,
            'show_prices' => $request->show_prices ?? true,
            'show_images' => $request->show_images ?? true,
            'layout' => $request->layout,
            'custom_css' => $request->custom_css ? json_decode($request->custom_css, true) : null,
        ]);

        return redirect()->back()->with('success', 'Design settings updated successfully.');
    }
}
