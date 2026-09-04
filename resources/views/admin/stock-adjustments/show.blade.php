@extends('admin.includes.main')

@section('title', 'Stock Adjustment Details')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Stock Adjustment Details" route="admin.stock-adjustments.index" button="Back" icon="bi-arrow-left" />

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Adjustment Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Adjustment No:</th>
                                <td>{{ $adjustment->adjustment_no }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $adjustment->adjustment_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>
                                    <span class="badge bg-{{ $adjustment->adjustment_type === 'increase' ? 'success' : 'danger' }}">
                                        {{ ucfirst($adjustment->adjustment_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Reason:</th>
                                <td>{{ ucfirst($adjustment->reason) }}</td>
                            </tr>
                            <tr>
                                <th>Total Cost:</th>
                                <td>{{ number_format($adjustment->total_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $adjustment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($adjustment->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created By:</th>
                                <td>{{ $adjustment->user->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        @if($adjustment->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p>{{ $adjustment->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Adjustment Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Unit Cost</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adjustment->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->unit->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->unit_cost, 2) }}</td>
                                        <td>{{ number_format($item->total_value, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Cost:</th>
                                    <th>{{ number_format($adjustment->total_cost, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('admin.stock-adjustments.destroy', $adjustment->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Adjustment</button>
            </form>
        </div>
    </div>
@endsection
