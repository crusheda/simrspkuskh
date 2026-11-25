<div class="row" id="form_resume_medis_ranap">
    <div class="col-md-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">

                {{-- HEADER KIRI --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="cppt-modal-icon bg-white bg-opacity-50 text-warning-emphasis">
                        <i class="ph-duotone ph-clipboard-text fs-5"></i>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0">
                                Formulir Resume Medis Rawat Inap
                            </h5>

                            <span class="badge bg-dark-subtle text-body-secondary"
                                id="badge_final_resume">
                                <i class="ri-loader-line ri-spin"></i>
                            </span>
                        </div>

                        <small class="text-body-secondary">
                            Finalisasi Resume Medis Rawat Inap
                        </small>
                    </div>
                </div>

                {{-- HEADER KANAN: INFO FINALISASI --}}
                <div id="info_final_resume" class="d-none text-end">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <i class="ri-shield-check-line text-success fs-5"></i>

                        <div>
                            <div class="fw-semibold text-success" id="info_final_oleh">
                                -
                            </div>

                            <small class="text-body-secondary" id="info_final_tanggal">
                                -
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                <fieldset id="resume_fields" class="border-0 p-0 m-0">

                    <div class="row">

                        {{-- INDIKASI RAWAT INAP --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Indikasi Rawat Inap
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="indikasi"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data"
                                    >
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="indikasi"
                                    rows="2">
                                </textarea>
                            </div>
                        </div>

                        {{-- RIWAYAT PENYAKIT --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Ringkasan Penyakit Sekarang (RPS)
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="rps"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data">
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="rps"
                                    rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Ringkasan Penyakit Dahulu
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="rpd"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data"
                                    >
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="rpd"
                                    rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Riwayat Pengobatan Medis
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="rpm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data"
                                    >
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="rpm"
                                    rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Riwayat Alergi
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="ra"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data">
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="ra"
                                    rows="2"></textarea>
                            </div>
                        </div>

                        {{-- PEMERIKSAAN FISIK --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <div class="resume-field-label mb-2">
                                    <label class="form-label mb-0 fw-semibold">
                                        Pemeriksaan Fisik
                                    </label>
                                    <button type="button"
                                        class="resume-info-btn"
                                        data-info-key="pemeriksaan_fisik"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeInfoModal"
                                        title="Informasi sumber data"
                                    >
                                        <i class="ph-duotone ph-info"></i>
                                    </button>
                                </div>
                                <textarea
                                    class="form-control"
                                    name="pemeriksaan_fisik"
                                    rows="9"></textarea>
                            </div>
                        </div>

                        {{-- DIAGNOSIS --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card card-body border border-dashed border-danger mb-0">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Diagnosa <span class="badge text-bg-info ms-1 me-1 p-1 py-0">Utama</span>
                                                    [<b class="text-danger">ICD 10</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_utama"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Diagnosa <span class="badge text-bg-warning ms-1 me-1 p-1 py-0">Sekunder</span>
                                                    [<b class="text-danger">ICD 10</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_sekunder"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Diagnosa
                                                    {{-- [<b class="text-danger">ICD 10</b>] --}}
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_dokter_icd10"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TINDAKAN --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card card-body border border-dashed border-success mb-0">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Tindakan / Prosedur Lainnya
                                                    [<b class="text-success">ICD 9</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_perawat"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Tindakan
                                                    {{-- [<b class="text-success">ICD 9</b>] --}}
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_dokter_icd9"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TERAPI --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Terapi dan Tindakan</h6>
                                <textarea
                                    class="form-control"
                                    name="terapi"
                                    rows="5"></textarea>
                            </div>
                        </div>

                        {{-- KONDISI KELUAR --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Kondisi Waktu Keluar RS</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $kondisiKeluar = [
                                            1 => 'Sembuh',
                                            2 => 'Membaik',
                                            3 => 'Belum Sembuh',
                                            4 => 'Meninggal <= 48 Jam',
                                            5 => 'Meninggal > 48 Jam',
                                            6 => 'DOA',
                                            7 => 'Rujuk RS Lain',
                                        ];
                                    @endphp

                                    @foreach ($kondisiKeluar as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="kondisi_keluar"
                                                id="kondisi_keluar_{{ $value }}"
                                                value="{{ $value }}">

                                            <label
                                                class="form-check-label"
                                                for="kondisi_keluar_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- CARA KELUAR --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Cara Keluar / Pasien Pulang</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $caraKeluar = [
                                            1 => 'Diijinkan Pulang',
                                            2 => 'Pulang Atas Permintaan Sendiri',
                                            3 => 'Dirujuk ke Rumah Sakit Lain',
                                            4 => 'Lari',
                                            // 5 => 'Pindah RS Lain',
                                            6 => 'Meninggal',
                                            7 => 'Dead On Arrival (DOA)',
                                            8 => 'Lain-lain',
                                        ];
                                    @endphp

                                    @foreach ($caraKeluar as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="cara_keluar"
                                                id="cara_keluar_{{ $value }}"
                                                value="{{ $value }}">

                                            <label
                                                class="form-check-label"
                                                for="cara_keluar_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- KONDISI UMUM PASIEN --}}
                        <div class="col-md-12 mb-3">

                            <h6>Kondisi Umum Pasien</h6>

                            <div class="row">

                                <div class="col-md-6">

                                    {{-- TEKANAN DARAH --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tekanan Darah</label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_td_up"
                                                min="0"
                                                step="1">
                                            <div class="input-group-text">
                                                /
                                            </div>
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_td_down"
                                                min="0"
                                                step="1">
                                            <div class="input-group-text">
                                                mmHg
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FREKUENSI NAFAS --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Frekuensi Nafas
                                        </label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_nafas"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                x/Menit
                                            </div>
                                        </div>
                                    </div>

                                    {{-- TINGKAT KESADARAN --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Tingkat Kesadaran
                                        </label>

                                        <select class="form-control" name="ku_tk" id="ku_tk"></select>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    {{-- FREKUENSI NADI --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Frekuensi Nadi
                                        </label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_nadi"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                x/Menit
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SUHU --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">Suhu</label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_suhu"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                °C
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SKALA NYERI --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Skala Nyeri
                                        </label>

                                        <input
                                            class="form-control"
                                            type="text"
                                            name="ku_sn">
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- LAYANAN LANJUTAN --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Layanan Lanjutan</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $layananLanjutan = [
                                            1 => 'Poliklinik RS',
                                            2 => 'RS Lain',
                                            3 => 'Puskesmas',
                                            4 => 'Dokter Luar',
                                            5 => 'Komunitas',
                                        ];
                                    @endphp

                                    @foreach ($layananLanjutan as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="layanan_lanjutan"
                                                id="layanan_lanjutan_{{ $value }}"
                                                value="{{ $value }}" @if ($value==1) checked @endif>

                                            <label
                                                class="form-check-label"
                                                for="layanan_lanjutan_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- EDUKASI --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6>Edukasi</h6>

                                <textarea
                                    class="form-control"
                                    name="edukasi"
                                    rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                </fieldset>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer d-flex justify-content-left align-items-center flex-wrap gap-2">
                <button type="button"
                        class="btn btn-subtle-primary"
                        id="btn_preview_resume_ranap">
                    <i class="ph-duotone ph-file-pdf me-1"></i>
                    Preview Resume
                </button>
                    {{-- <button
                        type="button"
                        class="btn btn-success"
                        id="btn_simpan_resume">

                        <i class="ph-duotone ph-floppy-disk me-2"></i>
                        Simpan
                    </button> --}}

                    <button
                        type="button"
                        class="btn btn-primary"
                        id="btn_final_resume">

                        <i class="ph-duotone ph-paper-plane-tilt me-2"></i>
                        Final Resume Medis
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger d-none"
                        id="btn_batal_final_resume">

                        <i class="ph-duotone ph-lock-open me-2"></i>
                        Batal Final
                    </button>

            </div>

        </div>
    </div>

    {{-- MODAL INFO INPUT --}}
    <div class="modal fade resume-info-modal"
        id="resumeInfoModal"
        tabindex="-1"
        aria-labelledby="resumeInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                {{-- HEADER --}}
                <div class="modal-header">
                    <div class="modal-title-wrapper">
                        <div class="modal-icon">
                            <i class="ph-duotone ph-info"></i>
                        </div>
                        <div>
                            <h5 class="modal-title"
                                id="resumeInfoModalLabel">
                                Informasi Isian
                            </h5>
                            <div class="modal-subtitle">
                                Sumber dan penjelasan data Resume Medis
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                {{-- BODY --}}
                <div class="modal-body">
                    {{-- SUMBER DATA --}}
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="ph-duotone ph-database"></i>
                            Sumber Data
                        </div>
                        <div id="resumeInfoSource"
                            class="source-badge">
                            <i class="ph-duotone ph-database"></i>
                            <span>Sumber Data</span>
                        </div>
                    </div>
                    {{-- PENJELASAN --}}
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="ph-duotone ph-text-align-left"></i>
                            Penjelasan
                        </div>
                        <p id="resumeInfoDescription"
                            class="info-description">
                            Informasi penjelasan akan ditampilkan di sini.
                        </p>
                    </div>
                    {{-- DETAIL --}}
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="ph-duotone ph-list-bullets"></i>
                            Bagaimana Data Diambil?
                        </div>
                        <p id="resumeInfoDetail"
                            class="info-description">
                            Detail proses pengambilan data akan ditampilkan di sini.
                        </p>
                    </div>
                    {{-- GAMBAR --}}
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="ph-duotone ph-images"></i>
                            Lokasi Pengambilan Data
                        </div>
                        <div id="resumeInfoImages"
                            class="info-images-container">

                            {{-- GAMBAR AKAN DI-RENDER DINAMIS DARI JAVASCRIPT --}}

                        </div>
                    </div>
                </div>
                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        <i class="ph ph-x me-1"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================
        RESUME RANAP PRINT PREVIEW MODAL
    ========================================================= -->
    <div class="modal fade resume-ranap-preview-modal"
        id="modal_preview_resume_ranap"
        tabindex="-1"
        aria-labelledby="modal_preview_resume_ranap_title"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-xxl">

            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header resume-ranap-preview-header">

                    <div class="resume-ranap-preview-header-left">

                        <div class="resume-ranap-preview-icon">
                            <i class="ph-duotone ph-file-pdf"></i>
                        </div>

                        <div class="resume-ranap-preview-header-info">

                            <h5 class="modal-title mb-0"
                                id="modal_preview_resume_ranap_title">
                                Print Preview Resume Medis
                            </h5>

                            <div class="resume-ranap-preview-subtitle">
                                Preview dokumen Resume Medis Rawat Inap
                            </div>

                        </div>

                    </div>

                    <!-- HEADER ACTIONS -->
                    <div class="resume-ranap-preview-actions">

                        <span id="resume_ranap_preview_status"
                            class="resume-ranap-preview-status badge bg-success-subtle text-success">

                            <i class="ph-duotone ph-check-circle me-1"></i>
                            Final

                        </span>

                        <!-- REFRESH -->
                        <button type="button"
                                class="btn btn-icon btn-light"
                                id="btn_refresh_preview_resume_ranap"
                                title="Refresh Preview">

                            <i class="ph-duotone ph-arrows-clockwise"></i>

                        </button>

                        <!-- PRINT -->
                        <button type="button"
                                class="btn btn-icon btn-light"
                                id="btn_print_preview_resume_ranap"
                                title="Print">

                            <i class="ph-duotone ph-printer"></i>

                        </button>

                        <!-- CLOSE -->
                        <button type="button"
                                class="btn btn-icon btn-light"
                                data-bs-dismiss="modal"
                                aria-label="Close">

                            <i class="ph-duotone ph-x"></i>

                        </button>

                    </div>

                </div>


                <!-- BODY -->
                <div class="modal-body resume-ranap-preview-body">

                    <!-- LOADING -->
                    <div id="resume_ranap_preview_loading"
                        class="resume-ranap-preview-loading">

                        <div class="resume-ranap-preview-loading-content">

                            <div class="spinner-border text-primary"
                                role="status">
                            </div>

                            <h6 class="mt-3 mb-1">
                                Menyiapkan dokumen
                            </h6>

                            <small class="text-muted">
                                Mohon tunggu, PDF Resume Medis Rawat Inap sedang dimuat...
                            </small>

                        </div>

                    </div>

                    <!-- ERROR -->
                    <div id="resume_ranap_preview_error"
                        class="resume-ranap-preview-error d-none">

                        <div class="resume-ranap-preview-error-content">

                            <div class="resume-ranap-preview-error-icon">
                                <i class="ph-duotone ph-warning-circle"></i>
                            </div>

                            <h5>
                                Preview tidak dapat dimuat
                            </h5>

                            <p id="resume_ranap_preview_error_message"
                                class="text-muted mb-3">
                                Terjadi kesalahan saat menampilkan dokumen Resume Medis Rawat Inap.
                            </p>

                            <button type="button"
                                class="btn btn-primary"
                                id="btn_retry_preview_resume_ranap">

                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                Coba Lagi

                            </button>

                        </div>

                    </div>

                    <!-- PDF VIEWER -->
                    <iframe id="resume_ranap_preview_iframe"
                            class="resume-ranap-preview-iframe"
                            title="Preview Resume Medis Rawat Inap"
                            src="about:blank">

                    </iframe>

                </div>

            </div>

        </div>

    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/v3/components/resume-ranap.css') }}">
    @endpush    
    @push('scripts')
        <script src="{{ asset('js/v3/components/resume-ranap.js') }}"></script>
    @endpush
@endonce