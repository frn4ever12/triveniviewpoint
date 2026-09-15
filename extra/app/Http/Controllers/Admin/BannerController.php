<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use FileUploadTrait;


    public function index(BannerDataTable $dataTable)
    {
        $view = request()->routeIs('superadmin.*') ? 'superadmin.banner.index' : 'admin.banner.index';
        return $dataTable->render($view);
    }

    public function create()
    {
        $view = request()->routeIs('superadmin.*') ? 'superadmin.banner.create' : 'admin.banner.create';
        return view($view);
    }

    public function store(BannerRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create Banner from validated data
            $banner = Banner::create($request->validated());

            // Handle image upload
            if ($request->hasFile('image')) {
                $this->uploadToMediaLibrary($banner, $request->file('image'), 'image');
            }

            DB::commit();
            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.banners.index' : 'admin.banners.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Banner created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create banner. Please try again.');
        }
    }

    public function edit(Banner $banner)
    {
        $banner->load('media');
        $view = request()->routeIs('superadmin.*') ? 'superadmin.banner.edit' : 'admin.banner.edit';
        return view($view, compact('banner'));
    }
    public function update(BannerRequest $request, Banner $banner)
    {
        DB::beginTransaction();

        try {
            // Update banner with validated data
            $banner->update($request->validated());

            // Handle image update
            if ($request->hasFile('image')) {
                $this->deleteFromMediaLibrary($banner, 'image'); // clear old one
                $this->uploadToMediaLibrary($banner, $request->file('image'), 'image'); // save new one
            }

            DB::commit();

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.banners.index' : 'admin.banners.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update banner. Please try again.');
        }
    }

    public function destroy(Banner $banner)
    {
        DB::beginTransaction();
        try {
            $banner->clearMediaCollection('image');
            $banner->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'banner deleted successfully.'
                ]);
            }
            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.banners.index' : 'admin.banners.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'banner deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete banner.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete banner. Please try again.');
        }
    }
}
