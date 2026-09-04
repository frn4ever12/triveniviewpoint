<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(ExpenseDataTable $dataTable)
    {
        return $dataTable->render('admin.expense.index');
    }

    public function create()
    {
        return view('admin.expense.create');
    }

    public function store(ExpenseRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            
            // Set entry user
            $data['entry_user_id'] = Auth::id();
            
            // Calculate tax amount if not provided
            $amount = (float)($data['amount'] ?? 0);
            $taxPercent = (float)($data['tax_percent'] ?? 0);
            
            if (!isset($data['tax_amount']) || empty($data['tax_amount'])) {
                $data['tax_amount'] = round(($amount * $taxPercent) / 100, 2);
            }

            // Generate expense number if not provided
            if (empty($data['expense_number'])) {
                $data['expense_number'] = Expense::generateExpenseNumber();
            }

            $expense = Expense::create($data);

            DB::commit();

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense created successfully with number: ' . $expense->expense_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create expense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $expense->load(['label', 'staff', 'entryUser', 'supplier']);
        return view('admin.expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $expense->load(['label']);
        return view('admin.expense.edit', compact('expense'));
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            
            // Recalculate tax if amount or tax percent changed
            $amount = (float)($data['amount'] ?? 0);
            $taxPercent = (float)($data['tax_percent'] ?? 0);
            
            // Only recalculate if tax_amount is not explicitly provided
            if (!isset($data['tax_amount']) || empty($data['tax_amount'])) {
                $data['tax_amount'] = round(($amount * $taxPercent) / 100, 2);
            }

            $expense->update($data);

            DB::commit();

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update expense: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        DB::beginTransaction();

        try {
            $expenseNumber = $expense->expense_number;
            $expense->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "Expense {$expenseNumber} deleted successfully."
                ]);
            }

            return redirect()->route('admin.expenses.index')
                ->with('success', "Expense {$expenseNumber} deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete expense.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete expense. Please try again.');
        }
    }


    // API endpoint for calculating tax
    public function calculateTax(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'tax_percent' => 'required|numeric|min:0|max:100'
        ]);

        $amount = (float) $request->amount;
        $taxPercent = (float) $request->tax_percent;
        $taxAmount = round(($amount * $taxPercent) / 100, 2);
        $totalAmount = $amount + $taxAmount;

        return response()->json([
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'formatted_tax_amount' => number_format($taxAmount, 2),
            'formatted_total_amount' => number_format($totalAmount, 2)
        ]);
    }

    // Get expense statistics
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total_expenses' => Expense::count(),
                'total_amount' => Expense::sum('amount'),
                'total_tax' => Expense::sum('tax_amount'),
                'pending_count' => Expense::where('status', 'pending')->count(),
                'approved_count' => Expense::where('status', 'approved')->count(),
                'paid_count' => Expense::where('status', 'paid')->count(),
                'this_month_count' => Expense::whereMonth('expense_date', now()->month)
                    ->whereYear('expense_date', now()->year)->count(),
                'this_month_amount' => Expense::whereMonth('expense_date', now()->month)
                    ->whereYear('expense_date', now()->year)->sum('amount'),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}