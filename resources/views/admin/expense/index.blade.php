@extends('admin.includes.main')
@section('title', 'Expenses')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Expenses" route="admin.expenses.create" button="Add New" icon="bi-plus-circle" />
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
