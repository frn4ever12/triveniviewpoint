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
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .checkout-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .checkout-header {
            background: white;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        .checkout-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .checkout-header-actions {
            display: flex;
            gap: 6px;
            align-items: center;
            .size-selector-inline {
                display: flex;
                gap: 4px;
            }
            .size-btn-inline {
                padding: 8px 4px;
                border: 1px solid #e5e7eb;
                background: #f9fafb;
                border-radius: 4px;
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
        }

        .header-btn {
            padding: 4px 10px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s;
            color: #374151;
        }

        .header-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .header-btn-primary {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .header-btn-primary:hover {
            background: #2563eb;
        }

        .checkout-body {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            flex: 1;
            min-height: 0;
        }

        .checkout-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkout-invoice-panel {
            width: 380px;
            flex-shrink: 0;
            background: white;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #E0E0E0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 10px;
            max-height: calc(100vh - 20px);
            overflow-y: auto;
        }

        .action-bar {
            display: flex;
            gap: 6px;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 4px 10px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .card {
            background: white;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #F4F6F8;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #1E293B;
            border-bottom: 1px solid #E0E0E0;
            font-size: 12px;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #E0E0E0;
            font-size: 13px;
            color: #1E293B;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .size-selector-inline {
            display: flex;
            gap: 4px;
        }
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

        .tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .tab {
            padding: 4px 10px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab:hover {
            color: #374151;
        }

        .tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .form-input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 12px;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #6b7280;
            font-size: 11px;
        }

        .summary-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 11px;
        }

        .summary-total {
            font-size: 14px;
            color: #059669;
        }

        .payment-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 8px;
        }

        .payment-tab {
            padding: 4px 10px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .payment-tab.active {
            border-color: #059669;
            background: #ecfdf5;
            color: #059669;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .payment-method {
            padding: 8px 4px;
            border: 1px solid #E0E0E0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .payment-method:hover {
            border-color: #1A73E8;
        }

        .payment-method.active {
            border-color: #1A73E8;
            background: #E0F2FE;
            color: #1A73E8;
            font-weight: 600;
        }

        .net-sales {
            background: #ecfdf5;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 8px;
        }

        .net-sales-label {
            color: #059669;
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .net-sales-amount {
            color: #059669;
            font-weight: 700;
            font-size: 14px;
        }

        .checkout-actions {
            display: flex;
            gap: 6px;
        }

        .checkout-btn {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkout-btn-primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .checkout-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }

        .checkout-btn-secondary {
            background: #9ca3af;
            color: white;
        }

        .checkout-btn-secondary:hover {
            background: #6b7280;
        }

        .estimate-header {
            text-align: center;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #fcd34d;
            font-size: 14px;
        }

        .estimate-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            color: #78350f;
            font-size: 12px;
        }

        .estimate-particular {
            margin: 10px 0;
            padding: 8px 0;
            border-top: 1px solid #fcd34d;
            border-bottom: 1px solid #fcd34d;
        }

        .estimate-particular-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #78350f;
            font-size: 12px;
        }

        /* Success Modal */
        .success-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            backdrop-filter: blur(4px);
        }

        .success-modal-overlay.show {
            display: flex;
        }

        .success-modal {
            background: white;
            border-radius: 8px;
            max-width: 450px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }

        .success-modal-header {
            background: #059669;
            color: white;
            padding: 12px 16px;
            text-align: center;
            font-weight: 700;
            font-size: 16px;
            border-radius: 8px 8px 0 0;
        }

        .success-modal-body {
            padding: 16px;
        }

        .restaurant-header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .restaurant-address {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .restaurant-phone {
            font-size: 12px;
            color: #6b7280;
        }

        .success-invoice-panel {
            background: #fef3c7;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #fcd34d;
        }

        .success-invoice-header {
            text-align: center;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #fcd34d;
            font-size: 12px;
        }

        .success-invoice-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            color: #78350f;
            font-size: 10px;
        }

        .success-invoice-particular {
            margin: 6px 0;
            padding: 4px 0;
            border-top: 1px solid #fcd34d;
            border-bottom: 1px solid #fcd34d;
        }

        .success-invoice-particular-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #78350f;
            font-size: 10px;
        }

        .success-modal-footer {
            padding: 12px 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 8px;
        }

        .success-modal-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .success-modal-btn-download {
            background: #3b82f6;
            color: white;
        }

        .success-modal-btn-download:hover {
            background: #2563eb;
        }

        .success-modal-btn-print {
            background: #059669;
            color: white;
        }

        .success-modal-btn-print:hover {
            background: #047857;
        }

        .success-modal-btn-close {
            background: #6b7280;
            color: white;
        }

        .success-modal-btn-close:hover {
            background: #4b5563;
        }

        @media print {
            body * { visibility: hidden; }
            .checkout-invoice-panel, .checkout-invoice-panel * { visibility: visible; }
            .checkout-invoice-panel {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
            }
            .no-print { display: none !important; }
        }

        @media (max-width: 1024px) {
            .checkout-body {
                flex-direction: column;
            }
            .checkout-invoice-panel {
                width: 100%;
                position: static;
            }
            .payment-methods {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .checkout-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .checkout-header-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header no-print">
            <h1>Checkout - {{ $table->name ?? 'N/A' }}</h1>
            <div class="checkout-header-actions">
                <button class="header-btn" onclick="toggleQuickMode()">Switch to Quick Mode</button>
                <button class="header-btn" onclick="downloadInvoice()">Download</button>
                <button class="header-btn header-btn-primary" onclick="printEstimate()">Print Estimate</button>
                <a href="/admin/orders/pos" class="header-btn">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="checkout-body">
            <!-- Main Content -->
            <div class="checkout-main">
                <!-- Order Header -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div>
                            <h2 style="font-size: 18px; font-weight: 700; color: #1E293B; margin: 0;">Checkout</h2>
                            <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">Complete the order and collect payment</p>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span style="background: #E0F2FE; color: #0369A1; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Order #1045</span>
                            <span style="background: #DCFCE7; color: #166534; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Table {{ $table->name ?? '5' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="card">
                    <div class="card-title">Order Items</div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Item</th>
                                <th width="80">Rate</th>
                                <th width="80">Qty</th>
                                <th width="80">Discount (%)</th>
                                <th width="80">Charge (₹)</th>
                                <th width="50">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grouped = [];
                                if (isset($orders)) {
                                    foreach ($orders as $ord) {
                                        foreach ($ord->items as $item) {
                                            $menuItem = $item->menuItem ?? null;
                                            $key = $item->menu_item_id . '-' . ($item->size ?? 1);
                                            if (!isset($grouped[$key])) {
                                                $grouped[$key] = [
                                                    'name' => $menuItem->name ?? 'Item',
                                                    'quantity' => 0,
                                                    'unit_price' => $item->unit_price ?? 0,
                                                    'size' => $item->size ?? 1,
                                                    'base_price' => ($item->unit_price ?? 0) / ($item->size ?? 1),
                                                    'menu_item_id' => $item->menu_item_id,
                                                    'image' => $menuItem->getFirstMediaUrl('image') ?? asset('assets/images/defaultfood.png'),
                                                ];
                                            }
                                            $grouped[$key]['quantity'] += $item->quantity ?? 0;
                                        }
                                    }
                                }
                                $sn = 1;
                            @endphp
                            @forelse($grouped as $key => $item)
                                <tr data-item-key="{{ $key }}" data-base-price="{{ $item['base_price'] }}">
                                    <td>{{ $sn++ }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid #E0E0E0;">
                                            <div>
                                                <div style="font-weight: 600; color: #1E293B;">{{ $item['name'] }}</div>
                                                <div style="font-size: 11px; color: #64748B;">{{ $item['size'] == 0.5 ? 'Half' : 'Full' }} portion</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rs {{ number_format($item['unit_price'], 2) }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 4px;">
                                            <button onclick="updateQuantity('{{ $key }}', -1)" style="width: 28px; height: 28px; border: 1px solid #E0E0E0; background: white; border-radius: 4px; cursor: pointer; font-size: 16px; color: #1E293B;">-</button>
                                            <span style="font-weight: 600; min-width: 30px; text-align: center;">{{ $item['quantity'] }}</span>
                                            <button onclick="updateQuantity('{{ $key }}', 1)" style="width: 28px; height: 28px; border: 1px solid #E0E0E0; background: white; border-radius: 4px; cursor: pointer; font-size: 16px; color: #1E293B;">+</button>
                                        </div>
                                    </td>
                                    <td><input type="number" value="0" min="0" max="100" style="width: 60px; padding: 4px 8px; border: 1px solid #E0E0E0; border-radius: 4px; font-size: 12px;"></td>
                                    <td>Rs {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <button onclick="deleteItem('{{ $key }}')" style="width: 32px; height: 32px; border: none; background: #FEE2E2; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:30px;color:#64748B;">No items in order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Special Instructions -->
                <div class="card">
                    <div class="card-title">Special Instructions</div>
                    <textarea class="form-input" rows="2" placeholder="Add special instructions (e.g., Less spicy, no onion...)" style="resize:vertical;"></textarea>
                </div>

                <!-- Extra Charge & Complimentary -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="card">
                        <div class="card-title" style="font-size: 13px;">Extra Charge</div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" class="form-input" placeholder="Packaging Charge" style="flex: 1;">
                            <input type="number" class="form-input" placeholder="Amount" style="width: 80px;">
                            <button style="padding: 6px 12px; background: #1A73E8; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;">+ Add</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-title" style="font-size: 13px;">Complimentary</div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" class="form-input" placeholder="Item name" style="flex: 1;">
                            <input type="number" class="form-input" placeholder="Amount" style="width: 80px;">
                            <button style="padding: 6px 12px; background: #1A73E8; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;">+ Add</button>
                        </div>
                    </div>
                </div>

                <!-- Summary Bar -->
                <div class="card" style="background: #F4F6F8; border: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div>
                                <span style="font-size: 12px; color: #64748B;">Item Total:</span>
                                <span style="font-weight: 600; color: #1E293B; margin-left: 4px;">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #DC2626;">Discount:</span>
                                <span style="font-weight: 600; color: #DC2626; margin-left: 4px;">-Rs 0.00</span>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748B;">Extra Charge:</span>
                                <span style="font-weight: 600; color: #059669; margin-left: 4px;">+Rs 0.00</span>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748B;">Complimentary:</span>
                                <span style="font-weight: 600; color: #DC2626; margin-left: 4px;">-Rs 0.00</span>
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 13px; color: #64748B;">Grand Total:</span>
                            <span style="font-size: 18px; font-weight: 700; color: #1A73E8; margin-left: 8px;">Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Selection -->
                <div class="card">
                    <div class="card-title">Customer</div>
                    <div style="position: relative;">
                        <div id="customerDropdown" style="width: 100%; padding: 10px 12px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 13px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: white;" onclick="toggleCustomerDropdown()">
                            <span id="selectedCustomer" style="font-weight: 500; color: #1E293B;">Walk-in Customer</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </div>
                        <div id="customerDropdownMenu" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #E0E0E0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 10; display: none; max-height: 250px; overflow-y: auto; margin-top: 4px;">
                            <div style="padding: 8px 12px; border-bottom: 1px solid #E0E0E0;">
                                <input type="text" placeholder="Search or select customer..." style="width: 100%; padding: 8px; border: 1px solid #E0E0E0; border-radius: 6px; font-size: 12px;">
                            </div>
                            <div style="padding: 8px 12px; cursor: pointer; font-size: 13px; color: #1E293B; display: flex; align-items: center; gap: 8px;" onclick="selectCustomer('Walk-in Customer')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span>Walk-in Customer</span>
                                <span style="font-size: 11px; color: #64748B; margin-left: auto;">Auto</span>
                            </div>
                            <div style="padding: 8px 12px; cursor: pointer; font-size: 13px; color: #1E293B; border-top: 1px solid #F4F6F8;" onclick="selectCustomer('Ram Bahadur')">
                                <span>Ram Bahadur</span>
                                <span style="font-size: 11px; color: #64748B; margin-left: auto;">9841234567</span>
                            </div>
                            <div style="padding: 8px 12px; cursor: pointer; font-size: 13px; color: #1E293B; border-top: 1px solid #F4F6F8;" onclick="selectCustomer('Sita Devi')">
                                <span>Sita Devi</span>
                                <span style="font-size: 11px; color: #64748B; margin-left: auto;">9847654321</span>
                            </div>
                            <div style="padding: 12px; cursor: pointer; font-size: 13px; color: #1A73E8; font-weight: 600; border-top: 1px solid #E0E0E0; display: flex; align-items: center; gap: 8px; background: #F4F6F8;" onclick="openAddCustomerModal()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1A73E8" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>+ Add New Customer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card">
                    <div class="card-title">Payment Method</div>
                    <div class="payment-methods">
                        <button class="payment-method active" data-method="cash" onclick="selectPaymentMethod(this, 'cash')">Cash</button>
                        <button class="payment-method" data-method="nepal_pay" onclick="selectPaymentMethod(this, 'nepal_pay')">Nepal Pay</button>
                        <button class="payment-method" data-method="card" onclick="selectPaymentMethod(this, 'card')">Card</button>
                        <button class="payment-method" data-method="fonepay" onclick="selectPaymentMethod(this, 'fonepay')">Fonepay</button>
                        <button class="payment-method" data-method="bank_transfer" onclick="selectPaymentMethod(this, 'bank_transfer')">Bank Transfer</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px; margin-top: auto;">
                    <button class="checkout-btn" style="flex: 1; padding: 14px; border: 2px solid #E0E0E0; background: white; border-radius: 8px; font-size: 14px; font-weight: 600; color: #64748B; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#F4F6F8'" onmouseout="this.style.background='white'">Cancel</button>
                    <button class="checkout-btn" style="flex: 2; padding: 14px; border: none; background: linear-gradient(135deg, #1A73E8 0%, #0066FF 100%); border-radius: 8px; font-size: 14px; font-weight: 700; color: white; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Confirm & Complete Order</button>
                </div>
            </div>

            <!-- Right Invoice Panel -->
            <div class="checkout-invoice-panel print-area">
                <!-- Invoice Header -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #E0E0E0;">
                    <div style="flex: 1;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1E293B; margin: 0 0 8px 0;">Checkout Summary</h3>
                        <div style="font-size: 11px; color: #64748B; margin-bottom: 4px;">Invoice No: #1045</div>
                        <div style="font-size: 11px; color: #64748B; margin-bottom: 4px;">Date: {{ now()->format('M d, Y') }}</div>
                        <div style="font-size: 11px; color: #64748B; margin-bottom: 4px;">Time: {{ now()->format('h:i A') }}</div>
                        <div style="font-size: 11px; color: #64748B; margin-bottom: 4px;">Table: {{ $table->name ?? '5' }} Dine In</div>
                        <div style="font-size: 11px; color: #64748B;">Customer: <span id="invoiceCustomer">Walk-in Customer</span></div>
                    </div>
                    <div style="text-align: center; margin-left: 8px;">
                        <div style="width: 50px; height: 50px; background: #fef9c3; border: 1px solid #fcd34d; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M8 8h8M8 12h8M8 16h4"/>
                            </svg>
                        </div>
                        <div style="font-size: 7px; color: #78350f; max-width: 50px;">Scan for full bill details & pay</div>
                    </div>
                </div>

                <!-- Invoice Items Table -->
                <div style="margin-bottom: 12px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr style="background: #F4F6F8; border-bottom: 1px solid #E0E0E0;">
                                <th style="padding: 6px 8px; text-align: left; font-weight: 600; color: #1E293B;">S.N</th>
                                <th style="padding: 6px 8px; text-align: left; font-weight: 600; color: #1E293B;">Item</th>
                                <th style="padding: 6px 8px; text-align: right; font-weight: 600; color: #1E293B;">Rate</th>
                                <th style="padding: 6px 8px; text-align: right; font-weight: 600; color: #1E293B;">Qty</th>
                                <th style="padding: 6px 8px; text-align: right; font-weight: 600; color: #1E293B;">Disc%</th>
                                <th style="padding: 6px 8px; text-align: right; font-weight: 600; color: #1E293B;">Charge</th>
                                <th style="padding: 6px 8px; text-align: right; font-weight: 600; color: #1E293B;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $estimateGrouped = [];
                                if (isset($orders)) {
                                    foreach ($orders as $ord) {
                                        foreach ($ord->items as $item) {
                                            $menuItem = $item->menuItem ?? null;
                                            $key = $item->menu_item_id . '-' . ($item->size ?? 1);
                                            if (!isset($estimateGrouped[$key])) {
                                                $estimateGrouped[$key] = [
                                                    'name' => $menuItem->name ?? 'Item',
                                                    'quantity' => 0,
                                                    'unit_price' => $item->unit_price ?? 0,
                                                    'size' => $item->size ?? 1,
                                                ];
                                            }
                                            $estimateGrouped[$key]['quantity'] += $item->quantity ?? 0;
                                        }
                                    }
                                }
                                $sn = 1;
                            @endphp
                            @forelse($estimateGrouped as $item)
                                <tr style="border-bottom: 1px solid #F4F6F8;">
                                    <td style="padding: 6px 8px; color: #1E293B;">{{ $sn++ }}</td>
                                    <td style="padding: 6px 8px; color: #1E293B;">{{ $item['name'] }}</td>
                                    <td style="padding: 6px 8px; text-align: right; color: #1E293B;">{{ number_format($item['unit_price'], 2) }}</td>
                                    <td style="padding: 6px 8px; text-align: right; color: #1E293B;">{{ $item['quantity'] }}</td>
                                    <td style="padding: 6px 8px; text-align: right; color: #1E293B;">0</td>
                                    <td style="padding: 6px 8px; text-align: right; color: #1E293B;">{{ number_format($item['unit_price'], 2) }}</td>
                                    <td style="padding: 6px 8px; text-align: right; color: #1E293B; font-weight: 600;">{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:20px;color:#64748B;">No items</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Calculation Footer -->
                <div style="border-top: 1px solid #E0E0E0; padding-top: 12px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #64748B;">
                        <span>Item Total</span>
                        <span>Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #DC2626;">
                        <span>Item Discount</span>
                        <span>-Rs 0.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #64748B;">
                        <span>Extra Charges</span>
                        <span>+Rs 0.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 8px; padding-top: 8px; border-top: 1px solid #E0E0E0;">
                        <span style="font-size: 13px; font-weight: 700; color: #1E293B;">Grand Total</span>
                        <span style="font-size: 16px; font-weight: 700; color: #1A73E8;">Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                    </div>
                </div>

                <!-- Payment Calculation Fields -->
                <div style="border-top: 1px solid #E0E0E0; padding-top: 12px;">
                    <div style="margin-bottom: 8px;">
                        <label style="font-size: 11px; color: #1E293B; font-weight: 600; display: block; margin-bottom: 4px;">Amount Received</label>
                        <input type="number" id="invoiceAmountReceived" value="{{ number_format($grandTotal ?? $subtotal ?? 0, 2, '.', '') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #E0E0E0; border-radius: 6px; font-size: 13px; font-weight: 600;" placeholder="0.00" oninput="calculateChangeDue()">
                    </div>
                    <div>
                        <label style="font-size: 11px; color: #1E293B; font-weight: 600; display: block; margin-bottom: 4px;">Change Due</label>
                        <div id="invoiceChangeDue" style="width: 100%; padding: 8px 12px; background: #F4F6F8; border: 1px solid #E0E0E0; border-radius: 6px; font-size: 14px; font-weight: 700; color: #059669;">Rs 0.00</div>
                    </div>
                </div>

                <!-- Footer Note -->
                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #E0E0E0; text-align: center;">
                    <div style="font-size: 10px; color: #64748B; margin-bottom: 4px;">Thank you for your visit!</div>
                    <div style="font-size: 10px; color: #64748B;">DMC Restro - Good Food Good Mood</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="success-modal-overlay" id="addCustomerModalOverlay">
        <div class="success-modal">
            <div class="success-modal-header" style="background: #1A73E8;">Add New Customer</div>
            <div class="success-modal-body">
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 11px; color: #374151; font-weight: 600; display: block; margin-bottom: 4px;">Full Name *</label>
                    <input type="text" id="newCustomerName" style="width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 12px;" placeholder="Enter full name">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 11px; color: #374151; font-weight: 600; display: block; margin-bottom: 4px;">Phone Number *</label>
                    <input type="tel" id="newCustomerPhone" style="width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 12px;" placeholder="Enter phone number">
                </div>
                <div style="margin-bottom: 0;">
                    <label style="font-size: 11px; color: #374151; font-weight: 600; display: block; margin-bottom: 4px;">Address (optional)</label>
                    <textarea id="newCustomerAddress" style="width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 4px; resize: vertical; font-size: 12px;" rows="3" placeholder="Enter address"></textarea>
                </div>
            </div>
            <div class="success-modal-footer">
                <button class="success-modal-btn success-modal-btn-close" onclick="closeAddCustomerModal()">Cancel</button>
                <button class="success-modal-btn success-modal-btn-download" onclick="saveNewCustomer()">Add Customer</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal-overlay" id="successModalOverlay">
        <div class="success-modal">
            <div class="success-modal-header">Successful Checkout</div>
            <div class="success-modal-body">
                <!-- Restaurant Header -->
                <div class="restaurant-header">
                    <div class="restaurant-name">{{ $siteName ?? 'Restaurant Name' }}</div>
                    <div class="restaurant-address">{{ $address ?? 'Address' }}</div>
                    <div class="restaurant-phone">{{ $contactPhone ?? 'Phone' }}</div>
                </div>

                <!-- Invoice Panel -->
                <div class="success-invoice-panel">
                    <div class="success-invoice-header">ESTIMATE INVOICE</div>
                    <div class="success-invoice-row">
                        <span>Invoice No:</span>
                        <span>INV-1</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Date:</span>
                        <span>{{ now()->format('M d, Y') }}</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Dine In:</span>
                        <span>{{ $table->name ?? 'N/A' }}</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Customer:</span>
                        <span>Cash Customer</span>
                    </div>
                    <div class="success-invoice-particular">
                        <div class="success-invoice-particular-title">Particular</div>
                        @php
                            $successGrouped = [];
                            if (isset($orders)) {
                                foreach ($orders as $ord) {
                                    foreach ($ord->items as $item) {
                                        $menuItem = $item->menuItem ?? null;
                                        $key = $item->menu_item_id . '-' . ($item->size ?? 1);
                                        if (!isset($successGrouped[$key])) {
                                            $successGrouped[$key] = [
                                                'name' => $menuItem->name ?? 'Item',
                                                'quantity' => 0,
                                                'unit_price' => $item->unit_price ?? 0,
                                            ];
                                        }
                                        $successGrouped[$key]['quantity'] += $item->quantity ?? 0;
                                    }
                                }
                            }
                        @endphp
                        @forelse($successGrouped as $item)
                            <div class="success-invoice-row">
                                <span>{{ $item['name'] }}</span>
                                <span>{{ number_format($item['unit_price'], 2) }}</span>
                            </div>
                            <div class="success-invoice-row">
                                <span>{{ $item['quantity'] }}</span>
                                <span>{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @empty
                            <div class="success-invoice-row">
                                <span>No items</span>
                            </div>
                        @endforelse
                    </div>
                    <div class="success-invoice-row">
                        <span>Total (Particular/QTY)</span>
                        <span>{{ count($successGrouped ?? []) }}/3</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Rs</span>
                        <span>{{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Amount in words:</span>
                        <span>Two Hundred Sixty Five Nepalese Rupee Only</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Payment Mode:</span>
                        <span>Cash (Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }})</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>KOT No: 1 (by {{ Auth::user()->name ?? 'john doe' }})</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Billed By: {{ Auth::user()->name ?? 'john doe' }}</span>
                    </div>
                    <div class="success-invoice-row">
                        <span>Service Duration: 4 days 23 hrs 44 mins</span>
                    </div>
                    <div style="text-align:center;margin-top:8px;font-weight:600;color:#92400e;font-size:9px;">
                        This is not a Tax Invoice!<br>
                        This bill is an internal working copy. For official purposes, kindly accept the original bill from the counter, as this bill is for estimate purposes only.
                    </div>
                    <div style="text-align:center;margin-top:8px;color:#78350f;font-size:9px;">
                        Thank You<br>
                        Thank you for your visit! Visit again
                    </div>
                </div>

                <!-- Restaurant Footer -->
                <div class="restaurant-header" style="margin-top: 12px; margin-bottom: 0; padding-top: 12px; padding-bottom: 0; border-top: 2px solid #e5e7eb; border-bottom: none;">
                    <div class="restaurant-name">{{ $siteName ?? 'Restaurant Name' }}</div>
                    <div class="restaurant-address">{{ $address ?? 'Address' }}</div>
                    <div class="restaurant-phone">{{ $contactPhone ?? 'Phone' }}</div>
                </div>
            </div>
            <div class="success-modal-footer">
                <button class="success-modal-btn success-modal-btn-download" onclick="downloadSuccessInvoice()">Download</button>
                <button class="success-modal-btn success-modal-btn-print" onclick="printSuccessInvoice()">Print Bill</button>
                <button class="success-modal-btn success-modal-btn-close" onclick="closeSuccessModal()">Close</button>
            </div>
        </div>
    </div>

    @include('admin.includes.bottom')

    <script>
        let selectedPaymentStatus = 'paid';
        let selectedPaymentMethod = 'cash';

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
            document.getElementById('addCustomerModalOverlay').classList.add('show');
        }
        
        // Close add customer modal
        function closeAddCustomerModal() {
            document.getElementById('addCustomerModalOverlay').classList.remove('show');
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
            newOption.style.cssText = 'padding: 6px 10px; cursor: pointer; font-size: 11px; color: #374151;';
            newOption.textContent = name;
            newOption.onclick = () => selectCustomer(name);
            menu.insertBefore(newOption, menu.lastElementChild);
            
            // Select the new customer
            selectCustomer(name);
            
            // Clear form and close modal
            document.getElementById('newCustomerName').value = '';
            document.getElementById('newCustomerPhone').value = '';
            document.getElementById('newCustomerAddress').value = '';
            closeAddCustomerModal();
        }
        
        // Calculate change due
        function calculateChangeDue() {
            const amountReceived = parseFloat(document.getElementById('invoiceAmountReceived').value) || 0;
            const grandTotal = parseFloat({{ $grandTotal ?? $subtotal ?? 0 }});
            const changeDue = Math.max(0, amountReceived - grandTotal);
            document.getElementById('invoiceChangeDue').textContent = 'Rs ' + changeDue.toFixed(2);
        }
        
        // Update quantity
        function updateQuantity(itemKey, change) {
            const row = document.querySelector(`tr[data-item-key="${itemKey}"]`);
            if (!row) return;
            
            const qtySpan = row.querySelector('td:nth-child(4) span');
            const currentQty = parseInt(qtySpan.textContent);
            const newQty = Math.max(1, currentQty + change);
            qtySpan.textContent = newQty;
            
            // Update charge
            const basePrice = parseFloat(row.dataset.basePrice);
            const chargeCell = row.querySelector('td:nth-child(6)');
            chargeCell.textContent = (basePrice * newQty).toFixed(2);
        }
        
        // Delete item
        function deleteItem(itemKey) {
            if (confirm('Remove this item from order?')) {
                const row = document.querySelector(`tr[data-item-key="${itemKey}"]`);
                if (row) {
                    row.remove();
                }
            }
        }

        function switchTab(btn, tabName) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            
            document.getElementById('customer-tab').style.display = tabName === 'customer' ? 'block' : 'none';
            document.getElementById('staff-tab').style.display = tabName === 'staff' ? 'block' : 'none';
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

        function selectPaymentStatus(btn, status) {
            document.querySelectorAll('.payment-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedPaymentStatus = status;
        }

        function selectPaymentMethod(btn, method) {
            document.querySelectorAll('.payment-method').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedPaymentMethod = method;
        }

        function changeItemSize(itemKey, newSize) {
            const row = document.querySelector(`tr[data-item-key="${itemKey}"]`);
            if (!row) return;

            const basePrice = parseFloat(row.dataset.basePrice);
            const quantity = parseInt(row.cells[3].textContent);

            // Update button states
            const buttons = row.querySelectorAll('.size-btn-inline');
            buttons.forEach(btn => btn.classList.remove('active'));
            if (newSize === 0.5) {
                buttons[0].classList.add('active');
            } else {
                buttons[1].classList.add('active');
            }

            // Calculate new price
            const newPrice = basePrice * newSize;
            const newTotal = newPrice * quantity;

            // Update rate and total cells
            row.querySelector('.item-rate').textContent = 'Rs ' + newPrice.toFixed(2);
            row.querySelector('.item-total').textContent = 'Rs ' + newTotal.toFixed(2);

            // Update the data attributes
            row.dataset.basePrice = basePrice;
        }

        function showSuccessModal() {
            document.getElementById('successModalOverlay').classList.add('show');
        }

        function closeSuccessModal() {
            document.getElementById('successModalOverlay').classList.remove('show');
            window.location.href = '/admin/orders/pos';
        }

        function downloadSuccessInvoice() {
            window.print();
        }

        function printSuccessInvoice() {
            window.print();
        }

        async function completeCheckout() {
            const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
            const totalAmount = {{ $grandTotal ?? $subtotal ?? 0 }};

            if (tenderAmount < totalAmount && selectedPaymentStatus === 'paid') {
                alert('Amount received cannot be less than total amount');
                return;
            }

            try {
                const url = '/admin/orders/table/{{ $table->id }}/checkout';
                
                const response = await fetch(url, {
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
                        subtotal: {{ $subtotal ?? 0 }},
                        service_charge_amount: 0,
                        vat_percent: 0,
                        vat_amount: 0,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    showSuccessModal();
                } else {
                    alert(data.message || 'Checkout failed');
                }
            } catch (error) {
                alert('Network error occurred');
            }
        }
    </script>
</body>
</html>
