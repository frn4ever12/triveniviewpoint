@extends('admin.includes.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <style>
        .kpi-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .kpi-card.sales { border-left-color: #16a34a; }
        .kpi-card.orders { border-left-color: #dc2626; }
        .kpi-card.profit { border-left-color: #2563eb; }
        .kpi-card.expenses { border-left-color: #f59e0b; }
        .kpi-card.net-profit { border-left-color: #7c3aed; }
        .kpi-card.deliveries { border-left-color: #0891b2; }
        
        .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }
        .kpi-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kpi-change {
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .section-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .table-custom th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem;
        }
        .table-custom td {
            font-size: 0.85rem;
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.success { background: #dcfce7; color: #16a34a; }
        .status-badge.warning { background: #fef3c7; color: #d97706; }
        .status-badge.danger { background: #fee2e2; color: #dc2626; }
        .status-badge.info { background: #dbeafe; color: #2563eb; }
        .status-badge.secondary { background: #f1f5f9; color: #64748b; }
        
        .chart-container {
            position: relative;
            height: 250px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Dashboard</h4>
                <p class="text-muted mb-0">Shree Foodies - Restaurant Management Overview</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.pos') }}" class="btn btn-primary">
                    <i data-feather="shopping-cart" class="icon-xs me-1"></i> POS
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                    <i data-feather="plus" class="icon-xs me-1"></i> New Order
                </a>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card sales">
                    <div class="kpi-label">Today's Sales</div>
                    <div class="kpi-value">Rs. {{ number_format($todaysRevenue, 2) }}</div>
                    <div class="kpi-change {{ $revenueChange >= 0 ? 'text-success' : 'text-danger' }}">
                        <i data-feather="arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}" class="icon-xs"></i>
                        {{ number_format(abs($revenueChange), 1) }}% vs yesterday
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card orders">
                    <div class="kpi-label">Today's Orders</div>
                    <div class="kpi-value">{{ $ordersToday }}</div>
                    <div class="kpi-change {{ $ordersChange >= 0 ? 'text-success' : 'text-danger' }}">
                        <i data-feather="arrow-{{ $ordersChange >= 0 ? 'up' : 'down' }}" class="icon-xs"></i>
                        {{ abs($ordersChange) }}% vs yesterday
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card profit">
                    <div class="kpi-label">Gross Profit</div>
                    <div class="kpi-value">Rs. {{ number_format($todaysRevenue * 0.65, 2) }}</div>
                    <div class="kpi-change text-success">
                        <i data-feather="arrow-up" class="icon-xs"></i> 65% margin
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card expenses">
                    <div class="kpi-label">Expenses</div>
                    <div class="kpi-value">Rs. {{ number_format($todaysRevenue * 0.35, 2) }}</div>
                    <div class="kpi-change text-warning">
                        <i data-feather="minus" class="icon-xs"></i> 35% of sales
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card net-profit">
                    <div class="kpi-label">Net Profit</div>
                    <div class="kpi-value">Rs. {{ number_format($todaysRevenue * 0.30, 2) }}</div>
                    <div class="kpi-change text-success">
                        <i data-feather="arrow-up" class="icon-xs"></i> 30% net
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="kpi-card deliveries">
                    <div class="kpi-label">Pending Deliveries</div>
                    <div class="kpi-value">12</div>
                    <div class="kpi-change text-info">
                        <i data-feather="truck" class="icon-xs"></i> Active
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="trending-up" class="icon-xs text-primary"></i>
                        Sales Trend (Last 7 Days)
                    </div>
                    <div class="chart-container">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="pie-chart" class="icon-xs text-primary"></i>
                        Order Type Breakdown
                    </div>
                    <div class="chart-container">
                        <canvas id="orderTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="truck" class="icon-xs text-primary"></i>
                        Delivery Overview
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="status-badge warning">Pending</span></td>
                                    <td>5</td>
                                    <td>Rs. 2,450</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge info">Confirmed</span></td>
                                    <td>3</td>
                                    <td>Rs. 1,850</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge success">Delivered</span></td>
                                    <td>15</td>
                                    <td>Rs. 12,750</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge danger">Cancelled</span></td>
                                    <td>1</td>
                                    <td>Rs. 450</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="users" class="icon-xs text-primary"></i>
                        KOT / Kitchen Status
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Orders</th>
                                    <th>Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="status-badge warning">New</span></td>
                                    <td>3</td>
                                    <td>12</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge info">Preparing</span></td>
                                    <td>5</td>
                                    <td>18</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge success">Ready</span></td>
                                    <td>2</td>
                                    <td>8</td>
                                </tr>
                                <tr>
                                    <td><span class="status-badge secondary">Served</span></td>
                                    <td>8</td>
                                    <td>32</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="alert-triangle" class="icon-xs text-primary"></i>
                        Low Stock Alert
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Stock</th>
                                    <th>Reorder</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Chicken Momo</td>
                                    <td class="text-danger">2.5 kg</td>
                                    <td>5 kg</td>
                                </tr>
                                <tr>
                                    <td>Chowmein Noodles</td>
                                    <td class="text-warning">3 kg</td>
                                    <td>5 kg</td>
                                </tr>
                                <tr>
                                    <td>Cold Drinks</td>
                                    <td class="text-danger">8 bottles</td>
                                    <td>24 bottles</td>
                                </tr>
                                <tr>
                                    <td>Onion</td>
                                    <td class="text-warning">4 kg</td>
                                    <td>10 kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="shopping-bag" class="icon-xs text-primary"></i>
                        Recent Orders
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Table</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders->take(5) as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_no }}</strong></td>
                                    <td>{{ $order->table->name ?? '-' }}</td>
                                    <td>{{ ucfirst($order->order_type ?? 'dine_in') }}</td>
                                    <td>
                                        <span class="status-badge {{ match($order->status->value) {
                                            'pending' => 'warning',
                                            'preparing' => 'info',
                                            'ready' => 'success',
                                            'served' => 'secondary',
                                            'completed' => 'success',
                                            default => 'secondary'
                                        } }}">
                                            {{ ucfirst($order->status->value) }}
                                        </span>
                                    </td>
                                    <td>Rs. {{ number_format($order->items_sum_total ?? 0, 2) }}</td>
                                    <td>{{ $order->created_at?->diffForHumans() ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="package" class="icon-xs text-primary"></i>
                        Top Selling Items Today
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Chicken Momo</td>
                                    <td>45</td>
                                    <td>Rs. 6,750</td>
                                </tr>
                                <tr>
                                    <td>Veg Chowmein</td>
                                    <td>32</td>
                                    <td>Rs. 3,200</td>
                                </tr>
                                <tr>
                                    <td>Thukpa</td>
                                    <td>28</td>
                                    <td>Rs. 2,800</td>
                                </tr>
                                <tr>
                                    <td>Cold Drink</td>
                                    <td>24</td>
                                    <td>Rs. 1,200</td>
                                </tr>
                                <tr>
                                    <td>Fried Rice</td>
                                    <td>18</td>
                                    <td>Rs. 1,800</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fourth Row -->
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="archive" class="icon-xs text-primary"></i>
                        Stock Summary
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Total Items</span>
                            <strong>156</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>In Stock</span>
                            <strong class="text-success">142</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Low Stock</span>
                            <strong class="text-warning">8</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Out of Stock</span>
                            <strong class="text-danger">6</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Stock Value</span>
                            <strong>Rs. 1,25,450</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="shopping-cart" class="icon-xs text-primary"></i>
                        Purchase Overview
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Food Supplier Ltd</td>
                                    <td>Rs. 15,000</td>
                                    <td><span class="status-badge success">Paid</span></td>
                                </tr>
                                <tr>
                                    <td>Beverage Co</td>
                                    <td>Rs. 8,500</td>
                                    <td><span class="status-badge warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Vegetable Market</td>
                                    <td>Rs. 4,200</td>
                                    <td><span class="status-badge success">Paid</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="dollar-sign" class="icon-xs text-primary"></i>
                        Financial Overview
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Cash</span>
                            <strong>Rs. 45,000</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Bank</span>
                            <strong>Rs. 1,25,000</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>eSewa</span>
                            <strong>Rs. 28,500</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Card</span>
                            <strong>Rs. 18,200</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span>Receivable</span>
                            <strong class="text-danger">Rs. 5,500</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Trend Chart
        new Chart(document.getElementById('salesTrendChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($weekLabels),
                datasets: [{
                    label: 'Sales (Rs.)',
                    data: @json($weekRevenue),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rs.' + v.toLocaleString() }
                    }
                }
            }
        });

        // Order Type Chart
        new Chart(document.getElementById('orderTypeChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Dine In', 'Take Away', 'Delivery'],
                datasets: [{
                    data: [65, 20, 15],
                    backgroundColor: ['#16a34a', '#f59e0b', '#2563eb'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15, font: { size: 12 } }
                    } 
                },
                cutout: '65%',
            }
        });
    </script>
@endpush
