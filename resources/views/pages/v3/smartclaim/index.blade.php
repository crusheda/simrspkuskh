@section('title', 'Smart Claim')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Smart Claim')

<style>
    #sep_px::placeholder {
        color: #999;
        opacity: 1;
    }

    /* Konten halaman Home Smart Claim di dalam panel workspace. */
    .claim-home-content {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 100%;
        padding: 1rem;
        box-sizing: border-box;
    }

    /* .claim-home-content .row {
        max-width: 100%;
    } */

    .claim-home-content .card {
        min-width: 0;
    }

    @media (max-width: 575.98px) {
        .claim-home-content {
            padding: .75rem;
        }
    }
</style>

<section class="claim-home-content">

        {{-- MAIN CONTENT --}}
        <div class="row g-3 w-100">

            {{-- CLAIM TABLE --}}
            <div class="col-xl-8">
                <div class="card rounded-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-table text-primary me-1"></i>
                            Claim Table

                            <span class="ms-2 f-14 px-2 badge text-bg-secondary"
                                id="jumlah_claim">
                                <i class="fas fa-sync fa-spin"></i>
                            </span>
                        </h5>
                    </div>

                    <div class="card-body p-3 pb-0 table-body">
                        <div class="table-responsive">

                            <table class="table mb-0 table-hover table-striped" id="dttable">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">KUNJUNGAN</th>
                                        <th style="width: 30%;" class="text-end">
                                            TGL KUNJUNGAN
                                        </th>
                                        <th style="width: 10%;" class="text-end">
                                            STATUS
                                        </th>
                                        <th style="width: 10%;" class="text-end">
                                            CATATAN
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="tampil-tbody">
                                    <tr style="font-size:13px">
                                        <td colspan="4">
                                            <center>
                                                <div class="spinner-border spinner-border-sm"
                                                    role="status"></div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-xl-4">

                {{-- PENCARIAN SEP --}}
                <div class="card rounded-4 mb-3">
                    <div class="card-body">

                        <h5>
                            <i class="ti ti-list-search text-primary me-1"></i>
                            Pencarian No.SEP
                        </h5>

                        <label class="form-label">
                            <small>
                                Lengkapi No.SEP dibawah dengan Format <br>Isian : 0151R013<b class="text-teal">xxx</b> (<b class="text-orange">19 Digit</b>)
                            </small>
                        </label>

                        <div class="input-group my-3 mt-2 mb-0">
                            <input type="text" class="form-control"
                                placeholder="Masukkan No.SEP Pasien" id="sep_px" maxlength="19"
                                autocomplete="off">

                            <button class="btn btn-subtle-success" type="button" id="btn-cari"
                                onclick="cariSep()" disabled>
                                <i class="fas fa-search align-middle me-1"></i>
                                Cari
                            </button>
                        </div>

                    </div>
                </div>

                {{-- FILTER --}}
                <div class="card rounded-4">
                    <div class="card-body">

                        <h5 class="mb-2">
                            <i class="ti ti-filter text-primary me-1"></i>
                            Filter
                        </h5>

                        <div class="row">

                            {{-- JENIS PERAWATAN --}}
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Jenis Perawatan
                                    </label>

                                    <select id="filter_rawat" class="form-control">
                                        <option value="5">
                                            Semua Perawatan
                                        </option>
                                        <option value="1" selected>
                                            Rawat Jalan
                                        </option>
                                        <option value="2">
                                            Rawat Darurat (Tanpa Inap)
                                        </option>
                                        <option value="3">
                                            Rawat Inap
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- FILTER TANGGAL --}}
                            <div class="col-md-12 mb-3" id="hide-filter-tgl" hidden>
                                <label class="form-label">
                                    Rentang Tgl Kunjungan
                                </label>

                                <div class="input-group">
                                    <input type="text" id="filter_tgl"
                                        class="form-control flatpickr-input active border border-2"
                                        placeholder="Pilih Rentang Tanggal" disabled>

                                    <button class="btn btn-secondary" type="button"
                                        id="btn-clear-filter-tgl" disabled>
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- FILTER BULAN --}}
                            <div class="col-md-12 mb-3" id="hide-filter-bulan">
                                <label class="form-label">
                                    Bulan Kunjungan
                                </label>

                                <div class="input-group">
                                    <input type="month" class="form-control"
                                        value="{{ $list['yearMonth'] }}" placeholder="Pilih Bulan"
                                        id="filter_bulan">

                                    <button class="btn btn-warning" type="button"
                                        id="btn-clear-filter-bulan">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- FILTER DPJP --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        DPJP
                                    </label>

                                    <select class="form-control" id="filter_dpjp">
                                        <option value="">
                                            Pilih Dokter
                                        </option>

                                        @if ($list['dr'])
                                            @foreach ($list['dr'] as $item)
                                                <option value="{{ $item->NIP }}">
                                                    {{ $item->NAMADOKTER }}
                                                    ({{ $item->DESKRIPSI }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary w-100" onclick="filter()" id="tombol-tampilkan"
                            disabled>
                            <i class="fa fa-filter me-1" style="font-size: 13px"></i>
                            Tampilkan
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL CATATAN --}}
        <div id="catatan" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="catatanLabel">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="catatanLabel">
                            <span class="badge text-bg-secondary">
                                CATATAN
                            </span>
                            |
                            IDKUNJUNGAN :
                            <a id="show-id-catatan" class="text-primary"></a>
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-3">

                        <small>
                            <i class="fas fa-sort-amount-down me-1"></i>
                            <b>
                                Tabel di bawah diurutkan berdasarkan
                                <mark>TANGGAL</mark>
                                catatan pertama kali ditambahkan
                            </b>
                        </small>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;" class="text-center">
                                            AKSI
                                        </th>
                                        <th style="width: 25%;">
                                            NAMA PENGGUNA
                                        </th>
                                        <th style="width: 65%;">
                                            DESKRIPSI CATATAN
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="tampil-catatan">
                                    <tr>
                                        <td colspan="3">
                                            <center>
                                                <div class="spinner-border spinner-border-sm"
                                                    role="status"></div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <div id="btn-refresh-catatan"></div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
        {{-- END MODAL CATATAN --}}

</section>

@script
    <script>
        $(function() {
            // SELECT2 DPJP
            initSelectDPJP();

            // INPUT SEP
            initSepInput();

            // INITIAL FILTER
            filter();

            // ENTER CARI SEP
            $('#sep_px').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    cariSep();
                }
            });

            // ENTER TAMPILKAN
            $('#filter_rawat, #filter_bulan, #filter_dpjp, #filter_tgl')
                .on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filter();
                    }
                });

            // FLATPICKR DATE
            const today = new Date();
            const fiveYearsAgo = new Date();

            fiveYearsAgo.setFullYear(today.getFullYear() - 5);

            $("#filter_tgl").flatpickr({
                mode: 'range',
                minDate: fiveYearsAgo,
                maxDate: today,
                dateFormat: 'Y-m-d'
            });

            // CLEAR FILTER TANGGAL
            $("#btn-clear-filter-tgl").on("click", function() {
                $(this)
                    .removeClass('btn-warning')
                    .addClass('btn-secondary')
                    .prop('disabled', true);

                $("#btn-clear-filter-bulan")
                    .removeClass('btn-secondary')
                    .addClass('btn-warning')
                    .prop('disabled', false);

                $("#filter_tgl")
                    .val("")
                    .trigger("change")
                    .prop('disabled', true);

                $("#filter_bulan")
                    .val("")
                    .trigger("change")
                    .prop('disabled', false);

                $("#hide-filter-bulan").prop('hidden', false);
                $("#hide-filter-tgl").prop('hidden', true);
            });

            // CLEAR FILTER BULAN
            $("#btn-clear-filter-bulan").on("click", function() {
                $(this)
                    .removeClass('btn-warning')
                    .addClass('btn-secondary')
                    .prop('disabled', true);

                $("#btn-clear-filter-tgl")
                    .removeClass('btn-secondary')
                    .addClass('btn-warning')
                    .prop('disabled', false);

                $("#filter_bulan")
                    .val("")
                    .trigger("change")
                    .prop('disabled', true);

                $("#filter_tgl")
                    .val("")
                    .trigger("change")
                    .prop('disabled', false);

                $("#hide-filter-tgl").prop('hidden', false);
                $("#hide-filter-bulan").prop('hidden', true);
            });

            $('#btn-cari').prop('disabled', false);

        });

        // PERMISSION
        const canSmartClaim = {{ auth()->user()->can('smart_claim') ? 'true' : 'false' }};
        const canSmartClaimFarmasi = {{ auth()->user()->can('smart_claim_farmasi') ? 'true' : 'false' }};

        /**
         * SELECT2 DPJP
         * Select biasa dengan fitur pencarian.
         */
        function initSelectDPJP() {
            const $dpjp = $('#filter_dpjp');

            if (!$dpjp.length) {
                return;
            }

            // Hindari inisialisasi ganda saat navigasi Livewire
            if ($dpjp.hasClass('select2-hidden-accessible')) {
                $dpjp.select2('destroy');
            }

            $dpjp.select2({
                width: '100%',
                placeholder: 'Pilih Dokter',
                allowClear: false,
                closeOnSelect: true,
                minimumResultsForSearch: 0,

                language: {
                    noResults: function () {
                        return 'Dokter tidak ditemukan';
                    },

                    searching: function () {
                        return 'Mencari dokter...';
                    }
                }
            });
        }

        // FUNCTION FILTER
        function filter() {
            $('#tombol-tampilkan')
                .prop('disabled', true)
                .find('i')
                .removeClass('fa-filter')
                .addClass('fa-sync fa-spin');

            $("#tampil-tbody").empty().append(`
            <tr style="font-size:13px">
                <td colspan="4">
                    <center>
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </center>
                </td>
            </tr>
        `);

            // INITIALIZE FILTER
            var pel = $("#filter_rawat").val();
            var bln = $("#filter_bulan").val() ? $("#filter_bulan").val() : '0';
            var dpjp = $("#filter_dpjp").val() ? $("#filter_dpjp").val() : '0';
            var tgl = $("#filter_tgl").val() ? $("#filter_tgl").val() : '0';

            var exTgl = tgl.split(' to ');

            if (exTgl.length == 2) {
                tgls = exTgl[0];
                tgle = exTgl[1];
            } else {
                tgls = exTgl[0];
                tgle = exTgl[0];
            }

            $.ajax({
                url: `/api/v3/klaim/table/${pel}/${tgls}/${tgle}/${bln}/${dpjp}`,
                type: 'GET',
                dataType: 'json',

                beforeSend: function() {
                    $('#jumlah_claim')
                        .empty()
                        .append('<i class="fas fa-sync fa-spin"></i>');
                },

                success: function(res) {

                    $("#tampil-tbody").empty();

                    if ($.fn.DataTable.isDataTable('#dttable')) {
                        $('#dttable').DataTable().clear().destroy();
                    }

                    const jumlahData = Array.isArray(res.show) ? res.show.length : 0;
                    $('#jumlah_claim').text(jumlahData + ' Data');

                    if (res.show && Array.isArray(res.show)) {

                        res.show.forEach(item => {

                            let badgeKonsul = '';
                            let addOnKonsul = null;

                            if (item.JENIS_KUNJUNGAN == 'UTAMA') {

                                badgeKonsul = `
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-sm">
                                    UTAMA
                                </span>
                            `;

                            } else {

                                if (item.JENIS_KUNJUNGAN == 'KONSUL') {

                                    badgeKonsul = `
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-sm">
                                        KONSUL
                                    </span>
                                `;

                                    if (item.RUANGAN_KONSUL && item.DOKTER_KONSUL) {

                                        addOnKonsul = `
                                        <span class="badge bg-success-subtle text-success border border-success-subtle badge-sm">
                                            Dari Ruangan ${item.RUANGAN_KONSUL}
                                            &nbsp;&nbsp;-&nbsp;&nbsp;
                                            ${item.DOKTER_KONSUL}
                                        </span>
                                    `;
                                    }

                                } else {

                                    badgeKonsul = `
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle badge-sm">
                                        TIDAK DITEMUKAN
                                    </span>
                                `;
                                }
                            }


                            // SEP
                            let SEP = '';

                            if (item.NOSEP) {

                                let valSEP = item.NOSEP.substring(8, 12);
                                let parts = item.TGLSEP.split("-");
                                let valTGLSEP = parts[1] + parts[0].slice(-2);

                                if (valSEP == valTGLSEP) {

                                    SEP = `
                                    <b class="text-salmon">
                                        ${item.NOSEP}
                                    </b>
                                `;

                                } else {

                                    SEP = `
                                    <b class="text-salmon">
                                        ${item.NOSEP}
                                    </b>

                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1">
                                        <b>Tanggal SEP Tidak Sesuai!</b>
                                    </span>
                                `;
                                }

                            } else {

                                SEP = `
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    <b>SEP Tidak Ditemukan</b>
                                </span>
                            `;
                            }

                            // STATUS VERIFIKASI
                            let stt = '';

                            if (item.STATUSVERIF) {

                                stt = `
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-subtle-success btn-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Telah Diverifikasi Oleh ${item.NAMAVERIF} pada ${item.TGLVERIF}"
                                    onclick="event.stopPropagation();"
                                >
                                    <i class="ti ti-square-check fs-40"></i>
                                </a>
                            `;

                            } else {

                                stt = `
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-subtle-danger btn-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Berkas Belum Diverifikasi"
                                    onclick="event.stopPropagation();"
                                >
                                    <i class="ti ti-square-x fs-40"></i>
                                </a>
                            `;
                            }

                            // CATATAN
                            let cat = '';

                            if (item.CATATAN == 2) {

                                cat = `
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-subtle-success btn-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Semua Catatan Berkas Klaim Terselesaikan"
                                    onclick="event.stopPropagation(); lihatCatatan('${item.NOMOR}')"
                                    id="catatan${item.NOMOR}"
                                >
                                    <i class="ti ti-file-text fs-40"></i>
                                </a>
                            `;

                            } else if (item.CATATAN == 1) {

                                cat = `
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-subtle-warning btn-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Lihat Catatan Berkas Klaim"
                                    onclick="event.stopPropagation(); lihatCatatan('${item.NOMOR}')"
                                    id="catatan${item.NOMOR}"
                                >
                                    <i class="ti ti-file-text fs-40"></i>
                                </a>
                            `;

                            } else {

                                cat = `
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-subtle-secondary btn-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Tidak Ada Catatan"
                                    onclick="event.stopPropagation();"
                                >
                                    <i class="ti ti-file-x fs-40"></i>
                                </a>
                            `;
                            }

                            // =====================================================
                            // URL DETAIL SMARTCLAIM
                            // =====================================================

                            let urlKlaim = '#';
                            let claimType = 'smart_claim';

                            if (canSmartClaim) {

                                claimType = 'smart_claim';

                                urlKlaim = `/v3/smartclaim/${encodeURIComponent(item.NOMOR)}`;

                            } else if (canSmartClaimFarmasi) {

                                claimType = 'smart_claim_farmasi';

                                urlKlaim = `/v3/smartclaim/${encodeURIComponent(item.NOMOR)}/farmasi`;

                            }

                            // =====================================================
                            // METADATA CLAIM WORKSPACE
                            // =====================================================
                            const statusVerif =
                                item.STATUSVERIF === true ||
                                Number(item.STATUSVERIF) === 1;

                            const namaRuangan = [
                                item.NAMARUANGAN ?? '',
                                item.NAMAKAMAR ?? '',
                                item.NAMATT ?? ''
                            ]
                                .filter(value => String(value).trim() !== '')
                                .join(' - ');

                            const claimMeta = encodeURIComponent(JSON.stringify({

                                // Identitas kunjungan
                                nomor: String(item.NOMOR ?? ''),
                                type: claimType,

                                // Identitas pasien (mengikuti alias EMR)
                                norm: item.NORM ?? '',
                                nama: item.NAMAPASIEN ?? '',
                                namaLengkap: item.NAMALENGKAPPASIEN ?? '',
                                panggilan: item.PANGGILANPASIEN ?? '',
                                tglLahir: item.TGLLAHIRPASIEN ?? '',
                                umur: item.UMURPASIEN ?? '',
                                jenisKelamin: item.JKPASIEN ?? '',
                                nik: item.NIKPASIEN ?? '',
                                noHP: item.NOHPPASIEN ?? '',

                                // Asuransi / BPJS
                                namaAsuransi: item.NAMAASURANSI ?? '',
                                idAsuransi: item.IDASURANSI ?? '',
                                refIdAsuransi: item.REFIDASURANSI ?? '',
                                noAsuransi: item.NOASURANSI ?? '',
                                noBPJS: item.NOBPJS ?? '',
                                noSEP: item.NOSEP ?? '',
                                tglSEP: item.TGLSEP ?? '',
                                statusBPJS: item.STATUSBPJS ?? '',
                                prbPasien: item.PRBPASIEN ?? '',

                                // Ruangan dan DPJP
                                idRuangan: item.IDRUANGAN ?? '',
                                namaRuangan: namaRuangan,
                                namaRuanganAsalKonsul: item.RUANGAN_KONSUL ?? '',
                                namaDokter: item.NAMADOKTER ?? '',
                                namaDokterAsalKonsul: item.DOKTER_KONSUL ?? '',

                                // Waktu kunjungan
                                tglDaftar: item.TGLDAFTAR ?? '',
                                tglMasuk: item.MASUK ?? '',
                                tglKeluar: item.KELUAR ?? '',

                                // Informasi konsul
                                jenisKunjungan: String(item.JENIS_KUNJUNGAN ?? '').trim().toUpperCase(),
                                noKunjunganKonsul: item.NO_KUNJUNGAN_KONSUL ?? '',
                                tglKunjunganKonsul: item.TGL_KUNJUNGAN_KONSUL ?? '',

                                // Status klaim
                                statusVerif: statusVerif,
                                statusKlaim: statusVerif
                                    ? 'SUDAH DIVERIFIKASI'
                                    : 'BELUM DIVERIFIKASI',

                                // Label tab
                                label: `SEP. ${item.NOSEP ?? '-'}`

                            }));

                            // ROW CLAIM
                            let content = `
                            <tr
                                style="cursor:pointer;"
                                class="clickable"
                                data-href="${urlKlaim}"
                                data-claim-href="${urlKlaim}"
                                data-claim-meta="${claimMeta}"
                            >
                                <td>
                                    <h5 class="mb-0">
                                        <a href="javascript:void(0);">
                                            <b
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="bottom"
                                                title="Nomor Surat Elegibilitas Peserta"
                                            >
                                                ${SEP}
                                            </b>
                                        </a>
                                    </h5>

                                    <p class="mb-0 fs-16">
                                        RM.${item.NORM} -
                                        <b class="text-skyblue">
                                            ${item.NAMAPASIEN}
                                        </b>
                                    </p>

                                    <p class="mb-0 fs-12">
                                        ${item.NAMARUANGAN} - ${item.NAMADOKTER}
                                    </p>

                                    <p class="mb-0 fs-12">
                                        Jenis Kunjungan :
                                        &nbsp;${badgeKonsul}
                                        ${addOnKonsul ?? ''}
                                    </p>
                                </td>

                                <td class="text-end align-middle">
                                    <p class="mb-0">
                                        ${item.MASUK}
                                    </p>
                                </td>

                                <td
                                    class="text-end align-middle"
                                    style="width:1%;white-space:nowrap;"
                                >
                                    ${stt}
                                </td>

                                <td
                                    class="text-end align-middle"
                                    style="width:1%;white-space:nowrap;"
                                >
                                    ${cat}
                                </td>
                            </tr>
                        `;

                            $('#tampil-tbody').append(content);
                        });
                    }

                    // DATATABLE
                    $('#dttable').DataTable({
                        order: [
                            [1, "desc"]
                        ],

                        columnDefs: [{
                                targets: [0],
                                sortable: true
                            },
                            {
                                targets: [2],
                                sortable: false
                            },
                            {
                                targets: [3],
                                sortable: false
                            }
                        ],

                        displayLength: 10
                    });

                    // TOOLTIP
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    $('.tooltip').remove();

                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger: 'hover'
                    });

                    // TOMBOL FILTER
                    $('#tombol-tampilkan')
                        .prop('disabled', false)
                        .find('i')
                        .removeClass('fa-sync fa-spin')
                        .addClass('fa-filter');
                },
                error: function(xhr, status, error) {

                    let pesan = xhr.responseJSON ?? xhr.responseText ?? 'Gagal mengambil data. Coba lagi.';

                    iziToast.error({
                        title: 'Pesan System!',
                        message: pesan,
                        position: 'topRight'
                    });

                    $("#tampil-tbody").empty().append(`
                        <tr style="font-size:13px">
                            <td colspan="4">
                                <center>Gagal Memuat Data Klaim</center>
                            </td>
                        </tr>
                    `);

                    $('#tombol-tampilkan')
                        .prop('disabled', false)
                        .find('i')
                        .removeClass('fa-sync fa-spin')
                        .addClass('fa-filter');

                    $('#jumlah_claim').empty().append('0 Data');
                },
                complete: function() {
                }
            });
        }

        // FUNCTION INPUT SEP
        function initSepInput() {

            const PREFIX = '0151R013';
            const MAX_LENGTH = 19;
            const $input = $('#sep_px');

            function sanitize(value) {

                value = value.toUpperCase();

                // Hanya huruf dan angka
                value = value.replace(/[^A-Z0-9]/g, '');

                // Pastikan prefix selalu ada
                if (!value.startsWith(PREFIX)) {
                    value = PREFIX + value.replace(new RegExp('^' + PREFIX), '');
                }

                // Buang prefix ganda
                while (value.substring(PREFIX.length).startsWith(PREFIX)) {
                    value = PREFIX + value.substring(PREFIX.length * 2);
                }

                // Batasi panjang
                value = value.substring(0, MAX_LENGTH);

                return value;
            }

            // Nilai awal
            $input.val(PREFIX);

            // Cursor selalu minimal setelah prefix
            $input.on('focus click mouseup', function() {

                const el = this;

                setTimeout(function() {

                    if (el.selectionStart < PREFIX.length) {
                        el.setSelectionRange(PREFIX.length, el.selectionEnd);
                    }

                }, 0);

            });

            // Cegah backspace/delete prefix
            $input.on('keydown', function(e) {

                const start = this.selectionStart;
                const end = this.selectionEnd;
                const allSelected = (start === 0 && end === this.value.length);

                if (e.ctrlKey && e.key.toLowerCase() === 'a') {
                    return;
                }

                if (allSelected) {
                    return;
                }

                if (
                    (e.key === 'Backspace' && start <= PREFIX.length) ||
                    (e.key === 'Delete' && start < PREFIX.length)
                ) {
                    e.preventDefault();
                }

            });

            // Setiap perubahan
            $input.on('input', function() {

                let value = $(this).val();

                if (value === '') {
                    $(this).val(PREFIX);
                    this.setSelectionRange(PREFIX.length, PREFIX.length);
                    return;
                }

                value = sanitize(value);

                $(this).val(value);
            });

            // Handle paste
            $input.on('paste', function(e) {

                e.preventDefault();

                let pasted = (e.originalEvent.clipboardData || window.clipboardData)
                    .getData('text');

                pasted = pasted.toUpperCase().replace(/[^A-Z0-9]/g, '');

                if (pasted.startsWith(PREFIX)) {
                    pasted = pasted.substring(PREFIX.length);
                }

                $(this).val(
                    sanitize(PREFIX + pasted)
                );
            });

            // Jika seluruh isi dihapus
            $input.on('keyup', function() {

                if ($(this).val().length < PREFIX.length) {
                    $(this).val(PREFIX);
                }

            });
        }
        // FUNCTION CARI SEP
        function cariSep() {

            let sepPX = $('#sep_px').val();

            if (sepPX.length != 19) {

                iziToast.error({
                    title: 'Pesan System!',
                    message: 'Nomor SEP (Surat Eligibilitas Peserta) Tidak Valid (Harus 19 Digit)',
                    position: 'topRight'
                });

                return;
            }

            $.ajax({
                url: `/api/v3/klaim/${sepPX}/verif`,
                type: 'GET',
                dataType: 'json',

                beforeSend: function() {

                    $('#btn-cari')
                        .prop('disabled', true)
                        .find('i')
                        .removeClass('fa-search')
                        .addClass('fa-sync fa-spin');
                },

                success: function(res) {

                    iziToast.success({
                        title: 'Pesan System!',
                        message: res.message,
                        position: 'topRight'
                    });

                    const type = canSmartClaim
                        ? 'smart_claim'
                        : 'smart_claim_farmasi';

                    const url = type === 'smart_claim_farmasi'
                        ? `/v3/smartclaim/${encodeURIComponent(res.kunjungan)}/farmasi`
                        : `/v3/smartclaim/${encodeURIComponent(res.kunjungan)}`;

                    const metadata = {
                        nomor: String(res.NOMOR ?? ''),
                        norm: res.NORM ?? '',

                        nama: res.NAMAPASIEN ?? '',
                        namaLengkap: res.NAMALENGKAPPASIEN ?? '',
                        panggilan: res.PANGGILANPASIEN ?? '',

                        tglLahir: res.TGLLAHIRPASIEN ?? '',
                        umur: res.UMURPASIEN ?? '',
                        jenisKelamin: res.JKPASIEN ?? '',

                        nik: res.NIKPASIEN ?? '',
                        noHP: res.NOHPPASIEN ?? '',

                        namaAsuransi: res.NAMAASURANSI ?? '',
                        idAsuransi: res.IDASURANSI ?? '',
                        refIdAsuransi: res.REFIDASURANSI ?? '',
                        noAsuransi: res.NOASURANSI ?? '',

                        noBPJS: res.NOBPJS ?? '',
                        noSEP: res.NOSEP ?? '',
                        tglSEP: res.TGLSEP ?? '',

                        statusBPJS: res.STATUSBPJS ?? '',

                        idRuangan: res.IDRUANGAN ?? '',
                        namaRuangan: res.NAMARUANGAN ?? '',

                        namaDokter: res.NAMADOKTER ?? '',

                        tglDaftar: res.TGLDAFTAR ?? '',
                        tglMasuk: res.MASUK ?? '',
                        tglKeluar: res.KELUAR ?? '',

                        jenisKunjungan: String(res.JENIS_KUNJUNGAN ?? '').trim().toUpperCase(),
                        ruanganKonsul: res.RUANGAN_KONSUL ?? '',
                        dokterKonsul: res.DOKTER_KONSUL ?? '',

                        statusVerif: res.STATUSVERIF ?? 0,
                        tglVerif: res.TGLVERIF ?? '',
                        namaVerif: res.NAMAVERIF ?? '',

                        statusKlaim: Number(res.STATUSVERIF) === 1
                            ? 'SUDAH DIVERIFIKASI'
                            : 'BELUM DIVERIFIKASI',

                        catatan: res.CATATAN ?? 0,

                        label: `SEP. ${res.NOSEP ?? ''}`,
                        url: url
                    };

                    if (window.ClaimWorkspace) {

                        window.ClaimWorkspace.openClaim(
                            res.kunjungan,
                            type,
                            metadata
                        );

                    } else {

                        window.location.href = url;

                    }
                },

                error: function(xhr) {

                    iziToast.error({
                        title: 'Pesan System!',
                        message: xhr.responseText,
                        position: 'topRight'
                    });
                },

                complete: function() {

                    $('#btn-cari')
                        .prop('disabled', false)
                        .find('i')
                        .removeClass('fa-sync fa-spin')
                        .addClass('fa-search');
                }
            });
        }

        // FUNCTION LIHAT CATATAN
        function lihatCatatan(kunjungan) {

            $("#tampil-catatan").empty().append(`
            <tr style="font-size:13px">
                <td colspan="3">
                    <center>
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </center>
                </td>
            </tr>
        `);

            $('#show-id-catatan').text(kunjungan);

            $('#catatan' + kunjungan)
                .find('i')
                .removeClass('ti ti-file-text fs-40')
                .addClass('fas fa-sync fa-spin fs-30');

            $.ajax({
                url: "/api/klaim/" + kunjungan + "/catatan",
                type: 'GET',
                dataType: 'json',

                success: function(res) {

                    if (res.show.length != 0) {

                        $('#tampil-catatan').empty();

                        let content = '';

                        res.show.forEach(item => {

                            content += `
                            <tr>
                                <td class="custom-column">
                        `;

                            if (!res.klaim || res.klaim.verif == 0) {

                                if (item.solved == 0) {

                                    content += `
                                    <button
                                        class="btn btn-link-success"
                                        onclick="selesaiCatatan('${item.id}')"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Tombol Apabila Catatan telah diselesaikan"
                                    >
                                        Selesai
                                        <i class="ti ti-thumb-up ms-1"></i>
                                    </button>
                                `;

                                } else {

                                    content += `
                                    <button
                                        class="btn btn-link-danger"
                                        onclick="batalSelesaiCatatan('${item.id}')"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Tombol Apabila Batal Menyelesaikan Catatan"
                                    >
                                        Batalkan
                                        <i class="ti ti-thumb-down ms-1"></i>
                                    </button>
                                `;
                                }

                            } else {

                                content += `
                                <button
                                    class="btn btn-link-secondary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Berkas telah diverifikasi dan catatan telah diselesaikan"
                                >
                                    <i class="ti ti-thumb-up fs-40"></i>
                                </button>
                            `;
                            }

                            content += `
                                </td>

                                <td class="custom-column">
                                    Ditambahkan Pada
                                    <span class="badge text-bg-info p-1">
                                        ${item.updated_at}
                                    </span>
                                    <br>
                                    Oleh
                                    <b>${item.NAMAPEGAWAI}</b>
                                </td>

                                <td class="custom-column">
                        `;

                            if (item.solved == 0) {

                                content += `
                                <span class="badge text-bg-danger p-1">
                                    Belum Terselesaikan
                                </span>
                                <br>
                            `;

                            } else {

                                content += `
                                <span class="badge text-bg-success p-1">
                                    Terselesaikan
                                </span>
                                <br>
                            `;
                            }

                            content += `
                                    ${item.deskripsi}
                                </td>
                            </tr>
                        `;
                        });

                        $('#tampil-catatan').append(content);

                        $('#btn-refresh-catatan').empty().append(`
                        <button
                            type="button"
                            class="btn btn-warning"
                            onclick="lihatCatatan('${kunjungan}')"
                        >
                            Refresh
                        </button>
                    `);

                        // TAMPILKAN MODAL
                        $('#catatan').modal('show');

                        $('#catatan' + kunjungan)
                            .find('i')
                            .removeClass('fas fa-sync fa-spin fs-30')
                            .addClass('ti ti-file-text fs-40');

                        // TOOLTIP
                        $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                        $('.tooltip').remove();

                        $('[data-bs-toggle="tooltip"]').tooltip({
                            trigger: 'hover'
                        });

                    } else {

                        iziToast.error({
                            title: 'Maaf!',
                            message: 'Data Catatan tidak ditemukan / belum diisi',
                            position: 'topRight'
                        });

                        $('#catatan' + kunjungan)
                            .find('i')
                            .removeClass('fas fa-sync fa-spin fs-30')
                            .addClass('ti ti-file-text fs-40');
                    }
                },

                error: function(xhr) {

                    iziToast.error({
                        title: 'Pesan System!',
                        message: xhr.responseText,
                        position: 'topRight'
                    });

                    $('#catatan' + kunjungan)
                        .find('i')
                        .removeClass('fas fa-sync fa-spin fs-30')
                        .addClass('ti ti-file-text fs-40');
                }
            });
        }

        // FUNCTION SELESAI CATATAN
        function selesaiCatatan(id) {

            $.ajax({
                url: "/api/klaim/catatan/" + id + "/solved",
                type: 'GET',
                dataType: 'json',

                success: function(res) {
                    lihatCatatan(res.nomor);
                }
            });
        }

        // FUNCTION BATAL SELESAI CATATAN
        function batalSelesaiCatatan(id) {

            $.ajax({
                url: "/api/klaim/catatan/" + id + "/unsolved",
                type: 'GET',
                dataType: 'json',

                success: function(res) {
                    lihatCatatan(res.nomor);
                }
            });
        }

        // EXPORT FUNCTION UNTUK INLINE ONCLICK
        window.filter = filter;
        window.cariSep = cariSep;
        window.lihatCatatan = lihatCatatan;
        window.selesaiCatatan = selesaiCatatan;
        window.batalSelesaiCatatan = batalSelesaiCatatan;
    </script>
@endscript
