@extends('admin.includes.main')
@section('title', 'Menu Availability')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Menu Availability</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Manage menu item availability</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Availability Settings</h5>
            </div>
            <div class="card-body p-3">
                <form method="POST" action="{{ route('admin.menu-availability.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Availability Status</label>
                        <select class="form-select" name="status">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger rounded-3">Update Availability</button>
                </form>
            </div>
        </div>
    </div>
@endsection
