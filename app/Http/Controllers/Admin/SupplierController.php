<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\SupplierDataTable;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(SupplierDataTable $dataTable)
    {
        return $dataTable->render('admin.supplier.index');
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(SupplierRequest $request)
    {
        DB::beginTransaction();
        try {
            Supplier::create($request->validated());
            DB::commit();
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create supplier. Please try again.');
        }
    }

    public function show(Supplier $supplier)
    {
        return view('admin.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $supplier->update($request->validated());
            DB::commit();
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update supplier. Please try again.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $supplier->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Supplier deleted successfully.']);
            }
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Failed to delete supplier.'], 500);
            }
            return back()->with('error', 'Failed to delete supplier.');
        }
    }


}
