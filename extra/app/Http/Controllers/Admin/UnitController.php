<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UnitDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index(UnitDataTable $dataTable)
    {
        return $dataTable->render('admin.unit.index');
    }

    public function create()
    {
        return view('admin.unit.create');
    }

    public function store(UnitRequest $request)
    {
        DB::beginTransaction();

        try {
            $unit = Unit::create($request->validated());
           
            DB::commit();
            return redirect()->route('admin.units.index')
                ->with('success', 'Unit created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create unit. Please try again.');
        }
    }
    public function show(Unit $unit)
    {
        return view('admin.unit.show', compact('unit'));
    }
    public function edit(Unit $unit)
    {
        return view('admin.unit.edit', compact('unit'));
    }
    public function update(UnitRequest $request, Unit $unit)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
           
            $unit->update($data);
        
            DB::commit();
            return redirect()->route('admin.units.index')
                ->with('success', 'Unit updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update unit. Please try again.');
        }
    }
    public function destroy(Unit $unit)
    {
        DB::beginTransaction();
        try {
            $unit->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Unit deleted successfully.'
                ]);
            }
            return redirect()->route('admin.units.index')
                ->with('success', 'Unit deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete unit.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete unit. Please try again.');
        }
    }
}
