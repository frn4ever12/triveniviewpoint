@extends('admin.includes.main')

@section('title', 'Food Cost & Recipes')

@push('styles')
    <style>
        .recipe-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #dc2626;
        }
        
        .recipe-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        
        .recipe-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .recipe-menu-item {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .cost-breakdown {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .cost-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .cost-row:last-child {
            border-bottom: none;
        }
        
        .cost-row.grand-total {
            font-size: 1.1rem;
            font-weight: 700;
            color: #dc2626;
            padding-top: 0.75rem;
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
        }
        
        .ingredient-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        
        .ingredient-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #dc2626;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .ingredient-details {
            flex: 1;
        }
        
        .ingredient-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .ingredient-qty {
            font-size: 0.8rem;
            color: #64748b;
        }
        
        .ingredient-cost {
            font-weight: 700;
            color: #dc2626;
        }
        
        .profit-card {
            background: #dcfce7;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .profit-margin {
            font-size: 2rem;
            font-weight: 700;
            color: #16a34a;
        }
        
        .profit-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
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
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Food Cost & Recipes</h4>
                <p class="text-muted mb-0">Recipe costing and ingredient management</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.recipes.index') }}" class="btn btn-outline-primary">
                    <i data-feather="list" class="icon-xs me-1"></i> All Recipes
                </a>
                <button class="btn btn-primary">
                    <i data-feather="plus" class="icon-xs me-1"></i> New Recipe
                </button>
            </div>
        </div>

        <!-- Recipe Detail -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="recipe-card">
                    <div class="recipe-header">
                        <div>
                            <div class="recipe-name">Chicken Momo</div>
                            <div class="recipe-menu-item">Menu Item: Chicken Momo (Rs. 150)</div>
                        </div>
                        <div class="profit-card">
                            <div class="profit-margin">65%</div>
                            <div class="profit-label">Profit Margin</div>
                        </div>
                    </div>
                    
                    <!-- Ingredients -->
                    <h6 class="fw-bold mb-3">Ingredients Breakdown</h6>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🥟</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Flour (Maida)</div>
                            <div class="ingredient-qty">100g @ Rs. 0.80/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 80.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🍗</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Chicken Minced</div>
                            <div class="ingredient-qty">150g @ Rs. 0.60/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 90.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🧅</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Onion</div>
                            <div class="ingredient-qty">30g @ Rs. 0.40/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 12.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🧄</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Garlic</div>
                            <div class="ingredient-qty">10g @ Rs. 0.50/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 5.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🫚</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Ginger</div>
                            <div class="ingredient-qty">10g @ Rs. 0.60/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 6.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🌶️</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Spices</div>
                            <div class="ingredient-qty">5g @ Rs. 0.80/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 4.00</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🫗</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Oil</div>
                            <div class="ingredient-qty">10ml @ Rs. 0.15/ml</div>
                        </div>
                        <div class="ingredient-cost">Rs. 1.50</div>
                    </div>
                    
                    <div class="ingredient-item">
                        <div class="ingredient-icon">🥬</div>
                        <div class="ingredient-details">
                            <div class="ingredient-name">Coriander</div>
                            <div class="ingredient-qty">5g @ Rs. 0.40/g</div>
                        </div>
                        <div class="ingredient-cost">Rs. 2.00</div>
                    </div>
                    
                    <!-- Cost Breakdown -->
                    <div class="cost-breakdown mt-4">
                        <h6 class="fw-bold mb-3">Cost Analysis</h6>
                        <div class="cost-row">
                            <span>Ingredient Cost</span>
                            <span>Rs. 200.00</span>
                        </div>
                        <div class="cost-row">
                            <span>Wastage (5%)</span>
                            <span>Rs. 10.00</span>
                        </div>
                        <div class="cost-row">
                            <span>Labor Cost</span>
                            <span>Rs. 15.00</span>
                        </div>
                        <div class="cost-row">
                            <span>Overhead</span>
                            <span>Rs. 10.00</span>
                        </div>
                        <div class="cost-row grand-total">
                            <span>Total Cost Per Plate</span>
                            <span>Rs. 52.50</span>
                        </div>
                    </div>
                    
                    <!-- Profit Analysis -->
                    <div class="row g-3 mt-3">
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 52.50</div>
                                <small class="text-muted">Cost/Plate</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1">Rs. 150.00</div>
                                <small class="text-muted">Selling Price</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h5 mb-1 text-success">Rs. 97.50</div>
                                <small class="text-muted">Gross Profit</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Recipe Stats</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Yield</span>
                            <strong>10 plates</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Prep Time</span>
                            <strong>20 mins</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Cook Time</span>
                            <strong>15 mins</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Last Updated</span>
                            <strong>2 days ago</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Actions</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary">
                                <i data-feather="edit-3" class="icon-xs me-1"></i> Edit Recipe
                            </button>
                            <button class="btn btn-outline-success">
                                <i data-feather="copy" class="icon-xs me-1"></i> Duplicate Recipe
                            </button>
                            <button class="btn btn-outline-info">
                                <i data-feather="printer" class="icon-xs me-1"></i> Print Recipe
                            </button>
                            <button class="btn btn-outline-warning">
                                <i data-feather="trending-up" class="icon-xs me-1"></i> Cost History
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Related Recipes -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Related Recipes</h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span>Veg Momo</span>
                                    <span class="text-success">68%</span>
                                </div>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span>Buff Момо</span>
                                    <span class="text-success">62%</span>
                                </div>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span>C-Momo (Spicy)</span>
                                    <span class="text-warning">58%</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Recipes Overview -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Recipes</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search recipes..." style="width: 200px;">
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>All Categories</option>
                            <option>Momo</option>
                            <option>Chowmein</option>
                            <option>Curry</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Recipe</th>
                                <th>Menu Item</th>
                                <th>Cost/Plate</th>
                                <th>Selling Price</th>
                                <th>Gross Profit</th>
                                <th>Margin</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Chicken Momo</strong></td>
                                <td>Chicken Momo</td>
                                <td>Rs. 52.50</td>
                                <td>Rs. 150.00</td>
                                <td class="text-success">Rs. 97.50</td>
                                <td><span class="status-badge success">65%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Veg Momo</strong></td>
                                <td>Veg Momo</td>
                                <td>Rs. 38.00</td>
                                <td>Rs. 120.00</td>
                                <td class="text-success">Rs. 82.00</td>
                                <td><span class="status-badge success">68%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Chicken Chowmein</strong></td>
                                <td>Chicken Chowmein</td>
                                <td>Rs. 65.00</td>
                                <td>Rs. 180.00</td>
                                <td class="text-success">Rs. 115.00</td>
                                <td><span class="status-badge success">64%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Veg Chowmein</strong></td>
                                <td>Veg Chowmein</td>
                                <td>Rs. 45.00</td>
                                <td>Rs. 140.00</td>
                                <td class="text-success">Rs. 95.00</td>
                                <td><span class="status-badge success">68%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Chicken Thukpa</strong></td>
                                <td>Chicken Thukpa</td>
                                <td>Rs. 72.00</td>
                                <td>Rs. 200.00</td>
                                <td class="text-success">Rs. 128.00</td>
                                <td><span class="status-badge success">64%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Chicken Curry</strong></td>
                                <td>Chicken Curry</td>
                                <td>Rs. 85.00</td>
                                <td>Rs. 220.00</td>
                                <td class="text-success">Rs. 135.00</td>
                                <td><span class="status-badge warning">61%</span></td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i data-feather="eye" class="icon-xs"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
