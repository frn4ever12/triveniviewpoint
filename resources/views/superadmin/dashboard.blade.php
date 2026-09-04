@extends('superadmin.includes.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Superadmin Dashboard</h1>
                <p class="page-subtitle">Manage your SaaS platform</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['total_tenants'] }}</div>
                    <div class="stat-card-label">Total Tenants</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['active_tenants'] }}</div>
                    <div class="stat-card-label">Active Tenants</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['pending_tenants'] }}</div>
                    <div class="stat-card-label">Pending Approval</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-card-info">
                <div class="stat-card-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">Rs. {{ number_format($stats['total_revenue']) }}</div>
                    <div class="stat-card-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="stat-card stat-card-secondary">
                <div class="stat-card-icon">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['total_subscriptions'] }}</div>
                    <div class="stat-card-label">Total Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['active_subscriptions'] }}</div>
                    <div class="stat-card-label">Active Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="stat-card stat-card-info">
                <div class="stat-card-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">{{ $stats['total_users'] }}</div>
                    <div class="stat-card-label">Total Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants & Subscriptions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Tenants</h5>
                    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentTenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTenants as $tenant)
                                    <tr>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $tenant->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No tenants registered yet.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Subscriptions</h5>
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentSubscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tenant</th>
                                        <th>Plan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->tenant->name }}</td>
                                        <td>{{ $subscription->plan->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'info' : 'danger') }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No subscriptions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    
    .stat-card-primary .stat-card-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card-success .stat-card-icon {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .stat-card-warning .stat-card-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card-info .stat-card-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card-secondary .stat-card-icon {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    
    .stat-card-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }
    
    .stat-card-label {
        font-size: 14px;
        color: #666;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #333;
    }
    
    .page-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 0;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection
