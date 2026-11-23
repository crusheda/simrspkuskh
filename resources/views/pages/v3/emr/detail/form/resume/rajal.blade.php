{{-- Resume Medis Rawat Jalan V3 --}}
<div id="form_resume_medis_rajal" data-initialized="false" class="resume-rj-container">
    <div class="card border mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="cppt-modal-icon bg-white bg-opacity-50 text-warning-emphasis">
                    <i class="ph-duotone ph-clipboard-text fs-5"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-bold mb-0">
                            Formulir Resume Medis Rawat Jalan
                        </h5>
                        <span id="badge_final_resume_rj" class="badge bg-danger">Belum Final</span>
                    </div>
                    <small class="text-body-secondary">
                        Finalisasi Resume Medis Rawat Jalan (S/O/A/P/I)
                    </small>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div id="info_final_resume_rj" class="alert alert-success d-none mb-3">
                <div><strong>Resume telah difinalisasi</strong></div>
                <div>Oleh: <span id="info_final_oleh_rj">-</span></div>
                <div>Tanggal: <span id="info_final_tanggal_rj">-</span></div>
            </div>
            <form id="resume_rj_form" autocomplete="off">
                <fieldset id="resume_rj_fields">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="resume_rj_subjektif" class="form-label fw-semibold">Subjektif (S)</label>
                            <textarea name="SUBYEKTIF" id="resume_rj_subjektif" class="form-control" rows="5" placeholder="Keluhan utama, riwayat penyakit sekarang, riwayat penyakit dahulu, alergi, dan informasi subjektif lainnya..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_objektif" class="form-label fw-semibold">Objektif (O)</label>
                            <textarea name="OBYEKTIF" id="resume_rj_objektif" class="form-control" rows="5" placeholder="Pemeriksaan fisik, tanda vital, dan hasil pemeriksaan penunjang..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_assessment" class="form-label fw-semibold">Assessment (A)</label>
                            <textarea name="ASSESMENT" id="resume_rj_assessment" class="form-control" rows="4" placeholder="Diagnosis / masalah klinis..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_planning" class="form-label fw-semibold">Planning (P)</label>
                            <textarea name="PLANNING" id="resume_rj_planning" class="form-control" rows="4" placeholder="Rencana terapi, tindakan, pemeriksaan, dan tindak lanjut..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_instruksi" class="form-label fw-semibold">Instruksi (I)</label>
                            <textarea name="INSTRUKSI" id="resume_rj_instruksi" class="form-control" rows="4" placeholder="Instruksi kepada pasien, edukasi, dan anjuran..."></textarea>
                        </div>
                    </div>
                </fieldset>
            </form>

            <div class="resume-rj-signature-section mt-4">
                <div class="card border">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0"><i class="ph-duotone-duotone ph-signature me-2"></i>Tanda Tangan Elektronik Dokter</h6>
                        <span id="resume_rj_tte_status" class="badge bg-secondary">Belum ditandatangani</span>
                    </div>
                    <div class="card-body">
                        <div class="resume-rj-signature-wrapper" id="resume_rj_signature_wrapper">
                            <canvas id="canvas_signature_resume_rj" class="resume-rj-signature-canvas"></canvas>
                        </div>
                        <small class="text-body-secondary d-block mt-2">Tanda tangan pada area di atas menggunakan mouse atau layar sentuh.</small>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-subtle-danger" id="btn_clear_signature_resume_rj"><i class="ph-duotone ph-eraser me-1"></i>Hapus Tanda Tangan</button>
                        </div>
                        <div id="resume_rj_signature_preview_wrap" class="mt-3 d-none">
                            <div class="border rounded p-2 d-inline-block">
                                <img id="resume_rj_signature_preview" alt="Tanda tangan dokter tersimpan" style="max-width:280px;max-height:130px">
                            </div>
                            <div class="small text-body-secondary mt-1" id="resume_rj_signature_saved_at"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-subtle-secondary" id="btn_print_resume_rj"><i class="ph-duotone ph-printer me-1"></i>Print Preview</button>
                <button type="button" class="btn btn-primary" id="btn_final_resume_rj"><i class="ph-duotone ph-check-circle me-1"></i>Finalisasi Resume</button>
                <button type="button" class="btn btn-subtle-warning d-none" id="btn_batal_final_resume_rj"><i class="ph-duotone ph-arrow-counter-clockwise me-1"></i>Batal Finalisasi</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     RESUME RJ PRINT PREVIEW MODAL
========================================================= -->
<div class="modal fade resume-rj-preview-modal"
     id="modal_preview_resume_rj"
     tabindex="-1"
     aria-labelledby="modal_preview_resume_rj_title"
     aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header resume-rj-preview-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="resume-rj-preview-icon">
                        <i class="ph-duotone ph-file-pdf"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0"
                            id="modal_preview_resume_rj_title">
                            Print Preview Resume Medis
                        </h5>
                        <div class="resume-rj-preview-subtitle">
                            Preview dokumen Resume Medis Rawat Jalan
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="resume_rj_preview_status"
                          class="badge bg-success-subtle text-success">
                        <i class="ph-duotone ph-check-circle me-1"></i>
                        Final
                    </span>
                    <button type="button"
                            class="btn btn-icon btn-light"
                            id="btn_refresh_preview_resume_rj"
                            title="Refresh Preview">
                        <i class="ph-duotone ph-arrows-clockwise"></i>
                    </button>
                    <button type="button"
                            class="btn btn-icon btn-light"
                            id="btn_print_preview_resume_rj"
                            title="Print">
                        <i class="ph-duotone ph-printer"></i>
                    </button>
                    <button type="button"
                            class="btn btn-icon btn-light"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        <i class="ph-duotone ph-x"></i>
                    </button>
                </div>
            </div>
            <!-- BODY -->
            <div class="modal-body resume-rj-preview-body">
                <!-- LOADING -->
                <div id="resume_rj_preview_loading"
                     class="resume-rj-preview-loading">
                    <div class="resume-rj-preview-loading-content">
                        <div class="spinner-border text-primary" role="status"></div>
                        <h6 class="mt-3 mb-1">
                            Menyiapkan dokumen
                        </h6>
                        <small class="text-muted">
                            Mohon tunggu, PDF Resume Medis sedang dimuat...
                        </small>
                    </div>
                </div>
                <!-- ERROR -->
                <div id="resume_rj_preview_error"
                     class="resume-rj-preview-error d-none">
                    <div class="resume-rj-preview-error-content">
                        <i class="ph-duotone ph-warning-circle"></i>
                        <h5>Preview tidak dapat dimuat</h5>
                        <p class="text-muted mb-3">
                            Terjadi kesalahan saat menampilkan dokumen Resume Medis.
                        </p>
                        <button type="button"
                                class="btn btn-primary"
                                id="btn_retry_preview_resume_rj">
                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                            Coba Lagi
                        </button>
                    </div>
                </div>
                <!-- PDF VIEWER -->
                <iframe id="resume_rj_preview_iframe"
                        class="resume-rj-preview-iframe"
                        title="Preview Resume Medis"
                        src="about:blank">
                </iframe>
            </div>
        </div>
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/v3/components/resume-rajal.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('js/v3/components/resume-rajal.js') }}"></script>
    @endpush
@endonce
