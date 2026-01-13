<div class="row">
    <div class="col-md-8">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-contract me-1"></i> Program Terapi</h5>
                <div>
                    <select class="form-select form-select-sm" id="jns_program_terapi">
                        <option value="0">Pilih Program Terapi</option>
                        <option value="1">Fisioterapi</option>
                        <option value="2">Okupasi Terapi</option>
                        <option value="3">Terapi Wicara</option>
                    </select>
                </div>
                {{-- <button class="btn btn-info btn-sm" onclick=""><i class="ph-duotone ph-file-search me-1"></i> Gunakan Form Lama</button> --}}
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s_t" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o_t" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a_t" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i><b>Procedure</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_p_t" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <center>Silakan memilih <b>Jenis Program Terapi</b> terlebih dahulu</center>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0">Riwayat Program</h5>
                <div class="dropdown">
                    <a class="avtar avtar-xs btn-light-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ph-duotone ph-dots-three-outline-vertical"></i></a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" href="javascript: void(0);"><s>Semua Program Terapi Pasien</s></a>
                        {{-- <a class="dropdown-item" href="#">Delete</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                ini row riwayat Program Terapi
            </div>
        </div>
    </div>
</div>
