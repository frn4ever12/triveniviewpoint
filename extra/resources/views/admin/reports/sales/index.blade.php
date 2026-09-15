@extends('admin.includes.main')

@section('title', 'Monthly Sales Report')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Monthly Sales Report" route="dashboard" button="Dashboard" icon="bi-arrow-left" />
    <div class="row mb-3">
    <div class="col-md-3">
        <label for="month_filter">Select Month:</label>
        <input type="month" id="month_filter" class="form-control" value="{{ now()->format('Y-m') }}">
    </div>
</div>


    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title text-white mb-0">{{ $summary['total_sales'] }}</h4>
                            <small>Total Sales</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cash-coin fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title text-white mb-0">{{ $summary['total_orders'] }}</h4>
                            <small>Total Orders</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-receipt fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title text-white mb-0">{{ $summary['total_dishes'] }}</h4>
                            <small>Dishes Sold</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-egg-fried fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title text-white mb-0">{{ $summary['total_quantity'] }}</h4>
                            <small>Total Quantity</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-seam fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body py-0 px-2">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover', 'width'=>'100%']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
{!! $dataTable->scripts() !!}

<script>
$(document).ready(function() {
    let table = $('#sales-report-table').DataTable();

    $('#month_filter').on('change', function() {
    let month = $(this).val();

    // Reload DataTable
    table.ajax.url("{!! route('admin.reports.sales_report') !!}?month=" + month).load();

    // Reload summary cards
    $.get("{!! route('admin.reports.sales_report') !!}?month=" + month, function(data) {
        $('.row.mb-4').html($(data).find('.row.mb-4').html());
    });
});

});
</script>
@endpush
