<!DOCTYPE html>
<html>
<head>
    <title>Bill - {{ $invoice->invoice_number ?? $order->order_no }}</title>
    <style>
        body { margin:0; padding:0; font-family:'Courier New',monospace; font-size:11px; max-width:80mm; margin:0 auto; }
        .bill-container { padding: 8px; }
        .bill-header { text-align:center; margin-bottom: 8px; }
        .bill-logo { max-width: 50px; max-height: 50px; margin: 0 auto 4px; }
        .bill-name { font-size: 14px; font-weight: bold; margin: 4px 0; }
        .bill-address { font-size: 10px; margin: 2px 0; }
        .bill-contact { font-size: 10px; margin: 2px 0; }
        .bill-pan { font-size: 9px; margin: 2px 0; }
        .bill-title { text-align:center; font-weight:bold; font-size:12px; margin: 8px 0; border-top:1px dashed #000; border-bottom:1px dashed #000; padding: 4px 0; }
        .bill-info { margin-bottom: 8px; border-bottom: 1px dashed #000; padding-bottom: 8px; }
        .bill-info-row { display:flex; justify-content:space-between; font-size:10px; margin-bottom: 2px; }
        .bill-items { margin-bottom: 8px; }
        .bill-item { display:flex; justify-content:space-between; margin-bottom: 4px; font-size:10px; }
        .bill-item-name { font-weight:bold; flex:1; }
        .bill-item-detail { font-size:9px; color:#666; }
        .bill-item-total { font-weight:bold; min-width:50px; text-align:right; }
        .bill-totals { margin-bottom: 8px; border-top:1px dashed #000; padding-top: 4px; }
        .bill-total-row { display:flex; justify-content:space-between; font-size:10px; margin-bottom: 2px; }
        .bill-grand-total { font-size:12px; font-weight:bold; border-top:2px solid #000; border-bottom:2px solid #000; padding: 6px 0; margin: 6px 0; }
        .bill-payment { margin-bottom: 8px; border-top:1px dashed #000; padding-top: 4px; }
        .bill-footer { text-align:center; font-size:9px; margin-top: 8px; border-top:2px dashed #000; padding-top: 8px; }
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="bill-container">
        @if($tenant->logo_url)
            <div class="bill-header">
                <img src="{{ $tenant->logo_url }}" alt="Logo" class="bill-logo">
            </div>
        @endif
        
        <div class="bill-header">
            <div class="bill-name">{{ $tenant->name ?? 'Restaurant' }}</div>
            <div class="bill-address">{{ $tenant->address ?? '' }}</div>
            <div class="bill-contact">{{ $tenant->phone ?? '' }}</div>
            @if($tenant->pan_no)
                <div class="bill-pan">PAN/VAT: {{ $tenant->pan_no }}</div>
            @endif
        </div>

        <div class="bill-title">TAX INVOICE / BILL</div>

        <div class="bill-info">
            <div class="bill-info-row"><span>Bill No: {{ $invoice->invoice_number ?? 'N/A' }}</span><span>Date: {{ $invoice->paid_at->format('Y-m-d') }}</span></div>
            <div class="bill-info-row"><span>Time: {{ $invoice->paid_at->format('H:i') }}</span><span></span></div>
            @if($order->order_type === 'dine_in' && $order->table)
                <div class="bill-info-row"><span>Table: {{ $order->table->name }}</span><span>Waiter: {{ $order->waiter->name ?? 'Staff' }}</span></div>
            @else
                <div class="bill-info-row"><span>Customer: {{ $invoice->customer_name ?? 'Cash Customer' }}</span><span></span></div>
                @if($invoice->customer_phone)
                    <div class="bill-info-row"><span>Phone: {{ $invoice->customer_phone }}</span><span></span></div>
                @endif
                @if($order->order_type === 'delivery' && $invoice->delivery_address)
                    <div class="bill-info-row"><span>Address: {{ $invoice->delivery_address }}</span><span></span></div>
                @endif
            @endif
            <div class="bill-info-row"><span>Cashier: {{ auth()->user()->name ?? 'Admin' }}</span><span></span></div>
        </div>

        <div class="bill-items">
            <div style="display:flex;justify-content:space-between;font-size:10px;font-weight:bold;margin-bottom:4px;border-bottom:1px solid #000;padding-bottom:2px;">
                <span>Item</span><span>Qty</span><span>Rate</span><span>Amount</span>
            </div>
            @foreach($order->items as $item)
                <div class="bill-item">
                    <div style="flex:2;">
                        <div class="bill-item-name">{{ $item->menuItem->name ?? 'Item' }}</div>
                    </div>
                    <div style="min-width:30px;text-align:center;">{{ $item->quantity }}</div>
                    <div style="min-width:40px;text-align:right;">{{ number_format($item->unit_price, 2) }}</div>
                    <div class="bill-item-total">{{ number_format($item->total, 2) }}</div>
                </div>
            @endforeach
        </div>

        <div class="bill-totals">
            <div class="bill-total-row"><span>Subtotal:</span><span>NPR {{ number_format($invoice->subtotal, 2) }}</span></div>
            <div class="bill-total-row"><span>Discount:</span><span>NPR {{ number_format($invoice->discount_amount ?? 0, 2) }}</span></div>
            @if($invoice->service_charge > 0)
                <div class="bill-total-row"><span>Service Charge:</span><span>NPR {{ number_format($invoice->service_charge, 2) }}</span></div>
            @endif
            @if($invoice->vat_amount > 0)
                <div class="bill-total-row"><span>VAT ({{ $invoice->vat_percent }}%):</span><span>NPR {{ number_format($invoice->vat_amount, 2) }}</span></div>
            @endif
            <div class="bill-grand-total"><span>GRAND TOTAL:</span><span>NPR {{ number_format($invoice->total_amount, 2) }}</span></div>
        </div>

        <div class="bill-payment">
            <div class="bill-total-row"><span>Payment Method: {{ ucfirst($invoice->payment_method) }}</span></div>
            <div class="bill-total-row"><span>Paid Amount: NPR {{ number_format($invoice->paid_amount, 2) }}</span></div>
            @if($invoice->change_amount > 0)
                <div class="bill-total-row"><span>Change: NPR {{ number_format($invoice->change_amount, 2) }}</span></div>
            @endif
        </div>

        <div class="bill-footer">
            <p>Thank you for dining with us!</p>
            <p>Please visit again.</p>
            <p style="margin-top:8px;">Cashier: _________________</p>
        </div>

        <div class="no-print" style="text-align:center;margin-top:16px;">
            <button onclick="window.print()" style="padding:8px 16px;font-size:12px;">Print Bill</button>
            <button onclick="window.close()" style="padding:8px 16px;font-size:12px;margin-left:8px;">Close</button>
        </div>
    </div>
</body>
</html>
