<section id="home" class="hero">
    @if(isset($tenant) && $tenant->logo)
        <div class="hero-slide active"
             style="background-image: url('{{ $tenant->getFirstMediaUrl('logo') ?: asset('cover.jpg') }}')">
        </div>
    @elseif($banners->count() > 0)
        @foreach($banners as $key => $banner)
            <div class="hero-slide {{ $key === 0 ? 'active' : '' }}"
                 style="background-image: url('{{ $banner?->getFirstMediaUrl('image') ?: asset('cover.jpg') }}')">
            </div>
        @endforeach
    @else
        <div class="hero-slide active"
             style="background-image: url('{{ asset("cover.jpg") }}')">
        </div>
    @endif

    <div class="hero-overlay">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1200">
            <div class="hero-badge">
                <i class="bi bi-star-fill"></i>
                @if(isset($tenant))
                    {{ $tenant->name }}
                @else
                    Premium Dining Experience
                @endif
            </div>
            <h1>
                @if(isset($tenant))
                    Welcome to <span>{{ $tenant->name }}</span><br>
                    {{ $tenant->company_name ?? 'Restaurant' }}
                @else
                    Taste the <span>Extraordinary</span><br>
                    Every Single Bite
                @endif
            </h1>
            <p>
                @if(isset($tenant))
                    Experience authentic flavors at {{ $tenant->name }}. We serve the best dishes crafted with passion and the finest ingredients.
                @else
                    Experience authentic flavors crafted by world-class chefs using the finest locally-sourced ingredients.
                @endif
            </p>
            <div class="hero-actions">
                <a href="#menu" class="hero-btn-primary">
                    Explore Our Menu <i class="bi bi-arrow-right"></i>
                </a>
                @if(isset($tenant))
                    <a href="{{ route('digitalmenu', $tenant->slug) }}" class="hero-btn-secondary">
                        Digital Menu <i class="bi bi-qr-code"></i>
                    </a>
                @else
                    <a href="#contact" class="hero-btn-secondary">
                        Reserve a Table <i class="bi bi-calendar-check"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(!isset($tenant) && $banners->count() > 1)
        <div class="hero-indicators" data-aos="fade-up" data-aos-duration="800">
            @foreach($banners as $key => $banner)
                <button class="hero-dot {{ $key === 0 ? 'active' : '' }}"
                        data-slide="{{ $key }}"
                        aria-label="Slide {{ $key + 1 }}"></button>
            @endforeach
        </div>
    @endif
</section>
