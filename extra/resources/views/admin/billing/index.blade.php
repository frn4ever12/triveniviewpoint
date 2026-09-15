@extends('admin.includes.main')

@section('title', 'Billing & Subscription')

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Billing & Subscription" icon="bi-credit-card" />

    <div class="row mb-4">
        <div class="col-12">
            <div class="card billing-header-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">{{ $packageName }} (Trial)</h4>
                            <p class="text-muted mb-0">{{ $daysRemaining }} Days Remaining</p>
                        </div>
                        <div class="billing-badge">
                            <span class="badge bg-primary">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Plan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="plan-info">
                                <h6 class="text-muted">Plan Name</h6>
                                <h4>{{ $packageName }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="plan-info">
                                <h6 class="text-muted">Billing Cycle</h6>
                                <h4>Yearly</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="plan-info">
                                <h6 class="text-muted">Active Since</h6>
                                <h4>{{ $currentTenant ? $currentTenant->created_at->format('d M Y') : 'N/A' }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="plan-actions">
                                <button class="btn btn-primary me-2">Renew</button>
                                <button class="btn btn-outline-secondary">Change Plan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Usage Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Members</h6>
                                    <h4>1/24</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar" style="width: 4%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-success">
                                    <i class="bi bi-table"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Tables</h6>
                                    <h4>3/50</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-success" style="width: 6%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-info">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Customers</h6>
                                    <h4>0/500</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-info" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-warning">
                                    <i class="bi bi-utensils"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Dishes</h6>
                                    <h4>6/1000</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-warning" style="width: 1%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-danger">
                                    <i class="bi bi-grid"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Add-ons</h6>
                                    <h4>5/250</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-danger" style="width: 2%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="usage-card">
                                <div class="usage-icon bg-secondary">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="usage-info">
                                    <h6>Spaces</h6>
                                    <h4>0/10</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-secondary" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Previous Subscription Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Plan</th>
                                    <th>Purchase Date</th>
                                    <th>Expiry Date</th>
                                    <th>Documents</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No data in table</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-muted">No purchase history found</p>
                        <p class="text-muted small">No Purchase Records Found.</p>
                        <p class="text-muted small">0 of 0 row(s) selected.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.billing-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.billing-header-card .card-body {
    color: #fff;
}

.billing-header-card h4 {
    color: #fff;
    font-weight: 700;
}

.billing-header-card p {
    color: rgba(255, 255, 255, 0.9);
}

.billing-badge .badge {
    font-size: 14px;
    padding: 8px 16px;
}

.plan-info h6 {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.plan-info h4 {
    font-weight: 600;
    color: #1e293b;
}

.plan-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    height: 100%;
}

.usage-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.usage-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.usage-icon i {
    font-size: 24px;
    color: #fff;
}

.usage-info h6 {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 5px;
}

.usage-info h4 {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.usage-info .progress {
    height: 6px;
    border-radius: 3px;
}
</style>
@endpush
@endsection
