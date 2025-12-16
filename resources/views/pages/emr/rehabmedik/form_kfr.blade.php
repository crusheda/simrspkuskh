<div class="row">
    <div class="col-md-8">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0">Formulir Rawat Jalan KFR</h5>
                <button class="btn btn-info btn-sm" onclick=""><i class="ph-duotone ph-file-search me-1"></i> Gunakan Form Lama</button>
            </div>
            <div class="card-body p-3 pb-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card table-card border shadow-none">
                            <div class="card-body p-3">
                                <div class="form-group">
                                    <label class="form-label"><i><b>Planning</b></i> <a class="text-danger">*</a></label>
                                    <textarea id="cppt_p_1" rows="2" class="form-control mb-3" placeholder="Goal of Treatment"></textarea>
                                    <textarea id="cppt_p_2" rows="2" class="form-control mb-3" placeholder="Tindakan/Program Rehabilitasi Medik"></textarea>
                                    <textarea id="cppt_p_3" rows="2" class="form-control mb-3" placeholder="Edukasi"></textarea>
                                    <textarea id="cppt_p_4" rows="2" class="form-control" placeholder="Frekuensi Kunjungan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 pt-0">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label"><b>Rencana Tindak Lanjut</b> <a class="text-danger">*</a></label>
                            <select class="form-select mb-2" id="cppt_i">
                                <option value="0">Pilih</option>
                                <option value="1">Evaluasi</option>
                                <option value="2">Rujuk</option>
                                <option value="3">Selesai</option>
                            </select>
                            <textarea id="cppt_i_rtl" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row align-items-center justify-content-between g-3">
                            <div class="col-sm-auto">
                                <button class="btn btn-warning me-2" onclick="kosongiFormKfr()">
                                    <i  class="fas fa-edit me-1"></i> Kosongkan Formulir
                                </button>
                                <button class="btn btn-secondary" onclick="showformKfrLama()" id="btn-form-lama" hidden>
                                    <i class="fab fa-wpforms me-1"></i> Lihat Formulir Yang Ada
                                </button>
                            </div>
                            <div class="col-sm-auto btn-page">
                                <button class="btn btn-primary" onclick="storeFormKfrBaru()">
                                    <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0">Riwayat Formulir</h5>
                <div class="dropdown">
                    <a class="avtar avtar-xs btn-light-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ph-duotone ph-dots-three-outline-vertical"></i></a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" href="javascript: void(0);"><s>Semua Formulir Pasien</s></a>
                        {{-- <a class="dropdown-item" href="#">Delete</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                ini row riwayat formulir
            </div>
        </div>
    </div>
</div>
