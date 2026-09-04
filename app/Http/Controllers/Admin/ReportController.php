<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReportDataTables\ExpenseDataTable;
use App\DataTables\ReportDataTables\PurchaseDataTable;
use App\DataTables\ReportDataTables\StockDataTable;
use App\DataTables\ReportDataTables\SalesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Label;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function purchaseReport(PurchaseDataTable $dataTable)
    {
        $totals = $dataTable->getTotals();

        return $dataTable->render('admin.reports.purchase.index',compact('totals'));
    }

    public function stockReport(StockDataTable $dataTable)
    {
        $summary = $dataTable->getTotals();
        
        return $dataTable->render('admin.reports.stock.index', compact('summary'));
    }

    public function stockSummary(StockDataTable $dataTable)
    {
        return response()->json($dataTable->getTotals());
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'add_usage' => 'required|integer|min:1',
        ]);
    
        $productId = $request->id;
        $addUsage = $request->add_usage;
    
        // Get total purchased quantity
        $totalPurchased = PurchaseItem::where('product_id', $productId)->sum('quantity');
        
        // Get current used quantity
        $currentUsed = StockUsage::where('product_id', $productId)->value('quantity_used') ?? 0;
        
        // Calculate current available stock
        $currentStock = $totalPurchased - $currentUsed;
        
        // Validate that add_usage doesn't exceed current stock
        if ($addUsage > $currentStock) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot use more quantity than available stock. Available: ' . number_format($currentStock)
            ], 422);
        }
        
        // Calculate new total used quantity
        $newTotalUsed = $currentUsed + $addUsage;
    
        // Update or create stock usage record
        $stockUsage = StockUsage::updateOrCreate(
            ['product_id' => $productId],
            ['quantity_used' => $newTotalUsed]
        );
    
        // Get product name for response
        $productName = Product::find($productId)->name;
    
        // Refresh summary counts
        $dataTable = new StockDataTable();
        $summary = $dataTable->getTotals();
        
        // Calculate new remaining stock
        $newRemainingStock = $totalPurchased - $newTotalUsed;
    
        return response()->json([
            'success' => true,
            'summary' => $summary,
            'data' => [
                'product_name' => $productName,
                'added_usage' => $addUsage,
                'total_used' => $newTotalUsed,
                'remaining_stock' => $newRemainingStock
            ],
            'message' => "Added {$addUsage} units to usage. Remaining stock: " . number_format($newRemainingStock)
        ]);
    }  

    public function expenseReport(ExpenseDataTable $dataTable)
    {
        $summary = $this->getExpenseSummary();
        $monthly = $this->getMonthlyExpenses();
    
        return $dataTable->with([
            'month' => now()->month,
            'year'  => now()->year,
        ])->render('admin.reports.expense.index', compact('summary', 'monthly'));
    }
    
    private function getExpenseSummary()
    {
        return [
            'total_expenses' => Expense::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
    
            'total_amount'   => Expense::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->sum(DB::raw('amount - tax_amount')),
    
            'pending'        => Expense::where('status','pending')
                                       ->whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
    
            'approved'       => Expense::where('status','approved')
                                       ->whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
    
            'rejected'       => Expense::where('status','rejected')
                                       ->whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
        ];
    }    
    
    private function getMonthlyExpenses()
    {
        return Expense::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount - tax_amount) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }

    public function profitLossReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
    
        if (!$startDate || !$endDate) {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate   = now()->endOfMonth()->toDateString();
        }

        $salesQuery = \DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->join('menus', 'dishes.menu_id', '=', 'menus.id')
            ->select(
                'menus.name as menu_name',
                \DB::raw('SUM(order_items.quantity) as total_quantity'),
                \DB::raw('SUM(order_items.total) as total_sales')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('menus.id', 'menus.name');
    
        $report = $salesQuery->get();
    
        $expenseQuery = \DB::table('expenses')
            ->leftJoin('labels', 'expenses.label_id', '=', 'labels.id')
            ->select(
                \DB::raw('COALESCE(labels.name, "Uncategorized") as label_name'),
                \DB::raw('SUM(expenses.total_amount) as total_expenses')
            )
            ->whereBetween('expenses.created_at', [$startDate, $endDate])
            ->groupBy('labels.id', 'labels.name');
    
        $expenses = $expenseQuery->get();
    
        $totalSales = $report->sum('total_sales');
        $totalExpenses = $expenses->sum('total_expenses');
        $profitOrLoss = $totalSales - $totalExpenses;

        $trendQuery = \DB::table('order_items')
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('SUM(total) as total_sales')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc');
    
        $trend = $trendQuery->get();
    
        return view('admin.reports.profit_and_loss.index', compact(
            'report',
            'expenses',
            'totalSales',
            'totalExpenses',
            'startDate',
            'endDate',
            'profitOrLoss',
            'trend'
        ));
    }    

    public function salesReport(SalesDataTable $dataTable)
    {
        $month = request('month', now()->format('Y-m'));

        $summary = [
            'total_sales'   => number_format(OrderItem::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])->sum('total'), 2),
            'total_orders'  => OrderItem::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])->distinct('order_id')->count('order_id'),
            'total_dishes'  => OrderItem::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])->distinct('dish_id')->count('dish_id'),
            'total_quantity'=> OrderItem::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])->sum('quantity'),
        ];
        return $dataTable->render('admin.reports.sales.index',compact('summary'));
    }

    public function financialTrackReport(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->format('m'));

        $totalRevenue = Invoice::where('payment_status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->sum('total_amount');

        $totalExpenses = Expense::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum(DB::raw('amount - tax_amount'));

        $totalPurchases = Purchase::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_amount');

        $netProfit = $totalRevenue - $totalExpenses - $totalPurchases;

        $revenueByMethod = Invoice::where('payment_status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $monthlyTrend = DB::table(function ($query) use ($year) {
            $query->from('invoices')
                ->where('payment_status', 'paid')
                ->whereYear('paid_at', $year)
                ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as period, SUM(total_amount) as revenue, 0 as expense, 0 as purchase")
                ->groupByRaw("DATE_FORMAT(paid_at, '%Y-%m')");
        }, 'revenue_data')
            ->unionAll(DB::table(function ($query) use ($year) {
                $query->from('expenses')
                    ->whereYear('created_at', $year)
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, 0 as revenue, SUM(amount - tax_amount) as expense, 0 as purchase")
                    ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')");
            }, 'expense_data'))
            ->unionAll(DB::table(function ($query) use ($year) {
                $query->from('purchases')
                    ->whereYear('created_at', $year)
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, 0 as revenue, 0 as expense, SUM(total_amount) as purchase")
                    ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')");
            }, 'purchase_data'))
            ->selectRaw('period, SUM(revenue) as revenue, SUM(expense) as expense, SUM(purchase) as purchase')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $expenseByLabel = Expense::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('label_id, SUM(amount - tax_amount) as total')
            ->groupBy('label_id')
            ->with('label')
            ->get()
            ->mapWithKeys(fn($e) => [$e->label?->name ?? 'Uncategorized' => $e->total]);

        $recentInvoices = Invoice::where('payment_status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->with('order.table')
            ->latest('paid_at')
            ->limit(10)
            ->get();

        return view('admin.reports.financial.index', compact(
            'totalRevenue',
            'totalExpenses',
            'totalPurchases',
            'netProfit',
            'revenueByMethod',
            'monthlyTrend',
            'expenseByLabel',
            'recentInvoices',
            'year',
            'month'
        ));
    }
}