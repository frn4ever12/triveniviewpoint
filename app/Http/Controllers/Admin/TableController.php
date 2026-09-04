<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\TableDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\TableRequest;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function index(TableDataTable $dataTable)
    {
        return $dataTable->render('admin.table.index');
    }

    public function getTablesJson()
    {
        $tables = Table::select('id', 'name', 'status', 'capacity')->get();
        return response()->json($tables);
    }

    public function create()
    {
        $waiters=User::get();
        return view('admin.table.create',compact('waiters'));
    }

    public function store(TableRequest $request)
    {
        DB::beginTransaction();

        try {
            $table = Table::create($request->validated());
          
            DB::commit();
            return redirect()->route('admin.tables.index')
                ->with('success', 'table created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create table. Please try again.');
        }
    }
    public function show(Table $table)
    {
        return view('admin.table.show', compact('table'));
    }
    public function edit(Table $table)
    {
        $waiters = User::get();
        return view('admin.table.edit', compact('table','waiters'));
    }
    public function update(TableRequest $request, Table $table)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $table->update($data);
            
            DB::commit();
            return redirect()->route('admin.tables.index')
                ->with('success', 'table updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update table. Please try again.');
        }
    }
    public function destroy(Table $table)
    {
        DB::beginTransaction();
        try {
            $table->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Table deleted successfully.'
                ]);
            }
            return redirect()->route('admin.tables.index')
                ->with('success', 'Table deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete Table.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete Table. Please try again.');
        }
    }

}

