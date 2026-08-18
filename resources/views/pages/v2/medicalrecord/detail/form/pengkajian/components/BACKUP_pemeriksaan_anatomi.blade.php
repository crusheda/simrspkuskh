<div class="row">
    {{-- PEMERIKSAAN MATA --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0">Mata : Anemis</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_anemis" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_anemis" value="1">
                        <label class="form-check-label"> Ada </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0">Ikterus</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_ikterus" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_ikterus" value="1">
                        <label class="form-check-label"> Ada </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0">Pupil</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_pupil" value="1" checked="">
                        <label class="form-check-label"> Isokor </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_pupil" value="2">
                        <label class="form-check-label"> Anisokor </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0"> Diameter Pupil </label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="number" class="form-control" name="pf_dia_up" value="0">
                        <div class="input-group-text"> mm / </div>
                        <input type="number" class="form-control" name="pf_dia_down" value="0">
                        <div class="input-group-text"> mm </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-auto">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0">Udem Palpebrae</label>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="pf_upal" value="0">
                    <label class="form-check-label"> Tidak Ada </label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="pf_upal" value="1">
                    <label class="form-check-label"> Ada , </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0 flex-shrink-0">Ada Kelainan Mata?</label>
                <select class="form-select form-select-sm" name="pf_kelainan_mata">
                    <option value="0">Tidak Diperiksa</option>
                    <option value="1">Tidak Ada</option>
                    <option value="2">Ada</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="mb-3">

    {{-- PEMERIKSAAN TENGGOROKAN --}}
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Tonsil</label>
                        <input type="text" class="form-control form-control-sm" name="pf_tonsil">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Faring</label>
                        <input type="text" class="form-control form-control-sm" name="pf_faring">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Lidah</label>
                        <input type="text" class="form-control form-control-sm" name="pf_lidah">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Bibir</label>
                        <input type="text" class="form-control form-control-sm" name="pf_bibir">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="mt-0">

    {{-- PEMERIKSAAN LEHER --}}
    <div class="col-md-12 mt-0">
        <div class="form-group mt-0 mb-3">
            <div class="d-flex align-items-center gap-2">
                <label class="form-label fw-bold flex-shrink-0">Jugular Venous Pressure (JVP)</label>
                <input type="text" class="form-control form-control-sm" name="pf_jvp">
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-0">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Pembesaran Kelenjar Limfe</label>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" data-target="#pf_pkl_lain" name="pf_pkl" value="0" checked="">
                    <label class="form-check-label">
                        Tidak Ada ,
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_pkl_lain" name="pf_pkl" value="1">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="pf_pkl_lain" id="pf_pkl_lain" disabled>
            </div>
        </div>
    </div>
    <div class="col mt-0">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Kaku Duduk</label>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" data-target="#pf_kd_lain" name="pf_kd" value="0" checked="">
                    <label class="form-check-label">
                        Tidak Ada ,
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_kd_lain" name="pf_kd" value="1">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="pf_kd_lain" id="pf_kd_lain" disabled>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-0">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0 flex-shrink-0">Ada Kelainan Leher?</label>
                <select class="form-select form-select-sm" name="pf_kelainan_leher">
                    <option value="0">Tidak Diperiksa</option>
                    <option value="1">Tidak Ada</option>
                    <option value="2">Ada</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="mt-0">

    {{-- PEMERIKSAAN DADA --}}
    <div class="col-md-12 mt-0">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Thoraks</label>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" data-target="#pf_thoraks_lain" name="pf_thoraks" value="1" checked="">
                    <label class="form-check-label">
                        Simetris
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_thoraks_lain" name="pf_thoraks" value="2">
                    <label class="form-check-label">
                        Asimetris
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="pf_thoraks_lain" id="pf_thoraks_lain" disabled>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-0">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Cor S1/S2 Irama</label>
                <input type="text" class="form-control form-control-sm" name="pf_cor">
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" name="pf_cor_cb" value="1">
                    <label class="form-check-label">
                        Reguler
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="pf_cor_cb" value="2">
                    <label class="form-check-label">
                        Ireguler
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Murmur</label>
                <input type="text" class="form-control form-control-sm" name="pf_murmur">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Lain-lain</label>
                <input type="text" class="form-control form-control-sm" name="pf_murmur_lain">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2">
                <label class="form-label fw-bold flex-shrink-0">Pulmo</label>
                <input type="text" class="form-control form-control-sm" name="pf_pulmo">
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Ronchi</label>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" data-target="#pf_ronchi_lain" name="pf_ronchi" value="1">
                    <label class="form-check-label">
                        Tidak Ada ,
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_ronchi_lain" name="pf_ronchi" value="2">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="pf_ronchi_lain" id="pf_ronchi_lain" disabled>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Wheezing</label>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox flex-grow-1" type="checkbox" data-target="#pf_wheezing_lain" name="pf_wheezing" value="1">
                    <label class="form-check-label">
                        Tidak Ada ,
                    </label>
                </div>
                <div class="form-check flex-shrink-0 m-0">
                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_wheezing_lain" name="pf_wheezing" value="2">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="pf_wheezing_lain" id="pf_wheezing_lain" disabled>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <label class="form-label fw-bold mb-0 flex-shrink-0">Ada Kelainan Dada?</label>
                <select class="form-select form-select-sm" name="pf_kelainan_dada">
                    <option value="0">Tidak Diperiksa</option>
                    <option value="1">Tidak Ada</option>
                    <option value="2">Ada</option>
                </select>
            </div>
        </div>
    </div>

    <hr>

    {{-- PEMERIKSAAN PERUT --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Abdomen : Distended</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_distended" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_distended" value="1">
                        <label class="form-check-label"> Ada </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Meteorismus</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_meteor" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_meteor" value="1">
                        <label class="form-check-label"> Ada </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Asites</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_asites" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_asites" value="1">
                        <label class="form-check-label"> Ada </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Peristaltik</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_peristal_normal">
                        <label class="form-check-label"> Normal </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_peristal_meningkat">
                        <label class="form-check-label"> Meningkat </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_peristal_menurun">
                        <label class="form-check-label"> Menurun </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_peristal_tidak">
                        <label class="form-check-label"> Tidak Ada </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Nyeri Tekan</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_nyeri_tekan_lain" name="pf_nyeri_tekan" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                        <div class="form-check flex-shrink-0 mb-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_nyeri_tekan_lain" name="pf_nyeri_tekan" value="1">
                            <label class="form-check-label">
                                Ada ,
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_nyeri_tekan_lain" id="pf_nyeri_tekan_lain" placeholder="Lokasi ?" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Hepar</label>
                <input type="text" class="form-control form-control-sm" name="pf_hepar">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Lien</label>
                <input type="text" class="form-control form-control-sm" name="pf_lien">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Extremitas</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_extremitas_hangat">
                        <label class="form-check-label"> Hangat </label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="pf_extremitas_dingin">
                        <label class="form-check-label"> Dingin </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Udem</label>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_udem_lain" name="pf_udem" value="0">
                        <label class="form-check-label"> Tidak </label>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                        <div class="form-check flex-shrink-0 mb-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_udem_lain" name="pf_udem" value="1">
                            <label class="form-check-label">
                                Ada ,
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_udem_lain" id="pf_udem_lain" placeholder="" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-bold flex-shrink-0">Lain-lain</label>
                <input type="text" class="form-control form-control-sm" name="pf_dada_lain">
            </div>
        </div>
    </div>
</div>
