@extends('admin.includes.main')

@section('title', 'Wastage Details')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Wastage Details" route="admin.wastages.index" button="Back" icon="bi-arrow-left" />

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Wastage Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Wastage No:</th>
                                <td>{{ $wastage->wastage_no }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $wastage->wastage_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Reason:</th>
                                <td>{{ ucfirst(str_replace('_', ' ', $wastage->reason)) }}</td>
                            </tr>
                            <tr>
                                <th>Total Cost:</th>
                                <td>{{ number_format($wastage->total_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $wastage->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($wastage->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created By:</th>
                                <td>{{ $wastage->user->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        @if($wastage->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p>{{ $wastage->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Wastage Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Batch No</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wastage->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->unit->name ?? 'N/A' }}</td>
                                        <td>{{ $item->batch_number ?? '-' }}</td>
                                        <td>{{ number_format($item->unit_cost, 2) }}</td>
                                        <td>{{ number_format($item->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total Cost:</th>
                                    <th>{{ number_format($wastage->total_cost, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('admin.wastages.destroy', $wastage->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Wastage</button>
            </form>
        </div>
    </div>
@endsection
