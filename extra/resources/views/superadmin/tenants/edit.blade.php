@extends('superadmin.includes.main')
@section('title', 'Edit Tenant')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Edit Tenant</h1>
            <p class="page-subtitle">Update tenant information and settings</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Tenant: {{ $tenant->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.tenants.update', $tenant) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tenant Name *</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $tenant->name) }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $tenant->company_name) }}">
                                @error('company_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email', $tenant->email) }}">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}">
                                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $tenant->address) }}</textarea>
                                @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $tenant->city) }}">
                                    @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', $tenant->country) }}">
                                    @error('country') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no', $tenant->pan_no) }}">
                                    @error('pan_no') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Domain</label>
                                    <input type="text" name="domain" class="form-control" value="{{ old('domain', $tenant->domain) }}">
                                    @error('domain') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $tenant->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $tenant->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ $tenant->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Current Subscription</h6>
                                </div>
                                <div class="card-body">
                                    @if($tenant->subscription)
                                        <p><strong>Plan:</strong> {{ $tenant->subscription->plan->name }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $tenant->subscription->status === 'active' ? 'success' : ($tenant->subscription->status === 'trialing' ? 'info' : 'warning') }}">
                                                {{ ucfirst($tenant->subscription->status) }}
                                            </span>
                                        </p>
                                        <p><strong>Billing Cycle:</strong> {{ ucfirst($tenant->subscription->billing_cycle) }}</p>
                                        <p><strong>Amount:</strong> Rs. {{ number_format($tenant->subscription->amount) }}</p>
                                        <p><strong>Starts:</strong> {{ $tenant->subscription->starts_at->format('M d, Y') }}</p>
                                        <p><strong>Ends:</strong> {{ $tenant->subscription->ends_at->format('M d, Y') }}</p>
                                        <p><strong>Days Remaining:</strong> {{ $tenant->subscription->daysRemaining() }}</p>
                                        <a href="{{ route('superadmin.subscriptions.edit', $tenant->subscription) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit Subscription
                                        </a>
                                    @else
                                        <p class="text-muted">No active subscription</p>
                                        <a href="{{ route('superadmin.subscriptions.create') }}?tenant_id={{ $tenant->id }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus me-1"></i> Add Subscription
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Users ({{ $tenant->users->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($tenant->users->take(5) as $user)
                                        <li class="mb-2">
                                            <i class="bi bi-person me-2"></i>{{ $user->name }}
                                            <br><small class="text-muted">{{ $user->email }}</small>
                                        </li>
                                        @endforeach
                                        @if($tenant->users->count() > 5)
                                        <li class="text-muted">...and {{ $tenant->users->count() - 5 }} more</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Tenant
                            </button>
                            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
