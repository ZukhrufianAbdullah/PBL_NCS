(function () {
    const stateKey = 'ncs-admin-sidebar-state';
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');
    const storedState = loadState();

    // Function to get current page from URL
    function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop().replace('.php', '');
        return page;
    }

    // Function to auto-open submenus based on active page
    function autoOpenActiveSubmenus() {
        const currentPage = getCurrentPage();
        const activeLink = document.querySelector(`.sidebar-link[data-page="${currentPage}"]`);
        
        if (activeLink) {
            // Add active class to the link
            activeLink.classList.add('is-active');
            
            // Find and open parent submenu
            const submenu = activeLink.closest('.submenu-wrapper');
            if (submenu) {
                const submenuId = submenu.id;
                const toggleButton = document.querySelector(`[data-sidebar-toggle="${submenuId}"]`);
                
                if (toggleButton) {
                    submenu.classList.add('is-open');
                    toggleButton.classList.add('is-open');
                    const icon = toggleButton.querySelector('.dropdown-icon');
                    if (icon) {
                        icon.classList.add('is-open');
                    }
                    storedState[submenuId] = true;
                    saveState(storedState);
                }
            }
        }
    }

    // Initialize toggle functionality
    toggles.forEach((toggle) => {
        const targetId = toggle.getAttribute('data-sidebar-toggle');
        const submenu = document.getElementById(targetId);
        if (!submenu) return;

        // Restore state from localStorage
        if (storedState[targetId]) {
            submenu.classList.add('is-open');
            const icon = toggle.querySelector('.dropdown-icon');
            if (icon) {
                icon.classList.add('is-open');
            }
            toggle.classList.add('is-open');
        }

        toggle.addEventListener('click', () => {
            const isOpen = submenu.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            const icon = toggle.querySelector('.dropdown-icon');
            if (icon) {
                icon.classList.toggle('is-open', isOpen);
            }
            storedState[targetId] = isOpen;
            saveState(storedState);
        });
    });

    // Auto-open submenus on page load
    autoOpenActiveSubmenus();

    // Also check for PHP-added active classes (fallback)
    const phpActiveLink = document.querySelector('.sidebar a.is-active');
    if (phpActiveLink) {
        const submenu = phpActiveLink.closest('.submenu-wrapper');
        if (submenu) {
            const submenuId = submenu.id;
            const toggleButton = document.querySelector(`[data-sidebar-toggle="${submenuId}"]`);
            if (toggleButton && !toggleButton.classList.contains('is-open')) {
                submenu.classList.add('is-open');
                toggleButton.classList.add('is-open');
                const icon = toggleButton.querySelector('.dropdown-icon');
                if (icon) {
                    icon.classList.add('is-open');
                }
                storedState[submenuId] = true;
                saveState(storedState);
            }
        }
    }

    function loadState() {
        try {
            const raw = localStorage.getItem(stateKey);
            return raw ? JSON.parse(raw) : {};
        } catch (error) {
            console.warn('Gagal memuat state sidebar:', error);
            return {};
        }
    }

    function saveState(state) {
        try {
            localStorage.setItem(stateKey, JSON.stringify(state));
        } catch (error) {
            console.warn('Gagal menyimpan state sidebar:', error);
        }
    }
})();