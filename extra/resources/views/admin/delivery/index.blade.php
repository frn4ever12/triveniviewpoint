@extends('admin.includes.main')

@section('title', 'Delivery Management')

@push('styles')
    <style>
        .delivery-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
        }
        .delivery-card.pending { border-left-color: #f59e0b; }
        .delivery-card.confirmed { border-left-color: #3b82f6; }
        .delivery-card.preparing { border-left-color: #8b5cf6; }
        .delivery-card.ready { border-left-color: #10b981; }
        .delivery-card.assigned { border-left-color: #06b6d4; }
        .delivery-card.picked-up { border-left-color: #6366f1; }
        .delivery-card.on-the-way { border-left-color: #f97316; }
        .delivery-card.delivered { border-left-color: #22c55e; }
        .delivery-card.cancelled { border-left-color: #ef4444; }
        
        .status-badge {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-badge.pending { background: #fef3c7; color: #d97706; }
        .status-badge.confirmed { background: #dbeafe; color: #2563eb; }
        .status-badge.preparing { background: #ede9fe; color: #7c3aed; }
        .status-badge.ready { background: #d1fae5; color: #059669; }
        .status-badge.assigned { background: #cffafe; color: #0891b2; }
        .status-badge.picked-up { background: #e0e7ff; color: #4f46e5; }
        .status-badge.on-the-way { background: #ffedd5; color: #ea580c; }
        .status-badge.delivered { background: #dcfce7; color: #16a34a; }
        .status-badge.cancelled { background: #fee2e2; color: #dc2626; }
        
        .driver-card {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .driver-card:hover {
            transform: translateY(-2px);
        }
        
        .map-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Delivery Management</h4>
                <p class="text-muted mb-0">Track and manage all delivery orders</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary">
                    <i data-feather="plus" class="icon-xs me-1"></i> New Delivery Order
                </button>
                <button class="btn btn-outline-primary">
                    <i data-feather="map" class="icon-xs me-1"></i> Live Map
                </button>
            </div>
        </div>

        <!-- Delivery Stats -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card pending">
                    <div class="text-muted small text-uppercase mb-1">Pending</div>
                    <div class="h3 mb-0">{{ $pendingOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($pendingTotal, 2) }}</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card confirmed">
                    <div class="text-muted small text-uppercase mb-1">Confirmed</div>
                    <div class="h3 mb-0">{{ $confirmedOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($confirmedTotal, 2) }}</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card preparing">
                    <div class="text-muted small text-uppercase mb-1">Preparing</div>
                    <div class="h3 mb-0">{{ $preparingOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($preparingTotal, 2) }}</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card ready">
                    <div class="text-muted small text-uppercase mb-1">Ready</div>
                    <div class="h3 mb-0">{{ $readyOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($readyTotal, 2) }}</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card on-the-way">
                    <div class="text-muted small text-uppercase mb-1">On The Way</div>
                    <div class="h3 mb-0">{{ $onTheWayOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($onTheWayTotal, 2) }}</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card delivered">
                    <div class="text-muted small text-uppercase mb-1">Delivered</div>
                    <div class="h3 mb-0">{{ $deliveredOrders->count() }}</div>
                    <small class="text-muted">Rs. {{ number_format($deliveredTotal, 2) }}</small>
                </div>
            </div>
        </div>

        <!-- Active Deliveries -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Active Delivery Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Amount</th>
                                        <th>Driver</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activeOrders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_no }}</strong></td>
                                        <td>{{ $order->invoice?->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $order->invoice?->customer_phone ?? 'N/A' }}</td>
                                        <td>{{ $order->invoice?->delivery_address ?? 'N/A' }}</td>
                                        <td>Rs. {{ number_format($order->invoice?->total_amount ?? 0, 2) }}</td>
                                        <td>{{ $order->driver?->name ?? '-' }}</td>
                                        <td><span class="status-badge {{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i data-feather="map-pin" class="icon-xs"></i></button>
                                            <button class="btn btn-sm btn-outline-success"><i data-feather="phone" class="icon-xs"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No active delivery orders</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Delivery Drivers</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            @forelse($drivers as $driver)
                            <div class="driver-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($driver->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $driver->name }}</h6>
                                        <small class="text-muted">{{ $driver->phone ?? 'N/A' }}</small>
                                    </div>
                                    <span class="badge bg-success">Available</span>
                                </div>
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Active: 0</span>
                                        <span class="text-muted">Today: Rs. 0</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4 text-muted">
                                <small>No delivery drivers assigned</small>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Map -->
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Live Delivery Tracking</h5>
                    </div>
                    <div class="card-body">
                        <div class="map-placeholder">
                            <div class="text-center">
                                <i data-feather="map" style="width: 64px; height: 64px; margin-bottom: 1rem;"></i>
                                <div>Interactive Map</div>
                                <small>Restaurant, Customer, and Driver Locations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
