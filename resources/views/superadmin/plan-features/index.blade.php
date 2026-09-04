@extends('superadmin.includes.main')
@section('title', 'Plan Features')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Plan Features</h1>
            <p class="page-subtitle">Manage features for {{ $plan->name }}</p>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Features for {{ $plan->name }}</h5>
                <a href="{{ route('superadmin.plan-features.create', $plan->id) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Feature
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($features as $feature)
                            <tr>
                                <td><code>{{ $feature->code }}</code></td>
                                <td>{{ $feature->name }}</td>
                                <td>{{ $feature->description ?? '—' }}</td>
                                <td>{{ $feature->value ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $feature->is_enabled ? 'success' : 'secondary' }}">
                                        {{ $feature->is_enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </td>
                                <td>{{ $feature->sort_order }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('superadmin.plan-features.edit', $feature) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('superadmin.plan-features.destroy', $feature) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this feature?')">
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
            </div>
        </div>
    </div>
@endsection
