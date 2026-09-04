<?php

namespace App\DataTables;

use App\Enums\OrderStatusEnum;
use Illuminate\Support\Str;
use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Order> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($order) {
                return view('admin.order.datatables-actions', compact('order'))->render();
            })
            ->editColumn('status', function($order) {
                $badgeClass = $order->status === OrderStatusEnum::ACTIVE ? 'success' : 'danger';
                return '<span class="badge bg-'.$badgeClass.' status-badge" style="cursor: pointer;" title="Click to toggle">'.$order->status->label().'</span>';
            })           
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Purchase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('created_at','desc')
            ->select([
                'id','order_no','subtotal','total_amount','payment_status','status'
            ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
 public function html()
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<\'row mb-3\'<\'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap\'B><\'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2\'f>><\'row\'<\'col-12\'tr>><\'row mt-1\'<\'col-12 col-sm-5 d-flex align-items-center\'i><\'col-12 col-sm-7 d-flex justify-content-sm-end\'p>>')
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('print')
                    ->text('<i class="bi bi-printer"></i> Print')
                    ->addClass('btn btn-print btn-sm rounded p-2'),
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel"></i> Excel')
                    ->addClass('btn btn-success btn-sm rounded'),
               
                [
                    'text' => '<i class="bi bi-arrow-clockwise"></i> Reload',
                    'className' => 'btn btn-reload btn-sm rounded p-2',
                    'action' => 'function (e, dt, node, config) {
                            dt.ajax.reload();
                        }'
                ],
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

                    'search' => 'Search orders:',
                    'lengthMenu' => 'Show _MENU_ orders per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ orders',
                    'infoEmpty' => 'No orders found',
                    'infoFiltered' => '(filtered from _MAX_ total orders)'
                ],
                'order' => [[1, 'desc']],
                'columnDefs' => [
                    [
                        'targets' => [0, 6], //  action columns
                        'orderable' => false,
                        'searchable' => false
                    ]
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('S.N')
                ->width(60)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('order_no')
                ->title('Order No.')
                ->width(200), 
            Column::make('subtotal')
                ->title('Subtotal')
                ->width(120),
            Column::make('total_amount')
                ->title('Total')
                ->width(120),
            Column::make('payment_status')
                ->title('Payment')
                ->width(120),
            Column::make('status')
                ->title('Status')
                ->width(80)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }
}
