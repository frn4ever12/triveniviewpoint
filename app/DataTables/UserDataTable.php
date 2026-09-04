<?php

namespace App\DataTables;

use App\Enums\CommonStatusEnum;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UserDataTable extends DataTable
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
            ->addColumn('action', function($user) {
                return view('admin.user.datatables-actions', compact('user'))->render();
            })
            ->editColumn('status', function($user) {
                $badgeClass = $user->status === CommonStatusEnum::ACTIVE ? 'success' : 'danger';
                return '<span class="badge bg-'.$badgeClass.' status-badge" style="cursor: pointer;" title="Click to toggle">'.$user->status->label().'</span>';
            })
           
            ->editColumn('name', function($user) {
                return '<strong>'.$user->name.'</strong>';
            })
        
            ->addIndexColumn()
            ->rawColumns(['action', 'name', 'email','phone','role','branch','password', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\user $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            
            ->select([
                'id', 'name', 'email','phone','role','branch','password', 'status'
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
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel"></i> Excel')
                    ->addClass('btn btn-success btn-sm'),
                Button::make('csv')
                    ->text('<i class="bi bi-filetype-csv"></i> CSV')
                    ->addClass('btn btn-info btn-sm'),
                Button::make('pdf')
                    ->text('<i class="bi bi-file-earmark-pdf"></i> PDF')
                    ->addClass('btn btn-danger btn-sm'),
                Button::make('print')
                    ->text('<i class="bi bi-printer"></i> Print')
                    ->addClass('btn btn-secondary btn-sm'),
                [
                    'text' => '<i class="fas fa-sync"></i> Reload', 
                    'className' => 'btn btn-info btn-sm',
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
                    'lengthMenu' => 'Show _Menu_ users per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ users',
                    'infoEmpty' => 'No users found',
                    'infoFiltered' => '(filtered from _MAX_ total users)'
                ],
                'order' => [[1, 'asc']], // Sort by name by default
                'columnDefs' => [
                    [
                        'targets' => [0, 5], //  action columns
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
            Column::make('phone')
                ->title('Phone')
                ->width(200),
            Column::make('role')
                ->title('Role')
                ->width(80)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('branch')
                ->title('Branch')
                ->width(80)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
           
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