@extends('layouts.v2.index')

@section('title','Detail Kunjungan - RM.'.$list["show"]->NORM ?? 'XXXX')

@push('styles')
    <link rel="stylesheet" href="{{ asset('v2/css/emr/cppt.css') }}">
@endpush

@section('content')

<div class="container-fluid">

    <!-- [ breadcrumb ] start -->
    <nav aria-label="breadcrumb" id="breadcrumb-detail-kunjungan">
        <ol class="breadcrumb px-3 py-2 bg-primary-subtle rounded-3">
            <li class="breadcrumb-item">
                <a class="link-primary" href="{{ route('v2.dashboard') }}">
                    <i class="fi fi-rr-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item"><a class="link-primary fw-medium text-decoration-none" href="javascript:void(0);">Digital</a></li>
            <li class="breadcrumb-item"><a class="link-primary fw-medium text-decoration-none" href="{{ route('v2.emr') }}">Medical Record</a></li>
            <li class="breadcrumb-item active" aria-current="page"><b class="fw-medium" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Kunjungan Pasien">KUNJ#{{ $list['KUNJUNGAN'] }}</b></li>
        </ol>

        <ol class="breadcrumb mb-0">
        </ol>
    </nav>

    <!-- [ Main Content ] start -->
    <div class="col-sm-12">
        <div class="card mb-2" id="btn-top-detail-kunjungan">
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div data-back-button class="btn btn-outline-primary shadow-lg">
                        <i class="ph-duotone ph-caret-double-left align-middle me-1"></i> Kembali
                    </div>
                    <div class="btn btn-outline-warning shadow-lg" id="btn-icare" onclick="showICare()">
                        <i class="fas fa-clipboard-list align-middle me-1"></i> Lihat I-Care
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body p-2">
                <div class="row justify-content-between d-flex align-items-center p-2">
                    <div class="col-xl-8 col-md-8 col-sm-8">
                        <h5 class="mb-1 align-middle"><i class="ri-user-3-line me-1"></i> <b class="fw-medium" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Rekam Medis Pasien">RM. {{ str_pad($list['show']->NORM, 8, '0', STR_PAD_LEFT) }}</b></h5>
                        <h4 class="text-truncate mb-1 fw-bold">{{ $list['show']->NAMAPASIEN }}</h4>
                        <p class="text-truncate mb-1" style="font-size: 12px">
                            <b>NOBPJS. <a class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Kartu BPJS Pasien">{{ $list['show']->NOBPJS }}</a></b>
                            | <b data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP Tgl. {{ $list['show']->TGLSEP?\Carbon\Carbon::parse($list['show']->TGLSEP)->translatedFormat('d F Y'):'' }}">SEP. <a class="text-purple-700">{{ $list['show']->NOSEP?$list['show']->NOSEP:'Tidak Ditemukan' }}</a></b>
                        </p>
                        @if ($list['show']->STATUS == 1)
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium">Pasien Sedang Dilayani</span>
                        @else
                            @if ($list['show']->STATUS == 2)
                                <span class="badge bg-primary-subtle text-dark border border-primary-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium">Kunjungan Pasien Selesai / Final</span>
                            @else
                                <span class="badge bg-danger-subtle text-dark border border-danger-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium">Pasien Batal Periksa</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4 text-end">
                        <p class="fw-bold mb-1 fs-16"><b>{{ $list['show']->NAMARUANGAN }}</b></p>
                        <h6 class="text-truncate text-mint mb-1 fs-20"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="DPJP. {{ $list['show']->NAMADOKTER }}">{{ $list['show']->NAMADOKTER }}</b></h6>
                        <p class="text-truncate mb-0 ms-3" style="font-size: 13px"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tgl. Pasien mulai dilayani / diterima">Masuk :&nbsp;&nbsp;{{ \Carbon\Carbon::parse($list['show']->MASUK)->locale('id')->translatedFormat('d M Y H.i') . ' WIB' }}</b></p>
                        <p class="text-truncate mb-0 ms-3" style="font-size: 13px"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tgl. Pasien selesai dilayani / dipulangkan">Keluar :&nbsp;&nbsp;{{ $list['show']->KELUAR?\Carbon\Carbon::parse($list['show']->KELUAR)->locale('id')->translatedFormat('d M Y H.i') . ' WIB':'-' }}</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header px-2">
                <ul class="nav nav-pills card-header-pills gap-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#identitas" role="tab"
                            aria-selected="false" tabindex="-1" aria-controls="identitas">
                            <i class="ph-duotone ph-user-switch me-2"></i> Identitas Pasien
                        </button>
                    </li>
                    @hasanyrole(['admin','dokterumum'])
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fpengkajian" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-fpengkajian" disabled>
                            <i class="ph-duotone ph-user-list me-2"></i> Form Pengkajian
                        </button>
                    </li>
                    @endhasanyrole
                    @if (Str::startsWith($list['show']->IDRUANGAN, '10207'))
                        <li class="nav-item" role="presentation" hidden> <!-- TIDAK DIPAKAI LAGI -->
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#frehab" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-frehab" disabled>
                                <i class="ph-duotone ph-archive-tray me-2"></i> Program Rehab Medik
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fmrehab" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-fmrehab" disabled>
                                <i class="ph-duotone ph-archive-box me-2"></i> Program Rehab Medik
                            </button>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="" data-bs-toggle="tab" href="#konsul" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-konsul" disabled>
                            <i class="ph-duotone ph-files me-2"></i> Form Konsul
                        </a>
                    </li>
                    @if (Str::startsWith($list['show']->IDRUANGAN, '10202'))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="" data-bs-toggle="tab" href="#fmatriks" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-fmatriks" disabled>
                                <i class="ph-duotone ph-film-script me-2"></i> Matriks
                            </a>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="" data-bs-toggle="tab" href="#fuploads" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-fuploads" disabled>
                            <i class="ph-duotone ph-upload-simple me-2"></i> Upload File
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show" id="identitas" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 col-xxl-8">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5>Biodata Pasien</h5>
                                <div class="dropdown" hidden>
                                    <a class="btn btn-secondary-subtle arrow-none" href="javascript: void(0);"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ph-duotone ph-dots-three-outline-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a class="dropdown-item" href="javascript: void(0);"><s>Ubah Data</s></a>
                                        {{-- <a class="dropdown-item" href="#">Delete</a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Nama Lengkap</p>
                                                <p class="mb-0">{{ $list['show']->NAMALENGKAPPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Panggilan</p>
                                                <p class="mb-0">{{ $list['show']->PANGGILANPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Nomor Induk Kependudukan</p>
                                                <p class="mb-0">{{ $list['show']->NIKPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">No.HP</p>
                                                <p class="mb-0">{{ $list['show']->NOHPPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Lahir di</p>
                                                <p class="mb-0 text-uppercase">{{ $list['show']->TLPASIEN.', ' }}{{ \Carbon\Carbon::parse($list['show']->TGLLAHIRPASIEN)->translatedFormat('d F Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Umur Sekarang</p>
                                                <p class="mb-0">{{ $list['show']->UMURPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Jenis Kelamin</p>
                                                <p class="mb-0">{{ $list['show']->JKPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Keluarga/Orang Terdekat</p>
                                                <p class="mb-0 text-uppercase">{{ $list['show']->KELUARGAPASIEN?$list['show']->KELUARGAPASIEN:'-' }}{{ $list['show']->STKELUARGAPASIEN?' ('.$list['show']->STKELUARGAPASIEN.')':'' }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 pb-0">
                                        <p class="mb-1 text-muted">Alamat Lengkap</p>
                                        <p class="mb-0"> {{ $list['show']->ALAMATPASIEN }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xxl-4">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title">Riwayat Kunjungan Pasien</h5>
                                <button class="btn btn-sm btn-outline-warning" onclick="loadRiwayatKunjunganPasien()" id="btn-refresh-riwayat-kunjungan"
                                    data-bs-toggle="tooltip" title="Refresh Riwayat Kunjungan Pasien"><i class="fas fa-sync"></i></button>
                            </div>
                            <div style="max-height: 420px; overflow-y: auto;" class="rounded-bottom">
                                <ul class="list-group list-group-flush" id="load-riwayat-kunjungan-pasien">
                                    <li class="list-group-item"><center><i class="fas fa-sync fa-spin me-1"></i> Menginisialisasi Riwayat Kunjungan</center></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fpengkajian" role="tabpanel">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.tab_pengkajian')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fmrehab" role="tabpanel">
                @include('pages.v2.medicalrecord.detail.rehabmedik.tab_rehab')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="konsul" role="tabpanel">
                @include('pages.v2.medicalrecord.detail.konsul.form')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fuploads" role="tabpanel">
                @include('pages.v2.medicalrecord.detail.upload')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fmatriks" role="tabpanel">
                @include('pages.v2.medicalrecord.detail.matriks')
            </div>
        </div>
    </div>

    {{-- MODAL STARTED --}}
    <div id="modalICare" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="catatanLabel" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="badge text-bg-info">i-Care</span> <a id="show-id-icare" class="text-dark ms-1"></a></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-icare"></div>
                <div class="modal-footer">
                    <div id="btn-refresh-catatan"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL ENDED --}}

    {{-- ============================================================
        FLOATING CPPT BUTTON
    ============================================================ --}}
    <div class="cppt-floating-container">
        <button type="button" id="btn-floating-cppt" class="cppt-floating-btn" onclick="showModalCppt('{{ $list['KUNJUNGAN'] }}')">
            <span class="cppt-floating-icon">
                <i class="ph-duotone ph-notepad"></i>
            </span>
            <span class="cppt-floating-label">
                CPPT
            </span>
            <span class="cppt-floating-badge" id="btn-count-cppt">
                {{ $list['cpptCount'] }}
            </span>
        </button>
    </div>

    {{-- ============================================================
        OPEN MODAL NEW CPPT
    ============================================================ --}}
    <div class="modal fade" id="modalCPPT" tabindex="-1" aria-labelledby="modalCPPTLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="modal-header bg-primary text-white border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="cppt-modal-icon">
                            <i class="ph-duotone ph-notepad fs-24"></i>
                        </div>
                        <div>
                            <h5 class="text-white fw-bold mb-0"
                                id="modalCPPTLabel">
                                CPPT
                            </h5>
                            <small class="opacity-75">
                                Catatan Perkembangan Pasien Terintegrasi
                            </small>
                        </div>
                    </div>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body p-3 p-md-4 position-relative">

                    <div class="cppt-patient-info rounded-3 p-3 mb-1">
                        <div class="row g-3" id="cppt_header">
                    </div>

                    <div class="card border border-info text-info-emphasis border-dashed rounded-3 shadow-none mb-3 mt-2">
                        <div class="card-header bg-info-subtle p-0" data-bs-toggle="tooltip" title="Buka / Sembunyikan Form Tambah CPPT">
                            <button type="button" class="btn w-100 text-start d-flex align-items-center justify-content-between px-3 py-3 shadow-none" data-bs-toggle="collapse" data-bs-target="#collapseTambahCPPT" aria-expanded="true" aria-controls="collapseTambahCPPT">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ph-duotone ph-plus-circle text-primary fs-20"></i>
                                    <strong>Tambah Catatan CPPT</strong>
                                </div>
                                <i class="ph ph-caret-down"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="collapseTambahCPPT">
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- TANGGAL --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="cppt_tanggal">
                                    </div>
                                    {{-- JAM --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Jam</label>
                                        <input type="time" class="form-control" id="cppt_jam">
                                    </div>
                                    {{-- PPA --}}
                                    <div class="col-md-5 position-relative">
                                        <label class="form-label">PPA</label>
                                        <input type="text" class="form-control" id="cppt_ppa" placeholder="Cari nama atau NIP PPA..." autocomplete="off">
                                        <input type="hidden" id="cppt_ppa_id">
                                        <div id="cppt_ppa_autocomplete" class="list-group position-absolute start-0 end-0 shadow-sm bg-body" style="z-index:1050;display:none;"></div>
                                    </div>
                                    {{-- METODE CPPT --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Metode</label>
                                        <div class="d-flex align-items-center gap-3 mt-2">
                                            {{-- SBAR --}}
                                            <div class="form-check">
                                                <input class="form-check-input cppt-format"
                                                    type="checkbox"
                                                    id="cppt_format_sbar"
                                                    value="SBAR">
                                                <label class="form-check-label" for="cppt_format_sbar">
                                                    SBAR
                                                </label>
                                            </div>
                                            {{-- TBAK --}}
                                            <div class="form-check">
                                                <input class="form-check-input cppt-format"
                                                    type="checkbox"
                                                    id="cppt_format_tbak"
                                                    value="TBAK">
                                                <label class="form-check-label" for="cppt_format_tbak">
                                                    TBAK
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- S --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">S <small class="text-muted">(Subjective)</small></label>
                                        <textarea class="form-control" id="cppt_s" rows="4" placeholder="Keluhan atau kondisi yang dirasakan pasien..."></textarea>
                                    </div>
                                    {{-- O --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">O <small class="text-muted">(Objective)</small></label>
                                        <textarea class="form-control" id="cppt_o" rows="4" placeholder="Hasil pemeriksaan objektif..."></textarea>
                                    </div>
                                    {{-- A --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">A <small class="text-muted">(Assessment)</small></label>
                                        <textarea class="form-control" id="cppt_a" rows="4" placeholder="Assessment atau diagnosis pasien..."></textarea>
                                    </div>
                                    {{-- P --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">P <small class="text-muted">(Planning)</small></label>
                                        <textarea class="form-control" id="cppt_p" rows="4" placeholder="Rencana terapi atau tindak lanjut..."></textarea>
                                    </div>
                                    {{-- I --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">I <small class="text-muted">(Instruction)</small></label>
                                        <textarea class="form-control" id="cppt_i" rows="4" placeholder="Instruksi untuk tindak lanjut pasien..."></textarea>
                                    </div>
                                    {{-- BUTTON --}}
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-light" id="btn-batal-cppt" data-bs-toggle="collapse" data-bs-target="#collapseTambahCPPT" aria-expanded="true" aria-controls="collapseTambahCPPT">
                                                <i class="ph ph-x me-1"></i> Batal
                                            </button>
                                            <button type="button" class="btn btn-primary" id="btn-simpan-cppt">
                                                <i class="ph ph-floppy-disk me-1"></i> Tambah CPPT
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT CPPT --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ph-duotone ph-clock-counter-clockwise text-primary fs-20"></i>
                            <strong>Riwayat CPPT</strong>
                        </div>
                        <span class="badge text-bg-primary" id="cppt_count"></span>
                    </div>

                    {{-- TIMELINE --}}
                    <div class="cppt-timeline" id="cppt_riwayat"></div>

                    {{-- SCROLL TO TOP --}}
                    <div class="position-sticky bottom-0 d-flex justify-content-end pe-1 pb-1"
                        style="z-index:1050;pointer-events:none;">
                        <button type="button"
                                id="btn-cppt-scroll-top"
                                class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center"
                                style="width:45px;height:45px;pointer-events:auto;" data-bs-toggle="tooltip" title="Kembali ke atas">
                            <i class="ph-duotone ph-arrow-fat-lines-up fs-20"></i>
                        </button>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 p-0 pt-3">
                    <button type="button" class="btn btn-outline-light border-dashed waves-effect waves-light" data-bs-dismiss="modal" data-bs-toggle="tooltip" title="Tutup Cppt">
                        <i class="ph-duotone ph-x me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-subtle-warning" onclick="showModalCppt('{{ $list['KUNJUNGAN'] }}')" data-bs-toggle="tooltip" title="Refresh Data Riwayat CPPT">
                        <i class="ph-duotone ph-arrows-clockwise me-1"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // INIT VARIABLE
    const kunjungan = @json($list["KUNJUNGAN"]);
    const rm = @json($list["show"]->NORM);
    const tgl_sep = @json($list["show"]->TGLSEP);
    const sep = @json($list["show"]->NOSEP);
    const tgl_kfr = @json(now()->format('Y-m-d H:i:s'));
    const tgl_masuk = @json($list["show"]->MASUK);
    const tgl_keluar = @json($list['show']->KELUAR);
    const tgl_sep_date = tgl_sep?tgl_sep.substring(0, 10):null;

    let dataPPA = [];
    let ppaSelected=false;

    $(document).ready(function() {

        initCppt();

        // aktifkan saat pertama kali load
        aktifkanTabsDariHash();

        // console.log("{{ $list['tte_pegawai'] }}");
        if ("{{ $list['tte_pegawai'] }}" != true) {
            // kalau ada tanda tangan pegawai
            Swal.fire({
                title: `Tanda Tangan tidak ditemukan!`,
                text: 'Silakan mengisi/menambahkan tanda tangan di menu Profil Akun Pengguna sebelum melakukan pengisian pada halaman Elektronik Medical Record.',
                icon: `warning`,
                showConfirmButton: false,
                showCancelButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 3000,
                timerProgressBar: true,
                backdrop: `rgba(26,27,41,0.8)`,
            });
        }

        // TOMBOL KEMBALI
        $('[data-back-button]').on('click', function() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = "{{ route('v2.emr') }}"; // fallback ke klaim
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
        $('.nav-link').prop('disabled', false);

        // Jalankan setiap kali berpindah tab
        $('button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]').on(
            'shown.bs.tab',
            function (e) {

                const target = $(e.target).attr('data-bs-target') || $(e.target).attr('href');

                handleTabVisibility(target);
            }
        );

        loadRiwayatKunjunganPasien();

        $('.cppt-format').on('change', function () {
            if ($(this).is(':checked')) {
                $('.cppt-format').not(this).prop('checked', false);
            }
        });
    });

    function initCppt() {
        /* ============================================================
        CPPT INIT STARTED
        ============================================================ */
        const tanggalCppt = document.getElementById('cppt_tanggal');
        const jamCppt = document.getElementById('cppt_jam');

        if (tanggalCppt) {
            const now = new Date();
            const year =
                now.getFullYear();
            const month =
                String(now.getMonth() + 1).padStart(2, '0');
            const day =
                String(now.getDate()).padStart(2, '0');
            tanggalCppt.value =
                `${year}-${month}-${day}`;
        }

        if (jamCppt) {
            const now = new Date();
            jamCppt.value = now.toTimeString().substring(0, 5);
        }

        /* ============================================================
        DUMMY SIMPAN
        ============================================================ */
        const btnSimpanCppt = document.getElementById('btn-dummy-simpan-cppt');
        if (btnSimpanCppt) {
            btnSimpanCppt.addEventListener('click', function () {
                const original =
                    this.innerHTML;
                this.disabled = true;
                this.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Menyimpan...
                `;
                setTimeout(() => {
                    this.innerHTML = `
                        <i class="ph-duotone ph-check-circle me-1"></i>
                        Tersimpan
                    `;
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML =
                            original;
                    }, 1500);
                }, 800);
            });
        }

        /* ============================================================
        ANIMASI KLIK FAB
        ============================================================ */
        const btnFloatingCppt = document.getElementById('btn-floating-cppt');
        if (btnFloatingCppt) {
            btnFloatingCppt.addEventListener(
                'click',
                function () {
                    this.style.animation = 'none';
                    void this.offsetWidth;
                    this.style.animation = 'cpptFloating .45s ease';
                }
            );
        }
    }

    function showModalCppt(kunjungan){
        const $btnCppt=$('#btn-floating-cppt');
        $.ajax({
            url:"/api/v2/emr/cppt/"+kunjungan,
            type:'GET',
            dataType:'json',
            beforeSend:function(){
                $btnCppt.prop('disabled',true).find('i').removeClass('ph-duotone ph-notepad').addClass('ri-refresh-line ri-spin');
                $('#cppt_riwayat').empty().append(`<center><div class="spinner-border spinner-border-sm" role="status"></div></center>`);
            },
            success:function(res){
                if(!res){
                    iziToast.error({
                        title:'Maaf!',
                        message:'Data CPPT tidak ditemukan / belum diisi',
                        position:'topRight'
                    });
                    return;
                }

                // Init Scroll to Top
                const modalCpptBody = document.querySelector('#modalCPPT .modal-body');
                const btnCpptScrollTop = document.getElementById('btn-cppt-scroll-top');
                if (modalCpptBody && btnCpptScrollTop) {
                    modalCpptBody.addEventListener('scroll', function() {
                        if (this.scrollTop > 300) {
                            btnCpptScrollTop.classList.remove('d-none');
                        } else {
                            btnCpptScrollTop.classList.add('d-none');
                        }
                    });
                    btnCpptScrollTop.addEventListener('click', function() {
                        modalCpptBody.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                }

                dataPPA=(res.ppa||[]).filter(item=>item.ID&&item.NAMA);

                $('#cppt_ppa_autocomplete').hide().empty();
                $('#cppt_ppa').data('ppa',dataPPA);

                let header='';
                let riwayat='';

                header+=`
                    <div class="col-md-5">
                        <div class="small text-muted">Pasien</div>
                        <div class="fw-bold text-truncate">${res.namapasien??'-'}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted">No. RM</div>
                        <div class="fw-bold">${res.norm??'-'}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Kunjungan</div>
                        <div class="fw-bold">${kunjungan}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted">CPPT</div>
                        <span class="badge text-bg-primary">${res.count??0} Catatan</span>
                    </div>
                `;
                $('#cppt_header').empty().append(header);
                $('#cppt_count').text((res.count??0)+' Catatan');
                $('#btn-count-cppt').text(res.count??0);
                (res.show||[]).forEach(item=>{
                    let ic='ph-duotone ph-notepad';
                    let ic_col='primary';
                    if(item.JNSPPA=='Dokter'){
                        ic='ph-duotone ph-stethoscope';
                        ic_col='primary';
                    }else if(item.JNSPPA=='Paramedis'){
                        ic='ph-duotone ph-heartbeat';
                        ic_col='success';
                    }else if(item.JNSPPA=='Apoteker'){
                        ic='ph-duotone ph-pill';
                        ic_col='warning';
                    }else if(item.JNSPPA=='Nutrisionis'){
                        ic='ph-duotone ph-bowl-food';
                        ic_col='pink-500';
                    }else {
                        ic='ph-duotone ph-x';
                        ic_col='red-700';
                    }
                    riwayat += `<div class="cppt-timeline-item">
                                    <div class="cppt-timeline-marker bg-${ic_col}">
                                        <i class="${ic}"></i>
                                    </div>

                                    <div class="cppt-timeline-content">
                                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                            <div>
                                                <div class="fw-bold">${item.PPA ?? '-'}</div>
                                                <small class="text-muted">${item.JNSPPA ?? '-'}</small>
                                            </div>

                                            <span class="badge bg-primary-subtle text-primary">
                                                ${item.TANGGAL ?? '-'}
                                            </span>
                                        </div>

                                        <hr class="my-2">

                                        <div class="small lh-lg">
                                            ${item.CATATAN ?? ''}
                                        </div>

                                        <div class="small lh-lg">
                                            <b>I/ :</b> ${item.INSTRUKSI ?? '-'}
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-subtle-warning border border-warning text-warning-emphasis border-dashed"
                                                    data-bs-toggle="tooltip" title="Ubah Cppt"
                                                    onclick="editCPPT('${item.ID}')">
                                                <i class="ri-edit-line"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-subtle-danger border border-danger text-danger-emphasis border-dashed"
                                                    data-bs-toggle="tooltip" title="Hapus Cppt"
                                                    onclick="hapusCPPT('${item.ID}')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                });
                $('#cppt_riwayat').empty().append(riwayat||'<div class="text-center text-muted py-4">Belum ada catatan CPPT.</div>');

                $('#modalCPPT').modal('show');

                initAutocompletePPA();
            },
            error:function(xhr){
                let message='Data gagal ditampilkan.';
                if(xhr.status===422&&xhr.responseJSON?.errors){
                    message=Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }else if(xhr.responseJSON?.message){
                    message=xhr.responseJSON.message;
                }
                iziToast.error({
                    title:'Proses Gagal!',
                    message:message,
                    position:'topRight'
                });
            },
            complete:function(){
                $btnCppt.prop('disabled',false).find('i').removeClass('ri-refresh-line ri-spin').addClass('ph-duotone ph-notepad');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function initAutocompletePPA(){
        const $input=$('#cppt_ppa');
        const $container=$('#cppt_ppa_autocomplete');

        $input.on('input',function(){
            const keyword=$(this).val().trim().toLowerCase();

            // Setiap kali user mengetik ulang, pilihan sebelumnya dibatalkan
            ppaSelected=false;
            $('#cppt_ppa_id').val('');
            $('#cppt_ppa').removeData('nip');

            $container.empty();

            if(keyword.length<2){
                $container.hide();
                return;
            }

            const hasil=dataPPA.filter(item=>{
                const nama=(item.NAMA||'').toLowerCase();
                const nip=(item.NIP||'').toLowerCase();

                return nama.includes(keyword)||nip.includes(keyword);
            }).slice(0,10);

            if(!hasil.length){
                $container.html(`
                    <div class="list-group-item text-muted">
                        <i class="ph ph-magnifying-glass me-1"></i>
                        PPA tidak ditemukan
                    </div>
                `).show();

                return;
            }

            hasil.forEach(item=>{
                $container.append(`
                    <button type="button"
                            class="list-group-item list-group-item-action cppt-ppa-item text-start bg-body"
                            data-id="${item.ID}"
                            data-nip="${item.NIP??''}"
                            data-nama="${item.NAMA??''}">
                        <div class="fw-semibold">${item.NAMA??'-'}</div>
                        <small class="text-muted">
                            NIP: ${item.NIP??'-'}
                        </small>
                    </button>
                `);
            });

            $container.show();
        });

        $container.on('click','.cppt-ppa-item',function(){
            const id=$(this).data('id');
            const nip=$(this).data('nip');
            const nama=$(this).data('nama');

            $('#cppt_ppa_id').val(id);
            $('#cppt_ppa').val(nama);
            $('#cppt_ppa').data('nip',nip);

            // Tandai bahwa PPA valid sudah dipilih dari list
            ppaSelected=true;

            $container.hide().empty();
        });

        $input.on('blur',function(){
            setTimeout(function(){

                $container.hide();

                // Jika belum memilih PPA dari autocomplete,
                // maka input dianggap tidak valid
                if(!ppaSelected || !$('#cppt_ppa_id').val()){
                    $input.val('');
                    $('#cppt_ppa_id').val('');
                    $input.removeData('nip');

                    ppaSelected=false;
                }

            },200);
        });
    }

    function showICare() {
        const btn = $('#btn-icare');
        $.ajax({
            url: `/api/emr/bpjs/icare/${rm}`,
            type: 'GET',
            beforeSend: function () {
                btn.addClass('disabled')
                    .find('i')
                    .removeClass('fa-clipboard-list')
                    .addClass('fa-sync fa-spin');
            },
            success: function(res) {
                if (res.status) {
                    $('#show-icare').empty().append(`<iframe
                                                        src="${res.url}"
                                                        width="100%"
                                                        height="800"
                                                        frameborder="0">
                                                    </iframe>`);
                    $('#show-id-icare').empty().html('NO BPJS PESERTA : <b class="text-success">'+res.no_kartu+'</b>' ?? '');
                    $('#modalICare').modal('show');
                } else {
                    Swal.fire(
                        'Gagal',
                        res.message ?? 'Terjadi kegagalan saat menampilkan data pasien',
                        'error'
                    );
                }

            }, error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan saat memproses data',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.removeClass('disabled')
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-clipboard-list');
            }
        });
    }

    function loadRiwayatKunjunganPasien() { // RIWAYAT DI GRID KANAN
        const btn = $('#btn-refresh-riwayat-kunjungan');

        $.ajax({
            url: `/api/emr/riwayat/kunjungan/${rm}`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .addClass('fa-spin');
                $('#load-riwayat-kunjungan-pasien').empty().append(`
                    <li class="list-group-item"><center><i class="fas fa-sync fa-spin me-1"></i> Memuat Data Kunjungan</center></li>
                `);
            },
            success: function(res) {
                content = ``;

                res.show.forEach((item, index) => {

                    let statusdaftar = '';
                    let dokter = '...';

                    if (item.STATUSDAFTAR == 1) {
                        statusdaftar = '<badge class="badge badge-sm text-bg-success">Aktif</badge>';
                    } else if (item.STATUSDAFTAR == 2) {
                        statusdaftar = '<badge class="badge badge-sm text-bg-primary">Selesai</badge>';
                    } else {
                        statusdaftar = '<badge class="badge badge-sm text-bg-danger">Non Aktif/Batal</badge>';
                    }

                    if (item.NAMADOKTER) {
                        if (item.NAMADOKTER.length > 25) {
                            dokter = item.NAMADOKTER.substring(0,25) + '...';
                        } else {
                            dokter = item.NAMADOKTER;
                        }
                    }

                    content += `
                        <li class="list-group-item border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <button
                                        class="btn btn-sm btn-outline-${item.NOKUNJUNGAN == kunjungan? 'warning':'secondary'} border-dashed waves-effect waves-light flex-shrink-0 me-2"
                                        data-bs-toggle="tooltip" ${item.NOKUNJUNGAN == kunjungan?'disabled':''}
                                        title="Lihat Detail Kunjungan" ${item.NOKUNJUNGAN == kunjungan ? `` : `onclick="window.location.href='/v2/emr/${item.NOKUNJUNGAN}'"`}>
                                        <i class="ph-duotone ph-stethoscope fs-20"></i>
                                    </button>
                                </div>

                                <div class="flex-grow-1 mx-2">
                                    <h6 class="mb-0">${item.NAMARUANGAN} ${item.NOKUNJUNGAN == kunjungan? `<badge class="badge badge-sm text-bg-warning p-1">SAAT INI</badge>` : ``}</h6>
                                    <p class="mb-0">${dokter}</p>
                                </div>

                                <div class="flex-shrink-0">
                                    <p class="mb-0 text-end" style="font-size:12px" data-bs-toggle="tooltip" title="Status Kunjungan">
                                        Status : ${statusdaftar}
                                    </p>

                                    <span class="badge bg-secondary-subtle text-secondary"
                                        style="font-size:12px"
                                        data-bs-toggle="tooltip"
                                        title="Pasien didaftarkan ${dayjs(item.TGLDAFTAR).fromNow()}">
                                        ${dayjs(item.TGLDAFTAR).format('DD MMMM YYYY, HH:mm WIB')}
                                    </span>
                                </div>
                            </div>
                        </li>
                    `;
                });

                content += `</ul>`;

                $('#load-riwayat-kunjungan-pasien').empty().append(content);
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function loadRiwayatKfr',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-spin');

                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    // function aktifkanTabsDariHash() {
    //     const hash = window.location.hash; // contoh: #frehab#formlayanankfr
    //     if (!hash) return;

    //     // pecah jadi array ['frehab', 'formlayanankfr']
    //     const ids = hash.split('#').filter(Boolean);

    //     ids.forEach((id, index) => {
    //         const selector = '#' + id;
    //         const $tabBtn = $('[data-bs-target="' + selector + '"]');

    //         if ($tabBtn.length) {
    //             const tab = new bootstrap.Tab($tabBtn[0]);
    //             tab.show();

    //             // SHOWING TOP BUTTON & BREADCRUMB
    //             $("#btn-top-detail-kunjungan").prop('hidden', false);
    //             $("#breadcrumb-detail-kunjungan").prop('hidden', false);

    //             // jalankan validasi sesuai target
    //             if (selector === '#frehab' || selector === '#formlayanankfr') {
    //                 validPageFormKfr();
    //                 // console.log('jalan kfr');
    //             } else if (selector === '#formjadwalpelayanan') {
    //                 validPageFormJp();
    //                 // console.log('jalan jp');
    //             } else if (selector === '#formkonsulkfr') {
    //                 validPageFormKs();
    //                 // console.log('jalan ks');
    //             } else if (selector === '#fmrehab' || selector === 'frjkfr') {
    //                 // console.log('masuk form kfr');
    //                 loadFormKfr();
    //                 loadCpptKfr();
    //                 loadRiwayatKfr();
    //             } else if (selector === 'pterapi') {
    //                 // console.log('masuk form program terapi');
    //             } else if (selector === '#fpengkajian') {
    //                 console.log('MASUK PENGKAJIAN');
    //                 // HIDDEN TOP BUTTON & BREADCRUMB
    //                 $("#btn-top-detail-kunjungan").prop('hidden', true);
    //                 $("#breadcrumb-detail-kunjungan").prop('hidden', true);
    //             } else {
    //                 console.log('tab lain');
    //             }
    //         }
    //     });
    // }

    function aktifkanTabsDariHash() {

        const hash = window.location.hash;

        if (!hash) return;

        const ids = hash.split('#').filter(Boolean);

        ids.forEach((id) => {

            const selector = '#' + id;
            const $tabBtn = $('[data-bs-target="' + selector + '"], a[href="' + selector + '"]');

            if ($tabBtn.length) {

                const tab = new bootstrap.Tab($tabBtn[0]);

                tab.show();

                // Logic khusus lainnya tetap di sini
                if (selector === '#frehab' || selector === '#formlayanankfr') {

                    validPageFormKfr();

                } else if (selector === '#formjadwalpelayanan') {

                    validPageFormJp();

                } else if (selector === '#formkonsulkfr') {

                    validPageFormKs();

                } else if (selector === '#fmrehab' || selector === '#frjkfr') {

                    loadFormKfr();
                    loadCpptKfr();
                    loadRiwayatKfr();

                } else if (selector === '#pterapi') {

                    // Form program terapi

                }
            }
        });
    }

    function handleTabVisibility(target) { // HIDE BREADCRUMB & BACK / ICARE BUTTON

        if (target === '#fpengkajian') {
            // Form Pengkajian
            $('#btn-top-detail-kunjungan').prop('hidden', true);
            $('#breadcrumb-detail-kunjungan').prop('hidden', true);
        } else {
            // Tab lainnya
            $('#btn-top-detail-kunjungan').prop('hidden', false);
            $('#breadcrumb-detail-kunjungan').prop('hidden', false);
        }
    }
</script>
@endsection
