<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Menu — {{ $siteName ?? 'RestaurantPro' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>
        @php
            $primaryColor = $design->primary_color ?? '#dc2626';
            $secondaryColor = $design->secondary_color ?? '#1a1a2e';
            $accentColor = $design->accent_color ?? '#f59e0b';
            $backgroundColor = $design->background_color ?? '#f8f9fa';
            $textColor = $design->text_color ?? '#1f2937';
            $fontFamily = $design->font_family ?? 'Inter';
            $cardStyle = $design->card_style ?? 'modern';
            $layout = $design->layout ?? 'grid';
            $showCategories = $design->show_categories ?? true;
            $showSearch = $design->show_search ?? true;
            $showPrices = $design->show_prices ?? true;
            $showImages = $design->show_images ?? true;
        @endphp

        :root {
            --dm-primary: {{ $primaryColor }};
            --dm-secondary: {{ $secondaryColor }};
            --dm-accent: {{ $accentColor }};
            --dm-background: {{ $backgroundColor }};
            --dm-text: {{ $textColor }};
            --dm-font: {{ $fontFamily }};
        }

        /* ─── Digital Menu Overrides ─── */
        .dm-hero {
            background: linear-gradient(135deg, var(--dm-secondary) 0%, {{ $secondaryColor }} 100%);
            padding: 3rem 0;
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
            background: radial-gradient(circle, {{ $primaryColor }}0.08) 0%, transparent 60%);
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
            font-family: var(--dm-font);
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
        }
        .dm-hero p {
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
        }
        /* Category nav */
        .dm-category-nav {
            background: var(--white);
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-100);
            position: sticky;
            top: 0;
            z-index: 100;
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
            padding: 0.55rem 1.5rem;
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
            max-width: 400px;
            margin: 1.5rem auto 0;
            position: relative;
        }
        .dm-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 50px;
            font-size: 0.9rem;
            background: var(--white);
            transition: var(--transition);
            outline: none;
        }
        .dm-search input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
        .dm-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        /* Menu items */
        .dm-section { padding: 2.5rem 0; }
        .dm-category-title {
            font-family: var(--dm-font);
            font-size: 1.8rem;
            color: var(--dm-text);
        }
        .dm-menu-name {
            font-family: var(--font-sans);
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--dm-text);
            margin: 1.5rem 0 1rem;
            padding-left: 0.75rem;
            border-left: 4px solid var(--dm-primary);
        }
        .dm-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: var(--transition);
            animation: fadeInUp 0.5s ease-out;
            height: 100%;
            border: 1px solid var(--gray-100);
        }
        .dm-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            border-color: var(--dm-primary);
        }
        .dm-card img { width: 100%; height: 180px; object-fit: cover; transition: transform 0.4s ease; }
        .dm-card:hover img { transform: scale(1.05); }
        .dm-card-body { padding: 1.15rem; }
        .dm-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem; }
        .dm-card-name { font-weight: 600; font-size: 0.95rem; color: var(--gray-800); flex: 1; margin-right: 0.5rem; }
        .dm-card-price { font-family: var(--font-serif); font-weight: 700; font-size: 1.05rem; color: var(--primary); white-space: nowrap; }
        .dm-card-desc { font-size: 0.8rem; color: var(--gray-400); line-height: 1.5; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .dm-card-footer { display: flex; align-items: center; justify-content: space-between; }
        .dm-tags { display: flex; gap: 0.3rem; flex-wrap: wrap; }
        .dm-tag { padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; }
        .dm-tag.veg { background: #dcfce7; color: #16a34a; }
        .dm-tag.popular { background: #fef3c7; color: #d97706; }
        .dm-no-results { text-align: center; padding: 4rem 0; color: var(--gray-400); }
        .dm-no-results i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }

        /* Cart Button */
        .qr-cart-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--dm-primary);
            color: var(--white);
            border: none;
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba({{ hex2rgb($primaryColor) }},0.3);
            z-index: 1000;
            transition: all 0.3s;
        }
        .qr-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba({{ hex2rgb($primaryColor) }},0.4);
        }
        .qr-waiter-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #f59e0b;
            color: var(--white);
            border: none;
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(245,158,11,0.3);
            z-index: 1000;
            transition: var(--transition);
        }
        .qr-waiter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(245,158,11,0.4);
        }
        .qr-waiter-btn.calling {
            background: #10b981;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .qr-cart-btn .badge {
            background: #ffc107;
            color: #000;
            font-size: 0.75rem;
        }

        /* Add to Cart Button */
        .add-to-cart-btn {
            background: var(--dm-primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .add-to-cart-btn:hover {
            background: var(--dm-primary);
            opacity: 0.9;
            background: var(--gray-100);
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
            .dm-card-desc { font-size: 0.75; }
            .hero-tagline { font-size: 1rem; }
            .restaurant-logo { max-height: 60px; max-width: 150pxrem; }
            .dm-category-title { font-size: 1.2rem; margin: 1.5rem 0 1rem; }
            .dm-menu-name { font-size: 1rem; margin: 1rem 0 0.75rem; }
            .dm-section { padding: 1.5rem 0; }
            .dm-card img { height: 160px;     .dm-card { animation: none; }
        }
            .qr-cart-btn {
                bottom: 15px;
                right: 15px;
                padding: 10px 20px;
                font-size: 0.9rem;
            }
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

    <!-- Hero -->
    <section class="dm-hero">
        <div class="container dm-hero-content">
            <h1>{{ $siteName ?? 'RestaurantPro' }}</h1>
            <p>Browse our menu and discover our culinary offerings</p>
            @if($tableRecord)
            <p class="mt-2"><span class="badge bg-light text-dark">Table: {{ $tableRecord->name }}</span></p>
            @endif
        </div>
    </section>

    <!-- Category Nav (sticky) -->
    @if($showSearch || $showCategories)
    <nav class="dm-category-nav">
        <div class="container">
            @if($showSearch)
            <div class="dm-search">
                <i class="bi bi-search"></i>
                <input type="text" id="dmSearch" placeholder="Search dishes..." autocomplete="off">
            </div>
            @endif
            @if($showCategories)
            <div class="scroll-x mt-3 justify-content-sm-center justify-content-start px-2 px-sm-0">
                <button class="dm-cat-btn active" data-category="all">All Items</button>
                @foreach($menuCategories as $category)
                    <button class="dm-cat-btn" data-category="{{ Str::slug($category->name) }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            @endif
        </div>
    </nav>
    @endif

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
                            <div class="row">
                                @foreach($items as $item)
                                    <div class="col-lg-3 col-md-6 col-sm-6 menu-item" data-category="{{ $catSlug }}" data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}">
                                        <div class="dm-card">
                                            @if($showImages)
                                            <img src="{{ $item->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png') }}"
                                                 alt="{{ $item->name }}" loading="lazy">
                                            @endif
                                            <div class="dm-card-body">
                                                <div class="dm-card-top">
                                                    <span class="dm-card-name">{{ $item->name }}</span>
                                                    @if($showPrices)
                                                    <span class="dm-card-price">Rs.{{ number_format($item->price, 2) }}</span>
                                                    @endif
                                                </div>
                                                @if($item->description)
                                                    <p class="dm-card-desc">{{ $item->description }}</p>
                                                @endif
                                                <div class="dm-card-footer">
                                                    <div class="dm-tags">
                                                        @if(($item->is_veg ?? false))<span class="dm-tag veg">Veg</span>@endif
                                                        @if(($item->is_popular ?? false))<span class="dm-tag popular">Popular</span>@endif
                                                        @if(($item->is_spicy ?? false))<span class="dm-tag spicy">Spicy</span>@endif
                                                        @if(($item->is_chef_special ?? false))<span class="dm-tag chef">Chef's</span>@endif
                                                    </div>
                                                    <button class="add-to-cart-btn" onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})">
                                                        <i class="bi bi-plus-lg"></i> Add
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

    <!-- Cart Button -->
    <button class="qr-cart-btn" onclick="showCartModal()" id="cartBtn" style="display: none;">
        <i class="bi bi-cart3"></i> Cart <span class="badge" id="cartCount">0</span>
    </button>

    <!-- Call Waiter Button -->
    <button class="qr-waiter-btn" onclick="callWaiter()" id="waiterBtn">
        <i class="bi bi-bell"></i> Call Waiter
    </button>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems"></div>
                    <div id="cartEmpty" class="text-center py-4">
                        <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--gray-300);"></i>
                        <p class="mt-3 text-muted">Your cart is empty</p>
                    </div>
                    <div id="cartTotal" class="mt-3 pt-3 border-top" style="display: none;">
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="cartTotalAmount">Rs 0.00</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Shopping</button>
                    <button type="button" class="btn btn-primary" id="placeOrderBtn" onclick="placeOrder()" style="display: none;">Place Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div class="modal fade" id="orderConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle"></i> Order Placed!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Order #<span id="orderNumber"></span></h4>
                        <p class="text-muted">Your order has been sent to the kitchen.</p>
                        <div id="orderDetails" class="mt-4 text-start"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="trackOrder()">Track Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Tracking Modal -->
    <div class="modal fade" id="trackOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Track Order #<span id="trackOrderNo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="trackOrderBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="refreshOrderStatus()">Refresh Status</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Cart State
    let cart = [];
    let currentOrder = null;
    let sessionToken = null;
    let tenantSlug = '{{ $tenant->slug ?? request()->segment(2) ?? '' }}';
    let tableId = {{ $tableRecord->id ?? 'null' }};
    let tableName = '{{ $tableRecord->name ?? 'Unknown' }}';

    // Debug logging
    console.log('Digital Menu initialized:', {
        tenantSlug,
        tableId,
        tableName,
        hasTenant: !!{{ isset($tenant) ? 'true' : 'false' }},
        hasTable: !!{{ isset($tableRecord) ? 'true' : 'false' }}
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Load cart from localStorage
        const savedCart = localStorage.getItem('qrCart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCartUI();
        }

        // Load saved order
        const savedOrder = localStorage.getItem('qrOrder');
        if (savedOrder) {
            currentOrder = JSON.parse(savedOrder);
            sessionToken = currentOrder.session_token;
        }

        // ─── Category filtering ──────────────────────────────
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

    // Cart Functions
    function addToCart(id, name, price) {
        console.log('addToCart called:', { id, name, price });
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        saveCart();
        updateCartUI();
        showToast('Item added to cart');
    }

    // Make function globally accessible
    window.addToCart = addToCart;

    function updateCartUI() {
        const cartBtn = document.getElementById('cartBtn');
        const cartCount = document.getElementById('cartCount');
        const cartItems = document.getElementById('cartItems');
        const cartEmpty = document.getElementById('cartEmpty');
        const cartTotal = document.getElementById('cartTotal');
        const cartTotalAmount = document.getElementById('cartTotalAmount');
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        cartCount.textContent = totalItems;
        cartBtn.style.display = totalItems > 0 ? 'block' : 'none';

        if (cart.length === 0) {
            cartItems.innerHTML = '';
            cartEmpty.style.display = 'block';
            cartTotal.style.display = 'none';
            placeOrderBtn.style.display = 'none';
        } else {
            cartEmpty.style.display = 'none';
            cartTotal.style.display = 'block';
            placeOrderBtn.style.display = 'block';
            cartTotalAmount.textContent = 'Rs ' + totalAmount.toFixed(2);

            cartItems.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">Rs ${item.price.toFixed(2)} x ${item.quantity}</small>
                    </div>
                    <div class="cart-item-qty">
                        <button onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)">+</button>
                        <button onclick="removeFromCart(${item.id})" class="btn btn-sm btn-outline-danger ms-2">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }
    }

    function updateQuantity(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCart(id);
            } else {
                saveCart();
                updateCartUI();
            }
        }
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        updateCartUI();
    }

    // Make functions globally accessible
    window.updateQuantity = updateQuantity;
    window.removeFromCart = removeFromCart;

    function saveCart() {
        localStorage.setItem('qrCart', JSON.stringify(cart));
    }

    function showCartModal() {
        console.log('showCartModal called');
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    window.showCartModal = showCartModal;

    // Waiter Call Functions
    async function callWaiter() {
        if (!tableId) {
            alert('Table information is required to call waiter.');
            return;
        }

        if (!tenantSlug) {
            alert('Restaurant information is missing.');
            return;
        }

        if (!confirm('Do you need assistance from our waiter?')) {
            return;
        }

        try {
            const response = await fetch('/api/qr/call-waiter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tenant_slug: tenantSlug,
                    table_id: tableId
                })
            });

            const result = await response.json();

            if (result.success) {
                const waiterBtn = document.getElementById('waiterBtn');
                waiterBtn.classList.add('calling');
                waiterBtn.innerHTML = '<i class="bi bi-bell"></i> Calling...';
                showToast('Waiter has been called');
                
                // Check status after 30 seconds
                setTimeout(checkWaiterStatus, 30000);
            } else {
                alert(result.message || 'Failed to call waiter');
            }
        } catch (error) {
            console.error('Error calling waiter:', error);
            alert('Failed to call waiter. Please try again.');
        }
    }

    async function checkWaiterStatus() {
        try {
            const response = await fetch(`/api/qr/waiter-status?tenant_slug=${tenantSlug}&table_id=${tableId}`);
            const result = await response.json();
            
            if (result.success && result.call) {
                const waiterBtn = document.getElementById('waiterBtn');
                if (result.call.status === 'accepted' || result.call.status === 'completed') {
                    waiterBtn.classList.remove('calling');
                    waiterBtn.innerHTML = '<i class="bi bi-bell"></i> Call Waiter';
                    showToast('Waiter is on the way');
                }
            }
        } catch (error) {
            console.error('Error checking waiter status:', error);
        }
    }

    window.callWaiter = callWaiter;

    // Order Functions
    async function placeOrder() {
        if (!tableId) {
            alert('Table information is required to place an order. Please scan a table QR code.');
            return;
        }

        if (!tenantSlug) {
            alert('Restaurant information is missing. Please try again.');
            return;
        }

        const customerName = prompt('Enter your name (optional):');
        const customerPhone = prompt('Enter your phone number (optional):');
        const notes = prompt('Any special instructions? (optional):');

        const orderData = {
            tenant_slug: tenantSlug,
            table_id: tableId,
            items: cart.map(item => ({
                menu_item_id: item.id,
                quantity: item.quantity,
                unit_price: item.price,
                size: 1
            })),
            customer_name: customerName || null,
            customer_phone: customerPhone || null,
            notes: notes || null
        };

        try {
            console.log('Placing order with data:', orderData);
            
            const response = await fetch('/api/qr/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(orderData)
            });

            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response not OK:', errorText);
                alert(`Server error: ${response.status} - ${errorText}`);
                return;
            }

            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                sessionToken = result.session_token;
                currentOrder = {
                    order_no: result.order.order_no,
                    session_token: sessionToken
                };
                localStorage.setItem('qrOrder', JSON.stringify(currentOrder));

                // Clear cart
                cart = [];
                saveCart();
                updateCartUI();

                // Show confirmation
                document.getElementById('orderNumber').textContent = result.order.order_no;
                document.getElementById('orderDetails').innerHTML = `
                    <p><strong>Table:</strong> {{ $tableRecord->name ?? 'N/A' }}</p>
                    <p><strong>Items:</strong> ${result.order.items.length}</p>
                    <p><strong>Total:</strong> Rs ${result.order.total_amount.toFixed(2)}</p>
                `;

                bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                new bootstrap.Modal(document.getElementById('orderConfirmModal')).show();
            } else {
                console.error('Order failed:', result);
                alert(result.message || 'Failed to place order');
            }    <p class="mt-3 text-muted small">Track your order status in real-time</p>
                
        } catch (error) {
            console.error('Error placing order:', error);
            alert('Failed to place order. Please try again.');ow();
                
                // Add track order button to confirmation modal
                setTimeout(() => {
                    const modalBody = document.querySelector('#orderConfirmModal .modal-body');
                    const trackBtn = document.createElement('button');
                    trackBtn.className = 'btn btn-outline-primary w-100 mt-3';
                    trackBtn.innerHTML = '<i class="bi bi-geo-alt"></i> Track Order';
                    trackBtn.onclick = () => {
                        wind.location.href = `/qr/track-page/${sessionToken}/${result.order.order_no}`;
                    };
                    modalBody.appendChildtrackBtn);
                }, 100
        }
    }

    function trackOrder() {
        if (!currentOrder || !sessionToken) {
            alert('No active order to track.');
            return;
        }

        document.getElementById('trackOrderNo').textContent = currentOrder.order_no;
        new bootstrap.Modal(document.getElementById('trackOrderModal')).show();
        refreshOrderStatus();
    }

    async function refreshOrderStatus() {
        if (!currentOrder || !sessionToken) return;

        const trackOrderBody = document.getElementById('trackOrderBody');
        trackOrderBody.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        try {
            const response = await fetch(`/qr/track/${sessionToken}/${currentOrder.order_no}`);
            const result = await response.json();

            if (result.success) {
                const order = result.order;
                const statusColors = {
                    pending: 'warning',
                    confirmed: 'info',
                    preparing: 'primary',
                    ready: 'success',
                    served: 'success',
                    completed: 'success',
                    cancelled: 'danger'
                };

                trackOrderBody.innerHTML = `
                    <div class="mb-3">
                        <span class="badge bg-${statusColors[order.status] || 'secondary'} fs-6">
                            ${order.status.toUpperCase()}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Table:</strong> ${order.table?.name || 'N/A'}</p>
                            <p><strong>Order Type:</strong> QR Order</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total:</strong> Rs ${order.total_amount.toFixed(2)}</p>
                            <p><strong>Payment:</strong> ${order.payment_status.toUpperCase()}</p>
                        </div>
                    </div>
                    <h6 class="mt-4 mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${order.items.map(item => `
                                    <tr>
                                        <td>${item.menu_item?.name || 'N/A'}</td>
                                        <td>${item.quantity}</td>
                                        <td>Rs ${item.total.toFixed(2)}</td>
                                        <td><span class="badge bg-${statusColors[item.status] || 'secondary'}">${item.status}</span></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                trackOrderBody.innerHTML = `
                    <div class="alert alert-danger">
                        Failed to load order status.
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error tracking order:', error);
            trackOrderBody.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load order status. Please try again.
                </div>
            `;
        }
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '1100';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
    </script>

</body>
</html>
        `;
   