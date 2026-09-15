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
            background: #f5f5f5;
            color: #333;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .checkout-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .checkout-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .checkout-header-actions {
            display: flex;
            gap: 10px;
        }

        .header-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .header-btn-primary {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .header-btn-primary:hover {
            background: rgba(255,255,255,0.3);
        }

        .checkout-body {
            background: white;
            padding: 25px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 10px 20px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            transition: all 0.2s;
        }

        .action-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .items-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #f9fafb;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .customer-staff-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .customer-box, .staff-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .box-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }

        .summary-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-value {
            font-weight: 600;
            color: #1f2937;
        }

        .summary-total {
            font-size: 18px;
            color: #059669;
        }

        .payment-section {
            margin-bottom: 25px;
        }

        .payment-status-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }

        .payment-tab {
            padding: 10px 20px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .payment-tab.active {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #3b82f6;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .payment-method {
            padding: 12px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 13px;
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

        .tender-section {
            margin-bottom: 25px;
        }

        .tender-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
        }

        .tender-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .estimate-invoice {
            background: #fef3c7;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            border: 1px solid #fcd34d;
        }

        .estimate-header {
            text-align: center;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #fcd34d;
        }

        .estimate-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #78350f;
        }

        .net-sales {
            background: #ecfdf5;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #059669;
            margin-bottom: 25px;
        }

        .checkout-actions {
            display: flex;
            gap: 15px;
        }

        .checkout-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkout-btn-primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .checkout-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .checkout-btn-secondary {
            background: #3b82f6;
            color: white;
        }

        .checkout-btn-secondary:hover {
            background: #2563eb;
        }

        @media print {
            body * { visibility: hidden; }
            .print-area, .print-area * { visibility: visible; }
            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header no-print">
            <h1>Checkout - {{ $table->name ?? 'N/A' }}</h1>
            <div class="checkout-header-actions">
                <button class="header-btn header-btn-primary" onclick="toggleQuickMode()">Switch to Quick Mode</button>
                <button class="header-btn header-btn-primary" onclick="downloadInvoice()">Download</button>
                <button class="header-btn header-btn-primary" onclick="printEstimate()">Print Estimate</button>
            </div>
        </div>

        <!-- Body -->
        <div class="checkout-body">
            <!-- Action Bar -->
            <div class="action-bar no-print">
                <button class="action-btn">Split Bill</button>
                <button class="action-btn">Complimentary</button>
                <button class="action-btn">Add Extra Charges</button>
            </div>

            <!-- Items Table -->
            <div class="items-section">
                <div class="section-title">All Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Item</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>Discount</th>
                            <th>Currency</th>
                            <th>%</th>
                            <th>Item Total</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                            ];
                                        }
                                        $itemGroups[$key]['quantity'] += $item->quantity ?? 0;
                                    }
                                }
                            }
                            $sn = 1;
                        @endphp
                        @forelse($itemGroups as $item)
                            <tr>
                                <td>{{ $sn++ }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['unit_price'], 2) }}</td>
                                <td>0.00</td>
                                <td>Rs</td>
                                <td>0</td>
                                <td>{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:20px;">No items</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Customer & Staff -->
            <div class="customer-staff-section no-print">
                <div class="customer-box">
                    <div class="box-title">Customer</div>
                    <div style="color:#6b7280;font-size:13px;">Select customer to assign</div>
                </div>
                <div class="staff-box">
                    <div class="box-title">Staff</div>
                    <div style="color:#6b7280;font-size:13px;">{{ Auth::user()->name ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">Item total</span>
                    <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Sub Total</span>
                    <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Discount ()</span>
                    <span class="summary-value">0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">0.00</span>
                    <span class="summary-value">0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Taxable Amount</span>
                    <span class="summary-value">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">No Tax (</span>
                    <span class="summary-value">{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">)</span>
                    <span class="summary-value">0</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Amount</span>
                    <span class="summary-value summary-total">Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Round Off/Tips -->
            <div style="margin-bottom:25px;" class="no-print">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" id="roundOffTips">
                    <span>Add Round Off/Tips?</span>
                </label>
            </div>

            <!-- Tender Amount -->
            <div class="tender-section no-print">
                <div class="section-title">Tender Amount</div>
                <input type="number" class="tender-input" id="tenderAmount" value="{{ number_format($grandTotal ?? $subtotal ?? 0, 2, '.', '') }}" placeholder="0.00">
            </div>

            <!-- Payment Mode -->
            <div class="payment-section no-print">
                <div class="section-title">Payment Mode *</div>
                <div class="payment-status-tabs">
                    <button class="payment-tab active" data-status="paid" onclick="selectPaymentStatus(this, 'paid')">Paid</button>
                    <button class="payment-tab" data-status="unpaid" onclick="selectPaymentStatus(this, 'unpaid')">Unpaid / Credit</button>
                    <button class="payment-tab" data-status="partial" onclick="selectPaymentStatus(this, 'partial')">Partial</button>
                </div>
                <div class="payment-methods">
                    <button class="payment-method active" data-method="cash" onclick="selectPaymentMethod(this, 'cash')">Ca<br>Cash</button>
                    <button class="payment-method" data-method="nepal_pay" onclick="selectPaymentMethod(this, 'nepal_pay')">NP<br>Nepal Pay</button>
                    <button class="payment-method" data-method="card" onclick="selectPaymentMethod(this, 'card')">Ca<br>Card</button>
                    <button class="payment-method" data-method="fonepay" onclick="selectPaymentMethod(this, 'fonepay')">Fo<br>Fonepay</button>
                    <button class="payment-method" data-method="bank_transfer" onclick="selectPaymentMethod(this, 'bank_transfer')">BT<br>Bank Transfer</button>
                </div>
            </div>

            <!-- Estimate Invoice -->
            <div class="estimate-invoice print-area">
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
                <div style="margin:15px 0;padding:10px 0;border-top:1px solid #fcd34d;border-bottom:1px solid #fcd34d;">
                    <div style="font-weight:600;margin-bottom:10px;">Particular</div>
                    @forelse($itemGroups as $item)
                        <div class="estimate-row">
                            <span>{{ $item['name'] }}</span>
                            <span>{{ number_format($item['unit_price'], 2) }}</span>
                        </div>
                        <div class="estimate-row">
                            <span>{{ $item['quantity'] }}</span>
                            <span>{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforelse
                </div>
                <div class="estimate-row">
                    <span>Total (Particular/QTY)</span>
                    <span>{{ count($itemGroups ?? []) }}/3</span>
                </div>
                <div class="estimate-row">
                    <span>Rs</span>
                    <span>{{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}</span>
                </div>
                <div class="estimate-row">
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
                <div style="text-align:center;margin-top:15px;font-weight:600;color:#92400e;">
                    This is not a Tax Invoice!<br>
                    Kindly accept the original bill from the counter.
                </div>
                <div style="text-align:center;margin-top:15px;">
                    Thank You<br>
                    Thank you for your visit! Visit again
                </div>
            </div>

            <!-- Net Sales -->
            <div class="net-sales no-print">
                Net sales amount<br>
                Rs {{ number_format($grandTotal ?? $subtotal ?? 0, 2) }}
            </div>

            <!-- Checkout Actions -->
            <div class="checkout-actions no-print">
                <button class="checkout-btn checkout-btn-secondary" onclick="printEstimate()">Confirm & Print</button>
                <button class="checkout-btn checkout-btn-primary" onclick="completeCheckout()">Confirm Checkout</button>
            </div>
        </div>
    </div>

    @include('admin.includes.bottom')

    <script>
        let selectedPaymentStatus = 'paid';
        let selectedPaymentMethod = 'cash';

        function toggleQuickMode() {
            // Toggle quick mode functionality
            console.log('Toggle Quick Mode');
        }

        function downloadInvoice() {
            window.print();
        }

        function printEstimate() {
            const invoiceEl = document.querySelector('.estimate-invoice');
            if (!invoiceEl) {
                window.print();
                return;
            }

            const originalContents = document.body.innerHTML;
            const printContents = invoiceEl.cloneNode(true);
            printContents.classList.add('print-area');
            printContents.style.width = '100%';
            printContents.style.maxWidth = '100%';

            document.body.innerHTML = '';
            document.body.appendChild(printContents);
            document.body.style.margin = '0';
            document.body.style.background = '#ffffff';

            window.print();

            document.body.innerHTML = originalContents;
            window.location.reload();
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

        function showCheckoutSuccessPopup(data = {}) {
            if (typeof showFinalBillModal === 'function') {
                const payload = {
                    items: window.currentCheckoutItems || [],
                    subtotal: Number(data.subtotal || 0),
                    service_charge_amount: Number(data.service_charge_amount || 0),
                    vat_amount: Number(data.vat_amount || 0),
                    grand_total: Number(data.total_amount || data.grand_total || 0),
                    table: { name: (window.currentTable && window.currentTable.name) || '{{ $table->name ?? "Table" }}' },
                    logo_url: data.logo_url || '',
                    site_name: data.restaurant_name || document.querySelector('meta[name="restaurant-name"]')?.content || 'Restaurant',
                    address: data.restaurant_address || document.querySelector('meta[name="restaurant-address"]')?.content || '',
                    contact_phone: data.restaurant_phone || document.querySelector('meta[name="restaurant-phone"]')?.content || '',
                    orders: []
                };
                showFinalBillModal(payload, data);
                return;
            }

            const successOverlay = document.getElementById('successModalOverlay');
            if (successOverlay) {
                successOverlay.classList.add('show');
                return;
            }

            window.location.href = '/admin/orders/pos';
        }

        async function completeCheckout() {
            const tenderAmount = parseFloat(document.getElementById('tenderAmount').value) || 0;
            const totalAmount = {{ $grandTotal ?? $subtotal ?? 0 }};

            if (tenderAmount < totalAmount && selectedPaymentStatus === 'paid') {
                alert('Amount received cannot be less than total amount');
                return;
            }

            try {
                const url = '/admin/orders/table/{{ $table->id ?? $order->id }}/checkout';
                
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
                        payment_status: selectedPaymentStatus
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    showCheckoutSuccessPopup(data);
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
