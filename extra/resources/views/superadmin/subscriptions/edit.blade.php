@extends('superadmin.includes.main')
@section('title', 'Edit Subscription')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Edit Subscription</h1>
            <p class="page-subtitle">Update subscription details and settings</p>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Subscription: {{ $subscription->tenant->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.subscriptions.update', $subscription) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subscription Plan *</label>
                                        <select name="plan_id" class="form-select" required id="planSelect">
                                            @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" data-monthly="{{ $plan->monthly_price }}" data-yearly="{{ $plan->yearly_price }}" {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - Rs. {{ number_format($plan->monthly_price) }}/month
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('plan_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Billing Cycle *</label>
                                        <select name="billing_cycle" class="form-select" required id="billingCycle">
                                            <option value="monthly" {{ $subscription->billing_cycle === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="yearly" {{ $subscription->billing_cycle === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                        @error('billing_cycle') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status *</label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="trialing" {{ $subscription->status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                                            <option value="past_due" {{ $subscription->status === 'past_due' ? 'selected' : '' }}>Past Due</option>
                                            <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="expired" {{ $subscription->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                        @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method', $subscription->payment_method) }}">
                                        @error('payment_method') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment ID</label>
                                        <input type="text" name="payment_id" class="form-control" value="{{ old('payment_id', $subscription->payment_id) }}">
                                        @error('payment_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $subscription->notes) }}</textarea>
                                        @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Update Subscription
                                    </button>
                                    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Subscription Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Tenant:</strong> {{ $subscription->tenant->name }}</p>
                        <p><strong>Current Plan:</strong> {{ $subscription->plan->name }}</p>
                        <p><strong>Amount:</strong> Rs. {{ number_format($subscription->amount) }}</p>
                        <p><strong>Start Date:</strong> {{ $subscription->starts_at->format('M d, Y') }}</p>
                        <p><strong>End Date:</strong> {{ $subscription->ends_at->format('M d, Y') }}</p>
                        <p><strong>Next Billing:</strong> {{ $subscription->next_billing_at->format('M d, Y') }}</p>
                        <p><strong>Days Remaining:</strong> {{ $subscription->daysRemaining() }}</p>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        @if($subscription->isActive())
                            <form action="{{ route('superadmin.subscriptions.cancel', $subscription) }}" method="POST" class="d-inline mb-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning w-100" onclick="return confirm('Cancel this subscription?')">
                                    <i class="bi bi-x-circle me-1"></i> Cancel Subscription
                                </button>
                            </form>
                        @endif
                        @if($subscription->isExpired() || $subscription->isCancelled())
                            <form action="{{ route('superadmin.subscriptions.renew', $subscription) }}" method="POST" class="d-inline mb-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Renew this subscription?')">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Renew Subscription
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('superadmin.tenants.show', $subscription->tenant) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-building me-1"></i> View Tenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
