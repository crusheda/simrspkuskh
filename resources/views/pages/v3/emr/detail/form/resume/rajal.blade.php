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
                            <label for="resume_rj_subjektif" class="form-label fw-semibold">Subjektif (<b class="text-danger">S</b>) - <i class="text-muted">View Only</i></label>
                            <textarea name="SUBYEKTIF" id="resume_rj_subjektif" class="form-control" rows="5" placeholder="..." readonly></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_objektif" class="form-label fw-semibold">Objektif (<b class="text-danger">O</b>) - <i class="text-muted">View Only</i></label>
                            <textarea name="OBYEKTIF" id="resume_rj_objektif" class="form-control" rows="5" placeholder="..." readonly></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_assessment" class="form-label fw-semibold">Assessment (<b class="text-danger">A</b>) - <i class="text-muted">View Only</i></label>
                            <textarea name="ASSESMENT" id="resume_rj_assessment" class="form-control" rows="4" placeholder="..." readonly></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_planning" class="form-label fw-semibold">Planning (<b class="text-danger">P</b>) - <i class="text-muted">View Only</i></label>
                            <textarea name="PLANNING" id="resume_rj_planning" class="form-control" rows="4" placeholder="..." readonly></textarea>
                        </div>
                        <div class="col-12">
                            <label for="resume_rj_instruksi" class="form-label fw-semibold">Instruksi (<b class="text-primary">I</b>) - <i class="text-muted">View Only</i></label>
                            <textarea name="INSTRUKSI" id="resume_rj_instruksi" class="form-control" rows="4" placeholder="..." readonly></textarea>
                        </div>
                    </div>
                </fieldset>
            </form>
            <div class="resume-rj-signature-section mt-4" id="resume_rj_signature_section">
                <div class="card border">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0">
                            <i class="ph-duotone ph-signature me-2"></i>
                            Tanda Tangan Elektronik Dokter
                        </h6>
                        <span id="resume_rj_tte_status" class="badge bg-secondary">
                            Belum ditandatangani
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- AREA TTE -->
                        <div class="resume-rj-signature-wrapper" id="resume_rj_signature_wrapper">
                            <!-- TTE YANG SUDAH TERSIMPAN -->
                            <div id="resume_rj_signature_saved" class="resume-rj-signature-saved d-none">
                                <img
                                    id="resume_rj_signature_preview"
                                    src=""
                                    alt="Tanda tangan dokter tersimpan"
                                >
                                <div class="resume-rj-signature-saved-info">
                                    <i class="ph ph-check-circle-fill me-1"></i>
                                    Tanda tangan elektronik telah tersimpan
                                </div>
                            </div>
                            <!-- CANVAS TTE BARU / UPDATE -->
                            <div id="resume_rj_signature_canvas_wrap" class="resume-rj-signature-canvas-wrap d-none">
                                <canvas
                                    id="canvas_signature_resume_rj"
                                    class="resume-rj-signature-canvas">
                                </canvas>
                            </div>
                            <!-- BELUM ADA TTE -->
                            <div id="resume_rj_signature_empty" class="resume-rj-signature-empty d-none">
                                <i class="ph-duotone ph-signature"></i>
                                <span>Belum ada tanda tangan elektronik</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small
                                id="resume_rj_signature_hint"
                                class="text-body-secondary d-block mt-2">
                                Tanda tangan elektronik dokter telah tersimpan.
                            </small>
                            <div
                                id="resume_rj_signature_saved_at"
                                class="small text-body-secondary mt-2">
                            </div>
                        </div>
                        <!-- BUTTON -->
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <!-- Update TTE -->
                            <button
                                type="button"
                                class="btn btn-subtle-primary d-none"
                                id="btn_update_signature_resume_rj">
                                <i class="ph-duotone ph-pencil-simple me-1"></i>
                                Perbarui TTE
                            </button>
                            <!-- Clear Canvas -->
                            <button
                                type="button"
                                class="btn btn-subtle-danger d-none"
                                id="btn_clear_signature_resume_rj">
                                <i class="ph-duotone ph-eraser me-1"></i>
                                Hapus Tanda Tangan
                            </button>
                            <!-- Cancel Update -->
                            <button
                                type="button"
                                class="btn btn-subtle-secondary d-none"
                                id="btn_cancel_signature_resume_rj">
                                <i class="ph-duotone ph-x me-1"></i>
                                Batal Perbarui
                            </button>
                        </div>
                        <!-- TTE META -->
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-start gap-2 mt-4" id="resume_rj_actions">
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
            <div class="modal-header resume-rj-preview-header d-flex justify-content-between align-items-center">
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
