<?php

namespace App\DataTables\ReportDataTables;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('supplier', fn($purchase) => optional($purchase->supplier)->name ?? 'N/A')
            ->editColumn('purchase_date', fn($p) => $p->purchase_date ? $p->purchase_date->format('Y-m-d') : '-')
            ->addColumn('items', function ($purchase) {
                return $purchase->items->map(fn($item) => ($item->product->name ?? $item->item_name ?? 'Item') . ' (x' . $item->quantity . ')')->implode('<br>');
            })
            ->editColumn('subtotal', fn($p) => number_format($p->subtotal, 2))
            ->editColumn('discount_amount', fn($p) => number_format($p->discount_amount, 2))
            ->editColumn('vat_amount', fn($p) => number_format($p->vat_amount, 2))
            ->editColumn('total_amount', fn($p) => '<strong>' . number_format($p->total_amount, 2) . '</strong>')
            ->rawColumns(['items', 'total_amount'])
            ->filterColumn('supplier', function($query, $keyword) {
                $query->whereHas('supplier', fn($q) => $q->where('name', 'like', "%{$keyword}%"));
            });
    }

    public function query(Purchase $model)
    {
        return $model->newQuery()
            ->with(['supplier', 'items.product'])
            ->select([
                'id', 'invoice_no', 'vendor_id', 'purchase_date',
                'subtotal', 'discount_amount', 'vat_amount', 'total_amount'
            ]);
    }

    public function getTotals()
    {
        $query = $this->query(new Purchase());
        return [
            'total_records' => $query->count(),
            'subtotal_sum' => $query->sum('subtotal'),
            'discount_sum' => $query->sum('discount_amount'),
            'vat_sum' => $query->sum('vat_amount'),
            'total_amount_sum' => $query->sum('total_amount'),
        ];
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row mb-3'<'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap'B><'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2'f>><'row'<'col-12'tr>><'row mt-1'<'col-12 col-sm-5 d-flex align-items-center'i><'col-12 col-sm-7 d-flex justify-content-sm-end'p>>")
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('print')->text('<i class="bi bi-printer"></i> Print')->addClass('btn btn-print btn-sm rounded p-2'),
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm rounded'),
            ])
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                'pageLength' => 25,
                'processing' => true,
                'serverSide' => true,
                'language' => [
                    'processing' => '<div class="spinner-border text-primary" role="status"></div>',
                    'search' => 'Search purchases:',
                    'lengthMenu' => 'Show _MENU_ records',
                ],
                'order' => [[1, 'desc']],
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')->title('S.N')->width(20)->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('invoice_no')->title('Invoice No')->width(120),
            Column::make('supplier')->title('Supplier')->width(150),
            Column::make('purchase_date')->title('Purchase Date')->width(100),
            Column::make('items')->title('Items & Qty')->width(250)->orderable(false)->searchable(false),
            Column::make('subtotal')->title('Subtotal')->width(80)->addClass('text-end'),
            Column::make('discount_amount')->title('Discount')->width(80)->addClass('text-end'),
            Column::make('vat_amount')->title('VAT')->width(80)->addClass('text-end'),
            Column::make('total_amount')->title('Total')->width(100)->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'Purchase_Report_' . date('YmdHis');
    }
}
