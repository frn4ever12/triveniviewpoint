<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
   
    
    public function index()
    {
        $about = About::first(); 
        $view = request()->routeIs('superadmin.*') ? 'superadmin.about.form' : 'admin.about.form';
        return view($view, compact('about'));
    }
    
    

    public function store(AboutRequest $request)
    {
        DB::beginTransaction();
        try {
            $about = About::create($request->validated());

            if ($request->hasFile('image')) {
                $about->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.abouts.index' : 'admin.abouts.index';
            return redirect()->route($redirectRoute, $about->id)->with('success', 'About section created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create About. Please try again.');
        }
    }

   



    public function update(AboutRequest $request, About $about)
    {
        DB::beginTransaction();
        try {
            $about->update($request->validated());

            if ($request->hasFile('image')) {
                $about->clearMediaCollection('image');
                $about->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.abouts.index' : 'admin.abouts.index';
            return redirect()->route($redirectRoute, $about->id)->with('success', 'About section updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update About. Please try again.');
        }
    }

    
  
}
