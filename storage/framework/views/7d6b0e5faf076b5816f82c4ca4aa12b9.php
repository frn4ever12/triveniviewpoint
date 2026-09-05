<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#dc3545">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>DMC Restro - Dashboard</title>
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 70px;
        }

        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #fff;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            color: #fff;
        }

        .stat-icon.orders { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .stat-icon.revenue { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .stat-icon.tables { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); }
        .stat-icon.staff { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            padding: 20px 20px 10px;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .quick-actions {
            padding: 0 20px 20px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .action-item {
            background: #fff;
            border-radius: 15px;
            padding: 20px 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .action-item:hover {
            transform: translateY(-5px);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            color: #fff;
        }

        .action-icon.new-order { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .action-icon.menu { background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); }
        .action-icon.tables { background: linear-gradient(135deg, #fd7e14 0%, #e67e22 100%); }
        .action-icon.customers { background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); }
        .action-icon.reports { background: linear-gradient(135deg, #6610f2 0%, #520dc2 100%); }
        .action-icon.settings { background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); }

        .action-label {
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }

        .recent-orders {
            padding: 0 20px 20px;
        }

        .order-card {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-id {
            font-weight: 700;
            color: #333;
        }

        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .order-status.pending { background: #fff3cd; color: #856404; }
        .order-status.preparing { background: #d1ecf1; color: #0c5460; }
        .order-status.ready { background: #d4edda; color: #155724; }
        .order-status.completed { background: #e2e3e5; color: #383d41; }

        .order-details {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }

        .order-amount {
            font-weight: 700;
            color: #dc3545;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-around;
            z-index: 1000;
        }

        .nav-item {
            text-align: center;
            color: #666;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .nav-item.active {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .nav-item i {
            font-size: 20px;
            display: block;
            margin-bottom: 4px;
        }

        .nav-item span {
            font-size: 11px;
            font-weight: 500;
        }

        .logout-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            font-size: 20px;
        }

        .logout-btn:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>DMC Restro</h1>
            <div class="user-info">
                <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orders">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-value"><?php echo e($todayOrders ?? 0); ?></div>
            <div class="stat-label">Today's Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">Rs.<?php echo e(number_format($todayRevenue ?? 0)); ?></div>
            <div class="stat-label">Today's Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon tables">
                <i class="bi bi-grid-3x3"></i>
            </div>
            <div class="stat-value"><?php echo e($activeTables ?? 0); ?></div>
            <div class="stat-label">Active Tables</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon staff">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-value"><?php echo e($activeStaff ?? 0); ?></div>
            <div class="stat-label">Active Staff</div>
        </div>
    </div>

    <div class="section-title">Quick Actions</div>
    <div class="quick-actions">
        <div class="action-grid">
            <a href="<?php echo e(route('admin.orders.pos')); ?>" class="action-item">
                <div class="action-icon new-order">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="action-label">New Order</div>
            </a>
            <a href="<?php echo e(route('admin.menu-items.index')); ?>" class="action-item">
                <div class="action-icon menu">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div class="action-label">Menu</div>
            </a>
            <a href="<?php echo e(route('admin.tables.index')); ?>" class="action-item">
                <div class="action-icon tables">
                    <i class="bi bi-grid-3x3"></i>
                </div>
                <div class="action-label">Tables</div>
            </a>
            <a href="<?php echo e(route('admin.contacts.index')); ?>" class="action-item">
                <div class="action-icon customers">
                    <i class="bi bi-people"></i>
                </div>
                <div class="action-label">Contacts</div>
            </a>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="action-item">
                <div class="action-icon reports">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <div class="action-label">Orders</div>
            </a>
            <a href="<?php echo e(route('admin.website.edit')); ?>" class="action-item">
                <div class="action-icon settings">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="action-label">Settings</div>
            </a>
        </div>
    </div>

    <div class="section-title">Recent Orders</div>
    <div class="recent-orders">
        <?php $__currentLoopData = $recentOrders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="order-card">
            <div class="order-header">
                <span class="order-id">#<?php echo e($order->id); ?></span>
                <span class="order-status <?php echo e(strtolower($order->status)); ?>"><?php echo e(ucfirst($order->status)); ?></span>
            </div>
            <div class="order-details">
                <span><?php echo e($order->created_at->format('H:i')); ?></span>
                <span class="order-amount">Rs.<?php echo e(number_format($order->total_amount)); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if(empty($recentOrders)): ?>
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox" style="font-size: 40px; opacity: 0.3;"></i>
            <p class="mt-2">No recent orders</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="bottom-nav">
        <a href="<?php echo e(route('mobile.dashboard')); ?>" class="nav-item active">
            <i class="bi bi-house"></i>
            <span>Home</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="nav-item">
            <i class="bi bi-receipt"></i>
            <span>Orders</span>
        </a>
        <a href="<?php echo e(route('admin.orders.pos')); ?>" class="nav-item">
            <i class="bi bi-cart"></i>
            <span>POS</span>
        </a>
        <a href="<?php echo e(route('admin.menu-items.index')); ?>" class="nav-item">
            <i class="bi bi-grid"></i>
            <span>Menu</span>
        </a>
        <a href="<?php echo e(route('admin.tables.index')); ?>" class="nav-item">
            <i class="bi bi-table"></i>
            <span>Tables</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/mobile/dashboard.blade.php ENDPATH**/ ?>