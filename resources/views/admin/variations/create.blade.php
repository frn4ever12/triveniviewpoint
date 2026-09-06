@extends('admin.includes.main')
@section('title', 'Create Variation')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Create Variation</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Add a new variation for menu items</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.variations.index') }}" class="btn btn-outline-danger btn-sm rounded-3">Back to Variations</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Variation Details</h5>
            </div>
            <div class="card-body p-3">
                <form method="POST" action="{{ route('admin.variations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" name="price" required>
                    </div>
                    <button type="submit" class="btn btn-danger rounded-3">Save Variation</button>
                </form>
            </div>
        </div>
    </div>
@endsection
