@section('title', 'Realtime Notification')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
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
                                    <option value="Pending" selected>Pending</option>
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
                            >
                                <i class="ri-git-repository-line me-1"></i>
                                Lihat Berkas Klaim Farmasi Pasien
                            </a>
                        @endcan
                        
                        {{-- Selesaikan Notifikasi --}}
                        <button
                            type="button"
                            class="btn btn-sm btn-subtle-success d-none"
                            id="btn-selesaikan-notifikasi"
                        >
                            <i class="ri-check-double-line me-1"></i>
                            Terselesaikan?
                        </button>

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

    {{-- ==========================================================
        MODAL SELESAIKAN NOTIFIKASI
    ========================================================== --}}
    <div
        class="modal fade"
        id="modalSelesaikanNotifikasi"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-check-double-line text-success me-2"></i>
                        Selesaikan Notifikasi
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-success mb-3">
                        <i class="ri-information-line me-1"></i>
                        Notifikasi ini akan ditandai sebagai selesai.
                    </div>

                    <label for="deskripsi_solved_notifikasi" class="form-label">
                        Catatan Penyelesaian
                        <small class="text-muted">(Opsional)</small>
                    </label>

                    <textarea
                        id="deskripsi_solved_notifikasi"
                        class="form-control"
                        rows="4"
                        placeholder="Tuliskan keterangan penyelesaian catatan jika diperlukan..."
                    ></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-subtle-secondary"
                        id="btn-batal-selesaikan-notifikasi">
                        <i class="ri-close-line me-1"></i>
                        Batal
                    </button>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btn-submit-solved-notifikasi"
                    >
                        <i class="ri-check-double-line me-1"></i>
                        Selesaikan Catatan
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
                    .off('click.emr')
                    .attr('href', 'javascript:void(0);');

                $btnLihatKlaim
                    .off('click.smartclaim')
                    .attr('href', 'javascript:void(0);');

                $btnLihatKlaimFarmasi
                    .off('click.smartclaim')
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

                // Set ID notifikasi yang sedang dibuka
                window.NotifikasiSolved?.setId(item.id);

                // Tampilkan tombol hanya jika masih pending
                if (parseInt(item.solved || 0) === 0) {
                    $('#btn-selesaikan-notifikasi').removeClass('d-none');
                } else {
                    $('#btn-selesaikan-notifikasi').addClass('d-none');
                }

                const escapeHtml = function (value) {
                    return $('<div>')
                        .text(value ?? '-')
                        .html();
                };

                // ======================================================
                // BUKA KUNJUNGAN PASIEN MELALUI EMR WORKSPACE
                // Mekanisme sama dengan notifikasi di header.
                // ======================================================
                const getPatientValue = function (...values) {
                    for (const value of values) {
                        if (
                            value !== undefined &&
                            value !== null &&
                            String(value).trim() !== ''
                        ) {
                            return value;
                        }
                    }

                    return '';
                };

                const patient = {
                    nomor: nomor,

                    // Identitas utama pasien
                    norm: getPatientValue(
                        pasien.NORM,
                        pasien.norm,
                        item.norm
                    ),

                    nama: getPatientValue(
                        pasien.NAMAPASIEN,
                        pasien.namaPasien,
                        pasien.nama,
                        item.nama
                    ),

                    namaLengkap: getPatientValue(
                        pasien.NAMALENGKAPPASIEN,
                        pasien.NAMAPASIEN,
                        pasien.namaLengkap,
                        pasien.nama,
                        item.nama
                    ),

                    alamat: getPatientValue(
                        pasien.ALAMATPASIEN,
                        pasien.alamatPasien,
                        pasien.alamat
                    ),

                    tglLahir: getPatientValue(
                        pasien.TGLLAHIRPASIEN,
                        pasien.TGLLAHIR,
                        pasien.tglLahir
                    ),

                    umur: getPatientValue(
                        pasien.UMURPASIEN,
                        pasien.UMUR,
                        pasien.umur
                    ),

                    jenisKelamin: getPatientValue(
                        pasien.JKPASIEN,
                        pasien.JENISKELAMIN,
                        pasien.jenisKelamin
                    ),

                    nik: getPatientValue(
                        pasien.NIKPASIEN,
                        pasien.NIK,
                        pasien.nik
                    ),

                    noHP: getPatientValue(
                        pasien.NOHPPASIEN,
                        pasien.NOHP,
                        pasien.noHP
                    ),

                    // Informasi ruangan dan DPJP
                    namaRuangan: getPatientValue(
                        pasien.NAMARUANGAN,
                        pasien.namaRuangan
                    ),

                    namaDokter: getPatientValue(
                        pasien.NAMADOKTER,
                        pasien.namaDokter
                    ),

                    idRuangan: getPatientValue(
                        pasien.IDRUANGAN,
                        pasien.idRuangan
                    ),

                    // Informasi asuransi
                    namaAsuransi: getPatientValue(
                        pasien.NAMAASURANSI,
                        pasien.namaAsuransi
                    ),

                    refIdAsuransi: getPatientValue(
                        pasien.REFIDASURANSI,
                        pasien.refIdAsuransi
                    ),

                    idAsuransi: getPatientValue(
                        pasien.IDASURANSI,
                        pasien.idAsuransi
                    ),

                    noAsuransi: getPatientValue(
                        pasien.NOASURANSI,
                        pasien.NOBPJS,
                        pasien.noAsuransi
                    ),

                    noBPJS: getPatientValue(
                        pasien.NOBPJS,
                        pasien.NOASURANSI,
                        pasien.noBPJS
                    ),

                    // Data SEP dan jenis kunjungan jika tersedia dari API
                    noSEP: getPatientValue(
                        pasien.NOSEP,
                        pasien.noSEP,
                        item.NOSEP,
                        item.noSEP
                    ),

                    jenisKunjungan: getPatientValue(
                        pasien.JENIS_KUNJUNGAN,
                        pasien.jenisKunjungan,
                        item.JENIS_KUNJUNGAN,
                        item.jenisKunjungan
                    ),

                    // Waktu kunjungan
                    tglDaftar: getPatientValue(
                        pasien.TGLPENDAFTARAN,
                        pasien.TGLDAFTAR,
                        pasien.tglDaftar
                    ),

                    tglMasuk: getPatientValue(
                        pasien.TGLMASUK,
                        pasien.tglMasuk
                    ),

                    tglKeluar: getPatientValue(
                        pasien.TGLKELUAR,
                        pasien.tglKeluar
                    ),

                    statusKunjungan: getPatientValue(
                        pasien.STATUSKUNJUNGAN,
                        pasien.statusKunjungan
                    ),

                    // Status verifikasi klaim, bukan status notifikasi.
                    statusVerif: getPatientValue(
                        item.STATUSVERIF,
                        item.statusVerif,
                        pasien.STATUSVERIF,
                        pasien.statusVerif
                    ),

                    statusKlaim: getPatientValue(
                        item.STATUSKLAIM,
                        item.statusKlaim,
                        pasien.STATUSKLAIM,
                        pasien.statusKlaim
                    ),

                    url: `/v3/emr/${encodeURIComponent(nomor)}`
                };

                // ======================================================
                // BUKA SMART CLAIM WORKSPACE
                // ======================================================
                function openSmartClaimWorkspace(type) {

                    if (!nomor) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Nomor kunjungan pasien tidak ditemukan.',
                            position: 'topRight'
                        });

                        return;
                    }

                    const metadata = {
                        nomor: nomor,

                        // Identitas pasien untuk header ClaimWorkspace
                        norm: patient.norm,
                        nama: patient.nama,
                        namaPasien: patient.nama,
                        namaLengkap: patient.namaLengkap,
                        alamat: patient.alamat,
                        tglLahir: patient.tglLahir,
                        umur: patient.umur,
                        jenisKelamin: patient.jenisKelamin,
                        nik: patient.nik,
                        noHP: patient.noHP,

                        // Asuransi
                        namaAsuransi: patient.namaAsuransi,
                        idAsuransi: patient.idAsuransi,
                        refIdAsuransi: patient.refIdAsuransi,
                        noAsuransi: patient.noAsuransi,
                        noBPJS: patient.noBPJS,

                        // SEP dan jenis kunjungan
                        noSEP: patient.noSEP,
                        jenisKunjungan: patient.jenisKunjungan,

                        // Informasi pelayanan
                        idRuangan: patient.idRuangan,
                        namaRuangan: patient.namaRuangan,
                        namaDokter: patient.namaDokter,

                        // Tanggal kunjungan
                        tglDaftar: patient.tglDaftar,
                        tglMasuk: patient.tglMasuk,
                        tglKeluar: patient.tglKeluar,

                        // Status klaim (tidak menggunakan status notifikasi)
                        statusVerif: patient.statusVerif,
                        statusKlaim: patient.statusKlaim ? 'SUDAH DIVERIFIKASI' : 'BELUM DIVERIFIKASI',

                        label: `SEP. ${patient.noSEP ?? '-'}`
                    };

                    const pending = {
                        nomor: nomor,
                        type: type,
                        metadata: metadata
                    };

                    // Tutup modal.
                    modal.hide();

                    // Jika sudah berada di halaman Smart Claim,
                    // langsung buka workspace pasien.
                    if (
                        document.querySelector('#claimWorkspace') &&
                        window.ClaimWorkspace &&
                        typeof window.ClaimWorkspace.openClaim === 'function'
                    ) {
                        window.ClaimWorkspace.openClaim(
                            nomor,
                            type,
                            metadata
                        );

                        return;
                    }

                    // Simpan tujuan workspace sebelum berpindah halaman.
                    try {
                        sessionStorage.setItem(
                            'sirmed.pendingClaimWorkspace',
                            JSON.stringify(pending)
                        );
                    } catch (error) {
                        console.error(
                            '[Notifikasi] Gagal menyimpan pending claim:',
                            error
                        );

                        iziToast.error({
                            title: 'Error',
                            message: 'Gagal mempersiapkan workspace klaim.',
                            position: 'topRight'
                        });

                        return;
                    }

                    // Navigasi ke halaman utama Smart Claim.
                    if (
                        window.Livewire &&
                        typeof window.Livewire.navigate === 'function'
                    ) {
                        window.Livewire.navigate('/v3/smartclaim');
                    } else {
                        window.location.href = '/v3/smartclaim';
                    }
                }

                // ======================================================
                // TOMBOL SMART CLAIM
                // ======================================================
                $btnLihatKlaim
                    .off('click.smartclaim')
                    .on('click.smartclaim', function (e) {
                        e.preventDefault();

                        openSmartClaimWorkspace('smart_claim');
                    });

                // ======================================================
                // TOMBOL SMART CLAIM FARMASI
                // ======================================================
                $btnLihatKlaimFarmasi
                    .off('click.smartclaim')
                    .on('click.smartclaim', function (e) {
                        e.preventDefault();

                        openSmartClaimWorkspace('smart_claim_farmasi');
                    });

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

                // Identitas pasien.
                const html = `
                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Nama Asuransi
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(patient.namaAsuransi)}
                                </div>
                            </div>

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
                                    ${escapeHtml(patient.nama)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tgl. Lahir (Umur)
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(patient.tglLahir)}
                                    (${escapeHtml(patient.umur)})
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Alamat Pasien
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(patient.alamat)}
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Nomor Asuransi
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(patient.noAsuransi)}&nbsp;
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tgl. Pendaftaran
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(patient.tglDaftar)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tanggal Masuk
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(patient.tglMasuk)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Tanggal Keluar
                                </label>
                                <div class="fw-semibold">
                                    ${escapeHtml(patient.tglKeluar)}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">
                                    Ruangan Dirawat (DPJP)
                                </label>
                                <div class="fw-semibold text-truncate">
                                    ${escapeHtml(patient.namaRuangan)}
                                    (${escapeHtml(patient.namaDokter)})
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

        // ==========================================================
        // SOLVED NOTIFIKASI
        // ==========================================================
        (function () {
            'use strict';

            const btnSelesaikan = $('#btn-selesaikan-notifikasi');

            if (!btnSelesaikan.length) {
                return;
            }

            const modalDetailElement = document.getElementById('modalDetailNotifikasi');
            const modalDetailNotifikasi = bootstrap.Modal.getOrCreateInstance(modalDetailElement);

            const modalElement = document.getElementById('modalSelesaikanNotifikasi');
            const modalSelesaikan = bootstrap.Modal.getOrCreateInstance(modalElement);

            let currentNotifikasiId = null;
            let kembaliKeDetail = false;

            // ======================================================
            // BUKA MODAL INPUT DESKRIPSI SOLVED
            // ======================================================
            btnSelesaikan
                .off('click.solved')
                .on('click', function () {
                    if (!currentNotifikasiId) {
                        iziToast.warning({
                            title: 'Peringatan',
                            message: 'ID notifikasi tidak ditemukan.'
                        });
                        return;
                    }

                    // Tutup modal detail sementara
                    modalDetailNotifikasi.hide();

                    // Reset textarea
                    $('#deskripsi_solved_notifikasi').val('');

                    // Buka modal penyelesaian setelah modal detail tertutup
                    $('#modalDetailNotifikasi').one('hidden.bs.modal', function () {
                        modalSelesaikan.show();
                    });
                });

            // ======================================================
            // TUTUP MODAL DESKRIPSI SOLVED
            // ======================================================
            $('#btn-batal-selesaikan-notifikasi').on('click', function () {
                modalSelesaikan.hide();

                // Kembali ke modal detail setelah modal penyelesaian tertutup
                $('#modalSelesaikanNotifikasi').one('hidden.bs.modal', function () {
                    modalDetailNotifikasi.show();
                });
            });

            // Ketika modal penyelesaian ditutup
            modalElement.addEventListener('hidden.bs.modal', function () {
                if (kembaliKeDetail) {
                    kembaliKeDetail = false;
                    modalDetailNotifikasi.show();
                } else {
                    // Close langsung: pastikan modal detail ikut tertutup
                    modalDetailNotifikasi.hide();
                }
            });

            // ======================================================
            // SUBMIT SOLVED NOTIFIKASI
            // ======================================================
            $('#btn-submit-solved-notifikasi')
                .off('click.solved')
                .on('click.solved', function () {

                    const $button = $(this);

                    if (!currentNotifikasiId) {
                        iziToast.error({
                            title: 'Error',
                            message: 'ID notifikasi tidak ditemukan.',
                            position: 'topRight'
                        });

                        return;
                    }

                    const deskripsiSolved = $('#deskripsi_solved_notifikasi')
                        .val()
                        .trim();

                    // ==================================================
                    // KONFIRMASI
                    // ==================================================
                    Swal.fire({
                        title: 'Selesaikan Notifikasi?',
                        text: 'Notifikasi ini akan ditandai sebagai selesai.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Selesaikan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#198754',
                        reverseButtons: true
                    }).then(function (result) {

                        if (!result.isConfirmed) {
                            return;
                        }

                        // ==================================================
                        // LOADING BUTTON
                        // ==================================================
                        const originalButton = $button.html();

                        $button
                            .prop('disabled', true)
                            .html(`
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            `);

                        // ==================================================
                        // AJAX
                        // ==================================================
                        $.ajax({
                            url: `/api/v2/notifikasi/data/${encodeURIComponent(currentNotifikasiId)}/solved`,
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                deskripsi_solved: deskripsiSolved
                            },

                            success: function (res) {

                                if (!res.status) {
                                    iziToast.error({
                                        title: 'Gagal',
                                        message: res.message || 'Notifikasi gagal diselesaikan.',
                                        position: 'topRight'
                                    });

                                    return;
                                }

                                // Tutup kedua modal
                                modalSelesaikan.hide();

                                bootstrap.Modal
                                    .getOrCreateInstance(
                                        document.getElementById('modalDetailNotifikasi')
                                    )
                                    .hide();

                                // ==================================================
                                // UPDATE STATUS ROW DATATABLE
                                // ==================================================
                                const rowButton = $(`#btn-detail-${currentNotifikasiId}`);
                                const row = rowButton.closest('tr');

                                const statusCell = row.find('td').eq(5);

                                statusCell
                                    .attr('data-search', 'Selesai')
                                    .attr('data-order', 1)
                                    .html(`
                                        <span class="badge badge-sm bg-success">
                                            Selesai
                                        </span>
                                    `);

                                // ==================================================
                                // UPDATE COUNTER
                                // ==================================================
                                const pendingElement = $('#total-notifikasi-pending');
                                const selesaiElement = $('#total-notifikasi-selesai');

                                const pendingText = pendingElement.text();
                                const selesaiText = selesaiElement.text();

                                const pendingCount = parseInt(
                                    pendingText.replace(/[^\d]/g, '')
                                ) || 0;

                                const selesaiCount = parseInt(
                                    selesaiText.replace(/[^\d]/g, '')
                                ) || 0;

                                pendingElement.text(
                                    `Pending : ${Math.max(0, pendingCount - 1).toLocaleString('id-ID')}`
                                );

                                selesaiElement.text(
                                    `Selesai : ${(selesaiCount + 1).toLocaleString('id-ID')}`
                                );

                                // ==================================================
                                // REFRESH DATATABLE
                                // ==================================================
                                if (window.notifikasiDataTable) {
                                    window.notifikasiDataTable
                                        .row(row)
                                        .invalidate('dom')
                                        .draw(false);
                                }

                                iziToast.success({
                                    title: 'Berhasil',
                                    message: res.message || 'Notifikasi berhasil diselesaikan.',
                                    position: 'topRight'
                                });

                            },

                            error: function (xhr) {

                                const message =
                                    xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menyelesaikan notifikasi.';

                                iziToast.error({
                                    title: 'Gagal',
                                    message: message,
                                    position: 'topRight'
                                });

                            },

                            complete: function () {

                                $button
                                    .prop('disabled', false)
                                    .html(originalButton);

                            }
                        });

                    });
                });


            // ======================================================
            // SET ID NOTIFIKASI SAAT DETAIL DIBUKA
            // ======================================================
            window.NotifikasiSolved = {
                setId: function (id) {
                    currentNotifikasiId = id;
                },

                reset: function () {
                    currentNotifikasiId = null;
                }
            };

        })();

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
            // console.error(
            //     '[Notifikasi] DataTables belum tersedia. Periksa urutan pemuatan datatables.js.'
            // );
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

        // =========================================================
        // FILTER STATUS NOTIFIKASI
        // Default: Pending
        // =========================================================
        if (filterElement) {
            const $filter = $(filterElement);

            // Default filter saat pertama kali load
            $filter.val('Pending');

            function applyStatusFilter(status) {
                if (!status) {
                    table.column(5).search('').draw();
                    return;
                }

                const keyword = '^' +
                    $.fn.dataTable.util.escapeRegex(status) +
                    '$';

                table.column(5).search(keyword, true, false).draw();
            }

            // Terapkan filter saat dropdown berubah
            $filter
                .off('change.notifikasi')
                .on('change.notifikasi', function () {
                    applyStatusFilter(this.value);
                });

            // Terapkan Pending langsung saat pertama kali tabel dimuat
            applyStatusFilter('Pending');
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
