@extends('admin.includes.main')

@section('title', 'Roles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Roles & Permissions" route="admin.roles.create" button="Add New" icon="bi-shield-plus" />

        <div class="card">
            <div class="card-body py-0 px-2">
                
                    {!! $dataTable->table(['class' => 'table table-striped table-hover']) !!}
                
            
        
    
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
