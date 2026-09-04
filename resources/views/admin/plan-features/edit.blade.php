@extends('admin.includes.main')
@section('title', 'Edit Plan Feature')
@section('content')
    <x-breadcrumb title="Edit Plan Feature" route="admin.plan-features.index" button="Back to Features" icon="bi-arrow-left" :route-params="['planId' => $plan->id]">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Subscription Plans</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.show', $plan) }}">{{ $plan->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit {{ $planFeature->name }}</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Feature: {{ $planFeature->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.plan-features.update', $planFeature) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Feature Code *</label>
                                <input type="text" name="code" class="form-control" required value="{{ old('code', $planFeature->code) }}">
                                <small class="text-muted">Unique identifier for the feature</small>
                                @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Feature Name *</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $planFeature->name) }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description', $planFeature->description) }}</textarea>
                                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Feature Value</label>
                                <input type="text" name="value" class="form-control" value="{{ old('value', $planFeature->value) }}">
                                <small class="text-muted">Optional: specific value for the feature</small>
                                @error('value') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sort Order *</label>
                                <input type="number" name="sort_order" class="form-control" required min="0" value="{{ old('sort_order', $planFeature->sort_order) }}">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('sort_order') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_enabled" class="form-check-input" id="is_enabled" {{ old('is_enabled', $planFeature->is_enabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_enabled">Enable this feature</label>
                                </div>
                            </div>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Plan Information</h6>
                                    <p><strong>Plan:</strong> {{ $plan->name }}</p>
                                    <p><strong>Monthly Price:</strong> Rs. {{ number_format($plan->monthly_price) }}</p>
                                    <p><strong>Max Users:</strong> {{ $plan->max_users }}</p>
                                    <p><strong>Max Menu Items:</strong> {{ $plan->max_menu_items }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Feature
                            </button>
                            <a href="{{ route('admin.plan-features.index', $plan->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
