@extends('admin.includes.main')

@section('title', 'View Label')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Label Details" route="admin.labels.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-eye"></i> Menu Details
                </h3>
                <div class="card-tools ">
                    <a href="{{ route('admin.labels.edit', $unit) }}" class="btn btn-sm btn-warning">
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
                                    <th style="width: 200px;">Name</th>
                                    <td>{{ $label->name }}</td>
                                </tr>
                               
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span
                                            class="badge bg-{{ $label->status->value === 'active' ? 'success' : 'danger' }}">
                                            {{ $label->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $label->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $label->updated_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

@endsection
