<?php

namespace App\DataTables;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoomDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($room) {
                return view('admin.room.datatables-actions', compact('room'))->render();
            })
            ->editColumn('status', function ($room) {
                $badgeClass = match ($room->status) {
                    'available' => 'success',
                    'occupied' => 'warning',
                    'maintenance' => 'danger',
                    'reserved' => 'info',
                    default => 'secondary',
                };

                return '<span class="badge bg-'.$badgeClass.'">'.ucfirst($room->status).'</span>';
            })
            ->editColumn('room_type', function ($room) {
                return '<span class="text-capitalize">'.e($room->room_type).'</span>';
            })
            ->editColumn('price_per_night', function ($room) {
                return 'Rs '.number_format($room->price_per_night, 2);
            })
            ->editColumn('name', function ($room) {
                return '<strong>'.e($room->name).'</strong><br><small class="text-muted">'.e($room->room_number).'</small>';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'room_type', 'name'])
            ->setRowId('id');
    }

    public function query(Room $model): QueryBuilder
    {
        return $model->newQuery()->select([
            'id', 'name', 'room_number', 'room_type', 'floor',
            'price_per_night', 'capacity', 'status',
        ]);
    }

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
                    'search' => 'Search rooms:',
                    'lengthMenu' => 'Show _MENU_ rooms per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ rooms',
                    'infoEmpty' => 'No rooms found',
                    'infoFiltered' => '(filtered from _MAX_ total rooms)',
                ],
                'order' => [[1, 'asc']],
                'columnDefs' => [
                    ['targets' => [0, 6], 'orderable' => false, 'searchable' => false],
                ],
            ]);
    }

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
                ->title('Room')
                ->width(200),
            Column::make('room_type')
                ->title('Type')
                ->width(100)
                ->addClass('text-center'),
            Column::make('floor')
                ->title('Floor')
                ->width(80)
                ->addClass('text-center'),
            Column::make('price_per_night')
                ->title('Price/Night')
                ->width(100)
                ->addClass('text-right'),
            Column::make('capacity')
                ->title('Capacity')
                ->width(80)
                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->width(100)
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
