<?php

namespace App\DataTables\ReportDataTables;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;

class ExpenseDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('staff_name', fn($expense) => optional($expense->staff)->name ?? '-')
            ->addColumn('supplier_name', fn($expense) => optional($expense->supplier)->name ?? '-')
            ->addColumn('label_name', fn($expense) => optional($expense->label)->name ?? '-')
            ->editColumn('expense_number', function ($expense) {
                return '<span class="badge bg-primary">' . ($expense->expense_number ?? '—') . '</span>';
            })
            ->editColumn('amount', fn($expense) => number_format($expense->amount, 2))
            ->editColumn('tax_amount', fn($expense) => number_format($expense->tax_amount, 2))
            ->editColumn('total_amount', function($expense) {
                return number_format($expense->amount - $expense->tax_amount, 2);
            })
            ->addColumn('payment_status', function($expense) {
                $map = [
                    'paid' => ['success', 'Paid'],
                    'pending' => ['warning', 'Pending'],
                    'approved' => ['primary', 'Approved'],
                    'rejected' => ['danger', 'Rejected'],
                    'cancelled' => ['secondary', 'Cancelled'],
                ];
                [$cls, $label] = $map[$expense->status] ?? ['info', ucfirst($expense->status)];
                return '<span class="badge bg-'.$cls.'">'.$label.'</span>';
            })
            ->filterColumn('payment_status', function($query, $keyword) {
                $query->where('status', 'like', "%{$keyword}%");
            })
            ->rawColumns(['payment_status','expense_number']);
    }

    public function query(Expense $model): QueryBuilder
    {
        return $model->with(['staff','label','supplier'])
            ->select('expenses.*')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('data-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->dom("<'row mb-3'<'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap'B><'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2'f>><'row'<'col-12'tr>><'row mt-1'<'col-12 col-sm-5 d-flex align-items-center'i><'col-12 col-sm-7 d-flex justify-content-sm-end'p>>")
        ->orderBy(2, 'desc')
        ->selectStyleSingle()
        ->buttons([
            Button::make('print')->text('<i class="bi bi-printer"></i> Print')->addClass('btn btn-print btn-sm rounded p-2'),
            Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm rounded'),
        ])
        ->parameters([
            'responsive' => true,
            'autoWidth' => false,
            'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
            'pageLength' => 25,
            'processing' => true,
            'serverSide' => true,
            'stateSave' => true,
            'order' => [[1,'desc']],
        ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('expense_number')->title('Expense No')->addClass('text-center'),
            Column::make('title')->title('Title')->addClass('text-center'),
            Column::computed('label_name')->title('Category/Label')->addClass('text-center'),
            Column::computed('supplier_name')->title('Supplier')->addClass('text-center'),
            Column::make('amount')->title('Amount')->addClass('text-center'),
            Column::make('tax_amount')->title('Tax')->addClass('text-center'),
            Column::computed('staff_name')->title('Staff')->addClass('text-center'),
            Column::make('total_amount')->title('Total')->addClass('text-center'),
            Column::make('payment_status')->title('Payment Status')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Expense_Report_' . date('YmdHis');
    }
}
