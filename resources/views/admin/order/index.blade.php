@extends('admin.includes.main')
@section('title', 'Orders')

@push('head')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="cache-buster" content="{{ time() }}">
<script>
    // Force reload with timestamp to bypass all caches
    (function() {
        var url = new URL(window.location.href);
        if (!url.searchParams.has('_t')) {
            url.searchParams.set('_t', Date.now());
            window.location.replace(url.toString());
        }
    })();
</script>
@endpush

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Orders</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Manage dine-in orders, tables, and KOTs.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-primary btn-sm rounded-3 nav-btn active" data-target="order">Orders</button>
                <a href="{{ route('admin.orders.details') }}" class="btn btn-outline-primary btn-sm rounded-3">Order List</a>
                <button class="btn btn-outline-primary btn-sm rounded-3 nav-btn" data-target="table">Tables</button>
                <button class="btn btn-outline-primary btn-sm rounded-3 nav-btn" data-target="kot">KOT</button>
                <a target="_blank" href="{{ route('admin.orders.pos') }}" class="btn btn-outline-primary btn-sm rounded-3">POS</a>
            </div>
        </div>

        <div id="content">
            {{-- Orders Section --}}
            <div id="order" class="content-pane">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Recent Orders</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary rounded-3" id="addNewOrderBtn" onclick="openAddOrderModal()">
                                    <i class="bi bi-plus-lg me-1"></i> Add New Order
                                </button>
                                <button class="btn btn-sm btn-success rounded-3" onclick="openQuickBilling()">
                                    <i class="bi bi-lightning me-1"></i> Quick Billing
                                </button>
                                <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="refreshOrders()">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button class="btn btn-sm btn-outline-primary order-tab active" data-type="all">
                                All Orders <span class="badge bg-primary ms-1" id="count-all">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="dine_in">
                                Dine In <span class="badge bg-primary ms-1" id="count-dine_in">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="takeaway">
                                Takeaway <span class="badge bg-primary ms-1" id="count-takeaway">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="delivery">
                                Delivery <span class="badge bg-primary ms-1" id="count-delivery">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="online">
                                Online <span class="badge bg-primary ms-1" id="count-online">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="cancelled">
                                Cancelled <span class="badge bg-danger ms-1" id="count-cancelled">0</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary order-tab" data-type="history">
                                History <span class="badge bg-secondary ms-1" id="count-history">0</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3" id="ordersTableBody">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tables Section --}}
            <div id="table" class="content-pane d-none">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Restaurant Tables</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach ($tables as $table)
                                <div class="table-ov-card {{ $table->status == \App\Enums\TableStatusEnum::AVAILABLE ? 'avail' : 'occ' }}"
                                     onclick="handleTableClick('{{ $table->name }}', {{ $table->id }}, '{{ $table->status }}')">
                                    <div class="table-ov-name">{{ $table->name }}</div>
                                    <div class="table-ov-status">{{ $table->status }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOT Section --}}
            <div id="kot" class="content-pane d-none">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Kitchen Order Tickets</h5>
                        <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="refreshKOTs()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-4" id="kotList">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Billing Modal --}}
        <div class="modal fade" id="quickBillingModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;border:none;">
                    <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #1A73E8 0%, #0066FF 100%);">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div>
                                <h5 class="modal-title mb-1 fw-bold text-white"><i class="bi bi-lightning-fill text-warning me-2"></i>Quick Billing</h5>
                                <p class="mb-0 text-white-50 small">Walk-in / Takeaway • Fast & Easy</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="text-white small mb-0">Order Type:</label>
                                    <select class="form-select form-select-sm" id="quickOrderType" style="width:120px;border-radius:6px;">
                                        <option value="walk_in">Walk-in</option>
                                        <option value="takeaway">Takeaway</option>
                                    </select>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded px-3 py-1">
                                    <span class="text-white small">Order #:</span>
                                    <span class="text-white fw-bold" id="quickOrderNumber">--</span>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <!-- LEFT SIDE - Product Selection -->
                            <div class="col-lg-7 col-md-8 border-end" style="background:#F8FAFC;">
                                <!-- Search -->
                                <div class="p-3 border-bottom bg-white">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="border-radius:8px 0 0 8px;">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" placeholder="Search item... (e.g. momo, chicken, coke)" id="quickDishSearch" style="border-radius:0 8px 8px 0;">
                                    </div>
                                </div>

                                <!-- Category Buttons -->
                                <div class="p-3 border-bottom bg-white">
                                    <div class="d-flex flex-wrap gap-2" id="quickMenuCategories">
                                        <button class="btn btn-sm btn-primary rounded-pill px-3 active" data-menu-id="all">All</button>
                                        @foreach ($menus as $menu)
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-menu-id="{{ $menu->id }}">{{ $menu->name }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Popular Items -->
                                <div class="p-3">
                                    <h6 class="fw-bold mb-3" style="font-size:.9rem;color:#1E293B;">
                                        <i class="bi bi-fire text-danger me-1"></i>Popular Items
                                    </h6>
                                    <div class="row g-2" id="quickDishesContainer" style="max-height:400px;overflow-y:auto;">
                                        @foreach ($dishes as $dish)
                                            <div class="col-6 col-md-4 col-lg-3 dish-card-wrap" data-menu-id="{{ $dish->category_id }}">
                                                <div class="card h-100 border-0 shadow-sm" style="border-radius:10px;cursor:pointer;transition:transform 0.2s;" onclick="addToQuickCart({{ $dish->id }}, '{{ $dish->name }}', {{ $dish->final_price ?? $dish->price }}, '{{ $dish->image_url ?: asset('assets/images/defaultfood.png') }}')">
                                                    <img src="{{ $dish->image_url ?: asset('assets/images/defaultfood.png') }}" class="card-img-top" alt="{{ $dish->name }}" style="height:100px;object-fit:cover;border-radius:10px 10px 0 0;">
                                                    <div class="card-body p-2 text-center">
                                                        <h6 class="card-title mb-1" style="font-size:.8rem;font-weight:600;color:#1E293B;">{{ Str::limit($dish->name, 20) }}</h6>
                                                        <p class="card-text text-danger fw-bold mb-0" style="font-size:.85rem;">NPR {{ number_format($dish->final_price ?? $dish->price, 0) }}</p>
                                                        <button class="btn btn-sm btn-primary w-100 mt-2 rounded-3" style="font-size:.75rem;">
                                                            <i class="bi bi-plus-lg me-1"></i>Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT SIDE - Current Order -->
                            <div class="col-lg-5 col-md-4" style="background:white;">
                                <div class="p-3 border-bottom">
                                    <h6 class="fw-bold mb-0" style="font-size:.95rem;color:#1E293B;">
                                        <i class="bi bi-cart3 me-2 text-primary"></i>Current Order
                                    </h6>
                                </div>

                                <div id="quickCartItems" class="p-3" style="max-height:300px;overflow-y:auto;">
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                                        <small>No items added</small>
                                    </div>
                                </div>

                                <!-- Special Instructions -->
                                <div class="px-3 pb-3">
                                    <label class="form-label small fw-semibold text-muted">Special instructions (optional)</label>
                                    <textarea class="form-control form-control-sm rounded-3" placeholder="e.g. Less spicy, no onion, extra sauce..." id="quickOrderNotes" rows="2"></textarea>
                                </div>

                                <!-- Bill Summary -->
                                <div class="px-3 pb-3 border-top">
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span class="text-muted">Subtotal</span>
                                            <span class="fw-semibold">NPR <span id="quickSubtotal">0</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span class="text-muted">Discount</span>
                                            <span class="fw-semibold text-danger">-NPR <span id="quickDiscount">0</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span class="text-muted">VAT (13%)</span>
                                            <span class="fw-semibold">+NPR <span id="quickVat">0</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span class="text-muted">Service Charge (5%)</span>
                                            <span class="fw-semibold">+NPR <span id="quickServiceCharge">0</span></span>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold" style="color:#1E293B;">Grand Total</span>
                                            <span class="fw-bold fs-5" style="color:#1A73E8;">NPR <span id="quickGrandTotal">0</span></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="px-3 pb-3 border-top">
                                    <label class="form-label small fw-semibold text-muted mb-2">Payment Method</label>
                                    <div class="d-flex gap-2 flex-wrap" id="quickPaymentMethods">
                                        <button class="btn btn-sm btn-outline-primary active rounded-3 px-3" data-method="cash" onclick="selectQuickPayment(this, 'cash')">
                                            <i class="bi bi-cash-stack me-1"></i>Cash
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-method="esewa" onclick="selectQuickPayment(this, 'esewa')">
                                            eSewa
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-method="khalti" onclick="selectQuickPayment(this, 'khalti')">
                                            Khalti
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-method="card" onclick="selectQuickPayment(this, 'card')">
                                            <i class="bi bi-credit-card me-1"></i>Card
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-method="other" onclick="selectQuickPayment(this, 'other')">
                                            Other
                                        </button>
                                    </div>
                                </div>

                                <!-- Cash Payment -->
                                <div id="quickCashPayment" class="px-3 pb-3 border-top">
                                    <label class="form-label small fw-semibold text-muted mb-2">Amount Received</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">NPR</span>
                                        <input type="number" class="form-control" id="quickAmountReceived" placeholder="0" oninput="calculateQuickChange()">
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Change:</span>
                                        <span class="fw-bold text-success">NPR <span id="quickChange">0</span></span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="p-3 border-top bg-light">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-secondary flex-fill rounded-3" id="quickPrintBtn" disabled>
                                            <i class="bi bi-printer me-1"></i>Print Bill (F4)
                                        </button>
                                        <button class="btn btn-primary flex-fill rounded-3" id="quickCompleteOrderBtn" disabled>
                                            <i class="bi bi-check-circle me-1"></i>Complete Order (Ctrl+Enter)
                                        </button>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">F2 Search | Enter Add Item | +/- Qty | F4 Cash | Esc Close</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Order Selection Modal --}}
        <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;border:none;">
                    <div class="modal-header border-bottom py-3 px-4">
                        <h5 class="modal-title mb-0 fw-bold" id="addOrderModalLabel"><i class="bi bi-plus-circle text-primary me-2"></i>Add New Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Order Type</label>
                            <div class="d-flex gap-2 mb-3">
                                <button class="btn btn-outline-primary flex-fill order-type-btn active" data-type="dine_in">
                                    <i class="bi bi-geo-alt me-1"></i> Dine In
                                </button>
                                <button class="btn btn-outline-primary flex-fill order-type-btn" data-type="takeaway">
                                    <i class="bi bi-bag me-1"></i> Takeaway
                                </button>
                                <button class="btn btn-outline-primary flex-fill order-type-btn" data-type="delivery">
                                    <i class="bi bi-truck me-1"></i> Delivery
                                </button>
                                <button class="btn btn-outline-primary flex-fill order-type-btn" data-type="quick_billing">
                                    <i class="bi bi-lightning me-1"></i> Quick Billing
                                </button>
                            </div>
                        </div>
                        <div id="tableSelectionSection" class="mb-3">
                            <label class="form-label fw-semibold">Select Table</label>
                            <div class="row g-2" id="tableSelectionGrid">
                                @foreach ($tables as $table)
                                <div class="col-4 col-md-3">
                                    <div class="table-select-card {{ $table->status == \App\Enums\TableStatusEnum::AVAILABLE ? 'available' : 'occupied' }}"
                                         data-table-id="{{ $table->id }}"
                                         data-table-name="{{ $table->name }}"
                                         data-table-status="{{ $table->status }}"
                                         onclick="selectTableForOrder(this)">
                                        <div class="table-select-name">{{ $table->name }}</div>
                                        <div class="table-select-status">{{ $table->status }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="customerInfoSection" class="mb-3 d-none">
                            <label class="form-label fw-semibold">Customer Information</label>
                            <input type="text" class="form-control mb-2" placeholder="Customer Name" id="addOrderCustomerName">
                            <input type="text" class="form-control mb-2" placeholder="Phone Number" id="addOrderCustomerPhone">
                            <textarea class="form-control" placeholder="Delivery Address" id="addOrderDeliveryAddress" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-3 px-4">
                        <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary rounded-3" id="proceedToItemsBtn" disabled onclick="proceedToItems()">
                            <i class="bi bi-arrow-right me-1"></i> Proceed to Add Items
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Slip Print Modal --}}
        <div class="modal fade" id="orderSlipModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 8px;">
                    <div class="modal-header border-bottom py-2 px-3">
                        <h5 class="modal-title mb-0" style="font-size: 14px; font-weight: 700;">Order Slip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3" style="font-family: monospace; font-size: 12px; background: white;">
                        <div id="orderSlipContent"></div>
                    </div>
                    <div class="modal-footer border-top py-2 px-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Checkout Modal --}}
        <div class="modal fade" id="checkoutModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 1400px; width: 82vw;">
                <div class="modal-content" style="border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; max-height: 95vh;">
                    <div class="modal-header" style="background: white; border: 1px solid #e5e7eb; border-bottom: none; padding: 12px 16px; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h5 class="modal-title mb-0" style="font-size: 16px; font-weight: 700; color: #1f2937;">Checkout</h5>
                            <small style="font-size: 12px; color: #6b7280;" id="checkoutTableName">Table</small>
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button type="button" class="btn btn-sm" onclick="window.print()" style="font-size: 11px; padding: 6px 12px; border: 1px solid #e5e7eb; background: white; border-radius: 2px; font-weight: 600;">Print</button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-left: 4px; padding: 0; width: 24px; height: 24px;"></button>
                        </div>
                    </div>
                    <div class="modal-body p-0" style="background: #f8f9fa; flex: 1; overflow: hidden; display: flex;">
                        <div id="checkoutContent" style="flex: 0 0 70%; background: white; display: flex; flex-direction: column; overflow: hidden;">
                            <div style="text-align: center; padding: 40px; color: #64748b;">
                                <div style="font-size: 2rem; margin-bottom: 12px;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <p style="font-size: 13px;">Loading checkout data...</p>
                            </div>
                        </div>
                        <div id="estimateInvoicePanel" style="flex: 0 0 30%; background: white; border-left: 1px solid #e5e7eb; flex-shrink: 0; display: flex; flex-direction: column;">
                            <div style="text-align: center; font-weight: 700; color: #1f2937; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb; font-size: 14px; flex-shrink: 0;">ESTIMATE INVOICE</div>
                            <div id="estimateInvoiceContent" style="flex: 1; overflow-y: auto; padding: 0 16px;">
                                <div style="text-align: center; color: #6b7280; padding: 16px; font-size: 12px;">
                                    Loading estimate...
                                </div>
                            </div>
                            <div id="estimateInvoiceButtons" style="padding: 16px; border-top: 1px solid #e5e7eb; flex-shrink: 0; background: white;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Customer Modal --}}
        <div class="modal fade" id="addCustomerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 8px; border: none;">
                    <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; padding: 16px 20px;">
                        <h5 class="modal-title mb-0" style="font-size: 16px; font-weight: 700; color: #1f2937;">Add New Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="padding: 0; width: 24px; height: 24px;"></button>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: #374151; font-weight: 600; display: block; margin-bottom: 6px;">Full Name *</label>
                            <input type="text" id="newCustomerName" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;" placeholder="Enter full name">
                        </div>
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: #374151; font-weight: 600; display: block; margin-bottom: 6px;">Phone Number *</label>
                            <input type="tel" id="newCustomerPhone" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;" placeholder="Enter phone number">
                        </div>
                        <div style="margin-bottom: 0;">
                            <label style="font-size: 12px; color: #374151; font-weight: 600; display: block; margin-bottom: 6px;">Address (optional)</label>
                            <textarea id="newCustomerAddress" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical; font-size: 13px;" rows="3" placeholder="Enter address"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 16px 20px; gap: 8px;">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="flex: 1; padding: 10px 16px; border: 1px solid #e5e7eb; background: white; border-radius: 6px; font-size: 13px; font-weight: 600; color: #374151;">Cancel</button>
                        <button type="button" class="btn" onclick="saveNewCustomer()" style="flex: 1; padding: 10px 16px; border: none; background: #1A73E8; border-radius: 6px; font-size: 13px; font-weight: 600; color: white;">Add Customer</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Items Modal --}}
        <div class="modal fade" id="addItemsModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;border:none;">
                    <div class="modal-header border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="modal-title mb-0 fw-bold">Add Items to <span id="selectedTableName">Table</span></h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="waiterSelect" style="width:auto;border-radius:8px;">
                                <option value="">Assign Waiter</option>
                                @foreach ($waiters as $waiter)
                                    <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                    <div class="modal-body p-4 row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius:10px 0 0 10px;"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control border-start-0" placeholder="Search dishes..." id="dishSearch" style="border-radius:0 10px 10px 0;">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="list-group menu-categories" id="menuCategories" style="border-radius:10px;">
                                        <button class="list-group-item list-group-item-action active" data-menu-id="all">All Items</button>
                                        @foreach ($menus as $menu)
                                            <button class="list-group-item list-group-item-action" data-menu-id="{{ $menu->id }}">
                                                {{ $menu->name }}
                                                <span class="badge bg-light text-dark ms-auto">{{ $menu->dishes->count() }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h6 id="menuTitle" class="fw-bold mb-2" style="font-size:.85rem;">All Items</h6>
                                    <div class="row g-2" id="dishesContainer" style="max-height:420px;overflow-y:auto;">
                                        @foreach ($dishes as $dish)
                                            <div class="col-6 col-lg-4 dish-card-wrap" data-menu-id="{{ $dish->menu_id }}">
                                                <div class="dish-card-new">
                                                    <img src="{{ $dish->image_url ?: asset('assets/images/defaultfood.png') }}"
                                                         class="dish-img-new" alt="{{ $dish->name }}">
                                                    <div class="dish-info-new">
                                                        <h6 class="mb-1" style="font-size:.82rem;">{{ $dish->name }}</h6>
                                                        <p class="text-danger fw-bold mb-1" style="font-size:.85rem;">Rs {{ number_format($dish->final_price ?? $dish->price, 2) }}</p>
                                                        <p class="text-muted small mb-2" style="font-size:.72rem;">{{ Str::limit($dish->description, 40) }}</p>
                                                        <button class="btn btn-outline-danger btn-sm w-100 rounded-3"
                                                                onclick="addToCart({{ $dish->id }}, '{{ $dish->name }}', {{ $dish->final_price ?? $dish->price }}, '{{ $dish->image_url ?: asset('assets/images/defaultfood.png') }}')">
                                                            <i class="bi bi-cart-plus me-1"></i> Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold" style="font-size:.85rem;"><i class="bi bi-cart me-1"></i> Cart</h6>
                                    <button class="btn btn-link btn-sm text-danger p-0" id="clearCartBtn" style="display:none;text-decoration:none;">Clear</button>
                                </div>
                                <div id="cartItems" class="mb-3" style="max-height:280px;overflow-y:auto;">
                                    <p class="text-muted text-center small">No items selected.</p>
                                </div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <input type="number" class="form-control form-control-sm mb-2 rounded-3" placeholder="No. of guests" id="guestCount" min="1">
                                    <textarea class="form-control form-control-sm rounded-3" placeholder="Notes..." id="orderNotes" rows="2"></textarea>
                                </div>
                                <div class="bg-white rounded-3 p-3 border">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="fw-medium">QTY: <span id="totalQty">0</span></span>
                                        <span class="fw-bold">Rs <span id="totalAmount">0.00</span></span>
                                    </div>
                                    <button class="btn btn-danger w-100 rounded-3" id="createOrderBtn" disabled>
                                        <span class="btn-text"><i class="bi bi-check-lg"></i> Create Order</span>
                                        <span class="spinner-border spinner-border-sm d-none"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Confirm Action Modal --}}
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon" id="confirmIcon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="confirm-title" id="confirmTitle">Are you sure?</div>
            <div class="confirm-desc" id="confirmDesc">This action cannot be undone.</div>
            <div class="confirm-actions">
                <button class="btn-cancel-act" id="confirmCancel">Cancel</button>
                <button class="btn-confirm-act" id="confirmProceed">Confirm</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .nav-btn { transition: all .15s; }
        .nav-btn.active { background-color: #dc2626 !important; color: #fff; border-color: #dc2626; }
        .content-pane { transition: opacity .15s; }

        .table-ov-card {
            width: 120px; padding: 1rem .75rem;
            border-radius: 10px;
            display: flex; flex-direction: column; align-items: center;
            cursor: pointer; font-weight: 600;
            transition: transform .15s, box-shadow .15s;
            border: 2px solid transparent;
            text-align: center;
        }
        .table-ov-card:hover { transform: scale(1.05); border-color: #dc2626; box-shadow: 0 4px 16px rgba(0,0,0,.1); }
        .table-ov-card.avail { background: #ecfdf5; color: #16a34a; }
        .table-ov-card.occ { background: #fef2f2; color: #dc2626; }
        .table-ov-name { font-size: 1rem; }
        .table-ov-status { font-size: .72rem; opacity: .8; text-transform: capitalize; }

        .table-select-card {
            padding: 1rem .5rem;
            border-radius: 8px;
            display: flex; flex-direction: column; align-items: center;
            cursor: pointer; font-weight: 600;
            transition: all .15s;
            border: 2px solid transparent;
            text-align: center;
        }
        .table-select-card:hover { transform: scale(1.05); }
        .table-select-card.available { background: #ecfdf5; color: #16a34a; border-color: #16a34a; }
        .table-select-card.occupied { background: #fef2f2; color: #dc2626; border-color: #dc2626; opacity: 0.6; cursor: not-allowed; }
        .table-select-card.selected { background: #dc2626; color: #fff; border-color: #dc2626; transform: scale(1.05); }
        .table-select-name { font-size: .9rem; }
        .table-select-status { font-size: .65rem; opacity: .8; text-transform: capitalize; }

        .order-type-btn {
            padding: .75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all .15s;
        }
        .order-type-btn.active { background: #dc2626; color: #fff; border-color: #dc2626; }
        .order-type-btn:hover:not(.active) { background: #f1f5f9; }
        body > .modal { z-index: 1080; }
        body > .modal-backdrop { z-index: 1070; }

        .payment-method, .payment-status {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10px;
            font-weight: 600;
            transition: all 0.15s;
        }
        .payment-method:hover, .payment-status:hover { background: #e5e7eb; }
        .payment-method.active, .payment-status.active { background: #dc2626; color: white; border-color: #dc2626; }

        .payment-method-compact, .payment-status-compact {
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            min-width: 60px;
        }
        .payment-method-compact:hover, .payment-status-compact:hover { background: #e5e7eb; }
        .payment-method-compact.active, .payment-status-compact.active { background: #dc2626; color: white; border-color: #dc2626; }

        /* Compact Order Card Styles */
        .order-card-compact {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.15s;
        }
        .order-card-compact:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            border-color: #d1d5db;
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        .table-name {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3b82f6;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.15s;
        }
        .add-btn:hover {
            background: #2563eb;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-badge.available {
            background: #d1fae5;
            color: #065f46;
        }
        .status-badge.occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .order-card-body {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .order-section {
            padding: 8px 0;
        }
        .order-section.border-top {
            border-top: 1px solid #f3f4f6;
        }

        .order-id {
            font-size: 11px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .order-status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .order-status-badge.status-confirmed {
            background: #dbeafe;
            color: #1e40af;
        }
        .order-status-badge.status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .order-status-badge.status-cooking {
            background: #e0e7ff;
            color: #3730a3;
        }
        .order-status-badge.status-ready {
            background: #d1fae5;
            color: #065f46;
        }
        .order-status-badge.status-served {
            background: #f3f4f6;
            color: #374151;
        }

        .order-time {
            font-size: 11px;
            color: #6b7280;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 8px;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #374151;
        }
        .order-item-row.more-items {
            padding-top: 2px;
        }

        .item-name {
            font-weight: 500;
        }

        .item-qty {
            font-weight: 600;
            color: #6b7280;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 6px;
            border-top: 1px solid #f3f4f6;
            margin-bottom: 8px;
        }

        .items-count {
            font-size: 11px;
            color: #6b7280;
        }

        .order-total {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
        }

        .order-actions-compact {
            display: flex;
            gap: 8px;
        }

        .action-btn-add {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 14px;
        }
        .action-btn-add:hover {
            background: #059669;
        }

        .action-btn-print {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .action-btn-print:hover {
            background: #4b5563;
        }

        .action-btn-cancel {
            flex: 1;
            padding: 8px 12px;
            background: white;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .action-btn-cancel:hover {
            background: #fee2e2;
        }

        .action-btn-checkout {
            flex: 1;
            padding: 8px 12px;
            background: #059669;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .action-btn-checkout:hover {
            background: #047857;
        }

        .size-badge {
            padding: 4px 8px;
            border-radius: 2px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .size-badge.half {
            background: #fef3c7;
            color: #92400e;
        }
        .size-badge.full {
            background: #dbeafe;
            color: #1e40af;
        }

        .dish-card-wrap { padding: 0; }
        .dish-card-new {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: all .15s;
            background: #fff;
            height: 100%;
        }
        .dish-card-new:hover { border-color: #dc2626; box-shadow: 0 4px 12px rgba(0,0,0,.06); transform: translateY(-1px); }
        .dish-img-new { width: 100%; height: 100px; object-fit: cover; display: block; }
        .dish-info-new { padding: .65rem; }
        .menu-categories .list-group-item {
            border: none; padding: .5rem .75rem; cursor: pointer; font-size: .82rem;
            transition: all .1s;
        }
        .menu-categories .list-group-item.active { background-color: #dc2626; border-color: #dc2626; }
        .menu-categories .list-group-item:not(.active):hover { background: #f1f5f9; }

        .cart-item {
            display: flex; align-items: center; gap: .5rem;
            padding: .5rem; background: #fff; border-radius: 8px;
            margin-bottom: .4rem; border: 1px solid #e2e8f0;
        }
        .cart-item-left { display: flex; align-items: center; gap: .5rem; flex: 1; min-width:0; }
        .cart-item-image { width: 32px; height: 32px; border-radius: 4px; object-fit: cover; flex-shrink:0; }
        .cart-item-details { min-width:0; }
        .cart-item-name { font-size: .78rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cart-item-price { font-size: .7rem; color: #64748b; }
        .size-selector { display: flex; align-items: center; gap: 2px; flex-shrink:0; }
        .size-option-btn {
            padding: 8px 4px; border: 1px solid #e2e8f0;
            background: #f8fafc; border-radius: 2px;
            cursor: pointer; font-size: .65rem; color: #475569;
            transition: all .1s; font-weight: 600;
            min-width: 35px;
        }
        .size-option-btn:hover { background: #e2e8f0; }
        .size-option-btn.active { background: #dc2626; color: #fff; border-color: #dc2626; }
        .qty-ctrl { display: inline-flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .qty-ctrl button { border: none; background: #f8fafc; width: 24px; height: 24px; font-size: .8rem; cursor: pointer; transition: all .1s; }
        .qty-ctrl button:hover { background: #dc2626; color: #fff; }
        .qty-ctrl span { padding: 0 .5rem; font-size: .8rem; font-weight: 600; min-width: 24px; text-align: center; background: #fff; }
        .remove-item-btn {
            background: none; border: none; color: #94a3b8; cursor: pointer;
            padding: 2px; transition: color .1s; margin-left: 2px;
        }
        .remove-item-btn:hover { color: #dc2626; }

        /* ── Modern Order Action Buttons ── */
        .order-actions {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-top:1rem;
        }
        .order-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border: none;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
            white-space: nowrap;
        }
        .order-action-btn i { font-size: .85rem; }
        .order-action-btn:active { transform: scale(.92); }
        .order-action-btn.danger {
            background: #fef2f2;
            color: #dc2626;
        }
        .order-action-btn.danger:hover {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 2px 12px rgba(220,38,38,.25);
        }
        .order-action-btn.primary {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
        }
        .order-action-btn.primary:hover {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            box-shadow: 0 2px 12px rgba(37,99,235,.25);
        }
        .order-action-btn.success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }
        .order-action-btn.success:hover {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
            box-shadow: 0 2px 12px rgba(22,163,74,.25);
        }
        .order-action-btn.muted {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .order-action-btn.muted:hover {
            background: #475569;
            color: #fff;
            border-color: #475569;
            box-shadow: 0 2px 12px rgba(71,85,105,.2);
        }
        .order-action-btn.xs {
            padding: 3px 8px;
            font-size: .65rem;
            border-radius: 14px;
        }
        .order-action-btn.xs i { font-size: .7rem; }
        .card-footer-actions {
            display: flex;
            gap: 8px;
        }
        .card-footer-actions .order-action-btn {
            flex: 1;
            justify-content: center;
        }

        /* ── Modern Confirm Modal ── */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all .2s ease;
        }
        .confirm-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .confirm-modal {
            background: #fff;
            border-radius: 16px;
            padding: 28px 32px 24px;
            max-width: 400px;
            width: calc(100% - 32px);
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            transform: scale(.92) translateY(12px);
            transition: transform .25s cubic-bezier(.16,1,.3,1);
        }
        .confirm-overlay.active .confirm-modal {
            transform: scale(1) translateY(0);
        }
        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.4rem;
        }
        .confirm-icon.danger { background: #fef2f2; color: #dc2626; }
        .confirm-icon.warning { background: #fffbeb; color: #f59e0b; }
        .confirm-title {
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .confirm-desc {
            font-size: .85rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 22px;
            line-height: 1.5;
        }
        .confirm-actions {
            display: flex;
            gap: 10px;
        }
        .confirm-actions button {
            flex: 1;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: .85rem;
            cursor: pointer;
            transition: all .15s;
        }
        .confirm-actions .btn-cancel-act {
            background: #f1f5f9;
            color: #475569;
        }
        .confirm-actions .btn-cancel-act:hover { background: #e2e8f0; }
        .confirm-actions .btn-confirm-act {
            background: #dc2626;
            color: #fff;
        }
        .confirm-actions .btn-confirm-act:hover { background: #b91c1c; }
        .confirm-actions .btn-confirm-act.muted {
            background: #64748b;
        }
        .confirm-actions .btn-confirm-act.muted:hover { background: #475569; }

        .order-card-slim {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: .85rem; transition: box-shadow .15s;
        }
        .order-card-slim:hover { box-shadow: 0 4px 12px rgba(0,0,0,.04); }

        .order-card-link {
            display: block;
            text-decoration: none;
        }

        .order-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
        }
        .order-action-btn.primary { background: #3b82f6; color: #fff; }
        .order-action-btn.primary:hover { background: #2563eb; }
        .order-action-btn.success { background: #22c55e; color: #fff; }
        .order-action-btn.success:hover { background: #16a34a; }
        .order-action-btn.danger { background: #ef4444; color: #fff; }
        .order-action-btn.danger:hover { background: #dc2626; }
        .order-action-btn.muted { background: #64748b; color: #fff; }
        .order-action-btn.muted:hover { background: #475569; }
        .order-action-btn.xs { padding: 4px 8px; font-size: 0.75rem; }

        .order-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .card-footer-actions {
            margin-top: 12px;
        }

        .dash-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .kot-card-new {
            border: 1px solid #e2e8f0; border-radius: 10px; background: #fff;
            overflow: hidden; transition: box-shadow .15s;
        }
        .kot-card-new:hover { box-shadow: 0 4px 12px rgba(0,0,0,.04); }
        .kot-card-new .kot-hd {
            background: #f8fafc; padding: .75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .kot-card-new .kot-bd { padding: .75rem 1rem; }
        .kot-card-new .kot-item-li {
            display: flex; justify-content: space-between; align-items: center;
            padding: .4rem .5rem; border-radius: 6px; margin-bottom: .3rem;
            cursor: pointer; transition: background .1s; border-left: 3px solid transparent;
        }
        .kot-card-new .kot-item-li:hover { background: #f8fafc; }
        .kot-card-new .kot-item-li.status-pending { border-left-color: #f59e0b; background: #fffbeb; }
        .kot-card-new .kot-item-li.status-served { border-left-color: #22c55e; background: #f0fdf4; }
        .kot-card-new .kot-item-li.status-preparing { border-left-color: #3b82f6; background: #eff6ff; }

        .qty-ctrl { display: inline-flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .qty-ctrl button { border: none; background: #f8fafc; width: 26px; height: 26px; font-size: .8rem; cursor: pointer; transition: all .1s; }
        .qty-ctrl button:hover { background: #dc2626; color: #fff; }
        .qty-ctrl span { padding: 0 .5rem; font-size: .8rem; font-weight: 600; min-width: 28px; text-align: center; background: #fff; }

        @media (max-width: 767px) {
            .table-ov-card { width: 100px; padding: .75rem .5rem; }
            .table-ov-name { font-size: .9rem; }
        }
    </style>
@endpush

@push('scripts')
<script data-cache-bust="{{ time() }}">
    let cart = [];
    let currentTable = { id: null, name: '' };
    let isOrderCreating = false;
    let quickCart = [];
    let quickPaymentMethod = 'cash';
    let quickOrderNumber = null;
    let isQuickOrderCreating = false;
    let selectedOrderType = 'dine_in';
    let selectedTableForOrder = null;

    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.content-pane').forEach(c => c.classList.add('d-none'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.remove('d-none');
            if (btn.dataset.target === 'kot') loadKOTs();
        });
    });

    document.querySelectorAll('#page-content .modal').forEach((modal) => {
        document.body.appendChild(modal);
    });

    function getModal(id) {
        const el = document.getElementById(id);
        if (!el || typeof bootstrap === 'undefined') {
            return null;
        }
        return bootstrap.Modal.getOrCreateInstance(el);
    }

    function openQuickBilling() {
        const modal = new bootstrap.Modal(document.getElementById('quickBillingModal'));
        modal.show();
        // Generate order number
        quickOrderNumber = 'QB' + Date.now().toString().slice(-4);
        document.getElementById('quickOrderNumber').textContent = quickOrderNumber;
        // Reset cart
        quickCart = [];
        updateQuickCartDisplay();
        // Focus search
        setTimeout(() => document.getElementById('quickDishSearch').focus(), 500);
    }

    function resetAddOrderModal() {
        selectedOrderType = 'dine_in';
        selectedTableForOrder = null;
        document.querySelectorAll('.order-type-btn').forEach(btn => btn.classList.remove('active'));
        const dineInBtn = document.querySelector('.order-type-btn[data-type="dine_in"]');
        if (dineInBtn) {
            dineInBtn.classList.add('active');
        }
        document.querySelectorAll('.table-select-card').forEach(card => card.classList.remove('selected'));
        document.getElementById('tableSelectionSection')?.classList.remove('d-none');
        document.getElementById('customerInfoSection')?.classList.add('d-none');
        const proceedBtn = document.getElementById('proceedToItemsBtn');
        if (proceedBtn) {
            proceedBtn.disabled = true;
        }
    }

    function openAddOrderModal() {
        const modalEl = document.getElementById('addOrderModal');
        if (!modalEl) {
            return;
        }
        document.body.appendChild(modalEl);
        resetAddOrderModal();
        getModal('addOrderModal')?.show();
    }

    document.getElementById('addOrderModal')?.addEventListener('show.bs.modal', function() {
        resetAddOrderModal();
    });

    document.querySelectorAll('.order-type-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedOrderType = btn.dataset.type;
            selectedTableForOrder = null;
            document.querySelectorAll('.table-select-card').forEach(card => card.classList.remove('selected'));

            if (selectedOrderType === 'dine_in') {
                document.getElementById('tableSelectionSection').classList.remove('d-none');
                document.getElementById('customerInfoSection').classList.add('d-none');
            } else if (selectedOrderType === 'quick_billing') {
                document.getElementById('tableSelectionSection').classList.add('d-none');
                document.getElementById('customerInfoSection').classList.remove('d-none');
                document.getElementById('proceedToItemsBtn').disabled = false;
            } else {
                document.getElementById('tableSelectionSection').classList.add('d-none');
                document.getElementById('customerInfoSection').classList.remove('d-none');
                document.getElementById('proceedToItemsBtn').disabled = false;
            }
        });
    });

    function selectTableForOrder(card) {
        const status = card.dataset.tableStatus;
        if (status !== 'available') return;

        document.querySelectorAll('.table-select-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedTableForOrder = {
            id: parseInt(card.dataset.tableId),
            name: card.dataset.tableName
        };
        document.getElementById('proceedToItemsBtn').disabled = false;

        // Auto-proceed to items for dine_in orders
        if (selectedOrderType === 'dine_in') {
            proceedToItems();
        }
    }

    function proceedToItems() {
        getModal('addOrderModal')?.hide();

        if (selectedOrderType === 'dine_in' && selectedTableForOrder) {
            currentTable = selectedTableForOrder;
            document.getElementById('selectedTableName').textContent = selectedTableForOrder.name;
            cart = [];
            updateCartDisplay();
            getModal('addItemsModal')?.show();
        } else if (selectedOrderType === 'quick_billing') {
            quickCart = [];
            updateQuickCartDisplay();
            document.getElementById('quickCustomerName').value = document.getElementById('addOrderCustomerName').value;
            const quickPhone = document.getElementById('quickCustomerPhone');
            if (quickPhone) {
                quickPhone.value = document.getElementById('addOrderCustomerPhone').value;
            }
            document.getElementById('quickOrderNotes').value = document.getElementById('addOrderDeliveryAddress').value;
            getModal('quickBillingModal')?.show();
        } else {
            quickCart = [];
            updateQuickCartDisplay();
            document.getElementById('quickCustomerName').value = document.getElementById('addOrderCustomerName').value;
            const quickPhone = document.getElementById('quickCustomerPhone');
            if (quickPhone) {
                quickPhone.value = document.getElementById('addOrderCustomerPhone').value;
            }
            document.getElementById('quickOrderNotes').value = document.getElementById('addOrderDeliveryAddress').value;
            getModal('quickBillingModal')?.show();
        }
    }

    function handleTableClick(name, id, status) {
        if (status === 'occupied') {
            openCheckoutModal(id, name);
        } else {
            currentTable = { id, name };
            document.getElementById('selectedTableName').textContent = name;
            cart = [];
            updateCartDisplay();
            getModal('addItemsModal')?.show();
        }
    }

    function openAddItemsModal(tableId, tableName, orderId = null) {
        currentTable = { id: tableId, name: tableName };
        document.getElementById('selectedTableName').textContent = tableName;
        cart = [];
        updateCartDisplay();
        getModal('addItemsModal')?.show();
    }

    async function printOrderSlip(orderId, tableName) {
        try {
            const response = await fetch(`/admin/orders/${orderId}/checkout-data`);
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                const order = data.order;
                const items = data.items;
                const table = data.table;

                const slipContent = `
                    <div style="text-align: center; margin-bottom: 10px; font-weight: bold; font-size: 14px;">Order Slip</div>
                    <div style="margin-bottom: 5px;">Type: ${order.order_type || 'Dine In'} Service</div>
                    <div style="margin-bottom: 5px;">ORD No: ${order.id}</div>
                    <div style="margin-bottom: 5px;">Table: ${table.name || 'N/A'}</div>
                    <div style="margin-bottom: 5px;">Status: ${order.status}</div>
                    <div style="margin-bottom: 10px;">Order At: ${new Date().toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' })} ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</div>
                    <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-weight: bold;">
                        <span>S.N</span><span>Dishes</span><span>QTY</span><span>Price</span>
                    </div>
                    ${items.map((item, index) => `
                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span>${index + 1}.</span>
                            <span>${item.name}</span>
                            <span>${item.quantity}</span>
                            <span>${parseFloat(item.unit_price * item.quantity).toFixed(0)}</span>
                        </div>
                    `).join('')}
                    <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-weight: bold;">
                        <span>Total Amount</span><span>Rs ${parseFloat(data.total_amount).toFixed(0)}</span>
                    </div>
                    <div style="margin-bottom: 5px;">KOT NO: ${order.id}</div>
                    <div style="margin-bottom: 5px;">Printed By: ${data.order?.waiter?.name || 'Staff'}</div>
                    <div style="margin-bottom: 10px;">Printed At: ${new Date().toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' })} ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</div>
                    <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                    <div style="text-align: center; margin-top: 10px; font-weight: bold;">Thank You!</div>
                `;

                document.getElementById('orderSlipContent').innerHTML = slipContent;
                getModal('orderSlipModal')?.show();
            } else {
                alert('Failed to load order data');
            }
        } catch (error) {
            console.error('Error loading order slip:', error);
            alert('Error loading order slip');
        }
    }

    function openCheckoutModal(tableId, tableName, orderId = null) {
        currentTable = { id: tableId, name: tableName };
        document.getElementById('checkoutTableName').textContent = tableName;
        loadCheckoutData(tableId, orderId);
        getModal('checkoutModal')?.show();
    }

    function loadCheckoutData(tableId, orderId = null) {
        const contentDiv = document.getElementById('checkoutContent');
        const estimateDiv = document.getElementById('estimateInvoiceContent');

        contentDiv.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; color: #64748b;">
                <div style="font-size: 3rem; margin-bottom: 16px;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <p style="font-size: 1.1rem;">Loading checkout data...</p>
            </div>
        `;

        const url = orderId ? `/admin/orders/${orderId}/checkout-data` : `/admin/orders/table/${tableId}/checkout-data`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    renderCheckoutContent(data.data);
                } else {
                    contentDiv.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                            <div style="font-size: 3rem; margin-bottom: 16px;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <p style="font-size: 1.1rem;">${data.message || 'Failed to load checkout data'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading checkout data:', error);
                contentDiv.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                        <div style="font-size: 3rem; margin-bottom: 16px;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <p style="font-size: 1.1rem;">Network error occurred</p>
                    </div>
                `;
            });
    }

    // Toggle customer dropdown
    function toggleCustomerDropdown() {
        const menu = document.getElementById('customerDropdownMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
    
    // Select customer from dropdown
    function selectCustomer(customerName) {
        document.getElementById('selectedCustomer').textContent = customerName;
        document.getElementById('customerDropdownMenu').style.display = 'none';
    }
    
    // Open add customer modal
    function openAddCustomerModal() {
        document.getElementById('customerDropdownMenu').style.display = 'none';
        const modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
        modal.show();
    }
    
    // Save new customer
    function saveNewCustomer() {
        const name = document.getElementById('newCustomerName').value.trim();
        const phone = document.getElementById('newCustomerPhone').value.trim();
        const address = document.getElementById('newCustomerAddress').value.trim();
        
        if (!name || !phone) {
            alert('Please fill in required fields (Full Name and Phone Number)');
            return;
        }
        
        // Add customer to dropdown (in real implementation, save to database)
        const menu = document.getElementById('customerDropdownMenu');
        const newOption = document.createElement('div');
        newOption.style.cssText = 'padding: 8px 12px; cursor: pointer; font-size: 12px; color: #374151;';
        newOption.textContent = name;
        newOption.onclick = () => selectCustomer(name);
        menu.insertBefore(newOption, menu.lastElementChild);
        
        // Select the new customer
        selectCustomer(name);
        
        // Clear form and close modal
        document.getElementById('newCustomerName').value = '';
        document.getElementById('newCustomerPhone').value = '';
        document.getElementById('newCustomerAddress').value = '';
        bootstrap.Modal.getInstance(document.getElementById('addCustomerModal')).hide();
    }
    
    // Calculate change due
    function calculateChangeDue() {
        const amountReceived = parseFloat(document.getElementById('invoiceAmountReceived').value) || 0;
        const grandTotal = parseFloat(document.querySelector('#tenderAmount')?.value || 0);
        const changeDue = Math.max(0, amountReceived - grandTotal);
        document.getElementById('invoiceChangeDue').textContent = 'Rs ' + changeDue.toFixed(2);
    }

    function renderCheckoutContent(data) {
        const contentDiv = document.getElementById('checkoutContent');
        const estimateDiv = document.getElementById('estimateInvoiceContent');
        const items = data.items || [];
        const table = data.table || {};
        const order = data.order || {};

        let itemsHtml = items.map((item, index) => {
            const sizeLabel = item.size === 0.5 ? 'Half' : item.size === 1 ? 'Full' : '';
            return `
                <tr data-item-key="${item.menu_item_id}-${item.size}" data-base-price="${item.unit_price}" data-quantity="${item.quantity}">
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>
                        <span class="size-badge ${item.size === 0.5 ? 'half' : item.size === 1 ? 'full' : ''}">${sizeLabel}</span>
                    </td>
                    <td>${item.quantity}</td>
                    <td class="item-rate">Rs ${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <select class="form-select form-select-sm" style="width: 50px; font-size: 10px; padding: 2px 4px;" onchange="updateItemDiscountType(this, '${item.menu_item_id}-${item.size}')">
                                <option value="rs">Rs</option>
                                <option value="percent">%</option>
                            </select>
                            <input type="number" class="form-control form-control-sm" style="width: 50px; font-size: 10px; padding: 2px 4px;" value="0" min="0" step="0.01" onchange="updateItemDiscount(this, '${item.menu_item_id}-${item.size}')" data-discount-type="rs">
                        </div>
                    </td>
                    <td class="item-total">Rs ${parseFloat(item.unit_price * item.quantity).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm" style="padding: 2px 6px; font-size: 10px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 2px;" onclick="deleteCheckoutItem('${item.menu_item_id}-${item.size}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `}).join('');

        if (items.length === 0) {
            itemsHtml = '<tr><td colspan="8" style="text-align:center;padding:20px;">No items</td></tr>';
        }

        const grandTotal = parseFloat(data.total_amount || 0).toFixed(2);

        contentDiv.innerHTML = `
            <div id="checkoutScrollableContent" style="flex: 1; overflow-y: auto; padding: 12px;">
                <!-- Order Information -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 2px; font-size: 11px; font-weight: 600;">${table.name || 'Table'}</span>
                        <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 2px; font-size: 11px; font-weight: 600;">${order.order_type || 'Dine In'}</span>
                    </div>
                    <span style="font-size: 11px; color: #6b7280;">${new Date().toLocaleString()}</span>
                </div>

                <!-- Order Items -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px; margin-bottom: 12px; max-height: 300px; overflow-y: auto;">
                    <h6 style="font-weight: 700; margin-bottom: 8px; color: #1f2937; font-size: 13px;">Order Items</h6>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f9fafb;">
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">S.N</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Item</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Size</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">QTY</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Rate</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Discount</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Item Total</th>
                                <th style="padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>

                <!-- Customer + Totals -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <!-- Customer -->
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px;">
                        <h6 style="font-weight: 700; margin-bottom: 8px; color: #1f2937; font-size: 13px;">Customer</h6>
                        <div style="position: relative; margin-bottom: 8px;">
                            <div id="customerDropdown" style="width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 2px; font-size: 12px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: white;" onclick="toggleCustomerDropdown()">
                                <span id="selectedCustomer">Walk-in Customer</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <div id="customerDropdownMenu" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 2px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); z-index: 10; display: none; max-height: 200px; overflow-y: auto;">
                                <div style="padding: 8px 12px; cursor: pointer; font-size: 12px; color: #374151;" onclick="selectCustomer('Walk-in Customer')">Walk-in Customer</div>
                                <div style="padding: 8px 12px; cursor: pointer; font-size: 12px; color: #374151; border-top: 1px solid #f3f4f6; display: flex; align-items: center; gap: 4px;" onclick="openAddCustomerModal()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    <span style="color: #3b82f6; font-weight: 600;">+ Add New Customer</span>
                                </div>
                            </div>
                        </div>
                        <textarea style="width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 2px; resize: vertical; font-size: 12px;" rows="2" placeholder="Remarks"></textarea>
                    </div>

                    <!-- Totals -->
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px;">
                        <h6 style="font-weight: 700; margin-bottom: 8px; color: #1f2937; font-size: 13px;">Totals</h6>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px; color: #6b7280;">
                            <span>Item Total</span>
                            <span>Rs ${parseFloat(data.subtotal || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px; color: #6b7280;">
                            <span>Discount</span>
                            <span style="color: #dc2626;">-Rs <span id="totalDiscount">0.00</span></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px; color: #6b7280;">
                            <span>Service Charge</span>
                            <span>Rs ${parseFloat(data.service_charge || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px; color: #6b7280;">
                            <span>VAT (${data.vat_percent || 0}%)</span>
                            <span>Rs ${parseFloat(data.vat_amount || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb; font-weight: 700; font-size: 13px; color: #1f2937;">
                            <span>Grand Total</span>
                            <span style="color: #059669;">Rs ${grandTotal}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px;">
                    <h6 style="font-weight: 700; margin-bottom: 10px; color: #1f2937; font-size: 13px;">Payment</h6>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="font-size: 10px; color: #6b7280; margin-bottom: 4px; display: block; font-weight: 600;">Payment Method</label>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <button class="payment-method-compact active" onclick="selectPaymentMethod(this, 'cash')">Cash</button>
                                <button class="payment-method-compact" onclick="selectPaymentMethod(this, 'card')">Card</button>
                                <button class="payment-method-compact" onclick="selectPaymentMethod(this, 'esewa')">eSewa</button>
                                <button class="payment-method-compact" onclick="selectPaymentMethod(this, 'khalti')">Khalti</button>
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 10px; color: #6b7280; margin-bottom: 4px; display: block; font-weight: 600;">Payment Status</label>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <button class="payment-status-compact active" onclick="selectPaymentStatus(this, 'paid')">Paid</button>
                                <button class="payment-status-compact" onclick="selectPaymentStatus(this, 'partial')">Partial</button>
                                <button class="payment-status-compact" onclick="selectPaymentStatus(this, 'unpaid')">Unpaid</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="checkoutStickyFooter" style="flex-shrink: 0; padding: 12px; border-top: 1px solid #e5e7eb; background: white;">
                <div style="display: flex; gap: 12px;">
                    <div style="flex: 1;">
                        <label style="font-size: 11px; color: #6b7280; margin-bottom: 6px; display: block; font-weight: 600;">Tender Amount</label>
                        <input type="number" id="tenderAmount" style="width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 2px; font-size: 18px; font-weight: 700; color: #1f2937;" placeholder="0.00" value="${grandTotal}">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 11px; color: #6b7280; margin-bottom: 6px; display: block; font-weight: 600;">Change Due</label>
                        <input type="text" id="changeDue" style="width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 2px; font-size: 18px; font-weight: 700; color: #1f2937; background: #f9fafb;" placeholder="0.00" readonly value="0.00">
                    </div>
                </div>
            </div>
        `;

        estimateDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <div>
                    <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Invoice No: ##</div>
                    <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Date: ${new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</div>
                    <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">${data.order?.order_type || 'Dine In'}: ${table.name || 'N/A'}</div>
                    <div style="margin-bottom: 12px; font-size: 11px; color: #6b7280;">Customer: Walk-in Customer</div>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M8 8h8M8 12h8M8 16h4"/>
                        </svg>
                    </div>
                    <div style="font-size: 8px; color: #6b7280; max-width: 60px;">Scan for full bill details & pay</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 4px; color: #374151;">Particular</th>
                        <th style="text-align: right; padding: 4px; color: #374151;">Rate</th>
                        <th style="text-align: right; padding: 4px; color: #374151;">QTY</th>
                        <th style="text-align: right; padding: 4px; color: #374151;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${items.map(item => `
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 4px; color: #374151;">${item.name}</td>
                            <td style="padding: 4px; text-align: right; color: #374151;">${parseFloat(item.unit_price).toFixed(0)}</td>
                            <td style="padding: 4px; text-align: right; color: #374151;">${item.quantity}</td>
                            <td style="padding: 4px; text-align: right; color: #374151;">${(item.unit_price * item.quantity).toFixed(0)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Total (Particular/QTY) ${items.length}/${items.reduce((sum, item) => sum + item.quantity, 0)}</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Rs ${parseFloat(grandTotal).toFixed(0)}</div>
            <div style="margin-bottom: 12px; font-size: 11px; color: #6b7280; font-style: italic;">${numberToWords(Math.round(grandTotal))} Nepalese Rupee Only</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Payment Mode: Unpaid (Rs ${parseFloat(grandTotal).toFixed(0)})</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">KOT No: ${data.order?.id || '##'} (by ${data.order?.waiter?.name || 'Staff'})</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Billed By: ${data.order?.waiter?.name || 'Staff'}</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #6b7280;">Service Duration: --</div>
            <div style="margin-bottom: 12px; font-size: 10px; color: #dc2626; font-weight: 600; text-align: center;">This is not a Tax Invoice!</div>
            <div style="margin-bottom: 8px; font-size: 10px; color: #6b7280; text-align: center;">Kindly accept the original bill from the counter.</div>
            <div style="margin-bottom: 8px; font-size: 11px; color: #1f2937; text-align: center; font-weight: 600;">Thank You</div>
            <div style="margin-bottom: 12px; font-size: 10px; color: #6b7280; text-align: center;">Thank you for your visit! Visit again</div>
            
            <!-- Payment Calculation Fields -->
            <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                <div style="margin-bottom: 8px;">
                    <label style="font-size: 10px; color: #6b7280; font-weight: 600; display: block; margin-bottom: 4px;">Amount Received</label>
                    <input type="number" id="invoiceAmountReceived" style="width: 100%; padding: 8px; border: 1px solid #e5e7eb; border-radius: 2px; font-size: 12px;" placeholder="0.00" oninput="calculateChangeDue()">
                </div>
                <div>
                    <label style="font-size: 10px; color: #6b7280; font-weight: 600; display: block; margin-bottom: 4px;">Change Due</label>
                    <div id="invoiceChangeDue" style="width: 100%; padding: 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 2px; font-size: 12px; font-weight: 700; color: #059669;">Rs 0.00</div>
                </div>
            </div>
        `;

        const buttonsDiv = document.getElementById('estimateInvoiceButtons');
        buttonsDiv.innerHTML = `
            <div style="margin-bottom: 12px; padding: 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px;">
                <div style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">Net sales amount</div>
                <div style="font-size: 18px; font-weight: 700; color: #1f2937;">${parseFloat(grandTotal).toFixed(0)}</div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button onclick="confirmAndPrint()" style="flex: 1; padding: 10px; background: #3b82f6; color: white; border: none; border-radius: 4px; font-weight: 600; font-size: 12px; cursor: pointer;">
                    Confirm & Print
                </button>
                <button onclick="completeCheckout()" style="flex: 1; padding: 10px; background: #059669; color: white; border: none; border-radius: 4px; font-weight: 600; font-size: 12px; cursor: pointer;">
                    Confirm Checkout
                </button>
            </div>
        `;
    }

    function deleteCheckoutItem(itemKey) {
        if (confirm('Delete this item?')) {
            const row = document.querySelector(`tr[data-item-key="${itemKey}"]`);
            if (row) {
                row.remove();
                recalculateTotals();
            }
        }
    }

    function recalculateTotals() {
        const rows = document.querySelectorAll('#checkoutContent tbody tr');
        let itemTotal = 0;
        let itemDiscount = 0;

        rows.forEach(row => {
            const rateCell = row.querySelector('.item-rate');
            const quantity = parseFloat(row.dataset.quantity) || 1;
            const discountInput = row.querySelector('input[type="number"]');
            const discountType = discountInput ? discountInput.dataset.discountType : 'rs';
            const discountValue = parseFloat(discountInput?.value) || 0;
            const totalCell = row.querySelector('.item-total');

            if (rateCell) {
                const rate = parseFloat(rateCell.textContent.replace('Rs ', '')) || 0;
                const baseTotal = rate * quantity;
                let discount = 0;

                if (discountType === 'percent') {
                    discount = baseTotal * (discountValue / 100);
                } else {
                    discount = discountValue;
                }

                const finalTotal = baseTotal - discount;
                itemTotal += finalTotal;
                itemDiscount += discount;

                if (totalCell) {
                    totalCell.textContent = 'Rs ' + finalTotal.toFixed(2);
                }
            }
        });

        // Calculate global discount
        const globalDiscountType = document.getElementById('globalDiscountType')?.value || 'rs';
        const globalDiscountValue = parseFloat(document.getElementById('globalDiscountValue')?.value) || 0;
        let globalDiscount = 0;

        if (globalDiscountType === 'percent') {
            globalDiscount = itemTotal * (globalDiscountValue / 100);
        } else {
            globalDiscount = globalDiscountValue;
        }

        const totalDiscount = itemDiscount + globalDiscount;
        const grandTotal = itemTotal - globalDiscount;

        // Update totals display
        document.getElementById('itemDiscount').textContent = itemDiscount.toFixed(2);
        document.getElementById('totalDiscount').textContent = totalDiscount.toFixed(2);
        document.querySelector('#checkoutContent [style*="color: #059669"]').textContent = 'Rs ' + grandTotal.toFixed(2);

        // Update tender amount if paid
        if (selectedPaymentStatus === 'paid') {
            document.getElementById('tenderAmount').value = grandTotal.toFixed(2);
        }

        // Update change due
        calculateChangeDue();
    }

    function updateItemDiscountType(select, itemKey) {
        const row = document.querySelector(`tr[data-item-key="${itemKey}"]`);
        const input = row.querySelector('input[type="number"]');
        input.dataset.discountType = select.value;
        recalculateTotals();
    }

    function updateItemDiscount(input, itemKey) {
        recalculateTotals();
    }

    function calculateChangeDue() {
        const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
        const grandTotal = parseFloat(document.querySelector('#checkoutContent [style*="color: #059669"]').textContent.replace('Rs ', '')) || 0;
        const changeDue = Math.max(0, tenderAmount - grandTotal);
        document.getElementById('changeDue').value = changeDue.toFixed(2);
    }

    function getNepaliDate() {
        const today = new Date();
        const nepaliMonths = ['Baishakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        // Simple approximation - in production, use a proper Nepali date library
        const year = today.getFullYear() - 56;
        const month = today.getMonth();
        const day = today.getDate();
        return `${day} ${nepaliMonths[month]}, ${year}`;
    }

    function numberToWords(num) {
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if (num === 0) return 'Zero';

        function convertHundreds(n) {
            let word = '';
            if (n >= 100) {
                word += ones[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n > 0) {
                if (n < 20) {
                    word += ones[n] + ' ';
                } else {
                    word += tens[Math.floor(n / 10)] + ' ';
                    if (n % 10 > 0) {
                        word += ones[n % 10] + ' ';
                    }
                }
            }
            return word;
        }

        let word = '';
        if (num >= 10000000) {
            word += convertHundreds(Math.floor(num / 10000000)) + 'Crore ';
            num %= 10000000;
        }
        if (num >= 100000) {
            word += convertHundreds(Math.floor(num / 100000)) + 'Lakh ';
            num %= 100000;
        }
        if (num >= 1000) {
            word += convertHundreds(Math.floor(num / 1000)) + 'Thousand ';
            num %= 1000;
        }
        if (num >= 100) {
            word += convertHundreds(Math.floor(num / 100)) + 'Hundred ';
            num %= 100;
        }
        if (num > 0) {
            word += convertHundreds(num);
        }

        return word.trim();
    }

    function confirmAndPrint() {
        const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
        const totalAmount = parseFloat(document.querySelector('#checkoutContent [style*="color: #059669"]').textContent.replace('Rs ', '')) || 0;
        if (tenderAmount < totalAmount && selectedPaymentStatus === 'paid') {
            alert('Amount received cannot be less than total amount');
            return;
        }
        // Print the estimate invoice
        window.print();
    }

    function printEstimate() {
        window.print();
    }

    let selectedPaymentMethod = 'cash';
    let selectedPaymentStatus = 'paid';

    function selectPaymentMethod(btn, method) {
        document.querySelectorAll('.payment-method-compact').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedPaymentMethod = method;
    }

    function selectPaymentStatus(btn, status) {
        document.querySelectorAll('.payment-status-compact').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedPaymentStatus = status;

        // Auto-fill tender amount when paid is selected
        if (status === 'paid') {
            const grandTotal = parseFloat(document.querySelector('#checkoutContent [style*="color: #059669"]').textContent.replace('Rs ', '')) || 0;
            document.getElementById('tenderAmount').value = grandTotal.toFixed(2);
            calculateChangeDue();
        }
    }

    async function completeCheckout() {
        const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
        const totalAmount = parseFloat(document.querySelector('#checkoutContent [style*="color: #059669"]').textContent.replace('Rs ', '')) || 0;
        if (tenderAmount < totalAmount && selectedPaymentStatus === 'paid') {
            alert('Amount received cannot be less than total amount');
            return;
        }
        try {
            const response = await fetch(`/admin/orders/table/${currentTable.id}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    payment_method: selectedPaymentMethod,
                    tender_amount: tenderAmount,
                    total_amount: totalAmount,
                    subtotal: 0,
                    service_charge_amount: 0,
                    vat_percent: 0,
                    vat_amount: 0,
                }),
            });
            const data = await response.json();
            if (data.success) {
                // Hide checkout modal
                bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                
                // Show success modal
                showCheckoutSuccessModal(data);
            } else {
                alert(data.message || 'Checkout failed');
            }
        } catch (error) {
            alert('Network error occurred');
        }
    }

    function showCheckoutSuccessModal(data) {
        // Check if order_id exists
        if (!data.order_id) {
            console.error('Order ID missing from checkout response', data);
            alert('Checkout completed but order ID not found. Please refresh the page.');
            setTimeout(() => location.reload(), 1000);
            return;
        }

        // Create success modal if it doesn't exist
        let modalHtml = `
            <div class="modal fade" id="checkoutSuccessModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 12px; border: none;">
                        <div class="modal-body text-center p-4">
                            <div style="width: 80px; height: 80px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <h4 style="font-weight: 700; color: #1f2937; margin-bottom: 8px;">Payment Successful</h4>
                            <p style="color: #6b7280; margin-bottom: 16px;">
                                Bill #${data.invoice_number || 'N/A'}<br>
                                Payment: ${data.tenant ? data.tenant.name : 'Cash'}
                            </p>
                            <div class="d-flex flex-column gap-2">
                                <button onclick="viewBill(${data.order_id})" class="btn btn-primary" style="border-radius: 8px; padding: 10px;">
                                    <i class="bi bi-eye me-2"></i>View Bill
                                </button>
                                <button onclick="printBill(${data.order_id})" class="btn btn-outline-primary" style="border-radius: 8px; padding: 10px;">
                                    <i class="bi bi-printer me-2"></i>Print Bill
                                </button>
                                <button onclick="downloadBillPdf(${data.order_id})" class="btn btn-outline-secondary" style="border-radius: 8px; padding: 10px;">
                                    <i class="bi bi-download me-2"></i>Download PDF
                                </button>
                                <button onclick="closeCheckoutSuccess()" class="btn btn-light" style="border-radius: 8px; padding: 10px; border: 1px solid #e5e7eb;">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if present
        const existingModal = document.getElementById('checkoutSuccessModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add new modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('checkoutSuccessModal'));
        modal.show();
    }

    function viewBill(orderId) {
        if (!orderId) {
            alert('Order ID not found');
            return;
        }
        window.open(`/admin/orders/${orderId}/view-bill`, '_blank');
    }

    function printBill(orderId) {
        if (!orderId) {
            alert('Order ID not found');
            return;
        }
        window.open(`/admin/orders/${orderId}/view-bill`, '_blank');
    }

    function downloadBillPdf(orderId) {
        if (!orderId) {
            alert('Order ID not found');
            return;
        }
        window.location.href = `/admin/orders/${orderId}/download-bill-pdf`;
    }

    function closeCheckoutSuccess() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutSuccessModal'));
        if (modal) {
            modal.hide();
        }
        setTimeout(() => location.reload(), 300);
    }

    // Add tender amount input listener
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'tenderAmount') {
            calculateChangeDue();
        }
    });

    document.querySelectorAll('.menu-categories button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.menu-categories button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const menuId = btn.dataset.menuId;
            document.querySelectorAll('.dish-card-wrap').forEach(d => {
                d.style.display = (menuId === 'all' || d.dataset.menuId === menuId) ? '' : 'none';
            });
            document.getElementById('menuTitle').textContent = btn.textContent.split('\n')[0].trim();
        });
    });

    document.getElementById('dishSearch').addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.dish-card-wrap').forEach(d => {
            const name = d.querySelector('h6').textContent.toLowerCase();
            const desc = d.querySelector('.text-muted').textContent.toLowerCase();
            d.style.display = (name.includes(q) || desc.includes(q)) ? '' : 'none';
        });
    });

    // Quick Billing Modal Functions
    // Variables already declared at top of script
    // openQuickBilling function already defined above

    // Category filter
    document.querySelectorAll('#quickMenuCategories button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('#quickMenuCategories button').forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-secondary');
            });
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-primary', 'active');
            const menuId = btn.dataset.menuId;
            document.querySelectorAll('#quickDishesContainer .dish-card-wrap').forEach(d => {
                d.style.display = (menuId === 'all' || d.dataset.menuId === menuId) ? '' : 'none';
            });
        });
    });

    // Search
    document.getElementById('quickDishSearch').addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#quickDishesContainer .dish-card-wrap').forEach(d => {
            const name = d.querySelector('h6').textContent.toLowerCase();
            d.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // Add to cart
    function addToQuickCart(id, name, price, image) {
        const defaultImage = '/assets/images/defaultfood.png';
        const validImage = (image && image !== 'null' && image !== '') ? image : defaultImage;
        const existing = quickCart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            quickCart.push({ id, name, price, image: validImage, quantity: 1, size: 1, basePrice: price });
        }
        updateQuickCartDisplay();
    }

    // Update cart display
    function updateQuickCartDisplay() {
        const cc = document.getElementById('quickCartItems');
        const cp = document.getElementById('quickCompleteOrderBtn');
        const pp = document.getElementById('quickPrintBtn');
        
        if (!quickCart.length) {
            cc.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i><small>No items added</small></div>';
            if (cp) cp.disabled = true;
            if (pp) pp.disabled = true;
            updateQuickBillSummary(0);
            return;
        }
        
        if (cp) cp.disabled = isQuickOrderCreating;
        if (pp) pp.disabled = isQuickOrderCreating;
        
        let html = '';
        quickCart.forEach((item, i) => {
            const total = item.price * item.quantity;
            html += `<div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded">
                <img src="${item.image}" alt="${item.name}" style="width:40px;height:40px;border-radius:6px;object-fit:cover;">
                <div class="flex-grow-1">
                    <div class="fw-semibold small" style="font-size:.8rem;">${item.name}</div>
                    <div class="text-muted small">${item.quantity} × NPR ${item.price.toFixed(0)}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold small text-primary">NPR ${total.toFixed(0)}</div>
                    <div class="d-flex align-items-center gap-1 mt-1">
                        <button class="btn btn-sm btn-outline-secondary rounded" style="padding:2px 6px;font-size:.7rem;" onclick="updateQuickQuantity(${i},-1)">−</button>
                        <span class="small fw-semibold">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary rounded" style="padding:2px 6px;font-size:.7rem;" onclick="updateQuickQuantity(${i},1)">+</button>
                    </div>
                </div>
                <button class="btn btn-sm text-danger" onclick="removeQuickItem(${i})"><i class="bi bi-trash"></i></button>
            </div>`;
        });
        cc.innerHTML = html;
        
        // Calculate subtotal
        const subtotal = quickCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        updateQuickBillSummary(subtotal);
    }

    // Update bill summary
    function updateQuickBillSummary(subtotal) {
        const vatPercent = 13;
        const serviceChargePercent = 5;
        
        const vatAmount = Math.round(subtotal * (vatPercent / 100));
        const serviceCharge = Math.round(subtotal * (serviceChargePercent / 100));
        const grandTotal = subtotal + vatAmount + serviceCharge;
        
        document.getElementById('quickSubtotal').textContent = subtotal.toFixed(0);
        document.getElementById('quickDiscount').textContent = '0';
        document.getElementById('quickVat').textContent = vatAmount.toFixed(0);
        document.getElementById('quickServiceCharge').textContent = serviceCharge.toFixed(0);
        document.getElementById('quickGrandTotal').textContent = grandTotal.toFixed(0);
        
        // Update amount received placeholder
        document.getElementById('quickAmountReceived').placeholder = grandTotal.toFixed(0);
    }

    // Update quantity
    function updateQuickQuantity(index, change) {
        if (!quickCart[index]) return;
        quickCart[index].quantity += change;
        if (quickCart[index].quantity <= 0) quickCart.splice(index, 1);
        updateQuickCartDisplay();
    }

    // Remove item
    function removeQuickItem(index) { 
        quickCart.splice(index, 1); 
        updateQuickCartDisplay(); 
    }

    // Select payment method
    function selectQuickPayment(btn, method) {
        quickPaymentMethod = method;
        document.querySelectorAll('#quickPaymentMethods button').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary', 'active');
        
        // Show/hide cash payment section
        const cashSection = document.getElementById('quickCashPayment');
        if (method === 'cash') {
            cashSection.style.display = 'block';
        } else {
            cashSection.style.display = 'none';
        }
    }

    // Calculate change
    function calculateQuickChange() {
        const received = parseFloat(document.getElementById('quickAmountReceived').value) || 0;
        const grandTotal = parseFloat(document.getElementById('quickGrandTotal').textContent) || 0;
        const change = Math.max(0, received - grandTotal);
        document.getElementById('quickChange').textContent = change.toFixed(0);
    }

    // Complete order
    document.getElementById('quickCompleteOrderBtn').addEventListener('click', async () => {
        if (!quickCart.length || isQuickOrderCreating) return;
        
        // Validate payment
        if (quickPaymentMethod === 'cash') {
            const received = parseFloat(document.getElementById('quickAmountReceived').value) || 0;
            const grandTotal = parseFloat(document.getElementById('quickGrandTotal').textContent) || 0;
            if (received < grandTotal) {
                alert('Insufficient amount received!');
                return;
            }
        }
        
        isQuickOrderCreating = true;
        const btn = document.getElementById('quickCompleteOrderBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        
        try {
            const subtotal = quickCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const vatPercent = 13;
            const serviceChargePercent = 5;
            const vatAmount = Math.round(subtotal * (vatPercent / 100));
            const serviceCharge = Math.round(subtotal * (serviceChargePercent / 100));
            const grandTotal = subtotal + vatAmount + serviceCharge;
            
            const r = await fetch('/admin/orders/quick-billing', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    order_type: document.getElementById('quickOrderType').value,
                    customer_name: document.getElementById('quickCustomerName')?.value || null,
                    notes: document.getElementById('quickOrderNotes').value,
                    payment_method: quickPaymentMethod,
                    amount_received: quickPaymentMethod === 'cash' ? document.getElementById('quickAmountReceived').value : null,
                    vat_percent: vatPercent,
                    service_charge: serviceCharge,
                    items: quickCart.map(item => ({ menu_item_id: item.id, quantity: item.quantity, unit_price: item.price, size: item.size }))
                })
            });
            const d = await r.json();
            if (d.success) {
                showToast('success', 'Order completed successfully!');
                quickCart = []; 
                updateQuickCartDisplay();
                bootstrap.Modal.getInstance(document.getElementById('quickBillingModal')).hide();
                loadRecentOrders();
                loadKOTs();
                setTimeout(() => location.reload(), 500);
            } else { 
                showToast('error', d.message); 
            }
        } catch (e) { 
            showToast('error', 'Failed to complete order'); 
        }
        finally {
            isQuickOrderCreating = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Complete Order (Ctrl+Enter)';
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('quickBillingModal');
        if (!modal || !modal.classList.contains('show')) return;
        
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            // Allow default behavior in input fields
            if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(modal).hide();
            }
            return;
        }
        
        switch(e.key) {
            case 'F2':
                e.preventDefault();
                document.getElementById('quickDishSearch').focus();
                break;
            case 'F4':
                e.preventDefault();
                selectQuickPayment(document.querySelector('[data-method="cash"]'), 'cash');
                document.getElementById('quickAmountReceived').focus();
                break;
            case 'Escape':
                e.preventDefault();
                bootstrap.Modal.getInstance(modal).hide();
                break;
        }
    });

    document.getElementById('quickDishSearch').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            // Add first visible item
            const firstVisible = document.querySelector('#quickDishesContainer .dish-card-wrap:not([style*="display: none"])');
            if (firstVisible) {
                firstVisible.click();
            }
        }
    });

    // Old Quick Billing Modal Functions (kept for backward compatibility)
        const existing = quickCart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            quickCart.push({ id, name, price, image: validImage, quantity: 1, size: 1, basePrice: price });
        }
        updateQuickCartDisplay();
    }

    function updateQuickCartDisplay() {
        const cc = document.getElementById('quickCartItems');
        const cb = document.getElementById('clearQuickCartBtn');
        const co = document.getElementById('quickCreateOrderBtn');
        const tq = document.getElementById('quickTotalQty');
        const ta = document.getElementById('quickTotalAmount');
        if (!quickCart.length) {
            cc.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i><small>No items added</small></div>';
            cb.style.display = 'none';
            if (co) { co.disabled = true; }
            tq.textContent = '0';
            ta.textContent = '0.00';
            return;
        }
        cb.style.display = 'block';
        if (co) co.disabled = isQuickOrderCreating;
        let html = '', totQ = 0, totA = 0;
        const defaultImage = '/assets/images/defaultfood.png';
        quickCart.forEach((item, i) => {
            const it = item.price * item.quantity;
            totQ += item.quantity;
            totA += it;
            const itemImage = (item.image && item.image !== 'null' && item.image !== '') ? item.image : defaultImage;
            html += `<div class="cart-item">
                <div class="cart-item-left">
                    <img src="${itemImage}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rs ${item.price.toFixed(0)}</div>
                    </div>
                </div>
                <div class="size-selector">
                    <button class="size-option-btn ${item.size === 0.5 ? 'active' : ''}" onclick="setQuickItemSize(${i}, 0.5)">Half</button>
                    <button class="size-option-btn ${item.size === 1 ? 'active' : ''}" onclick="setQuickItemSize(${i}, 1)">Full</button>
                </div>
                <div class="qty-ctrl">
                    <button onclick="updateQuickQuantity(${i},-1)">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuickQuantity(${i},1)">+</button>
                </div>
                <button class="remove-item-btn" onclick="removeQuickItem(${i})"><i class="bi bi-x"></i></button>
            </div>`;
        });
        cc.innerHTML = html;
        tq.textContent = totQ;
        ta.textContent = totA.toFixed(0);
    }

    function setQuickItemSize(index, size) {
        if (!quickCart[index]) return;
        quickCart[index].size = size;
        quickCart[index].price = quickCart[index].basePrice * size;
        updateQuickCartDisplay();
    }

    function updateQuickQuantity(index, change) {
        if (!quickCart[index]) return;
        quickCart[index].quantity += change;
        if (quickCart[index].quantity <= 0) quickCart.splice(index, 1);
        updateQuickCartDisplay();
    }

    function removeQuickItem(index) { quickCart.splice(index, 1); updateQuickCartDisplay(); }

    document.getElementById('clearQuickCartBtn').addEventListener('click', () => {
        if (confirm('Clear all items?')) { quickCart = []; updateQuickCartDisplay(); }
    });

    document.getElementById('quickCreateOrderBtn').addEventListener('click', async () => {
        if (!quickCart.length || isQuickOrderCreating) return;
        isQuickOrderCreating = true;
        const btn = document.getElementById('quickCreateOrderBtn');
        btn.disabled = true;
        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.querySelector('.btn-text').classList.add('d-none');
        try {
            const r = await fetch('/admin/orders/quick-billing', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    waiter_id: document.getElementById('quickWaiterSelect').value || null,
                    customer_name: document.getElementById('quickCustomerName').value || null,
                    no_of_guests: document.getElementById('quickGuestCount').value || null,
                    notes: document.getElementById('quickOrderNotes').value,
                    items: quickCart.map(item => ({ menu_item_id: item.id, quantity: item.quantity, unit_price: item.price, size: item.size }))
                })
            });
            const d = await r.json();
            if (d.success) {
                showToast('success', 'Quick order created');
                quickCart = []; updateQuickCartDisplay();
                bootstrap.Modal.getInstance(document.getElementById('quickBillingModal')).hide();
                // Redirect to checkout page with order
                window.location.href = d.redirect_url;
            } else { showToast('error', d.message); }
        } catch (e) { showToast('error', 'Failed to create quick order'); }
        finally {
            isQuickOrderCreating = false;
            btn.disabled = false;
            btn.querySelector('.spinner-border').classList.add('d-none');
            btn.querySelector('.btn-text').classList.remove('d-none');
        }
    });

    // Regular POS Cart Functions
    function updateCartDisplay() {
        const cc = document.getElementById('cartItems');
        const cb = document.getElementById('clearCartBtn');
        const co = document.getElementById('createOrderBtn');
        const tq = document.getElementById('totalQty');
        const ta = document.getElementById('totalAmount');
        if (!cart.length) {
            cc.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i><small>No items added</small></div>';
            cb.style.display = 'none';
            if (co) { co.disabled = true; }
            tq.textContent = '0';
            ta.textContent = '0.00';
            return;
        }
        cb.style.display = 'block';
        if (co) co.disabled = isOrderCreating;
        let html = '', totQ = 0, totA = 0;
        const defaultImage = '/assets/images/defaultfood.png';
        cart.forEach((item, i) => {
            const it = item.price * item.quantity;
            totQ += item.quantity;
            totA += it;
            const itemImage = (item.image && item.image !== 'null' && item.image !== '') ? item.image : defaultImage;
            html += `<div class="cart-item">
                <div class="cart-item-left">
                    <img src="${itemImage}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rs ${item.price.toFixed(0)}</div>
                    </div>
                </div>
                <div class="size-selector">
                    <button class="size-option-btn ${item.size === 0.5 ? 'active' : ''}" onclick="setItemSize(${i}, 0.5)">Half</button>
                    <button class="size-option-btn ${item.size === 1 ? 'active' : ''}" onclick="setItemSize(${i}, 1)">Full</button>
                </div>
                <div class="qty-ctrl">
                    <button onclick="updateQuantity(${i},-1)">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${i},1)">+</button>
                </div>
                <button class="remove-item-btn" onclick="removeItem(${i})"><i class="bi bi-x"></i></button>
            </div>`;
        });
        cc.innerHTML = html;
        tq.textContent = totQ;
        ta.textContent = totA.toFixed(0);
    }

    function setItemSize(index, size) {
        if (!cart[index]) return;
        cart[index].size = size;
        cart[index].price = cart[index].basePrice * size;
        updateCartDisplay();
    }

    function updateQuantity(index, change) {
        if (!cart[index]) return;
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) cart.splice(index, 1);
        updateCartDisplay();
    }

    function removeItem(index) { cart.splice(index, 1); updateCartDisplay(); }

    document.getElementById('clearCartBtn').addEventListener('click', () => {
        if (confirm('Clear all items?')) { cart = []; updateCartDisplay(); }
    });

    document.getElementById('createOrderBtn').addEventListener('click', async () => {
        if (!cart.length || !currentTable.id || isOrderCreating) return;
        isOrderCreating = true;
        const btn = document.getElementById('createOrderBtn');
        btn.disabled = true;
        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.querySelector('.btn-text').classList.add('d-none');
        try {
            const r = await fetch('/admin/orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    table_id: currentTable.id,
                    waiter_id: document.getElementById('waiterSelect').value || null,
                    no_of_guests: document.getElementById('guestCount').value || null,
                    notes: document.getElementById('orderNotes').value,
                    items: cart.map(item => ({ menu_item_id: item.id, quantity: item.quantity, unit_price: item.price, size: item.size }))
                })
            });
            const d = await r.json();
            if (d.success) {
                showToast('success', 'Order created');
                cart = []; updateCartDisplay();
                bootstrap.Modal.getInstance(document.getElementById('addItemsModal')).hide();
                loadRecentOrders();
                loadKOTs(); // Auto-refresh KOT section
                setTimeout(() => location.reload(), 800);
            } else { showToast('error', d.message); }
        } catch (e) { showToast('error', 'Failed to create order'); }
        finally {
            isOrderCreating = false;
            btn.querySelector('.spinner-border').classList.add('d-none');
            btn.querySelector('.btn-text').classList.remove('d-none');
            btn.disabled = !cart.length;
        }
    });

    function addToCart(dishId, dishName, price, image) {
        const idx = cart.findIndex(i => i.id === dishId && i.size === 1);
        const defaultImage = '/assets/images/defaultfood.png';
        if (idx > -1) { cart[idx].quantity += 1; }
        else { cart.push({ id: dishId, name: dishName, basePrice: parseFloat(price), price: parseFloat(price), quantity: 1, size: 1, image: (image && image !== 'null' && image !== '') ? image : defaultImage }); }
        updateCartDisplay();
    }

    async function loadKOTs() {
        try {
            const r = await fetch('/admin/orders/active', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const d = await r.json();
            const ct = document.getElementById('kotList');
            if (!d.success || !d.orders.length) { ct.innerHTML = '<div class="col-12"><p class="text-center text-muted py-4">No active KOTs</p></div>'; return; }
            ct.innerHTML = d.orders.map(order => `
                <div class="col-3">
                    <div class="kot-card-new">
                        <div class="kot-hd">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">#${order.order_no}</h6>
                                <small class="text-muted">${formatDate(order.created_at)}</small>
                            </div>
                            <small class="text-muted">Table: ${order.table.name} ${order.waiter ? '| Waiter: '+order.waiter.name : ''}</small>
                        </div>
                        <div class="kot-bd">
                            ${order.items.map(item => `
                                <div class="kot-item-li status-${item.status}" onclick="toggleItemStatus(${item.id},'${item.status}')">
                                    <div class="d-flex align-items-center gap-2"><span style="font-size:.82rem;">${item.name}</span></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold" style="font-size:.82rem;">x${item.quantity}</span>
                                        <span class="dash-badge ${item.status==='pending'?'bg-warning text-dark':item.status==='preparing'?'bg-info text-white':item.status==='served'?'bg-secondary text-white':''}" style="font-size:.65rem;padding:1px 8px;">${item.status}</span>
                                    </div>
                                </div>
                            `).join('')}
                            <hr class="my-2">
                            <div class="d-flex justify-content-between small fw-bold">
                                <span>Items: ${order.items.reduce((s,i)=>s+i.quantity,0)}</span>
                                <span>Rs ${parseFloat(order.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch(e) {}
    }

    async function toggleItemStatus(itemId, currentStatus) {
        const ns = currentStatus === 'pending' ? 'served' : 'pending';
        try {
            const r = await fetch(`/admin/order-items/${itemId}/status`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ status: ns })
            });
            const d = await r.json();
            if (d.success) { showToast('success', `Item ${ns}`); loadKOTs(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed'); }
    }

    function refreshKOTs() { loadKOTs(); showToast('success', 'KOTs refreshed'); }

    async function loadRecentOrders() {
        try {
            const r = await fetch('/admin/orders/recent', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const d = await r.json();
            console.log('Recent orders response:', d);
            if (!d.success) {
                console.error('Error from server:', d.message);
                if (d.trace) console.error('Trace:', d.trace);
                return;
            }

            // Update counts
            if (d.counts) {
                document.getElementById('count-all').textContent = d.counts.all || 0;
                document.getElementById('count-dine_in').textContent = d.counts.dine_in || 0;
                document.getElementById('count-takeaway').textContent = d.counts.takeaway || 0;
                document.getElementById('count-delivery').textContent = d.counts.delivery || 0;
                document.getElementById('count-online').textContent = d.counts.online || 0;
                document.getElementById('count-cancelled').textContent = d.counts.cancelled || 0;
                document.getElementById('count-history').textContent = d.counts.history || 0;
            }

            const ct = document.getElementById('ordersTableBody');
            console.log('ordersTableBody element:', ct);

            if (!ct) {
                console.error('ordersTableBody element not found');
                return;
            }

            const activeTab = document.querySelector('.order-tab.active')?.dataset.type || 'all';

            console.log('Tables data:', d.tables);
            console.log('Tables data type:', typeof d.tables);
            console.log('Tables data length:', d.tables?.length);
            console.log('Active tab:', activeTab);

            // For now, show all tables with orders without filtering
            let filteredTables = d.tables.filter(table => table.orders && table.orders.length > 0);

            console.log('Filtered tables:', filteredTables);
            console.log('Filtered tables length:', filteredTables.length);

            if (filteredTables.length === 0) {
                ct.innerHTML = '<div class="col-12"><p class="text-muted text-center py-4">No recent orders found</p></div>';
                return;
            }

            ct.innerHTML = filteredTables.map(table => {
                const oh = table.orders.length ? table.orders.map((order, orderIndex) => {
                    const displayItems = order.items.slice(0, 4);
                    const remainingItems = order.items.length - 4;
                    const itemsHtml = displayItems.map(item => `
                        <div class="order-item-row">
                            <span class="item-name ${item.status==='cancelled'?'text-decoration-line-through text-muted':''}">${item.name}</span>
                            <span class="item-qty ${item.status==='cancelled'?'text-decoration-line-through':''}">x${item.qty}</span>
                        </div>
                    `).join('');

                    const moreItemsHtml = remainingItems > 0 ? `
                        <div class="order-item-row more-items">
                            <span class="text-muted" style="font-size: 11px;">+ ${remainingItems} more items</span>
                        </div>
                    ` : '';

                    return `<div class="order-section ${orderIndex > 0 ? 'border-top pt-2 mt-2' : ''}">
                        <div class="order-id">#${order.order_no || 'ORD-' + order.id}</div>
                        <div class="order-header">
                            <span class="order-status-badge status-${order.status}">${order.status}</span>
                            <span class="order-time">${order.created_at}</span>
                        </div>
                        <div class="order-items">
                            ${itemsHtml}
                            ${moreItemsHtml}
                        </div>
                        <div class="order-footer">
                            <span class="items-count">Items: ${order.items_count}</span>
                            <span class="order-total">Rs ${parseFloat(order.total_amount).toFixed(2)}</span>
                        </div>
                        <div class="order-actions-compact">
                            <button class="action-btn-cancel" onclick="event.stopPropagation();cancelOrder(${order.id})">Cancel</button>
                            ${order.status !== 'completed' && order.status !== 'cancelled' ? `<button class="action-btn-add" onclick="event.stopPropagation();openAddItemsModal(${table.id}, '${table.name}', ${order.id})" title="Add Items">
                                <i class="bi bi-plus-lg"></i>
                            </button>` : ''}
                            <button class="action-btn-print" onclick="event.stopPropagation();printOrderSlip(${order.id}, '${table.name}')" title="Order Slip">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button class="action-btn-checkout" onclick="event.stopPropagation();openCheckoutModal(${table.id}, '${table.name}', ${order.id})">Checkout</button>
                        </div>
                    </div>`;
                }).join('') : '<p class="text-muted small py-2 mb-0">No recent orders</p>';

                return `<div class="col-3">
                    <div class="order-card-compact">
                        <div class="order-card-header">
                            <h6 class="table-name">${table.name}</h6>
                            <div class="header-right">
                                <span class="status-badge ${table.status==='available'?'available':'occupied'}">${table.status}</span>
                            </div>
                        </div>
                        <div class="order-card-body">
                            ${oh}
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch(e) { console.warn('Failed to load orders:', e); }
    }

    function formatDate(ds) {
        return new Date(ds).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // ── Modern Confirmation Modal ──
    let pendingAction = null;

    function confirmAction(type, id, itemName) {
        const overlay = document.getElementById('confirmOverlay');
        const icon = document.getElementById('confirmIcon');
        const title = document.getElementById('confirmTitle');
        const desc = document.getElementById('confirmDesc');
        const proceed = document.getElementById('confirmProceed');

        if (type === 'cancel-order') {
            icon.className = 'confirm-icon warning';
            icon.innerHTML = '<i class="bi bi-x-circle"></i>';
            title.textContent = 'Cancel Order?';
            desc.textContent = 'This will cancel all items and delete the order. The table will be freed for new orders.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Cancel Order';
            pendingAction = function() { executeCancelOrder(id); };
        } else if (type === 'cancel-item') {
            icon.className = 'confirm-icon warning';
            icon.innerHTML = '<i class="bi bi-dash-circle"></i>';
            title.textContent = 'Cancel Item?';
            desc.textContent = 'Remove "' + itemName + '" from this order.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Cancel Item';
            pendingAction = function() { executeCancelOrderItem(id); };
        } else if (type === 'delete') {
            icon.className = 'confirm-icon danger';
            icon.innerHTML = '<i class="bi bi-trash"></i>';
            title.textContent = 'Delete Order?';
            desc.textContent = 'This will permanently delete the entire order and all its items. Cannot be undone.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Delete Permanently';
            pendingAction = function() { executeDeleteOrder(id); };
        } else {
            icon.className = 'confirm-icon danger';
            icon.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
            title.textContent = 'Are you sure?';
            desc.textContent = 'This action cannot be undone.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Confirm';
            pendingAction = function() { if (typeof id === 'function') id(); };
        }

        overlay.classList.add('active');
    }

    document.getElementById('confirmCancel').addEventListener('click', function() {
        document.getElementById('confirmOverlay').classList.remove('active');
        pendingAction = null;
    });

    document.getElementById('confirmProceed').addEventListener('click', function() {
        document.getElementById('confirmOverlay').classList.remove('active');
        if (pendingAction) {
            pendingAction();
            pendingAction = null;
        }
    });

    document.getElementById('confirmOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            pendingAction = null;
        }
    });

    async function executeCancelOrder(orderId) {
        try {
            const r = await fetch(`/admin/orders/${orderId}/cancel`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' } });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to cancel order'); }
    }

    async function executeCancelOrderItem(itemId) {
        try {
            const r = await fetch('/admin/order-items/'+itemId+'/cancel', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ reason: '' }) });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to cancel item'); }
    }

    async function executeDeleteOrder(orderId) {
        try {
            const r = await fetch(`/admin/orders/${orderId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to delete order'); }
    }

    // Legacy wrappers with modal
    async function cancelOrder(orderId) { confirmAction('cancel-order', orderId); }
    async function cancelOrderItem(itemId, itemName) { confirmAction('cancel-item', itemId, itemName); }

    // Order tab switching
    document.querySelectorAll('.order-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.order-tab').forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            // Reload orders with new filter
            loadRecentOrders();
        });
    });
    async function deleteOrder(orderId) { confirmAction('delete', orderId); }

    function refreshOrders() { loadRecentOrders(); loadKOTs(); showToast('success', 'Refreshed'); }

    document.addEventListener('DOMContentLoaded', () => loadRecentOrders());
</script>
<!-- v2.0 - Quick Billing Update -->
@endpush
