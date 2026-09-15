@extends('superadmin.includes.main')

@section('title', 'Edit Role')

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Edit Role: {{ ucfirst($role->name) }}</h1>
            <p class="page-subtitle">Update role permissions</p>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $role->name) }}"
                                   {{ $role->name === 'superadmin' ? 'readonly' : '' }} required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($role->name === 'superadmin')
                                <div class="form-text text-warning">The superadmin role name cannot be changed.</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-shield-check me-1"></i> Permissions
                        </h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                            <label class="form-check-label" for="selectAllPermissions">Select / Deselect All</label>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach ($permissions as $module => $modulePermissions)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border h-100">
                                    <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                                        <span class="fw-medium text-capitalize">{{ $module }}</span>
                                        <div class="form-check">
                                            <input class="form-check-input module-check-all" type="checkbox"
                                                   id="check-all-{{ $module }}" data-module="{{ $module }}">
                                            <label class="form-check-label small" for="check-all-{{ $module }}">
                                                All
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2 px-3">
                                        @foreach ($modulePermissions as $permission)
                                            <div class="form-check form-switch mb-1">
                                                <input class="form-check-input permission-checkbox"
                                                       type="checkbox" role="switch"
                                                       id="perm-{{ $permission->id }}"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       data-module="{{ $module }}"
                                                       {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="perm-{{ $permission->id }}">
                                                    {{ ucfirst(str_replace('-', ' ', last(explode('.', $permission->name)))) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('permissions')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Role
                        </button>
                        <a href="{{ route('superadmin.roles.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all / deselect all across modules
    document.getElementById('selectAllPermissions').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(function (cb) {
            cb.checked = this.checked;
        }, this);
        document.querySelectorAll('.module-check-all').forEach(function (cb) {
            cb.checked = this.checked;
        }, this);
    });

    // Select-all per module
    document.querySelectorAll('.module-check-all').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const module = this.dataset.module;
            document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]')
                .forEach(function (cb) { cb.checked = checkbox.checked; });
            updateGlobalSelectAll();
        });
    });

    // Update per-module "All" checkbox when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const module = this.dataset.module;
            const allCbs = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            document.getElementById('check-all-' + module).checked = Array.from(allCbs).every(c => c.checked);
            updateGlobalSelectAll();
        });
    });

    function updateGlobalSelectAll() {
        const all = document.querySelectorAll('.permission-checkbox');
        document.getElementById('selectAllPermissions').checked = Array.from(all).every(c => c.checked);
    }

    // Initial state
    updateGlobalSelectAll();
});
</script>
@endpush
