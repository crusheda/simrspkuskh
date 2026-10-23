
/* =========================================================
   SIRMED V3 — GLOBAL BUTTON BEHAVIOR
========================================================= */

(() => {
    'use strict';

    // Hindari pemasangan listener berulang
    if (window.SIRMEDButtonsInitialized) return;
    window.SIRMEDButtonsInitialized = true;

    /**
     * Loading button:
     * <button data-loading-text="Menyimpan...">
     */
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-loading-text]');

        if (!button || button.disabled) return;

        // Hanya bekerja bila atribut loading dikonfigurasi
        if (button.dataset.loadingActive === 'true') return;

        // Jangan mengubah tombol submit secara otomatis jika form
        // memiliki validasi yang belum terpenuhi
        if (
            button.type === 'submit' &&
            button.form &&
            !button.form.checkValidity()
        ) {
            return;
        }

        const loadingText = button.dataset.loadingText;
        if (!loadingText) return;

        button.dataset.loadingActive = 'true';
        button.dataset.originalHtml = button.innerHTML;
        button.dataset.wasDisabled = String(button.disabled);

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');

        button.innerHTML = `
            <span class="spinner-border spinner-border-sm"
                  role="status"
                  aria-hidden="true"></span>
            <span>${escapeHtml(loadingText)}</span>
        `;
    });

    /**
     * Kembalikan tombol loading:
     * SIRMEDButtons.reset(button)
     */
    window.SIRMEDButtons = {
        reset(button) {
            if (!button || !button.dataset.originalHtml) return;

            button.innerHTML = button.dataset.originalHtml;
            button.disabled = button.dataset.wasDisabled === 'true';

            button.removeAttribute('aria-busy');
            delete button.dataset.loadingActive;
            delete button.dataset.originalHtml;
            delete button.dataset.wasDisabled;
        },

        setLoading(button, text = 'Memproses...') {
            if (!button || button.dataset.loadingActive === 'true') return;

            button.dataset.originalHtml = button.innerHTML;
            button.dataset.wasDisabled = String(button.disabled);
            button.dataset.loadingActive = 'true';

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm"
                      role="status"
                      aria-hidden="true"></span>
                <span>${escapeHtml(text)}</span>
            `;
        }
    };

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }
})();
