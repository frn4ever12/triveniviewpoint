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
            gap: 10px;
            align-items: flex-start;
            flex: 1;
            min-height: 0;
        }

        .checkout-main {
            flex: 0 0 70%;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .checkout-invoice-panel {
            flex: 0 0 30%;
            background: #fef3c7;
            border-radius: 4px;
            padding: 12px;
            border: 1px solid #fcd34d;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: sticky;
            top: 10px;
            max-height: 100%;
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
            background: #f9fafb;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
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
            gap: 4px;
        }

        .payment-method {
            padding: 6px 4px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            font-size: 10px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .payment-method:hover {
            border-color: #3b82f6;
        }

        .payment-method.active {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #3b82f6;
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
                <!-- Action Bar -->
                <div class="action-bar no-print">
                    <button class="action-btn">Split Bill</button>
                    <button class="action-btn">Complimentary</button>
                    <button class="action-btn">Add Extra Charges</button>
                </div>

                <!-- Items Table -->
                <div class="card">
                    <div class="card-title">All Items</div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Item</th>
                                <th>Size</th>
                                <th>QTY</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Item Total</th>
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
                                    <td>{{ $item['name'] }}</td>
                                    <td>
                                        <div class="size-selector-inline">
                                            <button class="size-btn-inline {{ $item['size'] == 0.5 ? 'active' : '' }}" onclick="changeItemSize('{{ $key }}', 0.5)">Half</button>
                                            <button class="size-btn-inline {{ $item['size'] == 1 ? 'active' : '' }}" onclick="changeItemSize('{{ $key }}', 1)">Full</button>
                                        </div>
                                    </td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td class="item-rate">Rs {{ number_format($item['unit_price'], 2) }}</td>
                                    <td>0.00</td>
                                    <td class="item-total">Rs {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:20px;">No items</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Customer / Staff + Summary -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <!-- Customer / Staff -->
                    <div class="card no-print">
                        <div class="tabs">
                            <button class="tab active" onclick="switchTab(this, 'customer')">Customer</button>
                            <button class="tab" onclick="switchTab(this, 'staff')">Staff</button>
                        </div>
                        <div id="customer-tab">
                            <input type="text" class="form-input" placeholder="Search or select customer...">
                        </div>
                        <div id="staff-tab" style="display:none;">
                            <div style="color:#6b7280;font-size:11px;">{{ Auth::user()->name ?? 'N/A' }}</div>
                        </div>
                        <!-- Remarks -->
                        <div style="margin-top: 8px;">
                            <textarea class="form-input" rows="2" placeholder="Add remarks to invoice" style="resize:vertical;"></textarea>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="card">
                        <div class="card-title">Totals</div>
                        <div class="summary-row">
                            <span class="summary-label">Item Total</span>
                            <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Sub Total</span>
                            <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Discount (-)</span>
                            <span class="summary-value">0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Taxable Amount</span>
                            <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">+ No Tax</span>
                            <span class="summary-value">0</span>
                        </div>
                        <div class="summary-row" style="margin-top: 4px; padding-top: 6px;">
                            <span class="summary-label" style="font-weight: 600;">Total Amount</span>
                            <span class="summary-value summary-total">Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tender Amount + Payment Mode -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <!-- Tender Amount -->
                    <div class="card no-print">
                        <div class="card-title">Tender Amount</div>
                        <input type="number" class="form-input" id="tenderAmount" value="{{ number_format($grandTotal ?? $subtotal ?? 0, 2, '.', '') }}" placeholder="0.00" style="font-size: 14px; font-weight: 600;">
                    </div>

                    <!-- Payment Mode -->
                    <div class="card no-print">
                        <div class="card-title">Payment Mode *</div>
                        <div class="payment-tabs">
                            <button class="payment-tab active" data-status="paid" onclick="selectPaymentStatus(this, 'paid')">Paid</button>
                            <button class="payment-tab" data-status="unpaid" onclick="selectPaymentStatus(this, 'unpaid')">Unpaid / Credit</button>
                            <button class="payment-tab" data-status="partial" onclick="selectPaymentStatus(this, 'partial')">Partial</button>
                        </div>
                        <div class="payment-methods">
                            <button class="payment-method active" data-method="cash" onclick="selectPaymentMethod(this, 'cash')">Cash</button>
                            <button class="payment-method" data-method="nepal_pay" onclick="selectPaymentMethod(this, 'nepal_pay')">Nepal Pay</button>
                            <button class="payment-method" data-method="card" onclick="selectPaymentMethod(this, 'card')">Card</button>
                            <button class="payment-method" data-method="fonepay" onclick="selectPaymentMethod(this, 'fonepay')">Fonepay</button>
                            <button class="payment-method" data-method="bank_transfer" onclick="selectPaymentMethod(this, 'bank_transfer')">Bank Transfer</button>
                        </div>
                    </div>
                </div>

                <!-- Net Sales -->
                <div class="net-sales no-print">
                    <div class="net-sales-label">Net sales amount</div>
                    <div class="net-sales-amount">Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</div>
                </div>

                <!-- Checkout Actions -->
                <div class="checkout-actions no-print">
                    <button class="checkout-btn checkout-btn-secondary" onclick="printEstimate()">Confirm & Print</button>
                    <button class="checkout-btn checkout-btn-primary" onclick="completeCheckout()">Confirm Checkout</button>
                </div>
            </div>

            <!-- Right Invoice Panel -->
            <div class="checkout-invoice-panel print-area">
                <div class="estimate-header">ESTIMATE INVOICE</div>
                <div class="estimate-row">
                    <span>Invoice No:</span>
                    <span>##</span>
                </div>
                <div class="estimate-row">
                    <span>Date:</span>
                    <span>{{ now()->format('M d, Y') }}</span>
                </div>
                <div class="estimate-row">
                    <span>Dine In:</span>
                    <span>{{ $table->name ?? 'N/A' }}</span>
                </div>
                <div class="estimate-row">
                    <span>Customer:</span>
                    <span>Cash Customer</span>
                </div>
                <div class="estimate-particular">
                    <div class="estimate-particular-title">Particular</div>
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
                    @endphp
                    @forelse($estimateGrouped as $item)
                        <div class="estimate-row">
                            <span>{{ $item['name'] }}</span>
                            <span>{{ number_format($item['unit_price'], 2) }}</span>
                        </div>
                        <div class="estimate-row">
                            <span>{{ $item['quantity'] }}</span>
                            <span>{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @empty
                        <div class="estimate-row">
                            <span>No items</span>
                        </div>
                    @endforelse
                </div>
                <div class="estimate-row">
                    <span>Total (Particular/QTY)</span>
                    <span>{{ count($estimateGrouped ?? []) }}/3</span>
                </div>
                <div class="estimate-row">
                    <span>Rs</span>
                    <span>{{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                </div>
                <div class="estimate-row">
                    <span>Amount in words:</span>
                    <span>Two Hundred Sixty Five Nepalese Rupee Only</span>
                </div>
                <div class="estimate-row">
                    <span>Payment Mode:</span>
                    <span>Unpaid (Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }})</span>
                </div>
                <div class="estimate-row">
                    <span>KOT No: 1 (by {{ Auth::user()->name ?? 'john doe' }})</span>
                </div>
                <div class="estimate-row">
                    <span>Billed By: {{ Auth::user()->name ?? 'john doe' }}</span>
                </div>
                <div class="estimate-row">
                    <span>Service Duration: 4 days 21 hrs 15 mins</span>
                </div>
                <div style="text-align:center;margin-top:12px;font-weight:600;color:#92400e;font-size:11px;">
                    This is not a Tax Invoice!<br>
                    Kindly accept the original bill from the counter.
                </div>
                <div style="text-align:center;margin-top:12px;color:#78350f;font-size:11px;">
                    Thank You<br>
                    Thank you for your visit! Visit again
                </div>
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
