@extends('admin.includes.main')
@section('title', 'Subscription Plan Details')
@section('content')
    <x-breadcrumb title="Subscription Plan Details" route="admin.subscription-plans.index" button="Back to Plans" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Subscription Plans</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $subscriptionPlan->name }}</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card @if($subscriptionPlan->is_popular) border-primary @endif">
                    @if($subscriptionPlan->is_popular)
                    <div class="card-header bg-primary text-white text-center py-2">
                        <span class="badge bg-warning text-dark">MOST POPULAR</span>
                    </div>
                    @endif
                    <div class="card-body">
                        <h4 class="text-center">{{ $subscriptionPlan->name }}</h4>
                        <p class="text-muted text-center small">{{ $subscriptionPlan->description }}</p>
                        <div class="text-center my-4">
                            <h2 class="text-primary">Rs. {{ number_format($subscriptionPlan->monthly_price) }}<small class="text-muted">/mo</small></h2>
                            <p class="text-muted">Rs. {{ number_format($subscriptionPlan->yearly_price) }}/year</p>
                        </div>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $subscriptionPlan->max_users }} Users</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $subscriptionPlan->max_menu_items }} Menu Items</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $subscriptionPlan->max_orders_per_month ? $subscriptionPlan->max_orders_per_month . ' Orders/month' : 'Unlimited Orders' }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $subscriptionPlan->trial_days }} Days Trial</li>
                        </ul>
                        <div class="mt-3">
                            @if($subscriptionPlan->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-pencil me-1"></i> Edit Plan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Plan Features</h5>
                        <a href="{{ route('admin.plan-features.create', $subscriptionPlan->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus me-1"></i> Add Feature
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptionPlan->planFeatures as $feature)
                                    <tr>
                                        <td><code>{{ $feature->code }}</code></td>
                                        <td>{{ $feature->name }}</td>
                                        <td>{{ $feature->description ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $feature->is_enabled ? 'success' : 'secondary' }}">
                                                {{ $feature->is_enabled ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.plan-features.edit', $feature) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.plan-features.destroy', $feature) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this feature?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Active Subscriptions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tenant</th>
                                        <th>Status</th>
                                        <th>Billing</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptionPlan->subscriptions->take(10) as $subscription)
                                    <tr>
                                        <td>{{ $subscription->tenant->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                                        <td>{{ $subscription->ends_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                    @if($subscriptionPlan->subscriptions->count() > 10)
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            ...and {{ $subscriptionPlan->subscriptions->count() - 10 }} more
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <h3>{{ $subscriptionPlan->subscriptions->count() }}</h3>
                                <p class="text-muted mb-0">Total Subscriptions</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <h3>{{ $subscriptionPlan->subscriptions->where('status', 'active')->count() }}</h3>
                                <p class="text-muted mb-0">Active</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <h3>{{ $subscriptionPlan->subscriptions->where('status', 'trialing')->count() }}</h3>
                                <p class="text-muted mb-0">On Trial</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <h3>{{ $subscriptionPlan->subscriptions->where('status', 'cancelled')->count() }}</h3>
                                <p class="text-muted mb-0">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
