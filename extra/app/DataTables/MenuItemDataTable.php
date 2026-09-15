<?php

namespace App\DataTables;

use App\Enums\CommonStatusEnum;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MenuItemDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($item) {
                return view('admin.menu-item.datatables-actions', compact('item'))->render();
            })
            ->editColumn('status', function ($item) {
                $badgeClass = $item->status === CommonStatusEnum::ACTIVE ? 'success' : 'danger';

                return '<span class="badge bg-'.$badgeClass.' status-badge" style="cursor: pointer;" title="Click to toggle">'.$item->status->label().'</span>';
            })
            ->editColumn('is_featured', function ($item) {
                $badgeClass = $item->is_featured ? 'primary' : 'secondary';
                $text = $item->is_featured ? 'Featured' : 'Not Featured';

                return '<span class="badge bg-'.$badgeClass.' featured-badge" style="cursor: pointer;" title="Click to toggle">'.$text.'</span>';
            })
            ->editColumn('name', function ($item) {
                return '<strong>'.$item->name.'</strong>';
            })
            ->addColumn('image', function ($item) {
                return '<img src="'.($item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png')).'" alt="Image" class="img-fluid img-thumbnail" style="width: 60px; height: 60px;">';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'image', 'is_featured', 'name'])
            ->setRowId('id');
    }

    public function query(MenuItem $model): QueryBuilder
    {
        return $model->newQuery()->with('category')->select([
            'id', 'name', 'category_id', 'price', 'is_featured', 'status',
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
                    'search' => 'Search menu items:',
                    'lengthMenu' => 'Show _MENU_ items per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ items',
                    'infoEmpty' => 'No items found',
                    'infoFiltered' => '(filtered from _MAX_ total items)',
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
            Column::computed('DT_RowIndex')->title('S.N')->width(60)->addClass('text-center')
                ->orderable(false)->searchable(false),
            Column::make('image')->title('Image')->width(100),
            Column::make('name')->title('Name')->width(200),
            Column::make('category.name')->title('Category')->width(150),
            Column::computed('is_featured')->title('Featured')->width(100)->addClass('text-center')
                ->orderable(false)->searchable(false),
            Column::make('status')->title('Status')->width(80)->addClass('text-center')
                ->orderable(false)->searchable(false),
            Column::computed('action')->title('Actions')->exportable(false)->printable(false)
                ->width(120)->addClass('text-center'),
        ];
    }
}
