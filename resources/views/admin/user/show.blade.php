@extends('admin.includes.main')

@section('title', 'View User')

@section('content')
    <x-breadcrumb title="User Details" route="admin.users.index" button="Back to List" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Details</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-eye">User Details
                        </h3>
                        <div class="card-tools ">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">Name</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td><code>{{ $user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td><code>{{ $user->role }}</td>
                                        </tr>
                                        <tr>
                                            <th>Branch</th>
                                            <td><code>{{ $user->branch }}</td>
                                        </tr>
                                       
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->status->value === 'active' ? 'success' : 'danger' }}">
                                                    {{ $user->status->label() }}
                                                </span>
                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $user->created_at->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $user->updated_at->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection