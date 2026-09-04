@extends('admin.includes.main')
@section('title', 'Tenants')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <x-breadcrumb title="Tenants" route="admin.tenants.create" button="Add New Tenant" icon="bi-plus-circle">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tenants</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
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
                                @if($tenant->domain)
                                <br><small class="text-muted">{{ $tenant->domain }}</small>
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
                                @elseif($tenant->status === 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                @else
                                    <span class="badge bg-danger">Suspended</span>
                                @endif
                                @if($tenant->isOnTrial())
                                    <br><small class="text-warning">Trial: {{ $tenant->trial_ends_at->diffForHumans() }}</small>
                                @endif
                            </td>
                            <td>{{ $tenant->users->count() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
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
