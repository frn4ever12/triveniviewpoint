@extends('admin.includes.main')

@section('title', 'Reports Dashboard')

@push('styles')
    <style>
        .report-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        
        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            border-color: #dc2626;
        }
        
        .report-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .report-icon.sales { background: #dcfce7; color: #16a34a; }
        .report-icon.inventory { background: #dbeafe; color: #2563eb; }
        .report-icon.accounting { background: #fef3c7; color: #d97706; }
        .report-icon.tax { background: #ede9fe; color: #7c3aed; }
        .report-icon.purchase { background: #cffafe; color: #0891b2; }
        .report-icon.financial { background: #fee2e2; color: #dc2626; }
        
        .report-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .report-description {
            font-size: 0.8rem;
            color: #64748b;
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
        
        .report-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .report-list-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .report-list-item:hover {
            background: #f8fafc;
            padding-left: 1rem;
        }
        
        .report-list-item:last-child {
            border-bottom: none;
        }
        
        .report-list-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        .report-list-name {
            flex: 1;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .report-list-action {
            color: #dc2626;
            font-size: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Reports Dashboard</h4>
                <p class="text-muted mb-0">Comprehensive business reports and analytics</p>
            </div>
            <div class="d-flex gap-2">
                <input type="date" class="form-control" style="width: 150px;">
                <input type="date" class="form-control" style="width: 150px;">
                <button class="btn btn-primary">
                    <i data-feather="filter" class="icon-xs me-1"></i> Apply Filter
                </button>
                <button class="btn btn-outline-primary">
                    <i data-feather="download" class="icon-xs me-1"></i> Export
                </button>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('sales')">
                    <div class="report-icon sales">
                        <i data-feather="trending-up"></i>
                    </div>
                    <div class="report-title">Sales Reports</div>
                    <div class="report-description">Daily, monthly, product, category sales</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('inventory')">
                    <div class="report-icon inventory">
                        <i data-feather="package"></i>
                    </div>
                    <div class="report-title">Inventory Reports</div>
                    <div class="report-description">Stock, ledger, valuation, wastage</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('accounting')">
                    <div class="report-icon accounting">
                        <i data-feather="pie-chart"></i>
                    </div>
                    <div class="report-title">Accounting Reports</div>
                    <div class="report-description">P&L, Balance Sheet, Trial Balance</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('tax')">
                    <div class="report-icon tax">
                        <i data-feather="percent"></i>
                    </div>
                    <div class="report-title">Tax Reports</div>
                    <div class="report-description">VAT, Input/Output VAT, Tax Summary</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('purchase')">
                    <div class="report-icon purchase">
                        <i data-feather="shopping-cart"></i>
                    </div>
                    <div class="report-title">Purchase Reports</div>
                    <div class="report-description">Supplier, returns, outstanding</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="report-card" onclick="showReportSection('financial')">
                    <div class="report-icon financial">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    <div class="report-title">Financial Reports</div>
                    <div class="report-description">Cash Book, Bank Book, Day Book</div>
                </div>
            </div>
        </div>

        <!-- Sales Reports Section -->
        <div class="row g-3 mb-4" id="sales-reports">
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="trending-up" class="icon-xs text-success"></i>
                        Sales Reports
                    </div>
                    <ul class="report-list">
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Daily Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Monthly Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="package" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Product Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="layers" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Category Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="users" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Dine-In Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="shopping-bag" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Takeaway Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="truck" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Delivery Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="percent" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Discount Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="rotate-ccw" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Refund Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="x-circle" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Cancelled Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="package" class="icon-xs text-primary"></i>
                        Inventory Reports
                    </div>
                    <ul class="report-list">
                        <li class="report-list-item">
                            <div class="report-list-icon bg-primary text-white">
                                <i data-feather="archive" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Stock Summary</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-primary text-white">
                                <i data-feather="list" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Stock Ledger</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-primary text-white">
                                <i data-feather="dollar-sign" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Stock Valuation</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="alert-triangle" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Low Stock Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="trash-2" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Wastage Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="clock" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Expired Items Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="clock" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Near Expiry Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-primary text-white">
                                <i data-feather="book-open" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Recipe Consumption Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="pie-chart" class="icon-xs text-warning"></i>
                        Accounting Reports
                    </div>
                    <ul class="report-list">
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="bar-chart-2" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Trial Balance</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="pie-chart" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Profit & Loss Statement</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="layers" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Balance Sheet</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="book" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">General Ledger</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="dollar-sign" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Cash Book</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-primary text-white">
                                <i data-feather="briefcase" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Bank Book</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-info text-white">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Day Book</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-success text-white">
                                <i data-feather="arrow-up" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Receivable Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="arrow-down" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Payable Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tax & Purchase Reports -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="percent" class="icon-xs text-purple"></i>
                        Tax Reports
                    </div>
                    <ul class="report-list">
                        <li class="report-list-item">
                            <div class="report-list-icon bg-purple text-white">
                                <i data-feather="trending-up" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">VAT Sales Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-purple text-white">
                                <i data-feather="shopping-cart" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">VAT Purchase Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-purple text-white">
                                <i data-feather="arrow-down" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Input VAT Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-purple text-white">
                                <i data-feather="arrow-up" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Output VAT Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-purple text-white">
                                <i data-feather="file-text" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">VAT Summary Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="shopping-cart" class="icon-xs text-info"></i>
                        Purchase Reports
                    </div>
                    <ul class="report-list">
                        <li class="report-list-item">
                            <div class="report-list-icon bg-info text-white">
                                <i data-feather="file-text" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Purchase Summary Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-info text-white">
                                <i data-feather="users" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Supplier Purchase Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-warning text-white">
                                <i data-feather="rotate-ccw" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Purchase Return Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                        <li class="report-list-item">
                            <div class="report-list-icon bg-danger text-white">
                                <i data-feather="alert-circle" class="icon-xs"></i>
                            </div>
                            <div class="report-list-name">Outstanding Purchase Report</div>
                            <div class="report-list-action">View →</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-3">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="bar-chart" class="icon-xs text-primary"></i>
                        Report Summary (This Month)
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 12.5L</div>
                                <small class="text-muted">Total Sales</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 1.25L</div>
                                <small class="text-muted">Stock Value</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1 text-success">Rs. 3.75L</div>
                                <small class="text-muted">Net Profit</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 58,500</div>
                                <small class="text-muted">VAT Collected</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 37,050</div>
                                <small class="text-muted">VAT Claimed</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1 text-warning">Rs. 13,500</div>
                                <small class="text-muted">Pending Payables</small>
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
        
        function showReportSection(section) {
            // Scroll to the relevant section
            const element = document.getElementById(section + '-reports');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
@endpush
