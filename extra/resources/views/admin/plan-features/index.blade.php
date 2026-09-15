@extends('admin.includes.main')
@section('title', 'Plan Features')
@section('content')
    <x-breadcrumb title="Plan Features" route="admin.plan-features.create" button="Add New Feature" icon="bi-plus-circle" :route-params="['planId' => $plan->id]">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Subscription Plans</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.show', $plan) }}">{{ $plan->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Features</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Features for {{ $plan->name }}</h5>
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
                                        <a href="{{ route('admin.plan-features.edit', $feature) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.plan-features.destroy', $feature) }}" method="POST" class="d-inline">
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
