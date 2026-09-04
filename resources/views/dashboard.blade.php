@extends('admin.includes.main')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('styles')
    <style>
        .dash-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            transition: box-shadow .2s, transform .2s;
            height: 100%;
        }
        .dash-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,.06);
            transform: translateY(-2px);
        }
        .dash-card .dash-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .dash-card .dash-value {
            font-family: 'Inter', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.02em;
        }
        .dash-card .dash-label {
            font-size: .82rem;
            color: #64748b;
            font-weight: 500;
        }
        .dash-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }
        .table-dash th {
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: .6rem .75rem;
        }
        .table-dash td {
            font-size: .82rem;
            padding: .6rem .75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .table-dash tbody tr:hover {
            background: #f8fafc;
        }
        .table-dash tbody tr:last-child td {
            border-bottom: none;
        }
        .dash-chart-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem;
            height: 100%;
        }
        .dash-chart-box h5 {
            font-size: .9rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .chart-container-inner {
            position: relative;
            height: 260px;
        }
        .dash-quick-btn {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1.1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #334155;
            font-weight: 500;
            font-size: .85rem;
            transition: all .15s;
            width: 100%;
            text-decoration: none;
        }
        .dash-quick-btn:hover {
            border-color: #dc2626;
            color: #dc2626;
            background: #fef2f2;
            transform: translateY(-1px);
        }
        .dash-quick-btn .q-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        @media (max-width: 575px) {
            .dash-card .dash-value {
                font-size: 1.4rem;
            }
            .chart-container-inner {
                height: 200px;
            }
        }
        .btn-icon-only {
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 4px;
            background: #e2e8f0;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: visible;
            flex-shrink: 0;
            min-width: 24px;
            min-height: 24px;
        }
        .btn-icon-only:hover {
            background: #dc2626;
            color: #fff;
            transform: scale(1.1);
        }
        .btn-icon-only::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 5px;
            background: #1e293b;
            color: #fff;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 100;
        }
        .btn-icon-only:hover::after {
            opacity: 1;
        }
        .btn-icon-only.btn-cancel:hover {
            background: #dc2626;
        }
        .btn-icon-only i {
            font-size: 11px;
            display: block;
        }
        .table-dash {
            width: 100%;
            table-layout: auto;
        }
        .table-dash th,
        .table-dash td {
            padding: 0.5rem 0.6rem;
        }
        .table-dash th:last-child,
        .table-dash td:last-child {
            white-space: nowrap;
            padding: 0.5rem 0.4rem;
            overflow: visible;
            min-width: 200px;
        }
        .d-flex.gap-1 {
            gap: 8px !important;
            justify-content: flex-start;
            flex-wrap: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
        .dash-chart-box {
            overflow-x: auto;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Dashboard</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Welcome back! Here's the overview.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.pos') }}" class="btn btn-danger btn-sm rounded-3 px-3" target="_blank">
                    <i class="bi bi-cart3 me-1"></i> POS
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">
                    <i class="bi bi-plus-lg me-1"></i> New Order
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="dash-card d-flex align-items-center gap-3" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#todaysRevenueModal">
                    <div class="dash-icon" style="background:#ecfdf5;color:#16a34a;">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="dash-value" style="color:#16a34a;">Rs. {{ number_format($todaysRevenue, 2) }}</div>
                        <div class="dash-label">Today's Revenue</div>
                        <small class="{{ $revenueChange >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.72rem;">
                            <i class="bi bi-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($revenueChange), 1) }}% vs yesterday
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="dash-card d-flex align-items-center gap-3">
                    <div class="dash-icon" style="background:#fef2f2;color:#dc2626;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="dash-value" style="color:#dc2626;">{{ $ordersToday }}</div>
                        <div class="dash-label">Orders Today</div>
                        <small class="{{ $ordersChange >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.72rem;">
                            <i class="bi bi-arrow-{{ $ordersChange >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($ordersChange) }}% vs yesterday
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="dash-card d-flex align-items-center gap-3">
                    <div class="dash-icon" style="background:#fffbeb;color:#d97706;">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="dash-value" style="color:#d97706;">{{ $occupiedTables }}/{{ $totalTables }}</div>
                        <div class="dash-label">Tables Occupied</div>
                        <small class="text-warning" style="font-size:.72rem;">
                            <i class="bi bi-clock"></i> {{ $occupancyPercent }}% occupancy
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="dash-chart-box">
                    <h5><i class="bi bi-graph-up me-2" style="color:#dc2626;"></i> Revenue Trends</h5>
                    <div class="chart-container-inner">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash-chart-box">
                    <h5><i class="bi bi-pie-chart me-2" style="color:#dc2626;"></i> Order Status</h5>
                    <div class="chart-container-inner">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="dash-chart-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0"><i class="bi bi-grid-3x3-gap me-2" style="color:#dc2626;"></i> Table Status</h5>
                        <a href="{{ route('admin.tables.index') }}" class="btn btn-sm btn-outline-danger rounded-3">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table-dash w-100">
                            <thead>
                                <tr><th>Table</th><th>Status</th><th>Capacity</th><th>Updated</th></tr>
                            </thead>
                            <tbody>
                            @foreach($latestTables as $table)
                                <tr>
                                    <td><strong>{{ $table->name }}</strong></td>
                                    <td>
                                        @php
                                            $sc = match($table->status->value) {
                                                'available' => 'bg-success text-white',
                                                'occupied' => 'bg-danger text-white',
                                                'reserved' => 'bg-warning text-dark',
                                                default => 'bg-secondary text-white',
                                            };
                                        @endphp
                                        <span class="dash-badge {{ $sc }}">{{ ucfirst($table->status->value) }}</span>
                                    </td>
                                    <td>{{ $table->capacity ?? '-' }}</td>
                                    <td style="font-size:.78rem;color:#94a3b8;">{{ $table->updated_at ? $table->updated_at->diffForHumans() : '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dash-chart-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2" style="color:#dc2626;"></i> Recent Orders</h5>
                        <a href="{{ route('admin.orders.details') }}" class="btn btn-sm btn-outline-danger rounded-3">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table-dash w-100">
                            <thead>
                                <tr><th>Order #</th><th>Table</th><th>Status</th><th>Total</th><th>Time</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_no }}</strong></td>
                                        <td>{{ $order->table->name ?? '-' }}</td>
                                        <td>
                                            @php
                                                $sc2 = match($order->status->value) {
                                                    'pending' => 'bg-warning text-dark',
                                                    'preparing' => 'bg-info text-white',
                                                    'ready' => 'bg-success text-white',
                                                    'served' => 'bg-secondary text-white',
                                                    'completed' => 'bg-primary text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="dash-badge {{ $sc2 }}">{{ ucfirst($order->status->value) }}</span>
                                        </td>
                                        <td>Rs. {{ number_format($order->items_sum_total, 2) }}</td>
                                        <td style="font-size:.78rem;color:#94a3b8;">{{ $order->created_at ? $order->created_at->diffForHumans() : '' }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn-icon-only" title="Add Order" data-bs-toggle="tooltip">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                                <button class="btn-icon-only" title="Print Order" data-bs-toggle="tooltip">
                                                    <i class="bi bi-printer"></i>
                                                </button>
                                                <button class="btn-icon-only" title="Checkout" data-bs-toggle="tooltip">
                                                    <i class="bi bi-cart-check"></i>
                                                </button>
                                                <button class="btn-icon-only btn-cancel" title="Cancel" data-bs-toggle="tooltip">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="dash-chart-box">
                    <h5><i class="bi bi-lightning me-2" style="color:#dc2626;"></i> Quick Actions</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.orders.index') }}" class="dash-quick-btn">
                            <span class="q-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-plus-lg"></i></span>
                            New Order
                        </a>
                        <a href="{{ route('admin.tables.index') }}" class="dash-quick-btn">
                            <span class="q-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-grid-3x3-gap"></i></span>
                            Manage Tables
                        </a>
                        <a href="{{ route('admin.menu-items.index') }}" class="dash-quick-btn">
                            <span class="q-icon" style="background:#ecfdf5;color:#16a34a;"><i class="bi bi-menu-button-wide"></i></span>
                            Update Menu
                        </a>
                        <a href="{{ route('admin.kitchen-display.index') }}" class="dash-quick-btn">
                            <span class="q-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-fire"></i></span>
                            Kitchen Display
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="todaysRevenueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px;border:none;">
                <div class="modal-header" style="background:#16a34a;color:#fff;border-radius:14px 14px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-currency-rupee me-1"></i> Today's Ordered Dishes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Dish</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="revenueTableBody">
                                <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <strong>Total Revenue: <span id="modalTotalRevenue" class="text-success">Rs. 0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($weekLabels),
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: @json($weekRevenue),
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: .4,
                    pointBackgroundColor: '#dc2626',
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rs.' + v }
                    }
                }
            }
        });

        const statusCounts = @json($statusCounts);
        new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Preparing', 'Ready', 'Served'],
                datasets: [{
                    data: [
                        statusCounts.pending || 0,
                        statusCounts.preparing || 0,
                        statusCounts.ready || 0,
                        statusCounts.served || 0,
                    ],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e', '#64748b'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } },
                cutout: '60%',
            }
        });

        const revModal = document.getElementById('todaysRevenueModal');
        revModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('revenueTableBody');
            const totalEl = document.getElementById('modalTotalRevenue');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>';
            totalEl.textContent = 'Rs. 0.00';
            fetch("{{ route('admin.revenue.today-dishes') }}")
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        tbody.innerHTML = d.dishes.map(dish => `
                            <tr>
                                <td>${dish.dish_name}</td>
                                <td>${dish.total_quantity}</td>
                                <td>Rs. ${Number(dish.unit_price).toLocaleString('en-NP', {minimumFractionDigits:2})}</td>
                                <td><strong class="text-success">Rs. ${Number(dish.total_amount).toLocaleString('en-NP', {minimumFractionDigits:2})}</strong></td>
                            </tr>
                        `).join('');
                        totalEl.textContent = 'Rs. ' + Number(d.total_revenue).toLocaleString('en-NP', {minimumFractionDigits:2});
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Failed to load</td></tr>';
                    }
                })
                .catch(() => {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading</td></tr>';
                });
        });
    </script>
@endpush
