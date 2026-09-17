<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="restaurant-name" content="<?php echo e($siteName ?? 'Restaurant Name'); ?>">
    <meta name="restaurant-address" content="<?php echo e($address ?? 'Your Restaurant Address'); ?>">
    <meta name="restaurant-phone" content="<?php echo e($contactPhone ?? 'Your Phone Number'); ?>">
    <meta name="user-name" content="<?php echo e(Auth::user()->name ?? 'Staff'); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>POS - <?php echo e($siteName ?? 'Restaurant'); ?></title>

    <?php echo $__env->make('admin.includes.top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- POS Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/pos.css')); ?>">
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
                <span><?php echo e($siteName ?? 'Restaurant'); ?></span>
                <small style="font-weight:400;font-size:12px;opacity:0.7;margin-left:4px;">POS</small>
            </div>
            <div class="pos-topbar-nav">
                <button class="pos-nav-btn" data-bs-toggle="modal" data-bs-target="#todaysOrdersModal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Orders</span>
                </button>
                <button class="pos-nav-btn" data-bs-toggle="modal" data-bs-target="#tablesModal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/>
                    </svg>
                    <span>Table</span>
                </button>
                <button class="pos-nav-btn" data-bs-toggle="modal" data-bs-target="#kotModal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span>KOT</span>
                </button>
                <button class="pos-nav-btn" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    <span>Checkout</span>
                </button>
            </div>
            <div class="pos-topbar-actions">
                <div class="dropdown pos-actions-dropdown">
                    <button class="pos-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item" type="button" onclick="location.reload()">
                                Refresh
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#ongoingOrdersModal">
                                Ongoing
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#deliveryOrdersModal">
                                Delivery
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#todaysOrdersModal">
                                Today's
                            </button>
                        </li>
                    </ul>
                </div>

                <button class="pos-btn pos-header-action" onclick="location.reload()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    <span>Refresh</span>
                </button>
                <button class="pos-btn pos-btn-warning pos-header-action" data-bs-toggle="modal" data-bs-target="#ongoingOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Ongoing</span>
                </button>
                <button class="pos-btn pos-btn-success pos-header-action" data-bs-toggle="modal" data-bs-target="#deliveryOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Delivery</span>
                </button>
                <button class="pos-btn pos-btn-info pos-header-action" data-bs-toggle="modal" data-bs-target="#todaysOrdersModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Today's</span>
                </button>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="pos-btn" style="background:rgba(255,255,255,0.15);">
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
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button class="pos-cat-btn" data-category="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php if($dishesByCategory->isEmpty()): ?>
                            <div class="text-center py-5">
                                <p class="text-muted">No menu items found. Please add menu items to categories.</p>
                                <p class="text-muted small">Categories: <?php echo e($categories->count()); ?> | Menu Items: <?php echo e($menuItems->count()); ?></p>
                            </div>
                        <?php else: ?>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($dishesByCategory->has($category->id) && $dishesByCategory[$category->id]->count() > 0): ?>
                                    <div class="pos-menu-section collapsed" data-category-id="<?php echo e($category->id); ?>">
                                        <div class="pos-menu-title" onclick="toggleCategory(this)">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                                            </svg>
                                            <?php echo e($category->name); ?>

                                            <span class="badge-count"><?php echo e($dishesByCategory[$category->id]->count()); ?> items</span>
                                            <svg class="category-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"/>
                                            </svg>
                                        </div>

                                        <div class="pos-items-grid" style="display: none;">
                                            <?php $__currentLoopData = $dishesByCategory[$category->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="pos-item-card"
                                                     data-category-id="<?php echo e($dish->category_id); ?>"
                                                     onclick='addToCart(<?php echo e($dish->id); ?>, <?php echo json_encode($dish->name, 15, 512) ?>, <?php echo e((float) ($dish->final_price ?? $dish->price)); ?>, <?php echo json_encode($dish->image_url, 15, 512) ?>)'>
                                                    <div class="pos-item-img-wrap">
                                                        <?php if($dish->getFirstMediaUrl('image')): ?>
                                                            <img src="<?php echo e($dish->getFirstMediaUrl('image')); ?>" alt="<?php echo e($dish->name); ?>" class="pos-item-img">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('assets/images/defaultfood.png')); ?>" alt="<?php echo e($dish->name); ?>" class="pos-item-img">
                                                        <?php endif; ?>
                                                        <div class="pos-item-add-overlay">
                                                            <div class="pos-item-add-circle">+</div>
                                                        </div>
                                                    </div>
                                                    <div class="pos-item-info">
                                                        <div class="pos-item-name"><?php echo e($dish->name); ?></div>
                                                        <div class="pos-item-price">Rs <?php echo e(number_format($dish->final_price ?? $dish->price, 0)); ?></div>
                                                        <?php if(($dish->original_price ?? 0) > ($dish->final_price ?? $dish->price)): ?>
                                                            <div class="pos-item-price-orig">Rs <?php echo e(number_format($dish->original_price, 0)); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
                        <div class="row g-2">
                            <div class="col-7">
                                <div class="pos-field">
                                    <label>Table</label>
                                    <select class="pos-select" id="tableSelect">
                                        <option value="">Select Table</option>
                                        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($table->id); ?>" <?php if($table->status->value === 'occupied'): ?> data-occupied="true" style="color:#d97706;" <?php endif; ?>><?php echo e($table->name); ?><?php if($table->status->value === 'occupied'): ?> (Occupied)<?php endif; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="pos-field">
                                    <label>Waiter</label>
                                    <select class="pos-select" id="waiterSelect">
                                        <option value="">Select Waiter</option>
                                        <?php $__currentLoopData = $waiters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $waiter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($waiter->id); ?>"><?php echo e($waiter->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
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
                <div class="modal-header pos-modal-header-success">
                    <h5 class="modal-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Order Confirmed
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="width:60px;height:60px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h6 class="mb-2">Order Created Successfully!</h6>
                    <p class="text-muted mb-3" id="orderDetails">Order confirmed!</p>
                    <div class="d-grid gap-2">
                        <button class="pos-btn pos-btn-modal-bill w-100" onclick="printOrderBill()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Print Bill
                        </button>
                        <button class="pos-btn pos-btn-modal-kot w-100" onclick="printKot()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            Print KOT
                        </button>
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

    <!-- Tables Modal -->
    <div class="modal fade" id="tablesModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#3b82f6;color:#fff;border-radius:10px 10px 0 0;">
                    <h5 class="modal-title">Tables</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="tablesContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-info mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading tables...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pos-btn" style="background:var(--pos-surface);color:var(--pos-text);" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="pos-btn pos-btn-info" onclick="refreshTables()">Refresh</button>
                </div>
            </div>
        </div>
    </div>

    <!-- KOT Modal -->
    <div class="modal fade" id="kotModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#8b5cf6;color:#fff;border-radius:10px 10px 0 0;">
                    <h5 class="modal-title">Kitchen Order Tickets (KOT)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="kotContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-purple mb-3" role="status"><span class="visually-hidden">Loading...</span></div>
                            <p class="text-muted">Loading KOTs...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pos-btn" style="background:var(--pos-surface);color:var(--pos-text);" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="pos-btn pos-btn-primary" onclick="refreshKots()">Refresh</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 98vw;">
            <div class="modal-content" style="border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; max-height: 95vh;">
                <div class="modal-header" style="background: white; border: 1px solid #e5e7eb; border-bottom: none; padding: 8px 12px; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <h5 class="modal-title mb-0" style="font-size: 14px; font-weight: 700; color: #1f2937;">Checkout - <span id="checkoutTableName">Table 1</span></h5>
                    </div>
                    <div style="display: flex; gap: 4px; align-items: center;">
                        <button type="button" class="btn btn-sm" onclick="toggleQuickMode()" style="font-size: 10px; padding: 4px 8px; border: 1px solid #e5e7eb; background: white; border-radius: 4px;">Switch to Quick Mode</button>
                        <button type="button" class="btn btn-sm" onclick="downloadInvoice()" style="font-size: 10px; padding: 4px 8px; border: 1px solid #e5e7eb; background: white; border-radius: 4px;">Download</button>
                        <button type="button" class="btn btn-sm" onclick="printEstimate()" style="font-size: 10px; padding: 4px 8px; background: #3b82f6; color: white; border: none; border-radius: 4px;">Print Estimate</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-left: 4px; padding: 0; width: 20px; height: 20px;"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="background: #f8f9fa; flex: 1; overflow: hidden; display: flex;">
                    <div id="checkoutContent" style="padding: 8px; flex: 0 0 70%; overflow-y: auto;">
                        <div style="text-align: center; padding: 20px; color: #64748b;">
                            <div style="font-size: 1.5rem; margin-bottom: 8px;">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <p style="font-size: 12px;">Loading checkout data...</p>
                        </div>
                    </div>
                    <div id="estimateInvoicePanel" style="flex: 0 0 30%; background: #fef3c7; padding: 12px; overflow-y: auto; border-left: 1px solid #fcd34d; flex-shrink: 0;">
                        <div style="text-align: center; font-weight: 700; color: #92400e; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #fcd34d; font-size: 14px;">ESTIMATE INVOICE</div>
                        <div id="estimateInvoiceContent">
                            <div style="text-align: center; color: #78350f; padding: 12px; font-size: 12px;">
                                Loading estimate...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finalBillModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered final-bill-modal">
            <div class="modal-content final-bill-content">
                <div class="modal-header final-bill-header">
                    <h5 class="modal-title mb-0">Final Bill</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="finalBillContent" class="final-bill-print-area"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="downloadFinalBillPdf()">Save as PDF</button>
                    <button type="button" class="btn btn-primary" onclick="printFinalBill()">Print Bill</button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('admin.includes.bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- POS JavaScript -->
    <script src="<?php echo e(asset('assets/js/pos.js')); ?>"></script>

    <style>
        .size-btn-inline {
            padding: 8px 4px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0;
            cursor: pointer;
            font-size: 10px;
            font-weight: 600;
            transition: all 0.15s;
            min-width: 35px;
        }
        .size-btn-inline:hover {
            background: #e5e7eb;
        }
        .size-btn-inline.active {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        .final-bill-modal .modal-dialog {
            max-width: 760px;
        }

        .final-bill-header {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: #fff;
            border-bottom: 0;
        }

        .final-bill-print-area {
            background: #fff;
            color: #111827;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .final-bill-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 18px;
        }

        .final-bill-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .final-bill-logo {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8d66d 0%, #d97706 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            overflow: hidden;
        }

        .final-bill-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .final-bill-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .final-bill-meta {
            font-size: 12px;
            color: #4b5563;
            line-height: 1.7;
        }

        .final-bill-headline {
            text-align: center;
            letter-spacing: 0.12em;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 18px;
            color: #111827;
        }

        .final-bill-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 18px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .final-bill-summary strong {
            color: #111827;
        }

        .final-bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .final-bill-table th,
        .final-bill-table td {
            border: 1px solid #e5e7eb;
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }

        .final-bill-table th {
            background: #f9fafb;
            font-weight: 700;
            color: #374151;
        }

        .final-bill-totals {
            margin-left: auto;
            width: min(100%, 300px);
            font-size: 14px;
        }

        .final-bill-totals-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .final-bill-totals-row.grand {
            font-weight: 800;
            font-size: 18px;
            color: #111827;
            border-top: 2px solid #111827;
            border-bottom: none;
            margin-top: 10px;
            padding-top: 12px;
        }

        .final-bill-footer {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 18px;
            border-top: 2px solid #e5e7eb;
            padding-top: 14px;
            font-size: 12px;
            color: #374151;
        }

        .final-bill-footer strong {
            display: block;
            margin-bottom: 4px;
            color: #111827;
        }

        .final-bill-thanks {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .final-bill-print-area, .final-bill-print-area * {
                visibility: visible;
            }
            .final-bill-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
            }
            .modal-backdrop,
            .modal-header,
            .modal-footer,
            .btn,
            .btn-close {
                display: none !important;
            }
        }
        .category-chevron {
            transition: transform 0.3s ease;
            margin-left: auto;
        }
        
        .pos-menu-section.collapsed .category-chevron {
            transform: rotate(0deg);
        }
        
        .pos-menu-section:not(.collapsed) .category-chevron {
            transform: rotate(180deg);
        }
    </style>

    <script>
        // Checkout Modal Functionality
        let currentTableId = null;
        let currentCheckoutPayload = null;

        document.addEventListener('DOMContentLoaded', function() {
            const checkoutModal = document.getElementById('checkoutModal');
            
            // Load checkout data when modal opens
            checkoutModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const tableId = button.getAttribute('data-table-id');
                const tableName = button.getAttribute('data-table-name');
                
                if (tableId) {
                    currentTableId = tableId;
                    document.getElementById('checkoutTableName').textContent = tableName || 'Table ' + tableId;
                    loadCheckoutData(tableId);
                }
            });

        });

        function loadCheckoutData(tableId) {
            const contentDiv = document.getElementById('checkoutContent');
            contentDiv.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; color: #64748b;">
                    <div style="font-size: 3rem; margin-bottom: 16px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <p style="font-size: 1.1rem;">Loading checkout data...</p>
                </div>
            `;

            fetch(`/admin/orders/table/${tableId}/checkout-data`)
                .then(response => response.json())
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

        function renderCheckoutContent(data) {
            currentCheckoutPayload = data;
            const contentDiv = document.getElementById('checkoutContent');
            const estimateDiv = document.getElementById('estimateInvoiceContent');
            const items = data.items || [];
            const table = data.table || {};
            
            let itemsHtml = items.map((item, index) => {
                const basePrice = item.unit_price / (item.size || 1);
                const sizeLabel = item.size === 0.5 ? 'Half' : item.size === 1 ? 'Full' : '';
                return `
                <tr data-item-key="${item.menu_item_id}-${item.size}" data-base-price="${basePrice}" data-quantity="${item.quantity}">
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <button class="size-btn-inline ${item.size === 0.5 ? 'active' : ''}" onclick="changeCheckoutItemSize('${item.menu_item_id}-${item.size}', 0.5, this)">Half</button>
                            <button class="size-btn-inline ${item.size === 1 ? 'active' : ''}" onclick="changeCheckoutItemSize('${item.menu_item_id}-${item.size}', 1, this)">Full</button>
                        </div>
                    </td>
                    <td>${item.quantity}</td>
                    <td class="item-rate">Rs ${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td>0.00</td>
                    <td class="item-total">Rs ${parseFloat(item.unit_price * item.quantity).toFixed(2)}</td>
                </tr>
            `}).join('');

            if (items.length === 0) {
                itemsHtml = '<tr><td colspan="7" style="text-align:center;padding:20px;">No items</td></tr>';
            }

            // Main checkout content (without estimate invoice)
            contentDiv.innerHTML = `
                <!-- Action Buttons -->
                <div style="display: flex; gap: 4px; margin-bottom: 6px; flex-wrap: wrap;">
                    <button class="btn btn-sm" style="font-size: 10px; padding: 4px 8px; border: 1px solid #e5e7eb; background: white; border-radius: 4px;">Split Bill</button>
                    <button class="btn btn-sm" style="font-size: 10px; padding: 4px 8px; border: 1px solid #e5e7eb; background: white; border-radius: 4px;">Complimentary</button>
                    <button class="btn btn-sm" style="font-size: 10px; padding: 4px 8px; border: 1px solid #e5e7eb; background: white; border-radius: 4px;">Add Extra Charges</button>
                </div>

                <!-- Items Table -->
                <div style="background: white; border-radius: 4px; padding: 8px; margin-bottom: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    <h6 style="font-weight: 700; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; color: #1f2937; font-size: 11px;">All Items</h6>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f9fafb;">
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">S.N</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Item</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Size</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">QTY</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Rate</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Discount</th>
                                <th style="padding: 4px 6px; text-align: left; font-size: 10px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Item Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>

                <!-- Customer / Staff + Summary -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <!-- Customer / Staff -->
                    <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                        <div style="display: flex; gap: 4px; margin-bottom: 6px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">
                            <button class="btn btn-sm active" style="font-size: 10px; padding: 2px 8px; border: none; background: none; color: #3b82f6; border-bottom: 2px solid #3b82f6; border-radius: 0;">Customer</button>
                            <button class="btn btn-sm" style="font-size: 10px; padding: 2px 8px; border: none; background: none; color: #6b7280; border-radius: 0;">Staff</button>
                        </div>
                        <input type="text" style="width: 100%; padding: 4px 6px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 10px;" placeholder="Search or select customer...">
                        <!-- Remarks -->
                        <div style="margin-top: 6px;">
                            <textarea style="width: 100%; padding: 4px 6px; border: 1px solid #e5e7eb; border-radius: 4px; resize: vertical; font-size: 10px;" rows="2" placeholder="Add remarks to invoice"></textarea>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                        <h6 style="font-weight: 700; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; color: #1f2937; font-size: 11px;">Totals</h6>
                        <div style="display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #6b7280; font-size: 10px;">Item Total</span>
                            <span style="font-weight: 600; color: #1f2937; font-size: 10px;">Rs ${parseFloat(data.subtotal).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #6b7280; font-size: 10px;">Sub Total</span>
                            <span style="font-weight: 600; color: #1f2937; font-size: 10px;">Rs ${parseFloat(data.subtotal).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #6b7280; font-size: 10px;">Discount (-)</span>
                            <span style="font-weight: 600; color: #1f2937; font-size: 10px;">0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #6b7280; font-size: 10px;">Taxable Amount</span>
                            <span style="font-weight: 600; color: #1f2937; font-size: 10px;">Rs ${parseFloat(data.subtotal).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #6b7280; font-size: 10px;">+ No Tax</span>
                            <span style="font-weight: 600; color: #1f2937; font-size: 10px;">0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; margin-top: 2px;">
                            <span style="color: #1f2937; font-weight: 600; font-size: 10px;">Total Amount</span>
                            <span style="font-weight: 700; color: #059669; font-size: 12px;">Rs ${parseFloat(data.grand_total).toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                <!-- Tender Amount + Payment Mode -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <!-- Tender Amount -->
                    <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                        <h6 style="font-weight: 700; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; color: #1f2937; font-size: 11px;">Tender Amount</h6>
                        <input type="number" id="tenderAmount" value="${parseFloat(data.grand_total).toFixed(2)}" style="width: 100%; padding: 4px 6px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 12px; font-weight: 600;">
                    </div>

                    <!-- Payment Mode -->
                    <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                        <h6 style="font-weight: 700; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; color: #1f2937; font-size: 11px;">Payment Mode *</h6>
                        <div style="display: flex; gap: 4px; margin-bottom: 6px;">
                            <button class="btn btn-sm active" style="border: 1px solid #e5e7eb; background: #ecfdf5; color: #059669; border-color: #059669; font-size: 10px; padding: 4px 8px; border-radius: 4px;" onclick="selectPaymentStatus(this, 'paid')">Paid</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 10px; padding: 4px 8px; border-radius: 4px;" onclick="selectPaymentStatus(this, 'unpaid')">Unpaid / Credit</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 10px; padding: 4px 8px; border-radius: 4px;" onclick="selectPaymentStatus(this, 'partial')">Partial</button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px;">
                            <button class="btn btn-sm active" style="border: 1px solid #e5e7eb; background: #eff6ff; color: #3b82f6; border-color: #3b82f6; font-size: 9px; padding: 4px 2px; border-radius: 4px;" onclick="selectPaymentMethod(this, 'cash')">Cash</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 9px; padding: 4px 2px; border-radius: 4px;" onclick="selectPaymentMethod(this, 'nepal_pay')">Nepal Pay</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 9px; padding: 4px 2px; border-radius: 4px;" onclick="selectPaymentMethod(this, 'card')">Card</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 9px; padding: 4px 2px; border-radius: 4px;" onclick="selectPaymentMethod(this, 'fonepay')">Fonepay</button>
                            <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 9px; padding: 4px 2px; border-radius: 4px;" onclick="selectPaymentMethod(this, 'bank_transfer')">Bank Transfer</button>
                        </div>
                    </div>
                </div>

                <!-- Net Sales -->
                <div style="background: #ecfdf5; border-radius: 4px; padding: 6px; text-align: center; margin-bottom: 6px;">
                    <div style="color: #059669; font-weight: 600; margin-bottom: 2px; font-size: 9px;">Net sales amount</div>
                    <div style="color: #059669; font-weight: 700; font-size: 12px;">Rs ${parseFloat(data.grand_total).toFixed(2)}</div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 4px;">
                    <button class="btn flex-grow-1" style="background: #9ca3af; color: white; padding: 6px; font-size: 10px; font-weight: 700; border: none; border-radius: 4px;" onclick="printEstimate()">Confirm & Print</button>
                    <button class="btn flex-grow-1" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 6px; font-size: 10px; font-weight: 700; border: none; border-radius: 4px;" onclick="completeCheckout()">Confirm Checkout</button>
                </div>
            `;

            // Estimate invoice content (separate panel)
            estimateDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Invoice No:</span>
                    <span>##</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Date:</span>
                    <span>${new Date().toLocaleDateString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Dine In:</span>
                    <span>${table.name || 'N/A'}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Customer:</span>
                    <span>Cash Customer</span>
                </div>
                <div style="margin: 10px 0; padding: 8px 0; border-top: 1px solid #fcd34d; border-bottom: 1px solid #fcd34d;">
                    <div style="font-weight: 600; margin-bottom: 8px; color: #78350f; font-size: 12px;">Particular</div>
                    ${items.map(item => `
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #78350f; font-size: 12px;">
                            <span>${item.name}</span>
                            <span>${parseFloat(item.unit_price).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #78350f; font-size: 12px;">
                            <span>${item.quantity}</span>
                            <span>${parseFloat(item.unit_price * item.quantity).toFixed(2)}</span>
                        </div>
                    `).join('')}
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Total (Particular/QTY)</span>
                    <span>${items.length}/3</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Rs</span>
                    <span>${parseFloat(data.grand_total).toFixed(2)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Amount in words:</span>
                    <span>Two Hundred Sixty Five Nepalese Rupee Only</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Payment Mode:</span>
                    <span>Unpaid (Rs ${parseFloat(data.grand_total).toFixed(2)})</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>KOT No: 1 (by ${data.site_name || 'Staff'})</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Billed By: ${data.site_name || 'Staff'}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; color: #78350f; font-size: 12px;">
                    <span>Service Duration: 4 days 21 hrs 15 mins</span>
                </div>
                <div style="text-align: center; margin-top: 12px; font-weight: 600; color: #92400e; font-size: 11px;">
                    This is not a Tax Invoice!<br>
                    Kindly accept the original bill from the counter.
                </div>
                <div style="text-align: center; margin-top: 12px; color: #78350f; font-size: 11px;">
                    Thank You<br>
                    Thank you for your visit! Visit again
                </div>
            `;
        }

        let selectedPaymentStatus = 'paid';
        let selectedPaymentMethod = 'cash';

        function selectPaymentStatus(btn, status) {
            const parent = btn.parentElement;
            parent.querySelectorAll('.btn').forEach(b => {
                b.style.background = 'white';
                b.style.color = '#374151';
                b.style.borderColor = '#e5e7eb';
            });
            btn.style.background = '#ecfdf5';
            btn.style.color = '#059669';
            btn.style.borderColor = '#059669';
            selectedPaymentStatus = status;
        }

        function selectPaymentMethod(btn, method) {
            const parent = btn.parentElement;
            parent.querySelectorAll('.btn').forEach(b => {
                b.style.background = 'white';
                b.style.color = '#374151';
                b.style.borderColor = '#e5e7eb';
            });
            btn.style.background = '#eff6ff';
            btn.style.color = '#3b82f6';
            btn.style.borderColor = '#3b82f6';
            selectedPaymentMethod = method === 'nepal_pay' || method === 'fonepay' || method === 'bank_transfer' ? 'digital_wallet' : method;
        }

        function buildFinalBillMarkup(payload, responseData = {}) {
            const items = payload.items || [];
            const subtotal = Number(payload.subtotal || 0);
            const serviceCharge = Number(payload.service_charge_amount || 0);
            const vatAmount = Number(payload.vat_amount || 0);
            const totalAmount = Number(payload.grand_total || responseData.total_amount || subtotal + serviceCharge + vatAmount || 0);
            const paymentMethod = responseData.payment_method || selectedPaymentMethod || 'cash';
            const invoiceNumber = responseData.invoice_number || 'INV-' + new Date().toISOString().slice(0, 10).replace(/-/g, '') + '-001';
            const tenderAmount = Number(responseData.tender_amount || document.getElementById('tenderAmount')?.value || totalAmount);
            const changeAmount = Number(responseData.change_amount ?? Math.max(0, tenderAmount - totalAmount));
            const tableName = payload.table?.name || responseData.table_name || 'Walk-in';
            const logoUrl = payload.logo_url || responseData.logo_url || '';
            const restaurantName = payload.site_name || responseData.restaurant_name || 'Restaurant';
            const restaurantAddress = payload.address || responseData.restaurant_address || '';
            const restaurantPhone = payload.contact_phone || responseData.restaurant_phone || '';
            const cashierName = responseData.cashier_name || document.querySelector('meta[name="user-name"]')?.content || 'Cashier';
            const orderNo = responseData.order_no || (payload.orders && payload.orders[0] ? payload.orders[0].order_no : 'N/A');

            return `
                <div class="final-bill-top">
                    <div class="final-bill-brand">
                        <div class="final-bill-logo">
                            ${logoUrl ? '<img src="' + logoUrl + '" alt="Restaurant logo">' : 'R'}
                        </div>
                        <div>
                            <p class="final-bill-name">${restaurantName}</p>
                            <div class="final-bill-meta">${restaurantAddress || 'Restaurant Address'}<br>${restaurantPhone || 'Phone Number'}</div>
                        </div>
                    </div>
                    <div class="final-bill-meta text-end">
                        <strong>Invoice No:</strong> ${invoiceNumber}<br>
                        <strong>Date:</strong> ${new Date().toLocaleString('en-GB', { dateStyle: 'short', timeStyle: 'short' })}
                    </div>
                </div>

                <div class="final-bill-headline">Official Bill</div>

                <div class="final-bill-summary">
                    <div><strong>Table / Order:</strong> ${tableName} / ${orderNo}</div>
                    <div><strong>Payment:</strong> ${paymentMethod.toString().replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase())}</div>
                    <div><strong>Cashier:</strong> ${cashierName}</div>
                    <div><strong>Customer:</strong> Walk-in Customer</div>
                </div>

                <table class="final-bill-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">S.N.</th>
                            <th>Item</th>
                            <th style="width: 12%;">Qty</th>
                            <th style="width: 18%;">Rate</th>
                            <th style="width: 18%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${items.map((item, index) => {
                            const qty = Number(item.quantity || 0);
                            const rate = Number(item.unit_price || 0);
                            const amount = qty * rate;
                            return `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.name || 'Item'}</td>
                                    <td>${qty}</td>
                                    <td>Rs ${rate.toFixed(2)}</td>
                                    <td>Rs ${amount.toFixed(2)}</td>
                                </tr>
                            `;
                        }).join('') || '<tr><td colspan="5" class="text-center">No items</td></tr>'}
                    </tbody>
                </table>

                <div class="final-bill-totals">
                    <div class="final-bill-totals-row"><span>Subtotal</span><span>Rs ${subtotal.toFixed(2)}</span></div>
                    <div class="final-bill-totals-row"><span>Service Charge</span><span>Rs ${serviceCharge.toFixed(2)}</span></div>
                    <div class="final-bill-totals-row"><span>VAT</span><span>Rs ${vatAmount.toFixed(2)}</span></div>
                    <div class="final-bill-totals-row grand"><span>Grand Total</span><span>Rs ${totalAmount.toFixed(2)}</span></div>
                    <div class="final-bill-totals-row"><span>Paid</span><span>Rs ${tenderAmount.toFixed(2)}</span></div>
                    <div class="final-bill-totals-row"><span>Change</span><span>Rs ${changeAmount.toFixed(2)}</span></div>
                </div>

                <div class="final-bill-footer">
                    <div>
                        <strong>Payment Method</strong>
                        ${paymentMethod.toString().replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase())}
                    </div>
                    <div>
                        <strong>Notes</strong>
                        Thank you for your visit.
                    </div>
                </div>

                <div class="final-bill-thanks">Thank you for dining with us!</div>
            `;
        }

        function showFinalBillModal(payload, responseData = {}) {
            const modalEl = document.getElementById('finalBillModal');
            const billContent = document.getElementById('finalBillContent');
            if (!modalEl || !billContent) return;

            const safePayload = payload && typeof payload === 'object' ? payload : {
                items: [],
                subtotal: Number(responseData.subtotal || 0),
                service_charge_amount: Number(responseData.service_charge_amount || 0),
                vat_amount: Number(responseData.vat_amount || 0),
                grand_total: Number(responseData.total_amount || responseData.grand_total || 0),
                table: { name: responseData.table_name || 'Table' },
                logo_url: responseData.logo_url || '',
                site_name: responseData.restaurant_name || document.querySelector('meta[name="restaurant-name"]')?.content || 'Restaurant',
                address: responseData.restaurant_address || document.querySelector('meta[name="restaurant-address"]')?.content || '',
                contact_phone: responseData.restaurant_phone || document.querySelector('meta[name="restaurant-phone"]')?.content || '',
                orders: []
            };

            billContent.innerHTML = buildFinalBillMarkup(safePayload, responseData);
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function printFinalBill() {
            window.print();
        }

        function downloadFinalBillPdf() {
            window.print();
        }

        function toggleQuickMode() {
            console.log('Toggle Quick Mode');
        }

        function downloadInvoice() {
            window.print();
        }

        function printEstimate() {
            window.print();
        }

        async function completeCheckout() {
            if (!currentCheckoutPayload) {
                alert('Checkout data is not available yet.');
                return;
            }

            const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
            const totalAmount = Number(currentCheckoutPayload.grand_total || currentCheckoutPayload.total_amount || 0);
            const subtotal = Number(currentCheckoutPayload.subtotal || 0);
            const serviceChargeAmount = Number(currentCheckoutPayload.service_charge_amount || 0);
            const vatPercent = Number(currentCheckoutPayload.vat_percent || 0);
            const vatAmount = Number(currentCheckoutPayload.vat_amount || 0);

            if (tenderAmount < totalAmount && selectedPaymentStatus === 'paid') {
                alert('Amount received cannot be less than total amount');
                return;
            }

            try {
                const response = await fetch(`/admin/orders/table/${currentTableId}/checkout`, {
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
                        subtotal: subtotal,
                        service_charge_amount: serviceChargeAmount,
                        vat_percent: vatPercent,
                        vat_amount: vatAmount,
                        is_non_chargeable: false,
                    }),
                });

                const text = await response.text();
                let data;
                try {
                    data = text ? JSON.parse(text) : {};
                } catch (parseError) {
                    throw new Error(text || 'Checkout failed with an unexpected server response.');
                }

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Checkout failed');
                }

                const checkoutModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                if (checkoutModal) checkoutModal.hide();

                const payload = currentCheckoutPayload && typeof currentCheckoutPayload === 'object'
                    ? currentCheckoutPayload
                    : {
                        items: Array.isArray(data.items) ? data.items : [],
                        subtotal: Number(data.subtotal || 0),
                        service_charge_amount: Number(data.service_charge_amount || 0),
                        vat_amount: Number(data.vat_amount || 0),
                        grand_total: Number(data.total_amount || data.grand_total || 0),
                        table: { name: data.table_name || 'Table' },
                        logo_url: data.logo_url || '',
                        site_name: data.restaurant_name || document.querySelector('meta[name="restaurant-name"]')?.content || 'Restaurant',
                        address: data.restaurant_address || document.querySelector('meta[name="restaurant-address"]')?.content || '',
                        contact_phone: data.restaurant_phone || document.querySelector('meta[name="restaurant-phone"]')?.content || '',
                        orders: []
                    };

                showFinalBillModal(payload, data);
                currentCheckoutPayload = null;

                if (typeof POS !== 'undefined') {
                    POS.cart = [];
                    if (typeof updateCartDisplay === 'function') updateCartDisplay();
                }
            } catch (error) {
                alert(error?.message || 'Network error occurred');
            }
        }

        function changeCheckoutItemSize(itemKey, newSize, btn) {
            const row = btn.closest('tr');
            if (!row) return;

            const basePrice = parseFloat(row.dataset.basePrice);
            const quantity = parseInt(row.dataset.quantity);

            // Update button states
            const buttons = row.querySelectorAll('.size-btn-inline');
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Calculate new price
            const newPrice = basePrice * newSize;
            const newTotal = newPrice * quantity;

            // Update rate and total cells
            row.querySelector('.item-rate').textContent = 'Rs ' + newPrice.toFixed(2);
            row.querySelector('.item-total').textContent = 'Rs ' + newTotal.toFixed(2);
        }
    </script>
</body>
</html><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/order/pos.blade.php ENDPATH**/ ?>