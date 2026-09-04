@extends('superadmin.includes.main')
@section('title', 'Create Plan Feature')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Create Plan Feature</h1>
            <p class="page-subtitle">Add a new feature to {{ $plan->name }}</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add Feature to {{ $plan->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.plan-features.store', $plan->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Feature Code *</label>
                                <input type="text" name="code" class="form-control" required placeholder="e.g., digital_menu" value="{{ old('code') }}">
                                <small class="text-muted">Unique identifier for the feature (lowercase, underscores)</small>
                                @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Feature Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g., Digital Menu" value="{{ old('name') }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Brief description of the feature">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Feature Value</label>
                                <input type="text" name="value" class="form-control" placeholder="e.g., unlimited, 100, true" value="{{ old('value') }}">
                                <small class="text-muted">Optional: specific value for the feature</small>
                                @error('value') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sort Order *</label>
                                <input type="number" name="sort_order" class="form-control" required min="0" value="{{ old('sort_order', 0) }}">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('sort_order') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_enabled" class="form-check-input" id="is_enabled" {{ old('is_enabled', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_enabled">Enable this feature</label>
                                </div>
                            </div>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Common Feature Codes</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">pos_basic</code>
                                            <small>Basic POS functionality</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">menu_management</code>
                                            <small>Menu item management</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">order_management</code>
                                            <small>Order processing</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">digital_menu</code>
                                            <small>QR code digital menu</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">inventory_advanced</code>
                                            <small>Advanced inventory tracking</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <code class="d-block p-2 bg-white rounded">api_access</code>
                                            <small>REST API access</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Add Feature
                            </button>
                            <a href="{{ route('superadmin.plan-features.index', $plan->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
