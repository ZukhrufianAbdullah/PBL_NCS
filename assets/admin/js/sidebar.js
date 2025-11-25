(function () {
    const stateKey = 'ncs-admin-sidebar-state';
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');
    const storedState = loadState();

    toggles.forEach((toggle) => {
        const targetId = toggle.getAttribute('data-sidebar-toggle');
        const submenu = document.getElementById(targetId);
        if (!submenu) return;

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

    // Pastikan submenu yang memiliki link aktif terbuka
    const activeLink = document.querySelector('.sidebar a.is-active');
    if (activeLink) {
        const submenu = activeLink.closest('.submenu-wrapper');
        if (submenu) {
            const toggleId = submenu.id;
            const toggleButton = document.querySelector(`[data-sidebar-toggle="${toggleId}"]`);
            if (toggleButton) {
                submenu.classList.add('is-open');
                toggleButton.classList.add('is-open');
                const icon = toggleButton.querySelector('.dropdown-icon');
                if (icon) {
                    icon.classList.add('is-open');
                }
                storedState[toggleId] = true;
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

