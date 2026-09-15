@extends('superadmin.includes.main')
@section('title', 'Subscription Details')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Subscription Details</h1>
            <p class="page-subtitle">View subscription information and plan details</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Subscription Info</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Tenant:</strong></td>
                                <td>{{ $subscription->tenant->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Plan:</strong></td>
                                <td>{{ $subscription->plan->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td>Rs. {{ number_format($subscription->amount) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Billing Cycle:</strong></td>
                                <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'info' : ($subscription->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>{{ $subscription->starts_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td>{{ $subscription->ends_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Next Billing:</strong></td>
                                <td>{{ $subscription->next_billing_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Days Remaining:</strong></td>
                                <td class="{{ $subscription->daysRemaining() <= 7 ? 'text-danger' : 'text-success' }}">
                                    {{ $subscription->daysRemaining() }} days
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method:</strong></td>
                                <td>{{ $subscription->payment_method ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Payment ID:</strong></td>
                                <td>{{ $subscription->payment_id ?? '—' }}</td>
                            </tr>
                        </table>
                        <div class="mt-3">
                            <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Tenant Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $subscription->tenant->name }}</p>
                                <p><strong>Company:</strong> {{ $subscription->tenant->company_name ?? '—' }}</p>
                                <p><strong>Email:</strong> {{ $subscription->tenant->email }}</p>
                                <p><strong>Phone:</strong> {{ $subscription->tenant->phone ?? '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Address:</strong> {{ $subscription->tenant->address ?? '—' }}</p>
                                <p><strong>City:</strong> {{ $subscription->tenant->city ?? '—' }}</p>
                                <p><strong>Country:</strong> {{ $subscription->tenant->country ?? '—' }}</p>
                                <p><strong>Status:</strong>
                                    <span class="badge bg-{{ $subscription->tenant->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($subscription->tenant->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Plan Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Plan Name:</strong> {{ $subscription->plan->name }}</p>
                                <p><strong>Description:</strong> {{ $subscription->plan->description ?? '—' }}</p>
                                <p><strong>Monthly Price:</strong> Rs. {{ number_format($subscription->plan->monthly_price) }}</p>
                                <p><strong>Yearly Price:</strong> Rs. {{ number_format($subscription->plan->yearly_price) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Max Users:</strong> {{ $subscription->plan->max_users }}</p>
                                <p><strong>Max Menu Items:</strong> {{ $subscription->plan->max_menu_items }}</p>
                                <p><strong>Max Orders/Month:</strong> {{ $subscription->plan->max_orders_per_month ?: 'Unlimited' }}</p>
                                <p><strong>Trial Days:</strong> {{ $subscription->plan->trial_days }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Plan Features</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($subscription->plan->planFeatures as $feature)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-{{ $feature->is_enabled ? 'success' : 'secondary' }}">
                                    {{ $feature->is_enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                                {{ $feature->name }}
                                @if($feature->description) <small class="text-muted">({{ $feature->description }})</small> @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        {{ $subscription->notes ?? 'No notes' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
