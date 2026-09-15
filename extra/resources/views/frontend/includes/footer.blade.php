<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>{{ $siteName ?? 'dmcrestro' }}</h3>
                <p>Experience authentic flavors crafted with passion. Every dish tells a story of tradition, quality, and culinary excellence.</p>
                @if(isset($socialUrls) && count(array_filter($socialUrls ?? [])))
                <div class="d-flex gap-2 mt-3">
                    @foreach($socialUrls as $platform => $url)
                        @if(!empty($url))
                            <a href="{{ $url }}" target="_blank" rel="noopener"
                               style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);transition:all 0.3s;text-decoration:none;">
                                <i class="bi bi-{{ $platform }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    @if(isset($tenant))
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#menu">Our Menu</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="{{ route('digitalmenu', $tenant->slug) }}">Digital Menu</a></li>
                    @else
                        <li><a href="#about">About Us</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h4>Opening Hours</h4>
                <ul style="font-size:0.9rem;color:rgba(255,255,255,0.6);">
                    <li>Monday – Friday</li>
                    <li style="color:rgba(255,255,255,0.4);margin-bottom:0.75rem;">11:00 AM – 10:00 PM</li>
                    <li>Saturday – Sunday</li>
                    <li style="color:rgba(255,255,255,0.4);">10:00 AM – 11:00 PM</li>
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <ul style="font-size:0.9rem;color:rgba(255,255,255,0.6);">
                    <li><i class="bi bi-geo-alt me-2"></i>{{ $address ?? 'Culinary District' }}</li>
                    <li class="mt-2"><i class="bi bi-telephone me-2"></i>{{ $contactPhone ?? '+1 (555) 123-4567' }}</li>
                    <li class="mt-2"><i class="bi bi-envelope me-2"></i>{{ $contactEmail ?? 'info@restaurantpro.com' }}</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $siteName ?? 'dmcrestro' }}. All rights reserved.</span>
        </div>
    </div>
</footer>

<!-- Go to Top Button -->
<button id="goToTop" class="go-to-top" aria-label="Go to top">
    <i class="bi bi-chevron-up"></i>
</button>
