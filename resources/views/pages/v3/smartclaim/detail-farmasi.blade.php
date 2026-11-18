@section('title', 'Smart Claim Farmasi')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Smart Claim')

<script>
    (function () {
        const params = new URLSearchParams(window.location.search);

        if (params.get('workspace') === '1') {
            document.documentElement.classList.add('claim-workspace-frame');
            document.body.classList.add('claim-workspace-frame');
        }
    })();
</script>

<style>
    .claim-farmasi-detail .claim-farmasi-back {
        cursor: pointer;
        min-height: 54px;
        border-radius: 1rem;
        transition: all .2s ease;
    }

    .claim-farmasi-detail .claim-farmasi-back:hover {
        background: var(--bs-tertiary-bg);
    }

    .claim-farmasi-detail .claim-farmasi-patient {
        border-radius: 1rem;
        overflow: hidden;
    }

    .claim-farmasi-detail .claim-farmasi-patient .card-body {
        padding: 1.25rem;
    }

    .claim-farmasi-detail .claim-farmasi-list {
        border-radius: 1rem;
        overflow: hidden;
    }

    .claim-farmasi-detail .claim-farmasi-list .list-group-item {
        min-height: 54px;
        transition: background-color .15s ease;
    }

    .claim-farmasi-detail .claim-farmasi-list .list-group-item:hover {
        background: var(--bs-tertiary-bg);
    }

    .claim-farmasi-detail .claim-farmasi-list .form-check-input {
        flex: 0 0 auto;
        cursor: pointer;
    }

    .claim-farmasi-detail .claim-farmasi-preview {
        border-radius: 1rem;
        overflow: hidden;
    }

    .claim-farmasi-detail #preview {
        min-height: 520px;
    }

    .claim-farmasi-detail #preview iframe {
        display: block;
        width: 100%;
        min-height: 650px;
        border: 0;
        border-radius: .5rem;
        background: var(--bs-body-bg);
    }

    .claim-farmasi-detail #alert_verif .alert {
        border-radius: .85rem;
    }

    .claim-farmasi-detail #footer_submit .card-footer {
        border-top: 1px solid var(--bs-border-color);
        background: var(--bs-card-cap-bg);
    }

    @media (max-width: 767.98px) {
        .claim-farmasi-detail #preview {
            min-height: 360px;
            padding: .75rem;
        }

        .claim-farmasi-detail #preview iframe {
            min-height: 520px;
        }

        .claim-farmasi-detail .claim-farmasi-patient .text-end {
            text-align: left !important;
            margin-top: 1rem;
        }
    }
</style>

<div class="claim-frame-content claim-detail-workspace claim-farmasi-detail">
    <div class="container-fluid claim-detail-container">

        {{-- BREADCRUMB --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('v2.dashboard') }}" class="text-decoration-none">
                        <i class="fi fi-rr-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Digital</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('v2.smartclaim') }}" class="text-decoration-none">
                        Smart Claim
                    </a>
                </li>
                <li class="breadcrumb-item">Farmasi</li>
                <li class="breadcrumb-item active" aria-current="page">
                    KUNJ#{{ $list['KUNJUNGAN'] }}
                </li>
            </ol>
        </nav>

        <div class="row g-3">

            {{-- SIDEBAR PILIHAN BERKAS --}}
            <div class="col-lg-3">

                {{-- TOMBOL KEMBALI --}}
                <div class="card mb-3 rounded-4">
                    <div class="card-body p-2">
                        <div
                            data-back-button
                            class="claim-farmasi-back d-flex align-items-center btn btn-outline-secondary border-dashed waves-effect waves-light w-100"
                            role="button"
                            tabindex="0"
                        >
                            <div class="flex-shrink-0 me-3">
                                <i class="ph-duotone ph-caret-double-left align-middle"></i>
                            </div>

                            <div class="flex-grow-1 text-start">
                                <small>Kembali ke Halaman Sebelumnya</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR BERKAS --}}
                <div class="card claim-farmasi-list mb-3">

                    <div class="card-header m-2 p-3 d-flex align-items-center gap-2 bg-teal text-white rounded-4">
                        <div class="mb-0 flex-shrink-0 form-check d-flex align-items-center">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ck_all"
                                style="width:2em;height:2.2em;margin-top:0;cursor:pointer;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Centang Semua Berkas Klaim Langsung"
                                onchange="checkAll(this)"
                            >

                            <label class="form-check-label ms-2">
                                <b>
                                    Centang Semua
                                    <span class="text-danger fs-16">*</span>
                                </b>
                            </label>
                        </div>

                        <h6 class="mb-0 flex-grow-1 text-end text-white">
                            <i class="ti ti-sort-descending-2 me-1"></i>
                            Pilihan Berkas
                        </h6>
                    </div>

                    <div class="list-group list-group-flush">

                        <label class="list-group-item d-flex align-items-center gap-3 p-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ck_sep"
                                style="width:1.35rem;height:1.35rem;margin:0;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Ceklist Berkas Klaim"
                                onchange="if(this.checked){ sep('{{ $list['KUNJUNGAN'] }}'); } else { $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`); }"
                                disabled
                            >
                            <span class="fw-medium">SEP</span>
                        </label>

                        <label class="list-group-item d-flex align-items-center gap-3 p-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ck_resume"
                                style="width:1.35rem;height:1.35rem;margin:0;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Ceklist Berkas Klaim"
                                onchange="if(this.checked){ resume('{{ $list['KUNJUNGAN'] }}'); } else { $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`); }"
                                disabled
                            >
                            <span class="fw-medium">Resume Medis</span>
                        </label>

                        <label class="list-group-item d-flex align-items-center gap-3 p-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ck_laboratorium"
                                style="width:1.35rem;height:1.35rem;margin:0;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Ceklist Berkas Klaim"
                                onchange="if(this.checked){ laboratorium('{{ $list['KUNJUNGAN'] }}'); } else { $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`); }"
                                disabled
                            >
                            <span class="fw-medium">Laboratorium</span>
                        </label>

                        <label class="list-group-item d-flex align-items-center gap-3 p-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ck_kwitansiresep"
                                style="width:1.35rem;height:1.35rem;margin:0;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Ceklist Berkas Klaim"
                                onchange="if(this.checked){ kwitansiresep('{{ $list['KUNJUNGAN'] }}'); } else { $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`); }"
                                disabled
                            >
                            <span class="fw-medium">Kwitansi Resep</span>
                        </label>

                    </div>

                    {{-- DOKUMEN TAMBAHAN DINAMIS --}}
                    <div id="dokumen_tambahan"></div>

                    {{-- TOMBOL AKSI DINAMIS --}}
                    <div id="footer_submit"></div>

                </div>
            </div>

            {{-- AREA PREVIEW --}}
            <div class="col-lg-9">

                <div id="alert_verif"></div>

                <div class="card claim-farmasi-preview mb-3">

                    <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                        <div>
                            <h6 class="mb-1">Preview Berkas Klaim Farmasi</h6>
                            <small class="text-muted">
                                Pilih daftar berkas di bilah menu kiri
                            </small>
                        </div>

                        <div id="btn-refresh-klaim"></div>
                    </div>

                    <div class="card-body" id="preview">
                        @if ($list['klaim'])
                            <iframe
                                src="/api/klaim/farmasi/{{ $list['klaim']->tahun }}/{{ $list['klaim']->bulan }}/{{ $list['klaim']->nomor }}/pdf"
                                width="100%"
                                height="650px"
                                frameborder="0"
                                title="Preview Berkas Klaim Farmasi"
                            ></iframe>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-center text-muted py-5">
                                <i class="ti ti-file-search fs-1 mb-2"></i>
                                <div class="fw-semibold">Preview Berkas Klaim</div>
                                <small>
                                    Area ini akan menampilkan preview berkas klaim yang dipilih
                                </small>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL HAPUS KLAIM --}}
{{-- ========================================================= --}}
<div class="modal animate__animated animate__rubberBand fade" id="hapus" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Hapus Klaim</h4>
            </div>

            <div class="modal-body">
                <input type="text" id="id_hapus" hidden>

                <p style="text-align: justify;">
                    Perhatian, saat ini Anda akan melakukan penghapusan Berkas Klaim
                    (ID#<a class="text-danger" id="tx_hapus"></a>),
                    lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.
                </p>

                <label class="switch">
                    <input type="checkbox" class="switch-input" id="setujuhapus">
                    <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                    <span class="switch-label">Anda siap menerima Risiko</span>
                </label>
            </div>

            <div class="col-12 text-center mb-4">
                <button
                    type="submit"
                    id="btn-hapus-klaim"
                    class="btn btn-danger me-sm-3 me-1"
                    onclick="prosesHapus()"
                >
                    <i class="fa fa-trash me-1" style="font-size:13px"></i>
                    Hapus
                </button>

                <button
                    type="reset"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="fa fa-times me-1" style="font-size:13px"></i>
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL UPLOAD DOKUMEN TAMBAHAN --}}
{{-- ========================================================= --}}
<div class="modal animate__animated animate__rubberBand fade" id="upload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Upload Berkas Tambahan</h4>
            </div>

            <div class="modal-body">
                <input type="text" id="id_upload" hidden>

                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <small>
                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i>
                        Batas maksimal upload berkas adalah <b><u>1 mb</u></b> dan berformat <b>PDF</b> atau <b>Image (JPG/PNG)</b>
                        <br>

                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i>
                        Untuk Mengupload 3 berkas maka silakan untuk melakukan upload pada masing-masing berkas satu persatu
                        <br>

                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i>
                        Pada kolom isian Nama / Jenis Berkas, isikan Penamaan berkas contoh : Hasil EKG, dll
                        <br>

                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i>
                        Isian-isian di bawah bersifat Wajib diisi atau tidak boleh dikosongi
                    </small>
                </div>

                <div class="form-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        id="nama_tambahan"
                        placeholder="Tuliskan Nama / Jenis Berkas * (e.g. Hasil EKG / dll)"
                    >
                </div>

                <div class="form-group">
                    <input type="file" class="form-control" id="filex">
                </div>
            </div>

            <div class="col-12 text-center mb-4">
                <button
                    type="submit"
                    id="btn-upload-proses"
                    class="btn btn-primary me-sm-3 me-1"
                    onclick="prosesUpload()"
                >
                    <i class="fa fa-upload me-1" style="font-size:13px"></i>
                    Upload
                </button>

                <button
                    type="reset"
                    class="btn btn-link-secondary"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    Batal
                    <i class="fa fa-times ms-1" style="font-size:13px"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL HAPUS DOKUMEN TAMBAHAN --}}
{{-- ========================================================= --}}
<div class="modal animate__animated animate__rubberBand fade" id="hapusTambahan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Hapus Berkas Tambahan</h4>
            </div>

            <div class="modal-body">
                <input type="text" id="id_hapus_tambahan" hidden>

                <p style="text-align: justify;">
                    Perhatian, saat ini Anda akan melakukan penghapusan Berkas Tambahan Klaim
                    (ID#<a class="text-danger" id="tx_hapus_tambahan"></a>),
                    lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.
                </p>

                <label class="switch">
                    <input type="checkbox" class="switch-input" id="setujuhapustambahan">
                    <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                    <span class="switch-label">Anda siap menerima Risiko</span>
                </label>
            </div>

            <div class="col-12 text-center mb-4">
                <button
                    type="submit"
                    id="btn-hapus-tambahan"
                    class="btn btn-danger me-sm-3 me-1"
                    onclick="prosesHapusTambahan()"
                >
                    <i class="fa fa-trash me-1" style="font-size:13px"></i>
                    Hapus
                </button>

                <button
                    type="reset"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="fa fa-times me-1" style="font-size:13px"></i>
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var editorCatatanTambah;
    var editorCatatanEdit;

    $(document).ready(function() {

        $('[data-back-button]').on('click', function() {

            // Jika dibuka di dalam ClaimWorkspace, tutup tab aktif parent.
            try {
                if (window.parent !== window && window.parent.ClaimWorkspace) {
                    const activeTab = window.parent.ClaimWorkspace.getActiveTab();

                    if (activeTab) {
                        window.parent.ClaimWorkspace.closeTab(activeTab.tabId);
                        return;
                    }
                }
            } catch (error) {
                console.warn('Tidak dapat mengakses parent workspace:', error);
            }

            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.assign("{{ route('v2.smartclaim') }}");
            }
        });

        verify();
    });

    // =========================================================
    // LOAD DATA / VERIFY
    // =========================================================
    function verify() {

        var kunjungan = "{{ $list['KUNJUNGAN'] }}";

        $('#daftar_catatan').empty()
            .append(`
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow spinner-grow-sm me-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <a class="align-middle">Memproses Data Catatan..</a>
                </div>
            `);

        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/data`,
            type: 'GET',
            dataType: 'json',

            success: function(res) {

                console.log(res.file);

                // BELUM ADA BERKAS KLAIM
                if (!res.show) {

                    $('#alert_verif').empty();

                    $('#ck_sep').prop('checked', false).prop('disabled', false);
                    $('#ck_resume').prop('checked', false).prop('disabled', false);
                    $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
                    $('#ck_kwitansiresep').prop('checked', false).prop('disabled', false);

                    submit = ``;

                    submit += `
                        <div class="card-footer p-3">
                            <div class="btn-group w-100">
                    `;

                    submit += `
                        <button class="btn btn-subtle-warning btn-sm" onclick="clearCheckbox()">
                            <i class="ti ti-eraser me-1"></i> Clear
                        </button>

                        <button class="btn btn-primary btn-sm" onclick="prosesSubmit('${kunjungan}')" id="btn-submit">
                            Submit <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    `;

                    submit += `
                            </div>
                        </div>
                    `;

                    $('#footer_submit').empty().append(submit);

                } else {

                    let koleksi = JSON.parse(res.show.koleksi || '[]');

                    if (koleksi.includes(1)) {
                        $('#ck_sep').prop('checked', true).prop('disabled', false);
                    } else {
                        $('#ck_sep').prop('checked', false).prop('disabled', false);
                    }

                    if (koleksi.includes(2)) {
                        $('#ck_resume').prop('checked', true).prop('disabled', false);
                    } else {
                        $('#ck_resume').prop('checked', false).prop('disabled', false);
                    }

                    if (koleksi.includes(6)) {
                        $('#ck_laboratorium').prop('checked', true).prop('disabled', false);
                    } else {
                        $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
                    }

                    if (koleksi.includes(12)) {
                        $('#ck_kwitansiresep').prop('checked', true).prop('disabled', false);
                    } else {
                        $('#ck_kwitansiresep').prop('checked', false).prop('disabled', false);
                    }

                    $('#btn-refresh-klaim').empty().append(`
                        <button class="btn btn-subtle-primary btn-sm" onclick="prosesSubmit('${kunjungan}')">
                            <i class="ti ti-refresh me-1"></i>
                            Refresh Preview Klaim
                        </button>
                    `);

                    // DOKUMEN TAMBAHAN
                    $('#dokumen_tambahan').empty();

                    tambahan = ``;

                    res.file.forEach(item => {

                        if (item.jenis == 10) {

                            tambahan += `
                                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0"
                                    style="justify-content:space-between;">

                                    <div class="dropdown">
                                        <button
                                            class="btn btn-link-secondary"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            style="width:2em;height:2.2em;display:flex;justify-content:center;align-items:center;padding:0;"
                                        >
                                            <i class="fas fa-angle-down" style="font-size:13px"></i>
                                        </button>

                                        <ul class="dropdown-menu">
                                            ${
                                                res.show.verif == 0
                                                ?
                                                `
                                                    <li>
                                                        <button class="dropdown-item text-primary" onclick="showUpload(${item.id})">
                                                            <i class="fas fa-eye" style="font-size:13px"></i>
                                                            Lihat
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <button class="dropdown-item text-danger" id="btn-hapus-tambahan${item.id}" onclick="hapusTambahan(${item.id})">
                                                            <i class="fas fa-trash" style="font-size:13px"></i>
                                                            Hapus
                                                        </button>
                                                    </li>
                                                `
                                                :
                                                `
                                                    <li>
                                                        <button class="dropdown-item text-primary" onclick="showUpload(${item.id})">
                                                            <i class="fas fa-eye" style="font-size:13px"></i>
                                                            Lihat
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <button class="dropdown-item text-secondary" disabled>
                                                            <i class="fas fa-trash" style="font-size:13px"></i>
                                                            Hapus
                                                        </button>
                                                    </li>
                                                `
                                            }
                                        </ul>
                                    </div>

                                    <a class="text-nowrap mt-1" style="margin-left:auto;">
                                        ${item.nama_tambahan}
                                    </a>
                                </div>
                            `;

                            console.log('muncul');
                        }
                    });

                    $('#dokumen_tambahan').append(tambahan);

                    submit = ``;

                    if (res.show.verif == 0) {

                        $('#alert_verif').empty().append(`
                            <div class="alert alert-warning d-block text-center text-uppercase">
                                <i class="feather icon-x-circle me-2"></i>
                                Berkas Klaim Belum Diverifikasi
                            </div>
                        `);

                        submit += `
                            <div class="card-footer p-3">
                                <button class="btn btn-danger w-100 btn-sm mb-3" onclick="hapus('${kunjungan}')">
                                    <i class="fas fa-trash me-1"></i>
                                    Hapus Berkas Klaim
                                </button>

                                <button class="btn btn-warning btn-sm w-100 mb-3" onclick="upload('${kunjungan}')" id="btn-upload">
                                    <i class="fas fa-upload me-1"></i>
                                    Upload Berkas Tambahan
                                </button>

                                <button class="btn btn-success btn-sm w-100 mb-3" onclick="prosesSubmit('${kunjungan}')" id="btn-submit">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Submit Ulang Klaim
                                </button>

                                <button class="btn btn-secondary btn-sm w-100" onclick="verifikasi('${kunjungan}')" id="btn-verif">
                                    <i class="fas fa-check-square me-1"></i>
                                    Verifikasi Berkas
                                </button>
                            </div>
                        `;

                    } else {

                        $('#alert_verif').empty().append(`
                            <div class="alert alert-success d-block text-center text-uppercase">
                                <i class="feather icon-check-circle me-2"></i>
                                Berkas Klaim Telah Diverifikasi
                            </div>
                        `);

                        submit += `
                            <div class="card-footer p-3">
                                <button class="btn btn-outline-secondary btn-sm w-100" onclick="batalVerifikasi('${kunjungan}')" id="btn-batal-verif">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Batal Verifikasi
                                </button>
                            </div>
                        `;
                    }

                    $('#footer_submit').empty().append(submit);
                }
            }
        });
    }

    // =========================================================
    // PREVIEW SEP
    // =========================================================
    function sep(kunjungan) {

        $('#ck_sep').prop('disabled', true);

        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        fetch("/api/pasien/" + kunjungan + "/sep")
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }

                return response.blob();
            })
            .then(blob => {

                const fileURL = URL.createObjectURL(blob);

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);

                $('#ck_sep').prop('disabled', false);
            })
            .catch(error => {

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data SEP tidak ditemukan atau belum dibuatkan oleh Simgos.',
                    position: 'topRight'
                });

                console.error(error);

                $('#preview').empty().append(`
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                `);

                $('#ck_sep').prop('checked', false).prop('disabled', false);
            });
    }

    // =========================================================
    // PREVIEW RESUME MEDIS
    // =========================================================
    function resume(kunjungan) {

        $('#ck_resume').prop('disabled', true);

        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        fetch("/api/pasien/" + kunjungan + "/resumeRj")
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }

                return response.blob();
            })
            .then(blob => {

                const fileURL = URL.createObjectURL(blob);

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);

                $('#ck_resume').prop('disabled', false);
            })
            .catch(error => {

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data Resume tidak ditemukan atau belum dibuatkan oleh Simgos.',
                    position: 'topRight'
                });

                console.error(error);

                $('#preview').empty().append(`
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                `);

                $('#ck_resume').prop('checked', false).prop('disabled', false);
            });
    }

    // =========================================================
    // PREVIEW LABORATORIUM
    // =========================================================
    function laboratorium(kunjungan) {

        $('#ck_laboratorium').prop('disabled', true);

        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        fetch("/api/pasien/" + kunjungan + "/lab")
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }

                return response.blob();
            })
            .then(blob => {

                const fileURL = URL.createObjectURL(blob);

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);

                $('#ck_laboratorium').prop('disabled', false);
            })
            .catch(error => {

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data Hasil Laboratorium tidak ditemukan atau belum dibuatkan oleh Simgos.',
                    position: 'topRight'
                });

                console.error(error);

                $('#preview').empty().append(`
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                `);

                $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
            });
    }

    // =========================================================
    // PREVIEW KWITANSI RESEP
    // =========================================================
    function kwitansiresep(kunjungan) {

        $('#ck_kwitansiresep').prop('disabled', true);

        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        fetch("/api/pasien/" + kunjungan + "/kwitansiResep")
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }

                return response.blob();
            })
            .then(blob => {

                const fileURL = URL.createObjectURL(blob);

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);

                $('#ck_kwitansiresep').prop('disabled', false);
            })
            .catch(error => {

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data Kwitansi Resep tidak ditemukan atau belum dibuatkan oleh Simgos.',
                    position: 'topRight'
                });

                console.error(error);

                $('#preview').empty().append(`
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                `);

                $('#ck_kwitansiresep').prop('checked', false).prop('disabled', false);
            });
    }

    // =========================================================
    // SUBMIT KLAIM FARMASI
    // =========================================================
    function prosesSubmit(kunjungan) {

        $('#btn-submit')
            .prop('disabled', true)
            .find('i')
            .removeClass('fa-paper-plane')
            .addClass('fa-sync fa-spin');

        var save = new FormData();

        save.append('kunjungan', kunjungan);
        save.append('sep', $('#ck_sep').prop('checked'));
        save.append('resume', $('#ck_resume').prop('checked'));
        save.append('laboratorium', $('#ck_laboratorium').prop('checked'));
        save.append('kwitansiresep', $('#ck_kwitansiresep').prop('checked'));
        save.append('user', "{{ Auth::user()->ID }}");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            url: "{{ route('api.klaim.farmasi.submit') }}",
            method: 'POST',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',

            success: function(res) {

                const tahun = res.tahun;
                const bulan = res.bulan;
                const kunjungan = res.kunjungan;

                const fileURL = `/api/klaim/farmasi/${tahun}/${bulan}/${kunjungan}/pdf`;

                verify();

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);

                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: res.message,
                    position: 'topRight'
                });

                $('#btn-submit')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-paper-plane');
            },

            error: function(xhr, status, error) {

                console.error('Terjadi kesalahan:', error);

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Submit Berkas Gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });

                $('#btn-submit')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-paper-plane');
            }
        });
    }

    // =========================================================
    // MODAL HAPUS KLAIM
    // =========================================================
    function hapus(kunjungan) {

        $('#id_hapus').val(kunjungan);
        $('#tx_hapus').text(kunjungan);

        var inputs = document.getElementById('setujuhapus');
        inputs.checked = false;

        $('#hapus').modal('show');
    }

    // =========================================================
    // PROSES HAPUS KLAIM
    // =========================================================
    function prosesHapus() {

        var checkboxHapus = $('#setujuhapus').is(":checked");

        if (checkboxHapus == false) {

            iziToast.error({
                title: 'Pesan Galat!',
                message: 'Mohon menyetujui untuk dilakukan penghapusan berkas klaim tersebut',
                position: 'topRight'
            });

        } else {

            var kunjungan = $('#id_hapus').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: `/api/klaim/farmasi/${kunjungan}/hapus`,
                type: 'DELETE',
                dataType: 'json',

                success: function(res) {

                    $('#preview').empty().append(`
                        Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                    `);

                    verify();

                    iziToast.success({
                        title: 'Pesan Berhasil!',
                        message: `Berkas Klaim dengan No.Kunjungan : ${kunjungan} berhasil dihapus dari datarecord`,
                        position: 'topRight'
                    });

                    $('#hapus').modal('hide');
                }
            });
        }
    }

    // =========================================================
    // MODAL UPLOAD DOKUMEN TAMBAHAN
    // =========================================================
    function upload(kunjungan) {

        $('#id_upload').val(kunjungan);
        $('#nama_tambahan').val('');
        $('#filex').val('');

        $('#upload').modal('show');
    }

    // =========================================================
    // PROSES UPLOAD DOKUMEN TAMBAHAN
    // =========================================================
    function prosesUpload() {

        $('#btn-upload-proses')
            .prop('disabled', true)
            .find('i')
            .removeClass('fa-upload')
            .addClass('fa-sync fa-spin');

        var save = new FormData();
        var filesAdded = $('#filex')[0].files;

        save.append('file', filesAdded[0]);
        save.append('kunjungan', $('#id_upload').val());
        save.append('nama_tambahan', $('#nama_tambahan').val());
        save.append('user', "{{ Auth::user()->ID }}");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            url: "{{ route('api.klaim.upload') }}",
            method: 'POST',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',

            success: function(res) {

                verify();

                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: res.message,
                    position: 'topRight'
                });

                $('#btn-upload-proses')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-upload');

                $('#upload').modal('hide');
            },

            error: function(xhr, status, error) {

                console.error('Terjadi kesalahan:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);

                try {

                    let response = JSON.parse(xhr.responseText);

                    iziToast.error({
                        title: 'Maaf!',
                        message: response.message,
                        position: 'topRight'
                    });

                } catch (e) {

                    alert('Terjadi kesalahan: ' + error);
                }

                $('#btn-upload-proses')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-upload');
            }
        });
    }

    // =========================================================
    // PREVIEW DOKUMEN TAMBAHAN
    // =========================================================
    function showUpload(id) {

        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        fetch("/api/klaim/upload/" + id + "/show")
            .then(response => {

                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }

                return response.blob();
            })
            .then(blob => {

                const fileURL = URL.createObjectURL(blob);

                $('#preview').empty().html(`
                    <iframe src="${fileURL}" width="100%" height="650px" frameborder="0"></iframe>
                `);
            })
            .catch(error => {

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Berkas Upload Tambahan tidak ditemukan atau gagal ditampilkan.',
                    position: 'topRight'
                });

                console.error(error);

                $('#preview').empty().append(`
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                `);
            });
    }

    // =========================================================
    // MODAL HAPUS DOKUMEN TAMBAHAN
    // =========================================================
    function hapusTambahan(id) {

        $('#id_hapus_tambahan').val(id);
        $('#tx_hapus_tambahan').text(id);

        var inputs = document.getElementById('setujuhapustambahan');
        inputs.checked = false;

        $('#hapusTambahan').modal('show');
    }

    // =========================================================
    // PROSES HAPUS DOKUMEN TAMBAHAN
    // =========================================================
    function prosesHapusTambahan() {

        var checkboxHapus = $('#setujuhapustambahan').is(":checked");

        if (checkboxHapus == false) {

            iziToast.error({
                title: 'Pesan Galat!',
                message: 'Mohon menyetujui untuk dilakukan penghapusan berkas Tambahan tersebut',
                position: 'topRight'
            });

        } else {

            var id_berkas = $('#id_hapus_tambahan').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: `/api/klaim/upload/${id_berkas}/hapus`,
                type: 'DELETE',
                dataType: 'json',

                success: function(res) {

                    verify();

                    iziToast.success({
                        title: 'Pesan Berhasil!',
                        message: `Salah satu Berkas Tambahan terpilih dengan ID Berkas : ${id_berkas} berhasil dihapus dari datarecord`,
                        position: 'topRight'
                    });

                    $('#hapusTambahan').modal('hide');
                }
            });
        }
    }

    // =========================================================
    // VERIFIKASI BERKAS
    // =========================================================
    function verifikasi(kunjungan) {

        $('#btn-verif')
            .prop('disabled', true)
            .find('i')
            .removeClass('fa-check-square')
            .addClass('fa-sync fa-spin');

        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/verifikasi`,
            type: 'GET',
            dataType: 'json',

            success: function(res) {

                if (res.code == 1) {

                    verify();

                    iziToast.success({
                        title: 'Pesan Sukses!',
                        message: res.message,
                        position: 'topRight'
                    });

                } else {

                    iziToast.warning({
                        title: 'Maaf!',
                        message: res.message,
                        position: 'topRight'
                    });
                }

                $('#btn-verif')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-check-square');
            },

            error: function(xhr, status, error) {

                console.error('Terjadi kesalahan:', error);

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Verifikasi berkas gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });

                $('#btn-verif')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-check-square');
            }
        });
    }

    // =========================================================
    // BATAL VERIFIKASI
    // =========================================================
    function batalVerifikasi(kunjungan) {

        $('#btn-batal-verif')
            .prop('disabled', true)
            .find('i')
            .removeClass('fa-times-circle')
            .addClass('fa-sync fa-spin');

        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/batalverifikasi`,
            type: 'GET',
            dataType: 'json',

            success: function(res) {

                verify();

                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: 'Batal Verifikasi berkas berhasil dilakukan pada ' + res,
                    position: 'topRight'
                });

                $('#btn-batal-verif')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-times-circle');
            },

            error: function(xhr, status, error) {

                console.error('Terjadi kesalahan:', error);

                iziToast.error({
                    title: 'Maaf!',
                    message: 'Batal Verifikasi berkas gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });

                $('#btn-batal-verif')
                    .prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-times-circle');
            }
        });
    }

    // =========================================================
    // CENTANG SEMUA BERKAS
    // =========================================================
    function checkAll(el) {

        if (el.checked) {

            $('#ck_all').prop('disabled', true);

            $('#ck_sep').prop('checked', true);
            sep('{{ $list['KUNJUNGAN'] }}');

            $('#ck_resume').prop('checked', true);
            resume('{{ $list['KUNJUNGAN'] }}');

            $('#ck_laboratorium').prop('checked', true);
            laboratorium('{{ $list['KUNJUNGAN'] }}');

            $('#ck_kwitansiresep').prop('checked', true);
            kwitansiresep('{{ $list['KUNJUNGAN'] }}');

        } else {

            $('#ck_all').prop('disabled', false);

            $('#preview').empty().append(`
                Area ini akan menampilkan Preview Berkas Klaim yang dipilih
            `);

            clearCheckbox();
        }
    }

    // =========================================================
    // RESET CHECKBOX
    // =========================================================
    function clearCheckbox() {

        $('#ck_resume').prop('checked', false).prop('disabled', false);
        $('#ck_sep').prop('checked', false).prop('disabled', false);
        $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
        $('#ck_kwitansiresep').prop('checked', false).prop('disabled', false);

        $('#ck_all').prop('checked', false).prop('disabled', false);

        $('#preview').empty().append(`
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        iziToast.success({
            title: 'Pesan Berhasil!',
            message: 'Ceklist Berkas dan Area Preview berhasil dibersihkan',
            position: 'topRight'
        });
    }
</script>

@endsection
