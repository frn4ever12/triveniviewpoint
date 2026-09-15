<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LabelDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LabelController extends Controller
{
    public function index(LabelDataTable $dataTable)
    {
        return $dataTable->render('admin.label.index');
    }

    public function create()
    {
        return view('admin.label.create');
    }

    public function store(LabelRequest $request)
    {
        DB::beginTransaction();

        try {
            $label = Label::create($request->validated());
           
            DB::commit();
            return redirect()->route('admin.labels.index')
                ->with('success', 'Label created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create label. Please try again.');
        }
    }
    public function show(Label $label)
    {
        return view('admin.label.show', compact('label'));
    }
    public function edit(Label $label)
    {
        return view('admin.label.edit', compact('label'));
    }
    public function update(LabelRequest $request, Label $label)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
           
            $label->update($data);
        
            DB::commit();
            return redirect()->route('admin.labels.index')
                ->with('success', 'Label updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update label. Please try again.');
        }
    }
    public function destroy(Label $label)
    {
        DB::beginTransaction();
        try {
            $label->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'label deleted successfully.'
                ]);
            }
            return redirect()->route('admin.labels.index')
                ->with('success', 'Label deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete label.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete label. Please try again.');
        }
    }
}
