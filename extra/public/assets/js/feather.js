(function (){
    function normalizeFeatherIcons() {
        if (typeof feather === 'undefined' || !feather.icons) {
            return;
        }

        document.querySelectorAll('[data-feather]').forEach(function (icon) {
            const name = icon.getAttribute('data-feather');
            if (!name || feather.icons[name]) {
                return;
            }

            icon.setAttribute('data-feather', 'circle');
        });
    }

    // Initialize feather icons with multiple attempts
    function initFeather() {
        if (typeof feather !== 'undefined') {
            normalizeFeatherIcons();

            try {
                feather.replace();
            } catch (error) {
                console.warn('Feather icon replace failed:', error);
                normalizeFeatherIcons();
                try {
                    feather.replace();
                } catch (retryError) {
                    console.warn('Feather icon replace retry failed:', retryError);
                }
            }

            // Force visibility on all icons after replacement
            setTimeout(forceIconVisibility, 50);
            setTimeout(forceIconVisibility, 200);
            setTimeout(forceIconVisibility, 500);
            return true;
        }
        return false;
    }

    // Force visibility on all icons
    function forceIconVisibility() {
        // Target all SVG elements in navbar-vertical
        document.querySelectorAll('.navbar-vertical svg').forEach(svg => {
            svg.style.display = 'inline-block';
            svg.style.visibility = 'visible';
            svg.style.opacity = '1';
            svg.style.width = '18px';
            svg.style.height = '18px';
            svg.style.minWidth = '18px';
            svg.style.minHeight = '18px';
        });
        
        // Target all i elements with data-feather in navbar-vertical
        document.querySelectorAll('.navbar-vertical i[data-feather]').forEach(i => {
            i.style.display = 'inline-block';
            i.style.visibility = 'visible';
            i.style.opacity = '1';
            i.style.width = '18px';
            i.style.height = '18px';
            i.style.minWidth = '18px';
            i.style.minHeight = '18px';
        });
        
        // Target all nav-icon elements
        document.querySelectorAll('.navbar-vertical .nav-icon').forEach(icon => {
            icon.style.display = 'inline-block';
            icon.style.visibility = 'visible';
            icon.style.opacity = '1';
            icon.style.width = '18px';
            icon.style.height = '18px';
            icon.style.minWidth = '18px';
            icon.style.minHeight = '18px';
        });
        
        // Target all icon-xs elements
        document.querySelectorAll('.navbar-vertical .icon-xs').forEach(icon => {
            icon.style.display = 'inline-block';
            icon.style.visibility = 'visible';
            icon.style.opacity = '1';
            icon.style.width = '16px';
            icon.style.height = '16px';
            icon.style.minWidth = '16px';
            icon.style.minHeight = '16px';
        });
        
        // Target all SVG elements globally
        document.querySelectorAll('svg').forEach(svg => {
            svg.style.display = 'inline-block';
            svg.style.visibility = 'visible';
            svg.style.opacity = '1';
        });
        
        // Target all i elements globally
        document.querySelectorAll('i').forEach(i => {
            i.style.display = 'inline-block';
            i.style.visibility = 'visible';
            i.style.opacity = '1';
        });
    }

    // Try immediately
    initFeather();

    // Try after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initFeather, 50);
            setTimeout(initFeather, 200);
            setTimeout(initFeather, 500);
            setTimeout(forceIconVisibility, 100);
            setTimeout(forceIconVisibility, 500);
        });
    } else {
        setTimeout(initFeather, 50);
        setTimeout(initFeather, 200);
        setTimeout(initFeather, 500);
        setTimeout(forceIconVisibility, 100);
        setTimeout(forceIconVisibility, 500);
    }

    // Try after window load
    window.addEventListener('load', function() {
        setTimeout(initFeather, 100);
        setTimeout(initFeather, 300);
        setTimeout(initFeather, 600);
        setTimeout(forceIconVisibility, 200);
        setTimeout(forceIconVisibility, 500);
        setTimeout(forceIconVisibility, 1000);
    });

    // Handle Bootstrap collapse events
    document.addEventListener('DOMContentLoaded', function() {
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(collapse) {
            collapse.addEventListener('show.bs.collapse', function() {
                setTimeout(initFeather, 100);
                setTimeout(forceIconVisibility, 150);
                setTimeout(forceIconVisibility, 300);
            });
            collapse.addEventListener('shown.bs.collapse', function() {
                setTimeout(initFeather, 50);
                setTimeout(forceIconVisibility, 100);
                setTimeout(forceIconVisibility, 200);
                setTimeout(forceIconVisibility, 400);
            });
        });
    });

    // Periodically check and fix hidden icons - more frequent
    setInterval(function() {
        forceIconVisibility();
    }, 1000);

    // Observe DOM changes for dynamic content
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            initFeather();
            setTimeout(forceIconVisibility, 100);
            setTimeout(forceIconVisibility, 300);
        });
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // Additional check for sidebar specifically
    function checkSidebarIcons() {
        const sidebar = document.querySelector('.navbar-vertical');
        if (sidebar) {
            const icons = sidebar.querySelectorAll('i[data-feather], svg.feather');
            icons.forEach(icon => {
                icon.style.display = 'inline-block';
                icon.style.visibility = 'visible';
                icon.style.opacity = '1';
            });
        }
    }
    
    // Run sidebar check periodically
    setInterval(checkSidebarIcons, 2000);
    
    // Run sidebar check on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkSidebarIcons);
    } else {
        checkSidebarIcons();
    }
})();