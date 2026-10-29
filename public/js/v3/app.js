(() => {
    'use strict';

    // Hindari inisialisasi global berulang.
    if (window.SIRMEDAppInitialized) return;
    window.SIRMEDAppInitialized = true;

    const root = document.documentElement;
    const THEME_KEY = 'sirmed-theme';

    /* =========================================================
       THEME
    ========================================================= */

    function getTheme() {
        return root.getAttribute('data-bs-theme') === 'dark'
            ? 'dark'
            : 'light';
    }

    function normalizeTheme(theme) {
        return theme === 'dark' ? 'dark' : 'light';
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

    /**
     * Kirim tema ke seluruh iframe yang berasal dari origin sama.
     */
    function syncThemeToIframes(theme = getTheme()) {
        const currentTheme = normalizeTheme(theme);

        document.querySelectorAll('iframe').forEach((iframe) => {
            try {
                if (!iframe.contentWindow) return;

                iframe.contentWindow.postMessage(
                    {
                        type: 'SIRMED_THEME_CHANGE',
                        theme: currentTheme
                    },
                    window.location.origin
                );
            } catch (error) {
                // Iframe mungkin belum siap atau berasal dari origin lain.
            }
        });
    }

    /**
     * Terapkan tema pada halaman utama dan sinkronkan ke iframe.
     */
    function applyTheme(theme, options = {}) {
        const {
            save = true,
            syncIframes = true
        } = options;

        const nextTheme = normalizeTheme(theme);

        root.setAttribute('data-bs-theme', nextTheme);
        root.style.colorScheme = nextTheme;

        if (save) {
            try {
                localStorage.setItem(THEME_KEY, nextTheme);
            } catch (error) {
                // localStorage mungkin tidak tersedia.
            }
        }

        refreshThemeControls();

        if (syncIframes) {
            syncThemeToIframes(nextTheme);
        }
    }

    /**
     * Ambil tema tersimpan atau gunakan preferensi sistem.
     */
    function syncSavedTheme() {
        let theme = 'light';

        try {
            const savedTheme = localStorage.getItem(THEME_KEY);

            if (savedTheme === 'dark' || savedTheme === 'light') {
                theme = savedTheme;
            } else {
                theme = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches
                    ? 'dark'
                    : 'light';
            }
        } catch (error) {
            theme = window.matchMedia(
                '(prefers-color-scheme: dark)'
            ).matches
                ? 'dark'
                : 'light';
        }

        applyTheme(theme);
    }

    /**
     * Toggle tema.
     */
    function toggleTheme() {
        const nextTheme = getTheme() === 'dark'
            ? 'light'
            : 'dark';

        applyTheme(nextTheme);
    }

    /* =========================================================
       EVENT TOMBOL TEMA
    ========================================================= */

    // Delegasi klik tetap bekerja ketika Livewire mengganti tombol.
    document.addEventListener('click', (event) => {
        const button = event.target.closest(
            '#themeToggle, #themeToggleTop'
        );

        if (!button) return;

        event.preventDefault();
        toggleTheme();
    });

    /* =========================================================
       SINKRONISASI TEMA ANTAR TAB BROWSER
    ========================================================= */

    window.addEventListener('storage', (event) => {
        if (event.key === THEME_KEY || event.key === null) {
            syncSavedTheme();
        }
    });

    /* =========================================================
       SINKRONISASI IFRAME
    ========================================================= */

    /**
     * Iframe yang selesai dimuat langsung menerima tema aktif.
     */
    function bindIframeThemeSync() {
        document.querySelectorAll('iframe').forEach((iframe) => {
            if (iframe.dataset.sirmedThemeListener === 'true') {
                return;
            }

            iframe.dataset.sirmedThemeListener = 'true';

            iframe.addEventListener('load', () => {
                syncThemeToIframes(getTheme());
            });

            // Sinkronisasi awal untuk iframe yang sudah dimuat.
            try {
                iframe.contentWindow?.postMessage(
                    {
                        type: 'SIRMED_THEME_CHANGE',
                        theme: getTheme()
                    },
                    window.location.origin
                );
            } catch (error) {
                // Abaikan iframe yang tidak dapat diakses.
            }
        });
    }

    /**
     * Amati iframe yang ditambahkan secara dinamis,
     * misalnya ketika tab EMR dibuka.
     */
    const iframeObserver = new MutationObserver(() => {
        bindIframeThemeSync();
    });

    iframeObserver.observe(document.documentElement, {
        childList: true,
        subtree: true
    });

    /* =========================================================
       SIDEBAR
    ========================================================= */

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
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    /* =========================================================
       LIVEWIRE NAVIGATION
    ========================================================= */

    document.addEventListener('livewire:navigated', () => {
        closeSidebar();

        // Baca ulang pilihan tema dan sinkronkan iframe.
        syncSavedTheme();

        // Perbarui kontrol jika tombol dirender ulang.
        refreshThemeControls();

        // Pastikan iframe baru terdeteksi.
        bindIframeThemeSync();
    });

    /* =========================================================
       INITIALIZATION
    ========================================================= */

    refreshThemeControls();
    bindIframeThemeSync();
})();
