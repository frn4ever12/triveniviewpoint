<!DOCTYPE html>
<html>
<head>
    <title>KOT - {{ $order->order_no }}</title>
    <style>
        body { margin:0; padding:0; font-family:'Courier New',monospace; font-size:11px; max-width:400px; margin:0 auto; }
        .pos-print-header { text-align:center; padding:16px 12px; background:#8b5cf6; color:#fff; margin-bottom:10px; }
        .pos-print-header h4 { font-size:14px; margin:0 0 4px; }
        .pos-print-header p { font-size:10px; margin:1px 0; }
        .pos-print-section { padding:0 12px 10px; border-bottom:1px dashed #333; margin-bottom:10px; }
        .pos-print-row { display:flex; justify-content:space-between; font-size:11px; margin-bottom:3px; }
        .pos-print-items { padding:0 12px 10px; margin-bottom:10px; }
        .pos-print-item { display:flex; justify-content:space-between; margin-bottom:5px; font-size:11px; }
        .pos-print-item-name { font-weight:700; flex:1; }
        .pos-print-item-detail { font-size:9px; color:#666; }
        .pos-print-item-total { font-weight:700; min-width:65px; text-align:right; }
        .pos-print-footer { text-align:center; padding:14px 12px; border-top:2px dashed #333; font-size:10px; margin-top:12px; }
    </style>
</head>
<body>
    <div class="pos-print-header">
        <h4>KITCHEN ORDER TICKET</h4>
        <p>{{ $currentTenant->name ?? 'Restaurant' }}</p>
    </div>
    <div class="pos-print-section">
        <div class="pos-print-row"><span>Order: {{ $order->order_no }}</span><span>{{ $order->created_at->format('Y-m-d H:i') }}</span></div>
        <div class="pos-print-row"><span>Type: {{ strtoupper(str_replace('_', ' ', $order->order_type)) }}</span></div>
        @if($order->order_type === 'dine_in' && $order->table)
            <div class="pos-print-row"><span>Table: {{ $order->table->name }}</span><span>Waiter: {{ $order->waiter->name ?? '-' }}</span></div>
        @else
            <div class="pos-print-row"><span>Customer: {{ $order->customer_name ?? '-' }}</span></div>
        @endif
        @if($order->notes)
            <div class="pos-print-row" style="margin-top:4px;"><span style="font-size:10px;">Notes: {{ $order->notes }}</span></div>
        @endif
    </div>
    <div class="pos-print-items">
        @foreach($order->items as $item)
            <div class="pos-print-item">
                <div>
                    <div class="pos-print-item-name">{{ $item->menuItem->name ?? 'Item' }}</div>
                    <div class="pos-print-item-detail">Qty: {{ $item->quantity }} @if($item->size && $item->size != 1) ({{ $item->size }}x) @endif</div>
                </div>
                <div class="pos-print-item-total">{{ $item->status }}</div>
            </div>
        @endforeach
    </div>
    <div class="pos-print-footer">
        <p>KOT #{{ $order->kot->id ?? '-' }}</p>
        <p>{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
