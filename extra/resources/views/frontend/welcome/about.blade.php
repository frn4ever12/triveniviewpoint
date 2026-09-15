<section id="about" class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-image-wrapper" data-aos="fade-right" data-aos-duration="1000">
                <div class="about-image">
                    @if(isset($tenant) && $tenant->logo)
                        <img src="{{ $tenant->getFirstMediaUrl('logo') }}" alt="{{ $tenant->name }}">
                    @else
                        <img src="{{ $about?->getFirstMediaUrl('image') ?: asset('assets/images/defaultrestro.png') }}"
                             alt="{{ $about?->title ?? 'Our Restaurant' }}">
                    @endif
                </div>
                <div class="about-image-badge">
                    @if(isset($tenant))
                        <span class="number">{{ $tenant->created_at->diffInYears(now()) ?: '1+' }}</span>
                        <span class="label">Years of Excellence</span>
                    @else
                        <span class="number">5+</span>
                        <span class="label">Years of Excellence</span>
                    @endif
                </div>
            </div>
            <div class="about-text" data-aos="fade-left" data-aos-duration="1000">
                <span class="section-tag">Our Story</span>
                @if(isset($tenant))
                    <h2>About {{ $tenant->name }}</h2>
                    <p>
                        @if($tenant->address)
                            Located at {{ $tenant->address }}, {{ $tenant->city ?? 'Nepal' }}. 
                        @endif
                        {{ $tenant->company_name ?? $tenant->name }} is dedicated to providing exceptional dining experiences with authentic flavors and quality service.
                    </p>
                @else
                    <h2>{{ $about?->title ?? 'Crafting Culinary Excellence Since 2010' }}</h2>
                    <p>{!! $about?->description ?? 'Welcome to our restaurant, where every dish tells a story of passion, tradition, and innovation. We bring together the finest ingredients and culinary expertise to create unforgettable dining experiences.' !!}</p>
                @endif
                <div class="about-features">
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <div class="about-feature-text">
                            <h4>Fresh Ingredients</h4>
                            <p>Sourced daily from local farms</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="about-feature-text">
                            <h4>Expert Chefs</h4>
                            <p>Award-winning culinary team</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <div class="about-feature-text">
                            <h4>Great Ambiance</h4>
                            <p>Warm, inviting atmosphere</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div class="about-feature-text">
                            <h4>Made with Love</h4>
                            <p>Every dish crafted with care</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
