(function (){
    // Initialize feather icons with multiple attempts
    function initFeather() {
        if (typeof feather !== 'undefined') {
            feather.replace();
            return true;
        }
        return false;
    }

    // Try immediately
    initFeather();

    // Try after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initFeather, 50);
            setTimeout(initFeather, 200);
            setTimeout(initFeather, 500);
        });
    } else {
        setTimeout(initFeather, 50);
        setTimeout(initFeather, 200);
        setTimeout(initFeather, 500);
    }

    // Try after window load
    window.addEventListener('load', function() {
        setTimeout(initFeather, 100);
        setTimeout(initFeather, 300);
    });

    // Observe DOM changes for dynamic content
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            initFeather();
        });
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();