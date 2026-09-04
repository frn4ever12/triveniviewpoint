<?php

namespace App\DataTables\ReportDataTables;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Carbon\Carbon;

class SalesDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('month', fn($row) => Carbon::parse($row->month . '-01')->format('F Y'))
            ->editColumn('menu_name', fn($row) => $row->menu_name ?? '-')
            ->editColumn('dish_name', fn($row) => $row->dish_name ?? '-')
            ->editColumn('total_quantity', fn($row) => $row->total_quantity)
            ->editColumn('total_sales', fn($row) => number_format($row->total_sales, 2));
    }

    public function query(OrderItem $model): QueryBuilder
    {
        $currentMonth = now()->format('Y-m');

        return $model->selectRaw('
                DATE_FORMAT(order_items.created_at, "%Y-%m") as month,
                menus.name as menu_name,
                dishes.name as dish_name,
                SUM(order_items.quantity) as total_quantity,
                SUM(order_items.total) as total_sales
            ')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->join('menus', 'dishes.menu_id', '=', 'menus.id')
            ->when(request()->has('month'), function ($query) {
                $query->whereRaw("DATE_FORMAT(order_items.created_at, '%Y-%m') = ?", [request('month')]);
            }, function ($query) use ($currentMonth) {
                $query->whereRaw("DATE_FORMAT(order_items.created_at, '%Y-%m') = ?", [$currentMonth]);
            })
            ->groupBy('month', 'menus.name', 'dishes.name')
            ->orderBy('month', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sales-report-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<\'row mb-3\'<\'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap\'B><\'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2\'f>><\'row\'<\'col-12\'tr>><\'row mt-1\'<\'col-12 col-sm-5 d-flex align-items-center\'i><\'col-12 col-sm-7 d-flex justify-content-sm-end\'p>>')
            ->orderBy(1, 'desc')
            ->buttons([
                Button::make('print')->text('<i class="bi bi-printer"></i> Print')->addClass('btn btn-primary btn-sm rounded p-2'),
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm rounded p-2'),
                [
                    'text' => '<i class="bi bi-arrow-clockwise"></i> Reload',
                    'className' => 'btn btn-info btn-sm rounded p-2',
                    'action' => 'function (e, dt, node, config) { dt.ajax.reload(); }'
                ],
            ])
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
                'pageLength' => 12,
                'processing' => true,
                'serverSide' => true,
                'stateSave' => true,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('S.N')->addClass('text-center'),
            Column::make('menu_name')->title('Menu')->addClass('text-center'),
            Column::make('dish_name')->title('Dish')->addClass('text-center'),
            Column::make('total_quantity')->title('Total Quantity')->addClass('text-center'),
            Column::make('total_sales')->title('Sales')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Monthly_Sales_Report_' . date('YmdHis');
    }
}
