@extends('admin.includes.main')

@section('title', 'Wastage')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Wastage" route="admin.wastages.create" button="Record Wastage" icon="bi-plus-circle" />

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Wastage No</th>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wastages as $wastage)
                                <tr>
                                    <td>{{ $wastage->wastage_no }}</td>
                                    <td>{{ $wastage->wastage_date->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $wastage->reason)) }}</td>
                                    <td>{{ number_format($wastage->total_cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $wastage->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($wastage->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.wastages.show', $wastage->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.wastages.destroy', $wastage->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="6" class="text-center">No wastage records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
