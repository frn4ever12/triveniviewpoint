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
                    <div class="h3 mb-0">5</div>
                    <small class="text-muted">Rs. 2,450</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card confirmed">
                    <div class="text-muted small text-uppercase mb-1">Confirmed</div>
                    <div class="h3 mb-0">3</div>
                    <small class="text-muted">Rs. 1,850</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card preparing">
                    <div class="text-muted small text-uppercase mb-1">Preparing</div>
                    <div class="h3 mb-0">2</div>
                    <small class="text-muted">Rs. 1,200</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card ready">
                    <div class="text-muted small text-uppercase mb-1">Ready</div>
                    <div class="h3 mb-0">4</div>
                    <small class="text-muted">Rs. 3,100</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card on-the-way">
                    <div class="text-muted small text-uppercase mb-1">On The Way</div>
                    <div class="h3 mb-0">6</div>
                    <small class="text-muted">Rs. 4,850</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="delivery-card delivered">
                    <div class="text-muted small text-uppercase mb-1">Delivered</div>
                    <div class="h3 mb-0">28</div>
                    <small class="text-muted">Rs. 24,500</small>
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
                                    <tr>
                                        <td><strong>#ORD-1025</strong></td>
                                        <td>Ram Sharma</td>
                                        <td>9800000001</td>
                                        <td>Thamel, Kathmandu</td>
                                        <td>Rs. 1,450</td>
                                        <td>Bikash</td>
                                        <td><span class="status-badge on-the-way">On The Way</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i data-feather="map-pin" class="icon-xs"></i></button>
                                            <button class="btn btn-sm btn-outline-success"><i data-feather="phone" class="icon-xs"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-1024</strong></td>
                                        <td>Sita Devi</td>
                                        <td>9800000002</td>
                                        <td>Lazimpat, Kathmandu</td>
                                        <td>Rs. 890</td>
                                        <td>Hari</td>
                                        <td><span class="status-badge picked-up">Picked Up</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i data-feather="map-pin" class="icon-xs"></i></button>
                                            <button class="btn btn-sm btn-outline-success"><i data-feather="phone" class="icon-xs"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-1023</strong></td>
                                        <td>Krishna Bahadur</td>
                                        <td>9800000003</td>
                                        <td>Baluwatar, Kathmandu</td>
                                        <td>Rs. 2,100</td>
                                        <td>-</td>
                                        <td><span class="status-badge ready">Ready</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Assign Driver</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-1022</strong></td>
                                        <td>Maya Tamang</td>
                                        <td>9800000004</td>
                                        <td>Baneshwor, Kathmandu</td>
                                        <td>Rs. 650</td>
                                        <td>-</td>
                                        <td><span class="status-badge preparing">Preparing</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-1021</strong></td>
                                        <td>Dipak Rai</td>
                                        <td>9800000005</td>
                                        <td>New Baneshwor</td>
                                        <td>Rs. 1,200</td>
                                        <td>-</td>
                                        <td><span class="status-badge confirmed">Confirmed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary">View</button>
                                        </td>
                                    </tr>
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
                            <div class="driver-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <span class="text-white fw-bold">BS</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Bikash Sharma</h6>
                                        <small class="text-muted">9800000010</small>
                                    </div>
                                    <span class="badge bg-success">Available</span>
                                </div>
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Active: 2</span>
                                        <span class="text-muted">Today: Rs. 450</span>
                                    </div>
                                </div>
                            </div>
                            <div class="driver-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <span class="text-white fw-bold">HK</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Hari Krishna</h6>
                                        <small class="text-muted">9800000011</small>
                                    </div>
                                    <span class="badge bg-warning">Busy</span>
                                </div>
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Active: 1</span>
                                        <span class="text-muted">Today: Rs. 280</span>
                                    </div>
                                </div>
                            </div>
                            <div class="driver-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <span class="text-white fw-bold">RS</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Ram Singh</h6>
                                        <small class="text-muted">9800000012</small>
                                    </div>
                                    <span class="badge bg-secondary">Offline</span>
                                </div>
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Active: 0</span>
                                        <span class="text-muted">Today: Rs. 0</span>
                                    </div>
                                </div>
                            </div>
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
