@extends('admin.includes.main')

@section('title', 'Kitchen / KOT Display')

@push('styles')
    <style>
        .kot-column {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            min-height: calc(100vh - 200px);
        }
        
        .kot-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .kot-column-header.new { background: #fef3c7; color: #d97706; }
        .kot-column-header.confirmed { background: #dbeafe; color: #2563eb; }
        .kot-column-header.preparing { background: #ede9fe; color: #7c3aed; }
        .kot-column-header.ready { background: #d1fae5; color: #059669; }
        .kot-column-header.served { background: #f1f5f9; color: #64748b; }
        
        .kot-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .kot-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .kot-card.new { border-left-color: #f59e0b; }
        .kot-card.confirmed { border-left-color: #3b82f6; }
        .kot-card.preparing { border-left-color: #8b5cf6; }
        .kot-card.ready { border-left-color: #10b981; }
        .kot-card.served { border-left-color: #64748b; }
        
        .kot-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .kot-number {
            font-weight: 700;
            font-size: 1rem;
        }
        
        .kot-time {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .kot-table {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.75rem;
        }
        
        .kot-items {
            margin-bottom: 0.75rem;
        }
        
        .kot-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 6px;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }
        
        .kot-item-qty {
            font-weight: 600;
            color: #dc2626;
        }
        
        .kot-special {
            background: #fef3c7;
            color: #d97706;
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        .kot-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        
        .kot-action-btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .kot-action-btn.primary {
            background: #dc2626;
            color: white;
        }
        
        .kot-action-btn.primary:hover {
            background: #b91c1c;
        }
        
        .kot-action-btn.success {
            background: #16a34a;
            color: white;
        }
        
        .kot-action-btn.success:hover {
            background: #15803d;
        }
        
        .kot-action-btn.secondary {
            background: #64748b;
            color: white;
        }
        
        .kot-action-btn.secondary:hover {
            background: #475569;
        }
        
        .kot-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .kot-badge.dine-in { background: #dcfce7; color: #16a34a; }
        .kot-badge.takeaway { background: #dbeafe; color: #2563eb; }
        .kot-badge.delivery { background: #fef3c7; color: #d97706; }
        
        .auto-refresh {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1e293b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Kitchen / KOT Display</h4>
                <p class="text-muted mb-0">Real-time kitchen order tracking</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-success rounded-pill">
                    <i data-feather="refresh-cw" class="icon-xs me-1"></i> Auto-refresh: 30s
                </span>
                <button class="btn btn-primary" onclick="location.reload()">
                    <i data-feather="refresh-cw" class="icon-xs me-1"></i> Refresh
                </button>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i data-feather="printer" class="icon-xs me-1"></i> Print All
                </button>
            </div>
        </div>

        <!-- KOT Columns -->
        <div class="row g-3">
            <!-- New Orders -->
            <div class="col-lg-2-4 col-md-3">
                <div class="kot-column">
                    <div class="kot-column-header new">
                        <span>NEW</span>
                        <span class="badge bg-warning text-dark">3</span>
                    </div>
                    
                    <div class="kot-card new">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1025</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">2 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 08
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Momo</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                            <div class="kot-item">
                                <span>Chowmein</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                            <div class="kot-item">
                                <span>Cold Drink</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-special">
                            <i data-feather="alert-triangle" class="icon-xs"></i> Less spicy for momo
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn primary" onclick="moveKot(this, 'confirmed')">Confirm</button>
                        </div>
                    </div>
                    
                    <div class="kot-card new">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1024</span>
                                <span class="kot-badge takeaway ms-2">Takeaway</span>
                            </div>
                            <span class="kot-time">5 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="shopping-bag" class="icon-xs"></i> Order #1024
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Veg Thukpa</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                            <div class="kot-item">
                                <span>Fried Rice</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn primary" onclick="moveKot(this, 'confirmed')">Confirm</button>
                        </div>
                    </div>
                    
                    <div class="kot-card new">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1023</span>
                                <span class="kot-badge delivery ms-2">Delivery</span>
                            </div>
                            <span class="kot-time">8 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="truck" class="icon-xs"></i> Order #1023
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Curry</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                            <div class="kot-item">
                                <span>Naan</span>
                                <span class="kot-item-qty">× 3</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn primary" onclick="moveKot(this, 'confirmed')">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmed -->
            <div class="col-lg-2-4 col-md-3">
                <div class="kot-column">
                    <div class="kot-column-header confirmed">
                        <span>CONFIRMED</span>
                        <span class="badge bg-info text-white">2</span>
                    </div>
                    
                    <div class="kot-card confirmed">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1022</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">10 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 05
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Momo</span>
                                <span class="kot-item-qty">× 3</span>
                            </div>
                            <div class="kot-item">
                                <span>Veg Momo</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn success" onclick="moveKot(this, 'preparing')">Start Preparing</button>
                        </div>
                    </div>
                    
                    <div class="kot-card confirmed">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1021</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">15 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 12
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chowmein</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                            <div class="kot-item">
                                <span>Thukpa</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn success" onclick="moveKot(this, 'preparing')">Start Preparing</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preparing -->
            <div class="col-lg-2-4 col-md-3">
                <div class="kot-column">
                    <div class="kot-column-header preparing">
                        <span>PREPARING</span>
                        <span class="badge bg-purple text-white">2</span>
                    </div>
                    
                    <div class="kot-card preparing">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1020</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">18 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 03
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Fried Rice</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                            <div class="kot-item">
                                <span>Chicken Curry</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                        </div>
                        <div class="kot-special">
                            <i data-feather="clock" class="icon-xs"></i> Started 5 min ago
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn primary" onclick="moveKot(this, 'ready')">Mark Ready</button>
                        </div>
                    </div>
                    
                    <div class="kot-card preparing">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1019</span>
                                <span class="kot-badge takeaway ms-2">Takeaway</span>
                            </div>
                            <span class="kot-time">22 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="shopping-bag" class="icon-xs"></i> Order #1019
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Veg Chowmein</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                            <div class="kot-item">
                                <span>Gulab Jamun</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn primary" onclick="moveKot(this, 'ready')">Mark Ready</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ready -->
            <div class="col-lg-2-4 col-md-3">
                <div class="kot-column">
                    <div class="kot-column-header ready">
                        <span>READY</span>
                        <span class="badge bg-success text-white">3</span>
                    </div>
                    
                    <div class="kot-card ready">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1018</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">25 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 07
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Momo</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                            <div class="kot-item">
                                <span>Cold Drink</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn success" onclick="moveKot(this, 'served')">Mark Served</button>
                        </div>
                    </div>
                    
                    <div class="kot-card ready">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1017</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">30 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 10
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Thukpa</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn success" onclick="moveKot(this, 'served')">Mark Served</button>
                        </div>
                    </div>
                    
                    <div class="kot-card ready">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1016</span>
                                <span class="kot-badge delivery ms-2">Delivery</span>
                            </div>
                            <span class="kot-time">35 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="truck" class="icon-xs"></i> Order #1016
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chicken Curry</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                            <div class="kot-item">
                                <span>Naan</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn success" onclick="moveKot(this, 'served')">Hand to Driver</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Served -->
            <div class="col-lg-2-4 col-md-3">
                <div class="kot-column">
                    <div class="kot-column-header served">
                        <span>SERVED</span>
                        <span class="badge bg-secondary text-white">5</span>
                    </div>
                    
                    <div class="kot-card served">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1015</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">40 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 02
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Chowmein</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                            <div class="kot-item">
                                <span>Cold Drink</span>
                                <span class="kot-item-qty">× 2</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn secondary" onclick="printKot(this)">Print</button>
                        </div>
                    </div>
                    
                    <div class="kot-card served">
                        <div class="kot-header">
                            <div>
                                <span class="kot-number">KOT #1014</span>
                                <span class="kot-badge dine-in ms-2">Dine In</span>
                            </div>
                            <span class="kot-time">45 min ago</span>
                        </div>
                        <div class="kot-table">
                            <i data-feather="grid" class="icon-xs"></i> Table 06
                        </div>
                        <div class="kot-items">
                            <div class="kot-item">
                                <span>Veg Fried Rice</span>
                                <span class="kot-item-qty">× 1</span>
                            </div>
                        </div>
                        <div class="kot-actions">
                            <button class="kot-action-btn secondary" onclick="printKot(this)">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto Refresh Indicator -->
        <div class="auto-refresh">
            <i data-feather="clock" class="icon-xs me-1"></i> Next refresh in 30s
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();
        
        function moveKot(btn, status) {
            const card = btn.closest('.kot-card');
            const column = card.closest('.kot-column');
            
            // Visual feedback
            btn.innerHTML = '<i data-feather="loader" class="icon-xs"></i> Moving...';
            feather.replace();
            
            setTimeout(() => {
                // In production, this would make an API call
                alert(`KOT moved to ${status.toUpperCase()}`);
                location.reload();
            }, 500);
        }
        
        function printKot(btn) {
            const kotNumber = btn.closest('.kot-card').querySelector('.kot-number').textContent;
            alert(`Printing ${kotNumber}...`);
        }
        
        // Auto refresh every 30 seconds
        let refreshCountdown = 30;
        setInterval(() => {
            refreshCountdown--;
            if (refreshCountdown <= 0) {
                refreshCountdown = 30;
                location.reload();
            }
            document.querySelector('.auto-refresh').innerHTML = `
                <i data-feather="clock" class="icon-xs me-1"></i> Next refresh in ${refreshCountdown}s
            `;
            feather.replace();
        }, 1000);
    </script>
@endpush
