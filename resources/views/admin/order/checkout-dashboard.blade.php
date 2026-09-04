<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-name" content="{{ Auth::user()->name ?? 'Staff' }}">
    <title>Checkout Dashboard — {{ $siteName ?? 'Restaurant' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ═══════════════════════════════════════════════════════════
           RESET & VARIABLES
           ═══════════════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cd-primary: #dc2626;
            --cd-primary-dark: #b91c1c;
            --cd-primary-50: #fef2f2;
            --cd-primary-100: #fee2e2;
            --cd-primary-200: #fecaca;
            --cd-white: #ffffff;
            --cd-bg: #f1f5f9;
            --cd-card-bg: #ffffff;
            --cd-text: #0f172a;
            --cd-text-secondary: #475569;
            --cd-muted: #64748b;
            --cd-muted-light: #94a3b8;
            --cd-border: #e2e8f0;
            --cd-border-light: #f1f5f9;
            --cd-success: #16a34a;
            --cd-success-bg: #dcfce7;
            --cd-warning: #d97706;
            --cd-warning-bg: #fef3c7;
            --cd-danger: #dc2626;
            --cd-danger-bg: #fee2e2;
            --cd-info: #2563eb;
            --cd-info-bg: #dbeafe;

            --cd-shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --cd-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.04);
            --cd-shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --cd-shadow-lg: 0 8px 24px rgba(0,0,0,0.1);
            --cd-radius-sm: 8px;
            --cd-radius: 12px;
            --cd-radius-lg: 16px;
            --cd-transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--cd-bg);
            color: var(--cd-text);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ═══════════════════════════════════════════════════════════
           TOP BAR
           ═══════════════════════════════════════════════════════════ */
        .cd-topbar {
            background: var(--cd-white);
            border-bottom: 1px solid var(--cd-border);
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--cd-shadow-sm);
        }

        .cd-topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .cd-topbar-logo {
            width: 36px;
            height: 36px;
            background: var(--cd-primary-100);
            border-radius: var(--cd-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cd-primary);
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .cd-topbar-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--cd-text);
            letter-spacing: -0.02em;
        }

        .cd-topbar-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.55rem;
            border-radius: 20px;
            background: var(--cd-primary-100);
            color: var(--cd-primary);
            margin-left: 0.5rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .cd-topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .cd-topbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--cd-text-secondary);
            padding: 0.35rem 0.75rem 0.35rem 0.5rem;
            background: var(--cd-bg);
            border-radius: 50px;
            border: 1px solid var(--cd-border);
        }

        .cd-topbar-user .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--cd-primary-100);
            color: var(--cd-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .cd-topbar-clock {
            font-size: 0.82rem;
            color: var(--cd-muted);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.65rem;
            background: var(--cd-bg);
            border-radius: 50px;
            border: 1px solid var(--cd-border);
        }

        .cd-btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--cd-radius-sm);
            border: 1px solid var(--cd-border);
            background: var(--cd-white);
            color: var(--cd-muted);
            cursor: pointer;
            transition: var(--cd-transition);
            text-decoration: none;
            font-size: 1rem;
        }

        .cd-btn-icon:hover {
            background: var(--cd-bg);
            color: var(--cd-text);
            border-color: var(--cd-muted-light);
        }

        .cd-btn-icon-danger:hover {
            background: var(--cd-danger-bg);
            color: var(--cd-danger);
            border-color: var(--cd-primary-200);
        }

        /* ═══════════════════════════════════════════════════════════
           CONTAINER
           ═══════════════════════════════════════════════════════════ */
        .cd-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        /* ═══════════════════════════════════════════════════════════
           STATS CARDS
           ═══════════════════════════════════════════════════════════ */
        .cd-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .cd-stat-card {
            background: var(--cd-card-bg);
            border-radius: var(--cd-radius);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--cd-shadow);
            border: 1px solid var(--cd-border);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--cd-transition);
        }

        .cd-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--cd-shadow-md);
        }

        .cd-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--cd-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .cd-stat-icon.orders     { background: var(--cd-primary-100); color: var(--cd-primary); }
        .cd-stat-icon.occupied   { background: var(--cd-info-bg); color: var(--cd-info); }
        .cd-stat-icon.revenue    { background: #dcfce7; color: var(--cd-success); }
        .cd-stat-icon.tables     { background: #fef3c7; color: var(--cd-warning); }

        .cd-stat-body { flex: 1; min-width: 0; }

        .cd-stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cd-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.15rem;
        }

        .cd-stat-value {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: var(--cd-text);
        }

        .cd-stat-sub {
            font-size: 0.78rem;
            color: var(--cd-muted-light);
            margin-top: 0.1rem;
        }

        /* ═══════════════════════════════════════════════════════════
           SECTION HEADER
           ═══════════════════════════════════════════════════════════ */
        .cd-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .cd-section-title {
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--cd-text);
            letter-spacing: -0.01em;
        }

        .cd-section-title i {
            color: var(--cd-primary);
            font-size: 1.1rem;
        }

        .cd-section-count {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--cd-muted);
        }

        .cd-section-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cd-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border: 1px solid var(--cd-border);
            border-radius: var(--cd-radius-sm);
            background: var(--cd-white);
            color: var(--cd-text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--cd-transition);
            text-decoration: none;
            font-family: inherit;
        }

        .cd-btn-outline:hover {
            border-color: var(--cd-muted-light);
            background: var(--cd-bg);
            color: var(--cd-text);
        }

        .cd-btn-outline i { font-size: 0.85rem; }

        /* ═══════════════════════════════════════════════════════════
           TABLES GRID
           ═══════════════════════════════════════════════════════════ */
        .cd-tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }

        .cd-table-card {
            background: var(--cd-card-bg);
            border-radius: var(--cd-radius);
            box-shadow: var(--cd-shadow);
            border: 1px solid var(--cd-border);
            padding: 1.25rem;
            cursor: pointer;
            transition: var(--cd-transition);
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .cd-table-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--cd-shadow-md);
            border-color: var(--cd-muted-light);
        }

        .cd-table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: var(--cd-radius) var(--cd-radius) 0 0;
        }

        .cd-table-card.available::before { background: var(--cd-success); }
        .cd-table-card.occupied::before  { background: var(--cd-danger); }

        .cd-table-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.6rem;
        }

        .cd-table-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--cd-text);
            letter-spacing: -0.01em;
        }

        .cd-table-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .cd-table-badge.available {
            background: var(--cd-success-bg);
            color: var(--cd-success);
        }

        .cd-table-badge.occupied {
            background: var(--cd-danger-bg);
            color: var(--cd-danger);
        }

        .cd-table-capacity {
            font-size: 0.8rem;
            color: var(--cd-muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 0.75rem;
        }

        .cd-table-order-count {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--cd-primary);
            color: var(--cd-white);
            font-size: 0.68rem;
            font-weight: 700;
            min-width: 24px;
            height: 24px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.45rem;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
        }

        .cd-table-details {
            border-top: 1px solid var(--cd-border-light);
            padding-top: 0.7rem;
            margin-top: 0.25rem;
        }

        .cd-table-waiter {
            font-size: 0.78rem;
            color: var(--cd-muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 0.25rem;
        }

        .cd-table-order-ref {
            font-size: 0.78rem;
            color: var(--cd-muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .cd-table-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.6rem;
            padding-top: 0.6rem;
            border-top: 1px solid var(--cd-border-light);
        }

        .cd-table-total-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--cd-muted);
        }

        .cd-table-total-amount {
            font-size: 1rem;
            font-weight: 700;
            color: var(--cd-primary);
            letter-spacing: -0.02em;
        }

        /* ═══════════════════════════════════════════════════════════
           PENDING ORDERS TABLE
           ═══════════════════════════════════════════════════════════ */
        .cd-pending-section {
            margin-top: 2rem;
        }

        .cd-pending-wrap {
            background: var(--cd-card-bg);
            border-radius: var(--cd-radius);
            box-shadow: var(--cd-shadow);
            border: 1px solid var(--cd-border);
            overflow: hidden;
        }

        .cd-pending-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .cd-pending-table th {
            text-align: left;
            padding: 0.75rem 1.25rem;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--cd-muted);
            background: var(--cd-bg);
            border-bottom: 1px solid var(--cd-border);
        }

        .cd-pending-table td {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--cd-border-light);
            color: var(--cd-text-secondary);
        }

        .cd-pending-table tbody tr {
            transition: var(--cd-transition);
        }

        .cd-pending-table tbody tr:hover {
            background: #f8fafc;
        }

        .cd-pending-table tbody tr:last-child td {
            border-bottom: none;
        }

        .cd-order-no {
            font-weight: 600;
            color: var(--cd-text);
        }

        .cd-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .cd-status-badge.pending   { background: var(--cd-warning-bg); color: var(--cd-warning); }
        .cd-status-badge.confirmed { background: var(--cd-info-bg); color: var(--cd-info); }
        .cd-status-badge.served    { background: #dcfce7; color: var(--cd-success); }
        .cd-status-badge.completed { background: var(--cd-primary-100); color: var(--cd-primary); }

        .cd-pending-empty {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--cd-muted-light);
        }

        .cd-pending-empty i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.75rem;
            color: var(--cd-success);
        }

        .cd-pending-empty p {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--cd-text-secondary);
        }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
           ═══════════════════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .cd-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            .cd-tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .cd-container { padding: 1rem; }

            .cd-topbar {
                padding: 0 1rem;
                height: 58px;
            }

            .cd-topbar-brand {
                font-size: 0.95rem;
            }

            .cd-topbar-brand-badge {
                display: none;
            }

            .cd-topbar-clock span.label {
                display: none;
            }

            .cd-topbar-user .user-name {
                display: none;
            }

            .cd-topbar-user {
                padding: 0.3rem;
            }

            .cd-stats {
                gap: 0.75rem;
            }

            .cd-stat-card {
                padding: 1rem 1.15rem;
            }

            .cd-stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .cd-stat-value {
                font-size: 1.35rem;
            }

            .cd-tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 0.75rem;
            }

            .cd-table-card {
                padding: 1rem;
            }

            .cd-section-header {
                margin-bottom: 0.75rem;
            }

            .cd-pending-table th,
            .cd-pending-table td {
                padding: 0.6rem 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .cd-container { padding: 0.75rem; }

            .cd-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .cd-stat-card {
                padding: 0.85rem 1rem;
                gap: 0.65rem;
            }

            .cd-stat-icon {
                width: 34px;
                height: 34px;
                font-size: 0.95rem;
            }

            .cd-stat-value {
                font-size: 1.15rem;
            }

            .cd-stat-label {
                font-size: 0.68rem;
            }

            .cd-tables-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .cd-table-card {
                padding: 0.85rem;
            }

            .cd-table-name {
                font-size: 0.9rem;
            }

            .cd-table-badge {
                font-size: 0.65rem;
                padding: 0.15rem 0.45rem;
            }

            .cd-table-total-amount {
                font-size: 0.85rem;
            }

            .cd-pending-section {
                margin-top: 1.25rem;
            }

            .cd-pending-table th,
            .cd-pending-table td {
                padding: 0.5rem 0.75rem;
                font-size: 0.78rem;
            }

            .cd-pending-table th:nth-child(4),
            .cd-pending-table td:nth-child(4),
            .cd-pending-table th:nth-child(5),
            .cd-pending-table td:nth-child(5) {
                display: none;
            }

            .cd-pending-empty {
                padding: 2rem 1rem;
            }

            .cd-pending-empty i {
                font-size: 2rem;
            }

            .cd-section-title {
                font-size: 0.95rem;
            }

            .cd-section-actions .cd-btn-outline span {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .cd-tables-grid {
                gap: 0.4rem;
            }

            .cd-table-card {
                padding: 0.7rem;
            }

            .cd-table-name {
                font-size: 0.82rem;
            }

            .cd-table-card-top {
                flex-direction: column;
                gap: 0.35rem;
            }

            .cd-stats {
                gap: 0.4rem;
            }

            .cd-stat-card {
                padding: 0.7rem 0.85rem;
            }

            .cd-stat-icon {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }

            .cd-stat-value {
                font-size: 1rem;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           ANIMATIONS
           ═══════════════════════════════════════════════════════════ */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .cd-animate-in {
            animation: slideUp 0.35s ease forwards;
        }

        .cd-animate-in:nth-child(1) { animation-delay: 0.02s; }
        .cd-animate-in:nth-child(2) { animation-delay: 0.06s; }
        .cd-animate-in:nth-child(3) { animation-delay: 0.10s; }
        .cd-animate-in:nth-child(4) { animation-delay: 0.14s; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0.5; }
        }

        .cd-refreshing .cd-stat-value {
            animation: pulse 1.2s ease-in-out infinite;
        }

        /* ═══════════════════════════════════════════════════════════
           SCROLLBAR
           ═══════════════════════════════════════════════════════════ */
        .cd-pending-wrap::-webkit-scrollbar {
            height: 6px;
        }

        .cd-pending-wrap::-webkit-scrollbar-track {
            background: var(--cd-border-light);
            border-radius: 3px;
        }

        .cd-pending-wrap::-webkit-scrollbar-thumb {
            background: var(--cd-muted-light);
            border-radius: 3px;
        }

        .cd-pending-wrap::-webkit-scrollbar-thumb:hover {
            background: var(--cd-muted);
        }
    </style>
</head>
<body>

    <!-- ═══ TOP BAR ═══ -->
    <header class="cd-topbar">
        <div class="cd-topbar-left">
            <div class="cd-topbar-logo">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <div class="cd-topbar-brand">
                    {{ $siteName ?? 'Restaurant' }}
                    <span class="cd-topbar-brand-badge">
                        <i class="bi bi-cash-coin"></i> Checkout
                    </span>
                </div>
            </div>
        </div>
        <div class="cd-topbar-right">
            <div class="cd-topbar-clock">
                <i class="bi bi-clock"></i>
                <span class="label">Clock</span>
                <span id="cdClock" style="font-weight:600;color:var(--cd-text);min-width:50px;display:inline-block;">{{ now()->format('h:i A') }}</span>
            </div>
            <div class="cd-topbar-user">
                <div class="avatar">
                    <i class="bi bi-person"></i>
                </div>
                <span class="user-name">{{ Auth::user()->name ?? 'Staff' }}</span>
            </div>
            <a class="cd-btn-icon cd-btn-icon-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
               title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </header>

    <!-- ═══ MAIN ═══ -->
    <div class="cd-container">

        {{-- Stats Cards --}}
        <div class="cd-stats">
            <div class="cd-stat-card cd-animate-in">
                <div class="cd-stat-icon tables">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <div class="cd-stat-body">
                    <div class="cd-stat-label">Total Tables</div>
                    <div class="cd-stat-value">{{ $tables->count() }}</div>
                </div>
            </div>
            <div class="cd-stat-card cd-animate-in">
                <div class="cd-stat-icon occupied">
                    <i class="bi bi-people"></i>
                </div>
                <div class="cd-stat-body">
                    <div class="cd-stat-label">Occupied</div>
                    <div class="cd-stat-value">{{ $occupiedCount }}</div>
                    @php $availCount = $tables->count() - $occupiedCount; @endphp
                    <div class="cd-stat-sub">{{ $availCount }} available</div>
                </div>
            </div>
            <div class="cd-stat-card cd-animate-in">
                <div class="cd-stat-icon orders">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="cd-stat-body">
                    <div class="cd-stat-label">Today's Orders</div>
                    <div class="cd-stat-value">{{ $todayOrders }}</div>
                </div>
            </div>
            <div class="cd-stat-card cd-animate-in">
                <div class="cd-stat-icon revenue">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="cd-stat-body">
                    <div class="cd-stat-label">Today's Revenue</div>
                    <div class="cd-stat-value">Rs {{ number_format($todayRevenue, 0) }}</div>
                </div>
            </div>
        </div>

        {{-- Tables Section --}}
        <div class="cd-section-header">
            <div class="cd-section-title">
                <i class="bi bi-grid-3x3-gap"></i>
                Tables
                <span class="cd-section-count">({{ $occupiedCount }} occupied · {{ $tables->count() }} total)</span>
            </div>
            <div class="cd-section-actions">
                <button class="cd-btn-outline" onclick="window.location.reload()" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <div class="cd-tables-grid">
            @forelse($tables as $table)
                @php
                    $isOccupied = ($table->status->value ?? $table->status) === 'occupied';
                    $activeOrders = $table->orders ?? collect();
                    $orderCount = $activeOrders->count();
                    $totalDue = $activeOrders->sum(fn($o) => $o->items->sum('total'));
                @endphp
                <a href="{{ $isOccupied ? route('admin.orders.table.checkout', $table) : '#' }}"
                   class="cd-table-card {{ $isOccupied ? 'occupied' : 'available' }} cd-animate-in"
                   @if(!$isOccupied) onclick="return false;" style="cursor:default;" @endif>

                    <div class="cd-table-card-top">
                        <div class="cd-table-name">{{ $table->name }}</div>
                        <span class="cd-table-badge {{ $isOccupied ? 'occupied' : 'available' }}">
                            <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i>
                            {{ $isOccupied ? 'Occupied' : 'Available' }}
                        </span>
                    </div>

                    <div class="cd-table-capacity">
                        <i class="bi bi-people"></i> Capacity: {{ $table->capacity ?? '—' }}
                    </div>

                    @if($isOccupied && $activeOrders->isNotEmpty())
                        @php $firstOrder = $activeOrders->first(); @endphp
                        <div class="cd-table-details">
                            <div class="cd-table-waiter">
                                <i class="bi bi-person-badge"></i>
                                {{ $firstOrder->waiter->name ?? '—' }}
                            </div>
                            <div class="cd-table-order-ref">
                                <i class="bi bi-receipt"></i>
                                Order #{{ $firstOrder->order_no }}
                            </div>
                        </div>
                        <div class="cd-table-total">
                            <span class="cd-table-total-label">Due Amount</span>
                            <span class="cd-table-total-amount">Rs {{ number_format($totalDue, 0) }}</span>
                        </div>
                    @endif

                    @if($isOccupied && $orderCount > 0)
                        <div class="cd-table-order-count">{{ $orderCount }}</div>
                    @endif
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem 2rem;color:var(--cd-muted-light);">
                    <i class="bi bi-table" style="font-size:2.5rem;display:block;margin-bottom:0.75rem;"></i>
                    <p style="font-weight:500;color:var(--cd-text-secondary);">No tables found</p>
                </div>
            @endforelse
        </div>

        {{-- Pending Orders --}}
        <div class="cd-pending-section">
            <div class="cd-section-header">
                <div class="cd-section-title">
                    <i class="bi bi-clock-history"></i>
                    Pending Orders
                    <span class="cd-section-count">({{ $pendingOrders->count() }} awaiting)</span>
                </div>
            </div>

            @if($pendingOrders->isNotEmpty())
                <div class="cd-pending-wrap">
                    <table class="cd-pending-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Table</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Waiter</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                                <tr>
                                    <td><span class="cd-order-no">#{{ $order->order_no }}</span></td>
                                    <td>{{ $order->table->name ?? '—' }}</td>
                                    <td>
                                        <span class="cd-status-badge {{ $order->status->value ?? $order->status }}">
                                            {{ ucfirst($order->status->value ?? $order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->items->sum('quantity') }} items</td>
                                    <td>{{ $order->waiter->name ?? '—' }}</td>
                                    <td>{{ $order->created_at->format('h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cd-pending-empty">
                    <i class="bi bi-check-circle"></i>
                    <p>All caught up — no pending orders right now</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const h12 = hours % 12 || 12;
            document.getElementById('cdClock').textContent = h12 + ':' + minutes + ' ' + ampm;
        }
        setInterval(updateClock, 30000);
    </script>

</body>
</html>
