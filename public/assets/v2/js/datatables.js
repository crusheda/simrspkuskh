// GLOBAL SETTING DATATABLE
$.extend(true, $.fn.dataTable.defaults, {
    dom: `
        <"d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2"
            <"dt-buttons"B>
            <"d-flex align-items-center gap-2"
                <"dt-length"l>
                <"dt-search"f>
            >
        >
        rt
        <"d-flex flex-wrap justify-content-between mt-2"ip>
    `,
    buttons: [
        {
            extend: 'excel',
            text: 'Excel',
            className: 'btn btn-subtle-success btn-sm me-2'
        },
        {
            extend: 'pdf',
            text: 'PDF',
            className: 'btn btn-subtle-danger btn-sm me-2'
        },
        {
            extend: 'colvis',
            text: 'Kolom ',
            className: 'btn btn-subtle-info btn-sm'
        }
    ],
    language: {
        searchPlaceholder: 'Tuliskan Kata Kunci...',
        sSearch: 'Cari Data ',
        lengthMenu: "Tampilkan _MENU_",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Tidak ada data",
        infoFiltered: "(difilter dari _MAX_ total data)",
        zeroRecords: "Data tidak ditemukan",
        emptyTable: "Tidak ada data yang tersedia",
        paginate: {
            first: "Awal",
            last: "Akhir",
            next: "›",
            previous: "‹"
        }
    },
    lengthChange: true,
    lengthMenu: [5, 10, 15, 20, 25, 30, 35, 50, 75, 100, 500, 1000, 3000, 5000, 7000, 10000],
    displayLength: 20
});

$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    let keyword = $('.dataTables_filter input')
        .val()
        .toLowerCase()
        .trim();

    if (!keyword) {
        return true;
    }

    let rowText = data.join(' ')
        .toLowerCase()
        .trim();

    let keywords = keyword.split(/\s+/);

    return keywords.every(function(word) {

        // partial match per kata
        return rowText.includes(word);

    });

});

$.extend(true, $.fn.dataTable.defaults, {
    initComplete: function () {
        $('.dataTables_wrapper').addClass('text-dark');
    }
});
