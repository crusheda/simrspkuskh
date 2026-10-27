
(() => {
    'use strict';

    // Hindari inisialisasi global berulang.
    if (window.SIRMEDAppInitialized) return;
    window.SIRMEDAppInitialized = true;

    const root = document.documentElement;

    function getTheme() {
        return root.getAttribute('data-bs-theme') === 'dark'
            ? 'dark'
            : 'light';
    }

    function refreshThemeControls() {
        const dark = getTheme() === 'dark';

        document.querySelectorAll(
            '#themeToggle, #themeToggleTop'
        ).forEach((button) => {
            const icon = button.querySelector('i');

            if (icon) {
                icon.className = dark
                    ? 'bi bi-sun'
                    : 'bi bi-moon-stars';
            }

            const label = button.querySelector(
                'span:not(.nav-trailing)'
            );

            if (label && button.id === 'themeToggle') {
                label.textContent = dark
                    ? 'Mode terang'
                    : 'Mode gelap';
            }

            button.setAttribute(
                'aria-pressed',
                String(dark)
            );
        });
    }

    function toggleTheme() {
        const next = getTheme() === 'dark' ? 'light' : 'dark';

        root.setAttribute('data-bs-theme', next);

        try {
            localStorage.setItem('sirmed-theme', next);
        } catch (e) {}

        refreshThemeControls();
    }

    function applyTheme(theme) {
        root.setAttribute('data-bs-theme', theme);

        try {
            localStorage.setItem('sirmed-theme', theme);
        } catch (e) {}

        refreshThemeControls();
    }

    function syncSavedTheme() {
        let theme = 'light';

        try {
            const savedTheme = localStorage.getItem('sirmed-theme');

            if (savedTheme === 'dark' || savedTheme === 'light') {
                theme = savedTheme;
            } else {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches
                    ? 'dark'
                    : 'light';
            }
        } catch (e) {}

        applyTheme(theme);
    }

    // Delegasi klik: tetap bekerja ketika Livewire mengganti tombol.
    document.addEventListener('click', (event) => {
        const button = event.target.closest(
            '#themeToggle, #themeToggleTop'
        );

        if (!button) return;

        event.preventDefault();
        toggleTheme();
    });

    // Sidebar
    function closeSidebar() {
        document.getElementById('appSidebar')
            ?.classList.remove('is-open');

        document.querySelector('.sidebar-backdrop')
            ?.classList.remove('is-visible');
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('#sidebarToggle')) {
            document.getElementById('appSidebar')
                ?.classList.add('is-open');

            document.querySelector('.sidebar-backdrop')
                ?.classList.add('is-visible');

            return;
        }

        if (event.target.closest('.sidebar-backdrop')) {
            closeSidebar();
            return;
        }

        const link = event.target.closest(
            '#appSidebar a[wire\\:navigate]'
        );

        if (
            link &&
            window.matchMedia('(max-width: 991.98px)').matches
        ) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeSidebar();
    });

    document.addEventListener('livewire:navigated', () => {
        closeSidebar();
        syncSavedTheme();
    });

    refreshThemeControls();
})();
