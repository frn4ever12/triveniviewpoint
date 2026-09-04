@extends('admin.includes.main')

@section('title', 'Monthly Expense Report')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Monthly Expense Report" route="dashboard" button="Dashboard" icon="bi-arrow-left" />

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title text-white mb-0">{{ $summary['total_expenses'] }}</h4>
                            <small>Total Expenses</small>
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
                            <h4 class="card-title text-white mb-0">{{ $summary['pending'] }}</h4>
                            <small>Pending</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Expense DataTable --}}
    <div class="card">
        <div class="card-body py-0 px-2">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover', 'width'=>'100%']) !!}
            </div>
        </div>
    </div>

    {{-- Monthly Expense Summary --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5>Monthly Expenses</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Month</th>
                            <th>Total Expenses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthly as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->month.'-01')->format('F Y') }}</td>
                                <td>{{ number_format($row->total, 2) }}</td>
                            </tr>
                        @endforeach
                        @if($monthly->isEmpty())
                            <tr>
                                <td colspan="2" class="text-center">No expense records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
{!! $dataTable->scripts() !!}
@endpush
