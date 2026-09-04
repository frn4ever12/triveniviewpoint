@extends('admin.includes.main')

@section('title', 'Administration')

@push('styles')
    <style>
        .admin-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            border-color: #dc2626;
        }
        
        .admin-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .admin-icon.staff { background: #dcfce7; color: #16a34a; }
        .admin-icon.roles { background: #dbeafe; color: #2563eb; }
        .admin-icon.restaurant { background: #fef3c7; color: #d97706; }
        .admin-icon.printer { background: #ede9fe; color: #7c3aed; }
        .admin-icon.tax { background: #cffafe; color: #0891b2; }
        .admin-icon.payment { background: #fee2e2; color: #dc2626; }
        .admin-icon.branch { background: #f1f5f9; color: #64748b; }
        .admin-icon.integration { background: #fef2f2; color: #dc2626; }
        
        .admin-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .admin-description {
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
                <h4 class="fw-bold mb-1">Administration</h4>
                <p class="text-muted mb-0">Restaurant settings and configuration</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i data-feather="download" class="icon-xs me-1"></i> Backup
                </button>
                <button class="btn btn-outline-primary">
                    <i data-feather="upload" class="icon-xs me-1"></i> Restore
                </button>
            </div>
        </div>

        <!-- Admin Modules -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card" onclick="location.href='{{ route('admin.staff.index') }}'">
                    <div class="admin-icon staff">
                        <i data-feather="users"></i>
                    </div>
                    <div class="admin-title">Staff Management</div>
                    <div class="admin-description">Manage employees, roles, permissions</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card" onclick="location.href='{{ route('admin.roles.index') }}'">
                    <div class="admin-icon roles">
                        <i data-feather="shield"></i>
                    </div>
                    <div class="admin-title">Roles & Permissions</div>
                    <div class="admin-description">Access control and permissions</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card" onclick="location.href='{{ route('admin.website.edit') }}'">
                    <div class="admin-icon restaurant">
                        <i data-feather="settings"></i>
                    </div>
                    <div class="admin-title">Restaurant Settings</div>
                    <div class="admin-description">General restaurant configuration</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card">
                    <div class="admin-icon printer">
                        <i data-feather="printer"></i>
                    </div>
                    <div class="admin-title">Printer Settings</div>
                    <div class="admin-description">Configure POS and kitchen printers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card">
                    <div class="admin-icon tax">
                        <i data-feather="percent"></i>
                    </div>
                    <div class="admin-title">Tax Settings</div>
                    <div class="admin-description">VAT rates and tax configuration</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card">
                    <div class="admin-icon payment">
                        <i data-feather="credit-card"></i>
                    </div>
                    <div class="admin-title">Payment Methods</div>
                    <div class="admin-description">Configure payment gateways</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card">
                    <div class="admin-icon branch">
                        <i data-feather="map-pin"></i>
                    </div>
                    <div class="admin-title">Branch Management</div>
                    <div class="admin-description">Multi-branch configuration</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="admin-card">
                    <div class="admin-icon integration">
                        <i data-feather="link"></i>
                    </div>
                    <div class="admin-title">Integrations</div>
                    <div class="admin-description">Third-party app integrations</div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="users" class="icon-xs text-success"></i>
                        Staff Overview
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span>Total Staff</span>
                            <strong>12</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Active</span>
                            <strong class="text-success">10</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>On Leave</span>
                            <strong class="text-warning">2</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Roles</span>
                            <strong>5</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="shield" class="icon-xs text-primary"></i>
                        Roles Overview
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span>Super Admin</span>
                            <strong>1</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Admin</span>
                            <strong>2</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Manager</span>
                            <strong>3</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Staff</span>
                            <strong>4</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Cashier</span>
                            <strong>2</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="credit-card" class="icon-xs text-danger"></i>
                        Payment Methods
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Cash</span>
                            <span class="status-badge success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Card</span>
                            <span class="status-badge success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>eSewa</span>
                            <span class="status-badge success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Khalti</span>
                            <span class="status-badge success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Fonepay</span>
                            <span class="status-badge warning">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="printer" class="icon-xs text-purple"></i>
                        Printer Status
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>POS Printer</span>
                            <span class="status-badge success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Kitchen Printer 1</span>
                            <span class="status-badge success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Kitchen Printer 2</span>
                            <span class="status-badge danger">Offline</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Bar Printer</span>
                            <span class="status-badge secondary">Not Configured</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff List & Recent Activity -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="users" class="icon-xs text-primary"></i>
                        Staff Members
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Ram Sharma</strong></td>
                                    <td>Super Admin</td>
                                    <td>9800000001</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td>2 min ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sita Devi</strong></td>
                                    <td>Admin</td>
                                    <td>9800000002</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td>1 hour ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Hari Krishna</strong></td>
                                    <td>Manager</td>
                                    <td>9800000003</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td>3 hours ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gopal Thapa</strong></td>
                                    <td>Staff</td>
                                    <td>9800000004</td>
                                    <td><span class="status-badge warning">On Leave</span></td>
                                    <td>2 days ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Maya Tamang</strong></td>
                                    <td>Cashier</td>
                                    <td>9800000005</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td>5 hours ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i data-feather="edit-3" class="icon-xs"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="activity" class="icon-xs text-primary"></i>
                        Recent Activity
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i data-feather="user-plus" class="icon-xs text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">New Staff Added</div>
                                <small class="text-muted">Dipak Rai joined as Cashier</small>
                                <div class="text-muted small">2 hours ago</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i data-feather="settings" class="icon-xs text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Settings Updated</div>
                                <small class="text-muted">VAT rate changed to 13%</small>
                                <div class="text-muted small">5 hours ago</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i data-feather="shield" class="icon-xs text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Role Modified</div>
                                <small class="text-muted">Manager permissions updated</small>
                                <div class="text-muted small">1 day ago</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i data-feather="printer" class="icon-xs text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Printer Offline</div>
                                <small class="text-muted">Kitchen Printer 2 disconnected</small>
                                <div class="text-muted small">1 day ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax & Payment Configuration -->
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="percent" class="icon-xs text-purple"></i>
                        Tax Configuration
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Tax Type</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>VAT</td>
                                    <td>13%</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>Service Charge</td>
                                    <td>10%</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>Delivery Charge</td>
                                    <td>Rs. 50</td>
                                    <td><span class="status-badge success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>PAN Number</td>
                                    <td>123456789</td>
                                    <td><span class="status-badge success">Verified</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card">
                    <div class="section-title">
                        <i data-feather="credit-card" class="icon-xs text-danger"></i>
                        Payment Gateway Configuration
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Gateway</th>
                                    <th>Merchant ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>eSewa</td>
                                    <td>EP-12345</td>
                                    <td><span class="status-badge success">Connected</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Configure</button></td>
                                </tr>
                                <tr>
                                    <td>Khalti</td>
                                    <td>KL-67890</td>
                                    <td><span class="status-badge success">Connected</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Configure</button></td>
                                </tr>
                                <tr>
                                    <td>Fonepay</td>
                                    <td>FP-54321</td>
                                    <td><span class="status-badge warning">Pending</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Configure</button></td>
                                </tr>
                                <tr>
                                    <td>ConnectIPS</td>
                                    <td>-</td>
                                    <td><span class="status-badge secondary">Not Configured</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Setup</button></td>
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
