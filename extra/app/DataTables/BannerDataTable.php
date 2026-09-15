<?php

namespace App\DataTables;

use App\Enums\CommonStatusEnum;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BannerDataTable extends DataTable
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
            ->addColumn('action', function ($banner) {
                return view('admin.banner.datatables-actions', compact('banner'))->render();
            })
            ->editColumn('status', function ($banner) {
                $badgeClass = $banner->status === CommonStatusEnum::ACTIVE ? 'success' : 'danger';
                return '<span class="badge bg-' . $badgeClass . ' status-badge" style="cursor: pointer;" title="Click to toggle">' . $banner->status->label() . '</span>';
            })

            ->editColumn('title', function ($banner) {
                return '<strong>' . $banner->title . '</strong>';
            })
            ->addColumn('image', function ($banner) {
                return '<img src="' . $banner->getFirstMediaUrl('image') . '" alt="Image" class="img-fluid img-thumbnail" style="width: 60px; height: 60px;">';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'image', 'title'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Banner $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Banner $model): QueryBuilder
    {
        return $model->newQuery()

            ->select([
                'id',
                'title',
                'priority',
                'status',
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

                    'search' => 'Search banners:',
                    'lengthMenu' => 'Show _Menu_ dishes per page',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ banners',
                    'infoEmpty' => 'No banners found',
                    'infoFiltered' => '(filtered from _MAX_ total banners)'
                ],
                'order' => [[1, 'asc']], // Sort by name by default
                'columnDefs' => [
                    [
                        'targets' => [0, 4], 
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
            Column::make('image')
                ->title('Image')
                ->width(200),
            Column::make('title')
                ->title('Title')
                ->width(200),

            Column::computed('priority')
                ->title('Priority')
                ->width(100)
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
