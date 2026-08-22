<div class="row">
    <div class="col-md-12 mb-1">
        <div class="form-group">
            <h5 class="border-bottom pb-2 text-bold">
                <strong>PSIKOLOGI - SOSIAL - EKONOMI - SPIRITUAL - FUNGSIONAL </strong>
            </h5>
        </div>
    </div>
    <div class="col-md-12 mb-2">
        <div class="row">
            <label class="form-label fw-bold">Status Psikologi</label>
            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="tak">
                    <label class="form-check-label" for="checkPrimary"> Tidak ada kelainan </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="marah">
                    <label class="form-check-label" for="checkPrimary"> Marah </label>
                </div>
            </div>
            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="cemas">
                    <label class="form-check-label" for="checkPrimary"> Cemas </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="takut">
                    <label class="form-check-label" for="checkPrimary"> Takut </label>
                </div>
            </div>
            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="sedih">
                    <label class="form-check-label" for="checkPrimary"> Sedih </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="bundir">
                    <label class="form-check-label" for="checkPrimary"> Kecenderungan bunuh diri </label>
                </div>
            </div>
            <div class="col">
                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                    <div class="form-check mb-0">
                        <input class="form-check-input check-primary" type="checkbox" name="psel">
                        <label class="form-check-label">
                            Lainnya
                        </label>
                    </div>
                    <input type="text" class="form-control form-control-sm flex-grow-1" name="pse_lain">
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-md-12 mb-2">
        <label class="form-label fw-bold">Status Mental</label>
        <div class="form-check mb-2">
            <input class="form-check-input single-checkbox" type="checkbox" name="sm" value="1">
            <label class="form-check-label">
                Sadar dan orientasi baik
            </label>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2 flex-grow-1">
            <div class="form-check mb-0 flex-shrink-0">
                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sm" value="2">
                <label class="form-check-label">
                    Ada masalah perilaku
                </label>
            </div>
            <input type="text" class="form-control form-control-sm flex-grow-1" name="sm2_lain">
        </div>
        <div class="d-flex align-items-center gap-2 mb-2 flex-grow-1">
            <div class="form-check mb-0 flex-shrink-0">
                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sm" value="3">
                <label class="form-check-label">
                    Perilaku kekerasan yang dialami pasien sebelumnya
                </label>
            </div>
            <input type="text" class="form-control form-control-sm flex-grow-1" name="sm3_lain">
        </div>
    </div>
    <hr>
    <div class="col-md-12 mb-2">
        <label class="form-label fw-bold">Hubungan Sosial</label>
        <div class="row align-items-center">
            <label class="col-md-3 col-form-label">Hubungan Pasien dan anggota keluarga</label>
            <div class="col-md-9">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hub" value="1">
                    <label class="form-check-label">
                        Baik
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hub" value="2">
                    <label class="form-check-label">
                        Tidak baik
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Pasien tinggal di</label>
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="1">
                    <label class="form-check-label">
                        Rumah
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="2">
                    <label class="form-check-label">
                        Panti
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="3">
                    <label class="form-check-label">
                        Lainnya
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="text" class="form-control form-control-sm" name="tinggal_lain" placeholder="Sebutkan">
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-md-12 mb-1">
        <label class="form-label fw-bold">Hubungan Spiritual</label>
        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Agama</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="agama" placeholder="Otomatis terisi oleh sistem" value="{{ $list['pasien']->AGAMA ?? '' }}" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Kebiasaan beribadah teratur</label>
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="kbt" value="1">
                    <label class="form-check-label">
                        Ya
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="kbt" value="2">
                    <label class="form-check-label">
                        Tidak
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Nilai-nilai Kepercayaan</label>
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="nk" value="1">
                    <label class="form-check-label">
                        Tidak ada
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="nk" value="2">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="text" class="form-control form-control-sm" name="nk_lain" placeholder="Sebutkan">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Pengambil keputusan dalam keluarga</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="pk">
            </div>
        </div>
    </div>
    <hr>
    <div class="col-md-12 mb-1">
        <label class="form-label fw-bold">Ekonomi</label>
        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Pekerjaan</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="kerja" value="{{ $list['pasien']->PEKERJAAN ?? '' }}" placeholder="Otomatis terisi oleh sistem" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Penghasilan per bulan</label>
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="1">
                    <label class="form-check-label">
                        < Rp. 5.000.000
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="2">
                    <label class="form-check-label">
                        Rp. 5.000.000 - Rp. 10.000.000
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="3">
                    <label class="form-check-label">
                        > Rp. 10.000.000
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
