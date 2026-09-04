@extends('superadmin.includes.main')

@section('title', 'Roles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Roles & Permissions</h1>
            <p class="page-subtitle">Manage user roles and permissions</p>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Roles</h5>
                <a href="{{ route('superadmin.roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-shield-plus me-1"></i> Add New Role
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
