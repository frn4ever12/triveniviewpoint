<?php

namespace App\DataTables\ReportDataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('product_group', fn($item) => $item->product_group ?? '-')
            ->editColumn('total_used_quantity', fn($item) => number_format($item->total_used_quantity, 2))
            ->editColumn('total_purchased', fn($item) => number_format($item->total_purchased, 2))
            ->editColumn('current_stock', fn($item) => number_format($item->current_stock, 2))
            ->addColumn('stock_value', fn($item) => 'Rs. ' . number_format($item->current_stock * $item->avg_unit_rate, 2))
            ->editColumn('supplier_name', fn($item) => $item->supplier_name ?? '-')
            ->addColumn('status', function ($item) {
                $stock = $item->current_stock;
                if ($stock == 0) {
                    return '<span class="badge bg-danger">Out of Stock</span>';
                } elseif ($stock <= 5) {
                    return '<span class="badge bg-warning">Low Stock</span>';
                }
                return '<span class="badge bg-success">In Stock</span>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <button type="button"
                        class="btn btn-sm btn-primary editStockBtn"
                        data-id="' . $item->product_id . '"
                        data-name="' . e($item->product_name) . '"
                        data-current="' . $item->current_stock . '">
                        <i class="bi bi-pencil-square"></i> Manage
                    </button>
                ';
            })
            ->rawColumns(['status', 'action']);
    }

    public function query(Product $model): QueryBuilder
    {
        $hasGroupColumn = Schema::hasColumn('products', 'group');

        $selects = [
            'products.id as product_id',
            'products.name as product_name',
            DB::raw('COALESCE(SUM(purchase_items.quantity), 0) as total_purchased'),
            DB::raw('COALESCE(stock_usages.quantity_used, 0) as total_used_quantity'),
            DB::raw('(COALESCE(SUM(purchase_items.quantity), 0) - COALESCE(stock_usages.quantity_used, 0)) as current_stock'),
            DB::raw('ROUND(AVG(purchase_items.unit_rate), 2) as avg_unit_rate'),
            DB::raw('MAX(purchase_items.created_at) as last_purchase_date'),
            DB::raw('NULL as supplier_name'),
        ];

        if ($hasGroupColumn) {
            $selects[] = 'products.group as product_group';
        }

        $query = $model->newQuery()
            ->select($selects)
            ->leftJoin('purchase_items', 'products.id', '=', 'purchase_items.product_id')
            ->leftJoin('stock_usages', 'products.id', '=', 'stock_usages.product_id');

        $groupBy = ['products.id', 'products.name', 'stock_usages.quantity_used'];
        if ($hasGroupColumn) {
            $groupBy[] = 'products.group';
        }
        $query->groupBy($groupBy)
              ->orderByDesc('last_purchase_date');

        // Only join with suppliers table if it exists
        if (Schema::hasTable('suppliers')) {
            $query->leftJoin('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                  ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                  ->addSelect('suppliers.name as supplier_name')
                  ->groupBy('suppliers.name');
        }

        return $query;
    }

    public function getTotals()
    {
        $results = $this->query(new Product())->get();

        $totalItems  = $results->count();
        $inStock     = $results->where('current_stock', '>', 5)->count();
        $lowStock    = $results->where('current_stock', '>', 0)->where('current_stock', '<=', 5)->count();
        $outOfStock  = $results->where('current_stock', '=', 0)->count();
        $totalValue  = $results->sum(fn($item) => $item->current_stock * $item->avg_unit_rate);

        return [
            'total_items' => $totalItems,
            'in_stock' => $inStock,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'total_inventory_value' => $totalValue,
        ];
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock-data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<\'row mb-3\'<\'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap\'B><\'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2\'f>><\'row\'<\'col-12\'tr>><\'row mt-1\'<\'col-12 col-sm-5 d-flex align-items-center\'i><\'col-12 col-sm-7 d-flex justify-content-sm-end\'p>>')
            ->orderBy(1, 'asc')
            ->buttons([
                Button::make('print')->text('<i class="bi bi-printer"></i> Print')->addClass('btn btn-print btn-sm rounded p-2'),
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm rounded p-2'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->addClass('btn btn-danger btn-sm rounded p-2'),
            ])
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'pageLength' => 25,
                'processing' => true,
                'serverSide' => true,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('S.N')->width(50)->addClass('text-center'),
            Column::make('product_name')->title('Stock Item')->width(200),
            Column::make('product_group')->title('Group')->width(120),
            Column::make('total_used_quantity')->title('Consumption Rate')->width(120)->addClass('text-center'),
            Column::make('total_purchased')->title('Opening')->width(100)->addClass('text-center'),
            Column::make('current_stock')->title('Closing')->width(100)->addClass('text-center'),
            Column::computed('stock_value')->title('Stock Value')->width(120)->addClass('text-center'),
            Column::make('supplier_name')->title('Supplier')->width(150),
            Column::computed('action')->title('Actions')->exportable(false)->printable(false)->width(100)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Stock_Report_' . date('YmdHis');
    }
}