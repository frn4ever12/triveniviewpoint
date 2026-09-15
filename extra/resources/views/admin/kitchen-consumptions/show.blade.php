@extends('admin.includes.main')

@section('title', 'Kitchen Consumption Details')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Kitchen Consumption Details" route="admin.kitchen-consumptions.index" button="Back" icon="bi-arrow-left" />

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Consumption Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Consumption No:</th>
                                <td>{{ $consumption->consumption_no }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $consumption->consumption_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Kitchen Department:</th>
                                <td>{{ $consumption->kitchen_department ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Reason:</th>
                                <td>{{ $consumption->reason ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Cost:</th>
                                <td>{{ number_format($consumption->total_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $consumption->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($consumption->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created By:</th>
                                <td>{{ $consumption->user->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        @if($consumption->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p>{{ $consumption->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Consumption Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumption->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->unit->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->unit_cost, 2) }}</td>
                                        <td>{{ number_format($item->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Cost:</th>
                                    <th>{{ number_format($consumption->total_cost, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('admin.kitchen-consumptions.destroy', $consumption->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Consumption</button>
            </form>
        </div>
    </div>
@endsection
