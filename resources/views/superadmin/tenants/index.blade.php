@extends('superadmin.includes.main')
@section('title', 'Tenants Management')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Tenants Management</h1>
            <p class="page-subtitle">Manage all restaurant tenants on the platform</p>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Tenants</h5>
                <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Tenant
                </a>
            </div>
            <div class="card-body py-0 px-2">
                <table class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Users</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $tenant->name }}</strong>
                                @if($tenant->slug)
                                <br><small class="text-muted">{{ $tenant->slug }}</small>
                                @endif
                            </td>
                            <td>{{ $tenant->company_name ?? '—' }}</td>
                            <td>{{ $tenant->email }}</td>
                            <td>{{ $tenant->phone ?? '—' }}</td>
                            <td>
                                @if($tenant->subscription && $tenant->subscription->plan)
                                    <span class="badge bg-info">{{ $tenant->subscription->plan->name }}</span>
                                @else
                                    <span class="badge bg-warning">No Plan</span>
                                @endif
                            </td>
                            <td>
                                @if($tenant->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($tenant->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($tenant->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($tenant->status === 'suspended')
                                    <span class="badge bg-secondary">Suspended</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                @if($tenant->isOnTrial())
                                    <br><small class="text-warning">Trial: {{ $tenant->trial_ends_at->diffForHumans() }}</small>
                                @endif
                            </td>
                            <td>{{ $tenant->users->count() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($tenant->status === 'pending')
                                        <form action="{{ route('superadmin.tenants.approve', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Approve" onclick="return confirm('Approve this tenant?')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.tenants.reject', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Reject" onclick="return confirm('Reject this tenant?')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @elseif($tenant->status === 'active')
                                        <form action="{{ route('superadmin.tenants.suspend', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning" title="Suspend" onclick="return confirm('Suspend this tenant?')">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                    @elseif($tenant->status === 'suspended')
                                        <form action="{{ route('superadmin.tenants.activate', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Activate" onclick="return confirm('Activate this tenant?')">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('superadmin.tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this tenant?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $tenants->links() }}
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
