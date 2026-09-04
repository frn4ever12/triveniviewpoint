@extends('superadmin.includes.main')
@section('title', 'Create Subscription')
@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <h1 class="page-title">Create Subscription</h1>
            <p class="page-subtitle">Add a new subscription for a tenant</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Subscription</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.subscriptions.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tenant *</label>
                                <select name="tenant_id" class="form-select" required>
                                    <option value="">Select a tenant...</option>
                                    @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }} ({{ $tenant->email }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('tenant_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subscription Plan *</label>
                                <select name="plan_id" class="form-select" required id="planSelect">
                                    <option value="">Select a plan...</option>
                                    @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-monthly="{{ $plan->monthly_price }}" data-yearly="{{ $plan->yearly_price }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - Rs. {{ number_format($plan->monthly_price) }}/month
                                    </option>
                                    @endforeach
                                </select>
                                @error('plan_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Billing Cycle *</label>
                                <select name="billing_cycle" class="form-select" required id="billingCycle">
                                    <option value="monthly" {{ old('billing_cycle', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('billing_cycle') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" name="starts_at" class="form-control" required value="{{ old('starts_at', now()->format('Y-m-d')) }}">
                                @error('starts_at') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Subscription Summary</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Amount:</strong> <span id="amountDisplay">Rs. 0.00</span></p>
                                    <p><strong>Billing Cycle:</strong> <span id="cycleDisplay">Monthly</span></p>
                                    <p><strong>Start Date:</strong> <span id="startDateDisplay">{{ now()->format('M d, Y') }}</span></p>
                                    <p><strong>End Date:</strong> <span id="endDateDisplay">—</span></p>
                                    <p><strong>Next Billing:</strong> <span id="nextBillingDisplay">—</span></p>
                                </div>
                            </div>
                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Plan Details</h6>
                                </div>
                                <div class="card-body" id="planDetails">
                                    <p class="text-muted">Select a plan to view details</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Create Subscription
                            </button>
                            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const plans = @json($plans);
        
        function updateSummary() {
            const planId = document.getElementById('planSelect').value;
            const billingCycle = document.getElementById('billingCycle').value;
            const startDate = document.getElementById('starts_at').value;
            
            const plan = plans.find(p => p.id == planId);
            
            if (plan) {
                const amount = billingCycle === 'yearly' ? plan.yearly_price : plan.monthly_price;
                document.getElementById('amountDisplay').textContent = 'Rs. ' + parseFloat(amount).toFixed(2);
                document.getElementById('cycleDisplay').textContent = billingCycle.charAt(0).toUpperCase() + billingCycle.slice(1);
                
                // Calculate end date
                const start = new Date(startDate);
                const end = new Date(start);
                if (billingCycle === 'yearly') {
                    end.setFullYear(end.getFullYear() + 1);
                } else {
                    end.setMonth(end.getMonth() + 1);
                }
                
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                document.getElementById('endDateDisplay').textContent = end.toLocaleDateString('en-US', options);
                document.getElementById('nextBillingDisplay').textContent = end.toLocaleDateString('en-US', options);
                
                // Update plan details
                document.getElementById('planDetails').innerHTML = `
                    <p><strong>Plan:</strong> ${plan.name}</p>
                    <p><strong>Max Users:</strong> ${plan.max_users}</p>
                    <p><strong>Max Menu Items:</strong> ${plan.max_menu_items}</p>
                    <p><strong>Max Orders/Month:</strong> ${plan.max_orders_per_month || 'Unlimited'}</p>
                    <p><strong>Trial Days:</strong> ${plan.trial_days}</p>
                `;
            }
        }
        
        document.getElementById('planSelect').addEventListener('change', updateSummary);
        document.getElementById('billingCycle').addEventListener('change', updateSummary);
        document.getElementById('starts_at').addEventListener('change', updateSummary);
        
        // Initial call
        updateSummary();
    </script>
@endpush
