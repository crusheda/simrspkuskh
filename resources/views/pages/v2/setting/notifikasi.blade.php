@extends('layouts.v2.index')

@section('title','SIRMED v2 - Notifikasi Sistem')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 bg-primary-subtle rounded-3">
            <li class="breadcrumb-item">
                <a class="link-primary" href="{{ route('v2.dashboard') }}">
                    <i class="fi fi-rr-home"></i>
                </a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Notifikasi Sistem
            </li>
        </ol>

        <ol class="breadcrumb mb-0"></ol>
    </nav>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-3">

                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-notification-4-line me-1"></i> Notifikasi Sistem <span class="badge badge-sm bg-danger-subtle text-danger me-1" id="total-notifikasi-pending">Pending : <i class="ri-restart-line ri-spin ms-1"></i></span> <span class="badge badge-sm bg-success-subtle text-success" id="total-notifikasi-selesai">Selesai : <i class="ri-restart-line ri-spin ms-1"></i></span>
                            </h5>

                            <p class="card-text mb-0">
                                Daftar Notifikasi Sistem terkait Klaim, Monitoring, dan lainnya. Status terdiri dari <span class="badge badge-sm bg-danger">Pending</span> dan <span class="badge badge-sm bg-success">Selesai</span>.
                            </p>
                        </div>

                        <button type="button"
                            class="btn btn-sm btn-outline-warning"
                            onclick="getData()"
                            id="btn-refresh-notifikasi">

                            <i class="fas fa-sync-alt me-1"></i>
                            Segarkan
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle"
                            id="table-notifikasi">

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
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary me-1"></div>
                                        Memuat notifikasi...
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>


{{-- ==========================================================
    MODAL DETAIL
========================================================== --}}
<div class="modal fade"
    id="modalDetailNotifikasi"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Detail Notifikasi
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div id="detailNotifikasiLoading"
                    class="text-center py-4">

                    <div class="spinner-border text-primary"></div>

                </div>

                <div id="detailNotifikasiContent" class="d-none">

                    <center>
                        <label class="form-label fw-bold text-muted mb-2">
                            <i class="ri-user-follow-line me-1"></i> Identitas Pasien
                        </label>
                    </center>
                    <div class="card shadow-lg mb-3">
                        <div class="card-body" id="detail-pasien"><i class="fas fa-sync fa-spin"></i></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">
                            Catatan
                        </label>
                        <div id="detailDeskripsi"
                            class="border rounded p-3">
                        </div>
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
                <a href="javascript:void(0);" class="btn btn-sm btn-subtle-primary" id="btn-lihat-kunjungan" target="_blank">
                    <i class="ri-git-repository-line me-1"></i> Lihat Kunjungan Pasien
                </a>
                <button type="button"
                    class="btn btn-subtle-secondary"
                    data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Tutup
                </button>

            </div>

        </div>

    </div>
</div>


<script>

    let tableNotifikasi = null;
    let daftarRoles = [];

    $(document).ready(function () {

        // getData();
        getRoles();

    });


    /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

    function getData() {

        if (tableNotifikasi) {
            tableNotifikasi.destroy();
        }

        const $tbody = $('#table-notifikasi-body');
        const $btnRefresh = $('#btn-refresh-notifikasi');

        $.ajax({
            url: "/api/v2/notifikasi/data",
            type: 'GET',
            dataType: 'json',

            beforeSend: function () {
                $btnRefresh.prop('disabled', true).find('i').addClass('fa-spin');
                $tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary me-1"></div>
                            Memuat notifikasi...
                        </td>
                    </tr>
                `);
            },
            success: function (res) {
                $tbody.empty();
                if (!res.status || !Array.isArray(res.data) || !res.data.length) {
                    $tbody.html(`
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-bell-slash fs-3 d-block mb-2"></i>
                                Tidak ada notifikasi.
                            </td>
                        </tr>
                    `);
                    return;
                }

                let totalNotifikasiPending = 0;
                let totalNotifikasiSelesai = 0;

                res.data.forEach(function (item, index) {

                    const unit = getNamaUnit(item.unit);

                    if (!item.solved) {
                        totalNotifikasiPending++;
                    } else {
                        totalNotifikasiSelesai++;
                    }

                    $tbody.append(`
                        <tr>

                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="showDetail(${item.id})"
                                    id="btn-detail-${item.id}"
                                    title="Lihat Detail"
                                    data-bs-toggle="tooltip">

                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>

                            <td>
                                ${item.id}
                            </td>

                            <td>
                                ${
                                    unit.length
                                        ? unit.map(function (nama) {
                                            return `
                                                <span class="badge badge-sm text-bg-light fw-light border me-1">
                                                    ${escapeHtml(nama)}
                                                </span>
                                            `;
                                        }).join('')
                                        : '<span class="text-muted">-</span>'
                                }
                            </td>

                            <td>
                                <small class="text-muted">
                                    No. Kunjungan : ${escapeHtml(item.nomor || '-')}
                                </small>
                                <div class="fw-medium">
                                    ${escapeHtml(item.deskripsi || '-')}
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <small class="text-muted">
                                    ${formatTanggalTimestamp(item.updated_at)}
                                </small>
                            </td>

                            <td>
                                ${
                                    item.solved
                                        ? '<span class="badge badge-sm bg-success">Selesai</span>'
                                        : '<span class="badge badge-sm bg-danger">Pending</span>'
                                }
                            </td>

                        </tr>
                    `);

                });

                $('#total-notifikasi-pending').text('Pending : '+totalNotifikasiPending);
                $('#total-notifikasi-selesai').text('Selesai : '+totalNotifikasiSelesai);

                tableNotifikasi = $('#table-notifikasi').DataTable({
                    processing: true,
                    pageLength: 20,
                    lengthMenu: [
                        [10, 20, 50, 100, 300, 500],
                        [10, 20, 50, 100, 300, 500]
                    ],
                    ordering: true,
                    searching: true,
                    info: true,
                    paging: true,
                    order: [[5, 'asc'],[4, 'desc']],

                    columnDefs: [
                        {
                            targets: [0, 1, 3],
                            orderable: false
                        }
                    ],
                });

                initTooltip();

            },
            error: function (xhr) {
                $tbody.html(`
                    <tr>
                        <td colspan="6"
                            class="text-center text-danger py-4">

                            <i class="fas fa-exclamation-circle me-1"></i>
                            Gagal mengambil data notifikasi.

                        </td>
                    </tr>
                `);
                console.error(
                    'Gagal mengambil notifikasi:',
                    xhr.status,
                    xhr.responseText
                );
            },
            complete: function () {
                $btnRefresh.prop('disabled', false).find('i').removeClass('fa-spin');
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW DETAIL
    |--------------------------------------------------------------------------
    */

    function showDetail(id) {

        const modalElement = document.getElementById('modalDetailNotifikasi');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const $btnShowDetail = $(`#btn-detail-${id}`);
        const $btnLihatKunjungan = $('#btn-lihat-kunjungan');

        $('#detailNotifikasiLoading').removeClass('d-none');
        $('#detailNotifikasiContent').addClass('d-none');

        $('#detailNomor').html('');
        $('#detailUnit').html('');
        $('#detailDeskripsi').html('');
        $('#detailWaktu').html('');

        modal.show();

        $.ajax({
            url: `/api/v2/notifikasi/data/${id}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $btnShowDetail.prop('disabled', true).find('i').removeClass('fa-eye').addClass('fa-sync fa-spin');
                $btnLihatKunjungan.prop('disabled',true);
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

                const unit = getNamaUnit(item.unit);

                $btnLihatKunjungan.prop('disabled',false).attr('href', `/v2/emr/${item.nomor}` || 'javascript:void(0);');

                let html = '';
                html += `<div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Nomor Kunjungan
                                    </label>
                                    <div class="fw-semibold">${item.nomor}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Nama Pasien
                                    </label>
                                    <div class="fw-semibold text-truncate">${item.pasien.NAMAPASIEN}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Alamat Pasien
                                    </label>
                                    <div class="fw-semibold text-truncate">${item.pasien.ALAMATPASIEN}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Tgl. Pendaftaran
                                    </label>
                                    <div class="fw-semibold">${item.pasien.TGLPENDAFTARAN}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        Ruangan Dirawat (DPJP)
                                    </label>
                                    <div class="fw-semibold text-truncate">${item.pasien.NAMARUANGAN} (${item.pasien.NAMADOKTER})</div>
                                </div>
                                <div class="">
                                    <label class="form-label text-muted mb-1">
                                        Tgl. Lahir (Umur)
                                    </label>
                                    <div class="fw-semibold text-truncate">${item.pasien.TGLLAHIRPASIEN} (${item.pasien.UMURPASIEN})</div>
                                </div>
                            </div>
                        </div>`;
                $('#detail-pasien').empty().append(html);

                // $('#detailNomor').text(
                //     item.nomor || '-'
                // );

                if (unit.length) {

                    $('#detailUnit').html(
                        unit.map(function (nama) {
                            return `
                                <span class="badge badge-sm text-bg-light fw-light border me-1">
                                    ${escapeHtml(nama)}
                                </span>
                            `;
                        }).join('')
                    );

                } else {

                    $('#detailUnit').html(
                        '<span class="text-muted">Semua Unit</span>'
                    );

                }

                $('#detailDeskripsi').html(
                    item.deskripsi || '-'
                );

                $('#detailWaktu').text(
                    formatTanggal(item.updated_at)
                );

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
                $btnShowDetail.prop('disabled', false).find('i').removeClass('fa-sync fa-spin').addClass('fa-eye');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET ROLES
    |--------------------------------------------------------------------------
    */
    function getRoles() {
        $.ajax({
            url: '/api/v2/roles/data',
            type: 'GET',
            dataType: 'json',
            success: function (res) {

                if (!res || !Array.isArray(res.show)) {
                    console.error('Data roles tidak ditemukan.');
                    daftarRoles = [];
                    return;
                }

                daftarRoles = res.show.map(function (role) {
                    return {
                        id: String(role.id),
                        name: role.name
                    };
                });

                getData();
            },
            error: function (xhr) {
                console.error(
                    'Gagal mengambil data roles:',
                    xhr.status,
                    xhr.responseText
                );

                daftarRoles = [];
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET NAMA UNIT
    |--------------------------------------------------------------------------
    */
    function getNamaUnit(unit) {

        if (!unit) {
            return [];
        }

        if (typeof unit === 'string') {
            try {
                unit = JSON.parse(unit);
            } catch (e) {
                return [];
            }
        }

        if (!Array.isArray(unit)) {
            return [];
        }

        return unit
            .map(function (id) {

                const role = daftarRoles.find(function (role) {
                    return Number(role.id) === Number(id);
                });

                return role ? role.name : null;

            })
            .filter(Boolean);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT TANGGAL
    |--------------------------------------------------------------------------
    */

    function formatTanggal(dateString) {

        if (!dateString) {
            return '-';
        }

        return moment(dateString)
            .locale('id')
            .format('D MMMM YYYY, HH:mm [WIB]');

    }

    function formatTanggalTimestamp(dateString) {
        if (!dateString) {
            return '-';
        }

        return moment(dateString).format('YYYY-MM-DD HH:mm:ss');
    }

    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(text) {

        return $('<div>')
            .text(text || '')
            .html();

    }


    /*
    |--------------------------------------------------------------------------
    | TOOLTIP
    |--------------------------------------------------------------------------
    */

    function initTooltip() {

        document
            .querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach(function (element) {

                bootstrap.Tooltip.getOrCreateInstance(element);

            });

    }
</script>

@endsection
