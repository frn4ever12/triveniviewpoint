@extends('admin.includes.main')
@section('title', 'Tenant Details')
@section('content')
    <x-breadcrumb title="Tenant Details" route="admin.tenants.index" button="Back to Tenants" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $tenant->name }}</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tenant Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Name:</strong></td>
                                <td>{{ $tenant->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Company:</strong></td>
                                <td>{{ $tenant->company_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $tenant->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $tenant->phone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $tenant->address ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>City:</strong></td>
                                <td>{{ $tenant->city ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Country:</strong></td>
                                <td>{{ $tenant->country ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>PAN No:</strong></td>
                                <td>{{ $tenant->pan_no ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Domain:</strong></td>
                                <td>{{ $tenant->domain ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($tenant->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($tenant->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Trial:</strong></td>
                                <td>
                                    @if($tenant->isOnTrial())
                                        <span class="text-warning">{{ $tenant->trial_ends_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-muted">No trial</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                        <div class="mt-3">
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Current Subscription</h5>
                    </div>
                    <div class="card-body">
                        @if($tenant->subscription)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Plan:</strong> {{ $tenant->subscription->plan->name }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $tenant->subscription->status === 'active' ? 'success' : ($tenant->subscription->status === 'trialing' ? 'info' : 'warning') }}">
                                            {{ ucfirst($tenant->subscription->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Billing Cycle:</strong> {{ ucfirst($tenant->subscription->billing_cycle) }}</p>
                                    <p><strong>Amount:</strong> Rs. {{ number_format($tenant->subscription->amount) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Start Date:</strong> {{ $tenant->subscription->starts_at->format('M d, Y') }}</p>
                                    <p><strong>End Date:</strong> {{ $tenant->subscription->ends_at->format('M d, Y') }}</p>
                                    <p><strong>Next Billing:</strong> {{ $tenant->subscription->next_billing_at->format('M d, Y') }}</p>
                                    <p><strong>Days Remaining:</strong> {{ $tenant->subscription->daysRemaining() }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.subscriptions.edit', $tenant->subscription) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i> Edit Subscription
                                </a>
                                @if($tenant->subscription->isActive())
                                    <form action="{{ route('admin.subscriptions.cancel', $tenant->subscription) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this subscription?')">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <p class="text-muted">No active subscription</p>
                            <a href="{{ route('admin.subscriptions.create') }}?tenant_id={{ $tenant->id }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus me-1"></i> Add Subscription
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Users ({{ $tenant->users->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenant->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-secondary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Subscription History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Cycle</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenant->subscriptions as $sub)
                                    <tr>
                                        <td>{{ $sub->plan->name }}</td>
                                        <td>Rs. {{ number_format($sub->amount) }}</td>
                                        <td>{{ ucfirst($sub->billing_cycle) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $sub->status === 'active' ? 'success' : ($sub->status === 'trialing' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $sub->starts_at->format('M d, Y') }} - {{ $sub->ends_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
