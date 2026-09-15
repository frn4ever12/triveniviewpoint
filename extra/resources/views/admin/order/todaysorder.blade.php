


<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Today's Orders
            </h4>
            <a href="{{ route('admin.orders.pos') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Order
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if($orders->count())
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Order No</th>
                            <th>Table</th>
                            <th>Total</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>#{{ $order->order_no }}</strong></td>
                            <td>{{ $order->table->name ?? 'No Table' }}</td>
                            <td>Rs.{{ number_format($order->invoice->total_amount, 2) }}</td>
                            <td title="{{ $order->created_at }}">{{ $order->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="printBill({{ $order->id }})">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <button class="btn btn-warning btn-sm" onclick="cancelOrder({{ $order->id }})">
                                        <i class="fas fa-ban"></i> Cancel
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteOrder({{ $order->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="print-content-{{ $order->id }}" style="display:none;">
                            <div class="print-bill">
                                <div class="print-header">
                                    <h4>{{ $siteName }}</h4>
                                    <p>{{ $address }}</p>
                                    <p>{{ $contactPhone }}</p>
                                </div>
                                <div class="print-order-info">
                                    <div class="print-order-row">
                                        <span>Order No:</span>
                                        <span>#{{ $order->order_no }}</span>
                                    </div>
                                    <div class="print-order-row">
                                        <span>Table:</span>
                                        <span>{{ $order->table->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="print-order-row">
                                        <span>Date:</span>
                                        <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                </div>
                                <div class="print-items">
    @foreach($order->items as $item)
    <div class="print-item" style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:6px;">
        <div style="flex: 1;">
            <div style="font-weight:bold;">{{ $item->dish->name ?? 'Unknown Item' }}</div>
            @if($item->quantity > 1)
                <div style="font-size:10px; color:#666;">
                    {{ $item->quantity }} x Rs.{{ number_format($item->unit_price, 2) }}
                </div>
            @endif
            @if($item->notes)
                <div style="font-size:9px; color:#888; font-style:italic;">
                    Note: {{ $item->notes }}
                </div>
            @endif
        </div>
        <div style="font-weight:bold;">Rs.{{ number_format($item->total, 2) }}</div>
    </div>
    @endforeach
</div>
                                <div class="print-footer" style="text-align:center; padding:15px 10px; border-top:2px dashed #333; font-size:10px; margin-top:15px;">
                                    Thank you for dining with us!<br>Please visit again.
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center text-muted">No orders found for the selected date range.</p>
                @endif
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('.table').DataTable({
            order: [[0, 'asc']],
            pageLength: 10,
        });
    });

    // Cancel entire order
    async function cancelOrder(orderId) {
        if (!confirm('Cancel entire order? All items will be cancelled.')) return;
        try {
            const response = await fetch('/admin/orders/' + orderId + '/cancel', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                if (window.showToast) showToast('error', data.message || 'Failed to cancel order');
                else alert(data.message || 'Failed to cancel order');
            }
        } catch (error) {
            if (window.showToast) showToast('error', 'Failed to cancel order');
            else alert('Failed to cancel order');
        }
    }

    // Delete order
    async function deleteOrder(orderId) {
        if (!confirm('Are you sure you want to permanently delete this order? This cannot be undone.')) return;
        try {
            const response = await fetch('/admin/orders/' + orderId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            if (data.success) {
                if (window.showToast) showToast('success', 'Order deleted successfully');
                else alert('Order deleted successfully');
                location.reload();
            } else {
                if (window.showToast) showToast('error', data.message || 'Failed to delete order');
                else alert(data.message || 'Failed to delete order');
            }
        } catch (error) {
            if (window.showToast) showToast('error', 'Failed to delete order');
            else alert('Failed to delete order');
        }
    }

    function printBill(orderId) {
        const printContent = document.getElementById('print-content-' + orderId).innerHTML;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Receipt - Order #${orderId}</title>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: 'Courier New', monospace;
                        font-size: 11px;
                        line-height: 1.2;
                        width: 100%;
                        max-width: 400px;
                        margin: 0 auto;
                    }
                    .print-header {
                        text-align: center;
                        padding: 15px 10px;
                        border-bottom: 2px dashed #333;
                        background: #000;
                        color: white;
                        margin-bottom: 10px;
                    }
                    .print-header h4 {
                        font-size: 14px;
                        margin: 5px 0;
                        font-weight: bold;
                    }
                    .print-header p {
                        font-size: 10px;
                        margin: 2px 0;
                    }
                    .print-order-info {
                        padding: 0 10px 10px;
                        border-bottom: 1px dashed #333;
                        margin-bottom: 10px;
                    }
                    .print-order-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 3px;
                        font-size: 11px;
                    }
                    .print-items {
                        padding: 0 10px 10px;
                        margin-bottom: 10px;
                    }
                    .print-item {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 6px;
                        font-size: 11px;
                        padding-right: 10px;
                    }
                    .print-item-name {
                        flex: 1;
                        font-weight: bold;
                    }
                    .print-item-total {
                        min-width: 60px;
                        text-align: right;
                        font-weight: bold;
                    }
                    .print-order-total {
                        border-top: 1px dashed #333;
                        padding-top: 5px;
                        margin-top: 5px;
                        font-weight: bold;
                        font-size: 11px;
                        text-align: right;
                    }
                    .print-calc {
                        padding: 0 10px;
                        border-top: 1px dashed #333;
                        margin-top: 10px;
                    }
                    .print-calc-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 4px;
                        font-size: 11px;
                    }
                    .print-total {
                        font-weight: bold;
                        font-size: 13px;
                        border-top: 2px solid #333;
                        border-bottom: 2px solid #333;
                        padding: 6px 0;
                        margin: 8px 0;
                    }
                    .print-footer {
                        text-align: center;
                        padding: 15px 10px;
                        border-top: 2px dashed #333;
                        font-size: 10px;
                        margin-top: 15px;
                    }
                    .no-print {
                        display: none !important;
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.print();
        };
    }
</script>
