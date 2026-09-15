<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('admin.product.index');
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['tenant_id'] = auth()->user()->tenant_id;
            $product = Product::create($data);

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create label. Please try again.');
        }
    }

    public function show(Product $product)
    {
        // Verify product belongs to current tenant
        if ($product->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to product');
        }
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Verify product belongs to current tenant
        if ($product->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to product');
        }
        return view('admin.product.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        // Verify product belongs to current tenant
        if ($product->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to product');
        }

        $data = $request->validated();

        DB::beginTransaction();
        try {
            $product->update($data);
            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        // Verify product belongs to current tenant
        if ($product->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to product');
        }

        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Product deleted successfully.'
                ]);
            }
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete product.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete product. Please try again.');
        }
    }

}

