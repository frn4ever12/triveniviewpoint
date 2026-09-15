<section id="menu" class="menu-section">
    <div class="container">
        <div class="menu-header" data-aos="fade-up" data-aos-duration="800">
            <span class="section-tag">Our Menu</span>
            <h2 class="section-title">Discover Our Culinary Offerings</h2>
            <p class="section-subtitle mx-auto">Handcrafted dishes made with passion, using the freshest ingredients sourced from local farms.</p>
        </div>

        <div class="menu-categories" data-aos="fade-up" data-aos-duration="900">
            <button class="menu-cat-btn active" data-category="all">All Items</button>
            @foreach($menuCategories as $category)
                <button class="menu-cat-btn" data-category="{{ Str::slug($category->name) }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div id="menuContainer" data-aos="fade-up" data-aos-duration="1000">
            @php
                $groupedByCategory = $menuItems->groupBy(function($item) {
                    return $item->category ? $item->category->name : 'Uncategorized';
                });
            @endphp

            @foreach($groupedByCategory as $categoryName => $categoryItems)
                @php
                    $categorySlug = Str::slug($categoryName);
                    $groupedByMenu = $categoryItems->groupBy(function($item) {
                        return $item->menu_name ?? 'Other';
                    });
                @endphp

                <div class="menu-category-group" data-category="{{ $categorySlug }}">
                    <h3 class="menu-group-title">{{ $categoryName }}</h3>

                    @foreach($groupedByMenu as $menuName => $menuItems)
                        <div class="row g-4 mb-5">
                            @foreach($menuItems as $item)
                                <div class="col-lg-3 col-md-6 col-sm-6 menu-item" data-category="{{ $categorySlug }}">
                                    <div class="menu-card">
                                        <div style="overflow: hidden;">
                                            <img src="{{ $item?->getFirstMediaUrl('image') ?: asset('assets/images/defaultfood.png') }}"
                                                 alt="{{ $item->name }}"
                                                 class="menu-card-image"
                                                 loading="lazy">
                                        </div>
                                        <div class="menu-card-body">
                                            <div class="menu-card-top">
                                                <span class="menu-card-name">{{ $item->name }}</span>
                                                <span class="menu-card-price">Rs.{{ number_format($item->price, 2) }}</span>
                                            </div>
                                            @if($item->description)
                                                <p class="menu-card-desc">{{ $item->description }}</p>
                                            @endif
                                            <div class="menu-card-footer">
                                                <div class="menu-card-tags">
                                                    @if(($item->is_veg ?? false))
                                                        <span class="menu-tag veg">Veg</span>
                                                    @endif
                                                    @if(($item->is_popular ?? false))
                                                        <span class="menu-tag popular">Popular</span>
                                                    @endif
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
                <div class="text-center py-5">
                    <i class="bi bi-emoji-frown" style="font-size: 3rem; color: var(--gray-300);"></i>
                    <h4 class="mt-3">No items available</h4>
                    <p class="text-muted">Please check back later for our delicious offerings.</p>
                </div>
            @endif
        </div>
    </div>
</section>
