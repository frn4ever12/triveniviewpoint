@extends('admin.includes.main')

@section('title', 'Stock Adjustments')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Stock Adjustments" route="admin.stock-adjustments.create" button="New Adjustment" icon="bi-plus-circle" />

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Adjustment No</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adjustments as $adjustment)
                                <tr>
                                    <td>{{ $adjustment->adjustment_no }}</td>
                                    <td>{{ $adjustment->adjustment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $adjustment->adjustment_type === 'increase' ? 'success' : 'danger' }}">
                                            {{ ucfirst($adjustment->adjustment_type) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($adjustment->reason) }}</td>
                                    <td>{{ number_format($adjustment->total_cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $adjustment->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($adjustment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.stock-adjustments.show', $adjustment->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.stock-adjustments.destroy', $adjustment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No stock adjustments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
