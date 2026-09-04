@extends('admin.includes.main')

@section('title', 'Kitchen Consumption')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Kitchen Consumption" route="admin.kitchen-consumptions.create" button="Record Consumption" icon="bi-plus-circle" />

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Consumption No</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Reason</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consumptions as $consumption)
                                <tr>
                                    <td>{{ $consumption->consumption_no }}</td>
                                    <td>{{ $consumption->consumption_date->format('Y-m-d') }}</td>
                                    <td>{{ $consumption->kitchen_department ?? '-' }}</td>
                                    <td>{{ $consumption->reason ?? '-' }}</td>
                                    <td>{{ number_format($consumption->total_cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $consumption->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($consumption->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.kitchen-consumptions.show', $consumption->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.kitchen-consumptions.destroy', $consumption->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="7" class="text-center">No kitchen consumption records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
