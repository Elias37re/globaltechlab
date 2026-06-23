/**
 * Theme Javascript utilities
 * Handles responsive menu toggle and styling triggers.
 */

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('masthead-toggle');
    const mainNav = document.getElementById('site-navigation');
    const body = document.body;

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            
            // Toggle active classes
            menuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
            body.classList.toggle('mobile-nav-active');
            
            // Accessibility update
            menuToggle.setAttribute('aria-expanded', !isExpanded);
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (mainNav.classList.contains('active') && !mainNav.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
                body.classList.remove('mobile-nav-active');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
