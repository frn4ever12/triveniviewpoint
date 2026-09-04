<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="restaurant-name" content="{{ $siteName ?? 'Restaurant Name' }}">
    <meta name="restaurant-address" content="{{ $address ?? '' }}">
    <meta name="restaurant-phone" content="{{ $contactPhone ?? '' }}">
    <meta name="user-name" content="{{ Auth::user()->name ?? 'Staff' }}">
    <title>Checkout - {{ $siteName ?? 'Restaurant' }}</title>

    @include('admin.includes.top')

    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">

    <style>
        :root {
            --checkout-primary: #059669;
            --checkout-primary-dark: #047857;
            --checkout-primary-light: #d1fae5;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .checkout-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar */
        .checkout-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            padding: 0 20px;
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .checkout-topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 18px;
        }

        .checkout-topbar-brand .brand-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkout-topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #fff;
            background: rgba(255,255,255,0.15);
            white-space: nowrap;
        }

        .checkout-btn:hover {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        .checkout-btn-primary {
            background: #059669;
        }
        .checkout-btn-primary:hover { background: #047857; }

        .checkout-btn-danger {
            background: #dc2626;
        }
        .checkout-btn-danger:hover { background: #b91c1c; }

        /* Main Content */
        .checkout-content {
            display: flex;
            flex: 1;
            gap: 20px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Left Panel - Order Summary */
        .checkout-left {
            flex: 1;
            min-width: 0;
        }

        /* Right Panel - Payment */
        .checkout-right {
            width: 420px;
            min-width: 420px;
        }

        /* Cards */
        .checkout-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .checkout-card-header {
            padding: 14px 18px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 15px;
            color: #0f172a;
            background: #f8fafc;
        }

        .checkout-card-body {
            padding: 16px 18px;
        }

        /* Table Info Bar */
        .table-info-bar {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 12px 18px;
            background: #f0fdf4;
            border-bottom: 1px solid #bbf7d0;
            font-size: 13px;
        }

        .table-info-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #065f46;
        }

        .table-info-item strong {
            font-weight: 600;
        }

        /* Order Items */
        .order-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item-qty {
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #475569;
            flex-shrink: 0;
        }

        .order-item-info {
            flex: 1;
            min-width: 0;
        }

        .order-item-name {
            font-weight: 600;
            font-size: 14px;
            color: #0f172a;
        }

        .order-item-detail {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .order-item-price {
            font-weight: 700;
            font-size: 14px;
            color: #059669;
            white-space: nowrap;
        }

        /* Calculation Rows */
        .calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .calc-row + .calc-row {
            border-top: 1px solid #f1f5f9;
        }

        .calc-label {
            font-size: 14px;
            color: #475569;
        }

        .calc-value {
            font-weight: 600;
            font-size: 14px;
            color: #0f172a;
        }

        .calc-input-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .calc-input {
            width: 90px;
            padding: 6px 8px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            text-align: right;
            font-weight: 600;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }

        .calc-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .calc-input-suffix {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Grand Total */
        .grand-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            margin-top: 8px;
            border-top: 2px solid #e2e8f0;
        }

        .grand-total-label {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .grand-total-amount {
            font-size: 24px;
            font-weight: 800;
            color: #059669;
        }

        .grand-total-amount.non-chargeable {
            color: #94a3b8;
            text-decoration: line-through;
        }

        /* Payment Method */
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .payment-method-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s;
        }

        .payment-method-btn:hover {
            border-color: #059669;
            color: #059669;
        }

        .payment-method-btn.active {
            border-color: #059669;
            background: #f0fdf4;
            color: #059669;
            font-weight: 600;
        }

        .payment-method-btn.credit-bill {
            grid-column: 1 / -1;
        }

        /* Tender Amount */
        .tender-section {
            margin-top: 16px;
        }

        .tender-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            margin-bottom: 6px;
        }

        .tender-input-wrap {
            position: relative;
        }

        .tender-input {
            width: 100%;
            padding: 12px 16px;
            padding-left: 38px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 20px;
            font-weight: 700;
            outline: none;
            transition: border-color 0.2s;
        }

        .tender-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .tender-input:disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .tender-currency {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            font-size: 16px;
            color: #94a3b8;
        }

        /* Change Display */
        .change-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-top: 12px;
        }

        .change-display.negative {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .change-label {
            font-size: 14px;
            font-weight: 600;
            color: #065f46;
        }

        .change-label.negative {
            color: #991b1b;
        }

        .change-amount {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
        }

        .change-amount.negative {
            color: #dc2626;
        }

        /* Non-Chargeable Toggle */
        .non-chargeable-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .non-chargeable-toggle:hover {
            border-color: #94a3b8;
        }

        .non-chargeable-toggle.active {
            background: #fef3c7;
            border-color: #f59e0b;
        }

        .toggle-switch {
            width: 40px;
            height: 22px;
            background: #e2e8f0;
            border-radius: 11px;
            position: relative;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .toggle-switch::after {
            content: '';
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        .non-chargeable-toggle.active .toggle-switch {
            background: #f59e0b;
        }

        .non-chargeable-toggle.active .toggle-switch::after {
            transform: translateX(18px);
        }

        .toggle-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .non-chargeable-toggle.active .toggle-label {
            color: #92400e;
        }

        /* Complete Button */
        .complete-btn {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
            margin-top: 16px;
        }

        .complete-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
        }

        .complete-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
        }

        .complete-btn .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .complete-btn.loading .spinner {
            display: inline-block;
        }

        .complete-btn.loading .btn-text {
            opacity: 0.7;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Cancel Button */
        .cancel-order-btn {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #fecaca;
            border-radius: 8px;
            background: #fff;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .cancel-order-btn:hover {
            background: #fef2f2;
            border-color: #dc2626;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .checkout-content {
                flex-direction: column;
            }
            .checkout-right {
                width: 100%;
                min-width: unset;
            }
        }

        @media (max-width: 768px) {
            .checkout-content {
                padding: 12px;
                gap: 12px;
            }
            .checkout-topbar {
                padding: 0 12px;
            }
            .checkout-topbar-brand span {
                display: none;
            }
            .checkout-card-body {
                padding: 12px 14px;
            }
            .payment-methods {
                grid-template-columns: 1fr;
            }
            .payment-method-btn.credit-bill {
                grid-column: 1;
            }
            .grand-total-amount {
                font-size: 20px;
            }
            .tender-input {
                font-size: 18px;
                padding: 10px 14px 10px 38px;
            }
        }

        @media (max-width: 480px) {
            .checkout-topbar-actions .checkout-btn span {
                display: none;
            }
            .table-info-bar {
                gap: 10px;
                font-size: 12px;
            }
        }

        /* Print Styles */
        @media print {
            body * { visibility: hidden; }
            .print-area, .print-area * { visibility: visible; }
            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                max-width: 400px;
                margin: 0;
                padding: 10px;
                font-family: 'Courier New', monospace;
                font-size: 11px;
            }
            .no-print { display: none !important; }
        }

        /* Success Modal Light Header Fix */
        .pos-modal-header-light {
            background: #f0fdf4 !important;
            color: #065f46 !important;
            border-bottom: 1px solid #bbf7d0 !important;
        }
        .pos-modal-header-light .btn-close {
            filter: none !important;
        }
    </style>
</head>
<body>
    <div class="checkout-wrapper">
        <!-- Top Bar -->
        <header class="checkout-topbar no-print">
            <div class="checkout-topbar-brand">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <span>{{ $siteName ?? 'Restaurant Name' }}</span>
                <small style="font-weight:400;font-size:12px;opacity:0.7;margin-left:4px;">Checkout</small>
            </div>
            <div class="checkout-topbar-actions">
                <button class="checkout-btn" onclick="window.print()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    <span>Print Bill</span>
                </button>
                <a href="{{ route('admin.orders.pos') }}" class="checkout-btn checkout-btn-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    <span>Close</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <div class="checkout-content">
            <!-- Left: Order Summary -->
            <div class="checkout-left">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        Order Summary
                    </div>

                    <div class="table-info-bar">
                        @if(isset($table))
                            <div class="table-info-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                                Table: <strong>{{ $table->name ?? 'N/A' }}</strong>
                            </div>
                        @endif
                        @if(isset($order))
                            <div class="table-info-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Order: <strong>#{{ $order->order_no ?? 'N/A' }}</strong>
                            </div>
                        @endif
                        <div class="table-info-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            Date: <strong>{{ now()->format('M d, Y g:i A') }}</strong>
                        </div>
                        @if(isset($orders) && $orders->isNotEmpty() && $orders->first()->waiter)
                            <div class="table-info-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                Server: <strong>{{ $orders->first()->waiter->name ?? 'N/A' }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="checkout-card-body">
                        @php
                            $itemGroups = [];
                            if (isset($orders)) {
                                foreach ($orders as $ord) {
                                    foreach ($ord->items as $item) {
                                        $key = $item->dish_id . '-' . ($item->size ?? 1);
                                        if (!isset($itemGroups[$key])) {
                                            $itemGroups[$key] = [
                                                'name' => $item->dish->name ?? 'Item',
                                                'quantity' => 0,
                                                'unit_price' => $item->unit_price ?? 0,
                                                'size' => $item->size ?? 1,
                                                'image' => $item->dish->image_url ?? '',
                                            ];
                                        }
                                        $itemGroups[$key]['quantity'] += $item->quantity ?? 0;
                                    }
                                }
                            }
                        @endphp

                        @forelse($itemGroups as $item)
                            <div class="order-item">
                                <div class="order-item-qty">{{ $item['quantity'] }}x</div>
                                <div class="order-item-info">
                                    <div class="order-item-name">
                                        {{ $item['name'] }}
                                        @if($item['size'] == 0.5)
                                            <span style="font-size:11px;color:#64748b;font-weight:400;"> (Half)</span>
                                        @elseif($item['size'] > 1)
                                            <span style="font-size:11px;color:#64748b;font-weight:400;"> ({{ $item['size'] }}x)</span>
                                        @endif
                                    </div>
                                    <div class="order-item-detail">Rs {{ number_format($item['unit_price'], 2) }} each</div>
                                </div>
                                <div class="order-item-price">Rs {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</div>
                            </div>
                        @empty
                            <div style="text-align:center;padding:40px 0;color:#94a3b8;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:10px;opacity:0.5;">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                <p style="margin:0;font-size:14px;">No items in this order</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right: Payment Panel -->
            <div class="checkout-right">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        Payment
                    </div>

                    <div class="checkout-card-body">
                        <!-- Non-Chargeable Toggle -->
                        <div class="non-chargeable-toggle" id="nonChargeableToggle" onclick="toggleNonChargeable()">
                            <div class="toggle-switch"></div>
                            <span class="toggle-label">Non-Chargeable / Complimentary</span>
                        </div>

                        <!-- Payment Methods -->
                        <div style="margin-bottom:16px;">
                            <div style="font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Payment Method</div>
                            <div class="payment-methods">
                                <button class="payment-method-btn active" data-method="cash" onclick="selectMethod(this, 'cash')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                                    </svg>
                                    Cash
                                </button>
                                <button class="payment-method-btn" data-method="card" onclick="selectMethod(this, 'card')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                                    </svg>
                                    Card
                                </button>
                                <button class="payment-method-btn" data-method="digital_wallet" onclick="selectMethod(this, 'digital_wallet')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                                    </svg>
                                    Digital Wallet
                                </button>
                                <button class="payment-method-btn credit-bill" data-method="credit_bill" onclick="selectMethod(this, 'credit_bill')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="14" y1="12" x2="18" y2="12"/>
                                    </svg>
                                    Credit Bill (Bill Later)
                                </button>
                            </div>
                        </div>

                        <!-- Calculation Summary -->
                        <div>
                            <div class="calc-row">
                                <span class="calc-label">Subtotal</span>
                                <span class="calc-value" id="subtotalDisplay">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                            </div>

                            <div class="calc-row">
                                <span class="calc-label">Service Charge</span>
                                <div class="calc-input-group">
                                    <input type="number" class="calc-input" id="serviceChargeInput" value="0.00" step="0.01" min="0" oninput="updateCalculations()">
                                    <span class="calc-input-suffix">Rs</span>
                                </div>
                            </div>

                            <div class="calc-row">
                                <span class="calc-label">VAT</span>
                                <div class="calc-input-group">
                                    <input type="number" class="calc-input" id="vatPercentInput" value="0" step="0.01" min="0" max="100" oninput="updateCalculations()">
                                    <span class="calc-input-suffix">%</span>
                                    <span class="calc-value" id="vatAmountDisplay" style="min-width:70px;text-align:right;">Rs 0.00</span>
                                </div>
                            </div>

                            <div class="grand-total-row">
                                <span class="grand-total-label">Grand Total</span>
                                <span class="grand-total-amount" id="grandTotalDisplay">Rs {{ number_format($grandTotal ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Tender Amount -->
                        <div class="tender-section">
                            <div class="tender-label">
                                <span>Amount Received</span>
                                <span id="tenderShortStatus" style="color:#94a3b8;font-size:12px;"></span>
                            </div>
                            <div class="tender-input-wrap">
                                <span class="tender-currency">Rs</span>
                                <input type="number" class="tender-input" id="tenderAmount" placeholder="0.00" step="0.01" value="{{ number_format($grandTotal ?? 0, 2, '.', '') }}" oninput="updateChange()">
                            </div>

                            <div class="change-display" id="changeDisplay">
                                <span class="change-label" id="changeLabel">Change Due</span>
                                <span class="change-amount" id="changeAmount">Rs 0.00</span>
                            </div>
                        </div>

                        <!-- Complete Button -->
                        <button class="complete-btn" id="completeCheckoutBtn">
                            <span class="btn-text" id="checkoutBtnText">Complete Checkout</span>
                            <span class="spinner"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.bottom')

    <script>
        // State
        const state = {
            subtotal: {{ $subtotal ?? 0 }},
            grandTotal: {{ $grandTotal ?? 0 }},
            selectedMethod: 'cash',
            isNonChargeable: false,
            isTableCheckout: {{ isset($table) ? 'true' : 'false' }},
            tableId: {{ $table->id ?? 'null' }},
            orderId: {{ $order->id ?? 'null' }},
            itemCount: {{ count($itemGroups ?? []) }}
        };

        // Select Payment Method
        function selectMethod(btn, method) {
            document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            state.selectedMethod = method;
        }

        // Toggle Non-Chargeable
        function toggleNonChargeable() {
            const toggle = document.getElementById('nonChargeableToggle');
            const tenderInput = document.getElementById('tenderAmount');
            const changeDisplay = document.getElementById('changeDisplay');
            const btnText = document.getElementById('checkoutBtnText');
            const grandTotalEl = document.getElementById('grandTotalDisplay');

            state.isNonChargeable = !state.isNonChargeable;

            if (state.isNonChargeable) {
                toggle.classList.add('active');
                tenderInput.value = '0.00';
                tenderInput.disabled = true;
                changeDisplay.style.display = 'none';
                btnText.textContent = 'Complete as Non-Chargeable';
                grandTotalEl.classList.add('non-chargeable');
                document.getElementById('completeCheckoutBtn').disabled = false;
            } else {
                toggle.classList.remove('active');
                tenderInput.disabled = false;
                changeDisplay.style.display = 'flex';
                btnText.textContent = 'Complete Checkout';
                grandTotalEl.classList.remove('non-chargeable');
                tenderInput.value = state.grandTotal.toFixed(2);
                updateChange();
            }
        }

        // Update Calculations
        function updateCalculations() {
            const serviceCharge = parseFloat(document.getElementById('serviceChargeInput').value) || 0;
            const vatPercent = parseFloat(document.getElementById('vatPercentInput').value) || 0;

            const taxableAmount = state.subtotal + serviceCharge;
            const vatAmount = Math.round(taxableAmount * (vatPercent / 100) * 100) / 100;
            const newGrandTotal = taxableAmount + vatAmount;

            state.grandTotal = newGrandTotal;

            document.getElementById('vatAmountDisplay').textContent = 'Rs ' + vatAmount.toFixed(2);
            document.getElementById('grandTotalDisplay').textContent = 'Rs ' + newGrandTotal.toFixed(2);

            // Adjust tender amount
            const tenderInput = document.getElementById('tenderAmount');
            if (!tenderInput.disabled) {
                const currentTender = parseFloat(tenderInput.value) || 0;
                if (currentTender > 0) {
                    tenderInput.value = newGrandTotal.toFixed(2);
                }
                updateChange();
            }
        }

        // Update Change
        function updateChange() {
            const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
            const change = tenderAmount - state.grandTotal;
            const changeDisplay = document.getElementById('changeDisplay');
            const changeAmount = document.getElementById('changeAmount');
            const changeLabel = document.getElementById('changeLabel');
            const btn = document.getElementById('completeCheckoutBtn');

            if (change >= 0) {
                changeDisplay.classList.remove('negative');
                changeAmount.classList.remove('negative');
                changeLabel.classList.remove('negative');
                changeLabel.textContent = 'Change Due';
                changeAmount.textContent = 'Rs ' + change.toFixed(2);
                btn.disabled = false;
            } else {
                changeDisplay.classList.add('negative');
                changeAmount.classList.add('negative');
                changeLabel.classList.add('negative');
                changeLabel.textContent = 'Amount Due';
                changeAmount.textContent = 'Rs ' + Math.abs(change).toFixed(2);
                btn.disabled = true;
            }
        }

        // Complete Checkout
        document.getElementById('completeCheckoutBtn').addEventListener('click', async function() {
            const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
            const serviceCharge = parseFloat(document.getElementById('serviceChargeInput').value) || 0;
            const vatPercent = parseFloat(document.getElementById('vatPercentInput').value) || 0;

            if (!state.isNonChargeable && tenderAmount < state.grandTotal) {
                if (window.showToast) showToast('error', 'Amount received cannot be less than total amount');
                else alert('Amount received cannot be less than total amount');
                return;
            }

            const taxableAmount = state.subtotal + serviceCharge;
            const vatAmount = Math.round(taxableAmount * (vatPercent / 100) * 100) / 100;

            // Loading state
            this.classList.add('loading');
            this.disabled = true;
            const originalText = document.getElementById('checkoutBtnText').textContent;
            document.getElementById('checkoutBtnText').textContent = 'Processing...';

            try {
                const orderIdField = state.isTableCheckout ? 'tableId' : 'orderId';
                const url = state.isTableCheckout
                    ? '/admin/orders/table/' + state.tableId + '/checkout'
                    : '/admin/orders/' + state.orderId + '/checkout';

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        payment_method: state.selectedMethod,
                        tender_amount: tenderAmount,
                        total_amount: state.isNonChargeable ? 0 : state.grandTotal,
                        subtotal: state.subtotal,
                        service_charge_amount: serviceCharge,
                        vat_percent: vatPercent,
                        vat_amount: vatAmount,
                        is_non_chargeable: state.isNonChargeable
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    if (window.showToast) showToast('success', data.message || 'Checkout completed');
                    window.print();
                    setTimeout(() => {
                        window.location.href = '/checkout-dashboard';
                    }, 1500);
                } else {
                    if (window.showToast) showToast('error', data.message || 'Checkout failed');
                    this.classList.remove('loading');
                    this.disabled = false;
                    document.getElementById('checkoutBtnText').textContent = originalText;
                }
            } catch (error) {
                if (window.showToast) showToast('error', 'Network error occurred');
                this.classList.remove('loading');
                this.disabled = false;
                document.getElementById('checkoutBtnText').textContent = originalText;
            }
        });

        // Initial calculation
        updateCalculations();
    </script>
</body>
</html>
