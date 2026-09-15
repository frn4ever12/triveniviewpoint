@extends('admin.includes.main')

@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Edit Profile" route="dashboard" button="Back to Dashboard" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" 
                                data-bs-target="#profile" type="button" role="tab" aria-controls="profile" 
                                aria-selected="true">
                            <i class="fas fa-user"></i> Profile Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" 
                                data-bs-target="#password" type="button" role="tab" aria-controls="password" 
                                aria-selected="false">
                            <i class="fas fa-lock"></i> Change Password
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabContent">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="pt-4">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        @if ($user->getFirstMediaUrl('profile_image'))
                                            <div class="current-profile_image mb-2">
                                                <img src="{{ $user->getFirstMediaUrl('profile_image', 'thumb') }}" 
                                                     alt="Current Profile Image" class="img-thumbnail" 
                                                     style="width: 120px; height: 80px;">
                                                <div class="form-text">Current Profile Image</div>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                               id="profile_image" name="profile_image" accept="image/*">
                                        <div class="form-text">Max size: 2MB. Recommended: 800x600px</div>
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                            @foreach(\App\Enums\CommonStatusEnum::cases() as $status)
                                                <option value="{{ $status->value }}"
                                                    {{ old('status', $user->status) == $status->value ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                   <!-- Password Change Tab -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <div class="pt-4">
                            <form method="POST" action="{{ route('password.update') }}">
                                @method('PUT')
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label">
                                            Current Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                            id="current_password" name="current_password" required 
                                            placeholder="Enter your current password">
                                        @error('current_password', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="password" class="form-label">
                                            New Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                            id="password" name="password" required 
                                            placeholder="Enter new password">
                                        @error('password', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="password_confirmation" class="form-label">
                                            Confirm New Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" required 
                                            placeholder="Confirm new password">
                                        @error('password_confirmation', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show password tab if there are password validation errors
        @if($errors->updatePassword->any())
            document.addEventListener('DOMContentLoaded', function() {
                var passwordTab = new bootstrap.Tab(document.querySelector('#password-tab'));
                passwordTab.show();
            });
        @endif
    </script>
    @endpush
@endsection