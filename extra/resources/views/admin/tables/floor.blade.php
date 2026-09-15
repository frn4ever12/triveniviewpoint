@extends('admin.includes.main')

@section('title', 'Table Management')

@push('styles')
    <style>
        .floor-plan {
            background: #f8fafc;
            border-radius: 12px;
            padding: 2rem;
            min-height: calc(100vh - 200px);
            border: 2px dashed #e2e8f0;
        }
        
        .table-card {
            position: relative;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 3px solid;
        }
        
        .table-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        
        .table-card.available {
            background: #dcfce7;
            border-color: #16a34a;
        }
        
        .table-card.occupied {
            background: #fee2e2;
            border-color: #dc2626;
        }
        
        .table-card.reserved {
            background: #fef3c7;
            border-color: #d97706;
        }
        
        .table-card.cleaning {
            background: #f1f5f9;
            border-color: #64748b;
        }
        
        .table-number {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .table-capacity {
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        
        .table-status {
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .table-card.available .table-status {
            background: #16a34a;
            color: white;
        }
        
        .table-card.occupied .table-status {
            background: #dc2626;
            color: white;
        }
        
        .table-card.reserved .table-status {
            background: #d97706;
            color: white;
        }
        
        .table-card.cleaning .table-status {
            background: #64748b;
            color: white;
        }
        
        .table-info {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(0,0,0,0.1);
            font-size: 0.8rem;
        }
        
        .table-order {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }
        
        .table-amount {
            font-weight: 700;
            color: #dc2626;
        }
        
        .table-waiter {
            color: #64748b;
        }
        
        .table-actions {
            position: absolute;
            top: -8px;
            right: -8px;
            display: none;
            gap: 0.25rem;
        }
        
        .table-card:hover .table-actions {
            display: flex;
        }
        
        .table-action-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .table-action-btn:hover {
            transform: scale(1.1);
        }
        
        .table-action-btn.primary {
            background: #dc2626;
            color: white;
        }
        
        .table-action-btn.success {
            background: #16a34a;
            color: white;
        }
        
        .table-action-btn.info {
            background: #2563eb;
            color: white;
        }
        
        .floor-legend {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        
        .legend-color.available { background: #16a34a; }
        .legend-color.occupied { background: #dc2626; }
        .legend-color.reserved { background: #d97706; }
        .legend-color.cleaning { background: #64748b; }
        
        .floor-section {
            margin-bottom: 2rem;
        }
        
        .floor-section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .floor-grid {
            display: grid;
            gap: 1rem;
        }
        
        .floor-grid.grid-2 { grid-template-columns: repeat(2, 1fr); }
        .floor-grid.grid-3 { grid-template-columns: repeat(3, 1fr); }
        .floor-grid.grid-4 { grid-template-columns: repeat(4, 1fr); }
        
        @media (max-width: 768px) {
            .floor-grid.grid-2,
            .floor-grid.grid-3,
            .floor-grid.grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .quick-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .quick-action-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: #dc2626;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.2s;
        }
        
        .quick-action-btn:hover {
            transform: scale(1.1);
        }
        
        .quick-action-btn.success {
            background: #16a34a;
        }
        
        .quick-action-btn.info {
            background: #2563eb;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Table Management</h4>
                <p class="text-muted mb-0">Visual restaurant floor layout</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="location.reload()">
                    <i data-feather="refresh-cw" class="icon-xs me-1"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="showMergeModal()">
                    <i data-feather="git-merge" class="icon-xs me-1"></i> Merge Tables
                </button>
                <button class="btn btn-success" onclick="showReserveModal()">
                    <i data-feather="calendar" class="icon-xs me-1"></i> Reserve Table
                </button>
            </div>
        </div>

        <!-- Legend -->
        <div class="floor-legend">
            <div class="legend-item">
                <div class="legend-color available"></div>
                <span>Available (8)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color occupied"></div>
                <span>Occupied (5)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color reserved"></div>
                <span>Reserved (2)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color cleaning"></div>
                <span>Cleaning (1)</span>
            </div>
        </div>

        <!-- Floor Plan -->
        <div class="floor-plan">
            <!-- Main Hall -->
            <div class="floor-section">
                <div class="floor-section-title">
                    <i data-feather="home" class="icon-xs"></i> Main Hall
                </div>
                <div class="floor-grid grid-4">
                    <div class="table-card available" onclick="showTableDetails(1)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(1)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(1)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">01</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card occupied" onclick="showTableDetails(2)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(2)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(2)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">02</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1020</span>
                                <span class="table-amount">Rs. 850</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Ram
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card occupied" onclick="showTableDetails(3)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(3)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(3)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">03</div>
                        <div class="table-capacity">2 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1021</span>
                                <span class="table-amount">Rs. 450</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Sita
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card available" onclick="showTableDetails(4)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(4)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(4)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">04</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card occupied" onclick="showTableDetails(5)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(5)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(5)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">05</div>
                        <div class="table-capacity">6 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1022</span>
                                <span class="table-amount">Rs. 1,200</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Hari
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card reserved" onclick="showTableDetails(6)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Reservation" onclick="event.stopPropagation(); viewReservation(6)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn primary" title="Cancel" onclick="event.stopPropagation(); cancelReservation(6)">
                                <i data-feather="x" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">06</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Reserved</div>
                        <div class="table-info">
                            <div class="table-waiter">
                                <i data-feather="clock" class="icon-xs"></i> 7:00 PM - Ram
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card available" onclick="showTableDetails(7)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(7)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(7)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">07</div>
                        <div class="table-capacity">2 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card occupied" onclick="showTableDetails(8)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(8)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(8)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">08</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1023</span>
                                <span class="table-amount">Rs. 950</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Krishna
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Private Room -->
            <div class="floor-section">
                <div class="floor-section-title">
                    <i data-feather="lock" class="icon-xs"></i> Private Room
                </div>
                <div class="floor-grid grid-2">
                    <div class="table-card occupied" onclick="showTableDetails(9)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(9)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(9)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">09</div>
                        <div class="table-capacity">8 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1024</span>
                                <span class="table-amount">Rs. 2,500</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Gopal
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card reserved" onclick="showTableDetails(10)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Reservation" onclick="event.stopPropagation(); viewReservation(10)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn primary" title="Cancel" onclick="event.stopPropagation(); cancelReservation(10)">
                                <i data-feather="x" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">10</div>
                        <div class="table-capacity">10 Seats</div>
                        <div class="table-status">Reserved</div>
                        <div class="table-info">
                            <div class="table-waiter">
                                <i data-feather="clock" class="icon-xs"></i> 8:00 PM - Party
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Outdoor -->
            <div class="floor-section">
                <div class="floor-section-title">
                    <i data-feather="sun" class="icon-xs"></i> Outdoor Seating
                </div>
                <div class="floor-grid grid-3">
                    <div class="table-card available" onclick="showTableDetails(11)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(11)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(11)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">11</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card cleaning" onclick="showTableDetails(12)">
                        <div class="table-actions">
                            <button class="table-action-btn success" title="Mark Clean" onclick="event.stopPropagation(); markClean(12)">
                                <i data-feather="check" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">12</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Cleaning</div>
                    </div>
                    
                    <div class="table-card available" onclick="showTableDetails(13)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(13)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(13)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">13</div>
                        <div class="table-capacity">2 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card occupied" onclick="showTableDetails(14)">
                        <div class="table-actions">
                            <button class="table-action-btn info" title="View Order" onclick="event.stopPropagation(); viewOrder(14)">
                                <i data-feather="eye" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Checkout" onclick="event.stopPropagation(); checkout(14)">
                                <i data-feather="credit-card" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">14</div>
                        <div class="table-capacity">6 Seats</div>
                        <div class="table-status">Occupied</div>
                        <div class="table-info">
                            <div class="table-order">
                                <span>Order #1025</span>
                                <span class="table-amount">Rs. 1,100</span>
                            </div>
                            <div class="table-waiter">
                                <i data-feather="user" class="icon-xs"></i> Maya
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-card available" onclick="showTableDetails(15)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(15)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(15)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">15</div>
                        <div class="table-capacity">4 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                    
                    <div class="table-card available" onclick="showTableDetails(16)">
                        <div class="table-actions">
                            <button class="table-action-btn primary" title="New Order" onclick="event.stopPropagation(); newOrder(16)">
                                <i data-feather="plus" class="icon-xs"></i>
                            </button>
                            <button class="table-action-btn success" title="Reserve" onclick="event.stopPropagation(); reserveTable(16)">
                                <i data-feather="calendar" class="icon-xs"></i>
                            </button>
                        </div>
                        <div class="table-number">16</div>
                        <div class="table-capacity">2 Seats</div>
                        <div class="table-status">Available</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="quick-action-btn" title="New Order" onclick="window.open('{{ route('admin.orders.pos') }}', '_blank')">
                <i data-feather="shopping-cart"></i>
            </button>
            <button class="quick-action-btn success" title="Kitchen Display" onclick="window.open('{{ route('admin.kitchen-display.index') }}', '_blank')">
                <i data-feather="users"></i>
            </button>
            <button class="quick-action-btn info" title="All Orders" onclick="window.open('{{ route('admin.orders.index') }}', '_blank')">
                <i data-feather="list"></i>
            </button>
        </div>
    </div>

    <!-- Table Details Modal -->
    <div class="modal fade" id="tableDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Table Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="tableDetailsContent">
                    <!-- Dynamic content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="newOrderFromModal()">New Order</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();
        
        function showTableDetails(tableId) {
            const content = document.getElementById('tableDetailsContent');
            content.innerHTML = `
                <div class="text-center">
                    <h3 class="mb-3">Table ${tableId}</h3>
                    <div class="mb-3">
                        <span class="badge bg-success">Available</span>
                    </div>
                    <div class="mb-3">
                        <strong>Capacity:</strong> 4 Seats
                    </div>
                    <div class="mb-3">
                        <strong>Location:</strong> Main Hall
                    </div>
                    <div class="mb-3">
                        <strong>Waiter:</strong> -
                    </div>
                    <div class="mb-3">
                        <strong>Current Order:</strong> None
                    </div>
                </div>
            `;
            const modal = new bootstrap.Modal(document.getElementById('tableDetailsModal'));
            modal.show();
        }
        
        function newOrder(tableId) {
            alert(`Creating new order for Table ${tableId}`);
            window.open('{{ route('admin.orders.pos') }}', '_blank');
        }
        
        function viewOrder(tableId) {
            alert(`Viewing order for Table ${tableId}`);
        }
        
        function checkout(tableId) {
            if (confirm(`Checkout Table ${tableId}?`)) {
                alert(`Table ${tableId} checked out successfully!`);
                location.reload();
            }
        }
        
        function reserveTable(tableId) {
            alert(`Reserving Table ${tableId}`);
        }
        
        function viewReservation(tableId) {
            alert(`Viewing reservation for Table ${tableId}`);
        }
        
        function cancelReservation(tableId) {
            if (confirm(`Cancel reservation for Table ${tableId}?`)) {
                alert(`Reservation cancelled successfully!`);
                location.reload();
            }
        }
        
        function markClean(tableId) {
            if (confirm(`Mark Table ${tableId} as clean?`)) {
                alert(`Table ${tableId} marked as clean!`);
                location.reload();
            }
        }
        
        function showMergeModal() {
            alert('Merge tables feature - Select multiple tables to merge');
        }
        
        function showReserveModal() {
            alert('Reserve table feature - Select table and time');
        }
        
        function newOrderFromModal() {
            bootstrap.Modal.getInstance(document.getElementById('tableDetailsModal')).hide();
            window.open('{{ route('admin.orders.pos') }}', '_blank');
        }
    </script>
@endpush
