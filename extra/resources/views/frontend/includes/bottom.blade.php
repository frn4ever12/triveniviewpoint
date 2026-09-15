<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('assets/js/toaster.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ─── Service Worker Registration ───────────────────────────
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        });
    }

    // ─── AOS Init ──────────────────────────────────────────
    AOS.init({
        duration: 800,
        once: true,
        offset: 80,
        easing: 'ease-out-cubic'
    });

    // ─── Preloader ─────────────────────────────────────────
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => { preloader.style.display = 'none'; }, 600);
            }, 1200);
        });
        // Fallback in case load already fired
        if (document.readyState === 'complete') {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => { preloader.style.display = 'none'; }, 600);
            }, 1200);
        }
    }

    // ─── Navbar Scroll Effect ──────────────────────────────
    const navbar = document.getElementById('mainNav');
    let ticking = false;

    function handleScroll() {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 60) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                ticking = false;
            });
            ticking = true;
        }
    }

    if (navbar) {
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll(); // initial check
    }

    // ─── Go to Top Button ──────────────────────────────────
    const goTopBtn = document.getElementById('goToTop');

    function toggleGoTop() {
        if (window.scrollY > 400) {
            goTopBtn.classList.add('visible');
        } else {
            goTopBtn.classList.remove('visible');
        }
    }

    if (goTopBtn) {
        window.addEventListener('scroll', toggleGoTop, { passive: true });
        goTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ─── Hero Carousel ─────────────────────────────────────
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    let currentSlide = 0;
    let autoInterval = null;

    if (slides.length > 1) {
        function goToSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            goToSlide((currentSlide + 1) % slides.length);
        }

        function startAuto() {
            if (autoInterval) clearInterval(autoInterval);
            autoInterval = setInterval(nextSlide, 5000);
        }

        // Dot click
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const idx = parseInt(dot.dataset.slide);
                goToSlide(idx);
                startAuto(); // reset timer
            });
        });

        startAuto();
    }

    // ─── Menu Category Filtering ──────────────────────────
    const catBtns = document.querySelectorAll('.menu-cat-btn');
    const catGroups = document.querySelectorAll('.menu-category-group');
    const menuItems = document.querySelectorAll('.menu-item');

    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const cat = btn.dataset.category;

            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            catGroups.forEach(group => {
                group.style.display = (cat === 'all' || group.dataset.category === cat) ? '' : 'none';
            });
        });
    });

    // ─── Smooth Scroll for Anchors ──────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offset = 100;
                const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

});
</script>
