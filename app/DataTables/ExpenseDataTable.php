<?php

namespace App\DataTables;

use App\Enums\CommonStatusEnum;
use App\Models\Expense;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ExpenseDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($expense) {
                return view('admin.expense.datatables-actions', compact('expense'))->render();
            })
            ->editColumn('status', function ($expense) {
                $badgeClass = $expense->status === 'approved' ? 'success' : 'danger';
                return '<span class="badge bg-' . $badgeClass . ' status-badge" style="cursor: pointer;" title="Click to toggle">' . $expense->status . '</span>';
            })
            ->editColumn('expense_date_bs', function ($expense) {
                return $expense->expense_date_bs ??  '—';
            })
            ->editColumn('label_id', function ($expense) {
                return $expense->label?->name ?? '—';
            })
            ->editColumn('supplier_id', function ($expense) {
                return $expense->supplier?->name ?? '—';
            })
            ->editColumn('expense_number', function ($expense) {
                return '<span class="badge bg-primary">' . ($expense->expense_number ?? '—') . '</span>';
            })
            ->editColumn('total_amount', function ($expense) {
                $total = ($expense->amount ?? 0) - ($expense->tax_amount ?? 0);
                return 'Rs ' . number_format($total, 2);
            })
            
            ->addIndexColumn()
            ->rawColumns([
                'action', 'status',  
                'total_amount', 'expense_date_bs',  
                'label_id', 'supplier_id', 'expense_number'
            ])
            ->setRowId('id');
    }

    public function query(Expense $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['label', 'staff', 'supplier'])
            ->select([
                'expenses.id',
                'expenses.expense_number',
                'expenses.expense_date_bs',
                'expenses.label_id',
                'expenses.supplier_id',
                'expenses.title',
                'expenses.amount',
                'expenses.tax_amount',
                'expenses.status',
                'expenses.created_at'
            ]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<\'row mb-3\'<\'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap\'B><\'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2\'f>><\'row\'<\'col-12\'tr>><\'row mt-1\'<\'col-12 col-sm-5 d-flex align-items-center\'i><\'col-12 col-sm-7 d-flex justify-content-sm-end\'p>>')
            ->orderBy(2, 'desc') 
            ->selectStyleSingle()
            ->buttons([
                Button::make('print')
                    ->text('<i class="bi bi-printer"></i> Print')
                    ->addClass('btn btn-primary btn-sm rounded p-2'),
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel"></i> Excel')
                    ->addClass('btn btn-success btn-sm rounded p-2'),
                [
                    'text' => '<i class="bi bi-arrow-clockwise"></i> Reload',
                    'className' => 'btn btn-info btn-sm rounded p-2',
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
                    'processing' => '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    'search' => 'Search expenses:',
                    'lengthMenu' => 'Show _MENU_ expenses per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ expenses',
                    'infoEmpty' => 'No expenses found',
                    'infoFiltered' => '(filtered from _MAX_ total expenses)',
                    'emptyTable' => 'No expense records available'
                ],
                'order' => [[2, 'desc']], // Order by expense_date descending
                'columnDefs' => [
                    [
                        'targets' => [0, -1], // checkbox and actions columns
                        'orderable' => false,
                        'searchable' => false
                    ]
                ]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->width(50)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),

            Column::make('expense_date_bs')
                ->title('Date')
                ->width(120)
                ->addClass('text-center'),

            Column::make('expense_number')
                ->title('Number')
                ->width(140)
                ->addClass('text-center'),

            Column::make('label_id')
                ->title('Label')
                ->width(150),

            Column::make('supplier_id')
                ->title('Supplier')
                ->width(150)
                ->addClass('text-center'),

        

            Column::computed('total_amount')
                ->title('Total')
                ->width(110)
                ->addClass('text-end'),


            Column::make('status')
                ->title('Status')
                ->width(80)
                ->addClass('text-center'),

            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }
}