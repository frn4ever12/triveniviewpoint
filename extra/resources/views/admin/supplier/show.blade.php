@extends('admin.includes.main')
@section('title', 'View Supplier')
@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Supplier Details" route="admin.suppliers.index" button="Back to List" icon="bi-arrow-left" />
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="bi bi-eye"></i> Supplier Details</h3>
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="mb-3">Basic Information</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px;">Name</th><td>{{ $supplier->name }}</td></tr>
                                <tr><th>Company Name</th><td>{{ $supplier->company_name ?? '—' }}</td></tr>
                                <tr><th>Contact Person</th><td>{{ $supplier->contact_person ?? '—' }}</td></tr>
                                <tr><th>Email</th><td>{{ $supplier->email ?? '—' }}</td></tr>
                                <tr><th>Phone</th><td>{{ $supplier->phone ?? '—' }}</td></tr>
                                <tr><th>Alternate Phone</th><td>{{ $supplier->alternate_phone ?? '—' }}</td></tr>
                                <tr><th>Website</th><td>@if($supplier->website)<a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a>@else—@endif</td></tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $supplier->status->value === 'active' ? 'success' : 'danger' }}">{{ $supplier->status->label() }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3">Address</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px;">Address</th><td>{{ $supplier->address ?? '—' }}</td></tr>
                                <tr><th>City</th><td>{{ $supplier->city ?? '—' }}</td></tr>
                                <tr><th>State</th><td>{{ $supplier->state ?? '—' }}</td></tr>
                                <tr><th>Postal Code</th><td>{{ $supplier->postal_code ?? '—' }}</td></tr>
                                <tr><th>Country</th><td>{{ $supplier->country ?? '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3">Business Information</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px;">PAN Number</th><td>{{ $supplier->pan_no ?? '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3">Notes</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px;">Notes</th><td>{{ $supplier->notes ?? '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3">Timestamps</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px;">Created At</th><td>{{ $supplier->created_at?->format('d-m-Y H:i:s') ?? '—' }}</td></tr>
                                <tr><th>Updated At</th><td>{{ $supplier->updated_at?->format('d-m-Y H:i:s') ?? '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
