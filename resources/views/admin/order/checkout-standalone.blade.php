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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/checkout.css') }}">
</head>
<body>

    <header class="ck-topbar no-print">
        <div class="d-flex align-items-center gap-2">
            <span class="ck-topbar-brand">{{ $siteName ?? 'Restaurant' }}</span>
            <span class="badge bg-light text-dark ms-2">Checkout</span>
        </div>
        <div class="ck-topbar-info">
            @if(isset($table))
                <span>Table: <strong>{{ $table->name }}</strong></span>
            @endif
            <span>Orders: <strong>{{ $orders->count() }}</strong></span>
            <span>{{ now()->format('M d, Y g:i A') }}</span>
            @if($orders->isNotEmpty() && $orders->first()->waiter)
                <span>Server: <strong>{{ $orders->first()->waiter->name }}</strong></span>
            @endif
        </div>
        <div class="ck-topbar-actions">
            <button class="ck-btn ck-btn-outline" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Bill
            </button>
            <a href="{{ isset($table) ? route('admin.orders.table.edit', $table->id) : route('admin.orders.index') }}" class="ck-btn ck-btn-outline">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </header>

    <main class="ck-main no-print">
        <div class="checkout-grid">

            <div class="ck-card">
                <div class="ck-card-header">
                    <span><i class="bi bi-receipt"></i> Order Summary</span>
                    <span class="text-muted" style="font-size:0.8rem;font-weight:400;">
                        Total Items: <strong>{{ $orders->sum(fn($o) => $o->items->sum('quantity')) }}</strong>
                    </span>
                </div>
                <div class="ck-card-body p-0">

                    @php
                        $grouped = [];
                        if (isset($orders)) {
                            foreach ($orders as $ord) {
                                foreach ($ord->items as $item) {
                                    $key = $item->dish_id . '-' . ($item->size ?? 1);
                                    if (!isset($grouped[$key])) {
                                        $grouped[$key] = [
                                            'name' => $item->dish->name ?? $item->menuItem->name ?? 'Item',
                                            'quantity' => 0,
                                            'unit_price' => $item->unit_price ?? 0,
                                            'size' => $item->size ?? 1,
                                        ];
                                    }
                                    $grouped[$key]['quantity'] += $item->quantity ?? 0;
                                }
                            }
                        }
                    @endphp

                    <div style="padding:0.75rem 1.25rem;">
                        @forelse($grouped as $item)
                            <div class="item-row">
                                <span class="item-qty">{{ $item['quantity'] }}x</span>
                                <span class="item-name">
                                    {{ $item['name'] }}
                                    @if($item['size'] == 0.5)
                                        <small class="text-muted">(Half)</small>
                                    @elseif($item['size'] > 1)
                                        <small class="text-muted">({{ $item['size'] }}x)</small>
                                    @endif
                                </span>
                                <span class="item-price">Rs {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @empty
                            <div style="text-align:center;padding:2.5rem 0;color:#94a3b8;">
                                <i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                                No items found
                            </div>
                        @endforelse
                    </div>

                    @if(isset($orders) && $orders->count() > 1)
                        <div style="border-top:1px solid var(--ck-border);padding:0.75rem 1.25rem;background:var(--ck-light);font-size:0.8rem;color:var(--ck-muted);">
                            <i class="bi bi-layers"></i>
                            {{ $orders->count() }} active orders consolidated
                        </div>
                    @endif
                </div>
            </div>

            <div class="payment-section">
                <div class="ck-card">
                    <div class="ck-card-header">
                        <i class="bi bi-credit-card"></i> Payment
                    </div>
                    <div class="ck-card-body">

                        <div class="fin-row">
                            <span class="fin-label">Subtotal</span>
                            <span class="fin-value" id="subtotalDisplay">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
                        </div>

                        <div class="fin-row">
                            <span class="fin-label">Service Charge</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="ck-input-group" style="width:100px;">
                                    <input type="number" class="ck-input ck-input-sm text-end" id="serviceChargeInput" value="0.00" step="0.01" min="0">
                                </div>
                                <small class="text-muted">Rs</small>
                            </div>
                        </div>

                        <div class="fin-row">
                            <span class="fin-label">VAT</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="ck-input-group" style="width:70px;">
                                    <input type="number" class="ck-input ck-input-sm text-end" id="vatPercentInput" value="{{ $vatPercent ?? 0 }}" step="0.01" min="0" max="100">
                                    <span class="ck-input-suffix">%</span>
                                </div>
                                <span class="fin-value" id="vatAmountDisplay" style="min-width:70px;">Rs {{ number_format($vatAmount ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <div class="fin-row fin-total">
                            <span class="fin-label">Grand Total</span>
                            <span class="fin-value" id="grandTotalDisplay">Rs {{ number_format($grandTotal ?? 0, 2) }}</span>
                        </div>

                        <hr style="margin:1rem 0;border-color:var(--ck-border);">

                        <label class="toggle-switch mb-3">
                            <input type="checkbox" id="nonChargeableToggle">
                            <span class="toggle-slider"></span>
                            Non-Chargeable / Complimentary
                        </label>

                        <div style="margin-bottom:1rem;">
                            <div style="font-size:0.85rem;font-weight:600;color:#475569;margin-bottom:0.5rem;">Payment Method</div>
                            <div class="payment-methods">
                                <button class="pm-btn active" data-method="cash" onclick="Checkout.selectMethod(this, 'cash')">
                                    <i class="bi bi-cash"></i> Cash
                                </button>
                                <button class="pm-btn" data-method="card" onclick="Checkout.selectMethod(this, 'card')">
                                    <i class="bi bi-credit-card-2-front"></i> Card
                                </button>
                                <button class="pm-btn" data-method="digital_wallet" onclick="Checkout.selectMethod(this, 'digital_wallet')">
                                    <i class="bi bi-wallet2"></i> Wallet
                                </button>
                                <button class="pm-btn" data-method="credit_bill" onclick="Checkout.selectMethod(this, 'credit_bill')">
                                    <i class="bi bi-journal-text"></i> Credit Bill
                                </button>
                            </div>
                        </div>

                        <div class="tender-section">
                            <label style="font-size:0.85rem;font-weight:600;color:#475569;margin-bottom:0.35rem;">Amount Received</label>
                            <div class="ck-input-group">
                                <span class="ck-input-prefix">Rs</span>
                                <input type="number" class="ck-input" id="tenderAmount" placeholder="0.00" step="0.01" value="{{ number_format($grandTotal ?? 0, 2, '.', '') }}" style="font-size:1.1rem;font-weight:700;padding-left:2rem;">
                            </div>
                        </div>

                        <div class="ck-change due" id="changeDisplay" style="margin-top:0.75rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span id="changeLabel">Change Due</span>
                                <span id="changeAmount">Rs 0.00</span>
                            </div>
                        </div>

                        <button class="ck-btn ck-btn-accent ck-btn-block" id="completeCheckoutBtn" style="margin-top:1rem;">
                            <span id="checkoutBtnText">Complete Checkout</span>
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </main>

    <div class="print-receipt">
        <div class="print-receipt-header">
            <div class="pr-title">{{ $siteName ?? 'Restaurant' }}</div>
            @if($address)
                <div class="pr-line">{{ $address }}</div>
            @endif
            @if($contactPhone)
                <div class="pr-line">Tel: {{ $contactPhone }}</div>
            @endif
            <div class="pr-divider"></div>
        </div>

        <div class="print-receipt-info">
            <div class="pr-row">
                <span>Date:</span>
                <span>{{ now()->format('M d, Y g:i A') }}</span>
            </div>
            @if(isset($table))
            <div class="pr-row">
                <span>Table:</span>
                <span>{{ $table->name }}</span>
            </div>
            @endif
            @if($orders->isNotEmpty() && $orders->first()->waiter)
            <div class="pr-row">
                <span>Server:</span>
                <span>{{ $orders->first()->waiter->name }}</span>
            </div>
            @endif
        </div>

        <div class="pr-divider"></div>

        @foreach($orders as $ord)
            @foreach($ord->items as $item)
            <div class="pr-item-row">
                <div class="pr-item-left">
                    <span class="pr-item-qty">{{ $item->quantity }}x</span>
                    <span class="pr-item-name">{{ $item->dish->name ?? $item->menuItem->name ?? 'Item' }}</span>
                </div>
                <span class="pr-item-total">Rs {{ number_format($item->total, 2) }}</span>
            </div>
            @endforeach
        @endforeach

        <div class="pr-divider"></div>

        <div class="pr-calc-row">
            <span>Subtotal</span>
            <span id="prSubtotal">Rs {{ number_format($subtotal ?? 0, 2) }}</span>
        </div>
        <div class="pr-calc-row" id="prServiceChargeRow" style="display:none;">
            <span>Service Charge</span>
            <span id="prServiceCharge">Rs 0.00</span>
        </div>
        <div class="pr-calc-row" id="prVatRow" style="display:none;">
            <span>VAT (<span id="prVatPercent">{{ $vatPercent ?? 0 }}</span>%)</span>
            <span id="prVatAmount">Rs {{ number_format($vatAmount ?? 0, 2) }}</span>
        </div>
        <div class="pr-total-row">
            <span>Total</span>
            <span id="prGrandTotal">Rs {{ number_format($grandTotal ?? 0, 2) }}</span>
        </div>

        <div class="pr-divider"></div>

        <div class="pr-footer">
            <div>Thank You for Your Visit!</div>
            <div class="pr-line" style="font-size:10px;">Please come again</div>
        </div>
    </div>

    <script>
        window.CheckoutConfig = {
            subtotal: {{ $subtotal ?? 0 }},
            grandTotal: {{ $grandTotal ?? 0 }},
            isTableCheckout: {{ isset($table) ? 'true' : 'false' }},
            tableId: {{ $table->id ?? 'null' }},
            orderId: {{ $order->id ?? 'null' }},
            siteName: '{{ ($siteName ?? 'Restaurant') }}',
            address: '{{ ($address ?? '') }}',
            phone: '{{ ($contactPhone ?? '') }}',
            waiterName: '{{ $orders->first()->waiter->name ?? "Staff" }}',
            tableName: '{{ $table->name ?? "" }}',
        };
    </script>
    <script src="{{ asset('assets/js/admin/checkout.js') }}"></script>
</body>
</html>
