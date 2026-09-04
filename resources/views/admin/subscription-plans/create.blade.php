@extends('admin.includes.main')
@section('title', 'Create Subscription Plan')
@section('content')
    <x-breadcrumb title="Create Subscription Plan" route="admin.subscription-plans.index" button="Back to Plans" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Subscription Plans</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Plan</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Subscription Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Plan Name *</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Monthly Price (Rs.) *</label>
                                    <input type="number" name="monthly_price" class="form-control" required min="0" step="0.01" value="{{ old('monthly_price') }}">
                                    @error('monthly_price') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Yearly Price (Rs.) *</label>
                                    <input type="number" name="yearly_price" class="form-control" required min="0" step="0.01" value="{{ old('yearly_price') }}">
                                    @error('yearly_price') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Trial Days *</label>
                                    <input type="number" name="trial_days" class="form-control" required min="0" value="{{ old('trial_days', 0) }}">
                                    @error('trial_days') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Max Users *</label>
                                    <input type="number" name="max_users" class="form-control" required min="1" value="{{ old('max_users', 1) }}">
                                    @error('max_users') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sort Order *</label>
                                    <input type="number" name="sort_order" class="form-control" required min="0" value="{{ old('sort_order', 0) }}">
                                    @error('sort_order') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Max Menu Items *</label>
                                    <input type="number" name="max_menu_items" class="form-control" required min="1" value="{{ old('max_menu_items', 50) }}">
                                    @error('max_menu_items') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Max Orders/Month</label>
                                    <input type="number" name="max_orders_per_month" class="form-control" min="0" value="{{ old('max_orders_per_month') }}">
                                    <small class="text-muted">Leave empty for unlimited</small>
                                    @error('max_orders_per_month') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_popular" class="form-check-input" id="is_popular" {{ old('is_popular') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">Mark as Popular</label>
                                </div>
                            </div>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Plan Features (JSON)</h6>
                                    <textarea name="features" class="form-control" rows="5" placeholder='{"feature1": true, "feature2": "value"}'>{{ old('features') }}</textarea>
                                    <small class="text-muted">Optional: JSON format for additional features</small>
                                    @error('features') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Create Plan
                            </button>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
