@extends('admin.includes.main')
@section('title', 'Orders')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#1e293b;">Orders</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Manage dine-in orders, tables, and KOTs.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-danger btn-sm rounded-3 nav-btn active" data-target="order">Orders</button>
                <a href="{{ route('admin.orders.details') }}" class="btn btn-outline-danger btn-sm rounded-3">Order List</a>
                <button class="btn btn-outline-danger btn-sm rounded-3 nav-btn" data-target="table">Tables</button>
                <button class="btn btn-outline-danger btn-sm rounded-3 nav-btn" data-target="kot">KOT</button>
                <a target="_blank" href="{{ route('admin.orders.pos') }}" class="btn btn-outline-danger btn-sm rounded-3">POS</a>
            </div>
        </div>

        <div id="content">
            {{-- Orders Section --}}
            <div id="order" class="content-pane">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Recent Orders</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.orders.pos') }}" class="btn btn-sm btn-danger rounded-3">
                                <i class="bi bi-plus-lg me-1"></i> Add New Order
                            </a>
                            <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="refreshOrders()">
                                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3" id="ordersTableBody">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tables Section --}}
            <div id="table" class="content-pane d-none">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Restaurant Tables</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach ($tables as $table)
                                <div class="table-ov-card {{ $table->status == \App\Enums\TableStatusEnum::AVAILABLE ? 'avail' : 'occ' }}"
                                     onclick="handleTableClick('{{ $table->name }}', {{ $table->id }}, '{{ $table->status }}')">
                                    <div class="table-ov-name">{{ $table->name }}</div>
                                    <div class="table-ov-status">{{ $table->status }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOT Section --}}
            <div id="kot" class="content-pane d-none">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-3" style="border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;">Kitchen Order Tickets</h5>
                        <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="refreshKOTs()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3" id="kotList">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Items Modal --}}
        <div class="modal fade" id="addItemsModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;border:none;">
                    <div class="modal-header border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="modal-title mb-0 fw-bold">Add Items to <span id="selectedTableName">Table</span></h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="waiterSelect" style="width:auto;border-radius:8px;">
                                <option value="">Assign Waiter</option>
                                @foreach ($waiters as $waiter)
                                    <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                    <div class="modal-body p-4 row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius:10px 0 0 10px;"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control border-start-0" placeholder="Search dishes..." id="dishSearch" style="border-radius:0 10px 10px 0;">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="list-group menu-categories" id="menuCategories" style="border-radius:10px;">
                                        <button class="list-group-item list-group-item-action active" data-menu-id="all">All Items</button>
                                        @foreach ($menus as $menu)
                                            <button class="list-group-item list-group-item-action" data-menu-id="{{ $menu->id }}">
                                                {{ $menu->name }}
                                                <span class="badge bg-light text-dark ms-auto">{{ $menu->dishes->count() }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h6 id="menuTitle" class="fw-bold mb-2" style="font-size:.85rem;">All Items</h6>
                                    <div class="row g-2" id="dishesContainer" style="max-height:420px;overflow-y:auto;">
                                        @foreach ($dishes as $dish)
                                            <div class="col-6 col-lg-4 dish-card-wrap" data-menu-id="{{ $dish->menu_id }}">
                                                <div class="dish-card-new">
                                                    <img src="{{ $dish->image_url ?: 'https://via.placeholder.com/150x100?text=' . urlencode($dish->name) }}"
                                                         class="dish-img-new" alt="{{ $dish->name }}">
                                                    <div class="dish-info-new">
                                                        <h6 class="mb-1" style="font-size:.82rem;">{{ $dish->name }}</h6>
                                                        <p class="text-danger fw-bold mb-1" style="font-size:.85rem;">Rs {{ number_format($dish->final_price ?? $dish->price, 2) }}</p>
                                                        <p class="text-muted small mb-2" style="font-size:.72rem;">{{ Str::limit($dish->description, 40) }}</p>
                                                        <button class="btn btn-outline-danger btn-sm w-100 rounded-3"
                                                                onclick="addToCart({{ $dish->id }}, '{{ $dish->name }}', {{ $dish->final_price ?? $dish->price }}, '{{ $dish->image_url }}')">
                                                            <i class="bi bi-cart-plus me-1"></i> Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold" style="font-size:.85rem;"><i class="bi bi-cart me-1"></i> Cart</h6>
                                    <button class="btn btn-link btn-sm text-danger p-0" id="clearCartBtn" style="display:none;text-decoration:none;">Clear</button>
                                </div>
                                <div id="cartItems" class="mb-3" style="max-height:280px;overflow-y:auto;">
                                    <p class="text-muted text-center small">No items selected.</p>
                                </div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <input type="number" class="form-control form-control-sm mb-2 rounded-3" placeholder="No. of guests" id="guestCount" min="1">
                                    <textarea class="form-control form-control-sm rounded-3" placeholder="Notes..." id="orderNotes" rows="2"></textarea>
                                </div>
                                <div class="bg-white rounded-3 p-3 border">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="fw-medium">QTY: <span id="totalQty">0</span></span>
                                        <span class="fw-bold">Rs <span id="totalAmount">0.00</span></span>
                                    </div>
                                    <button class="btn btn-danger w-100 rounded-3" id="createOrderBtn" disabled>
                                        <span class="btn-text"><i class="bi bi-check-lg"></i> Create Order</span>
                                        <span class="spinner-border spinner-border-sm d-none"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Confirm Action Modal --}}
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon" id="confirmIcon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="confirm-title" id="confirmTitle">Are you sure?</div>
            <div class="confirm-desc" id="confirmDesc">This action cannot be undone.</div>
            <div class="confirm-actions">
                <button class="btn-cancel-act" id="confirmCancel">Cancel</button>
                <button class="btn-confirm-act" id="confirmProceed">Confirm</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .nav-btn { transition: all .15s; }
        .nav-btn.active { background-color: #dc2626 !important; color: #fff; border-color: #dc2626; }
        .content-pane { transition: opacity .15s; }

        .table-ov-card {
            width: 120px; padding: 1rem .75rem;
            border-radius: 10px;
            display: flex; flex-direction: column; align-items: center;
            cursor: pointer; font-weight: 600;
            transition: transform .15s, box-shadow .15s;
            border: 2px solid transparent;
            text-align: center;
        }
        .table-ov-card:hover { transform: scale(1.05); border-color: #dc2626; box-shadow: 0 4px 16px rgba(0,0,0,.1); }
        .table-ov-card.avail { background: #ecfdf5; color: #16a34a; }
        .table-ov-card.occ { background: #fef2f2; color: #dc2626; }
        .table-ov-name { font-size: 1rem; }
        .table-ov-status { font-size: .72rem; opacity: .8; text-transform: capitalize; }

        .dish-card-wrap { padding: 0; }
        .dish-card-new {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: all .15s;
            background: #fff;
            height: 100%;
        }
        .dish-card-new:hover { border-color: #dc2626; box-shadow: 0 4px 12px rgba(0,0,0,.06); transform: translateY(-1px); }
        .dish-img-new { width: 100%; height: 100px; object-fit: cover; display: block; }
        .dish-info-new { padding: .65rem; }
        .menu-categories .list-group-item {
            border: none; padding: .5rem .75rem; cursor: pointer; font-size: .82rem;
            transition: all .1s;
        }
        .menu-categories .list-group-item.active { background-color: #dc2626; border-color: #dc2626; }
        .menu-categories .list-group-item:not(.active):hover { background: #f1f5f9; }

        .cart-item {
            display: flex; align-items: center; gap: .5rem;
            padding: .5rem; background: #fff; border-radius: 8px;
            margin-bottom: .4rem; border: 1px solid #e2e8f0;
        }
        .cart-item-left { display: flex; align-items: center; gap: .5rem; flex: 1; min-width:0; }
        .cart-item-image { width: 32px; height: 32px; border-radius: 4px; object-fit: cover; flex-shrink:0; }
        .cart-item-details { min-width:0; }
        .cart-item-name { font-size: .78rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cart-item-price { font-size: .7rem; color: #64748b; }
        .size-controls { display: flex; align-items: center; gap: 2px; flex-shrink:0; }
        .size-btn, .qty-btn {
            width: 22px; height: 22px; border: 1px solid #e2e8f0;
            background: #f8fafc; border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .7rem; color: #475569;
            transition: all .1s; padding: 0; line-height: 1;
        }
        .size-btn:hover, .qty-btn:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
        .size-btn:disabled { opacity: .3; cursor: not-allowed; }
        .size-btn:disabled:hover { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
        .size-display { font-size: .7rem; font-weight: 600; min-width: 28px; text-align: center; color: #475569; }
        .qty-display { font-size: .78rem; font-weight: 700; min-width: 24px; text-align: center; }
        .remove-item-btn {
            background: none; border: none; color: #94a3b8; cursor: pointer;
            padding: 2px; transition: color .1s; margin-left: 2px;
        }
        .remove-item-btn:hover { color: #dc2626; }

        /* ── Modern Order Action Buttons ── */
        .order-actions {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-top:1rem;
        }
        .order-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border: none;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
            white-space: nowrap;
        }
        .order-action-btn i { font-size: .85rem; }
        .order-action-btn:active { transform: scale(.92); }
        .order-action-btn.danger {
            background: #fef2f2;
            color: #dc2626;
        }
        .order-action-btn.danger:hover {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 2px 12px rgba(220,38,38,.25);
        }
        .order-action-btn.muted {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .order-action-btn.muted:hover {
            background: #475569;
            color: #fff;
            border-color: #475569;
            box-shadow: 0 2px 12px rgba(71,85,105,.2);
        }
        .order-action-btn.xs {
            padding: 3px 8px;
            font-size: .65rem;
            border-radius: 14px;
        }
        .order-action-btn.xs i { font-size: .7rem; }

        /* ── Modern Confirm Modal ── */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all .2s ease;
        }
        .confirm-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .confirm-modal {
            background: #fff;
            border-radius: 16px;
            padding: 28px 32px 24px;
            max-width: 400px;
            width: calc(100% - 32px);
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            transform: scale(.92) translateY(12px);
            transition: transform .25s cubic-bezier(.16,1,.3,1);
        }
        .confirm-overlay.active .confirm-modal {
            transform: scale(1) translateY(0);
        }
        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.4rem;
        }
        .confirm-icon.danger { background: #fef2f2; color: #dc2626; }
        .confirm-icon.warning { background: #fffbeb; color: #f59e0b; }
        .confirm-title {
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .confirm-desc {
            font-size: .85rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 22px;
            line-height: 1.5;
        }
        .confirm-actions {
            display: flex;
            gap: 10px;
        }
        .confirm-actions button {
            flex: 1;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: .85rem;
            cursor: pointer;
            transition: all .15s;
        }
        .confirm-actions .btn-cancel-act {
            background: #f1f5f9;
            color: #475569;
        }
        .confirm-actions .btn-cancel-act:hover { background: #e2e8f0; }
        .confirm-actions .btn-confirm-act {
            background: #dc2626;
            color: #fff;
        }
        .confirm-actions .btn-confirm-act:hover { background: #b91c1c; }
        .confirm-actions .btn-confirm-act.muted {
            background: #64748b;
        }
        .confirm-actions .btn-confirm-act.muted:hover { background: #475569; }

        .order-card-slim {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: .85rem; transition: box-shadow .15s;
        }
        .order-card-slim:hover { box-shadow: 0 4px 12px rgba(0,0,0,.04); }

        .kot-card-new {
            border: 1px solid #e2e8f0; border-radius: 10px; background: #fff;
            overflow: hidden; transition: box-shadow .15s;
        }
        .kot-card-new:hover { box-shadow: 0 4px 12px rgba(0,0,0,.04); }
        .kot-card-new .kot-hd {
            background: #f8fafc; padding: .75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .kot-card-new .kot-bd { padding: .75rem 1rem; }
        .kot-card-new .kot-item-li {
            display: flex; justify-content: space-between; align-items: center;
            padding: .4rem .5rem; border-radius: 6px; margin-bottom: .3rem;
            cursor: pointer; transition: background .1s; border-left: 3px solid transparent;
        }
        .kot-card-new .kot-item-li:hover { background: #f8fafc; }
        .kot-card-new .kot-item-li.status-pending { border-left-color: #f59e0b; background: #fffbeb; }
        .kot-card-new .kot-item-li.status-served { border-left-color: #22c55e; background: #f0fdf4; }
        .kot-card-new .kot-item-li.status-preparing { border-left-color: #3b82f6; background: #eff6ff; }

        .qty-ctrl { display: inline-flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .qty-ctrl button { border: none; background: #f8fafc; width: 26px; height: 26px; font-size: .8rem; cursor: pointer; transition: all .1s; }
        .qty-ctrl button:hover { background: #dc2626; color: #fff; }
        .qty-ctrl span { padding: 0 .5rem; font-size: .8rem; font-weight: 600; min-width: 28px; text-align: center; background: #fff; }

        @media (max-width: 767px) {
            .table-ov-card { width: 100px; padding: .75rem .5rem; }
            .table-ov-name { font-size: .9rem; }
        }
    </style>
@endpush

@push('scripts')
<script>
    let cart = [];
    let currentTable = { id: null, name: '' };
    let isOrderCreating = false;

    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.content-pane').forEach(c => c.classList.add('d-none'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.remove('d-none');
            if (btn.dataset.target === 'kot') loadKOTs();
        });
    });

    function handleTableClick(name, id, status) {
        if (status === 'occupied') {
            window.location.href = `/admin/orders/table/${id}/edit`;
        } else {
            currentTable = { id, name };
            document.getElementById('selectedTableName').textContent = name;
            cart = [];
            updateCartDisplay();
            new bootstrap.Modal(document.getElementById('addItemsModal')).show();
        }
    }

    document.querySelectorAll('.menu-categories button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.menu-categories button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const menuId = btn.dataset.menuId;
            document.querySelectorAll('.dish-card-wrap').forEach(d => {
                d.style.display = (menuId === 'all' || d.dataset.menuId === menuId) ? '' : 'none';
            });
            document.getElementById('menuTitle').textContent = btn.textContent.split('\n')[0].trim();
        });
    });

    document.getElementById('dishSearch').addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.dish-card-wrap').forEach(d => {
            const name = d.querySelector('h6').textContent.toLowerCase();
            const desc = d.querySelector('.text-muted').textContent.toLowerCase();
            d.style.display = (name.includes(q) || desc.includes(q)) ? '' : 'none';
        });
    });

    function updateItemSize(index, change) {
        if (!cart[index]) return;
        const ns = Math.round((cart[index].size + change) * 10) / 10;
        if (ns >= .5 && ns <= 1) {
            cart[index].size = ns;
            cart[index].price = cart[index].basePrice * ns;
            updateCartDisplay();
        }
    }

    function updateCartDisplay() {
        const cc = document.getElementById('cartItems');
        const cb = document.getElementById('clearCartBtn');
        const co = document.getElementById('createOrderBtn');
        const tq = document.getElementById('totalQty');
        const ta = document.getElementById('totalAmount');
        if (!cart.length) {
            cc.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i><small>No items added</small></div>';
            cb.style.display = 'none';
            if (co) { co.disabled = true; }
            tq.textContent = '0';
            ta.textContent = '0.00';
            return;
        }
        cb.style.display = 'block';
        if (co) co.disabled = isOrderCreating;
        let html = '', totQ = 0, totA = 0;
        cart.forEach((item, i) => {
            const it = item.price * item.quantity;
            totQ += item.quantity;
            totA += it;
            const sl = item.size === .5 ? 'Half' : item.size === 1 ? 'Full' : '';
            html += `<div class="cart-item">
                <div class="cart-item-left">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rs ${item.price.toFixed(0)}</div>
                    </div>
                </div>
                <div class="size-controls">
                    <button class="size-btn" onclick="updateItemSize(${i},-0.5)" ${item.size<=.5?'disabled':''}><i class="bi bi-dash"></i></button>
                    <span class="size-display">${sl}</span>
                    <button class="size-btn" onclick="updateItemSize(${i},0.5)" ${item.size>=1?'disabled':''}><i class="bi bi-plus"></i></button>
                </div>
                <div class="qty-ctrl">
                    <button onclick="updateQuantity(${i},-1)">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${i},1)">+</button>
                </div>
                <button class="remove-item-btn" onclick="removeItem(${i})"><i class="bi bi-x"></i></button>
            </div>`;
        });
        cc.innerHTML = html;
        tq.textContent = totQ;
        ta.textContent = totA.toFixed(0);
    }

    function updateQuantity(index, change) {
        if (!cart[index]) return;
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) cart.splice(index, 1);
        updateCartDisplay();
    }

    function removeItem(index) { cart.splice(index, 1); updateCartDisplay(); }

    document.getElementById('clearCartBtn').addEventListener('click', () => {
        if (confirm('Clear all items?')) { cart = []; updateCartDisplay(); }
    });

    document.getElementById('createOrderBtn').addEventListener('click', async () => {
        if (!cart.length || !currentTable.id || isOrderCreating) return;
        isOrderCreating = true;
        const btn = document.getElementById('createOrderBtn');
        btn.disabled = true;
        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.querySelector('.btn-text').classList.add('d-none');
        try {
            const r = await fetch('/admin/orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    table_id: currentTable.id,
                    waiter_id: document.getElementById('waiterSelect').value || null,
                    no_of_guests: document.getElementById('guestCount').value || null,
                    notes: document.getElementById('orderNotes').value,
                    items: cart.map(item => ({ menu_item_id: item.id, quantity: item.quantity, unit_price: item.price, size: item.size }))
                })
            });
            const d = await r.json();
            if (d.success) {
                showToast('success', 'Order created');
                cart = []; updateCartDisplay();
                bootstrap.Modal.getInstance(document.getElementById('addItemsModal')).hide();
                loadRecentOrders();
                setTimeout(() => location.reload(), 800);
            } else { showToast('error', d.message); }
        } catch (e) { showToast('error', 'Failed to create order'); }
        finally {
            isOrderCreating = false;
            btn.querySelector('.spinner-border').classList.add('d-none');
            btn.querySelector('.btn-text').classList.remove('d-none');
            btn.disabled = !cart.length;
        }
    });

    function addToCart(dishId, dishName, price, image) {
        const idx = cart.findIndex(i => i.id === dishId && i.size === 1);
        if (idx > -1) { cart[idx].quantity += 1; }
        else { cart.push({ id: dishId, name: dishName, basePrice: parseFloat(price), price: parseFloat(price), quantity: 1, size: 1, image: image || 'https://via.placeholder.com/48' }); }
        updateCartDisplay();
    }

    async function loadKOTs() {
        try {
            const r = await fetch('/admin/orders/active', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const d = await r.json();
            const ct = document.getElementById('kotList');
            if (!d.success || !d.orders.length) { ct.innerHTML = '<div class="col-12"><p class="text-center text-muted py-4">No active KOTs</p></div>'; return; }
            ct.innerHTML = d.orders.map(order => `
                <div class="col-md-4">
                    <div class="kot-card-new">
                        <div class="kot-hd">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">#${order.order_no}</h6>
                                <small class="text-muted">${formatDate(order.created_at)}</small>
                            </div>
                            <small class="text-muted">Table: ${order.table.name} ${order.waiter ? '| Waiter: '+order.waiter.name : ''}</small>
                        </div>
                        <div class="kot-bd">
                            ${order.items.map(item => `
                                <div class="kot-item-li status-${item.status}" onclick="toggleItemStatus(${item.id},'${item.status}')">
                                    <div class="d-flex align-items-center gap-2"><span style="font-size:.82rem;">${item.name}</span></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold" style="font-size:.82rem;">x${item.quantity}</span>
                                        <span class="dash-badge ${item.status==='pending'?'bg-warning text-dark':item.status==='preparing'?'bg-info text-white':item.status==='served'?'bg-secondary text-white':''}" style="font-size:.65rem;padding:1px 8px;">${item.status}</span>
                                    </div>
                                </div>
                            `).join('')}
                            <hr class="my-2">
                            <div class="d-flex justify-content-between small fw-bold">
                                <span>Items: ${order.items.reduce((s,i)=>s+i.quantity,0)}</span>
                                <span>Rs ${parseFloat(order.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch(e) {}
    }

    async function toggleItemStatus(itemId, currentStatus) {
        const ns = currentStatus === 'pending' ? 'served' : 'pending';
        try {
            const r = await fetch(`/admin/order-items/${itemId}/status`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ status: ns })
            });
            const d = await r.json();
            if (d.success) { showToast('success', `Item ${ns}`); loadKOTs(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed'); }
    }

    function refreshKOTs() { loadKOTs(); showToast('success', 'KOTs refreshed'); }

    async function loadRecentOrders() {
        try {
            const r = await fetch('/admin/orders/recent', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const d = await r.json();
            if (!d.success) return;
            const ct = document.getElementById('ordersTableBody');
            ct.innerHTML = d.tables.map(table => {
                const oh = table.orders.length ? table.orders.map(order => {
                    const items = order.items.map(item => `
                        <li class="d-flex justify-content-between align-items-center py-1" style="font-size:.82rem;border-bottom:1px solid #f1f5f9;">
                            <span class="${item.status==='cancelled'?'text-decoration-line-through text-muted':''}">${item.name}</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="${item.status==='cancelled'?'text-decoration-line-through':''}">x${item.qty}</span>
                                ${item.status!=='cancelled'?`<button class="order-action-btn danger xs" onclick="event.stopPropagation();cancelOrderItem(${item.id},'${item.name.replace(/'/g,"\\'")}')" title="Cancel item"><i class="bi bi-x"></i></button>`:''}
                            </div>
                        </li>
                    `).join('');
                    return `<div class="border-bottom pb-2 mb-2" style="font-size:.82rem;">
                        <div class="d-flex justify-content-between small"><span class="fw-medium text-capitalize">${order.status}</span><span class="text-muted">${order.created_at}</span></div>
                        <ul class="list-unstyled mt-1 mb-1">${items}</ul>
                        <div class="d-flex justify-content-between small border-top pt-1"><span>Items: ${order.items_count}</span><span class="fw-bold">Rs ${parseFloat(order.total_amount).toFixed(2)}</span></div>
                        <div class="order-actions">
                            <button class="order-action-btn danger" onclick="event.stopPropagation();cancelOrder(${order.id})" title="Cancel order"><i class="bi bi-x-circle"></i> Cancel</button>
                            ${order.status==='pending'?`<button class="order-action-btn muted" onclick="event.stopPropagation();confirmAction('delete', ${order.id})" title="Delete order"><i class="bi bi-trash"></i> Delete</button>`:''}
                        </div>
                    </div>`;
                }).join('') : '<p class="text-muted small py-2 mb-0">No recent orders</p>';

                return `<div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="order-card-link" style="cursor:pointer;" onclick="window.location.href='/admin/orders/table/${table.id}/edit'">
                        <div class="order-card-slim">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">${table.name}</h6>
                                <span class="dash-badge ${table.status==='available'?'bg-success text-white':'bg-danger text-white'}">${table.status}</span>
                            </div>
                            ${oh}
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch(e) { console.warn('Failed to load orders:', e); }
    }

    function formatDate(ds) {
        return new Date(ds).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // ── Modern Confirmation Modal ──
    let pendingAction = null;

    function confirmAction(type, id, itemName) {
        const overlay = document.getElementById('confirmOverlay');
        const icon = document.getElementById('confirmIcon');
        const title = document.getElementById('confirmTitle');
        const desc = document.getElementById('confirmDesc');
        const proceed = document.getElementById('confirmProceed');

        if (type === 'cancel-order') {
            icon.className = 'confirm-icon warning';
            icon.innerHTML = '<i class="bi bi-x-circle"></i>';
            title.textContent = 'Cancel Order?';
            desc.textContent = 'This will cancel all items and delete the order. The table will be freed for new orders.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Cancel Order';
            pendingAction = function() { executeCancelOrder(id); };
        } else if (type === 'cancel-item') {
            icon.className = 'confirm-icon warning';
            icon.innerHTML = '<i class="bi bi-dash-circle"></i>';
            title.textContent = 'Cancel Item?';
            desc.textContent = 'Remove "' + itemName + '" from this order.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Cancel Item';
            pendingAction = function() { executeCancelOrderItem(id); };
        } else if (type === 'delete') {
            icon.className = 'confirm-icon danger';
            icon.innerHTML = '<i class="bi bi-trash"></i>';
            title.textContent = 'Delete Order?';
            desc.textContent = 'This will permanently delete the entire order and all its items. Cannot be undone.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Delete Permanently';
            pendingAction = function() { executeDeleteOrder(id); };
        } else {
            icon.className = 'confirm-icon danger';
            icon.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
            title.textContent = 'Are you sure?';
            desc.textContent = 'This action cannot be undone.';
            proceed.className = 'btn-confirm-act';
            proceed.textContent = 'Confirm';
            pendingAction = function() { if (typeof id === 'function') id(); };
        }

        overlay.classList.add('active');
    }

    document.getElementById('confirmCancel').addEventListener('click', function() {
        document.getElementById('confirmOverlay').classList.remove('active');
        pendingAction = null;
    });

    document.getElementById('confirmProceed').addEventListener('click', function() {
        document.getElementById('confirmOverlay').classList.remove('active');
        if (pendingAction) {
            pendingAction();
            pendingAction = null;
        }
    });

    document.getElementById('confirmOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            pendingAction = null;
        }
    });

    async function executeCancelOrder(orderId) {
        try {
            const r = await fetch(`/admin/orders/${orderId}/cancel`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' } });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to cancel order'); }
    }

    async function executeCancelOrderItem(itemId) {
        try {
            const r = await fetch('/admin/order-items/'+itemId+'/cancel', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ reason: '' }) });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to cancel item'); }
    }

    async function executeDeleteOrder(orderId) {
        try {
            const r = await fetch(`/admin/orders/${orderId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const d = await r.json();
            if (d.success) { showToast('success', d.message); loadRecentOrders(); }
            else showToast('error', d.message);
        } catch(e) { showToast('error', 'Failed to delete order'); }
    }

    // Legacy wrappers with modal
    async function cancelOrder(orderId) { confirmAction('cancel-order', orderId); }
    async function cancelOrderItem(itemId, itemName) { confirmAction('cancel-item', itemId, itemName); }
    async function deleteOrder(orderId) { confirmAction('delete', orderId); }

    function refreshOrders() { loadRecentOrders(); showToast('success', 'Refreshed'); }

    document.addEventListener('DOMContentLoaded', () => loadRecentOrders());
</script>
@endpush
