@extends('admin.includes.main')
@section('title', 'Create Staff')
@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Create New Staff" route="admin.staff.index" button="Back to List" icon="bi-arrow-left" />
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 class="fw-semibold mb-3"><i class="bi bi-person-badge me-1"></i> Personal Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="staff@example.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone') }}" placeholder="e.g. 98XXXXXXXX">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth"
                            name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" rows="2" placeholder="Street, city, state...">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="fw-semibold mb-3"><i class="bi bi-shield-lock me-1"></i> Account & Access</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">-- Select Role --</option>
                            @foreach ($roles->where('name', '!=', 'superadmin') as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Min. 8 characters">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Leave empty to create without login access.</div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="Re-enter password">
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="fw-semibold mb-3"><i class="bi bi-image me-1"></i> Profile Image</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label for="profile_image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                            id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/gif,image/webp">
                        @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">JPEG, PNG, GIF, WebP. Max 2MB.</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i> Create Staff
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
