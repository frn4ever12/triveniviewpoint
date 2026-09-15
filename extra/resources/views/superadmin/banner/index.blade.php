@extends('superadmin.includes.main')

@section('title', 'Banners')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Banners</h1>
            <p class="page-subtitle">Manage homepage banners</p>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Banners</h5>
                <a href="{{ route('superadmin.banners.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New
                </a>
            </div>
            <div class="card-body py-0 px-2">
                {!! $dataTable->table(['class' => 'table table-striped table-hover']) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
