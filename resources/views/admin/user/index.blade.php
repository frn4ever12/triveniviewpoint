@extends('admin.includes.main')
@section('title', 'Users')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <x-breadcrumb title="Users" route="admin.users.create" button="Add New" icon="bi-plus-circle">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body py-0 px-2">
                {!! $dataTable->table(['class' => 'table table-striped table-hover', 'style' => 'width:100%']) !!}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}

@endpush
