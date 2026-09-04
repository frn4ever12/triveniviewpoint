@extends('admin.includes.main')
@section('title', 'Edit Table - ' . $table->name)

@push('styles')
<style>
:root {
    --edit-primary: #dc2626;
    --edit-primary-dark: #b91c1c;
    --edit-primary-light: #fef2f2;
    --edit-success: #059669;
    --edit-success-dark: #047857;
    --edit-warning: #d97706;
    --edit-info: #2563eb;
}

body { background: #f1f5f9; }

.table-banner {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.table-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(220,38,38,0.12) 0%, transparent 70%);
    border-radius: 50%;
}

.table-banner-content { position: relative; z-index: 1; }

.table-banner h3 { font-size: 1.75rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }

.table-banner .table-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    font-size: 0.88rem;
    color: rgba(255,255,255,0.75);
}

.table-banner .table-meta strong { color: #fff; font-weight: 600; }

.banner-actions { position: relative; z-index: 1; display: flex; gap: 0.75rem; flex-wrap: wrap; }

.btn-edit-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.5rem;
    background: var(--edit-primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-edit-primary:hover { background: var(--edit-primary-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,38,38,0.3); }

.btn-edit-success {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.5rem;
    background: var(--edit-success);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-edit-success:hover { background: var(--edit-success-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(5,150,105,0.3); }

.btn-edit-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.5rem;
    background: transparent;
    color: rgba(255,255,255,0.85);
    border: 1.5px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-edit-outline:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.4); }

.section-label {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--edit-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-label::after {
    content: '';
    flex: 1;
    height: 1.5px;
    background: linear-gradient(to right, var(--edit-primary), transparent);
}

.order-card-modern {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.order-card-modern:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}

.order-card-header .order-no { font-weight: 700; font-size: 0.95rem; color: #0f172a; }

.order-card-header .order-time { font-size: 0.8rem; color: #94a3b8; }

.order-card-body { padding: 0.5rem 0; }

.order-item-row {
    display: flex;
    align-items: center;
    padding: 0.6rem 1.25rem;
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s ease;
}

.order-item-row:hover { background: #f8fafc; }

.order-item-row:last-child { border-bottom: none; }

.order-item-row .item-qty {
    font-weight: 700;
    color: #0f172a;
    min-width: 2.5rem;
    font-size: 0.9rem;
}

.order-item-row .item-name { flex: 1; font-size: 0.88rem; color: #334155; font-weight: 500; }

.order-item-row .item-price { font-weight: 600; font-size: 0.88rem; color: #0f172a; min-width: 4.5rem; text-align: right; }

.order-item-row .item-status { min-width: 4.5rem; text-align: right; }

.status-pill {
    display: inline-block;
    padding: 0.15rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-pill.placed { background: #fef3c7; color: #92400e; }
.status-pill.preparing { background: #dbeafe; color: #1e40af; }
.status-pill.ready { background: #d1fae5; color: #065f46; }
.status-pill.served { background: #d1fae5; color: #065f46; }
.status-pill.cancelled { background: #fee2e2; color: #991b1b; }

.order-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 1.25rem;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    border-radius: 0 0 14px 14px;
}

.order-card-footer .total-amount { font-weight: 700; font-size: 1.05rem; color: var(--edit-primary); }

.order-card-footer .waiter-info { font-size: 0.8rem; color: #64748b; display: flex; align-items: center; gap: 0.35rem; }

.order-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    background: transparent;
    text-decoration: none;
}

.order-action-btn.danger {
    color: #dc2626;
    border-color: #fecaca;
    background: #fef2f2;
}

.order-action-btn.danger:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
    box-shadow: 0 2px 8px rgba(220,38,38,0.2);
}

.order-action-btn.muted {
    color: #64748b;
    border-color: #e2e8f0;
    background: #f8fafc;
}

.order-action-btn.muted:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #cbd5e1;
}

.cancel-item-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 6px;
    border: 1.5px solid #fecaca;
    background: #fef2f2;
    color: #dc2626;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
}

.cancel-item-btn:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
    box-shadow: 0 2px 8px rgba(220,38,38,0.25);
}

.cancel-item-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.cancel-item-btn:disabled:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
    box-shadow: none;
}

.kot-card-modern {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.kot-card-modern:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.kot-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 1.25rem;
    background: var(--edit-primary-light);
    border-bottom: 1px solid #fecaca;
    border-radius: 14px 14px 0 0;
}

.kot-card-header .kot-number { font-weight: 700; font-size: 0.9rem; color: var(--edit-primary-dark); }

.kot-item-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 1.25rem;
    border-bottom: 1px solid #fef2f2;
    font-size: 0.85rem;
    color: #475569;
}

.kot-item-row:last-child { border-bottom: none; }

.kot-card-footer { padding: 0.75rem 1.25rem; border-top: 1px solid #f1f5f9; }

/* Add Items Modal */
.modern-modal .modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

.modern-modal .modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 1.25rem 1.5rem;
}

.modern-modal .modal-body { padding: 1.5rem; }

.category-sidebar {
    background: #f8fafc;
    border-radius: 12px;
    padding: 0.5rem;
    max-height: 380px;
    overflow-y: auto;
}

.category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.65rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    font-size: 0.85rem;
    font-weight: 500;
    color: #475569;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.category-item:hover { background: #e2e8f0; color: #0f172a; }

.category-item.active { background: var(--edit-primary); color: #fff; font-weight: 600; }

.category-item .count-badge {
    background: rgba(0,0,0,0.08);
    padding: 0.1rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
}

.category-item.active .count-badge { background: rgba(255,255,255,0.2); color: #fff; }

.dish-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.85rem;
    max-height: 480px;
    overflow-y: auto;
    padding-right: 0.25rem;
}

.dish-card-modern {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
    cursor: pointer;
}

.dish-card-modern:hover { border-color: var(--edit-primary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

.dish-card-modern .dish-img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    display: block;
}

.dish-card-modern .dish-body { padding: 0.65rem; }

.dish-card-modern .dish-name { font-weight: 600; font-size: 0.82rem; color: #0f172a; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.dish-card-modern .dish-price { font-weight: 700; font-size: 0.9rem; color: var(--edit-primary); }

.dish-card-modern .add-btn {
    width: 100%;
    margin-top: 0.4rem;
    padding: 0.35rem;
    border: 1.5px solid var(--edit-primary);
    background: transparent;
    color: var(--edit-primary);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}

.dish-card-modern .add-btn:hover { background: var(--edit-primary); color: #fff; }

.cart-panel {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.cart-header h6 { font-weight: 700; color: #0f172a; margin: 0; }

.cart-items-container {
    flex: 1;
    max-height: 260px;
    overflow-y: auto;
    margin-bottom: 0.75rem;
}

.cart-item-modern {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.55rem 0.65rem;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 0.4rem;
    border: 1px solid #e2e8f0;
}

.cart-item-modern .ci-name { flex: 1; font-size: 0.82rem; font-weight: 500; color: #0f172a; }

.cart-item-modern .ci-price { font-size: 0.82rem; font-weight: 600; color: var(--edit-primary); }

.qty-stepper {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.qty-stepper button {
    border: none;
    background: #f1f5f9;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 700;
    color: #475569;
    transition: background 0.15s ease;
}

.qty-stepper button:hover { background: #e2e8f0; }

.qty-stepper span {
    min-width: 28px;
    text-align: center;
    font-size: 0.82rem;
    font-weight: 600;
    color: #0f172a;
    background: #fff;
}

.cart-remove-btn {
    border: none;
    background: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.15rem;
    transition: color 0.15s ease;
    font-size: 1rem;
}

.cart-remove-btn:hover { color: var(--edit-primary); }

.cart-summary {
    background: #fff;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: auto;
}

.cart-summary .cs-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #475569;
}

.cart-summary .cs-total {
    font-weight: 700;
    font-size: 1rem;
    color: #0f172a;
    border-top: 1px solid #e2e8f0;
    padding-top: 0.5rem;
    margin-top: 0.3rem;
}

.empty-orders {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
}

.empty-orders i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4; }

.empty-orders h5 { color: #64748b; font-size: 1rem; }

.empty-orders p { font-size: 0.85rem; margin: 0; }

.search-box {
    position: relative;
}

.search-box input {
    padding-left: 2.25rem;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    font-size: 0.85rem;
}

.search-box input:focus { border-color: var(--edit-primary); box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }

.search-box .search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
}

.loading-dots::after {
    content: '';
    animation: dots 1.5s steps(4, end) infinite;
}

@keyframes dots {
    0% { content: ''; }
    25% { content: '.'; }
    50% { content: '..'; }
    75% { content: '...'; }
}

.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }

/* ── Modern Confirm Modal ── */
.confirm-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.6);
    backdrop-filter: blur(4px);
    z-index: 99999;
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

@media (max-width: 768px) {
    .table-banner { padding: 1.25rem; }
    .table-banner .table-meta { gap: 1rem; font-size: 0.82rem; }
    .banner-actions { width: 100%; }
    .banner-actions .btn-edit-primary,
    .banner-actions .btn-edit-success,
    .banner-actions .btn-edit-outline { flex: 1; justify-content: center; font-size: 0.82rem; padding: 0.5rem 1rem; }
    .order-card-header { flex-direction: column; gap: 0.3rem; align-items: flex-start; }
    .dish-grid-modern { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); }
}
</style>
@endpush

@section('content')
{{-- Modern Confirm Action Modal --}}
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-modal">
        <div class="confirm-icon" id="confirmIcon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="confirm-title" id="confirmTitle">Are you sure?</div>
        <div class="confirm-desc" id="confirmDesc">This action cannot be undone.</div>
        <div class="mb-3" id="reasonField" style="display:none;">
            <label style="font-size:.78rem;font-weight:600;color:#475569;display:block;margin-bottom:4px;">Reason (optional)</label>
            <input type="text" id="cancelReason" class="form-control" placeholder="Why is this being cancelled?" style="border-radius:8px;font-size:.85rem;">
        </div>
        <div class="confirm-actions">
            <button class="btn-cancel-act" id="confirmCancel">Cancel</button>
            <button class="btn-confirm-act" id="confirmProceed">Confirm</button>
        </div>
    </div>
</div>

<div class="container-fluid py-3">
    <div class="toast-container"></div>

    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn-edit-outline" style="color:#475569;border-color:#cbd5e1;">
            <i data-feather="arrow-left" width="16" height="16"></i> Back
        </a>
    </div>

    <div class="table-banner mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="table-banner-content">
                    <h3><i data-feather="grid" width="22" height="22" style="margin-right:0.5rem;"></i>{{ $table->name }}</h3>
                    <div class="table-meta">
                        <span>Status: <strong>{{ $table->status->value ?? $table->status }}</strong></span>
                        <span>Active Orders: <strong>{{ $activeOrders->count() }}</strong></span>
                        <span>Total KOTs: <strong>{{ $allKots->count() }}</strong></span>
                        @if($activeOrders->isNotEmpty())
                            @php
                                $totalAmount = $activeOrders->sum(fn($o) => $o->invoice ? $o->invoice->total_amount : 0);
                            @endphp
                            <span>Total: <strong>Rs {{ number_format($totalAmount, 2) }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-5 mt-3 mt-md-0">
                <div class="banner-actions justify-content-md-end">
                    @if($activeOrders->isNotEmpty())
                        <a href="{{ route('admin.orders.table.checkout', $table->id) }}" class="btn-edit-success">
                            <i data-feather="credit-card" width="16" height="16"></i> Checkout
                        </a>
                    @endif
                    <button class="btn-edit-primary" data-bs-toggle="modal" data-bs-target="#addItemsModal">
                        <i data-feather="plus" width="16" height="16"></i> Add Items
                    </button>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="tableTabs" style="border-bottom-color:#e2e8f0;">
        <li class="nav-item">
            <button class="nav-link active" data-tab="orders" style="border:none;border-radius:10px 10px 0 0;padding:0.6rem 1.25rem;font-weight:600;font-size:0.85rem;color:#64748b;">
                <i data-feather="shopping-bag" width="15" height="15" style="margin-right:0.3rem;"></i> Orders ({{ $activeOrders->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-tab="kots" style="border:none;border-radius:10px 10px 0 0;padding:0.6rem 1.25rem;font-weight:600;font-size:0.85rem;color:#64748b;">
                <i data-feather="file-text" width="15" height="15" style="margin-right:0.3rem;"></i> KOT History ({{ $allKots->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tabContent">
        <div class="tab-pane show active" id="pane-orders">
            @forelse($activeOrders as $order)
                <div class="order-card-modern">
                    <div class="order-card-header">
                        <div>
                            <span class="order-no">{{ $order->order_no }}</span>
                            <span class="order-time ms-2">{{ $order->created_at->format('M j, g:i A') }}</span>
                        </div>
                        <span class="status-pill {{ $order->status }}">{{ $order->status }}</span>
                    </div>
                    <div class="order-card-body">
                        @foreach($order->items as $item)
                            <div class="order-item-row @if($item->status === 'cancelled') text-muted text-decoration-line-through @endif">
                                <span class="item-qty">{{ $item->quantity }}x</span>
                                <span class="item-name">{{ $item->dish->name }}</span>
                                <span class="item-price">Rs {{ number_format($item->total, 2) }}</span>
                                <span class="item-status">
                                    <span class="status-pill {{ $item->status }}">{{ $item->status }}</span>
                                    @if($item->status !== 'cancelled')
                                        <button type="button" class="cancel-item-btn" onclick="cancelOrderItem({{ $item->id }}, '{{ $order->order_no }}', '{{ addslashes($item->dish->name) }}')" title="Cancel item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </button>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="order-card-footer" data-order-id="{{ $order->id }}">
                        <div class="d-flex align-items-center gap-2">
                            <span class="waiter-info">
                                @if($order->waiter)<i data-feather="user" width="13" height="13"></i> {{ $order->waiter->name }}@endif
                            </span>
                            @if($order->notes)
                                <span style="font-size:0.78rem;color:#94a3b8;font-style:italic;">"{{ Str::limit($order->notes, 25) }}"</span>
                            @endif
                            <button class="order-action-btn danger" onclick="cancelEntireOrder({{ $order->id }}, '{{ $order->order_no }}')" title="Cancel entire order">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Cancel All
                            </button>
                            <button class="order-action-btn muted" onclick="deleteOrder({{ $order->id }}, '{{ $order->order_no }}')" title="Permanently delete order">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Delete
                            </button>
                        </div>
                        <span class="total-amount">Rs {{ number_format($order->invoice->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-orders">
                    <i data-feather="inbox"></i>
                    <h5>No active orders</h5>
                    <p>Click "Add Items" to create the first order for this table.</p>
                </div>
            @endforelse
        </div>

        <div class="tab-pane" id="pane-kots">
            <div class="row">
                @forelse($allKots as $kot)
                    <div class="col-md-6 col-lg-4">
                        <div class="kot-card-modern">
                            <div class="kot-card-header">
                                <span class="kot-number">{{ $kot->kot_number }}</span>
                                <span class="status-pill {{ $kot->status }}">{{ $kot->status }}</span>
                            </div>
                            <div class="kot-card-body">
                                @forelse($kot->items as $item)
                                    <div class="kot-item-row">
                                        <span>{{ $item->quantity }}× {{ $item->dish->name }}</span>
                                        <span class="status-pill {{ $item->status }}">{{ $item->status }}</span>
                                    </div>
                                @empty
                                    <div class="kot-item-row"><span class="text-muted">No items</span></div>
                                @endforelse
                            </div>
                            <div class="kot-card-footer">
                                <small style="color:#94a3b8;">{{ $kot->sent_at ? $kot->sent_at->format('M j, g:i A') : 'Not sent' }}</small>
                                <button class="btn btn-sm btn-outline-danger reprint-kot-btn" data-kot-id="{{ $kot->id }}" style="border-radius:8px;font-weight:600;font-size:0.78rem;">
                                    <i data-feather="printer" width="13" height="13"></i> Reprint
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-orders">
                            <i data-feather="file-text"></i>
                            <h5>No KOTs found</h5>
                            <p>KOTs will appear here once orders are placed.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade modern-modal" id="addItemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color:#0f172a;">Add Items to {{ $table->name }}</h5>
                <div class="d-flex align-items-center gap-2">
                    @if($activeOrders->isEmpty())
                        <select class="form-select" id="waiterSelect" style="width:auto;font-size:0.85rem;border-radius:8px;">
                            <option value="">Assign Waiter</option>
                            @foreach ($waiters as $waiter)
                                <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="search-box mb-3">
                            <i data-feather="search" class="search-icon" width="16" height="16"></i>
                            <input type="text" class="form-control" placeholder="Search dishes..." id="dishSearch">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="category-sidebar" id="categorySidebar">
                                    <button class="category-item active" data-menu-id="all">
                                        All Items
                                        <span class="count-badge">{{ $dishes->count() }}</span>
                                    </button>
                                    @foreach ($menus as $menu)
                                        <button class="category-item" data-menu-id="{{ $menu->id }}">
                                            {{ $menu->name }}
                                            <span class="count-badge">{{ $menu->dishes->count() }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div style="font-size:0.85rem;font-weight:600;color:#475569;margin-bottom:0.75rem;" id="menuTitle">All Items</div>
                                <div class="dish-grid-modern" id="dishesContainer">
                                    @foreach ($dishes as $dish)
                                        <div class="dish-card-modern" data-menu-id="{{ $dish->menu_id }}" data-dish-id="{{ $dish->id }}">
                                            <img src="{{ $dish->image_url ?: asset('assets/images/defaultfood.png') }}" class="dish-img" alt="{{ $dish->name }}" loading="lazy">
                                            <div class="dish-body">
                                                <div class="dish-name">{{ $dish->name }}</div>
                                                <div class="dish-price">Rs {{ number_format($dish->price, 2) }}</div>
                                                <button type="button" class="add-btn add-dish-btn"
                                                    data-id="{{ $dish->id }}"
                                                    data-name="{{ $dish->name }}"
                                                    data-price="{{ $dish->price }}"
                                                    data-image="{{ $dish->image_url }}">+ Add</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cart-panel">
                            <div class="cart-header">
                                <h6>Selected Items</h6>
                                <button class="btn btn-link btn-sm text-danger p-0" id="clearCartBtn" style="display:none;font-size:0.78rem;">Clear All</button>
                            </div>
                            <div class="cart-items-container" id="cartItems">
                                <div style="text-align:center;padding:2rem 0;color:#94a3b8;font-size:0.85rem;">No items selected.</div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control" placeholder="Special instructions..." id="orderNotes" rows="2" style="font-size:0.82rem;border-radius:8px;"></textarea>
                            </div>
                            <div class="cart-summary">
                                <div class="cs-row mb-1"><span>Items:</span><span id="totalQty">0</span></div>
                                <div class="cs-total cs-row"><span>Total:</span><span>Rs <span id="totalAmount">0.00</span></span></div>
                            </div>
                            <button class="btn btn-edit-primary w-100 mt-2" id="confirmAddItemsBtn" disabled style="justify-content:center;padding:0.75rem;">
                                <span id="btnLoadingIcon" class="d-none" style="width:18px;height:18px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin 0.8s linear infinite;display:inline-block;"></span>
                                <span id="btnText">{{ $activeOrders->isNotEmpty() ? 'Add to Existing Order' : 'Create Order' }}</span>
                            </button>
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
window.OrderEditConfig = {
    tableId: {{ $table->id }},
    tableName: @json($table->name),
    hasActiveOrders: {{ $activeOrders->isNotEmpty() ? 'true' : 'false' }}
};

// ── Modern Confirmation Modal ──
let pendingAction = null;

function confirmActionEdit(type, id, extra) {
    var overlay = document.getElementById('confirmOverlay');
    var icon = document.getElementById('confirmIcon');
    var title = document.getElementById('confirmTitle');
    var desc = document.getElementById('confirmDesc');
    var proceed = document.getElementById('confirmProceed');
    var reasonField = document.getElementById('reasonField');
    var reasonInput = document.getElementById('cancelReason');

    reasonField.style.display = 'none';
    reasonInput.value = '';

    if (type === 'cancel-item') {
        icon.className = 'confirm-icon warning';
        icon.innerHTML = '<i class="bi bi-dash-circle"></i>';
        title.textContent = 'Cancel Item?';
        desc.textContent = 'Remove "' + (extra || 'this item') + '" from the order.';
        proceed.className = 'btn-confirm-act';
        proceed.textContent = 'Cancel Item';
        reasonField.style.display = 'block';
        pendingAction = function() { executeCancelItemEdit(id, reasonInput.value || ''); };
    } else if (type === 'cancel-order') {
        icon.className = 'confirm-icon warning';
        icon.innerHTML = '<i class="bi bi-x-circle"></i>';
        title.textContent = 'Cancel Order?';
        desc.textContent = 'Order ' + (extra || '') + ' will be cancelled and deleted. The table will be freed.';
        proceed.className = 'btn-confirm-act';
        proceed.textContent = 'Cancel Order';
        pendingAction = function() { executeCancelOrderEdit(id); };
    } else if (type === 'delete') {
        icon.className = 'confirm-icon danger';
        icon.innerHTML = '<i class="bi bi-trash"></i>';
        title.textContent = 'Delete Order?';
        desc.textContent = 'Permanently delete ' + (extra || 'this order') + '? Cannot be undone.';
        proceed.className = 'btn-confirm-act';
        proceed.textContent = 'Delete Permanently';
        pendingAction = function() { executeDeleteOrderEdit(id); };
    }

    overlay.classList.add('active');
    setTimeout(function() { if (reasonField.style.display !== 'none') reasonInput.focus(); }, 100);
}

document.getElementById('confirmCancel').addEventListener('click', function() {
    document.getElementById('confirmOverlay').classList.remove('active');
    document.getElementById('cancelReason').value = '';
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
        document.getElementById('cancelReason').value = '';
        pendingAction = null;
    }
});

function getOrderButtons(orderId) {
    var footer = document.querySelector('.order-card-footer[data-order-id="' + orderId + '"]');
    return footer ? footer.querySelectorAll('button') : [];
}

function executeCancelItemEdit(itemId, itemName) {
    var reason = ''; // Could optionally prompt for reason
    var btn = document.querySelector('.cancel-item-btn[onclick*="' + itemId + '"]') ||
              document.querySelector('button[onclick*="cancelOrderItem(' + itemId + '"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:12px;height:12px;border-width:2px;"></span>';
    }

    fetch('/admin/order-items/' + itemId + '/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ reason: reason || '' }),
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (window.showToast) showToast('success', data.message);
                setTimeout(function () { window.location.reload(); }, 800);
            } else {
                if (window.showToast) showToast('error', data.message || 'Failed to cancel item');
                if (btn) { btn.disabled = false; btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'; }
            }
        })
        .catch(function () {
            if (window.showToast) showToast('error', 'Network error');
            if (btn) { btn.disabled = false; btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'; }
        });
}

function executeCancelOrderEdit(orderId) {
    var btns = getOrderButtons(orderId);
    btns.forEach(function (b) { b.disabled = true; });

    fetch('/admin/orders/' + orderId + '/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (window.showToast) showToast('success', data.message);
                setTimeout(function () { window.location.reload(); }, 800);
            } else {
                if (window.showToast) showToast('error', data.message || 'Failed to cancel order');
                btns.forEach(function (b) { b.disabled = false; });
            }
        })
        .catch(function () {
            if (window.showToast) showToast('error', 'Network error');
            btns.forEach(function (b) { b.disabled = false; });
        });
}

function executeDeleteOrderEdit(orderId) {
    var btns = getOrderButtons(orderId);
    btns.forEach(function (b) { b.disabled = true; });

    fetch('/admin/orders/' + orderId, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (window.showToast) showToast('success', data.message);
                setTimeout(function () { window.location.reload(); }, 800);
            } else {
                if (window.showToast) showToast('error', data.message || 'Failed to delete order');
                btns.forEach(function (b) { b.disabled = false; });
            }
        })
        .catch(function () {
            if (window.showToast) showToast('error', 'Network error');
            btns.forEach(function (b) { b.disabled = false; });
        });
}

// Legacy wrappers using modal
function cancelOrderItem(itemId, orderNo, itemName) {
    confirmActionEdit('cancel-item', itemId, itemName);
}

function cancelEntireOrder(orderId, orderNo) {
    confirmActionEdit('cancel-order', orderId, orderNo);
}

function deleteOrder(orderId, orderNo) {
    confirmActionEdit('delete', orderId, orderNo);
}
</script>
<script src="{{ asset('assets/js/admin/order-edit.js') }}"></script>
@endpush
