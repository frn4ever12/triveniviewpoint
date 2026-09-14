<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Tracking — {{ $siteName ?? 'RestaurantPro' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .track-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        .track-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        .order-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: -30px auto 2rem;
            max-width: 600px;
            padding: 2rem;
        }
        .order-number {
            font-size: 2rem;
            font-weight: 700;
            color: #dc2626;
        }
        .table-badge {
            background: #fef3c7;
            color: #d97706;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }
        .status-timeline {
            margin: 2rem 0;
        }
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: -20px;
            width: 2px;
            background: #e5e7eb;
        }
        .timeline-item:last-child::before {
            display: none;
        }
        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            z-index: 1;
        }
        .timeline-icon.completed {
            background: #10b981;
            color: white;
        }
        .timeline-icon.current {
            background: #dc2626;
            color: white;
            animation: pulse 1.5s infinite;
        }
        .timeline-icon.pending {
            background: #e5e7eb;
            color: #9ca3af;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .timeline-content h5 {
            margin: 0;
            font-weight: 600;
        }
        .timeline-content small {
            color: #6b7280;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-weight: 600;
        }
        .item-qty {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .item-price {
            font-weight: 600;
        }
        .total-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1.5rem;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .total-row.grand-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #dc2626;
            border-top: 2px solid #e5e7eb;
            padding-top: 1rem;
            margin-top: 1rem;
            margin-bottom: 0;
        }
        .notification-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
            z-index: 1000;
        }
        .notification-badge {
            background: #ffc107;
            color: #000;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.75rem;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div class="track-header">
        <div class="container">
            <h1><i class="bi bi-geo-alt"></i> Order Tracking</h1>
            <p class="mb-0">{{ $tenant->name ?? 'Restaurant' }}</p>
        </div>
    </div>

    <div class="container">
        <div class="order-card">
            <div class="text-center mb-4">
                <div class="order-number">#{{ $order->order_no }}</div>
                <span class="table-badge"><i class="bi bi-table"></i> Table {{ $order->table->name ?? 'N/A' }}</span>
            </div>

            <div class="status-timeline" id="statusTimeline">
                <!-- Timeline will be populated by JavaScript -->
            </div>

            <div class="mt-4">
                <h5 class="mb-3">Order Items</h5>
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div>
                            <div class="item-name">{{ $item->menuItem->name ?? 'Unknown' }}</div>
                            <div class="item-qty">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div class="item-price">Rs {{ number_format($item->total, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rs {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->vat_amount > 0)
                    <div class="total-row">
                        <span>VAT ({{ $order->vat_percent ?? 13 }}%)</span>
                        <span>Rs {{ number_format($order->vat_amount, 2) }}</span>
                    </div>
                @endif
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span>Rs {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <button class="notification-btn" onclick="showNotifications()">
        <i class="bi bi-bell"></i> Notifications
        <span class="notification-badge" id="notifCount">0</span>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const orderData = @json($order);
        const sessionToken = '{{ request()->segment(2) }}';
        const orderNo = '{{ request()->segment(3) }}';

        const statusSteps = [
            { key: 'pending', label: 'Order Placed' },
            { key: 'confirmed', label: 'Confirmed' },
            { key: 'preparing', label: 'Preparing' },
            { key: 'ready', label: 'Ready' },
            { key: 'served', label: 'Served' },
            { key: 'completed', label: 'Completed' }
        ];

        function renderTimeline() {
            const currentStatus = orderData.status;
            const timeline = document.getElementById('statusTimeline');
            let html = '';

            statusSteps.forEach((step, index) => {
                const isCompleted = statusSteps.slice(0, index).some(s => s.key === currentStatus);
                const isCurrent = step.key === currentStatus;
                const isPending = !isCompleted && !isCurrent;

                let iconClass = 'pending';
                if (isCompleted) iconClass = 'completed';
                if (isCurrent) iconClass = 'current';

                html += `
                    <div class="timeline-item">
                        <div class="timeline-icon ${iconClass}">
                            ${isCompleted || isCurrent ? '<i class="bi bi-check"></i>' : '<i class="bi bi-circle"></i>'}
                        </div>
                        <div class="timeline-content">
                            <h5>${step.label}</h5>
                            <small>${isCurrent ? 'In Progress' : (isCompleted ? 'Completed' : 'Pending')}</small>
                        </div>
                    </div>
                `;
            });

            timeline.innerHTML = html;
        }

        async function fetchOrderStatus() {
            try {
                const response = await fetch(`/qr/track/${sessionToken}/${orderNo}`);
                const result = await response.json();
                
                if (result.success && result.order) {
                    orderData.status = result.order.status;
                    renderTimeline();
                    
                    // Check for new notifications
                    if (result.order.status !== localStorage.getItem('lastStatus')) {
                        addNotification(`Order #${orderNo}`, `Status updated to ${result.order.status}`);
                        localStorage.setItem('lastStatus', result.order.status);
                    }
                }
            } catch (error) {
                console.error('Error fetching order status:', error);
            }
        }

        let notifications = [];

        function addNotification(title, message) {
            notifications.unshift({ title, message, time: new Date().toLocaleTimeString(), read: false });
            updateNotificationBadge();
        }

        function updateNotificationBadge() {
            const unreadCount = notifications.filter(n => !n.read).length;
            document.getElementById('notifCount').textContent = unreadCount;
        }

        function showNotifications() {
            if (notifications.length === 0) {
                alert('No notifications');
                return;
            }

            let html = '<div style="max-height: 400px; overflow-y: auto;">';
            notifications.forEach(n => {
                html += `
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; ${!n.read ? 'background: #fef3c7;' : ''}">
                        <strong>${n.title}</strong>
                        <p class="mb-0">${n.message}</p>
                        <small class="text-muted">${n.time}</small>
                    </div>
                `;
            });
            html += '</div>';

            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Notifications</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">${html}</div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            new bootstrap.Modal(modal).show();
            
            notifications.forEach(n => n.read = true);
            updateNotificationBadge();
        }

        // Initialize
        renderTimeline();
        
        // Poll for status updates every 10 seconds
        setInterval(fetchOrderStatus, 10000);
    </script>
</body>
</html>
