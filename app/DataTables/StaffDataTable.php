<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class StaffDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($staff) {
                return view('admin.staff.datatables-actions', compact('staff'))->render();
            })
            ->editColumn('status', function ($staff) {
                $colors = ['active' => 'success', 'inactive' => 'secondary', 'suspended' => 'warning', 'terminated' => 'danger'];
                $color = $colors[$staff->status->value] ?? 'secondary';
                return '<span class="badge bg-' . $color . ' rounded-pill px-2" style="font-size:0.7rem;">' . $staff->status->label() . '</span>';
            })
            ->editColumn('name', function ($staff) {
                $img = $staff->getFirstMediaUrl('profile_image', 'thumb');
                $avatar = $img
                    ? '<img src="' . $img . '" alt="" class="rounded-circle me-2" style="width:28px;height:28px;object-fit:cover;">'
                    : '<span class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-danger text-white" style="width:28px;height:28px;font-size:0.7rem;font-weight:600;">' . strtoupper(substr($staff->name, 0, 1)) . '</span>';
                return '<div class="d-flex align-items-center">' . $avatar . '<strong>' . e($staff->name) . '</strong></div>';
            })
            ->editColumn('email', function ($staff) {
                return '<span class="text-muted" style="font-size:0.8rem;">' . e($staff->email) . '</span>';
            })
            ->addColumn('role', function ($staff) {
                $role = $staff->getRoleNames()->first() ?? '-' ;
                $colors = ['superadmin' => 'danger', 'admin' => 'primary', 'cashier' => 'info', 'waiter' => 'success'];
                $color = $colors[$role] ?? 'secondary';
                return $role
                    ? '<span class="badge bg-' . $color . ' bg-opacity-10 text-white' . ' rounded-pill px-2" style="font-size:0.7rem;font-weight:500;">' . ucfirst($role) . '</span>'
                    : '<span class="text-muted" style="font-size:0.78rem;">—</span>';
            })
            ->editColumn('login_enabled', function ($staff) {
                if (!$staff->password) {
                    return '<span class="text-muted" style="font-size:0.72rem;">—</span>';
                }
                $checked = $staff->login_enabled ? 'checked' : '';
                return '<div class="form-check form-switch d-flex justify-content-center m-0"><input class="form-check-input login-toggle" type="checkbox" data-id="' . $staff->id . '" ' . $checked . ' style="cursor:pointer;"></div>';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'name', 'email', 'role', 'login_enabled'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->with('roles')
            ->select(['id', 'name', 'email', 'phone', 'status', 'login_enabled', 'created_at']);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row mb-3'<'col-12 col-sm-6 d-flex align-items-center gap-1 flex-wrap'B><'col-12 col-sm-6 d-flex align-items-center justify-content-sm-end gap-2'f>><'row'<'col-12'tr>><'row mt-1'<'col-12 col-sm-5 d-flex align-items-center'i><'col-12 col-sm-7 d-flex justify-content-sm-end'p>>")
            ->orderBy(1, 'desc')
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
                    'search' => 'Search staff:',
                    'lengthMenu' => 'Show _MENU_ staff per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ staff',
                    'infoEmpty' => 'No staff found',
                    'infoFiltered' => '(filtered from _MAX_ total staff)',
                ],
                'order' => [[1, 'asc']],
                'columnDefs' => [
                    ['targets' => [4], 'orderable' => false, 'searchable' => false],
                ],
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('S.N')
                ->width(50)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('name')->title('Name')->width(200),
            Column::make('email')->title('Email')->width(220),
            Column::computed('role')
                ->title('Role')
                ->width(100)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('login_enabled')
                ->title('Login')
                ->width(70)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('status')
                ->title('Status')
                ->width(90)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(110)
                ->addClass('text-center'),
        ];
    }
}
