(() => {
    'use strict';
    const root = document.documentElement;
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    const savedTheme = localStorage.getItem('sirmed-theme');
    root.setAttribute('data-bs-theme', savedTheme || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));

    function refreshThemeControls() {
        const dark = root.getAttribute('data-bs-theme') === 'dark';
        document.querySelectorAll('#themeToggle, #themeToggleTop').forEach((button) => {
            const icon = button.querySelector('i');
            if (icon) icon.className = dark ? 'bi bi-sun' : (button.id === 'themeToggleTop' ? 'bi bi-moon-stars' : 'bi bi-moon-stars');
            const label = button.querySelector('span:not(.nav-trailing)');
            if (label && button.id === 'themeToggle') label.textContent = dark ? 'Mode terang' : 'Mode gelap';
        });
    }
    function toggleTheme() {
        const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-bs-theme', next);
        localStorage.setItem('sirmed-theme', next);
        refreshThemeControls();
    }
    document.querySelectorAll('#themeToggle, #themeToggleTop').forEach((button) => button.addEventListener('click', toggleTheme));

    function closeSidebar() {
        sidebar?.classList.remove('is-open');
        backdrop?.classList.remove('is-visible');
    }
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        sidebar?.classList.add('is-open');
        backdrop?.classList.add('is-visible');
    });
    backdrop?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeSidebar(); });
    sidebar?.querySelectorAll('a[wire\\:navigate]').forEach((link) => link.addEventListener('click', () => {
        if (matchMedia('(max-width: 991.98px)').matches) closeSidebar();
    }));
    document.addEventListener('livewire:navigated', () => { closeSidebar(); refreshThemeControls(); });
    refreshThemeControls();
})();
