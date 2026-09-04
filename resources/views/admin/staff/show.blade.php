@extends('admin.includes.main')
@section('title', 'Staff Details')
@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Staff Details" route="admin.staff.index" button="Back to List" icon="bi-arrow-left" />
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="card-title mb-0">
                <i class="bi bi-person-badge me-1"></i> Staff Details
            </h3>
            <div>
                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-4 text-center mb-3">
                    @php $img = $staff->getFirstMediaUrl('profile_image', 'thumb'); @endphp
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $staff->name }}"
                             class="rounded-circle shadow-sm" style="width:120px;height:120px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center shadow-sm"
                             style="width:120px;height:120px;font-size:2.5rem;font-weight:700;">
                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                        </div>
                    @endif
                    <h4 class="mt-3 mb-1">{{ $staff->name }}</h4>
                    @php $roleName = $staff->getRoleNames()->first(); @endphp
                    @if($roleName)
                        <span class="badge bg-primary rounded-pill px-3 py-1">{{ ucfirst($roleName) }}</span>
                    @endif
                </div>
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:140px;background:#f8f9fa;">Email</th>
                                        <td>{{ $staff->email }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Phone</th>
                                        <td>{{ $staff->phone ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Gender</th>
                                        <td>{{ $staff->gender ? ucfirst($staff->gender) : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Date of Birth</th>
                                        <td>{{ $staff->date_of_birth?->format('d-m-Y') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:140px;background:#f8f9fa;">Status</th>
                                        <td>
                                            @php
                                                $statusColors = ['active' => 'success', 'inactive' => 'secondary', 'suspended' => 'warning', 'terminated' => 'danger'];
                                                $color = $statusColors[$staff->status->value] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }} rounded-pill px-2">
                                                {{ $staff->status->label() }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Login Access</th>
                                        <td>
                                            @if($staff->password)
                                                <span class="badge bg-{{ $staff->login_enabled ? 'success' : 'danger' }} rounded-pill px-2">
                                                    {{ $staff->login_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            @else
                                                <span class="text-muted">No password set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Address</th>
                                        <td>{{ $staff->address ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#f8f9fa;">Member Since</th>
                                        <td>{{ $staff->created_at?->format('d-m-Y') ?? '—' }}</td>
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
