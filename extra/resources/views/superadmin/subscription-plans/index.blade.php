@extends('superadmin.includes.main')
@section('title', 'Subscription Plans')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Subscription Plans</h1>
                <p class="page-subtitle">Manage subscription plans for tenants</p>
            </div>
            <a href="{{ route('superadmin.subscription-plans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Plan
            </a>
        </div>
        <div class="row">
            @foreach($plans as $plan)
            <div class="col-md-4 mb-4">
                <div class="card h-100 @if($plan->is_popular) border-primary @endif">
                    @if($plan->is_popular)
                    <div class="card-header bg-primary text-white text-center py-2">
                        <span class="badge bg-warning text-dark">MOST POPULAR</span>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-center">{{ $plan->name }}</h5>
                        <p class="card-text text-muted text-center small">{{ $plan->description }}</p>
                        <div class="text-center my-3">
                            <h3 class="text-primary">Rs. {{ number_format($plan->monthly_price) }}<small class="text-muted">/mo</small></h3>
                            <small class="text-muted">Rs. {{ number_format($plan->yearly_price) }}/year</small>
                        </div>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $plan->max_users }} Users</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $plan->max_menu_items }} Menu Items</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $plan->max_orders_per_month ? $plan->max_orders_per_month . ' Orders/month' : 'Unlimited Orders' }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $plan->trial_days }} Days Trial</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $plan->planFeatures->where('is_enabled', true)->count() }} Features</li>
                        </ul>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('superadmin.subscription-plans.show', $plan) }}" class="btn btn-sm btn-outline-primary flex-grow-1 me-1">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('superadmin.subscription-plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary flex-grow-1 me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('superadmin.subscription-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this plan?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        @if($plan->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer">
            {{ $plans->links() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
@endpush
