<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MenuItemDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Models\MenuItem;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuItemController extends Controller
{
    use FileUploadTrait;

    public function index(MenuItemDataTable $dataTable)
    {
        return $dataTable->render('admin.menu-item.index');
    }

    public function create()
    {
        return view('admin.menu-item.create');
    }

    public function store(MenuItemRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $item = new MenuItem($data);
            $item->calculateFinalPrice();
            $item->calculateDiscountAmount();
            $item->save();

            if ($request->hasFile('image')) {
                $this->uploadToMediaLibrary($item, $request->file('image'), 'image');
            }

            DB::commit();

            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create menu item. Please try again.');
        }
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('media');

        return view('admin.menu-item.show', compact('menuItem'));
    }

    public function edit(MenuItem $menuItem)
    {
        $menuItem->load('media');

        return view('admin.menu-item.edit', compact('menuItem'));
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $data['is_featured'] = $request->has('is_featured') ? true : false;
            $data['is_vegetarian'] = $request->has('is_vegetarian') ? true : false;

            $menuItem->fill($data);
            $menuItem->calculateFinalPrice();
            $menuItem->calculateDiscountAmount();
            $menuItem->save();

            if ($request->hasFile('image')) {
                $this->deleteFromMediaLibrary($menuItem, 'image');
                $this->uploadToMediaLibrary($menuItem, $request->file('image'), 'image');
            }

            DB::commit();

            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update menu item. Please try again.');
        }
    }

    public function destroy(MenuItem $menuItem)
    {
        DB::beginTransaction();
        try {
            $menuItem->clearMediaCollection('image');
            $menuItem->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Menu item deleted successfully.']);
            }

            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Failed to delete menu item.'], 500);
            }

            return back()->with('error', 'Failed to delete menu item. Please try again.');
        }
    }

}

