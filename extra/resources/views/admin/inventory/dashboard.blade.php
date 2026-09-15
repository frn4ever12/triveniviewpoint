@extends('admin.includes.main')

@section('title', 'Inventory Dashboard')

@push('styles')
    <style>
        .inventory-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
        }
        .inventory-card.total { border-left-color: #2563eb; }
        .inventory-card.in-stock { border-left-color: #16a34a; }
        .inventory-card.low-stock { border-left-color: #f59e0b; }
        .inventory-card.out-of-stock { border-left-color: #dc2626; }
        .inventory-card.expired { border-left-color: #7c3aed; }
        .inventory-card.value { border-left-color: #0891b2; }
        
        .inventory-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .inventory-label {
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
        
        .stock-level {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .stock-level-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s;
        }
        
        .stock-level-fill.high { background: #16a34a; }
        .stock-level-fill.medium { background: #f59e0b; }
        .stock-level-fill.low { background: #dc2626; }
        
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
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Inventory Dashboard</h4>
                <p class="text-muted mb-0">Stock management and tracking</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                    <i data-feather="package" class="icon-xs me-1"></i> Products
                </a>
                <a href="{{ route('admin.stock-adjustments.index') }}" class="btn btn-outline-primary">
                    <i data-feather="edit-3" class="icon-xs me-1"></i> Adjustments
                </a>
                <a href="{{ route('admin.wastages.index') }}" class="btn btn-outline-primary">
                    <i data-feather="trash-2" class="icon-xs me-1"></i> Wastage
                </a>
                <a href="{{ route('admin.recipes.index') }}" class="btn btn-outline-primary">
                    <i data-feather="book-open" class="icon-xs me-1"></i> Recipes
                </a>
                <button class="btn btn-primary">
                    <i data-feather="plus" class="icon-xs me-1"></i> New Stock Entry
                </button>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card total">
                    <div class="inventory-label">Total Items</div>
                    <div class="inventory-value">156</div>
                    <small class="text-muted">Active products</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card in-stock">
                    <div class="inventory-label">In Stock</div>
                    <div class="inventory-value">142</div>
                    <small class="text-success">91% of total</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card low-stock">
                    <div class="inventory-label">Low Stock</div>
                    <div class="inventory-value">8</div>
                    <small class="text-warning">Below reorder level</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card out-of-stock">
                    <div class="inventory-label">Out of Stock</div>
                    <div class="inventory-value">6</div>
                    <small class="text-danger">Need to order</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card expired">
                    <div class="inventory-label">Expired</div>
                    <div class="inventory-value">3</div>
                    <small class="text-danger">Remove items</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="inventory-card value">
                    <div class="inventory-label">Stock Value</div>
                    <div class="inventory-value">Rs. 1.25L</div>
                    <small class="text-muted">Total inventory</small>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert & Recent Movements -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="alert-triangle" class="icon-xs text-warning"></i>
                        Low Stock Alert
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Current</th>
                                    <th>Reorder</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Chicken Momo</td>
                                    <td class="text-danger">2.5 kg</td>
                                    <td>5 kg</td>
                                    <td><span class="status-badge danger">Critical</span></td>
                                    <td><button class="btn btn-sm btn-primary">Order</button></td>
                                </tr>
                                <tr>
                                    <td>Chowmein Noodles</td>
                                    <td class="text-warning">3 kg</td>
                                    <td>5 kg</td>
                                    <td><span class="status-badge warning">Low</span></td>
                                    <td><button class="btn btn-sm btn-primary">Order</button></td>
                                </tr>
                                <tr>
                                    <td>Cold Drinks</td>
                                    <td class="text-danger">8 bottles</td>
                                    <td>24 bottles</td>
                                    <td><span class="status-badge danger">Critical</span></td>
                                    <td><button class="btn btn-sm btn-primary">Order</button></td>
                                </tr>
                                <tr>
                                    <td>Onion</td>
                                    <td class="text-warning">4 kg</td>
                                    <td>10 kg</td>
                                    <td><span class="status-badge warning">Low</span></td>
                                    <td><button class="btn btn-sm btn-primary">Order</button></td>
                                </tr>
                                <tr>
                                    <td>Chicken Breast</td>
                                    <td class="text-warning">3 kg</td>
                                    <td>8 kg</td>
                                    <td><span class="status-badge warning">Low</span></td>
                                    <td><button class="btn btn-sm btn-primary">Order</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="activity" class="icon-xs text-primary"></i>
                        Recent Stock Movements
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Today, 10:30 AM</td>
                                    <td>Chicken Momo</td>
                                    <td><span class="status-badge success">IN</span></td>
                                    <td>+10 kg</td>
                                    <td>PUR-1025</td>
                                </tr>
                                <tr>
                                    <td>Today, 09:15 AM</td>
                                    <td>Vegetables</td>
                                    <td><span class="status-badge success">IN</span></td>
                                    <td>+15 kg</td>
                                    <td>PUR-1024</td>
                                </tr>
                                <tr>
                                    <td>Yesterday, 08:00 PM</td>
                                    <td>Chicken Momo</td>
                                    <td><span class="status-badge danger">OUT</span></td>
                                    <td>-5 kg</td>
                                    <td>Recipe Usage</td>
                                </tr>
                                <tr>
                                    <td>Yesterday, 06:30 PM</td>
                                    <td>Cold Drinks</td>
                                    <td><span class="status-badge warning">WASTE</span></td>
                                    <td>-2 bottles</td>
                                    <td>WAS-1023</td>
                                </tr>
                                <tr>
                                    <td>Yesterday, 02:00 PM</td>
                                    <td>Chowmein</td>
                                    <td><span class="status-badge info">ADJUST</span></td>
                                    <td>-1 kg</td>
                                    <td>ADJ-1022</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock by Category & Expiry Tracking -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="pie-chart" class="icon-xs text-primary"></i>
                        Stock by Category
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Meat & Poultry</span>
                                <span class="fw-bold">Rs. 45,000</span>
                            </div>
                            <div class="stock-level">
                                <div class="stock-level-fill high" style="width: 75%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Vegetables</span>
                                <span class="fw-bold">Rs. 18,500</span>
                            </div>
                            <div class="stock-level">
                                <div class="stock-level-fill medium" style="width: 45%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Dairy</span>
                                <span class="fw-bold">Rs. 22,000</span>
                            </div>
                            <div class="stock-level">
                                <div class="stock-level-fill high" style="width: 60%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Beverages</span>
                                <span class="fw-bold">Rs. 28,000</span>
                            </div>
                            <div class="stock-level">
                                <div class="stock-level-fill low" style="width: 25%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Dry Goods</span>
                                <span class="fw-bold">Rs. 11,500</span>
                            </div>
                            <div class="stock-level">
                                <div class="stock-level-fill high" style="width: 80%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="clock" class="icon-xs text-primary"></i>
                        Expiry Tracking
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Batch</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dairy Cream</td>
                                    <td>BAT-001</td>
                                    <td>2024-06-10</td>
                                    <td class="text-danger">2</td>
                                    <td>2 L</td>
                                    <td><button class="btn btn-sm btn-warning">Use First</button></td>
                                </tr>
                                <tr>
                                    <td>Chicken</td>
                                    <td>BAT-002</td>
                                    <td>2024-06-12</td>
                                    <td class="text-warning">4</td>
                                    <td>5 kg</td>
                                    <td><button class="btn btn-sm btn-warning">Use First</button></td>
                                </tr>
                                <tr>
                                    <td>Vegetables</td>
                                    <td>BAT-003</td>
                                    <td>2024-06-14</td>
                                    <td class="text-warning">6</td>
                                    <td>8 kg</td>
                                    <td><button class="btn btn-sm btn-secondary">Monitor</button></td>
                                </tr>
                                <tr>
                                    <td>Sauce</td>
                                    <td>BAT-004</td>
                                    <td>2024-06-20</td>
                                    <td class="text-success">12</td>
                                    <td>10 bottles</td>
                                    <td><button class="btn btn-sm btn-secondary">OK</button></td>
                                </tr>
                                <tr>
                                    <td>Spices</td>
                                    <td>BAT-005</td>
                                    <td>2024-12-01</td>
                                    <td class="text-success">180</td>
                                    <td>5 kg</td>
                                    <td><button class="btn btn-sm btn-secondary">OK</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Consuming Recipes & Purchase Overview -->
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="trending-up" class="icon-xs text-primary"></i>
                        Top Consuming Recipes (This Week)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Recipe</th>
                                    <th>Items Used</th>
                                    <th>Total Cost</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Chicken Momo</td>
                                    <td>12 items</td>
                                    <td>Rs. 4,500</td>
                                    <td>45</td>
                                </tr>
                                <tr>
                                    <td>Chicken Chowmein</td>
                                    <td>8 items</td>
                                    <td>Rs. 3,200</td>
                                    <td>32</td>
                                </tr>
                                <tr>
                                    <td>Chicken Thukpa</td>
                                    <td>10 items</td>
                                    <td>Rs. 2,800</td>
                                    <td>28</td>
                                </tr>
                                <tr>
                                    <td>Veg Chowmein</td>
                                    <td>6 items</td>
                                    <td>Rs. 1,800</td>
                                    <td>24</td>
                                </tr>
                                <tr>
                                    <td>Chicken Curry</td>
                                    <td>9 items</td>
                                    <td>Rs. 2,500</td>
                                    <td>18</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="shopping-cart" class="icon-xs text-primary"></i>
                        Purchase Overview (This Month)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Purchases</th>
                                    <th>Total Amount</th>
                                    <th>Pending</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Food Supplier Ltd</td>
                                    <td>5</td>
                                    <td>Rs. 45,000</td>
                                    <td>Rs. 0</td>
                                    <td><span class="status-badge success">All Paid</span></td>
                                </tr>
                                <tr>
                                    <td>Beverage Co</td>
                                    <td>3</td>
                                    <td>Rs. 28,500</td>
                                    <td>Rs. 8,500</td>
                                    <td><span class="status-badge warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Vegetable Market</td>
                                    <td>8</td>
                                    <td>Rs. 18,200</td>
                                    <td>Rs. 0</td>
                                    <td><span class="status-badge success">All Paid</span></td>
                                </tr>
                                <tr>
                                    <td>Dairy Farm</td>
                                    <td>4</td>
                                    <td>Rs. 22,000</td>
                                    <td>Rs. 5,000</td>
                                    <td><span class="status-badge warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Dry Goods Store</td>
                                    <td>2</td>
                                    <td>Rs. 11,500</td>
                                    <td>Rs. 0</td>
                                    <td><span class="status-badge success">All Paid</span></td>
                                </tr>
                            </tbody>
                        </table>
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
