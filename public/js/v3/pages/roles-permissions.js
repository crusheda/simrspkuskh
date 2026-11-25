(function () {
    const TABLE_SELECTOR = '.sirmed-datatable';
    const PAGE_SELECTOR = '.sirmed-setting-page';

    function getTables(root = document) {
        const tables = [];

        if (root.matches?.(TABLE_SELECTOR)) {
            tables.push(root);
        }

        if (root.querySelectorAll) {
            tables.push(...root.querySelectorAll(TABLE_SELECTOR));
        }

        return $(tables);
    }

    function initSirmedDataTables(root = document) {
        if (typeof $.fn.DataTable === 'undefined') return;

        getTables(root).each(function () {
            if ($.fn.DataTable.isDataTable(this)) return;

            $(this).DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 20,
                ordering: true,
                // Baca order dari attribute Blade
                // order: [],
                order: $(this).data('order') || [],
                searching: true,
                paging: true,
                info: true
            });
        });
    }

    function destroySirmedDataTables(root = document) {
        if (typeof $.fn.DataTable === 'undefined') return;

        getTables(root).each(function () {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().destroy();
            }
        });
    }

    function getSettingPage(el) {
        if (!(el instanceof Element)) return null;

        return el.closest(PAGE_SELECTOR);
    }

    function reinitialize(el) {
        const page = getSettingPage(el);

        if (!page) return;

        // Tunggu Livewire menyelesaikan proses DOM morph
        setTimeout(() => {
            if (page.isConnected) {
                initSirmedDataTables(page);
            }
        }, 0);
    }

    document.addEventListener('livewire:init', () => {

        Livewire.hook('morph.updating', ({ el }) => {
            const page = getSettingPage(el);

            if (!page) return;

            // Destroy hanya DataTables pada halaman terkait
            destroySirmedDataTables(page);
        });

        Livewire.hook('morph.updated', ({ el }) => {
            reinitialize(el);
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initSirmedDataTables();
    });

    document.addEventListener('DOMContentLoaded', () => {
        initSirmedDataTables();
    });

    window.SirmedDataTable = {
        init: initSirmedDataTables,
        destroy: destroySirmedDataTables
    };
})();
