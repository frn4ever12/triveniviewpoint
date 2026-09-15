<?php

namespace App\DataTables;

use App\Models\Purchase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PurchaseDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($purchase) {
                return view('admin.purchase.datatables-actions', compact('purchase'))->render();
            })
            ->editColumn('title', function($purchase) {
                return '<strong>'.$purchase->title.'</strong>';
            })
            ->editColumn('vendor_id', function($purchase) {
                return $purchase->supplier->name ?? '-';
            })
            ->editColumn('purchase_date', function($purchase) {
                return $purchase->purchase_date_bs ?? '-';
            })
            ->addIndexColumn()
            ->rawColumns(['action','title'])
            ->setRowId('id');
    }

    public function query(Purchase $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('supplier')
            ->select([
                'id','title','purchase_date_bs','vendor_id','total_amount'
            ]);
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
                ['text' => '<i class="bi bi-arrow-clockwise"></i> Reload', 'className' => 'btn btn-reload btn-sm rounded p-2', 'action' => 'function (e, dt, node, config) { dt.ajax.reload(); }'],
            ])
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                'pageLength' => 25,
                'processing' => true,
                'serverSide' => true,
                'stateSave' => true,
                'language' => [
                    'processing' => '<div class="spinner-border" role="status"></div>',
                    'search' => 'Search purchases:',
                    'lengthMenu' => 'Show _MENU_ purchases per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ purchases',
                    'infoEmpty' => 'No purchases found',
                    'infoFiltered' => '(filtered from _MAX_ total purchases)'
                ],
                'order' => [[1, 'asc']],
                'columnDefs' => [['targets' => [0, 6], 'orderable' => false, 'searchable' => false]]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')->title('S.N')->width(60)->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('purchase_date')->title('Purchase Date')->width(200),
            Column::make('title')->title('Title')->width(200),
            Column::make('total_amount')->title('Amount')->width(120),
            Column::make('vendor_id')->title('Supplier')->width(180),
            Column::computed('action')->title('Actions')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}
