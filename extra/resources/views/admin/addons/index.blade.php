@extends('admin.includes.main')
@section('title', 'Add-ons / Extras')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Add-ons / Extras</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Manage add-ons and extras for menu items</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.addons.create') }}" class="btn btn-danger btn-sm rounded-3">
                    <i class="bi bi-plus-lg me-1"></i> Add New Add-on
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Add-ons List</h5>
            </div>
            <div class="card-body p-3">
                <div class="text-center py-5">
                    <p class="text-muted">No add-ons found</p>
                    <a href="{{ route('admin.addons.create') }}" class="btn btn-sm btn-danger rounded-3">Create Add-on</a>
                </div>
            </div>
        </div>
    </div>
@endsection
