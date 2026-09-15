@extends('admin.includes.main')

@section('title', 'Purchase Report')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Purchase Report" route="dashboard" button="Dashboard" icon="bi-arrow-left" />
        
        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title text-white mb-0">{{ number_format($totals['total_records']) }}</h4>
                                <small>Total Purchases</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-receipt fs-2"></i>
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
                                <h4 class="card-title text-white mb-0">{{ number_format($totals['subtotal_sum'], 2) }}</h4>
                                <small>Total Subtotal</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calculator fs-2"></i>
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
                                <h4 class="card-title text-white mb-0">{{ number_format($totals['discount_sum'], 2) }}</h4>
                                <small>Total Discount</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-2"></i>
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
                                <h4 class="card-title text-white mb-0">{{ number_format($totals['total_amount_sum'], 2) }}</h4>
                                <small>Grand Total</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-rupee fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Purchase Details</h5>
            </div>
            <div class="card-body py-0 px-2">
                {{-- DataTable --}}
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-striped table-hover', 'width' => '100%']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
    
    <script>
        // Optional: Add some custom styling for the footer
        $(document).ready(function() {
            $('#data-table tfoot tr').addClass('table-dark');
            $('#data-table tfoot th').css('font-weight', 'bold');
        });
    </script>
@endpush