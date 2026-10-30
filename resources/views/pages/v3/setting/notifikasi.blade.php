
@section('title', 'Realtime')
@section('page-title', 'Notifikasi Sistem')

<div id="notifikasi-page">

    {{-- ==========================================================
        HEADER NOTIFIKASI
    ========================================================== --}}
    <section>
        <div class="row gy-4">
            <div class="col-md-12">

                <div class="card mb-3">

                    <div class="card-body pb-0">

                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">

                            <div>
                                <h5 class="card-title mb-1">

                                    <i class="ri-notification-4-line me-1"></i>
                                    Notifikasi Sistem

                                    <span class="badge badge-sm bg-danger-subtle text-danger me-1"
                                        id="total-notifikasi-pending">
                                        Pending : {{ number_format($totalNotifikasiPending) }}
                                    </span>

                                    <span class="badge badge-sm bg-success-subtle text-success"
                                        id="total-notifikasi-selesai">
                                        Selesai : {{ number_format($totalNotifikasiSelesai) }}
                                    </span>

                                </h5>

                                <p class="card-text mb-0">
                                    Daftar Notifikasi Sistem terkait Klaim,
                                    Monitoring, dan lainnya. Status terdiri dari
                                    <span class="badge badge-sm bg-danger">Pending</span>
                                    dan
                                    <span class="badge badge-sm bg-success">Selesai</span>.
                                </p>
                            </div>

                            <div class="d-flex gap-2">

                                <select
                                    class="form-select form-select-sm"
                                    id="filter-notifikasi-status"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Selesai">Selesai</option>
                                </select>

                            </div>

                        </div>

                    </div>

                    {{-- ==================================================
                        TABEL NOTIFIKASI SSR
                    =================================================== --}}
                    <div class="card-body pt-0">

                        <div class="table-responsive">

                            <table
                                class="table table-borderless table-hover align-middle"
                                id="table-notifikasi"
                            >

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Unit Tujuan</th>
                                        <th>Catatan</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody id="table-notifikasi-body">

                                    @foreach ($notifikasi as $index => $item)

                                        <tr>

                                            {{-- DETAIL --}}
                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="showDetail({{ $item->id }})"
                                                    id="btn-detail-{{ $item->id }}"
                                                    title="Lihat Detail"
                                                    data-bs-toggle="tooltip"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>

                                            {{-- ID --}}
                                            <td class="text-center">
                                                {{ $item->id }}
                                            </td>

                                            {{-- UNIT TUJUAN --}}
                                            <td>
                                                @forelse ($item->unitNames as $namaUnit)

                                                    <span class="badge badge-sm text-bg-light fw-light border me-1">
                                                        {{ $namaUnit }}
                                                    </span>

                                                @empty

                                                    <span class="text-muted">Semua Unit</span>

                                                @endforelse
                                            </td>

                                            {{-- CATATAN --}}
                                            <td>
                                                <small class="text-muted">
                                                    No. Kunjungan :
                                                    {{ $item->nomor ?? '-' }}
                                                </small>

                                                <div class="fw-medium">
                                                    {{ strip_tags($item->deskripsi ?? '-') }}
                                                </div>
                                            </td>

                                            {{-- WAKTU --}}
                                            <td
                                                class="text-nowrap"
                                                data-order="{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->timestamp : 0 }}"
                                            >
                                                <small class="text-muted">
                                                    {{ $item->updated_at
                                                        ? \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y H:i:s')
                                                        : '-' }}
                                                </small>
                                            </td>

                                            {{-- STATUS --}}
                                            <td
                                                data-search="{{ $item->solved ? 'Selesai' : 'Pending' }}"
                                                data-order="{{ $item->solved ? 1 : 0 }}"
                                            >
                                                @if ($item->solved)

                                                    <span class="badge badge-sm bg-success">
                                                        Selesai
                                                    </span>

                                                @else

                                                    <span class="badge badge-sm bg-danger">
                                                        Pending
                                                    </span>

                                                @endif
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================================
        MODAL DETAIL NOTIFIKASI
    ========================================================== --}}
    <div
        class="modal fade"
        id="modalDetailNotifikasi"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="ri-notification-line me-2"></i>
                        Detail Notifikasi
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <div class="modal-body">

                    <div id="detailNotifikasiLoading" class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </div>

                    <div id="detailNotifikasiContent" class="d-none">

                        <center>
                            <label class="form-label fw-bold text-muted mb-2">
                                <i class="ri-user-follow-line me-1"></i>
                                Identitas Pasien
                            </label>
                        </center>

                        <div class="card shadow-lg mb-3">
                            <div class="card-body" id="detail-pasien">
                                <i class="fas fa-sync fa-spin"></i>
                            </div>
                        </div>

                        <div class="mb-3">

                            <label class="form-label text-muted d-flex align-items-center mb-1">
                                <i class="ri-alert-line text-danger fs-20 me-1"></i>
                                Catatan
                            </label>

                            <div
                                id="detailDeskripsi"
                                class="border border-danger border-dashed rounded p-3"
                            ></div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Unit Tujuan
                                    </label>
                                    <div id="detailUnit"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted mb-1">
                                    Waktu Catatan Ditambahkan
                                </label>
                                <div id="detailWaktu"></div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-between">

                    <div class="d-flex gap-2 flex-wrap">

                        <a
                            href="javascript:void(0);"
                            class="btn btn-sm btn-subtle-primary"
                            id="btn-lihat-kunjungan"
                        >
                            <i class="ri-git-repository-line me-1"></i>
                            Lihat Kunjungan Pasien
                        </a>

                        @can('smart_claim')
                            <a
                                href="javascript:void(0);"
                                class="btn btn-sm btn-subtle-danger"
                                id="btn-lihat-klaim"
                                target="_blank"
                            >
                                <i class="ri-git-repository-line me-1"></i>
                                Lihat Berkas Klaim Pasien
                            </a>
                        @endcan

                        @can('smart_claim_farmasi')
                            <a
                                href="javascript:void(0);"
                                class="btn btn-sm btn-subtle-warning"
                                id="btn-lihat-klaim-farmasi"
                                target="_blank"
                            >
                                <i class="ri-git-repository-line me-1"></i>
                                Lihat Berkas Klaim Farmasi Pasien
                            </a>
                        @endcan

                    </div>

                    <button
                        type="button"
                        class="btn btn-subtle-secondary"
                        data-bs-dismiss="modal"
                    >
                        <i class="fas fa-times me-1"></i>
                        Tutup
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================================
    JAVASCRIPT MODAL DETAIL
    Daftar notifikasi tidak lagi menggunakan AJAX.
========================================================== --}}
<script>
    window.NotifikasiRoles = @js($roleNames);

    window.showDetail = function (id) {

        const modalElement = document.getElementById('modalDetailNotifikasi');

        if (!modalElement) {
            return;
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        const $btnShowDetail = $(`#btn-detail-${id}`);
        const $btnLihatKunjungan = $('#btn-lihat-kunjungan');
        const $btnLihatKlaim = $('#btn-lihat-klaim');
        const $btnLihatKlaimFarmasi = $('#btn-lihat-klaim-farmasi');

        $('#detailNotifikasiLoading').removeClass('d-none');
        $('#detailNotifikasiContent').addClass('d-none');

        $('#detail-pasien').empty();
        $('#detailUnit').empty();
        $('#detailDeskripsi').empty();
        $('#detailWaktu').empty();

        modal.show();

        $.ajax({
            url: `/api/v2/notifikasi/data/${encodeURIComponent(id)}`,
            type: 'GET',
            dataType: 'json',

            beforeSend: function () {

                $btnShowDetail
                    .prop('disabled', true)
                    .find('i')
                    .removeClass('fa-eye')
                    .addClass('fa-sync fa-spin');

                $btnLihatKunjungan
                    .attr('href', 'javascript:void(0);');

                $btnLihatKlaim
                    .attr('href', 'javascript:void(0);');

                $btnLihatKlaimFarmasi
                    .attr('href', 'javascript:void(0);');

            },

            success: function (res) {

                if (!res.status || !res.data) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Data notifikasi tidak ditemukan.',
                        position: 'topRight'
                    });

                    modal.hide();
                    return;
                }

                const item = res.data;
                const pasien = item.pasien || {};

                const nomor = item.nomor || '';

                const escapeHtml = function (value) {
                    return $('<div>')
                        .text(value ?? '-')
                        .html();
                };

                // ======================================================
                // BUKA KUNJUNGAN PASIEN MELALUI EMR WORKSPACE
                // Mekanisme sama dengan notifikasi di header.
                // ======================================================

                const patient = {
                    nomor: nomor,
                    norm: pasien.NORM || item.norm || '',
                    nama: pasien.NAMAPASIEN || item.nama || '',
                    url: `/v3/emr/${encodeURIComponent(nomor)}`
                };

                // Hindari event click terpasang berulang kali.
                $btnLihatKunjungan
                    .off('click.emr')
                    .on('click.emr', function (e) {
                        e.preventDefault();

                        if (!patient.nomor) {
                            iziToast.error({
                                title: 'Error',
                                message: 'Nomor kunjungan pasien tidak ditemukan.',
                                position: 'topRight'
                            });

                            return;
                        }

                        // Tutup modal detail.
                        modal.hide();

                        // Periksa halaman saat ini.
                        const currentPath = window.location.pathname.replace(/\/+$/, '');

                        const isMedicalRecordPage = currentPath === '/v3/emr';

                        // Jika bukan di halaman Medical Record.
                        if (!isMedicalRecordPage) {
                            try {
                                sessionStorage.setItem(
                                    'sirmed.pendingEmrPatient',
                                    JSON.stringify(patient)
                                );
                            } catch (error) {
                                console.error('Gagal menyimpan kunjungan:', error);

                                iziToast.error({
                                    title: 'Error',
                                    message: 'Gagal mempersiapkan data kunjungan pasien.',
                                    position: 'topRight'
                                });

                                return;
                            }

                            if (
                                window.Livewire &&
                                typeof window.Livewire.navigate === 'function'
                            ) {
                                window.Livewire.navigate('/v3/emr');
                            } else {
                                console.error('Livewire.navigate tidak tersedia.');

                                iziToast.error({
                                    title: 'Error',
                                    message: 'Navigasi Medical Record tidak tersedia.',
                                    position: 'topRight'
                                });
                            }

                            return;
                        }

                        // Jika sudah di halaman Medical Record,
                        // langsung buka iframe kunjungan pasien.
                        if (
                            window.EMRWorkspace &&
                            typeof window.EMRWorkspace.openPatient === 'function'
                        ) {
                            window.EMRWorkspace.openPatient(patient);
                        } else {
                            console.error(
                                'EMRWorkspace belum tersedia pada halaman Medical Record.'
                            );

                            iziToast.error({
                                title: 'Error',
                                message: 'Workspace Medical Record belum tersedia.',
                                position: 'topRight'
                            });
                        }
                    });

                $btnLihatKlaim.attr(
                    'href',
                    `/v3/klaim/${encodeURIComponent(nomor)}`
                );

                $btnLihatKlaimFarmasi.attr(
                    'href',
                    `/v3/klaim/${encodeURIComponent(nomor)}/farmasi`
                );

                // Identitas pasien.
                const html = `
                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Nomor Kunjungan
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(nomor)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Nama Pasien
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(pasien.NAMAPASIEN)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Alamat Pasien
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(pasien.ALAMATPASIEN)}
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tgl. Pendaftaran
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(pasien.TGLPENDAFTARAN)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Ruangan Dirawat (DPJP)
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(pasien.NAMARUANGAN)}
                                    (${escapeHtml(pasien.NAMADOKTER)})
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tgl. Lahir (Umur)
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(pasien.TGLLAHIRPASIEN)}
                                    (${escapeHtml(pasien.UMURPASIEN)})
                                </div>
                            </div>

                        </div>

                    </div>
                `;

                $('#detail-pasien').html(html);

                // Unit tujuan.
                let unit = item.unit || [];

                if (typeof unit === 'string') {
                    try {
                        unit = JSON.parse(unit);
                    } catch (error) {
                        unit = [];
                    }
                }

                const roles = window.NotifikasiRoles || {};

                if (Array.isArray(unit) && unit.length) {

                    const unitHtml = unit.map(function (id) {

                        const nama = roles[String(id)] || null;

                        if (!nama) {
                            return '';
                        }

                        return `
                            <span class="badge badge-sm text-bg-light fw-light border me-1">
                                ${escapeHtml(nama)}
                            </span>
                        `;

                    }).join('');

                    $('#detailUnit').html(
                        unitHtml || '<span class="text-muted">Semua Unit</span>'
                    );

                } else {

                    $('#detailUnit').html(
                        '<span class="text-muted">Semua Unit</span>'
                    );

                }

                $('#detailDeskripsi').text(
                    item.deskripsi || '-'
                );

                const waktu = item.updated_at
                    ? moment(item.updated_at)
                        .locale('id')
                        .format('D MMMM YYYY, HH:mm [WIB]')
                    : '-';

                $('#detailWaktu').text(waktu);

                $('#detailNotifikasiLoading').addClass('d-none');
                $('#detailNotifikasiContent').removeClass('d-none');

            },

            error: function (xhr) {

                $('#detailNotifikasiLoading').addClass('d-none');

                iziToast.error({
                    title: 'Error',
                    message: xhr.status === 403
                        ? 'Anda tidak memiliki akses ke notifikasi ini.'
                        : 'Gagal mengambil detail notifikasi.',
                    position: 'topRight'
                });

                modal.hide();

            },

            complete: function () {

                $btnShowDetail
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-eye');

            }

        });

    };
</script>
<script>

/* =========================================================
   DATATABLES NOTIFIKASI
   SSR DATA + CLIENT SIDE FILTER
========================================================= */

(function ($) {
    'use strict';

    function initNotifikasiDataTable() {
        const tableElement = document.getElementById('table-notifikasi');
        const filterElement = document.getElementById('filter-notifikasi-status');

        if (!tableElement) return;

        // Pastikan jQuery dan DataTables sudah tersedia.
        if (!$ || !$.fn || !$.fn.dataTable) {
            console.error(
                '[Notifikasi] DataTables belum tersedia. Periksa urutan pemuatan datatables.js.'
            );
            return;
        }

        let table;

        // Gunakan instance yang sudah ada jika tabel telah diinisialisasi.
        if ($.fn.dataTable.isDataTable(tableElement)) {
            table = $(tableElement).DataTable();
        } else {
            table = $(tableElement).DataTable({
                order: [[4, 'desc']],

                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [0, 1],
                        className: 'text-center'
                    }
                ]
            });
        }

        // Simpan instance agar bisa diperiksa saat Livewire navigasi.
        window.notifikasiDataTable = table;

        // Filter status.
        if (filterElement) {
            $(filterElement)
                .off('change.notifikasi')
                .on('change.notifikasi', function () {
                    const status = this.value;

                    if (!status) {
                        table.column(5).search('').draw();
                        return;
                    }

                    const keyword = '^' +
                        $.fn.dataTable.util.escapeRegex(status) +
                        '$';

                    table.column(5).search(keyword, true, false).draw();
                });
        }

        console.info('[Notifikasi] DataTables berhasil diinisialisasi.');
    }

    // Inisialisasi saat script dijalankan.
    initNotifikasiDataTable();

    // Inisialisasi ulang setelah navigasi Livewire.
    if (!window.__notifikasiDataTableNavigationBound) {
        window.__notifikasiDataTableNavigationBound = true;

        document.addEventListener('livewire:navigated', function () {
            initNotifikasiDataTable();
        });
    }

})(window.jQuery);

</script>
