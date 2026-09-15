<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($role) {
                return view('admin.role.datatables-actions', compact('role'))->render();
            })
            ->editColumn('name', function ($role) {
                $badgeColor = match ($role->name) {
                    'superadmin' => 'danger',
                    'admin' => 'warning',
                    'cashier' => 'info',
                    'waiter' => 'secondary',
                    default => 'primary',
                };

                return '<span class="badge bg-'.$badgeColor.' text-uppercase">'.e($role->name).'</span>';
            })
            ->editColumn('permissions_count', function ($role) {
                return $role->permissions_count;
            })
            ->editColumn('users_count', function ($role) {
                return $role->users_count;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'name'])
            ->setRowId('id');
    }

    /**
     * Get query source.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount(['permissions', 'users']);
    }

    /**
     * Optional method if you want to use html builder.
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
                    'action' => 'function (e, dt, node, config) { dt.ajax.reload(); }',
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
                    'search' => 'Search roles:',
                    'lengthMenu' => 'Show _MENU_ roles per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ roles',
                    'infoEmpty' => 'No roles found',
                    'infoFiltered' => '(filtered from _MAX_ total roles)',
                ],
                'order' => [[1, 'asc']],
                'columnDefs' => [
                    ['targets' => [0, 4], 'orderable' => false, 'searchable' => false],
                ],
            ]);
    }

    /**
     * Get columns.
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
                ->title('Role')
                ->width(180),
            Column::make('permissions_count')
                ->title('Permissions')
                ->width(100)
                ->addClass('text-center')
                ->searchable(false),
            Column::make('users_count')
                ->title('Users')
                ->width(80)
                ->addClass('text-center')
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
