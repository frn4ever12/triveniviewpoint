@extends('admin.includes.main')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.reports.profit_loss_report') }}" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control"
                    value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.reports.profit_loss_report') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
    

    <h2><b>Sales Report by Menu</b></h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
            <tr class="text-center">
                <th>Menu</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @forelse($report as $row)
                <tr>
                    <td><strong>{{ $row->menu_name }}</strong></td>
                    <td class="text-center">{{ $row->total_quantity }}</td>
                    <td class="text-success fw-bold">Rs.{{ number_format($row->total_sales, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No sales data found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-dark">
            <tr class="text-center">
                <th>Total</th>
                <th></th>
                <th class="text-warning">Rs.{{ number_format($totalSales, 2) }}</th>
            </tr>
        </tfoot>
    </table>


    <div class="row mt-5">
        <div class="col-md-6">
            <h4>Sales Bar Graph</h4>
            <div style="height:300px;">
                <canvas id="salesBarChart"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <h4>Sales by Menu</h4>
            <div style="heigth:300px">
                <canvas id="salesDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    <h2 class="mt-5"><b>Expenses Report After Tax Deduction</b></h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
            <tr class="text-center">
                <th>Expense Label</th>
                <th>Total Expenses</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @forelse($expenses as $row)
                <tr>
                    <td>{{ $row->label_name ?? 'Uncategorized' }}</td>
                    <td>Rs.{{ number_format($row->total_expenses, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">No expense data found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-dark">
            <tr class="text-center">
                <th>Total</th>
                <th>Rs.{{ number_format($totalExpenses, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5">
        <div class="col-md-6">
            <h4>Expenses by Label</h4>
            <div style="height:300px;">
                <canvas id="expensesDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    <h2 class="mt-5"><b>Profit & Loss Summary</b></h2>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th>Category</th>
                <th>Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td><strong>Total Sales</strong></td>
                <td>Rs.{{ number_format($totalSales, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Expenses</strong></td>
                <td>Rs.{{ number_format($totalExpenses, 2) }}</td>
            </tr>
            <tr class="{{ $profitOrLoss >= 0 ? 'table-success' : 'table-danger' }}">
                <td><strong>{{ $profitOrLoss >= 0 ? 'Net Profit' : 'Net Loss' }}</strong></td>
                <td>Rs.{{ number_format($profitOrLoss, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-md-6">
            <h4>Sales vs Expenses</h4>
            <div style="height:300px;">
                <canvas id="profitLossChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
    {{-- Chart.js library --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // Sales Bar Chart
    const barCtx = document.getElementById('salesBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: @json($report->pluck('menu_name')),
            datasets: [{
                label: 'Total Sales',
                data: @json($report->pluck('total_sales')),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Doughnut chart
    const doughnutCtx = document.getElementById('salesDoughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: @json($report->pluck('menu_name')),
            datasets: [{
                label: 'Sales by Menu',
                data: @json($report->pluck('total_sales')),
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Expenses Doughnut Chart
    const expensesCtx = document.getElementById('expensesDoughnutChart').getContext('2d');
    new Chart(expensesCtx, {
        type: 'doughnut',
        data: {
            labels: @json($expenses->pluck('label_name')),
            datasets: [{
                label: 'Expenses by Label',
                data: @json($expenses->pluck('total_expenses')),
                backgroundColor: ['#17a2b8', '#fd7e14', '#6f42c1', '#20c997', '#e83e8c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const profitLossCtx = document.getElementById('profitLossChart').getContext('2d');
    new Chart(profitLossCtx, {
        type: 'bar',
        data: {
            labels: ['Sales', 'Expenses'],
            datasets: [{
                label: 'Amount',
                data: [{{ $totalSales }}, {{ $totalExpenses }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    </script>
@endpush


