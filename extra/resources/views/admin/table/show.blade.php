@extends('admin.includes.main')

@section('title', 'View Table')

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="View Table" route="admin.tables.index" button="Back to List" icon="bi-arrow-left" />

    <div class="card">
        <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-eye"></i> Table Details
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.tables.edit', $table) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $table->name }}</td>
                            </tr>

                            <tr>
                                <th>Slug</th>
                                <td><code>{{ $table->slug }}</code></td>
                            </tr>

                            <tr>
                                <th>Capacity</th>
                                <td>{{ $table->capacity }}</td>
                            </tr>

                            <tr>
                                <th>Type</th>
                                <td>{{ ucfirst($table->table_type ?? '—') }}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge 
                                        bg-{{ $table->status->value === 'available' ? 'success' : ($table->status->value === 'occupied' ? 'danger' : 'warning') }}">
                                        {{ $table->status->label() }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Location</th>
                                <td>{{ $table->location ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th>Floor</th>
                                <td>{{ $table->floor ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th>Section</th>
                                <td>{{ $table->section ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th>Features</th>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        <li>Air Conditioning: {{ $table->has_air_conditioning ? 'Yes' : 'No' }}</li>
                                        <li>TV: {{ $table->has_tv ? 'Yes' : 'No' }}</li>
                                        <li>WiFi: {{ $table->has_wifi ? 'Yes' : 'No' }}</li>
                                        <li>Smoking Allowed: {{ $table->is_smoking_allowed ? 'Yes' : 'No' }}</li>
                                        <li>Wheelchair Accessible: {{ $table->is_wheelchair_accessible ? 'Yes' : 'No' }}</li>
                                    </ul>
                                </td>
                            </tr>

                            <tr>
                                <th>Assigned Waiter</th>
                                <td>{{ $table->assigned_waiter?->name ?? '—' }}</td>
                            </tr>

                            

                            <tr>
                                <th>Reserved Until</th>
                                <td>{{ $table->reserved_until?->format('d-m-Y H:i') ?? '—' }}</td>
                            </tr>

                            
                            <tr>
                                <th>Notes</th>
                                <td>{{ $table->notes ?? '—' }}</td>
                            </tr>

                            
                            <tr>
                                <th>Created At</th>
                                <td>{{ $table->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>

                            <tr>
                                <th>Updated At</th>
                                <td>{{ $table->updated_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
