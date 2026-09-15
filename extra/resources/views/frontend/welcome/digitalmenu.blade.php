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
        /* ─── Digital Menu Overrides ─── */
        .dm-hero {
            background: linear-gradient(135deg, var(--gray-900) 0%, #1a1a2e 100%);
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
            font-family: var(--font-serif);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 2rem 0 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--primary);
        }
        .dm-menu-name {
            font-family: var(--font-sans);
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 1.5rem 0 1rem;
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
        }
        .dm-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .dm-card img { width: 100%; height: 180px; object-fit: cover; transition: transform 0.4s ease; }
        .dm-card:hover img { transform: scale(1.05); }
        .dm-card-body { padding: 1.15rem; }
        .dm-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem; }
        .dm-card-name { font-weight: 600; font-size: 0.95rem; color: var(--gray-800); flex: 1; margin-right: 0.5rem; }
        .dm-card-price { font-family: var(--font-serif); font-weight: 700; font-size: 1.05rem; color: var(--primary); white-space: nowrap; }
        .dm-card-desc { font-size: 0.8rem; color: var(--gray-400); line-height: 1.5; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .dm-card-footer { display: flex; align-items: center; justify-content: flex-start; }
        .dm-tags { display: flex; gap: 0.3rem; flex-wrap: wrap; }
        .dm-tag { padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; }
        .dm-tag.veg { background: #dcfce7; color: #16a34a; }
        .dm-tag.popular { background: #fef3c7; color: #d97706; }
        .dm-no-results { text-align: center; padding: 4rem 0; color: var(--gray-400); }
        .dm-no-results i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }

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

    <!-- Hero -->
    <section class="dm-hero">
        <div class="container dm-hero-content">
            <h1>{{ $siteName ?? 'RestaurantPro' }}</h1>
            <p>Browse our menu and discover our culinary offerings</p>
        </div>
    </section>

    <!-- Category Nav (sticky) -->
    <nav class="dm-category-nav">
        <div class="container">
            <div class="dm-search">
                <i class="bi bi-search"></i>
                <input type="text" id="dmSearch" placeholder="Search dishes..." autocomplete="off">
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
                                    <div class="col-lg-3 col-md-6 col-sm-6 menu-item" data-category="{{ $catSlug }}">
                                        <div class="dm-card">
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
                                                        @if(($item->is_veg ?? false))<span class="dm-tag veg">Veg</span>@endif
                                                        @if(($item->is_popular ?? false))<span class="dm-tag popular">Popular</span>@endif
                                                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
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
    </script>

</body>
</html>
