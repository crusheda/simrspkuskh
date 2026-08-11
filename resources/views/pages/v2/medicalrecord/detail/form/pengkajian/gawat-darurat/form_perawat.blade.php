<div class="form-wrapper">
    <div class="form-content">
        <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN KEPERAWATAN</center></h1>
        <div class="row">
            <div class="col-md-12">
                <h6 class="mb-2 flex-shrink-0">Cara Kedatangan</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="1">
                                <label class="form-check-label ms-1">
                                    Datang sendiri, diantar oleh
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="dd_ck_p" placeholder="Masukkan Nama Pengantar">
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="2">
                                <label class="form-check-label ms-1">
                                    Rujukan dari
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="dd_ck_k" id="dd_ck_k" placeholder="Tuliskan Asal Rujukan">
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="3">
                                <label class="form-check-label ms-1">
                                    Dikirim oleh Polisi dari
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="dd_ck_a" placeholder="Masukkan Unit Kepolisian">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input check-primary" type="checkbox" name="dd_ck_a_v" >
                                <label class="form-check-label ms-1">
                                    Disertai permintaan visum et repertum
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="mb-0 flex-shrink-0">
                                <label class="form-label">Tgl. Kedatangan</label>
                            </div>
                            <input type="datetime-local" class="form-control form-control-sm" name="tgl_ck">
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="mb-0 flex-shrink-0">
                                <label class="form-label">Alat Transportasi</label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="tr_ck">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Jenis Kasus</h6>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jks" value="1">
                                    <label class="form-check-label fw-bold"> Trauma </label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_kll">
                                    <label class="form-check-label"> Kecelakaan Lalu Lintas </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_kk">
                                    <label class="form-check-label"> Kecelakaan Kerja </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_uppa">
                                    <label class="form-check-label"> Kasus Perempuan & Anak (UPPA) </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jks" value="0">
                                    <label class="form-check-label fw-bold"> Non Trauma </label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input check-primary" type="checkbox" name="jks_end">
                                        <label class="form-check-label"> Riwayat ke Daerah Endemis </label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="jks_end_dm" placeholder="Dimana ?">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Resiko penularan infeksi</h6>
                    <div class="row">
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="1">
                                <label class="form-check-label"> Batuk > 2 minggu dengan demam dan sesak nafas </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="2">
                                <label class="form-check-label"> Rujukan dengan suspek (konfirmasi) airbone disease </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="3">
                                <label class="form-check-label"> Tidak berisiko penularan airbone disease </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="4">
                                <label class="form-check-label"> B - 20 </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Anamnesis</h6>
                <div class="row">
                    <div class="col">
                        <div class="form-group mb-2">
                            <label class="form-label"> Keluhan Utama </label>
                            <input class="form-control form-control-sm" type="text" name="anm_ku">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label"> Terpimpin </label>
                            <input class="form-control form-control-sm" type="text" name="anm_tp">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Tanda Vital</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-3">
                            <h6>Keadaan Umum</h6>
                            <div class="form-group">
                                <input type="text" class="form-control" name="p_keu" placeholder="">
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <h6 class="mb-0">Jalan Nafas ( <b class="text-warning">A</b> )</h6>
                            <div class="form-check m-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_jn" value="1">
                                <label class="form-check-label"> Paten </label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_jn" value="2">
                                <label class="form-check-label"> Obstruksi Parsial </label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_jn" value="3">
                                <label class="form-check-label"> Obstruksi Total </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Pernafasan ( <b class="text-warning">B</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nafas</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group input-group-sm flex-grow-1">
                                    <input type="number" class="form-control" name="p_fr">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="p_fr_cb" value="1" checked="">
                                    <label class="form-check-label">
                                        Simetris
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="p_fr_cb" value="2">
                                    <label class="form-check-label">
                                        Asimetris
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Sirkulasi ( <b class="text-warning">C</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nadi</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group input-group-sm flex-grow-1">
                                    <input type="number" class="form-control" name="p_nadi">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="p_fr_nadi" value="1" checked="">
                                    <label class="form-check-label">
                                        Reguler
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="p_fr_nadi" value="2">
                                    <label class="form-check-label">
                                        Ireguler
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="p_td_up">
                                <div class="input-group-text"> / </div>
                                <input type="number" class="form-control" name="p_td_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Suhu</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="p_suhu">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SpO2</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="p_spo2">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Neorologi ( <b class="text-warning">D</b> )</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Tingkat Kesadaran</label>
                                    <select class="form-control" name="p_tks">
                                        <option value="">Pilih</option>
                                        @if ($list['tingkat_kesadaran'])
                                            @foreach ($list['tingkat_kesadaran'] as $item)
                                                <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center column-gap-5 row-gap-3 flex-wrap mb-3">
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <label class="form-label mb-0">Pupil</label>
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_pupil" value="1" checked="">
                                            <label class="form-check-label"> Isokor </label>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_pupil" value="2">
                                            <label class="form-check-label"> Anisokor </label>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <label class="form-label mb-0"> Diameter Pupil </label>
                                        <div class="input-group input-group-sm" style="width: 180px;">
                                            <input type="number" class="form-control" name="p_dia_up">
                                            <div class="input-group-text"> mm / </div>
                                            <input type="number" class="form-control" name="p_dia_down">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="form-label mb-0 flex-shrink-0">
                                            RC (Refleks Cahaya)
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check mb-0">
                                                <input
                                                    class="form-check-input single-checkbox"
                                                    type="checkbox"
                                                    name="p_rc_up"
                                                    value="+"
                                                    id="p_rc_up_plus"
                                                >
                                                <label class="form-check-label" for="p_rc_up_plus">
                                                    +
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input
                                                    class="form-check-input single-checkbox"
                                                    type="checkbox"
                                                    name="p_rc_up"
                                                    value="-"
                                                    id="p_rc_up_minus"
                                                >
                                                <label class="form-check-label" for="p_rc_up_minus">
                                                    -
                                                </label>
                                            </div>
                                        </div>
                                        <span class="text-danger fw-bold">/</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check mb-0">
                                                <input
                                                    class="form-check-input single-checkbox"
                                                    type="checkbox"
                                                    name="p_rc_down"
                                                    value="+"
                                                    id="p_rc_down_plus"
                                                >
                                                <label class="form-check-label" for="p_rc_down_plus">
                                                    +
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input
                                                    class="form-check-input single-checkbox"
                                                    type="checkbox"
                                                    name="p_rc_down"
                                                    value="-"
                                                    id="p_rc_down_minus"
                                                >
                                                <label class="form-check-label" for="p_rc_down_minus">
                                                    -
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <label class="form-label mb-0"> RC (Refleks Cahaya) </label>
                                        <div class="input-group input-group-sm" style="width: 130px;">
                                            <input type="number" class="form-control" name="rc_up">
                                            <div class="input-group-text"> / </div>
                                            <input type="number" class="form-control" name="rc_down">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">GCS (<i>Glasgow Coma Scale</i>)</label>
                                    <div class="d-flex align-items-center column-gap-3 row-gap-3 flex-wrap mb-3">
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <label class="form-check-label">Eye</label>
                                            <input type="number" class="form-control form-control-sm" name="p_gcs_e" min="1" max="4" style="width: 70px; flex: 0 0 60px;" placeholder="">
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <label class="form-check-label">Verbal</label>
                                            <input type="number" class="form-control form-control-sm" name="p_gcs_v" min="1" max="5" style="width: 70px; flex: 0 0 60px;" placeholder="">
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <label class="form-check-label">Move</label>
                                            <input type="number" class="form-control form-control-sm" name="p_gcs_m" min="1" max="6" style="width: 70px; flex: 0 0 60px;" placeholder="">
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <label class="form-check-label">Total</label>
                                            <input type="number" class="form-control form-control-sm" name="p_gcs_t" style="width: 70px; flex: 0 0 60px;" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">VAS (<i>Visual Analog Scale</i>)</label>
                                    <input type="number" class="form-control" name="p_vas">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <h6>Alat Bantu Nafas</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_abn" value="2">
                                                    <label class="form-check-label"> Ya </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_abn" value="0">
                                                    <label class="form-check-label"> Tidak </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6>Kulit</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="1">
                                                    <label class="form-check-label"> Normal </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="2">
                                                    <label class="form-check-label"> Jaundice </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="3">
                                                    <label class="form-check-label"> Akral Dingin </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="4">
                                                    <label class="form-check-label"> Sianotik </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="5">
                                                    <label class="form-check-label"> Berkeringat </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <h6 class="mb-0">Status Reproduksi</h6>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="p_sr" value="1" checked="">
                                <label class="form-check-label" for="sr2">
                                    Tidak
                                </label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="p_sr" value="2">
                                <label class="form-check-label" for="sr1">
                                    Kasus Obstetri Ginekologi
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_sr_cb" value="2">
                                        </div>
                                        <label class="form-label mb-0 me-2">
                                            HPHT
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="p_sr_hpht">
                                        </div>
                                        <label class="form-label mb-0 ms-2 me-2">
                                            Siklus
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="p_sr_siklus">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_sr_cb" value="3">
                                        </div>
                                        <label class="form-label mb-0 me-2" for="kb">
                                            KB
                                        </label>
                                        <input type="text" class="form-control" name="p_sr_kb">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_sr_cb" value="1">
                                        </div>
                                        <label class="form-label mb-0 me-2">
                                            Hamil
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Gravida</span>
                                            <input type="text" class="form-control" name="p_sr_grv">
                                            <span class="input-group-text">Paritas</span>
                                            <input type="text" class="form-control" name="p_sr_prt">
                                            <span class="input-group-text">Abortus</span>
                                            <input type="text" class="form-control" name="p_sr_abr">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="p_sr_cb" value="0">
                                        </div>
                                        <label class="form-label mb-0">
                                            Tidak Hamil
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="tv_up">
                                <div class="input-group-text"> / </div>
                                <input type="number" class="form-control" name="tv_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nafas (RR)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="tv_fr">
                                <span class="input-group-text">X/menit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nadi (HR)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="tv_nadi">
                                <div class="input-group-text">X/menit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Suhu (OC)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="tv_sh">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Skala Nyeri</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="tv_sn">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Metode Ukur</label>
                            <input type="text" class="form-control" name="tv_mu">
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="col-md-12 mb-3">
                <h6>Khusus Obgyn</h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Usia Gestasi</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="ko_ug">
                                <div class="input-group-text">Minggu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Kontrasi Uterus</label>
                            <input type="text" class="form-control" name="ko_ku">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Detak Jantung Janin</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="ko_dj">
                                <div class="input-group-text">X/menit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Dilatasi Serviks</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="ko_ds">
                                <div class="input-group-text">cm</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Kebutuhan Khusus</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label class="form-label">Airbone</label>
                            <input type="text" class="form-control" name="kk_a">
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label class="form-label">Dekontaminan</label>
                            <input type="text" class="form-control" name="kk_d">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary mb-3">
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
                                    <input type="text" class="form-control form-control-sm" name="agama" placeholder="Otomatis terisi oleh sistem">
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
                                    <input type="text" class="form-control form-control-sm" name="kerja" placeholder="Otomatis terisi oleh sistem">
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
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-success mb-3">
                    <h6 class="mb-3">SKRINING NYERI</h6>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <div class="row align-items-center">
                                <label class="col-md-4 col-form-label fw-bold">Nyeri</label>
                                <div class="col-md-8">
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="1">
                                            <label class="form-check-label">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="0" checked>
                                            <label class="form-check-label">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row align-items-center">
                                <label class="col-md-2 col-form-label fw-bold">Onset</label>
                                <div class="col-md-10">
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_onset" value="1">
                                            <label class="form-check-label" for="onsetAkut">Akut</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox"name="sn_onset" value="2">
                                            <label class="form-check-label" for="onsetKronis">Kronis</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <div class="row align-items-center">
                                <label class="col-md-4 col-form-label fw-bold">Skala Nyeri</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="sn_skala" placeholder="Otomatis terisi" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row align-items-center">
                                <label class="col-md-2 col-form-label fw-bold">Metode</label>
                                <div class="col-md-10">
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="1">
                                            <label class="form-check-label">NRS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="2">
                                            <label class="form-check-label">BPS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="3">
                                            <label class="form-check-label">NIPS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="4">
                                            <label class="form-check-label">FLACC</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="5">
                                            <label class="form-check-label">VAS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div id="tampil_sn_nrs" hidden>
                            <img src="{{ asset('images/erm/skrining_nyeri_nrs.png') }}"
                                alt="Indikator Penilaian Nyeri"
                                class="img-fluid mb-3"
                                style="width: 25rem;">
                            <div class="form-group">
                                <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                                <input type="range" class="form-range" min="0" max="10" step="1" value="0" name="sn_nrs" id="sn_nrs">
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span>Interpretasi Skor Nyeri VAS :</span>
                                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                    <li>0 = tidak nyeri</li>
                                    <li>1-3 = nyeri ringan</li>
                                    <li>4-6 = nyeri sedang</li>
                                    <li>7-10 = nyeri berat</li>
                                </ul>
                            </div>
                        </div>
                        <div id="tampil_sn_bps" hidden>
                            <div class="table-responsive">
                                <table class="table table-bordered table-display">
                                    <thead class="text-uppercase">
                                        <tr class="table-light">
                                            <th class="text-center">Indikator Penilaian</th>
                                            <th class="text-center">Keterangan Skor</th>
                                            <th class="text-center">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>1 = rileks</h6>
                                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_bps_1" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>1 = rileks</h6>
                                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_bps_2" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>1 = rileks</h6>
                                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_bps_3" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span>Interpretasi Skor BPS :</span>
                                                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                                        <li>3 = tidak nyeri</li>
                                                        <li>4-6 = nyeri ringan</li>
                                                        <li>7-9 = nyeri sedang</li>
                                                        <li>10-12 = nyeri berat</li>
                                                    </ul>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="tampil_sn_nips" hidden>
                            <div class="table-responsive">
                                <table class="table table-bordered table-display">
                                    <thead class="text-uppercase">
                                        <tr class="table-light">
                                            <th class="text-center">Indikator Penilaian</th>
                                            <th class="text-center">Keterangan Skor</th>
                                            <th class="text-center">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>0 = Relaksasi</h6>
                                                <h6>1 = Meringis</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_1" class="form-control form-control-sm mx-auto"  min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Tangisan</th>
                                            <td>
                                                <h6>0 = Tidak menangis</h6>
                                                <h6>1 = Meringis</h6>
                                                <h6>2 = Menangis kuat</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Gerakan Lengan</th>
                                            <td>
                                                <h6>0 = Relaksasi</h6>
                                                <h6>1 = Fleksi / ekstensi</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_3" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Gerakan Tungkai</th>
                                            <td>
                                                <h6>0 = relaksasi</h6>
                                                <h6>1 = Fleksi / ekstensi</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_4" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Status Terjaga</th>
                                            <td>
                                                <h6>0 = Tidur / bangun</h6>
                                                <h6>1 = Rewel</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_5" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Pola Nafas</th>
                                            <td>
                                                <h6>0 = Relaksasi</h6>
                                                <h6>1 = Perubahan pola nafas</h6>
                                            </td>
                                            <td class="text-center"><input type="number" name="sn_nips_6" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span>Keterangan :</span>
                                                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                                        <li>> 3 = Nyeri</li>
                                                        <li>≤ 3 = Tidak Nyeri</li>
                                                    </ul>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="tampil_sn_flacc" hidden>
                            <div class="table-responsive">
                                <table class="table table-bordered table-display">
                                    <thead class="text-uppercase">
                                        <tr class="table-light">
                                            <th class="text-center">Indikator</th>
                                            <th class="text-center">0</th>
                                            <th class="text-center">1</th>
                                            <th class="text-center">2</th>
                                            <th class="text-center">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Wajah</th>
                                            <td>Tersenyum / tidak ada ekspresi khusus</td>
                                            <td>Terkadang meringis / menarik diri</td>
                                            <td>Sering menggetarkan dagu dan mengatupkan rahang</td>
                                            <td class="text-center"><input type="number" name="sn_flacc_1" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Kaki</th>
                                            <td>Gerakan normal / relaksasi</td>
                                            <td>Tidak tenang</td>
                                            <td>Kaki dibuat menendang / menarik diri</td>
                                            <td class="text-center"><input type="number" name="sn_flacc_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Aktivitas</th>
                                            <td>Tidur, posisi normal mudah bergerak</td>
                                            <td>Gerakan menggeliat, berguling, kaku</td>
                                            <td>Melengkungkan punggung / kaku / menghentak</td>
                                            <td class="text-center"><input type="number" name="sn_flacc_3" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Menangis</th>
                                            <td>Tidak menangis (bangung / tidur)</td>
                                            <td>Mengerang, merengek-rengek</td>
                                            <td>Menangis terus-menerus, terisak, menjerit</td>
                                            <td class="text-center"><input type="number" name="sn_flacc_4" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Bersuara</th>
                                            <td>Bersuara normal, tenang</td>
                                            <td>Tenang bila dipeluk, digendong, atau diajak bicara</td>
                                            <td>Sulit untuk ditenangkan</td>
                                            <td class="text-center"><input type="number" name="sn_flacc_5" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="5">Interpretasi : Skor total dari lima parameter di atas menentukan tingkat keparahan nyeri dengan skala 0 - 10. Nilai 10 menunjukan tingkat nyei yang hebat.</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="tampil_sn_vas" hidden>
                            <img src="{{ asset('images/erm/skrining_nyeri_vas.png') }}"
                                alt="Indikator Penilaian Nyeri"
                                class="img-fluid mb-3"
                                style="width: 25rem;">
                            <div class="form-group">
                                <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                                <input type="range" class="form-range" min="0" max="10" step="1" value="0" name="sn_vas" id="sn_vas">
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span>Interpretasi Skor Nyeri VAS :</span>
                                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                    <li>0 = tidak nyeri</li>
                                    <li>1-3 = nyeri ringan</li>
                                    <li>4-6 = nyeri sedang</li>
                                    <li>7-10 = nyeri berat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-md-2 col-form-label fw-bold">Pencetus</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" name="sn_pencetus" placeholder="[ Pencetus ]">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-md-2 col-form-label fw-bold">Gambaran</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" name="sn_gambaran" placeholder="[ Gambaran ]">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-md-2 col-form-label fw-bold">Durasi</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" name="sn_durasi" placeholder="[ Durasi ]">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-md-2 col-form-label fw-bold">Lokasi</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" name="sn_lokasi" placeholder="[ Lokasi ]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-secondary mb-3">
                    <h6>SKRINING RESIKO JATUH</h6>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" id="srj_hd">
                            <label class="form-check-label ms-1">
                                <small class="mb-2 fw-bold">Penilaian Resiko Jatuh Anak ( Skala Humpty Dumpty)</small>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="tampil_srj_hd" hidden>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm flex-grow-1">
                                <span class="input-group-text">Usia</span>
                                <select class="form-select form-select-sm" name="rj_usia" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    @if ($list['usia'])
                                        @foreach ($list['usia'] as $item)
                                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm flex-grow-1">
                                <span class="input-group-text">Jenis Kelamin</span>
                                <select class="form-select form-select-sm" name="rj_jk" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    @if ($list['jk'])
                                        @foreach ($list['jk'] as $item)
                                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Diagnosa</label>
                                <select class="form-select form-select-sm" name="rj_hd_1" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    <option value="4">Kelainan neurogis (meningitis) enchepalitis kejang dan atau gelisah</option>
                                    <option value="3">Gangguan perilaku / spikiatri</option>
                                    <option value="2">Perubahan oksigenasi (diagnosis, respiratorik, asthma, syncope, dehidrasi, anemia, anoresia)</option>
                                    <option value="1">Diagnosis lainnya</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Gangguan Kognitif</label>
                                <select class="form-select form-select-sm" name="rj_hd_2" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    <option value="3">Belum punya kontrol diri / gelisah</option>
                                    <option value="2">Lupa akan kondisi sakitnya / kadang gelisah</option>
                                    <option value="1">Orientasi terhadap kemampuan diri</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Faktor Lingkungan</label>
                                <select class="form-select form-select-sm" name="rj_hd_3" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    <option value="4">Riwayat jatuh / pasien ditempatkan di tempat tidur dewasa</option>
                                    <option value="3">Pasien menggunakan alat bantu, ditempatkan dikursi / pangkuan</option>
                                    <option value="2">Pasien diletakkan di tempat tidur bayi / khusus anak</option>
                                    <option value="1">Area di luar rumah sakit</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Respon Terhadap Tindakan Operasi (Anastesi Sedasi)</label>
                                <select class="form-select form-select-sm" name="rj_hd_4" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    <option value="3">Dalam 24 jam</option>
                                    <option value="2">Dalam 48 jam</option>
                                    <option value="1">48 jam / tidak menjalani operasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Penggunaan Obat</label>
                                <select class="form-select form-select-sm" name="rj_hd_5" data-hd-required data-hd-score>
                                    <option value="">Pilih</option>
                                    <option value="3">Penggunaan multiple sedative (obat), hypnosi, barbiturate, antidrepesan, pencahar, diuretic, narcose</option>
                                    <option value="2">Penggunaan obat salah satu diatas</option>
                                    <option value="1">Obat lain / tidak menggunakan obat salah satu diatas</option>
                                </select>
                            </div>
                            <input type="number" class="form-control" name="skor_rj_hd" value="0" hidden>
                            <div id="skor_rj_hd" class="mb-3" hidden>
                                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                                    <div class="me-4 flex-shrink-0">
                                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_hd">0</h1>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                                        <div class="fw-bold text-success" id="kategori_rj_hd"></div>
                                        <small class="text-muted" id="keterangan_rj_hd"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr class="mt-0">
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" id="srj_sm">
                            <label class="form-check-label ms-1">
                                <small class="mb-2 fw-bold">Penilaian Resiko Jatuh Dewasa ( Skala Morse)</small>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="tampil_srj_sm" hidden>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Riwayat Jatuh (Baru Saja / 3 Bulan Terakhir)</label>
                                <select class="form-select" name="rj_sm_1" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Tidak</option> {{-- 0 --}}
                                    <option value="2">Ya</option> {{-- 25 --}}
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Diagnosa Lain / Diagnosa Sekunder</label>
                                <select class="form-select" name="rj_sm_2" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Tidak</option> {{-- 0 --}}
                                    <option value="2">Ya</option> {{-- 15 --}}
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Menggunakan Alat Bantu</label>
                                <select class="form-select" name="rj_sm_3" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Tidak ada, bed rest</option> {{-- 0 --}}
                                    <option value="2">Tongkat, alat penopang, walker</option> {{-- 15 --}}
                                    <option value="3">Furnitur</option> {{-- 30 --}}
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Penggunaan Obat Yang Bisa Mempengaruhi Keseimbangan (Heparin, Diuretik, Anti Hipertensi, Anestesi, Anti Depresan dll)</label>
                                <select class="form-select" name="rj_sm_4" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Tidak</option> {{-- 0 --}}
                                    <option value="2">Ya</option> {{-- 20 --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Gaya Berjalan</label>
                                <select class="form-select" name="rj_sm_5" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Lemah</option> {{-- 10 --}}
                                    <option value="2">Terganggu</option> {{-- 20 --}}
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Kesadaran</label>
                                <select class="form-select" name="rj_sm_6" data-sm-required>
                                    <option value="">Pilih</option>
                                    <option value="1">Baik</option> {{-- 0 --}}
                                    <option value="2">Lupa / pelupa</option> {{-- 15 --}}
                                </select>
                            </div>
                            <input type="number" class="form-control" name="skor_rj_sm" value="0" hidden>
                            <div id="skor_rj_sm" hidden>
                                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                                    <div class="me-4 flex-shrink-0">
                                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_sm">0</h1>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                                        <div class="fw-bold text-success" id="kategori_rj_sm"></div>
                                        <small class="text-muted" id="keterangan_rj_sm"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" id="srj_epfra">
                            <label class="form-check-label ms-1">
                                <small class="mb-2 fw-bold">Penilaian Edmonson Psychiatric Fall Risk Assessment ( EPFRA )</small>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="tampil_srj_epfra" hidden>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia Pasien</label>
                            <div class="input-group input-group-sm flex-grow-1">
                                <select class="form-select form-select-sm" name="rj_epfra_usia" data-epfra-required data-epfra-score>
                                    <option value="">Pilih</option>
                                    @if ($list['usia'])
                                        @foreach ($list['usia'] as $item)
                                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_1" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0" selected>Pilih</option>
                                    <option value="1" data-score="4">Sadar penuh dan orientasi waktu baik</option>
                                    <option value="2" data-score="13">Agitasi / Cemas</option>
                                    <option value="3" data-score="12">Sering bingung</option>
                                    <option value="4" data-score="14">Bingung dan disorientasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Eliminasi</label>
                                <select class="form-select form-select-sm" name="rj_epfra_2" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="8">Mandiri untuk BAB dan BAK</option>
                                    <option value="2" data-score="12">Memakai Kateter / Ostomy</option>
                                    <option value="3" data-score="10">BAB dan BAK dengan bantuan</option>
                                    <option value="4" data-score="12">Gangguan eliminasi (inkontinensia, banyak BAK di malam hari, sering BAB danBAK)</option>
                                    <option value="5" data-score="12">Inkontinensia tetapi bisa ambulasi mandiri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Medikasi</label>
                                <select class="form-select form-select-sm" name="rj_epfra_3" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="10">Tidak ada pengobatan yang diberikan</option>
                                    <option value="2" data-score="10">Obat-obatan jantung</option>
                                    <option value="3" data-score="8">Obat psikiatri termasuk benzodiazepin dan anti depresan</option>
                                    <option value="4" data-score="12">Meningkatnya dosis obat yang dikonsumsi / ditambahkan dalam 24 jam terakhir</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_4" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="10">Bipolar / gangguan scizo affective</option>
                                    <option value="2" data-score="8">Penyalahgunaan zat terlarang dan alkohol</option>
                                    <option value="3" data-score="10">Gangguan depresi mayor</option>
                                    <option value="4" data-score="12">Dimensia / Delirium</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_5" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="7">Ambulasi mandiri dan langkah stabil atau pasien imobil</option>
                                    <option value="2" data-score="8">Penggunaan alat bantu yang tepat (tongkat, walker, tripod, dll)</option>
                                    <option value="3" data-score="10">Vertigo / Hipotensi Ortostatik / Kelemahan</option>
                                    <option value="4" data-score="8">Langkah tidak stabil, butuh bantuan dan menyadari kemampuannya</option>
                                    <option value="5" data-score="15">Langkah tidak stabil, butuh bantuan dan tidak menyadari ketidakmampuannya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_6" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="12">Hanya sedikit mendapatkan asupan makanan / minum dalam 24 jam terakhir</option>
                                    <option value="2" data-score="0">Nafsu makan baik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_7" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="8">Tidak ada gangguan tidur</option>
                                    <option value="2" data-score="12">Ada gangguan tidur yang dilaporkan keluarga pasien / staf</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Status Mental</label>
                                <select class="form-select form-select-sm" name="rj_epfra_8" data-epfra-required data-epfra-score>
                                    <option value="" data-score="0">Pilih</option>
                                    <option value="1" data-score="8">Tidak ada riwayat jatuh</option>
                                    <option value="2" data-score="14">Ada riwayat jatuh dalam 3 bulan terakhir</option>
                                </select>
                            </div>
                            <input type="number" class="form-control" name="skor_rj_epfra" value="0" hidden>
                            <div id="skor_rj_epfra" hidden>
                                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                                    <div class="me-4 flex-shrink-0">
                                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_epfra">0</h1>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                                        <div class="fw-bold text-success" id="kategori_rj_epfra"></div>
                                        <small class="text-muted" id="keterangan_rj_epfra"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary mb-3">
                    <h6 class="mb-3">SKRINING GIZI</h6>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" id="sg_must">
                            <label class="form-check-label ms-1">
                                <small class="mb-2 fw-bold">Assesmen Nutrisi Pasien Dewasa (Mainutrition Screening Tool / MUST)</small>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="tampil_sg_must" hidden>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <h6>Adakah perubahan berat badan signifikan dalam 3 bualn terakhir</h6>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Jumlah perubahan berat badan</h6>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="1">
                                        <label class="form-check-label">
                                            0,5 - 5 kg
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="2">
                                        <label class="form-check-label">
                                            > 5 - 10 kg
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="3">
                                        <label class="form-check-label">
                                            > 10 - 15 kg
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="4">
                                        <label class="form-check-label">
                                            > 15 kg
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Intake makanan kurang karena tidak ada nafsu makan</h6>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd2" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd2" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Kondisi Khusus</h6>
                                <input type="text" class="form-control form-control-sm" name="sgd3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="skor_sgd" value="0" hidden>
                            <div id="skor_sgd" class="mb-3" hidden>
                                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                                    <div class="me-4 flex-shrink-0">
                                        <h1 class="display-1 fw-bold mb-0" id="nilai_sgd">0</h1>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Skor Skrining Gizi</h5>
                                        <div class="fw-bold text-success" id="kategori_sgd">Risiko Ringan</div>
                                        <small class="text-muted" id="keterangan_sgd"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr class="mt-0">
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" id="sg_sk">
                            <label class="form-check-label ms-1">
                                <small class="mb-2 fw-bold">Assesmen Nutrisi Pasien Anak (STRONG KID)</small>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="tampil_sg_sk" hidden>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <h6>Apakah pasien tampak kurus</h6>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga1" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga1" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Apakah terjadi mengalami penurunan berat badan selama 1 bulan terakhir</h6>
                                <ul>
                                    <li>Berdasarkan penilaian obyektif data BB atau penilaian subyektif orang tua pasien</li>
                                    <li>Untuk bayi < 1 tahun, BB tidak baik selama 3 bulan terakhir</li>
                                </ul>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga2" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga2" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Apakah terdapat salah satu dari kondisi berikut</h6>
                                <ul>
                                    <li>Diare > 5 kali /hari dan atau muntah > 3 kali /hari dalam seminggu terakhir</li>
                                    <li>Asupan makanan berkurang selama seminggu terakhir</li>
                                </ul>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga3" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga3" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien beresiko mengalami malnutrisi</h6>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga4" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sga4" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="skor_sga" value="0" hidden>
                            <div id="skor_sga" hidden>
                                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                                    <div class="me-4 flex-shrink-0">
                                        <h1 class="display-1 fw-bold mb-0" id="nilai_sga">0</h1>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Skor Skrining Gizi</h5>
                                        <div class="fw-bold text-success" id="kategori_sga"></div>
                                        <small class="text-muted" id="keterangan_sga"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-warning mb-3">
                    <h6 class="mb-3">Status Kehamilan</h6>
                    <div class="d-flex align-items-center gap-4">
                        <label class="form-label flex-shrink-0">Hamil ?</label>
                        <div class="form-check mb-0">
                            <input class="form-check-input check-danger single-checkbox" type="checkbox" name="sh" value="0" checked="">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sh" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="row mt-3" id="tampil_sh_ya" hidden>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-4 mb-3">
                                <label class="form-label fw-bold flex-shrink-0">G (Gravida)</label>
                                <input type="text" class="form-control form-control-sm" name="sh_g">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-4 mb-3">
                                <label class="form-label fw-bold flex-shrink-0">P (Para)</label>
                                <input type="text" class="form-control form-control-sm" name="sh_p">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-4 mb-3">
                                <label class="form-label fw-bold flex-shrink-0">A (Abortus)</label>
                                <input type="text" class="form-control form-control-sm" name="sh_a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-4 mb-3">
                                <label class="form-label fw-bold flex-shrink-0">HPHT (Hari Pertama Haid Terakhir)</label>
                                <input type="text" class="form-control form-control-sm" name="sh_h">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-success mb-3">
                    <h6 class="mb-3">Implementasi Keperawatan</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-display">
                            <colgroup>
                                <col style="width: 1%;">
                                <col>
                            </colgroup>
                            <thead class="text-uppercase">
                                <tr class="table-light">
                                    <th class="text-center">✔</th>
                                    <th class="text-center">Implementasi Keperawatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_1"></td>
                                    <td>Melakukan observasi TTV</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_2"></td>
                                    <td>Melakukan observasi keadaan umum pasien</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_3"></td>
                                    <td>Memonitor intake output</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_4"></td>
                                    <td>Memonitor pernafasan : irama, pengembangan dinding dada, penggunaan otot tambahan pernafasan bunyi nafas</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_5"></td>
                                    <td>Melakukan pemasangan Oximetri</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_6"></td>
                                    <td>Mengobservasi produk sputum, jumlah, warna, dan kekentalan</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_7"></td>
                                    <td>Memberikan posisi semi fowler atau posisi miring yang nyaman</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_8"></td>
                                    <td>Melakukan pemasangan OPA</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_9"></td>
                                    <td>Melakukan Suction bila perlu</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_10"></td>
                                    <td>Mengajarkan pasien untuk nafas dalam dan batuk efektif</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_11"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Memberikan oksigen</div> <input type="text" class="form-control form-control-sm w-auto" name="ik_11_lain"> <div class="flex-shrink-0">liter/menit</div></div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_12"></td>
                                    <td>Mengibolisasikan daerah cedera : memasang bidai / spalk / sling</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_13"></td>
                                    <td>Melakukan perawatan luka</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_14"></td>
                                    <td>Mengajarkan manajemen pengelolaan nyeri</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_15"></td>
                                    <td>Melakukan tindakan dengan teknik aseptic</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h6 class="mb-3">Tindakan Kolaborasi</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-display">
                            <colgroup>
                                <col style="width: 1%;">
                                <col style="width: 1%;">
                                <col>
                            </colgroup>
                            <thead class="text-uppercase">
                                <tr class="table-light">
                                    <th class="text-center">✔</th>
                                    <th class="text-center">Pukul</th>
                                    <th class="text-center">Tindakan Kolaborasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_1"></td>
                                    <td><input type="time" class="form-control" name="tk_1_dt"></td>
                                    <td>OPA</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_2"></td>
                                    <td><input type="time" class="form-control" name="tk_2_dt"></td>
                                    <td>O2</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_3"></td>
                                    <td><input type="time" class="form-control" name="tk_3_dt"></td>
                                    <td>Suction</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_4"></td>
                                    <td><input type="time" class="form-control" name="tk_4_dt"></td>
                                    <td>Nasal kanul</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_5"></td>
                                    <td><input type="time" class="form-control" name="tk_5_dt"></td>
                                    <td>Coliat neck</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_6"></td>
                                    <td><input type="time" class="form-control" name="tk_6_dt"></td>
                                    <td>Masker</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_7"></td>
                                    <td><input type="time" class="form-control" name="tk_7_dt"></td>
                                    <td>Resusitasi</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_8"></td>
                                    <td><input type="time" class="form-control" name="tk_8_dt"></td>
                                    <td>Nebiizer</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_9"></td>
                                    <td><input type="time" class="form-control" name="tk_9_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Infus</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_9_lain"></div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_10"></td>
                                    <td><input type="time" class="form-control" name="tk_10_dt"></td>
                                    <td>Kateter urine</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_11"></td>
                                    <td><input type="time" class="form-control" name="tk_11_dt"></td>
                                    <td>NGT / OGT</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_12"></td>
                                    <td><input type="time" class="form-control" name="tk_12_dt"></td>
                                    <td>Bilas lambung</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_13"></td>
                                    <td><input type="time" class="form-control" name="tk_13_dt"></td>
                                    <td>Jahitan</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_14"></td>
                                    <td><input type="time" class="form-control" name="tk_14_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Rontgen</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_14_lain"></div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_15"></td>
                                    <td><input type="time" class="form-control" name="tk_15_dt"></td>
                                    <td>CT Scan</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_16"></td>
                                    <td><input type="time" class="form-control" name="tk_16_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Lab. Darah</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_16_lain"></div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_18"></td>
                                    <td><input type="time" class="form-control" name="tk_18_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Hecting permanen</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_18_lain"></div> <div class="flex-shrink-0">jahitan</div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_19"></td>
                                    <td><input type="time" class="form-control" name="tk_19_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Hecting situasi</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_19_lain"></div> <div class="flex-shrink-0">jahitan</div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_20"></td>
                                    <td><input type="time" class="form-control" name="tk_20_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">ATS, skin test, hasil</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_20_lain"></div></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_21"></td>
                                    <td><input type="time" class="form-control" name="tk_21_dt"></td>
                                    <td>Debridemen</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_22"></td>
                                    <td><input type="time" class="form-control" name="tk_22_dt"></td>
                                    <td>Corpus alianum</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_23"></td>
                                    <td><input type="time" class="form-control" name="tk_23_dt"></td>
                                    <td>Combustio</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_24"></td>
                                    <td><input type="time" class="form-control" name="tk_24_dt"></td>
                                    <td>Hidung</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_25"></td>
                                    <td><input type="time" class="form-control" name="tk_25_dt"></td>
                                    <td>Dekubitus</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_26"></td>
                                    <td><input type="time" class="form-control" name="tk_26_dt"></td>
                                    <td>Mulut</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_27"></td>
                                    <td><input type="time" class="form-control" name="tk_27_dt"></td>
                                    <td>Telinga</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_17"></td>
                                    <td><input type="time" class="form-control" name="tk_17_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Lain-lain</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_17_lain"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h6 class="mb-3">Diagnosis Keperawatan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_1">
                                <label class="form-check-label">
                                    Nyeri
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_2">
                                <label class="form-check-label">
                                    Cemas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_3">
                                <label class="form-check-label">
                                    Perubahan Nutrisi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_4">
                                <label class="form-check-label">
                                    Gangguan Pernafasan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_5">
                                <label class="form-check-label">
                                    Gangguan Perfusi Jaringan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_6">
                                <label class="form-check-label">
                                    Gangguan Volume Cairan
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_7">
                                <label class="form-check-label">
                                    Potensi Infeksi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_8">
                                <label class="form-check-label">
                                    Hipertermi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_9">
                                <label class="form-check-label">
                                    Takut (Pada Anak)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="dmk_10">
                                <label class="form-check-label">
                                    Ketidak Efektifan Pola Makan
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Masalah Lain</label>
                                <textarea class="form-control" name="dmk_lain" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h6 class="mb-2">Kriteria Perencanaan Pulang (Discharge Planning)</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien tinggal sendiri :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_1" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_1" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien/keluarga khawatir ketika kembali di rumah :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_2" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_2" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-grou mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien di rumah tidak ada yang merawat :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_3" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_3" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien tinggal di lantai atas rumah :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_4" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_4" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien masih ada perawatan lanjutan / penggunaan alat medis yang dilakukan di rumah :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_5" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_5" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_5" hidden>
                                <div class="row">
                                    <h6>Jika Ada, sebutkan :</h6>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_1">
                                            <label class="form-check-label">
                                                Kateter Urin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_2">
                                            <label class="form-check-label">
                                                Traechostomy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_3">
                                            <label class="form-check-label">
                                                NGT
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_4">
                                            <label class="form-check-label">
                                                Colostomy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-grow-1">
                                            <label class="form-check-label">
                                                Lainnya
                                            </label>
                                            <input type="text" class="form-control form-control-sm flex-grow-1" name="dp_5_lain">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Kebutuhan pelayanan berkelanjutan :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_9" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_9" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_9" hidden>
                                <div class="row">
                                    <h6>Jika Ada, sebutkan :</h6>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_1">
                                            <label class="form-check-label">
                                                Rawat luka
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_2">
                                            <label class="form-check-label">
                                                TB
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_3">
                                            <label class="form-check-label">
                                                DM dengan terapi insulin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_4">
                                            <label class="form-check-label">
                                                PPOK
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_5">
                                            <label class="form-check-label">
                                                Pasien kemoterapi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_6">
                                            <label class="form-check-label">
                                                HIV / AIDS
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_7">
                                            <label class="form-check-label">
                                                DM
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_8">
                                            <label class="form-check-label">
                                                Stroke
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_9">
                                            <label class="form-check-label">
                                                CKD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien pulang dengan jumlah obat > 6 :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_6" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_6" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Pasien mengajukan permohonan pendampingan ke Rumah Sakit :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_7" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_7" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Tidak ada kriteria Pasien :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_8" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_8" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Penggunaan alat medis/bantu :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_10" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_10" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_10" hidden>
                                <div class="row">
                                    <h6>Jika Ada, sebutkan :</h6>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_10_1" value="1">
                                            <label class="form-check-label">
                                                Kateter Urin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_10_2" value="1">
                                            <label class="form-check-label">
                                                Traechostomy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_10_3" value="1">
                                            <label class="form-check-label">
                                                NGT
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_10_4" value="1">
                                            <label class="form-check-label">
                                                Colostomy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-grow-1">
                                            <label class="form-check-label">
                                                Lainnya
                                            </label>
                                            <input type="text" class="form-control form-control-sm flex-grow-1" name="dp_10_lain">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <label class="form-label">Skrining lanjutan :</label>
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_11" value="0">
                                        <label class="form-check-label">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_11" value="1">
                                        <label class="form-check-label">
                                            Ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_11" hidden>
                                <div class="row">
                                    <h6>Jika Ada :</h6>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="dp_11_skrining" value="1">
                                            <label class="form-check-label">
                                                Konsul MPP
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="dp_11_skrining" value="2">
                                            <label class="form-check-label">
                                                Edukasi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer d-flex justify-content-between">
        <button type="button" class="btn btn-subtle-info btnLihatCPPT" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat CPPT" onclick="showCppt('{{ $list['kunjungan'] }}')">
            <i class="ri-booklet-line me-1"></i> Lihat CPPT
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianGdP(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    var $sectionGdP = $('#gd_perawat');
    $(document).ready(function() {

        $sectionGdP.on('input', 'input[type="number"][min][max]', function () {

            const min = parseFloat(this.min);
            const max = parseFloat(this.max);
            const value = parseFloat(this.value);

            // Kosongkan jika nilai di luar range
            if (!isNaN(value) && (value < min || value > max)) {
                this.value = '';
            }
        });

        // ASAL RUJUKAN ------------------------------------------------------------------------------
        const $inputPPK = $('[name="dd_ck_k"]');

        if ($inputPPK.length) {

            let ppkSelected = false;

            const ppkAutoComplete = new autoComplete({
                selector: '[name="dd_ck_k"]',
                placeHolder: 'Tuliskan Asal Rujukan',
                threshold: 2,
                debounce: 300,

                data: {
                    src: async function (query) {
                        try {
                            return await $.ajax({
                                url: "/api/v2/emr/pengkajian/asal_rujukan_ppk",
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    q: query
                                }
                            });
                        } catch (error) {
                            console.error('Gagal mengambil data PPK:', error);
                            return [];
                        }
                    },

                    keys: ['nama'],
                    cache: false
                },

                resultsList: {
                    maxResults: 15,
                    noResults: true
                },

                resultItem: {
                    highlight: true,

                    element: function (item, data) {
                        const ppk = data.value;

                        $(item)
                            .empty()
                            .append(
                                $('<strong>').text(ppk.nama),
                                $('<small>')
                                    .addClass('d-block text-muted')
                                    .text(
                                        `${ppk.jenis ?? '-'} | ${ppk.wilayah ?? '-'}`
                                    ),
                                $('<small>')
                                    .addClass('d-block text-muted')
                                    .text(
                                        ppk.alamat ?? '-'
                                    )
                            );
                    }
                },

                events: {
                    input: {

                        // User memilih salah satu PPK
                        selection: function (event) {
                            const ppk = event.detail.selection.value;

                            ppkSelected = true;

                            $inputPPK.val(ppk.nama);

                            // Jika menggunakan ID PPK
                            // $('#dd_ck_k_id').val(ppk.id);
                        }
                    }
                }
            });

            // User mulai mengetik lagi setelah memilih
            $inputPPK.on('input', function () {
                ppkSelected = false;
            });

            // User klik di luar input
            $inputPPK.on('blur', function () {
                setTimeout(function () {
                    if (!ppkSelected) {
                        $inputPPK.val('');

                        // Jika menggunakan ID PPK
                        // $('#dd_ck_k_id').val('');
                    }
                }, 150);
            });
        }

        // ================================
        // DATANG / RUJUKAN / POLISI
        // ================================
        $sectionGdP.on('change', '[name="dd_ck"]', function () {

            const value = $(this).val();

            // dd_ck tidak boleh di-uncheck
            if (!this.checked) {
                $(this).prop('checked', true);
                return;
            }

            // Hanya satu pilihan dd_ck
            $sectionGdP
                .find('[name="dd_ck"]')
                .not(this)
                .prop('checked', false);

            const $pengantar = $sectionGdP.find('[name="dd_ck_p"]');
            const $rujukan   = $sectionGdP.find('[name="dd_ck_k"]');
            const $polisi    = $sectionGdP.find('[name="dd_ck_a"]');
            const $visum     = $sectionGdP.find('[name="dd_ck_a_v"]');

            // Reset dan disable semuanya
            $pengantar.val('').prop('disabled', true);
            $rujukan.val('').prop('disabled', true);
            $polisi.val('').prop('disabled', true);
            $visum.prop('checked', false).prop('disabled', true);

            // Aktifkan sesuai pilihan
            if (value === '1') {

                // Datang sendiri, diantar
                $pengantar.prop('disabled', false);

            } else if (value === '2') {

                // Rujukan
                $rujukan.prop('disabled', false);

            } else if (value === '3') {

                // Polisi
                $polisi.prop('disabled', false);
                $visum.prop('disabled', false);
            }
        });

        // JENIS KASUS
        $sectionGdP.on('change', 'input[name="jks"]', function () {

            const $jks = $sectionGdP.find('input[name="jks"]:checked');

            const $trauma = $sectionGdP.find(
                '[name="jks_kll"], [name="jks_kk"], [name="jks_uppa"]'
            );

            const $nonTrauma = $sectionGdP.find(
                '[name="jks_end"], [name="jks_end_dm"]'
            );

            // Tidak ada JKS yang dipilih
            if (!$jks.length) {

                resetJenisKasus($sectionGdP);

                return;
            }

            const nilai = $jks.val();

            if (nilai === '1') {

                // =========================
                // TRAUMA
                // =========================

                $trauma
                    .prop('disabled', false);

                // Reset + disable Non Trauma
                $sectionGdP.find('[name="jks_end"]')
                    .prop('checked', false)
                    .prop('disabled', true);

                $sectionGdP.find('[name="jks_end_dm"]')
                    .val('')
                    .prop('disabled', true);

            } else if (nilai === '0') {

                // =========================
                // NON TRAUMA
                // =========================

                // Reset + disable Trauma
                $trauma
                    .prop('checked', false)
                    .prop('disabled', true);

                // Aktifkan Non Trauma
                $sectionGdP.find('[name="jks_end"], [name="jks_end_dm"]')
                    .prop('disabled', false);
            }
        });

        // START SKRINING NYERI ------------------------------------------------------------------------------
        const metodeMap = {
            1: '#tampil_sn_nrs',
            2: '#tampil_sn_bps',
            3: '#tampil_sn_nips',
            4: '#tampil_sn_flacc',
            5: '#tampil_sn_vas'
        };

        // ==========================================
        // PILIH METODE
        // ==========================================
        $sectionGdP.on('change', 'input[name="sn_metode"]', function () {

            const $this = $(this);
            const metode = $this.val();

            // ==========================================
            // JIKA METODE DIPILIH
            // ==========================================
            if ($this.is(':checked')) {

                // Pastikan hanya satu metode yang aktif
                $sectionGdP
                    .find('input[name="sn_metode"]')
                    .not($this)
                    .prop('checked', false);

                // Reset skor metode sebelumnya
                resetSemuaSkor();

                // Hide semua metode
                $.each(metodeMap, function (key, selector) {
                    $(selector).prop('hidden', true);
                });

                // Tampilkan metode yang dipilih
                if (metodeMap[metode]) {
                    $(metodeMap[metode]).prop('hidden', false);
                }

            } else {

                // ==========================================
                // JIKA METODE DIBATALKAN
                // ==========================================

                // Hide semua
                $.each(metodeMap, function (key, selector) {
                    $(selector).prop('hidden', true);
                });

                // Reset semua skor dan sn_skala kembali 0
                resetSemuaSkor();
            }
        });

        // ==========================================
        // KONDISI AWAL
        // ==========================================
        $.each(metodeMap, function (key, selector) {
            $(selector).prop('hidden', true);
        });

        resetSemuaSkor();

        // ==========================================
        // NRS
        // ==========================================
        $('#sn_nrs').on('input', function () {
            hitungSkorNyeri('1');
        });

        // ==========================================
        // BPS
        // ==========================================
        $('#tampil_sn_bps input[type="number"]').on('input', function () {
            hitungSkorNyeri('2');
        });

        // ==========================================
        // NIPS
        // ==========================================
        $('#tampil_sn_nips input[type="number"]').on('input', function () {
            hitungSkorNyeri('3');
        });

        // ==========================================
        // FLACC
        // ==========================================
        $('#tampil_sn_flacc input[type="number"]').on('input', function () {
            hitungSkorNyeri('4');
        });

        // ==========================================
        // VAS
        // ==========================================
        $('#sn_vas').on('input', function () {
            hitungSkorNyeri('5');
        });
        // END SKRINING NYERI ------------------------------------------------------------------------------

        // RESIKO JATUH ------------------------------------------------------------------------------
        // Event delegation: tetap berjalan walaupun isi accordion dirender ulang
        $sectionGdP.on('change input', '[data-hd-required]', function () {
            hitungSkorHumptyDumpty($sectionGdP);
        });
        $sectionGdP.on('change', '[data-sm-required]', function () {
            hitungSkorMorse($sectionGdP);
        });
        $sectionGdP.on('change', '[data-epfra-required]', function () {
            hitungSkorEPFRA($sectionGdP);
        });
        $sectionGdP.on('change input', '[name="sgd1"], [name="sgd1_c"], [name="sgd2"]', function () {
            hitungSkorMUST($sectionGdP);
        });
        $sectionGdP.on('change', 'input[name="sga1"], input[name="sga2"], input[name="sga3"], input[name="sga4"]', function () {
            hitungSkorStrongKid($sectionGdP);
        });

        // CB - SKRINING RESIKO JATUH
        $sectionGdP.on('change', '#srj_hd', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Humpty Dumpty
                $sectionGdP.find('#tampil_srj_hd').prop('hidden', false);
            } else {
                // Sembunyikan
                $sectionGdP.find('#tampil_srj_hd').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetHumptyDumpty($sectionGdP);
            }
        });
        $sectionGdP.on('change', '#srj_sm', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Morse
                $sectionGdP.find('#tampil_srj_sm').prop('hidden', false);
            } else {
                // Sembunyikan
                $sectionGdP.find('#tampil_srj_sm').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetMorse($sectionGdP);
            }
        });
        $sectionGdP.on('change', '#srj_epfra', function () {
            if ($(this).is(':checked')) {
                $sectionGdP.find('#tampil_srj_epfra') .prop('hidden', false);
            } else {
                $sectionGdP.find('#tampil_srj_epfra') .prop('hidden', true);
                resetEPFRA($sectionGdP);
            }
        });

        // CB - SKRINING GIZI
        $sectionGdP.on('change', '#sg_must', function () {
            if ($(this).is(':checked')) {
                // Tampilkan MUST
                $sectionGdP.find('#tampil_sg_must').prop('hidden', false);
            } else {
                // Sembunyikan MUST
                $sectionGdP.find('#tampil_sg_must').prop('hidden', true);
                // Reset data MUST
                resetSkriningMUST($sectionGdP);
            }
        });
        $sectionGdP.on('change', '#sg_sk', function () {
            if ($(this).is(':checked')) {
                // Tampilkan STRONG KID
                $sectionGdP.find('#tampil_sg_sk').prop('hidden', false);
            } else {
                // Sembunyikan STRONG KID
                $sectionGdP.find('#tampil_sg_sk').prop('hidden', true);
                // Reset data STRONG KID
                resetSkriningStrongKid($sectionGdP);
            }
        });

        // CB - Status Kehamilan
        $sectionGdP.on('change', '[name="sh"]', function () {
            const nilai = $sectionGdP.find('[name="sh"]:checked').val();
            if (nilai === '1') {
                // Ya -> tampilkan detail kehamilan
                $sectionGdP.find('#tampil_sh_ya').prop('hidden', false);
            } else {
                // Tidak -> sembunyikan detail
                $sectionGdP.find('#tampil_sh_ya').prop('hidden', true);
                // Reset detail kehamilan
                resetStatusKehamilan($sectionGdP);
            }
        });

        // CB - Discharge Planning
        $sectionGdP.on('change', '.single-checkbox-bos', function () {

            // Hanya proses checkbox yang sedang checked
            if (!this.checked) {
                return;
            }

            const name = this.name;
            const value = String(this.value);

            // Hanya dp_1, dp_2, dp_3, dst.
            if (!/^dp_\d+$/.test(name)) {
                return;
            }

            const $target = $sectionGdP.find('#tampil_' + name);

            if (value === '1') {

                // ADA
                $target.prop('hidden', false);

            } else {

                // TIDAK ADA
                $target.prop('hidden', true);

                resetDischargePlanningDetail($sectionGdP, name);
            }
        });

        // INIT FUNCTION ------------------------------------------------------------------------------
        resetDatangCara($sectionGdP);
        resetJenisKasus($sectionGdP);

        getDataPengkajianGdP();
        hitungSkorHumptyDumpty($sectionGdP);
        hitungSkorMorse($sectionGdP);
        hitungSkorEPFRA($sectionGdP);
        hitungSkorMUST($sectionGdP);
        hitungSkorStrongKid($sectionGdP);
    })

    // FUNCTION-FUNCTION AREA  /////////////////////////////////////////////////////

    // RESET CARA KEDATANGAN
    function resetDatangCara($sectionGdP) {

        // Reset pilihan utama
        $sectionGdP.find('[name="dd_ck"]')
            .prop('checked', false);

        // Reset dan disable input
        $sectionGdP.find('[name="dd_ck_p"]')
            .val('')
            .prop('disabled', true);

        $sectionGdP.find('[name="dd_ck_k"]')
            .val('')
            .prop('disabled', true);

        $sectionGdP.find('[name="dd_ck_a"]')
            .val('')
            .prop('disabled', true);

        $sectionGdP.find('[name="dd_ck_a_v"]')
            .prop('checked', false)
            .prop('disabled', true);
    }

    // RESET JENIS KASUS
    function resetJenisKasus($sectionGdP) {

        // Reset pilihan Trauma
        $sectionGdP.find(
            '[name="jks_kll"], [name="jks_kk"], [name="jks_uppa"]'
        )
            .prop('checked', false)
            .prop('disabled', true);

        // Reset Non Trauma
        $sectionGdP.find('[name="jks_end"]')
            .prop('checked', false)
            .prop('disabled', true);

        // Reset keterangan
        $sectionGdP.find('[name="jks_end_dm"]')
            .val('')
            .prop('disabled', true);
    }

    // HITUNG SKOR SKRINING NYERI BERDASARKAN METODE
    function getValidNumber($input) {

        const value = parseFloat($input.val());
        const min = parseFloat($input.attr('min'));
        const max = parseFloat($input.attr('max'));

        // Kosong
        if (isNaN(value)) {
            return 0;
        }

        // Di luar range
        if (value < min || value > max) {
            $input.val('');
            return 0;
        }

        return value;
    }
    function hitungSkorNyeri(metode) {

        let total = 0;

        switch (metode) {

            // NRS
            case '1':
                total = getValidNumber($('#sn_nrs'));
                break;

            // BPS
            case '2':
                $('#tampil_sn_bps input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // NIPS
            case '3':
                $('#tampil_sn_nips input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // FLACC
            case '4':
                $('#tampil_sn_flacc input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // VAS
            case '5':
                total = getValidNumber($('#sn_vas'));
                break;
        }

        $('input[name="sn_skala"]').val(total);
    }

    function resetSemuaSkor() {

        // Reset range
        $('#sn_nrs, #sn_vas').val(0);

        // Reset semua input skor
        $('#tampil_sn_bps input[type="number"], ' +
        '#tampil_sn_nips input[type="number"], ' +
        '#tampil_sn_flacc input[type="number"]'
        ).val('');

        // Kembali ke nilai default
        $('input[name="sn_skala"]').val(0);
    }

    // RESIKO JATUH HUMPTY DUMPTY
    function hitungSkorHumptyDumpty($sectionGdP) {
        const $requiredFields = $sectionGdP.find('[data-hd-required]');
        const $scoreFields = $sectionGdP.find('[data-hd-score]');

        // Selector selalu dibatasi di dalam #gd_perawat
        const $scoreBox = $sectionGdP.find('#skor_rj_hd');
        const $nilai = $sectionGdP.find('#nilai_rj_hd');
        const $kategori = $sectionGdP.find('#kategori_rj_hd');
        const $keterangan = $sectionGdP.find('#keterangan_rj_hd');

        const semuaTerisi = $requiredFields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '';
        });

        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        let skor = 0;

        $scoreFields.each(function () {
            skor += Number($(this).val());
        });

        const isHighRisk = skor >= 12;
        const kategori = isHighRisk
            ? 'High Risk Dumpty'
            : 'Low Humpty Dumpty';

        $nilai.text(skor);
        $sectionGdP.find('input[name="skor_rj_hd"]').val(skor);

        $kategori
            .text(kategori)
            .removeClass('text-success text-danger')
            .addClass(isHighRisk ? 'text-danger' : 'text-success');

        $keterangan.text(
            isHighRisk
                ? 'Lakukan pencegahan risiko jatuh sesuai prosedur.'
                : 'Monitoring dan evaluasi risiko jatuh sesuai prosedur.'
        );

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-danger')
            .addClass(isHighRisk ? 'alert-danger' : 'alert-success');

        $scoreBox.prop('hidden', false);
    }

    function resetHumptyDumpty($sectionGdP) {

        // Reset semua pilihan
        $sectionGdP.find('[data-hd-required]').val('');

        // Reset nilai skor
        $sectionGdP.find('[name="skor_rj_hd"]').val(0);

        // Reset tampilan skor
        $sectionGdP.find('#nilai_rj_hd').text(0);

        // Sembunyikan hasil skor
        $sectionGdP.find('#skor_rj_hd').prop('hidden', true);

        // Kembalikan tampilan kategori
        $sectionGdP.find('#kategori_rj_hd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $sectionGdP.find('#keterangan_rj_hd').text('');

        // Kembalikan alert ke default
        $sectionGdP.find('#skor_rj_hd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorMorse($sectionGdP) {
        const $fields = $sectionGdP.find('[data-sm-required]');
        const $scoreBox = $sectionGdP.find('#skor_rj_sm');

        const semuaTerisi = $fields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '';
        });

        // Hasil hanya muncul jika seluruh penilaian Morse sudah diisi
        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        // Mapping: name field => value option => skor Morse
        const scoreMap = {
            rj_sm_1: { 1: 0, 2: 25 },         // Riwayat jatuh
            rj_sm_2: { 1: 0, 2: 15 },         // Diagnosa sekunder
            rj_sm_3: { 1: 0, 2: 15, 3: 30 },  // Alat bantu
            rj_sm_4: { 1: 0, 2: 20 },         // Obat
            rj_sm_5: { 1: 10, 2: 20 },        // Gaya berjalan
            rj_sm_6: { 1: 0, 2: 15 }          // Kesadaran
        };

        let skor = 0;

        $fields.each(function () {
            const name = this.name;
            const value = $(this).val();

            skor += scoreMap[name][value];
        });

        $sectionGdP.find('input[name="skor_rj_sm"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 45) {
            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko tinggi.';
        } else if (skor >= 25) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko sedang.';
        } else {
            kategori = 'Risiko Rendah';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi sesuai prosedur.';
        }

        $sectionGdP.find('#nilai_rj_sm').text(skor);

        $sectionGdP.find('#kategori_rj_sm')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $sectionGdP.find('#keterangan_rj_sm').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetMorse($sectionGdP) {

        // Reset semua pilihan
        $sectionGdP.find('[data-sm-required]').val('');

        // Reset nilai skor
        $sectionGdP.find('[name="skor_rj_sm"]').val(0);

        // Reset tampilan skor
        $sectionGdP.find('#nilai_rj_sm').text(0);

        // Sembunyikan hasil skor
        $sectionGdP.find('#skor_rj_sm').prop('hidden', true);

        // Kembalikan tampilan kategori
        $sectionGdP.find('#kategori_rj_sm')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $sectionGdP.find('#keterangan_rj_sm').text('');

        // Kembalikan alert ke default
        $sectionGdP.find('#skor_rj_sm .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorEPFRA($sectionGdP) {

        const $fields = $sectionGdP.find('[data-epfra-required]');
        const $scoreBox = $sectionGdP.find('#skor_rj_epfra');

        // Semua field harus sudah dipilih
        const semuaTerisi = $fields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '' && $(field).val() !== '0';
        });

        // Jika belum lengkap, sembunyikan hasil
        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);

            // Reset skor
            $sectionGdP.find('input[name="skor_rj_epfra"]').val(0);
            $sectionGdP.find('#nilai_rj_epfra').text(0);

            return;
        }

        // Hitung skor dari data-score option yang dipilih
        let skor = 0;

        $fields.each(function () {

            const score = parseInt(
                $(this).find('option:selected').attr('data-score')
            ) || 0;

            skor += score;
        });

        // Simpan skor
        $sectionGdP
            .find('input[name="skor_rj_epfra"]')
            .val(skor);

        // ==========================================
        // KATEGORI
        // Sesuaikan batas dengan SOP EPFRAS Anda
        // ==========================================

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 50) {

            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko tinggi.';

        } else if (skor >= 30) {

            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko sedang.';

        } else {

            kategori = 'Risiko Rendah';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi sesuai prosedur.';
        }

        // ==========================================
        // TAMPILKAN HASIL
        // ==========================================

        $sectionGdP
            .find('#nilai_rj_epfra')
            .text(skor);

        $sectionGdP
            .find('#kategori_rj_epfra')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $sectionGdP
            .find('#keterangan_rj_epfra')
            .text(keterangan);

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetEPFRA($sectionGdP) {

        // Reset semua pilihan EPFRA
        $sectionGdP.find('[data-epfra-required]').each(function () {
            $(this).val('');
        });

        // Reset skor hidden
        $sectionGdP.find('[name="skor_rj_epfra"]').val(0);

        // Reset nilai skor
        $sectionGdP.find('#nilai_rj_epfra').text(0);

        // Reset kategori
        $sectionGdP.find('#kategori_rj_epfra')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $sectionGdP.find('#keterangan_rj_epfra').text('');

        // Sembunyikan kotak hasil
        $sectionGdP.find('#skor_rj_epfra').prop('hidden', true);

        // Kembalikan warna alert ke default
        $sectionGdP.find('#skor_rj_epfra .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorMUST($sectionGdP) {
        const sgd1 = $sectionGdP.find('input[name="sgd1"]:checked').val();
        const sgd1c = $sectionGdP.find('input[name="sgd1_c"]:checked').val();
        const sgd2 = $sectionGdP.find('input[name="sgd2"]:checked').val();
        const sgd3 = $.trim($sectionGdP.find('[name="sgd3"]').val());

        const $scoreBox = $sectionGdP.find('#skor_sgd');

        // Jumlah perubahan berat badan hanya wajib bila sgd1 = Ya
        const jumlahBeratBadanWajib = sgd1 === '1';

        const semuaTerisi =
            sgd1 !== undefined &&
            sgd2 !== undefined &&
            // sgd3 !== '' &&
            (!jumlahBeratBadanWajib || sgd1c !== undefined);

        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        // Jika tidak ada perubahan berat badan, sgd1_c tidak dihitung.
        const skor =
            Number(sgd1) +
            Number(sgd2) +
            (jumlahBeratBadanWajib ? Number(sgd1c) : 0);

        // Simpan skor ke input agar ikut terkirim saat AJAX
        $sectionGdP.find('input[name="skor_sgd"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 4) {
            kategori = 'Risiko Berat';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Perlu tindak lanjut skrining gizi sesuai prosedur.';
        } else if (skor >= 2) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan monitoring dan evaluasi status gizi.';
        } else {
            kategori = 'Risiko Ringan';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi berkala.';
        }

        $sectionGdP.find('#nilai_sgd').text(skor);

        $sectionGdP.find('#kategori_sgd')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $sectionGdP.find('#keterangan_sgd').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningMUST($sectionGdP) {

        // Reset checkbox
        $sectionGdP.find('input[name="sgd1"]').prop('checked', false);
        $sectionGdP.find('input[name="sgd1_c"]').prop('checked', false);
        $sectionGdP.find('input[name="sgd2"]').prop('checked', false);

        // Reset input
        $sectionGdP.find('[name="sgd3"]').val('');

        // Reset skor
        $sectionGdP.find('[name="skor_sgd"]').val(0);

        // Reset tampilan skor
        $sectionGdP.find('#nilai_sgd').text(0);

        // Reset kategori
        $sectionGdP.find('#kategori_sgd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $sectionGdP.find('#keterangan_sgd').text('');

        // Sembunyikan hasil skor
        $sectionGdP.find('#skor_sgd').prop('hidden', true);

        // Reset alert
        $sectionGdP.find('#skor_sgd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorStrongKid($sectionGdP) {
        const fieldNames = ['sga1', 'sga2', 'sga3', 'sga4'];
        const $scoreBox = $sectionGdP.find('#skor_sga');

        const nilai = fieldNames.map(function (name) {
            return $sectionGdP.find('input[name="' + name + '"]:checked').val();
        });

        // Skor hanya tampil jika keempat pertanyaan sudah dijawab
        if (nilai.some(function (value) {
            return value === undefined;
        })) {
            $scoreBox.prop('hidden', true);
            return;
        }

        const skor = nilai.reduce(function (total, value) {
            return total + Number(value);
        }, 0);

        $sectionGdP.find('input[name="skor_sga"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 3) {
            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Perlu asesmen dan tindak lanjut gizi lebih lanjut.';
        } else if (skor === 2) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan monitoring status gizi sesuai prosedur.';
        } else {
            // Skor 0 atau 1
            kategori = 'Tidak Berisiko';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi berkala.';
        }

        $sectionGdP.find('#nilai_sga').text(skor);

        $sectionGdP.find('#kategori_sga')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $sectionGdP.find('#keterangan_sga').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningStrongKid($sectionGdP) {
        // Reset checkbox
        $sectionGdP.find('input[name="sga1"]').prop('checked', false);
        $sectionGdP.find('input[name="sga2"]').prop('checked', false);
        $sectionGdP.find('input[name="sga3"]').prop('checked', false);
        $sectionGdP.find('input[name="sga4"]').prop('checked', false);

        // Reset skor
        $sectionGdP.find('[name="skor_sga"]').val(0);

        // Reset tampilan skor
        $sectionGdP.find('#nilai_sga').text(0);

        // Reset kategori
        $sectionGdP.find('#kategori_sga')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $sectionGdP.find('#keterangan_sga').text('');

        // Sembunyikan hasil skor
        $sectionGdP.find('#skor_sga').prop('hidden', true);

        // Reset alert
        $sectionGdP.find('#skor_sga .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function resetStatusKehamilan($sectionGdP) {
        // Reset input
        $sectionGdP.find('input[name="sh_g"]').val('');
        $sectionGdP.find('input[name="sh_p"]').val('');
        $sectionGdP.find('input[name="sh_a"]').val('');
        $sectionGdP.find('input[name="sh_h"]').val('');
    }

    function resetDischargePlanningDetail($sectionGdP, name) {
        const $target = $sectionGdP.find('#tampil_' + name);

        // Reset semua checkbox di dalam card
        $target.find('input[type="checkbox"]')
            .prop('checked', false);

        // Reset semua input text
        $target.find('input[type="text"]')
            .val('');

        // Reset semua select
        $target.find('select')
            .val('');

        // Reset textarea
        $target.find('textarea')
            .val('');

        // Pastikan pilihan utama kembali ke "Tidak Ada"
        $sectionGdP.find(`[name="${name}"][value="0"]`)
            .prop('checked', true);

        $sectionGdP.find(`[name="${name}"][value="1"]`)
            .prop('checked', false);
    }

    function getDataPengkajianGdP() {
        if (!$sectionGdP.length) {
            console.warn('Section Pengkajian Medis IGD tidak ditemukan.');
            return;
        }

        const $form = $sectionGdP.find('.form-wrapper').first();

        if (!$form.length) {
            console.warn('Form Pengkajian Medis IGD tidak ditemukan.');
            return;
        }

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/gd/pr/${kunjungan}`,
            type: 'GET',

            beforeSend: function () {

            },

            success: function (res) {

                if (!res.status || !res.data) {
                    return;
                }

                const data = res.data;

                // ==========================================
                // TRIAGE
                // ==========================================
                const triage = data.triage;

                if (triage) {

                    // ------------------------------------------
                    // Cara Kedatangan
                    // ------------------------------------------
                    const kedatangan = triage.KEDATANGAN;

                    if (kedatangan) {
                        FormHelper.setSingleCheckbox($form,
                            'dd_ck',
                            kedatangan.JENIS
                        );

                        FormHelper.setValue($form,
                            'tgl_ck',
                            FormHelper.formatDateTimeLocal(kedatangan.TANGGAL)
                        );

                        FormHelper.setValue($form,
                            'tr_ck',
                            kedatangan.ALAT_TRANSPORTASI
                        );

                        FormHelper.setValue($form,
                            'dd_ck_p',
                            kedatangan.PENGANTAR
                        );

                        FormHelper.setValue($form,
                            'dd_ck_k',
                            kedatangan.ASAL_RUJUKAN
                        );

                        FormHelper.setValue($form,
                            'dd_ck_a',
                            kedatangan.KEPOLISIAN
                        );

                        FormHelper.setCheckbox($form,
                            'dd_ck_a_v',
                            kedatangan.VISUM
                        );
                    }

                    // ------------------------------------------
                    // Jenis Kasus
                    // ------------------------------------------
                    const kasus = triage.KASUS;

                    if (kasus) {

                        FormHelper.setSingleCheckbox($form,
                            'jks',
                            kasus.JENIS
                        );

                        FormHelper.setCheckbox($form,
                            'jks_kll',
                            kasus.LAKA_LANTAS
                        );

                        FormHelper.setCheckbox($form,
                            'jks_kk',
                            kasus.KECELAKAAN_KERJA
                        );

                        FormHelper.setCheckbox($form,
                            'jks_uppa',
                            kasus.UPPA
                        );

                        FormHelper.setValue($form,
                            'jks_end_dm',
                            kasus.DIMANA
                        );
                    }

                    // ====================================================
                    // RISIKO PENULARAN INFEKSI
                    // ====================================================

                    if (
                        triage.RISIKO_PENULARAN_INFEKSI !== null &&
                        triage.RISIKO_PENULARAN_INFEKSI !== undefined
                    ) {
                        FormHelper.setSingleCheckbox(
                            $form,
                            'rpi',
                            triage.RISIKO_PENULARAN_INFEKSI
                        );
                    }

                    // ------------------------------------------
                    // Anamnese
                    // ------------------------------------------
                    const anamnese = triage.ANAMNESE;

                    if (anamnese) {
                        FormHelper.setValue($form,
                            'anm_ku',
                            anamnese.KELUHAN_UTAMA
                        );

                        FormHelper.setValue($form,
                            'anm_tp',
                            anamnese.TERPIMPIN
                        );
                    }

                    // ------------------------------------------
                    // Tanda Vital
                    // ------------------------------------------
                    const tandaVital = triage.TANDA_VITAL;

                    if (tandaVital) {
                        FormHelper.setValue($form,'tv_sh', tandaVital.SUHU);
                        FormHelper.setValue($form,'tv_up', tandaVital.SISTOLE);
                        FormHelper.setValue($form,'tv_down', tandaVital.DIASTOLE);
                        FormHelper.setValue($form,'tv_nadi', tandaVital.FREK_NADI);
                        FormHelper.setValue($form,'tv_fr', tandaVital.FREK_NAFAS);
                        FormHelper.setValue($form,'tv_mu', tandaVital.METODE_UKUR);
                        FormHelper.setValue($form,'tv_sn', tandaVital.SKALA_NYERI);
                    }

                    // ------------------------------------------
                    // OBGYN
                    // ------------------------------------------
                    const obgyn = triage.OBGYN;

                    if (obgyn) {
                        FormHelper.setValue($form,'ko_ug', obgyn.USIA_GESTASI);
                        FormHelper.setValue($form,'ko_ku', obgyn.KONTRAKSI_UTERUS);
                        FormHelper.setValue($form,'ko_dj', obgyn.DETAK_JANTUNG);
                        FormHelper.setValue($form,'ko_ds', obgyn.DILATASI_SERVIKS);
                    }

                    // ------------------------------------------
                    // Kebutuhan Khusus
                    // ------------------------------------------
                    const kebutuhanKhusus = triage.KEBUTUHAN_KHUSUS;

                    if (kebutuhanKhusus) {
                        FormHelper.setValue($form,'kk_a', kebutuhanKhusus.AIRBONE);
                        FormHelper.setValue($form,'kk_d', kebutuhanKhusus.DEKONTAMINAN);
                    }

                    // ------------------------------------------
                    // Data triage lain
                    // ------------------------------------------
                    FormHelper.setValue($form,'kr', triage.KRITERIA);
                    FormHelper.setCheckbox($form,
                        'risiko_penularan_infeksi',
                        triage.RISIKO_PENULARAN_INFEKSI
                    );
                }

                // ==========================================
                // KONDISI SOSIAL
                // ==========================================
                const sosial = data.kondisi_sosial;

                if (sosial) {
                    FormHelper.setCheckbox($form,'tak', sosial.TIDAK_ADA_KELAINAN);
                    FormHelper.setCheckbox($form,'marah', sosial.MARAH);
                    FormHelper.setCheckbox($form,'cemas', sosial.CEMAS);
                    FormHelper.setCheckbox($form,'takut', sosial.TAKUT);
                    FormHelper.setCheckbox($form,'sedih', sosial.SEDIH);
                    FormHelper.setCheckbox($form,'bundir', sosial.BUNUH_DIRI);

                    FormHelper.setValue($form,'pse_lain', sosial.LAINNYA);

                    FormHelper.setSingleCheckbox($form,
                        'sm',
                        sosial.STATUS_MENTAL
                    );

                    FormHelper.setValue($form,
                        'sm2_lain',
                        sosial.MASALAH_PERILAKU
                    );

                    FormHelper.setValue($form,
                        'sm3_lain',
                        sosial.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA
                    );

                    FormHelper.setSingleCheckbox($form,
                        'hub',
                        sosial.HUBUNGAN_PASIEN_DENGAN_KELUARGA
                    );

                    FormHelper.setSingleCheckbox($form,
                        'tinggal',
                        sosial.TEMPAT_TINGGAL
                    );

                    FormHelper.setValue($form,
                        'tinggal_lain',
                        sosial.TEMPAT_TINGGAL_LAINNYA
                    );

                    FormHelper.setSingleCheckbox($form,
                        'kbt',
                        sosial.KEBIASAAN_BERIBADAH_TERATUR
                    );

                    FormHelper.setSingleCheckbox($form,
                        'nk',
                        sosial.NILAI_KEPERCAYAAN
                    );

                    FormHelper.setValue($form,
                        'nk_lain',
                        sosial.NILAI_KEPERCAYAAN_DESKRIPSI
                    );

                    FormHelper.setSingleCheckbox($form,
                        'pk',
                        sosial.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA
                    );

                    FormHelper.setValue($form,
                        'hasil',
                        sosial.PENGHASILAN_PERBULAN
                    );
                }

                // ==========================================
                // PENILAIAN NYERI
                // ==========================================
                const nyeri = data.penilaian_nyeri;
                const metodeMapSkriningNyeri = {
                    1: '#tampil_sn_nrs',
                    2: '#tampil_sn_bps',
                    3: '#tampil_sn_nips',
                    4: '#tampil_sn_flacc',
                    5: '#tampil_sn_vas'
                };

                if (nyeri) {
                    FormHelper.setSingleCheckbox($form,'sn_nyeri', nyeri.NYERI);
                    FormHelper.setSingleCheckbox($form,'sn_onset', nyeri.ONSET);
                    FormHelper.setSingleCheckbox($form,'sn_metode', nyeri.METODE);
                    FormHelper.setValue($form,'sn_pencetus', nyeri.PENCETUS);
                    FormHelper.setValue($form,'sn_gambaran', nyeri.GAMBARAN);
                    FormHelper.setValue($form,'sn_durasi', nyeri.DURASI);
                    FormHelper.setValue($form,'sn_lokasi', nyeri.LOKASI);

                    // $(metodeMapSkriningNyeri[metode]).prop('hidden', false);
                    FormHelper.setValue($form,'sn_skala', nyeri.SKALA);

                    if (nyeri.METODE === 1 || nyeri.METODE === 5) {
                        FormHelper.setValue($form, 'sn_nrs', nyeri.SKALA);
                    } else if (nyeri.METODE === 2) {
                        FormHelper.setValue($form, 'sn_bps_1', nyeri.SKOR1);
                        FormHelper.setValue($form, 'sn_bps_2', nyeri.SKOR2);
                        FormHelper.setValue($form, 'sn_bps_3', nyeri.SKOR3);
                    } else if (nyeri.METODE === 3) {
                        FormHelper.setValue($form, 'sn_nips_1', nyeri.SKOR1);
                        FormHelper.setValue($form, 'sn_nips_2', nyeri.SKOR2);
                        FormHelper.setValue($form, 'sn_nips_3', nyeri.SKOR3);
                        FormHelper.setValue($form, 'sn_nips_4', nyeri.SKOR4);
                        FormHelper.setValue($form, 'sn_nips_5', nyeri.SKOR5);
                        FormHelper.setValue($form, 'sn_nips_6', nyeri.SKOR6);
                    } else if (nyeri.METODE === 4) {
                        FormHelper.setValue($form, 'sn_flacc_1', nyeri.SKOR1);
                        FormHelper.setValue($form, 'sn_flacc_2', nyeri.SKOR2);
                        FormHelper.setValue($form, 'sn_flacc_3', nyeri.SKOR3);
                        FormHelper.setValue($form, 'sn_flacc_4', nyeri.SKOR4);
                        FormHelper.setValue($form, 'sn_flacc_5', nyeri.SKOR5);
                    } else if (nyeri.METODE === 5) {
                        FormHelper.setValue($form, 'sn_vas', nyeri.SKALA);
                    } else {}
                }

                // ==========================================
                // HUMPTY DUMPTY
                // ==========================================
                const hd = data.humpty_dumpty;

                if (hd) {
                    $('#srj_hd').prop('checked', true);
                    $('#tampil_srj_hd').prop('hidden', false);
                    FormHelper.setValue($form,'rj_usia', hd.UMUR);
                    FormHelper.setValue($form,'rj_jk', hd.JENIS_KELAMIN);

                    FormHelper.setValue($form,'rj_hd_1', hd.DIAGNOSA);
                    FormHelper.setValue($form,'rj_hd_2', hd.GANGGUAN_KONGNITIF);
                    FormHelper.setValue($form,'rj_hd_3', hd.FAKTOR_LINGKUNGAN);
                    FormHelper.setValue($form,'rj_hd_4', hd.RESPON);
                    FormHelper.setValue($form,'rj_hd_5', hd.PENGGUNAAN_OBAT);
                }

                // ==========================================
                // MORSE
                // ==========================================
                const morse = data.morse;

                if (morse) {
                    $('#srj_sm').prop('checked', true);
                    $('#tampil_srj_sm').prop('hidden', false);
                    FormHelper.setValue($form,'rj_sm_1', morse.RIWAYAT_JATUH);
                    FormHelper.setValue($form,'rj_sm_2', morse.DIAGNOSIS);
                    FormHelper.setValue($form,'rj_sm_3', morse.ALAT_BANTU);
                    FormHelper.setValue($form,'rj_sm_4', morse.HEPARIN);
                    FormHelper.setValue($form,'rj_sm_5', morse.GAYA_BERJALAN);
                    FormHelper.setValue($form,'rj_sm_6', morse.KESADARAN);
                }

                // ==========================================
                // EPFRA
                // ==========================================
                const epfra = data.epfra;

                if (epfra) {
                    $('#srj_epfra').prop('checked', true);
                    $('#tampil_srj_epfra').prop('hidden', false);
                    FormHelper.setValue($form,'rj_epfra_usia', epfra.USIA);

                    FormHelper.setValue($form,
                        'rj_epfra_1',
                        epfra.STATUS_MENTAL
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_2',
                        epfra.ELIMINASI
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_3',
                        epfra.MEDIKASI
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_4',
                        epfra.DIAGNOSIS
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_5',
                        epfra.AMBULASI
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_6',
                        epfra.NUTRISI
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_7',
                        epfra.GANGGUAN_TIDUR
                    );

                    FormHelper.setValue($form,
                        'rj_epfra_8',
                        epfra.RIWAYAT_JATUH
                    );
                }

                // ==========================================
                // MUST
                // ==========================================
                const must = data.must;

                if (must) {
                    FormHelper.setSingleCheckbox($form,
                        'sgd1',
                        must.BERAT_BADAN_SIGNIFIKAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sgd1_c',
                        must.PERUBAHAN_BERAT_BADAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sgd2',
                        must.INTAKE_MAKANAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sgd3',
                        must.KONDISI_KHUSUS
                    );

                    FormHelper.setValue($form,
                        'skor_sgd',
                        must.SKOR
                    );
                }

                // ==========================================
                // STRONG KID
                // ==========================================
                const strongKid = data.strong_kid;

                if (strongKid) {
                    FormHelper.setSingleCheckbox($form,
                        'sga1',
                        strongKid.TAMPAK_KURUS
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sga2',
                        strongKid.PENURUNAN_BERAT_BADAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sga3',
                        strongKid.DIARE_INTAKE_MAKANAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'sga4',
                        strongKid.RESIKO_MALNUTRISI
                    );

                    FormHelper.setValue($form,
                        'skor_sga',
                        strongKid.SKOR
                    );
                }

                // ==========================================
                // STATUS REPRODUKSI
                // ==========================================
                const reproduksi = data.status_reproduksi;

                if (reproduksi) {
                    FormHelper.setSingleCheckbox($form,
                        'sh',
                        reproduksi.STATUS_REPRODUKSI
                    );

                    FormHelper.setValue($form,
                        'sh_g',
                        reproduksi.HAMIL_GRAVIDA
                    );

                    FormHelper.setValue($form,
                        'sh_p',
                        reproduksi.HAMIL_PARITAS
                    );

                    FormHelper.setValue($form,
                        'sh_a',
                        reproduksi.HAMIL_ABORTUS
                    );

                    FormHelper.setValue($form,
                        'sh_h',
                        reproduksi.HPHT
                    );
                }

                // ==========================================
                // IMPLEMENTASI KEPERAWATAN
                // ==========================================
                const impkeper = data.implementasi_keperawatan;

                if (impkeper) {
                    FormHelper.setCheckbox($form,'ik_1', impkeper.IK1);
                    FormHelper.setCheckbox($form,'ik_2', impkeper.IK2);
                    FormHelper.setCheckbox($form,'ik_3', impkeper.IK3);
                    FormHelper.setCheckbox($form,'ik_4', impkeper.IK4);
                    FormHelper.setCheckbox($form,'ik_5', impkeper.IK5);
                    FormHelper.setCheckbox($form,'ik_6', impkeper.IK6);
                    FormHelper.setCheckbox($form,'ik_7', impkeper.IK7);
                    FormHelper.setCheckbox($form,'ik_8', impkeper.IK8);
                    FormHelper.setCheckbox($form,'ik_9', impkeper.IK9);
                    FormHelper.setCheckbox($form,'ik_10', impkeper.IK10);
                    FormHelper.setCheckbox($form,'ik_11', impkeper.IK11);
                    FormHelper.setCheckbox($form,'ik_12', impkeper.IK12);
                    FormHelper.setCheckbox($form,'ik_13', impkeper.IK13);
                    FormHelper.setCheckbox($form,'ik_14', impkeper.IK14);
                    FormHelper.setCheckbox($form,'ik_15', impkeper.IK15);

                    FormHelper.setValue($form,'ik_11_lain', impkeper.IK11_LAIN);
                }

                // ==========================================
                // TINDAKAN KOLABORASI
                // ==========================================
                const tinkolab = data.tindakan_kolaborasi;

                if (tinkolab) {
                    FormHelper.setCheckbox($form,'tk_1', tinkolab.TK1);
                    FormHelper.setCheckbox($form,'tk_2', tinkolab.TK2);
                    FormHelper.setCheckbox($form,'tk_3', tinkolab.TK3);
                    FormHelper.setCheckbox($form,'tk_4', tinkolab.TK4);
                    FormHelper.setCheckbox($form,'tk_5', tinkolab.TK5);
                    FormHelper.setCheckbox($form,'tk_6', tinkolab.TK6);
                    FormHelper.setCheckbox($form,'tk_7', tinkolab.TK7);
                    FormHelper.setCheckbox($form,'tk_8', tinkolab.TK8);
                    FormHelper.setCheckbox($form,'tk_9', tinkolab.TK9);
                    FormHelper.setCheckbox($form,'tk_10', tinkolab.TK10);
                    FormHelper.setCheckbox($form,'tk_11', tinkolab.TK11);
                    FormHelper.setCheckbox($form,'tk_12', tinkolab.TK12);
                    FormHelper.setCheckbox($form,'tk_13', tinkolab.TK13);
                    FormHelper.setCheckbox($form,'tk_14', tinkolab.TK14);
                    FormHelper.setCheckbox($form,'tk_15', tinkolab.TK15);
                    FormHelper.setCheckbox($form,'tk_16', tinkolab.TK16);
                    FormHelper.setCheckbox($form,'tk_17', tinkolab.TK17);
                    FormHelper.setCheckbox($form,'tk_18', tinkolab.TK18);
                    FormHelper.setCheckbox($form,'tk_19', tinkolab.TK19);
                    FormHelper.setCheckbox($form,'tk_20', tinkolab.TK20);
                    FormHelper.setCheckbox($form,'tk_21', tinkolab.TK21);
                    FormHelper.setCheckbox($form,'tk_22', tinkolab.TK22);
                    FormHelper.setCheckbox($form,'tk_23', tinkolab.TK23);
                    FormHelper.setCheckbox($form,'tk_24', tinkolab.TK24);
                    FormHelper.setCheckbox($form,'tk_25', tinkolab.TK25);
                    FormHelper.setCheckbox($form,'tk_26', tinkolab.TK26);
                    FormHelper.setCheckbox($form,'tk_27', tinkolab.TK27);

                    FormHelper.setValue($form,'tk_9_lain', tinkolab.TK9_LAIN);
                    FormHelper.setValue($form,'tk_14_lain', tinkolab.TK14_LAIN);
                    FormHelper.setValue($form,'tk_16_lain', tinkolab.TK16_LAIN);
                    FormHelper.setValue($form,'tk_17_lain', tinkolab.TK17_LAIN);
                    FormHelper.setValue($form,'tk_18_lain', tinkolab.TK18_LAIN);
                    FormHelper.setValue($form,'tk_19_lain', tinkolab.TK19_LAIN);
                    FormHelper.setValue($form,'tk_20_lain', tinkolab.TK20_LAIN);

                    FormHelper.setValue($form,'tk_1_dt', tinkolab.TK1_TIME);
                    FormHelper.setValue($form,'tk_2_dt', tinkolab.TK2_TIME);
                    FormHelper.setValue($form,'tk_3_dt', tinkolab.TK3_TIME);
                    FormHelper.setValue($form,'tk_4_dt', tinkolab.TK4_TIME);
                    FormHelper.setValue($form,'tk_5_dt', tinkolab.TK5_TIME);
                    FormHelper.setValue($form,'tk_6_dt', tinkolab.TK6_TIME);
                    FormHelper.setValue($form,'tk_7_dt', tinkolab.TK7_TIME);
                    FormHelper.setValue($form,'tk_8_dt', tinkolab.TK8_TIME);
                    FormHelper.setValue($form,'tk_9_dt', tinkolab.TK9_TIME);
                    FormHelper.setValue($form,'tk_10_dt', tinkolab.TK10_TIME);
                    FormHelper.setValue($form,'tk_11_dt', tinkolab.TK11_TIME);
                    FormHelper.setValue($form,'tk_12_dt', tinkolab.TK12_TIME);
                    FormHelper.setValue($form,'tk_13_dt', tinkolab.TK13_TIME);
                    FormHelper.setValue($form,'tk_14_dt', tinkolab.TK14_TIME);
                    FormHelper.setValue($form,'tk_15_dt', tinkolab.TK15_TIME);
                    FormHelper.setValue($form,'tk_16_dt', tinkolab.TK16_TIME);
                    FormHelper.setValue($form,'tk_17_dt', tinkolab.TK17_TIME);
                    FormHelper.setValue($form,'tk_18_dt', tinkolab.TK18_TIME);
                    FormHelper.setValue($form,'tk_19_dt', tinkolab.TK19_TIME);
                    FormHelper.setValue($form,'tk_20_dt', tinkolab.TK20_TIME);
                    FormHelper.setValue($form,'tk_21_dt', tinkolab.TK21_TIME);
                    FormHelper.setValue($form,'tk_22_dt', tinkolab.TK22_TIME);
                    FormHelper.setValue($form,'tk_23_dt', tinkolab.TK23_TIME);
                    FormHelper.setValue($form,'tk_24_dt', tinkolab.TK24_TIME);
                    FormHelper.setValue($form,'tk_25_dt', tinkolab.TK25_TIME);
                    FormHelper.setValue($form,'tk_26_dt', tinkolab.TK26_TIME);
                    FormHelper.setValue($form,'tk_27_dt', tinkolab.TK27_TIME);
                }

                // ==========================================
                // MASALAH KEPERAWATAN
                // ==========================================
                const masalah = data.masalah_keperawatan;

                if (masalah) {
                    FormHelper.setCheckbox($form,'dmk_1', masalah.NYERI);
                    FormHelper.setCheckbox($form,'dmk_2', masalah.CEMAS);
                    FormHelper.setCheckbox($form,'dmk_3', masalah.PERUBAHAN_NUTRISI);
                    FormHelper.setCheckbox($form,'dmk_4', masalah.GANGGUAN_PERNAFASAN);
                    FormHelper.setCheckbox($form,'dmk_5', masalah.GANGGUAN_PERFUSI_JARINGAN);
                    FormHelper.setCheckbox($form,'dmk_6', masalah.GANGGUAN_VOLUME_CAIRAN);
                    FormHelper.setCheckbox($form,'dmk_7', masalah.POTENSI_INFEKSI);
                    FormHelper.setCheckbox($form,'dmk_8', masalah.HIPERTEMI);
                    FormHelper.setCheckbox($form,'dmk_9', masalah.TAKUT);
                    FormHelper.setCheckbox($form,
                        'dmk_10',
                        masalah.KETIDAKEFEKTIFAN_POLA_MAKAN
                    );

                    FormHelper.setValue($form,
                        'dmk_lain',
                        masalah.MASALAH_LAIN
                    );
                }

                // ==========================================
                // DISCHARGE PLANNING - FAKTOR RISIKO
                // ==========================================
                const dpFaktor = data.discharge_faktor_risiko;

                if (dpFaktor) {
                    FormHelper.setSingleCheckbox($form,
                        'dp_1',
                        dpFaktor.PASIEN_TINGGAL_SENDIRI
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_2',
                        dpFaktor.PASIEN_KHAWATIR_KETIKA_DIRUMAH
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_3',
                        dpFaktor.PASIEN_TAK_ADA_YANG_MERAWAT
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_4',
                        dpFaktor.PASIEN_DILANTAI_ATAS
                    );
                }

                // ==========================================
                // DISCHARGE PLANNING - SKRINING
                // ==========================================
                const dpSkrining = data.discharge_skrining;

                if (dpSkrining) {

                    FormHelper.setSingleCheckbox($form,
                        'dp_5',
                        dpSkrining.PERAWATAN_LANJUTAN_MEDIS
                    );

                    FormHelper.setCheckbox($form,
                        'dp_5_1',
                        dpSkrining.PLM_KATETER_URIN
                    );

                    FormHelper.setCheckbox($form,
                        'dp_5_2',
                        dpSkrining.PLM_TRAECHOSTOMY
                    );

                    FormHelper.setCheckbox($form,
                        'dp_5_3',
                        dpSkrining.PLM_NGT
                    );

                    FormHelper.setCheckbox($form,
                        'dp_5_4',
                        dpSkrining.PLM_COLOSTOMY
                    );

                    // dp_5_lain -> KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA
                    FormHelper.setValue($form,
                        'dp_5_lain',
                        dpSkrining.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA
                    );

                    // Kriteria dasar
                    FormHelper.setSingleCheckbox($form,
                        'dp_6',
                        dpSkrining.PASIEN_PULANG
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_7',
                        dpSkrining.PASIEN_MENGAJUKAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_8',
                        dpSkrining.TIDAK_ADA_KRITERIA
                    );

                    // KPB
                    FormHelper.setSingleCheckbox($form,
                        'dp_9',
                        dpSkrining.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_1',
                        dpSkrining.KPB_RAWAT_LUKA
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_2',
                        dpSkrining.KPB_TB
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_3',
                        dpSkrining.KPB_DM_TERAPI_INSULIN
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_4',
                        dpSkrining.KPB_PPOK
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_5',
                        dpSkrining.KPB_PASIEN_KEMO
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_6',
                        dpSkrining.KPB_HIV
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_7',
                        dpSkrining.KPB_DM
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_8',
                        dpSkrining.KPB_STROKE
                    );

                    FormHelper.setCheckbox($form,
                        'dp_9_9',
                        dpSkrining.KPB_CKD
                    );

                    // Penggunaan alat medis
                    FormHelper.setSingleCheckbox($form,
                        'dp_10',
                        dpSkrining.PENGGUNAAN_ALAT_MEDIS_PAM
                    );

                    FormHelper.setCheckbox($form,
                        'dp_10_1',
                        dpSkrining.PAM_KATETER_URIN
                    );

                    FormHelper.setCheckbox($form,
                        'dp_10_2',
                        dpSkrining.PAM_TRAECHOSTOMY
                    );

                    FormHelper.setCheckbox($form,
                        'dp_10_3',
                        dpSkrining.PAM_NGT
                    );

                    FormHelper.setCheckbox($form,
                        'dp_10_4',
                        dpSkrining.PAM_COLOSTOMY
                    );

                    FormHelper.setValue($form,
                        'dp_10_lain',
                        dpSkrining.PAM_LAINNYA
                    );

                    // Skrining lanjutan
                    FormHelper.setSingleCheckbox($form,
                        'dp_11',
                        dpSkrining.SKRINING_LANJUTAN
                    );

                    FormHelper.setSingleCheckbox($form,
                        'dp_11_skrining',
                        dpSkrining.SKRINING
                    );
                }

                // ==========================================
                // Jalankan ulang kalkulasi / tampilan
                // ==========================================
                // $form.find('input, select, textarea').trigger('change');
            },

            error: function (xhr) {
                let message = 'Data gagal dimuat.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            },

            complete: function () {

            }
        });
    }

    function saveDataPengkajianGdP(btn) {
        const $button = $(btn);
        const $sectionGdP = $('#gd_perawat');

        const data = getFormDataByName($sectionGdP, {
            NOKUNJ: $sectionGdP.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/gd/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (res) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: res.message || "Data berhasil disimpan",
                    showConfirmButton: false,
                    timer: 1500,
                    backdrop: `
                        rgba(0,0,123,0.4)
                        url("/images/nyan-cat.gif")
                        left top
                        no-repeat
                    `
                });
                getDataPengkajianGdP();
            },

            error: function (xhr) {
                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            },

            complete: function () {
                $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    }
</script>
