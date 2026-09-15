<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Display - {{ $siteName ?? 'RestaurantPro' }}</title>

    @if($faviconUrl ?? false)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon"/>
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f0f1a;
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Header Bar ── */
        .ktd-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .ktd-header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ktd-header h1 i { color: #f59e0b; font-size: 1.6rem; }
        .ktd-header .ktd-badge {
            background: rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .75rem;
            color: #94a3b8;
        }

        /* ── Filter Tabs ── */
        .ktd-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .ktd-filters .btn-filter {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            color: #94a3b8;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: .8rem;
            font-weight: 500;
            transition: all .2s;
            cursor: pointer;
        }
        .ktd-filters .btn-filter:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .ktd-filters .btn-filter.active {
            background: #f59e0b;
            border-color: #f59e0b;
            color: #000;
        }
        .ktd-filters .btn-filter .count {
            display: inline-block;
            background: rgba(0,0,0,.2);
            border-radius: 10px;
            padding: 0 8px;
            margin-left: 6px;
            font-size: .7rem;
        }
        .ktd-filters .btn-filter.active .count { background: rgba(0,0,0,.15); }

        /* ── Grid ── */
        .ktd-grid {
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
        }

        /* ── Order Card ── */
        .ktd-card {
            background: linear-gradient(145deg, #1e1e32, #1a1a2e);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.06);
            overflow: hidden;
            transition: transform .15s, box-shadow .15s;
            display: flex;
            flex-direction: column;
        }
        .ktd-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,.3);
        }

        .ktd-card.priority { border-left: 4px solid #ef4444; }
        .ktd-card.preparing { border-left: 4px solid #3b82f6; }
        .ktd-card.ready { border-left: 4px solid #22c55e; opacity: .85; }

        .ktd-card-head {
            padding: 12px 14px 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }
        .ktd-card-head .order-no {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: #fff;
            word-break: break-all;
        }
        .ktd-card-head .table-name {
            font-size: .82rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }
        .ktd-card-head .badge-status {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 3px 10px;
            border-radius: 6px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .ktd-card-head .badge-status.pending { background: rgba(245,158,11,.15); color: #f59e0b; }
        .ktd-card-head .badge-status.preparing { background: rgba(59,130,246,.15); color: #60a5fa; }
        .ktd-card-head .badge-status.ready { background: rgba(34,197,94,.15); color: #4ade80; }
        .ktd-card-head .badge-status.served { background: rgba(34,197,94,.15); color: #4ade80; }

        .ktd-card-body {
            padding: 4px 14px 10px;
            flex: 1;
        }
        .ktd-card-body .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .ktd-card-body .item-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid rgba(255,255,255,.04);
            font-size: .85rem;
            gap: 8px;
        }
        .ktd-card-body .item-list li:last-child { border-bottom: none; }
        .ktd-card-body .item-list li .qty {
            font-weight: 700;
            color: #f59e0b;
            min-width: 28px;
            text-align: right;
        }
        .ktd-card-body .item-list li .name {
            flex: 1;
            color: #e2e8f0;
        }
        .ktd-card-body .item-list li .item-status {
            font-size: .65rem;
            padding: 2px 8px;
            border-radius: 4px;
            white-space: nowrap;
        }
        .ktd-card-body .item-list li .item-status.pending { background: rgba(245,158,11,.12); color: #f59e0b; }
        .ktd-card-body .item-list li .item-status.preparing { background: rgba(59,130,246,.12); color: #60a5fa; }
        .ktd-card-body .item-list li .item-status.ready { background: rgba(34,197,94,.12); color: #4ade80; }

        .ktd-card-foot {
            padding: 8px 14px 12px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            border-top: 1px solid rgba(255,255,255,.04);
        }
        .ktd-card-foot .btn-action {
            flex: 1;
            min-width: 60px;
            border: none;
            border-radius: 8px;
            padding: 8px 6px;
            font-size: .72rem;
            font-weight: 600;
            transition: all .15s;
            cursor: pointer;
            text-align: center;
        }
        .ktd-card-foot .btn-action:active { transform: scale(.96); }
        .ktd-card-foot .btn-prepare { background: rgba(59,130,246,.15); color: #60a5fa; }
        .ktd-card-foot .btn-prepare:hover { background: rgba(59,130,246,.3); }
        .ktd-card-foot .btn-ready { background: rgba(34,197,94,.15); color: #4ade80; }
        .ktd-card-foot .btn-ready:hover { background: rgba(34,197,94,.3); }
        .ktd-card-foot .btn-served { background: rgba(139,92,246,.15); color: #a78bfa; }
        .ktd-card-foot .btn-served:hover { background: rgba(139,92,246,.3); }

        /* ── Empty State ── */
        .ktd-empty {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            color: #475569;
        }
        .ktd-empty i { font-size: 4rem; margin-bottom: 16px; opacity: .4; }
        .ktd-empty h3 { font-size: 1.2rem; font-weight: 600; color: #64748b; margin-bottom: 6px; }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .ktd-grid { grid-template-columns: 1fr; padding: 10px; gap: 10px; }
            .ktd-header { padding: 10px 14px; }
            .ktd-header h1 { font-size: 1.1rem; }
            .ktd-card-head { flex-direction: column; }
            .ktd-card-head .order-no { font-size: .95rem; }
        }
        @media (min-width: 1200px) {
            .ktd-grid { grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); }
        }
        @media (min-width: 1600px) {
            .ktd-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .ktd-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .ktd-header-actions .btn-logout {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            color: #94a3b8;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: .78rem;
            transition: all .2s;
            text-decoration: none;
        }
        .ktd-header-actions .btn-logout:hover {
            background: rgba(239,68,68,.15);
            border-color: rgba(239,68,68,.3);
            color: #ef4444;
        }
        .ktd-header-actions .last-update {
            font-size: .7rem;
            color: #475569;
        }

        #toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column-reverse;
            gap: 10px;
            pointer-events: none;
        }

        .st-toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            padding-right: 40px;
            border-radius: 12px;
            background: #1a1a2e;
            color: #fff;
            font-size: .875rem;
            font-weight: 500;
            line-height: 1.4;
            box-shadow: 0 8px 32px rgba(0,0,0,.25), 0 2px 8px rgba(0,0,0,.15);
            min-width: 300px;
            max-width: 420px;
            position: relative;
            overflow: hidden;
            pointer-events: auto;
            transform: translateX(120%);
            transition: transform .35s cubic-bezier(.16,1,.3,1), opacity .35s ease;
            opacity: 0;
        }

        .st-toast.st-toast-in {
            transform: translateX(0);
            opacity: 1;
        }

        .st-toast.st-toast-out {
            transform: translateX(120%);
            opacity: 0;
        }

        .st-toast-icon {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .st-toast-success .st-toast-icon { background: rgba(34,197,94,.15); color: #4ade80; }
        .st-toast-error .st-toast-icon { background: rgba(239,68,68,.15); color: #ef4444; }
        .st-toast-warning .st-toast-icon { background: rgba(245,158,11,.15); color: #f59e0b; }
        .st-toast-info .st-toast-icon { background: rgba(59,130,246,.15); color: #60a5fa; }

        .st-toast-msg { flex: 1; color: #e2e8f0; }

        .st-toast-close {
            position: absolute;
            top: 6px;
            right: 8px;
            background: none;
            border: none;
            color: #475569;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
            transition: color .15s;
        }
        .st-toast-close:hover { color: #94a3b8; }

        .st-toast-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 2px 2px 0;
        }
        .st-toast-success .st-toast-bar { background: #22c55e; }
        .st-toast-error .st-toast-bar { background: #ef4444; }
        .st-toast-warning .st-toast-bar { background: #f59e0b; }
        .st-toast-info .st-toast-bar { background: #3b82f6; }

        @media (max-width: 480px) {
            #toast-container {
                left: 12px;
                right: 12px;
                bottom: 12px;
            }
            .st-toast {
                min-width: 0;
                max-width: none;
                padding: 12px 14px;
                padding-right: 36px;
                font-size: .82rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <header class="ktd-header">
        <h1><i class="bi bi-fire"></i> {{ $siteName ?? 'RestaurantPro' }} <span class="ktd-badge">Kitchen Display</span></h1>
        <div class="ktd-header-actions">
            <span class="last-update" id="lastUpdate"></span>
            <div class="ktd-filters">
                <button class="btn-filter active" data-filter="all">All <span class="count" id="countAll">0</span></button>
                <button class="btn-filter" data-filter="pending">Pending <span class="count" id="countPending">0</span></button>
                <button class="btn-filter" data-filter="preparing">Preparing <span class="count" id="countPreparing">0</span></button>
                <button class="btn-filter" data-filter="ready">Ready <span class="count" id="countReady">0</span></button>
            </div>
            <a href="{{ route('logout') }}" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
        </div>
    </header>

    <div class="ktd-grid" id="ktdGrid">
        @yield('ktd-content')
    </div>

    <script src="{{ asset('assets/js/toaster.js') }}"></script>

    @if(session('success'))
    <script>showToast('success', '{{ session('success') }}');</script>
    @endif
    @if(session('error'))
    <script>showToast('error', '{{ session('error') }}');</script>
    @endif

    <script>
        async function updateOrderStatus(orderId, action) {
            const statusMap = { prepare: 'preparing', ready: 'ready', served: 'served' };
            const status = statusMap[action];
            if (!status) return;

            const btn = document.querySelector(`.btn-action[data-order-id="${orderId}"][data-action="${action}"]`);
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;"></span>';
            }

            try {
                const r = await fetch('/admin/kitchen-display/order/' + orderId + '/status', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: status }),
                });
                const d = await r.json();
                if (d.success) {
                    if (window.showToast) showToast('success', d.message);
                    setTimeout(function() { location.reload(); }, 600);
                } else {
                    if (window.showToast) showToast('error', d.message);
                }
            } catch (e) {
                if (window.showToast) showToast('error', 'Failed to update status');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    const labels = { prepare: 'Prepare', ready: 'Ready', served: 'Served' };
                    const icons = {
                        prepare: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
                        ready: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
                        served: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
                    };
                    btn.innerHTML = icons[action] + ' ' + labels[action];
                }
            }
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-action');
            if (!btn) return;

            const action = btn.dataset.action;
            const orderId = btn.dataset.orderId;
            if (!action || !orderId) return;

            updateOrderStatus(orderId, action);
        });

        // Filter tabs
        document.querySelectorAll('.btn-filter').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                document.querySelectorAll('.ktd-card').forEach(card => {
                    if (filter === 'all') { card.style.display = ''; return; }
                    card.style.display = card.dataset.status === filter ? '' : 'none';
                });
            });
        });

        // Auto-refresh
        let refreshTimeout;

        function refreshKTD() {
            clearTimeout(refreshTimeout);
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html, */*' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newGrid = doc.getElementById('ktdGrid');
                    if (newGrid) {
                        document.getElementById('ktdGrid').innerHTML = newGrid.innerHTML;
                    }
                    const newUpdate = doc.getElementById('lastUpdate');
                    if (newUpdate) {
                        document.getElementById('lastUpdate').textContent = newUpdate.textContent;
                    }
                    // Update counts
                    const cards = document.querySelectorAll('.ktd-card');
                    const counts = { all: cards.length, pending: 0, preparing: 0, ready: 0 };
                    cards.forEach(c => { const s = c.dataset.status; if (counts.hasOwnProperty(s)) counts[s]++; });
                    Object.keys(counts).forEach(k => {
                        const el = document.getElementById('count' + k.charAt(0).toUpperCase() + k.slice(1));
                        if (el) el.textContent = counts[k];
                    });
                })
                .catch(() => {});
            refreshTimeout = setTimeout(refreshKTD, 15000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            document.getElementById('lastUpdate').textContent = 'Updated ' + now.toLocaleTimeString();
            refreshTimeout = setTimeout(refreshKTD, 15000);
        });
    </script>

    @stack('scripts')
</body>
</html>