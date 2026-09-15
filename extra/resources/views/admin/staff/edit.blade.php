@extends('admin.includes.main')
@section('title', 'Edit Staff')
@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Edit Staff: {{ $staff->name }}" route="admin.staff.index" button="Back to List" icon="bi-arrow-left" />
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="fw-semibold mb-3"><i class="bi bi-person-badge me-1"></i> Personal Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $staff->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $staff->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone', $staff->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth"
                            name="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" rows="2">{{ old('address', $staff->address) }}</textarea>
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
                                <option value="{{ $role->name }}"
                                    {{ old('role', $staff->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $staff->status->value) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $staff->status->value) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $staff->status->value) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ old('status', $staff->status->value) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Leave empty to keep current">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Min. 8 characters. Leave empty to keep current password.</div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password">
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="fw-semibold mb-3"><i class="bi bi-image me-1"></i> Profile Image</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label for="profile_image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                            id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/gif,image/webp">
                        @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">JPEG, PNG, GIF, WebP. Max 2MB. Leave empty to keep current.</div>
                        @if($staff->getFirstMediaUrl('profile_image', 'thumb'))
                            <div class="d-flex align-items-center gap-3 mt-2 p-2 bg-light rounded">
                                <img src="{{ $staff->getFirstMediaUrl('profile_image', 'thumb') }}"
                                     alt="Current profile" class="rounded-circle border"
                                     style="width:48px;height:48px;object-fit:cover;">
                                <div>
                                    <span class="fw-medium text-dark small">Current image</span>
                                    <div class="text-muted" style="font-size:0.72rem;">Upload a new one to replace</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i> Update Staff
                    </button>
                    <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-info">
                        <i class="bi bi-eye me-2"></i> View Details
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary ms-auto">
                        <i class="bi bi-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
