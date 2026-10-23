
/* =========================================================
   GLOBAL SETTING DATATABLES
   SIRMED V3 - Bootstrap 5
========================================================= */

$.extend(true, $.fn.dataTable.defaults, {
    // DOM layout Bootstrap / NexLink v3
    dom: `
        <"d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"
            <"dt-buttons d-flex flex-wrap align-items-center gap-2"B>
            <"d-flex flex-wrap align-items-center gap-2 ms-auto"
                <"dt-length"l>
                <"dt-search"f>
            >
        >
        <"table-responsive"rt>
        <"d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3"
            <"dt-info"i>
            <"dt-paging"p>
        >
    `,

    // Tampilan dan tombol
    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
            className: 'btn btn-soft-success btn-sm',
            titleAttr: 'Export ke Excel'
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="ri-file-pdf-2-line me-1"></i> PDF',
            className: 'btn btn-soft-danger btn-sm',
            titleAttr: 'Export ke PDF',
            orientation: 'landscape',
            pageSize: 'A4'
        },
        {
            extend: 'colvis',
            text: '<i class="ti ti-columns-3 me-1"></i> <span class="me-1">Kolom</span>',
            className: 'btn btn-soft-info btn-sm',
            titleAttr: 'Atur kolom tabel'
        }
    ],

    // Bahasa Indonesia
    language: {
        search: '',
        searchPlaceholder: 'Tuliskan kata kunci...',
        lengthMenu: 'Tampilkan _MENU_ data',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        infoFiltered: '(difilter dari _MAX_ total data)',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Tidak ada data yang tersedia',
        loadingRecords: 'Memuat data...',
        processing: 'Memproses data...',
        thousands: '.',
        decimal: ',',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: '<i class="ti ti-chevron-right"></i>',
            previous: '<i class="ti ti-chevron-left"></i>'
        },
        buttons: {
            colvis: 'Kolom'
        }
    },

    // Default tabel
    lengthChange: true,
    lengthMenu: [
        [5, 10, 15, 20, 25, 30, 35, 50, 75, 100, 500, 1000, 3000, 5000, 7000, 10000],
        [5, 10, 15, 20, 25, 30, 35, 50, 75, 100, 500, 1000, 3000, 5000, 7000, 10000]
    ],
    pageLength: 20,
    displayLength: 20,
    autoWidth: false,
    processing: true,

    // Bahasa dan style setelah tabel siap
    initComplete: function () {
        const api = this.api();
        const $wrapper = $(api.table().container());

        $wrapper.addClass('text-body');

        // Input pencarian Bootstrap v3
        $wrapper.find('.dataTables_filter input')
            .addClass('form-control form-control-sm')
            .attr('placeholder', 'Tuliskan kata kunci...')
            .attr('aria-label', 'Cari data');

        // Dropdown jumlah data
        $wrapper.find('.dataTables_length select')
            .addClass('form-select form-select-sm')
            .attr('aria-label', 'Jumlah data per halaman');

        // Pastikan tombol memiliki style konsisten
        $wrapper.find('.dt-buttons .dt-button')
            .addClass('waves-effect');
    }
});


/* =========================================================
   GLOBAL SMART SEARCH
   Semua kata harus cocok, urutan kata bebas
========================================================= */

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const api = new $.fn.dataTable.Api(settings);
    const wrapper = $(api.table().container());

    const keyword = wrapper
        .find('.dataTables_filter input')
        .val()
        ?.toLowerCase()
        .trim() ?? '';

    if (!keyword) {
        return true;
    }

    const rowText = data
        .join(' ')
        .toLowerCase();

    const keywords = keyword.split(/\s+/);

    return keywords.every(function (word) {
        return rowText.includes(word);
    });
});
