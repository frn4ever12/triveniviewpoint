@extends('admin.includes.main')

@section('title', 'Accounting Dashboard')

@push('styles')
    <style>
        .accounting-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
        }
        .accounting-card.cash { border-left-color: #16a34a; }
        .accounting-card.bank { border-left-color: #2563eb; }
        .accounting-card.receivable { border-left-color: #f59e0b; }
        .accounting-card.payable { border-left-color: #dc2626; }
        .accounting-card.income { border-left-color: #0891b2; }
        .accounting-card.expense { border-left-color: #7c3aed; }
        
        .accounting-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .accounting-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Accounting Dashboard</h4>
                <p class="text-muted mb-0">Financial management and reporting</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i data-feather="file-text" class="icon-xs me-1"></i> Journal Voucher
                </button>
                <button class="btn btn-outline-primary">
                    <i data-feather="pie-chart" class="icon-xs me-1"></i> P&L Report
                </button>
                <button class="btn btn-outline-primary">
                    <i data-feather="bar-chart-2" class="icon-xs me-1"></i> Balance Sheet
                </button>
                <button class="btn btn-primary">
                    <i data-feather="plus" class="icon-xs me-1"></i> New Entry
                </button>
            </div>
        </div>

        <!-- Financial Stats -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card cash">
                    <div class="accounting-label">Cash</div>
                    <div class="accounting-value">Rs. 45,000</div>
                    <small class="text-success">+ Rs. 5,000 today</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card bank">
                    <div class="accounting-label">Bank Balance</div>
                    <div class="accounting-value">Rs. 1,25,000</div>
                    <small class="text-success">+ Rs. 15,000 today</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card receivable">
                    <div class="accounting-label">Receivable</div>
                    <div class="accounting-value">Rs. 5,500</div>
                    <small class="text-warning">5 pending payments</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card payable">
                    <div class="accounting-label">Payable</div>
                    <div class="accounting-value">Rs. 13,500</div>
                    <small class="text-danger">3 suppliers pending</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card income">
                    <div class="accounting-label">Today's Income</div>
                    <div class="accounting-value">Rs. 58,750</div>
                    <small class="text-success">+12% vs yesterday</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="accounting-card expense">
                    <div class="accounting-label">Today's Expense</div>
                    <div class="accounting-value">Rs. 18,500</div>
                    <small class="text-warning">Within budget</small>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Payment Methods -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="activity" class="icon-xs text-primary"></i>
                        Recent Transactions
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Particulars</th>
                                    <th>Account</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Today, 2:30 PM</td>
                                    <td><span class="status-badge success">Sales</span></td>
                                    <td>Dine-in Sales - Table 08</td>
                                    <td>Cash</td>
                                    <td>-</td>
                                    <td class="text-success">Rs. 850</td>
                                    <td>Rs. 45,000</td>
                                </tr>
                                <tr>
                                    <td>Today, 1:15 PM</td>
                                    <td><span class="status-badge info">eSewa</span></td>
                                    <td>Delivery Order #1025</td>
                                    <td>eSewa</td>
                                    <td>-</td>
                                    <td class="text-success">Rs. 1,450</td>
                                    <td>Rs. 28,500</td>
                                </tr>
                                <tr>
                                    <td>Today, 11:00 AM</td>
                                    <td><span class="status-badge danger">Purchase</span></td>
                                    <td>Vegetables - Fresh Market</td>
                                    <td>Cash</td>
                                    <td class="text-danger">Rs. 4,200</td>
                                    <td>-</td>
                                    <td>Rs. 44,200</td>
                                </tr>
                                <tr>
                                    <td>Today, 10:30 AM</td>
                                    <td><span class="status-badge success">Sales</span></td>
                                    <td>Takeaway Order #1024</td>
                                    <td>Card</td>
                                    <td>-</td>
                                    <td class="text-success">Rs. 650</td>
                                    <td>Rs. 18,200</td>
                                </tr>
                                <tr>
                                    <td>Yesterday, 6:00 PM</td>
                                    <td><span class="status-badge warning">Expense</span></td>
                                    <td>Staff Salary - Ram</td>
                                    <td>Bank</td>
                                    <td class="text-danger">Rs. 15,000</td>
                                    <td>-</td>
                                    <td>Rs. 1,10,000</td>
                                </tr>
                                <tr>
                                    <td>Yesterday, 4:30 PM</td>
                                    <td><span class="status-badge success">Sales</span></td>
                                    <td>Dine-in Sales - Table 12</td>
                                    <td>Cash</td>
                                    <td>-</td>
                                    <td class="text-success">Rs. 1,200</td>
                                    <td>Rs. 40,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="credit-card" class="icon-xs text-primary"></i>
                        Payment Methods Breakdown
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cash</span>
                                <span class="fw-bold">Rs. 45,000</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 45%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>eSewa</span>
                                <span class="fw-bold">Rs. 28,500</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: 28%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Card</span>
                                <span class="fw-bold">Rs. 18,200</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: 18%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Khalti</span>
                                <span class="fw-bold">Rs. 8,500</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 8%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Bank Transfer</span>
                                <span class="fw-bold">Rs. 1,25,000</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" style="width: 1%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VAT Summary & Pending Payments -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="percent" class="icon-xs text-primary"></i>
                        VAT Summary (This Month)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Taxable Amount</th>
                                    <th>VAT Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Output VAT (Sales)</td>
                                    <td>Rs. 4,50,000</td>
                                    <td class="text-success">Rs. 58,500</td>
                                    <td><span class="status-badge success">Collected</span></td>
                                </tr>
                                <tr>
                                    <td>Input VAT (Purchases)</td>
                                    <td>Rs. 2,85,000</td>
                                    <td class="text-danger">Rs. 37,050</td>
                                    <td><span class="status-badge success">Claimed</span></td>
                                </tr>
                                <tr>
                                    <td>Net VAT Payable</td>
                                    <td>-</td>
                                    <td class="text-primary fw-bold">Rs. 21,450</td>
                                    <td><span class="status-badge warning">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="alert-circle" class="icon-xs text-warning"></i>
                        Pending Payments
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Party</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Beverage Co</td>
                                    <td><span class="status-badge warning">Payable</span></td>
                                    <td class="text-danger">Rs. 8,500</td>
                                    <td>2024-06-10</td>
                                    <td><button class="btn btn-sm btn-primary">Pay</button></td>
                                </tr>
                                <tr>
                                    <td>Dairy Farm</td>
                                    <td><span class="status-badge warning">Payable</span></td>
                                    <td class="text-danger">Rs. 5,000</td>
                                    <td>2024-06-12</td>
                                    <td><button class="btn btn-sm btn-primary">Pay</button></td>
                                </tr>
                                <tr>
                                    <td>Ram Sharma (Customer)</td>
                                    <td><span class="status-badge info">Receivable</span></td>
                                    <td class="text-success">Rs. 2,500</td>
                                    <td>2024-06-08</td>
                                    <td><button class="btn btn-sm btn-success">Collect</button></td>
                                </tr>
                                <tr>
                                    <td>Sita Devi (Customer)</td>
                                    <td><span class="status-badge info">Receivable</span></td>
                                    <td class="text-success">Rs. 3,000</td>
                                    <td>2024-06-15</td>
                                    <td><button class="btn btn-sm btn-success">Collect</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports Access -->
        <div class="row g-3">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="file-text" class="icon-xs text-primary"></i>
                        Quick Reports
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="bar-chart-2" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #2563eb;"></i>
                                    <h6 class="fw-bold">Trial Balance</h6>
                                    <small class="text-muted">Account balances</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="pie-chart" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #16a34a;"></i>
                                    <h6 class="fw-bold">Profit & Loss</h6>
                                    <small class="text-muted">Income & expenses</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="layers" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #f59e0b;"></i>
                                    <h6 class="fw-bold">Balance Sheet</h6>
                                    <small class="text-muted">Assets & liabilities</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="book" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #7c3aed;"></i>
                                    <h6 class="fw-bold">Cash Book</h6>
                                    <small class="text-muted">Cash transactions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="briefcase" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #0891b2;"></i>
                                    <h6 class="fw-bold">Bank Book</h6>
                                    <small class="text-muted">Bank transactions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="calendar" class="icon-xs mb-2" style="width: 32px; height: 32px; color: #dc2626;"></i>
                                    <h6 class="fw-bold">Day Book</h6>
                                    <small class="text-muted">Daily transactions</small>
                                </div>
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
