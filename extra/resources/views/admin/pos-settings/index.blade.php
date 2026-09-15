@extends('admin.includes.main')

@section('title')
    <title>POS Settings - {{ auth()->user()?->tenant?->name ?? 'Restaurant' }}</title>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">POS Settings</h4>
                <p class="text-muted mb-0">Configure your Point of Sale preferences</p>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.pos-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Tax & Charges -->
                            <div class="mb-4">
                                <h5 class="card-title mb-3">
                                    <i data-feather="percent" class="icon-xs me-2"></i>Tax & Charges
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">VAT Percent (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   class="form-control" 
                                                   name="vat_percent" 
                                                   value="{{ $settings['vat_percent'] ?? 13 }}"
                                                   required>
                                            <small class="text-muted">Value Added Tax percentage</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Service Charge (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   class="form-control" 
                                                   name="service_charge_percent" 
                                                   value="{{ $settings['service_charge_percent'] ?? 10 }}"
                                                   required>
                                            <small class="text-muted">Service charge percentage</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Settings -->
                            <div class="mb-4">
                                <h5 class="card-title mb-3">
                                    <i data-feather="credit-card" class="icon-xs me-2"></i>Payment Settings
                                </h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Default Payment Method</label>
                                    <select class="form-select" name="default_payment_method" required>
                                        <option value="cash" {{ $settings['default_payment_method'] == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ $settings['default_payment_method'] == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="esewa" {{ $settings['default_payment_method'] == 'esewa' ? 'selected' : '' }}>eSewa</option>
                                        <option value="khalti" {{ $settings['default_payment_method'] == 'khalti' ? 'selected' : '' }}>Khalti</option>
                                        <option value="fonepay" {{ $settings['default_payment_method'] == 'fonepay' ? 'selected' : '' }}>Fonepay</option>
                                    </select>
                                    <small class="text-muted">Default payment method for orders</small>
                                </div>
                            </div>

                            <!-- Receipt Settings -->
                            <div class="mb-4">
                                <h5 class="card-title mb-3">
                                    <i data-feather="file-text" class="icon-xs me-2"></i>Receipt Settings
                                </h5>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" 
                                           name="auto_print_receipt" 
                                           id="auto_print_receipt"
                                           {{ $settings['auto_print_receipt'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_print_receipt">
                                        Auto Print Receipt
                                    </label>
                                    <small class="text-muted d-block">Automatically print receipt after order completion</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Receipt Footer Text</label>
                                    <textarea class="form-control" 
                                              name="receipt_footer" 
                                              rows="2"
                                              maxlength="200">{{ $settings['receipt_footer'] ?? 'Thank you for dining with us!' }}</textarea>
                                    <small class="text-muted">Custom message at bottom of receipt (max 200 chars)</small>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="mb-4">
                                <h5 class="card-title mb-3">
                                    <i data-feather="settings" class="icon-xs me-2"></i>Features
                                </h5>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" 
                                           name="enable_kot" 
                                           id="enable_kot"
                                           {{ $settings['enable_kot'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_kot">
                                        Enable KOT (Kitchen Order Ticket)
                                    </label>
                                    <small class="text-muted d-block">Send orders to kitchen display system</small>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" 
                                           name="enable_table_reservation" 
                                           id="enable_table_reservation"
                                           {{ $settings['enable_table_reservation'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_table_reservation">
                                        Enable Table Reservation
                                    </label>
                                    <small class="text-muted d-block">Allow customers to reserve tables</small>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" class="icon-xs me-1"></i> Save Settings
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i data-feather="info" class="icon-xs me-2"></i>Quick Info
                        </h5>
                        <div class="alert alert-info">
                            <small>
                                <strong>VAT:</strong> Value Added Tax is applied to all orders.<br><br>
                                <strong>Service Charge:</strong> Additional service charge added to bills.<br><br>
                                <strong>KOT:</strong> Kitchen Order Ticket system sends orders directly to kitchen staff.
                            </small>
                        </div>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Note:</strong> Changes to VAT and service charge will apply to new orders only.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
