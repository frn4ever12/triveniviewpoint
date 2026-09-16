<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Menu — {{ $siteName ?? 'RestaurantPro' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>
        /* ─── Modern Digital Menu ─── */
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --secondary: #1e293b;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s ease;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            padding-bottom: 80px;
        }

        /* Modern Header */
        .dm-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--white);
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 1rem;
        }
        .dm-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .dm-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        .dm-brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            object-fit: cover;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.2rem;
        }
        .dm-brand-info h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.2;
        }
        .dm-brand-info p {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin: 0;
        }
        .dm-header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .dm-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        .dm-action-btn:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
        }
        .dm-action-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary);
            color: var(--white);
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }
        .dm-cart-btn {
            background: var(--primary);
            color: var(--white);
        }
        .dm-cart-btn:hover {
            background: var(--primary-dark);
        }

        /* Hero Section */
        .dm-hero {
            background: linear-gradient(135deg, var(--gray-900) 0%, #1a1a2e 100%);
            padding: 2rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .dm-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 60%);
            animation: dmPulse 8s ease-in-out infinite;
        }
        @keyframes dmPulse {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(5%, 5%); }
        }
        .dm-hero-content {
            position: relative;
            z-index: 2;
        }
        .dm-hero h1 {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
        }
        .dm-hero p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
        }
        .dm-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--white);
        }
        /* Category nav */
        .dm-category-nav {
            background: var(--white);
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-100);
            position: sticky;
            top: 65px;
            z-index: 999;
        }
        .dm-category-nav .scroll-x {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .dm-category-nav .scroll-x::-webkit-scrollbar { display: none; }
        .dm-cat-btn {
            flex-shrink: 0;
            padding: 0.6rem 1.25rem;
            background: var(--gray-50);
            color: var(--gray-600);
            border: 1.5px solid var(--gray-200);
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }
        .dm-cat-btn:hover { border-color: var(--primary); color: var(--primary); }
        .dm-cat-btn.active { background: var(--primary); color: var(--white); border-color: var(--primary); box-shadow: 0 4px 15px rgba(220,38,38,0.25); }

        /* Search */
        .dm-search {
            max-width: 100%;
            margin: 1rem 0;
            position: relative;
        }
        .dm-search input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 3rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 50px;
            font-size: 0.95rem;
            background: var(--white);
            transition: var(--transition);
            outline: none;
        }
        .dm-search input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
        .dm-search i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1.1rem;
        }

        /* Menu items */
        .dm-section { padding: 1.5rem 0; }
        .dm-category-title {
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--primary);
        }
        .dm-menu-name {
            font-family: var(--font-sans);
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 1rem 0 0.75rem;
            padding-left: 0.75rem;
            border-left: 4px solid var(--primary);
        }
        .dm-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            height: 100%;
            border: 1px solid var(--gray-100);
            animation: fadeInUp 0.5s ease forwards;
            cursor: pointer;
        }
        .dm-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .dm-card img { width: 100%; height: 160px; object-fit: cover; transition: transform 0.4s ease; }
        .dm-card:hover img { transform: scale(1.05); }
        .dm-card-body { padding: 1rem; }
        .dm-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
        .dm-card-name { font-weight: 600; font-size: 0.95rem; color: var(--gray-800); flex: 1; margin-right: 0.5rem; line-height: 1.3; }
        .dm-card-price { font-family: var(--font-serif); font-weight: 700; font-size: 1rem; color: var(--primary); white-space: nowrap; }
        .dm-card-desc { font-size: 0.8rem; color: var(--gray-400); line-height: 1.4; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .dm-card-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 0.5rem; }
        .dm-tags { display: flex; gap: 0.3rem; flex-wrap: wrap; }
        .dm-tag { padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; }
        .dm-tag.veg { background: #dcfce7; color: #16a34a; }
        .dm-tag.popular { background: #fef3c7; color: #d97706; }
        .dm-add-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }
        .dm-add-btn:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .dm-no-results { text-align: center; padding: 4rem 0; color: var(--gray-400); }
        .dm-no-results i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }

        /* Sticky Cart */
        .dm-sticky-cart {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--white);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            padding: 1rem;
            z-index: 1000;
            display: none;
        }
        .dm-sticky-cart.show { display: block; }
        .dm-sticky-cart-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .dm-cart-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .dm-cart-info i { font-size: 1.5rem; color: var(--primary); }
        .dm-cart-info span { font-weight: 600; color: var(--gray-800); }
        .dm-cart-total {
            font-family: var(--font-serif);
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
        }
        .dm-view-cart-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
        }
        .dm-view-cart-btn:hover { background: var(--primary-dark); transform: translateY(-2px); }

        /* Modals */
        .dm-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }
        .dm-modal-overlay.show { display: flex; }
        .dm-modal-content {
            background: var(--white);
            border-radius: 20px 20px 0 0;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        .dm-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dm-modal-header h3 { margin: 0; font-size: 1.2rem; font-weight: 700; }
        .dm-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
        }
        .dm-modal-body { padding: 1.5rem; }
        .dm-modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-100);
            display: flex;
            gap: 1rem;
        }
        .dm-quantity-control {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--gray-100);
            padding: 0.5rem;
            border-radius: 10px;
        }
        .dm-quantity-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: var(--white);
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dm-quantity-value {
            font-weight: 700;
            font-size: 1.1rem;
            min-width: 30px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .dm-hero { padding: 2rem 0; }
            .dm-hero h1 { font-size: 2.2rem; }
            .dm-category-title { font-size: 1.4rem; }
            .dm-search { max-width: 100%; }
            .dm-category-nav { padding: 0.75rem 0; }
        }
        @media (max-width: 576px) {
            .dm-hero { padding: 1.5rem 0; }
            .dm-hero h1 { font-size: 1.8rem; }
            .dm-hero p { font-size: 0.9rem; }
            .dm-category-nav { padding: 0.5rem 0; }
            .dm-category-nav .scroll-x { gap: 0.35rem; margin-top: 0.75rem !important; }
            .dm-cat-btn { padding: 0.4rem 1rem; font-size: 0.78rem; }
            .dm-search { margin-top: 0.75rem; }
            .dm-search input { padding: 0.55rem 1rem 0.55rem 2.5rem; font-size: 0.85rem; }
            .dm-card img { height: 130px; }
            .dm-card-body { padding: 0.85rem; }
            .dm-card-name { font-size: 0.85rem; }
            .dm-card-price { font-size: 0.95rem; }
            .dm-card-desc { font-size: 0.75rem; }
            .dm-category-title { font-size: 1.2rem; margin: 1.5rem 0 1rem; }
            .dm-menu-name { font-size: 1rem; margin: 1rem 0 0.75rem; }
            .dm-section { padding: 1.5rem 0; }
            .dm-card { animation: none; }
        }
        @media (max-width: 400px) {
            .dm-category-nav .scroll-x { gap: 0.25rem; margin-top: 0.5rem !important; }
            .dm-cat-btn { padding: 0.3rem 0.75rem; font-size: 0.72rem; }
            .dm-card img { height: 100px; }
            .dm-card-body { padding: 0.6rem; }
            .dm-card-name { font-size: 0.8rem; }
            .dm-card-price { font-size: 0.85rem; }
            .dm-menu-name { font-size: 0.9rem; }
        }


        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Modern Header -->
    <header class="dm-header">
        <div class="container dm-header-content">
            <div class="dm-brand">
                @if(isset($tenant) && $tenant->logo)
                    <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="dm-brand-logo">
                @else
                    <div class="dm-brand-logo">
                        {{ substr($siteName ?? 'R', 0, 1) }}
                    </div>
                @endif
                <div class="dm-brand-info">
                    <h1>{{ $siteName ?? 'RestaurantPro' }}</h1>
                    <p>@if(isset($tableRecord)) Table {{ $tableRecord->name }} @endif</p>
                </div>
            </div>
            <div class="dm-header-actions">
                <button class="dm-action-btn" id="notificationBtn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="badge" id="notificationBadge">0</span>
                </button>
                <button class="dm-action-btn" id="callWaiterBtn" title="Call Waiter">
                    <i class="bi bi-bell-fill"></i>
                </button>
                <button class="dm-action-btn dm-cart-btn" id="cartBtn" title="Cart">
                    <i class="bi bi-cart"></i>
                    <span class="badge" id="cartBadge">0</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="dm-hero">
        <div class="container dm-hero-content">
            <h1>{{ $siteName ?? 'RestaurantPro' }}</h1>
            <p>Delicious food, freshly made with love</p>
            @if(isset($tableRecord))
                <div class="dm-hero-badge">
                    <i class="bi bi-geo-alt"></i>
                    Table {{ $tableRecord->name }}
                </div>
            @endif
        </div>
    </section>

    <!-- Category Nav (sticky) -->
    <nav class="dm-category-nav">
        <div class="container">
            <div class="dm-search">
                <i class="bi bi-search"></i>
                <input type="text" id="dmSearch" placeholder="Search menu items..." autocomplete="off">
            </div>
            <div class="scroll-x mt-3 justify-content-sm-center justify-content-start px-2 px-sm-0">
                <button class="dm-cat-btn active" data-category="all">All Items</button>
                @foreach($menuCategories as $category)
                    <button class="dm-cat-btn" data-category="{{ Str::slug($category->name) }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- Menu -->
    <section class="dm-section">
        <div class="container">
            <div id="dmContainer">
                @php
                    $groupedByCategory = $menuItems->groupBy(function($item) {
                        return $item->category ? $item->category->name : 'Uncategorized';
                    });
                @endphp

                @foreach($groupedByCategory as $categoryName => $categoryItems)
                    @php
                        $catSlug = Str::slug($categoryName);
                        $groupedByMenu = $categoryItems->groupBy(function($item) {
                            return $item->menu_name ?? 'Other';
                        });
                    @endphp

                    <div class="dm-category-group" data-category="{{ $catSlug }}">
                        <h2 class="dm-category-title">{{ $categoryName }}</h2>

                        @foreach($groupedByMenu as $menuName => $items)
                            <h3 class="dm-menu-name">{{ $menuName }}</h3>
                            <div class="row g-3 mb-4">
                                @foreach($items as $item)
                                    <div class="col-lg-3 col-md-6 col-sm-6 menu-item" data-category="{{ $catSlug }}" data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}" data-item-image="{{ $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png') }}" data-item-desc="{{ $item->description ?? '' }}">
                                        <div class="dm-card" onclick="openItemModal({{ $item->id }}, '{{ $item->name }}', {{ $item->price }}, '{{ $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png') }}', '{{ $item->description ?? '' }}')">
                                            <img src="{{ $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png') }}"
                                                 alt="{{ $item->name }}" loading="lazy">
                                            <div class="dm-card-body">
                                                <div class="dm-card-top">
                                                    <span class="dm-card-name">{{ $item->name }}</span>
                                                    <span class="dm-card-price">Rs.{{ number_format($item->price, 2) }}</span>
                                                </div>
                                                @if($item->description)
                                                    <p class="dm-card-desc">{{ $item->description }}</p>
                                                @endif
                                                <div class="dm-card-footer">
                                                    <div class="dm-tags">
                                                        @if(($item->is_vegetarian ?? false))<span class="dm-tag veg">Veg</span>@endif
                                                        @if(($item->is_featured ?? false))<span class="dm-tag popular">Popular</span>@endif
                                                    </div>
                                                    <button class="dm-add-btn" onclick="event.stopPropagation(); addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})">
                                                        + Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach

                @if($menuItems->isEmpty())
                    <div class="dm-no-results">
                        <i class="bi bi-emoji-frown"></i>
                        <h4>No items available</h4>
                        <p class="text-muted">Please check back later.</p>
                    </div>
                @endif
            </div>

            <div id="dmNoResults" class="dm-no-results" style="display:none;">
                <i class="bi bi-search"></i>
                <h4>No dishes found</h4>
                <p class="text-muted">Try adjusting your search or category filter.</p>
            </div>
        </div>
    </section>

    <!-- Sticky Cart -->
    <div class="dm-sticky-cart" id="stickyCart">
        <div class="container dm-sticky-cart-content">
            <div class="dm-cart-info">
                <i class="bi bi-cart"></i>
                <span id="cartItemCount">0 Items</span>
            </div>
            <div class="dm-cart-total" id="cartTotal">Rs. 0</div>
            <button class="dm-view-cart-btn" onclick="viewCart()">View Cart →</button>
        </div>
    </div>

    <!-- Item Detail Modal -->
    <div class="dm-modal-overlay" id="itemModal">
        <div class="dm-modal-content">
            <div class="dm-modal-header">
                <h3 id="modalItemName">Item Name</h3>
                <button class="dm-modal-close" onclick="closeItemModal()">&times;</button>
            </div>
            <div class="dm-modal-body">
                <img id="modalItemImage" src="" alt="" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem;">
                <p id="modalItemDesc" style="color: var(--gray-600); margin-bottom: 1rem;"></p>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-family: var(--font-serif); font-weight: 700; font-size: 1.5rem; color: var(--primary);" id="modalItemPrice">Rs. 0</span>
                </div>
            </div>
            <div class="dm-modal-footer">
                <div class="dm-quantity-control">
                    <button class="dm-quantity-btn" onclick="decreaseModalQuantity()">-</button>
                    <span class="dm-quantity-value" id="modalQuantity">1</span>
                    <button class="dm-quantity-btn" onclick="increaseModalQuantity()">+</button>
                </div>
                <button class="dm-view-cart-btn" style="flex: 1;" onclick="addModalItemToCart()">Add to Cart - <span id="modalTotalPrice">Rs. 0</span></button>
            </div>
        </div>
    </div>

    <!-- Call Waiter Modal -->
    <div class="dm-modal-overlay" id="callWaiterModal">
        <div class="dm-modal-content" style="max-width: 400px; border-radius: 20px;">
            <div class="dm-modal-header">
                <h3>🔔 Call Waiter</h3>
                <button class="dm-modal-close" onclick="closeCallWaiterModal()">&times;</button>
            </div>
            <div class="dm-modal-body" style="text-align: center;">
                <p style="color: var(--gray-600); margin-bottom: 1rem;">Need assistance?</p>
                <p style="color: var(--gray-500); margin-bottom: 1.5rem;">Our waiter will be notified that you are calling from:</p>
                <div style="background: var(--gray-100); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                    @if(isset($tableRecord))
                        <strong>Table {{ $tableRecord->name }}</strong>
                    @else
                        <strong>Your Table</strong>
                    @endif
                </div>
            </div>
            <div class="dm-modal-footer" style="justify-content: center;">
                <button class="dm-action-btn" style="flex: 1; padding: 0.75rem;" onclick="closeCallWaiterModal()">Cancel</button>
                <button class="dm-view-cart-btn" style="flex: 1;" onclick="confirmCallWaiter()">Call Waiter</button>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="testNotification()" style="background: #f59e0b; border: none; padding: 0.5rem 1rem; border-radius: 8px; color: white; font-size: 0.8rem;">
                    🔔 Test Notification
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="dm-modal-overlay" id="notificationModal">
        <div class="dm-modal-content" style="max-width: 400px; border-radius: 20px;">
            <div class="dm-modal-header">
                <h3>🔔 Notifications</h3>
                <button class="dm-modal-close" onclick="closeNotificationModal()">&times;</button>
            </div>
            <div class="dm-modal-body" id="notificationList">
                <p style="text-align: center; color: var(--gray-500);">No notifications</p>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="dm-modal-overlay" id="cartModal">
        <div class="dm-modal-content">
            <div class="dm-modal-header">
                <h3>🛒 Your Cart</h3>
                <button class="dm-modal-close" onclick="closeCartModal()">&times;</button>
            </div>
            <div class="dm-modal-body">
                <div id="cartItemsList"></div>
                <div id="cartEmptyMessage" style="text-align: center; padding: 2rem; color: var(--gray-500); display: none;">
                    <i class="bi bi-cart" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <p>Your cart is empty</p>
                </div>
            </div>
            <div class="dm-modal-footer" id="cartFooter">
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--gray-600);">Subtotal:</span>
                        <span style="font-weight: 600;" id="cartSubtotal">Rs. 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700;">
                        <span>Total:</span>
                        <span style="color: var(--primary);" id="cartModalTotal">Rs. 0</span>
                    </div>
                </div>
            </div>
            <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100);">
                <button class="dm-view-cart-btn" style="width: 100%;" onclick="proceedToCheckout()">Proceed to Checkout</button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="dm-modal-overlay" id="checkoutModal">
        <div class="dm-modal-content" style="max-width: 500px; border-radius: 20px;">
            <div class="dm-modal-header">
                <h3>📝 Checkout</h3>
                <button class="dm-modal-close" onclick="closeCheckoutModal()">&times;</button>
            </div>
            <div class="dm-modal-body">
                <form id="checkoutForm">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">Your Name (Optional)</label>
                        <input type="text" id="customerName" placeholder="Enter your name" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--gray-200); border-radius: 8px; font-size: 0.95rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">Phone Number (Optional)</label>
                        <input type="tel" id="customerPhone" placeholder="Enter your phone number" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--gray-200); border-radius: 8px; font-size: 0.95rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">Special Instructions (Optional)</label>
                        <textarea id="orderNotes" placeholder="Any special requests?" rows="3" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--gray-200); border-radius: 8px; font-size: 0.95rem; resize: vertical;"></textarea>
                    </div>
                    <div style="background: var(--gray-100); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--gray-600);">Items:</span>
                            <span style="font-weight: 600;" id="checkoutItemCount">0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700;">
                            <span>Total:</span>
                            <span style="color: var(--primary);" id="checkoutTotal">Rs. 0</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="dm-modal-footer" style="justify-content: center;">
                <button class="dm-action-btn" style="flex: 1; padding: 0.75rem;" onclick="closeCheckoutModal()">Cancel</button>
                <button class="dm-view-cart-btn" style="flex: 1;" onclick="placeOrder()">Place Order</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Cart Management
    let cart = [];
    let currentModalItem = null;
    let modalQuantity = 1;

    function addToCart(itemId, itemName, price) {
        const existingItem = cart.find(item => item.id === itemId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: itemId, name: itemName, price: price, quantity: 1 });
        }
        updateCartUI();
        showToast('Item added to cart!');
    }

    function updateCartUI() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        document.getElementById('cartBadge').textContent = totalItems;
        document.getElementById('cartItemCount').textContent = totalItems + ' Items';
        document.getElementById('cartTotal').textContent = 'Rs. ' + totalPrice.toFixed(2);

        const stickyCart = document.getElementById('stickyCart');
        if (totalItems > 0) {
            stickyCart.classList.add('show');
        } else {
            stickyCart.classList.remove('show');
        }
    }

    function openItemModal(itemId, itemName, price, image, desc) {
        currentModalItem = { id: itemId, name: itemName, price: price, image: image, desc: desc };
        modalQuantity = 1;

        document.getElementById('modalItemName').textContent = itemName;
        document.getElementById('modalItemPrice').textContent = 'Rs. ' + price.toFixed(2);
        document.getElementById('modalItemImage').src = image;
        document.getElementById('modalItemDesc').textContent = desc || '';
        document.getElementById('modalQuantity').textContent = modalQuantity;
        document.getElementById('modalTotalPrice').textContent = 'Rs. ' + (price * modalQuantity).toFixed(2);

        document.getElementById('itemModal').classList.add('show');
    }

    function closeItemModal() {
        document.getElementById('itemModal').classList.remove('show');
        currentModalItem = null;
    }

    function increaseModalQuantity() {
        modalQuantity++;
        document.getElementById('modalQuantity').textContent = modalQuantity;
        if (currentModalItem) {
            document.getElementById('modalTotalPrice').textContent = 'Rs. ' + (currentModalItem.price * modalQuantity).toFixed(2);
        }
    }

    function decreaseModalQuantity() {
        if (modalQuantity > 1) {
            modalQuantity--;
            document.getElementById('modalQuantity').textContent = modalQuantity;
            if (currentModalItem) {
                document.getElementById('modalTotalPrice').textContent = 'Rs. ' + (currentModalItem.price * modalQuantity).toFixed(2);
            }
        }
    }

    function addModalItemToCart() {
        if (currentModalItem) {
            for (let i = 0; i < modalQuantity; i++) {
                addToCart(currentModalItem.id, currentModalItem.name, currentModalItem.price);
            }
            closeItemModal();
        }
    }

    function viewCart() {
        updateCartModal();
        document.getElementById('cartModal').classList.add('show');
    }

    function closeCartModal() {
        document.getElementById('cartModal').classList.remove('show');
    }

    function updateCartModal() {
        const cartItemsList = document.getElementById('cartItemsList');
        const cartEmptyMessage = document.getElementById('cartEmptyMessage');
        const cartFooter = document.getElementById('cartFooter');

        if (cart.length === 0) {
            cartItemsList.innerHTML = '';
            cartEmptyMessage.style.display = 'block';
            cartFooter.style.display = 'none';
            return;
        }

        cartEmptyMessage.style.display = 'none';
        cartFooter.style.display = 'block';

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            html += `
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--gray-100);">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--gray-800);">${item.name}</div>
                        <div style="font-size: 0.85rem; color: var(--gray-500);">Rs. ${item.price.toFixed(2)} × ${item.quantity}</div>
                    </div>
                    <div style="font-weight: 700; color: var(--primary);">Rs. ${itemTotal.toFixed(2)}</div>
                    <button onclick="removeFromCart(${item.id})" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 1.2rem;">&times;</button>
                </div>
            `;
        });

        cartItemsList.innerHTML = html;
        document.getElementById('cartSubtotal').textContent = 'Rs. ' + subtotal.toFixed(2);
        document.getElementById('cartModalTotal').textContent = 'Rs. ' + subtotal.toFixed(2);
    }

    function removeFromCart(itemId) {
        const itemIndex = cart.findIndex(item => item.id === itemId);
        if (itemIndex > -1) {
            if (cart[itemIndex].quantity > 1) {
                cart[itemIndex].quantity -= 1;
            } else {
                cart.splice(itemIndex, 1);
            }
            updateCartUI();
            updateCartModal();
        }
    }

    function proceedToCheckout() {
        closeCartModal();
        updateCheckoutModal();
        document.getElementById('checkoutModal').classList.add('show');
    }

    function closeCheckoutModal() {
        document.getElementById('checkoutModal').classList.remove('show');
    }

    function updateCheckoutModal() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        document.getElementById('checkoutItemCount').textContent = totalItems;
        document.getElementById('checkoutTotal').textContent = 'Rs. ' + totalPrice.toFixed(2);
    }

    async function placeOrder() {
        const customerName = document.getElementById('customerName').value;
        const customerPhone = document.getElementById('customerPhone').value;
        const orderNotes = document.getElementById('orderNotes').value;

        if (cart.length === 0) {
            showToast('Your cart is empty');
            return;
        }

        // Convert cart to session format
        const sessionCart = {};
        cart.forEach(item => {
            sessionCart[item.id] = item.quantity;
        });

        // Sync cart to session
        try {
            const syncResponse = await fetch('/api/cart/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart: sessionCart })
            });

            if (!syncResponse.ok) {
                console.error('Cart sync failed');
                showToast('Failed to sync cart');
                return;
            }
        } catch (error) {
            console.error('Cart sync error:', error);
            showToast('Failed to sync cart');
            return;
        }

        // Submit checkout form
        const formData = new FormData();
        formData.append('order_notes', orderNotes || '');
        
        @if(isset($tableRecord))
            const tableId = {{ $tableRecord->id }};
        @else
            const tableId = null;
        @endif

        const checkoutUrl = tableId ? `/checkout/process/${tableId}` : '/checkout/process';

        try {
            const response = await fetch(checkoutUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            console.log('Checkout response status:', response.status);

            if (response.redirected) {
                cart = [];
                updateCartUI();
                closeCheckoutModal();
                showToast('Order placed successfully!');
                window.location.href = response.url;
            } else if (response.ok) {
                cart = [];
                updateCartUI();
                closeCheckoutModal();
                showToast('Order placed successfully!');
            } else {
                const errorText = await response.text();
                console.error('Checkout failed:', errorText);
                showToast('Failed to place order');
            }
        } catch (error) {
            console.error('Checkout error:', error);
            showToast('Failed to place order. Please try again.');
        }
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.style.cssText = 'position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); background: var(--gray-900); color: white; padding: 12px 24px; border-radius: 8px; z-index: 3000; animation: fadeIn 0.3s ease;';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // Call Waiter
    document.getElementById('callWaiterBtn').addEventListener('click', () => {
        document.getElementById('callWaiterModal').classList.add('show');
    });

    function closeCallWaiterModal() {
        document.getElementById('callWaiterModal').classList.remove('show');
    }

    async function confirmCallWaiter() {
        @if(isset($tenant))
            const tenantSlug = '{{ $tenant->slug }}';
        @else
            const tenantSlug = 'demo-restaurant';
        @endif
        
        @if(isset($tableRecord))
            const tableId = {{ $tableRecord->id }};
        @else
            const tableId = 1;
        @endif

        const callData = {
            tenant_slug: tenantSlug,
            table_id: tableId
        };

        try {
            console.log('Calling waiter with data:', callData);
            
            const response = await fetch('/api/qr/call-waiter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(callData)
            });

            console.log('Response status:', response.status);
            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                closeCallWaiterModal();
                showToast('Waiter has been notified!');
            } else {
                showToast(result.message || 'Failed to call waiter');
            }
        } catch (error) {
            console.error('Error calling waiter:', error);
            showToast('Failed to call waiter. Please try again.');
        }
    }

    // Test notification function
    function testNotification() {
        console.log('Testing notification from QR menu');
        
        fetch('/api/qr/test-notification', {
            method: 'GET',
        }).then(r => r.json()).then(data => {
            console.log('Test notification response:', data);
            if (data.success) {
                alert('✓ Test notification created! Check admin dashboard.');
            } else {
                alert('✗ Failed: ' + (data.error || data.message || 'Unknown error'));
            }
        }).catch(err => {
            console.error('Test notification error:', err);
            alert('✗ Error: ' + err.message);
        });
    }

    // Notifications
    document.getElementById('notificationBtn').addEventListener('click', () => {
        document.getElementById('notificationModal').classList.add('show');
    });

    function closeNotificationModal() {
        document.getElementById('notificationModal').classList.remove('show');
    }

    // Category filtering
    document.addEventListener('DOMContentLoaded', () => {
        const catBtns = document.querySelectorAll('.dm-cat-btn');
        const catGroups = document.querySelectorAll('.dm-category-group');
        const items = document.querySelectorAll('.menu-item');
        const container = document.getElementById('dmContainer');
        const noResults = document.getElementById('dmNoResults');
        const searchInput = document.getElementById('dmSearch');

        let activeCategory = 'all';
        let searchTerm = '';

        function filterItems() {
            catGroups.forEach(group => {
                const groupCat = group.dataset.category;
                const catMatch = activeCategory === 'all' || activeCategory === groupCat;
                group.style.display = catMatch ? '' : 'none';
            });

            let visible = 0;
            items.forEach(item => {
                const itemCat = item.dataset.category;
                const catMatch = activeCategory === 'all' || activeCategory === itemCat;
                const searchMatch = !searchTerm ||
                    item.querySelector('.dm-card-name')?.textContent.toLowerCase().includes(searchTerm) ||
                    item.querySelector('.dm-card-desc')?.textContent.toLowerCase().includes(searchTerm);

                if (catMatch && searchMatch) {
                    item.style.display = '';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });

            catGroups.forEach(group => {
                const hasVisible = Array.from(group.querySelectorAll('.menu-item')).some(
                    el => el.style.display !== 'none'
                );
                group.style.display = hasVisible ? '' : 'none';
            });

            if (visible === 0) {
                noResults.style.display = '';
                container.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                container.style.display = '';
            }
        }

        catBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                catBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeCategory = btn.dataset.category;
                filterItems();
            });
        });

        searchInput.addEventListener('input', () => {
            searchTerm = searchInput.value.trim().toLowerCase();
            filterItems();
        });
    });
    </script>

</body>
</html>
