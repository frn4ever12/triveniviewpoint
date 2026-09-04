<?php

namespace App\DataTables;

use App\Enums\TableStatusEnum;
use App\Models\Table;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TableDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($table) {
                return view('admin.table.datatables-actions', compact('table'))->render();
            })
            ->editColumn('status', function($table) {
                $badgeClass = $table->status === TableStatusEnum::AVAILABLE ? 'success' : 'danger';
                return '<span class="badge bg-'.$badgeClass.' status-badge" style="cursor: pointer;" title="Click to toggle">'.$table->status->label().'</span>';
            })
           
            ->editColumn('name', function($table) {
                return '<strong>'.$table->name.'</strong>';
            })
            ->editColumn('capacity', function($table) {
                return '<strong>'.$table->capacity.'</strong>';
            })
           
            ->addIndexColumn()
            ->rawColumns(['action', 'status','name','capacity', 'slug'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Table $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Table $model): QueryBuilder
    {
        return $model->newQuery()
            
            ->select([
                'id', 'name', 'capacity','status'
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

                    'search' => 'Search tables:',
                    'lengthMenu' => 'Show _Menu_ tables per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ tables',
                    'infoEmpty' => 'No tables found',
                    'infoFiltered' => '(filtered from _MAX_ total tables)'
                ],
                'order' => [[1, 'asc']], // Sort by name by default
                'columnDefs' => [
                    [
                        'targets' => [0, 4], // action columns
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
            Column::make('name')
                ->title('Name')
                ->width(200),
            Column::make('capacity')
                ->title('No of Seats')
                ->width(200),
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