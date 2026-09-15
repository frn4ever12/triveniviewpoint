@extends('admin.includes.main')

@section('title', 'Financial Track Report')

@push('styles')
<style>
    .financial-stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }
    .financial-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }
    .financial-stat-card .card-body {
        padding: 1.5rem;
    }
    .financial-stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .financial-stat-card .stat-amount {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .financial-stat-card .stat-label {
        font-size: 0.82rem;
        font-weight: 500;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .recent-invoice-table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom-width: 2px;
    }
    .chart-container {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }
    .chart-container h5 {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
        color: #374151;
    }
    .filter-card {
        border: none;
        border-radius: 16px;
        background: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Financial Track" route="dashboard" button="Dashboard" icon="bi-arrow-left" />

    {{-- Month/Year Filter --}}
    <div class="filter-card shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('admin.reports.financial_track') }}" class="row g-3 align-items-end">
            <div class="col-md-4 col-sm-6">
                <label for="year" class="form-label fw-semibold small">Year</label>
                <select name="year" id="year" class="form-select form-select-sm">
                    @for ($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <label for="month" class="form-label fw-semibold small">Month</label>
                <select name="month" id="month" class="form-select form-select-sm">
                    @foreach (['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $val => $label)
                        <option value="{{ $val }}" {{ $month == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                <a href="{{ route('admin.reports.financial_track') }}" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="financial-stat-card shadow-sm text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-label">Revenue</div>
                        <div class="stat-icon bg-white bg-opacity-25">
                            <i class="bi bi-currency-rupee"></i>
                        </div>
                    </div>
                    <div class="stat-amount">Rs. {{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="financial-stat-card shadow-sm text-white" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-label">Expenses</div>
                        <div class="stat-icon bg-white bg-opacity-25">
                            <i class="bi bi-arrow-up-right-circle"></i>
                        </div>
                    </div>
                    <div class="stat-amount">Rs. {{ number_format($totalExpenses, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="financial-stat-card shadow-sm text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-label">Purchases</div>
                        <div class="stat-icon bg-white bg-opacity-25">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                    <div class="stat-amount">Rs. {{ number_format($totalPurchases, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="financial-stat-card shadow-sm text-white" style="background: linear-gradient(135deg, {{ $netProfit >= 0 ? '#059669 0%, #047857 100%' : '#6b7280 0%, #4b5563 100%' }});">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-label">{{ $netProfit >= 0 ? 'Net Profit' : 'Net Loss' }}</div>
                        <div class="stat-icon bg-white bg-opacity-25">
                            <i class="bi {{ $netProfit >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                        </div>
                    </div>
                    <div class="stat-amount">Rs. {{ number_format(abs($netProfit), 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-container shadow-sm">
                <h5><i class="bi bi-bar-chart-line me-2 text-primary"></i>Monthly Financial Trend ({{ $year }})</h5>
                <div style="height: 280px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container shadow-sm">
                <h5><i class="bi bi-pie-chart me-2 text-success"></i>Revenue by Payment Method</h5>
                <div style="height: 280px;">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Expense by Label --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="chart-container shadow-sm">
                <h5><i class="bi bi-tag me-2 text-danger"></i>Expense by Label</h5>
                <div style="height: 260px;">
                    <canvas id="expenseLabelChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-container shadow-sm">
                <h5><i class="bi bi-receipt me-2 text-warning"></i>Revenue vs Expenses vs Purchases</h5>
                <div style="height: 260px;">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Invoices Table --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-0 rounded-top-4 py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Recent Paid Invoices ({{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }})
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 recent-invoice-table">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Table</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Paid At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $inv)
                            <tr>
                                <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                                <td>{{ $inv->order?->table?->name ?? '—' }}</td>
                                <td class="fw-semibold text-success">Rs. {{ number_format($inv->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark text-capitalize">{{ $inv->payment_method ?? 'N/A' }}</span>
                                </td>
                                <td class="text-muted small">{{ $inv->paid_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No paid invoices found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    const COLORS = ['#2563eb', '#dc2626', '#f59e0b', '#059669', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'];

    // Monthly Trend Chart
    var trendEl = document.getElementById('trendChart');
    if (trendEl) {
        var labels = @json($monthlyTrend->pluck('period'));
        new Chart(trendEl.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: @json($monthlyTrend->pluck('revenue')),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyTrend->pluck('expense')),
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220,38,38,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Purchases',
                        data: @json($monthlyTrend->pluck('purchase')),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Revenue by Payment Method (Doughnut)
    var paymentEl = document.getElementById('paymentChart');
    if (paymentEl) {
        var pmLabels = @json($revenueByMethod->keys());
        var pmData = @json($revenueByMethod->values());
        new Chart(paymentEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: pmLabels.map(function(l) { return l ? l.charAt(0).toUpperCase() + l.slice(1) : 'Unknown'; }),
                datasets: [{
                    data: pmData,
                    backgroundColor: COLORS.slice(0, pmData.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } }
                }
            }
        });
    }

    // Expense by Label (Bar)
    var expenseEl = document.getElementById('expenseLabelChart');
    if (expenseEl) {
        var elLabels = @json($expenseByLabel->keys());
        var elData = @json($expenseByLabel->values());
        new Chart(expenseEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: elLabels,
                datasets: [{
                    label: 'Expense (Rs.)',
                    data: elData,
                    backgroundColor: COLORS.slice(0, elData.length),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // Revenue vs Expenses vs Purchases (Comparison Bar)
    var compEl = document.getElementById('comparisonChart');
    if (compEl) {
        new Chart(compEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}'],
                datasets: [
                    { label: 'Revenue', data: [{{ $totalRevenue }}], backgroundColor: '#2563eb', borderRadius: 6 },
                    { label: 'Expenses', data: [{{ $totalExpenses }}], backgroundColor: '#dc2626', borderRadius: 6 },
                    { label: 'Purchases', data: [{{ $totalPurchases }}], backgroundColor: '#f59e0b', borderRadius: 6 },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
})();
</script>
@endpush
