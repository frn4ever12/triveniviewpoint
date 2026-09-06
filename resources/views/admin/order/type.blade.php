@extends('admin.includes.main')
@section('title', 'Orders')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">
                    @if($type == 'dine-in') Dine In Orders
                    @elseif($type == 'takeaway') Takeaway Orders
                    @elseif($type == 'delivery') Delivery Orders
                    @elseif($type == 'online') Online Orders
                    @elseif($type == 'cancelled') Cancelled Orders
                    @elseif($type == 'history') Order History
                    @else Orders
                    @endif
                </h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    @if($type == 'dine-in') Manage dine-in orders
                    @elseif($type == 'takeaway') Manage takeaway orders
                    @elseif($type == 'delivery') Manage delivery orders
                    @elseif($type == 'online') Manage online orders
                    @elseif($type == 'cancelled') View cancelled orders
                    @elseif($type == 'history') View order history
                    @else Manage orders
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger btn-sm rounded-3">All Orders</a>
                <a href="{{ route('admin.orders.pos') }}" class="btn btn-danger btn-sm rounded-3">POS</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-3" style="border-radius:12px 12px 0 0;">
                <h5 class="mb-0 fw-bold" style="font-size:.95rem;">
                    @if($type == 'dine-in') Dine In Orders
                    @elseif($type == 'takeaway') Takeaway Orders
                    @elseif($type == 'delivery') Delivery Orders
                    @elseif($type == 'online') Online Orders
                    @elseif($type == 'cancelled') Cancelled Orders
                    @elseif($type == 'history') Order History
                    @else Orders
                    @endif
                </h5>
                <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="refreshOrders()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </button>
            </div>
            <div class="card-body p-3">
                <div class="row g-3" id="ordersTableBody">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const orderType = '{{ $type }}';

    async function loadOrders() {
        try {
            let endpoint = '/admin/orders/recent';
            if (orderType === 'dine-in') endpoint = '/admin/orders/dine-in/data';
            else if (orderType === 'takeaway') endpoint = '/admin/orders/takeaway/data';
            else if (orderType === 'delivery') endpoint = '/admin/orders/delivery/data';
            else if (orderType === 'online') endpoint = '/admin/orders/online/data';
            else if (orderType === 'cancelled') endpoint = '/admin/orders/cancelled/data';
            else if (orderType === 'history') endpoint = '/admin/orders/history/data';

            const r = await fetch(endpoint, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const d = await r.json();
            if (!d.success) return;

            const ct = document.getElementById('ordersTableBody');
            
            if (!d.orders || d.orders.length === 0) {
                ct.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No orders found</p></div>';
                return;
            }

            ct.innerHTML = d.orders.map(order => {
                const items = order.items.map(item => `
                    <li class="d-flex justify-content-between align-items-center py-1" style="font-size:.82rem;border-bottom:1px solid #f1f5f9;">
                        <span class="${item.status==='cancelled'?'text-decoration-line-through text-muted':''}">${item.name}</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="${item.status==='cancelled'?'text-decoration-line-through':''}">x${item.quantity}</span>
                        </div>
                    </li>
                `).join('');

                return `<div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="order-card-link" style="cursor:pointer;" onclick="window.location.href='/admin/orders/${order.id}'">
                        <div class="order-card-slim">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">#${order.order_no}</h6>
                                <span class="dash-badge ${order.status==='pending'?'bg-warning text-dark':order.status==='confirmed'?'bg-info text-white':order.status==='served'?'bg-success text-white':order.status==='cancelled'?'bg-danger text-white':'bg-secondary text-white'}">${order.status}</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Type:</span>
                                <span class="text-capitalize">${order.order_type || 'N/A'}</span>
                            </div>
                            ${order.table ? `<div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Table:</span>
                                <span>${order.table.name}</span>
                            </div>` : ''}
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Time:</span>
                                <span>${order.created_at}</span>
                            </div>
                            <ul class="list-unstyled mt-2 mb-2">${items}</ul>
                            <div class="d-flex justify-content-between small border-top pt-1">
                                <span>Items: ${order.items_count}</span>
                                <span class="fw-bold">Rs ${parseFloat(order.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch(e) {
            console.error('Failed to load orders:', e);
            document.getElementById('ordersTableBody').innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Failed to load orders</p></div>';
        }
    }

    function refreshOrders() { loadOrders(); }

    document.addEventListener('DOMContentLoaded', () => loadOrders());
</script>
@endpush
