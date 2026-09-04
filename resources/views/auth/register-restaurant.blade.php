@extends('frontend.includes.auth-main')

@section('content')
<div class="auth-wrapper">
    <div class="auth-container" style="max-width: 1200px;">
        <div class="auth-card" style="max-width: 100%;">
            <div class="auth-brand">
                <div class="auth-logo">
                    @if(isset($logoUrl) && $logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName ?? 'RestaurantPro' }}" class="auth-logo-img">
                    @else
                        <i class="bi bi-cup-hot-fill"></i>
                    @endif
                </div>
                <h1 class="auth-title">Register Your Restaurant</h1>
                <p class="auth-subtitle">Start your restaurant's journey with our POS system</p>
            </div>

            <form class="auth-form" action="{{ route('register.restaurant.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Restaurant Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Restaurant Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="auth-label">Restaurant Name *</label>
                                    <input type="text" id="restaurant_name" name="restaurant_name" class="auth-input" value="{{ old('restaurant_name') }}" required autofocus>
                                    @error('restaurant_name')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Company Name</label>
                                    <input type="text" id="company_name" name="company_name" class="auth-input" value="{{ old('company_name') }}">
                                    @error('company_name')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Restaurant Email *</label>
                                    <input type="email" id="email" name="email" class="auth-input" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Phone Number</label>
                                    <input type="text" id="phone" name="phone" class="auth-input" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="auth-label">Address</label>
                                    <textarea id="address" name="address" class="auth-input" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">City</label>
                                    <input type="text" id="city" name="city" class="auth-input" value="{{ old('city') }}">
                                    @error('city')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Country</label>
                                    <input type="text" id="country" name="country" class="auth-input" value="{{ old('country', 'Nepal') }}">
                                    @error('country')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">PAN Number</label>
                                    <input type="text" id="pan_no" name="pan_no" class="auth-input" value="{{ old('pan_no') }}">
                                    @error('pan_no')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Admin Account Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Admin Account</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="auth-label">Admin Name *</label>
                                    <input type="text" id="admin_name" name="admin_name" class="auth-input" value="{{ old('admin_name') }}" required>
                                    @error('admin_name')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Admin Email *</label>
                                    <input type="email" id="admin_email" name="admin_email" class="auth-input" value="{{ old('admin_email') }}" required>
                                    @error('admin_email')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Password *</label>
                                    <input type="password" id="password" name="password" class="auth-input" required autocomplete="new-password">
                                    @error('password')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="auth-label">Confirm Password *</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="auth-input" required autocomplete="new-password">
                                    @error('password_confirmation')
                                        <div class="auth-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Plan Selection -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6 sticky top-4">
                            <h3 class="text-lg font-semibold mb-4">Choose Your Plan</h3>
                            
                            <div class="space-y-4">
                                @foreach($plans as $plan)
                                <div class="border rounded-lg p-4 cursor-pointer plan-card @if(old('plan_id') == $plan->id) border-indigo-500 bg-indigo-50 @endif" data-plan="{{ $plan->id }}">
                                    <input type="radio" name="plan_id" value="{{ $plan->id }}" class="hidden" {{ old('plan_id') == $plan->id ? 'checked' : '' }} required>
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">{{ $plan->name }}</h4>
                                            @if($plan->is_popular)
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Popular</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-indigo-600">Rs. {{ number_format($plan->monthly_price) }}</div>
                                            <div class="text-xs text-gray-500">/month</div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-3">{{ $plan->description }}</p>
                                    
                                    <ul class="text-sm space-y-1">
                                        <li>✓ {{ $plan->max_users }} Users</li>
                                        <li>✓ {{ $plan->max_menu_items }} Menu Items</li>
                                        <li>✓ {{ $plan->max_orders_per_month ? $plan->max_orders_per_month . ' Orders/mo' : 'Unlimited Orders' }}</li>
                                        <li>✓ {{ $plan->trial_days }} Days Trial</li>
                                    </ul>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                <label class="auth-label">Billing Cycle *</label>
                                <select id="billing_cycle" name="billing_cycle" class="auth-input" required>
                                    <option value="monthly" {{ old('billing_cycle', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly (Save 17%)</option>
                                </select>
                                @error('billing_cycle')
                                    <div class="auth-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="auth-button w-full">
                                    <span>Register Restaurant</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                            <div class="mt-4 text-center">
                                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                    Already have an account? Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="auth-footer">
                &copy; {{ date('Y') }} {{ $siteName ?? 'RestaurantPro' }} &mdash; Admin Panel
            </div>
        </div>
    </div>
</div>

<style>
    .grid {
        display: grid;
        gap: 1.5rem;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    @media (min-width: 768px) {
        .md\:col-span-2 {
            grid-column: span 2 / span 2;
        }
    }
    
    @media (min-width: 1024px) {
        .lg\:col-span-1 {
            grid-column: span 1 / span 1;
        }
        
        .lg\:col-span-2 {
            grid-column: span 2 / span 2;
        }
        
        .lg\:col-span-3 {
            grid-column: span 3 / span 3;
        }
    }
    
    .sticky {
        position: sticky;
    }
    
    .top-4 {
        top: 1rem;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    
    .rounded-lg {
        border-radius: 0.5rem;
    }
    
    .p-6 {
        padding: 1.5rem;
    }
    
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
    
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    
    .border {
        border: 1px solid #e5e7eb;
    }
    
    .border-indigo-500 {
        border-color: #6366f1;
    }
    
    .bg-indigo-50 {
        background-color: #eef2ff;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .font-semibold {
        font-weight: 600;
    }
    
    .text-lg {
        font-size: 1.125rem;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem;
    }
    
    .mt-6 {
        margin-top: 1.5rem;
    }
    
    .mt-4 {
        margin-top: 1rem;
    }
    
    .flex {
        display: flex;
    }
    
    .justify-between {
        justify-content: space-between;
    }
    
    .items-start {
        align-items: flex-start;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .bg-yellow-100 {
        background-color: #fef3c7;
    }
    
    .text-yellow-800 {
        color: #92400e;
    }
    
    .px-2 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .py-1 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    
    .rounded-full {
        border-radius: 9999px;
    }
    
    .font-bold {
        font-weight: 700;
    }
    
    .text-indigo-600 {
        color: #4f46e5;
    }
    
    .text-gray-500 {
        color: #6b7280;
    }
    
    .text-gray-600 {
        color: #4b5563;
    }
    
    .space-y-1 > * + * {
        margin-top: 0.25rem;
    }
    
    .w-full {
        width: 100%;
    }
    
    .text-center {
        text-align: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planCards = document.querySelectorAll('.plan-card');
        
        planCards.forEach(card => {
            card.addEventListener('click', function() {
                const planId = this.dataset.plan;
                const radio = this.querySelector('input[type="radio"]');
                
                // Remove active class from all cards
                planCards.forEach(c => {
                    c.classList.remove('border-indigo-500', 'bg-indigo-50');
                });
                
                // Add active class to clicked card
                this.classList.add('border-indigo-500', 'bg-indigo-50');
                
                // Check the radio
                radio.checked = true;
            });
        });
    });
</script>
@endsection
