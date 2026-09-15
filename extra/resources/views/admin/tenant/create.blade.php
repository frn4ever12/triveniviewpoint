@extends('admin.includes.main')
@section('title', 'Create Tenant')
@section('content')
    <x-breadcrumb title="Create Tenant" route="admin.tenants.index" button="Back to Tenants" icon="bi-arrow-left">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Tenant</li>
    </x-breadcrumb>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Tenant</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Tenant Information</h6>
                            <div class="mb-3">
                                <label class="form-label">Tenant Name *</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                                @error('company_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                    @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country') ?? 'Nepal' }}">
                                    @error('country') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') }}">
                                    @error('pan_no') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Domain</label>
                                    <input type="text" name="domain" class="form-control" value="{{ old('domain') }}">
                                    @error('domain') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Subscription Plan</h6>
                            <div class="mb-3">
                                <label class="form-label">Select Plan *</label>
                                <select name="plan_id" class="form-select" required>
                                    <option value="">Choose a plan...</option>
                                    @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - Rs. {{ number_format($plan->monthly_price) }}/month
                                        @if($plan->trial_days > 0) ({{ $plan->trial_days }} days trial) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('plan_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            @foreach($plans as $plan)
                            <div class="card mb-2 @if(old('plan_id') == $plan->id) border-primary @endif">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $plan->name }}</strong>
                                            @if($plan->is_popular) <span class="badge bg-warning ms-2">Popular</span> @endif
                                            <br><small class="text-muted">{{ $plan->description }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">Rs. {{ number_format($plan->monthly_price) }}/mo</div>
                                            <small class="text-muted">{{ $plan->max_users }} users, {{ $plan->max_menu_items }} items</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <h6 class="text-primary mb-3 mt-4">Admin User</h6>
                            <div class="mb-3">
                                <label class="form-label">Admin Name *</label>
                                <input type="text" name="admin_name" class="form-control" required value="{{ old('admin_name') }}">
                                @error('admin_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Admin Email *</label>
                                <input type="email" name="admin_email" class="form-control" required value="{{ old('admin_email') }}">
                                @error('admin_email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="admin_password" class="form-control" required minlength="8">
                                @error('admin_password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Create Tenant
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
