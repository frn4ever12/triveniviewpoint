<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            Category::create($request->validated());

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function show(Category $category)
    {
        return view('admin.category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        DB::beginTransaction();

        try {
            $category->update($request->validated());

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();

        try {
            $category->delete();
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Category deleted successfully.',
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete category.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to delete category. Please try again.');
        }
    }

}

