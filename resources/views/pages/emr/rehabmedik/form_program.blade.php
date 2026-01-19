<div class="row">
    <div class="col-md-8">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-contract me-1"></i> Program Terapi</h5>
                <a><i class="fas fa-exclamation-circle me-1"></i><i>Anda login sebagai <b><u class="text-primary">{{ Auth::user()->NAMA }}</u> (NIP#{{ Auth::user()->NIP }})</b></i></a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s_t" rows="3" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o_t" rows="3" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a_t" rows="3" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i><b>Procedure</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_p_t" rows="3" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row align-items-center justify-content-between g-3">
                    <div class="col-sm-auto">
                        <button class="btn btn-warning me-2" onclick="kosongiFormProgramTerapi()" id="btn-kosongi-form-program-terapi" hidden>
                            <i  class="fas fa-edit me-1"></i> Kosongkan Formulir
                        </button>
                        <button class="btn btn-danger me-2" onclick="deleteFormProgramTerapi()" id="btn-hapus-form-program-terapi" data-bs-toggle="tooltip" title="Hapus Formulir Program Terapi" hidden>
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-warning me-2" onclick="generateFormProgramTerapi()" id="btn-generate-form-program-terapi" data-bs-toggle="tooltip" title="Generate Ulang Formulir Program Terapi" hidden>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="btn btn-info me-2" onclick="showFormProgramTerapi()" id="btn-lihat-form-program-terapi" data-bs-toggle="tooltip" title="Lihat Berkas Formulir Program Terapi Saat Ini" hidden>
                            <i class="fas fa-book-open"></i>
                        </button>
                    </div>
                    <div class="col-sm-auto btn-page">
                        <button class="btn btn-primary" onclick="storeFormProgramTerapiBaru()" id="btn-simpan-form-program-Terapi" hidden>
                            <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                        </button>
                        <button class="btn btn-success" onclick="updateFormProgramTerapi()" id="btn-update-form-program-Terapi" data-bs-toggle="tooltip" title="Perbarui Formulir & TTE" hidden>
                            <i class="fas fa-edit me-1"></i> Update Formulir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="accordion card" id="collapse-riwayat-program-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-program-kfr">
                    <button class="accordion-button text-dark" style="background-color: #f3e3f5" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-program-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-alpha-down me-1"></i> Riwayat Program <span class="badge text-bg-dark ms-1">By.NORM</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-program-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-program-kfr" data-bs-parent="#collapse-riwayat-program-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-program-kfr">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow spinner-grow-sm me-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> <a class="align-middle">Memproses Data Riwayat..</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion card" id="collapse-riwayat-cppt-program-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-cppt-program-kfr">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-cppt-program-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-amount-down me-1"></i> Riwayat CPPT <span class="badge text-bg-dark ms-1">By.KUNJUNGAN</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-cppt-program-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-cppt-program-kfr" data-bs-parent="#collapse-riwayat-cppt-program-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-cppt-program-kfr">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow spinner-grow-sm me-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> <a class="align-middle">Mengambil Data Riwayat..</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
