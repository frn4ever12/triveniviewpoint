@extends('superadmin.includes.main')
@section('title', 'Subscriptions')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Subscriptions Management</h1>
            <p class="page-subtitle">Manage all tenant subscriptions</p>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Subscriptions</h5>
                <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Subscription
                </a>
            </div>
            <div class="card-body py-0 px-2">
                <table class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Cycle</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $subscription->tenant->name }}</strong>
                                <br><small class="text-muted">{{ $subscription->tenant->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $subscription->plan->name }}</span>
                            </td>
                            <td>Rs. {{ number_format($subscription->amount) }}</td>
                            <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                            <td>
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'info' : ($subscription->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td>{{ $subscription->starts_at->format('M d, Y') }}</td>
                            <td>{{ $subscription->ends_at->format('M d, Y') }}</td>
                            <td>
                                <span class="{{ $subscription->daysRemaining() <= 7 ? 'text-danger' : 'text-success' }}">
                                    {{ $subscription->daysRemaining() }} days
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.subscriptions.show', $subscription) }}" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($subscription->isActive())
                                        <form action="{{ route('superadmin.subscriptions.cancel', $subscription) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning" title="Cancel" onclick="return confirm('Cancel this subscription?')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($subscription->isExpired() || $subscription->isCancelled())
                                        <form action="{{ route('superadmin.subscriptions.renew', $subscription) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Renew" onclick="return confirm('Renew this subscription?')">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                pageLength: 25,
                order: [[0, 'asc']]
            });
        });
    </script>
@endpush
