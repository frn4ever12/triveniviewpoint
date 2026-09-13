<!DOCTYPE html>
<html>
<head>
    <title>Bill - {{ $order->order_no }}</title>
    <style>
        body { margin:0; padding:0; font-family:'Courier New',monospace; font-size:11px; max-width:400px; margin:0 auto; }
        .pos-print-header { text-align:center; padding:16px 12px; background:#000; color:#fff; margin-bottom:10px; }
        .pos-print-header h4 { font-size:14px; margin:0 0 4px; }
        .pos-print-header p { font-size:10px; margin:1px 0; }
        .pos-print-section { padding:0 12px 10px; border-bottom:1px dashed #333; margin-bottom:10px; }
        .pos-print-row { display:flex; justify-content:space-between; font-size:11px; margin-bottom:3px; }
        .pos-print-items { padding:0 12px 10px; margin-bottom:10px; }
        .pos-print-item { display:flex; justify-content:space-between; margin-bottom:5px; font-size:11px; }
        .pos-print-item-name { font-weight:700; flex:1; }
        .pos-print-item-detail { font-size:9px; color:#666; }
        .pos-print-item-total { font-weight:700; min-width:65px; text-align:right; }
        .pos-print-calc { padding:0 12px; border-top:1px dashed #333; padding-top:8px; }
        .pos-print-calc-row { display:flex; justify-content:space-between; font-size:11px; margin-bottom:3px; }
        .pos-print-grand-total { font-size:14px; font-weight:700; border-top:2px solid #333; border-bottom:2px solid #333; padding:8px 0; margin:8px 0; }
        .pos-print-footer { text-align:center; padding:14px 12px; border-top:2px dashed #333; font-size:10px; margin-top:12px; }
    </style>
</head>
<body>
    <div class="pos-print-header">
        <h4>{{ $currentTenant->name ?? 'Restaurant' }}</h4>
        <p>{{ $currentTenant->address ?? '' }}</p>
        <p>{{ $currentTenant->phone ?? '' }}</p>
    </div>
    <div class="pos-print-section">
        <div class="pos-print-row"><span>Order: {{ $order->order_no }}</span><span>{{ $order->created_at->format('Y-m-d') }}</span></div>
        <div class="pos-print-row"><span>Type: {{ strtoupper(str_replace('_', ' ', $order->order_type)) }}</span><span>{{ $order->created_at->format('H:i') }}</span></div>
        @if($order->order_type === 'dine_in' && $order->table)
            <div class="pos-print-row"><span>Table: {{ $order->table->name }}</span><span>Staff: {{ auth()->user()->name ?? 'Staff' }}</span></div>
        @else
            <div class="pos-print-row"><span>Customer: {{ $order->customer_name ?? '-' }}</span><span>Phone: {{ $order->customer_phone ?? '-' }}</span></div>
            @if($order->order_type === 'delivery' && $order->delivery_address)
                <div class="pos-print-row" style="margin-top:4px;"><span style="font-size:10px;">Address: {{ $order->delivery_address }}</span></div>
            @endif
        @endif
    </div>
    <div class="pos-print-items">
        @foreach($order->items as $item)
            <div class="pos-print-item">
                <div>
                    <div class="pos-print-item-name">{{ $item->menuItem->name ?? 'Item' }}</div>
                    <div class="pos-print-item-detail">{{ $item->quantity }} x Rs {{ number_format($item->unit_price, 2) }}</div>
                </div>
                <div class="pos-print-item-total">Rs {{ number_format($item->total, 2) }}</div>
            </div>
        @endforeach
    </div>
    <div class="pos-print-calc">
        @if($order->invoice)
            <div class="pos-print-calc-row"><span>Subtotal:</span><span>Rs {{ number_format($order->invoice->subtotal, 2) }}</span></div>
            <div class="pos-print-calc-row"><span>VAT ({{ $order->invoice->vat_percent }}%):</span><span>Rs {{ number_format($order->invoice->vat_amount, 2) }}</span></div>
        @endif
        <div class="pos-print-calc-row pos-print-grand-total"><span>TOTAL:</span><span>Rs {{ number_format($order->invoice->total_amount ?? $order->total_amount, 2) }}</span></div>
        <div class="pos-print-calc-row"><span>Payment:</span><span>{{ ucfirst($order->payment_status) }}</span></div>
    </div>
    <div class="pos-print-footer">
        <p>Thank you! Please visit again.</p>
        <p>{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
