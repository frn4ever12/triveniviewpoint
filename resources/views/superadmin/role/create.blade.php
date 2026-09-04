@extends('superadmin.includes.main')

@section('title', 'Create Role')

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Create New Role</h1>
            <p class="page-subtitle">Add a new role with permissions</p>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.roles.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="e.g. manager, receptionist" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use lowercase letters, no spaces (e.g. "floor_manager").</div>
                        </div>
                    </div>

                    <h5 class="mb-3 fw-semibold">
                        <i class="bi bi-shield-check me-1"></i> Assign Permissions
                    </h5>

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
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                            <i class="bi bi-check-lg me-2"></i> Create Role
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
    // Select-all / deselect-all per module
    document.querySelectorAll('.module-check-all').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const module = this.dataset.module;
            document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]')
                .forEach(function (cb) { cb.checked = checkbox.checked; });
        });
    });

    // Update "All" checkbox when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const module = this.dataset.module;
            const allCbs = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            const allChecked = Array.from(allCbs).every(c => c.checked);
            document.getElementById('check-all-' + module).checked = allChecked;
        });
    });
});
</script>
@endpush
