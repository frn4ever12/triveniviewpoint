@extends('admin.includes.main')
@section('title', 'Create User')
@section('content')
    <x-breadcrumb title="Create New User" route="admin.users.index" button="Back to List" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="phone" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="email" class="form-label">Username/Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="branch" class="form-label">Branch <span class="text-danger">*</span></label>
                                    <select class="form-select @error('branch') is-invalid @enderror" id="branch" name="branch" required>
                                        <option value="">-- Select Branch --</option>
                                        <option value="kathmandu" {{ old('branch') == 'kathmandu' ? 'selected' : '' }}>Kathmandu</option>
                                        <option value="pokhara" {{ old('branch') == 'pokhara' ? 'selected' : '' }}>Pokhara</option>
                                        <option value="butwal" {{ old('branch') == 'butwal' ? 'selected' : '' }}>Butwal</option>
                                        <option value="chitwan" {{ old('branch') == 'chitwan' ? 'selected' : '' }}>Chitwan</option>
                                    </select>
                                    @error('branch')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i data-feather="lock" style="width:18px;height:18px;"></i></span>
                                        <input type="password" class="form-control with-icon" id="password" name="password" placeholder="Enter password" required>
                                    </div>
                                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label" for="password_confirmation">Retype Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i data-feather="lock" style="width:18px;height:18px;"></i></span>
                                        <input type="password" class="form-control with-icon" id="password_confirmation" name="password_confirmation" placeholder="Retype password" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach(\App\Enums\CommonStatusEnum::cases() as $status)
                                            <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i> Create User</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-danger"><i class="bi bi-x-lg me-2"></i> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
