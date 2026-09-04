<?php
$file = 'D:/DMC/Projects/Restaurant/resources/views/admin/order/pos.blade.php';

$content = <<<'BLADE'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="restaurant-name" content="{{ $siteName ?? 'Restaurant Name' }}">
    <meta name="restaurant-address" content="{{ $address ?? 'Your Restaurant Address' }}">
    <meta name="restaurant-phone" content="{{ $contactPhone ?? 'Your Phone Number' }}">
    <meta name="user-name" content="{{ Auth::user()->name ?? 'Staff' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - {{ $siteName ?? 'Restaurant' }}</title>

    @include('admin.includes.top')

    <!-- POS Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
</head>
<body>
    <div class="pos-wrapper">
        <!-- Top Bar -->
        <header class="pos-topbar">
            <div class="pos-topbar-brand">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <span>{{ $siteName ?? 'Restaurant' }}</span>
                <small style="font-weight:400;font-size:12px;opacity:0.7;margin-left:4px;">POS</small>
            </div>
            <div class="pos-topbar-actions">
                <button class="pos-btn" onclick="location.reload()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    <span>Refresh</span>
                </button>
                <button class="pos-btn pos-btn-warning" data-bs-toggle="modal" data-bs-target="#ongoingOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Ongoing</span>
                </button>
                <button class="pos-btn pos-btn-success" data-bs-toggle="modal" data-bs-target="#deliveryOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Delivery</span>
                </button>
                <button class="pos-btn pos-btn-info" data-bs-toggle="modal" data-bs-target="#todaysOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Today's</span>
                </button>
                <a href="{{ route('admin.orders.index') }}" class="pos-btn" style="background:rgba(255,255,255,0.15);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    <span>Exit</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <div class="pos-content">
            <!-- Left: Menu Panel -->
            <div class="pos-menu-panel">
                <!-- Filters -->
                <div class="pos-filters">
                    <!-- Category Navigation -->
                    <div class="pos-categories" id="menuCategorySlider">
                        <button class="pos-cat-btn active" data-category="all">All</button>
                        @foreach ($menuCategories as $menuCategory)
                            <button class="pos-cat-btn" data-category="{{ $menuCategory->id }}">{{ $menuCategory->name }}</button>
                        @endforeach
                    </div>
                    <!-- Search -->
                    <div class="pos-search-wrap">
                        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" class="pos-search-input" id="dishSearch" placeholder="Search dishes..." autocomplete="off">
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="pos-items-container" id="menuItemsContainer">
                    <div id="menuItemsGrid">
                        @php
                            $dishesByMenu = $dishes->groupBy('menu_id');
                        @endphp

                        @foreach ($menus as $menu)
                            @if ($dishesByMenu->has($menu->id) && $dishesByMenu[$menu->id]->count() > 0)
                                <div class="pos-menu-section" data-category-id="{{ $menu->menu_category_id }}">
                                    <div class="pos-menu-title">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                                        </svg>
                                        {{ $menu->name }}
                                        <span class="badge-count">{{ $dishesByMenu[$menu->id]->count() }} items</span>
                                    </div>

                                    <div class="pos-items-grid">
                                        @foreach ($dishesByMenu[$menu->id] as $dish)
                                            <div class="pos-item-card"
                                                 data-menu-id="{{ $dish->menu_id }}"
                                                 data-category-id="{{ $dish->menu->menu_category_id }}"
                                                 onclick="addToCart({{ $dish->id }}, '{{ addslashes($dish->name) }}', {{ $dish->final_price }}, '{{ $dish->image_url }}')">
                                                <div class="pos-item-img-wrap">
                                                    <img src="{{ $dish->image_url ?: 'https://via.placeholder.com/200x120?text=' . urlencode($dish->name) }}" alt="{{ $dish->name }}" class="pos-item-img">
                                                    <div class="pos-item-add-overlay">
                                                        <div class="pos-item-add-circle">+</div>
                                                    </div>
                                                </div>
                                                <div class="pos-item-info">
                                                    <div class="pos-item-name">{{ $dish->name }}</div>
                                                    <div class="pos-item-price">Rs {{ number_format($dish->final_price, 0) }}</div>
                                                    @if($dish->original_price > $dish->final_price)
                                                        <div class="pos-item-price-orig">Rs {{ number_format($dish->original_price, 0) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Cart Panel -->
            <div class="pos-cart-panel" id="cartPanel">
                <div class="pos-cart-drag-handle"></div>

                <!-- Cart Header -->
                <div class="pos-cart-header">
                    <div class="pos-cart-header-top">
                        <h6>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>Order Items
                        </h6>
                        <button class="pos-clear-btn" id="clearCartBtn" style="display:none;" onclick="clearCart()">Clear All</button>
                    </div>

                    <!-- Order Type Tabs -->
                    <div class="pos-order-types">
                        <button class="pos-type-btn active" data-mode="dine_in">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            Dine In
                        </button>
                        <button class="pos-type-btn" data-mode="delivery">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Delivery
                        </button>
                        <button class="pos-type-btn" data-mode="pickup">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Pickup
                        </button>
                    </div>
                </div>

                <!-- Order Fields -->
                <div class="pos-order-fields">
                    <div id="tableSelection">
                        <div class="pos-field">
                            <label>Table</label>
                            <select class="pos-select" id="tableSelect">
                                <option value="">Select Table</option>
                                @foreach ($tables->where('status', 'available') as $table)
                                    <option value="{{ $table->id }}">{{ $table->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="customerInfo" class="d-none">
                        <div class="pos-field">
                            <label>Customer Name</label>
                            <input type="text" class="pos-input" id="customerName" placeholder="Enter name">
                        </div>
                        <div class="pos-field">
                            <label>Phone Number</label>
                            <input type="tel" class="pos-input" id="customerPhone" placeholder="Enter phone">
                        </div>
                        <div id="deliveryAddressField" class="d-none pos-field">
                            <label>Delivery Address</label>
                            <textarea class="pos-input" id="deliveryAddress" rows="2" placeholder="Enter address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="pos-cart-items" id="cartItems">
                    <div class="pos-cart-empty">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <p>No items added</p>
                        <small>Tap items from the menu</small>
                    </div>
                </div>

                <!-- Notes -->
                <div class="pos-cart-notes-wrap">
                    <button class="pos-notes-toggle" onclick="toggleNotes()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Add Remarks to KOT
                    </button>
                    <textarea class="pos-notes-textarea d-none" id="orderNotes" rows="2" placeholder="Enter order notes..."></textarea>
                </div>

                <!-- Summary -->
                <div class="pos-cart-summary">
                    <div class="pos-summary-row">
                        <span>Total Quantity</span>
                        <span><strong id="totalQty">0</strong></span>
                    </div>
                    <div class="pos-summary-row pos-summary-total">
                        <span>Total Amount</span>
                        <span class="amount">Rs <span id="totalAmount">0</span></span>
                    </div>
                    <button class="pos-confirm-btn" id="confirmOrderBtn" disabled>
                        <span class="btn-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Confirm Order
                        </span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -->

    <!-- Order Confirmation Modal -->
    <div class="modal fade" id="orderConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header pos-modal-header border-0">
                    <h5 class="modal-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Order Confirmed
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="pos-success-icon mb-3">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h6 class="mb-2">Order Created Successfully!</h6>
                    <p class="text-muted mb-3" id="orderDetails">Order confirmed!</p>
                    <div class="d-grid gap-2">
                        <button class="pos-btn pos-btn-success w-100" onclick="printOrderBill()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Print Bill
                        </button>
                        <button class="pos-btn pos-btn-primary w-100" onclick="quickCheckout()" id="quickCheckoutBtn" style="display:none;">Quick Checkout</button>
                        <a href="#" id="checkoutBtn" class="pos-btn pos-btn-info w-100" style="display:none;text-decoration:none;">Checkout</a>
                        <button class="pos-btn w-100" style="background:var(--pos-primary);color:#fff;" data-bs-dismiss="modal" onclick="startNewOrder()">Start New Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Orders Modal -->
    <div class="modal fade" id="todaysOrdersModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pos-modal-header">
                    <h5 class="modal-title">Today's Orders</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="ordersContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-danger mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading orders...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Orders Modal -->
    <div class="modal fade" id="deliveryOrdersModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#059669;color:#fff;border-radius:10px 10px 0 0;">
                    <h5 class="modal-title">Online Orders &amp; Delivery</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="deliveryOrdersContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading delivery orders...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pos-modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading order details...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pos-btn" style="background:var(--pos-surface);color:var(--pos-text);" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="pos-btn pos-btn-info" onclick="printOrderDetails()">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Status Modal -->
    <div class="modal fade" id="deliveryStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Delivery Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold small">Order No:</label>
                        <p id="modalOrderNo" class="text-muted small"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Customer:</label>
                        <p id="modalCustomerInfo" class="text-muted small"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Address:</label>
                        <p id="modalDeliveryAddress" class="text-muted small"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Status</label>
                        <select class="pos-select" id="deliveryStatus">
                            <option value="pending">Pending</option>
                            <option value="on the way">On The Way</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Notes</label>
                        <textarea class="pos-input" id="statusNotes" rows="3" placeholder="Add notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pos-btn" style="background:var(--pos-surface);color:var(--pos-text);" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="pos-btn pos-btn-primary" onclick="updateDeliveryStatus()">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ongoing Orders Modal -->
    <div class="modal fade" id="ongoingOrdersModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#d97706;color:#fff;border-radius:10px 10px 0 0;">
                    <h5 class="modal-title">Ongoing Orders</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="ongoingOrdersContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading ongoing orders...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pos-btn" style="background:var(--pos-surface);color:var(--pos-text);" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="pos-btn pos-btn-warning" onclick="refreshOngoingOrders()">Refresh</button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.bottom')

    <!-- POS JavaScript -->
    <script src="{{ asset('assets/js/pos.js') }}"></script>
</body>
</html>
BLADE;

$result = file_put_contents($file, $content);
if ($result !== false) {
    echo 'SUCCESS: File written! Size: ' . $result . ' bytes' . PHP_EOL;
} else {
    echo 'FAILED to write file!' . PHP_EOL;
    exit(1);
}
