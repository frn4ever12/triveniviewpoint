@extends('admin.includes.main')
@section('title', 'Orders List')
@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Orders List" route="admin.orders.index" button="Back to Orders" icon="bi-arrow-left" />
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <h5 class="mb-0">
                <i class="bi bi-calendar-range me-2 text-danger"></i>
                Orders from {{ $startDate->format('M j, Y') }} to {{ $endDate->format('M j, Y') }}
            </h5>
            <span class="badge bg-dark rounded-pill fs-6 px-3 py-2">{{ $todayOrders->count() }} orders</span>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-4 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                           value="{{ request('start_date') ?? $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm"
                           value="{{ request('end_date') ?? $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <a href="{{ route('admin.orders.details') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm w-100">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </form>

            @if($todayOrders->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="orders-table" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Order No</th>
                            <th>Table</th>
                            <th>Items</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th class="text-center" style="width:100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayOrders as $order)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td><strong class="text-danger">#{{ $order->order_no }}</strong></td>
                            <td>
                                @if($order->table)
                                    <span class="badge bg-dark">{{ $order->table->name }}</span>
                                @else
                                    <span class="text-muted fst-italic">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $order->items->sum('quantity') }} items</span>
                            </td>
                            <td class="text-end fw-bold">Rs. {{ number_format($order->items_sum_total ?? $order->items->sum('total'), 2) }}</td>
                            <td>
                                @php
                                    $statusColor = match($order->status) {
                                        \App\Enums\OrderStatusEnum::COMPLETED => 'success',
                                        \App\Enums\OrderStatusEnum::CANCELLED => 'secondary',
                                        \App\Enums\OrderStatusEnum::CONFIRMED => 'info',
                                        \App\Enums\OrderStatusEnum::SERVED => 'primary',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">{{ $order->status->label() }}</span>
                            </td>
                            <td>
                                <span title="{{ $order->created_at }}">{{ $order->created_at->format('h:i A') }}</span>
                                <br><small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" onclick="printBill({{ $order->id }})" title="Print Bill">
                                    <i class="bi bi-receipt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2 mb-0">No orders found for the selected date range.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@foreach($todayOrders as $order)
<div id="print-content-{{ $order->id }}" style="display:none;">
    <div class="print-bill">
        <div class="print-header">
            <h4>{{ $siteName ?? 'Restaurant' }}</h4>
            <p>{{ $address ?? '' }}</p>
            <p>{{ $contactPhone ?? '' }}</p>
        </div>
        <div class="print-order-info">
            <div class="print-row"><span>Order No:</span><span>#{{ $order->order_no }}</span></div>
            <div class="print-row"><span>Table:</span><span>{{ $order->table->name ?? 'N/A' }}</span></div>
            <div class="print-row"><span>Date:</span><span>{{ $order->created_at->format('d M Y, h:i A') }}</span></div>
        </div>
        <div class="print-items">
            @foreach($order->items as $item)
            <div class="print-item">
                <div class="print-item-left">
                    <div class="print-item-name">{{ $item->menuItem->name ?? 'Unknown' }}</div>
                    @if($item->quantity > 1)
                    <div class="print-item-qty">{{ $item->quantity }} x Rs.{{ number_format($item->unit_price, 2) }}</div>
                    @endif
                </div>
                <div class="print-item-total">Rs.{{ number_format($item->total, 2) }}</div>
            </div>
            @endforeach
        </div>
        <div class="print-total-row">
            <span>Total</span>
            <span>Rs. {{ number_format($order->items->sum('total'), 2) }}</span>
        </div>
        <div class="print-footer">Thank you for dining with us!</div>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
@media print {
    .navbar, .sidebar, .breadcrumb, form, #sidebar,
    .navbar-vertical, footer, .no-print, [class*="btn"],
    .dataTables_filter, .dataTables_length, .dataTables_paginate,
    .dataTables_info, .dt-buttons, .card-header .badge { display: none !important; }
    body { background: #fff !important; }
    .card { border: none !important; box-shadow: none !important; }
    .table { border: 1px solid #ddd !important; }
    .table th { background: #f5f5f5 !important; color: #000 !important; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#orders-table').DataTable({
        responsive: true,
        order: [[0, 'asc']],
        pageLength: 25,
        language: { search: "", searchPlaceholder: "Search orders..." }
    });
});

function printBill(orderId) {
    const el = document.getElementById('print-content-' + orderId);
    if (!el) return;
    const w = window.open('', '_blank');
    w.document.write(`<!DOCTYPE html>
<html><head><title>Receipt #${orderId}</title>
<style>
body { margin:0; padding:10px; font-family:'Courier New',monospace; font-size:11px; max-width:320px; margin:0 auto; }
.print-header { text-align:center; padding:12px 8px; border-bottom:2px dashed #333; background:#000; color:#fff; margin-bottom:8px; }
.print-header h4 { font-size:13px; margin:4px 0; font-weight:700; }
.print-header p { font-size:9px; margin:2px 0; }
.print-order-info { padding:0 8px 8px; border-bottom:1px dashed #333; margin-bottom:8px; }
.print-row { display:flex; justify-content:space-between; margin-bottom:2px; font-size:10px; }
.print-items { padding:0 8px 8px; }
.print-item { display:flex; justify-content:space-between; margin-bottom:5px; font-size:10px; }
.print-item-left { flex:1; }
.print-item-name { font-weight:700; }
.print-item-qty { font-size:9px; color:#666; }
.print-item-total { font-weight:700; min-width:60px; text-align:right; }
.print-total-row { display:flex; justify-content:space-between; border-top:2px solid #333; border-bottom:2px solid #333; padding:6px 8px; margin:8px 0; font-weight:700; font-size:12px; }
.print-footer { text-align:center; padding:10px 8px; border-top:2px dashed #333; font-size:9px; margin-top:10px; }
</style></head><body>${el.innerHTML}</body></html>`);
    w.document.close();
    w.onload = () => { w.print(); };
}
</script>
@endpush
