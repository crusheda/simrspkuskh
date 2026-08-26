<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-success">RAWAT DARURAT</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
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
                            <input type="datetime-local" class="form-control form-control-sm" name="tgl_ck" value="{{ $list['pasien']->TGL_KEDATANGAN }}">
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
                <div class="card card-body border border-dashed border-warning">
                    <h6 class="fs-16">Tanda Vital</h6>
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
                            <div class="form-group mb-3">
                                <label class="form-label">Tekanan Darah (mmHg)</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input type="number" class="form-control" name="p_td_up">
                                    <div class="input-group-text"> / </div>
                                    <input type="number" class="form-control" name="p_td_down">
                                    <div class="input-group-text"> mmHg </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
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
                            <div class="form-group mb-3">
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
                                {{-- <div class="col-md-12">
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
                                                <div class="input-group-text"> mm / </div>
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
                                    </div>
                                </div> --}}
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
                                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_abn" value="0" checked="">
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
                                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="p_kulit" value="1" checked="">
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
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <h6>Skala Nyeri</h6>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control" name="p_sn">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h6>Metode Ukur</h6>
                                                <input type="text" class="form-control" name="p_mu">
                                            </div>
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
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial', ['section' => '#gd_perawat'])
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-success mb-3">
                    <h6 class="mb-3">SKRINING NYERI</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                        [
                            'section' => '#gd_perawat',
                            'metodeNyeri' => ['nrs', 'bps', 'nips', 'flacc', 'vas']
                        ]
                    )
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-secondary mb-3">
                    <h6>SKRINING RESIKO JATUH</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_epfra', ['section' => '#gd_perawat'])
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary mb-3">
                    <h6 class="mb-3">SKRINING GIZI</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#gd_perawat'])
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
                                    <td>Terapi Oksigenasi</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_2"></td>
                                    <td><input type="time" class="form-control" name="tk_2_dt"></td>
                                    <td>Infus</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_3"></td>
                                    <td><input type="time" class="form-control" name="tk_3_dt"></td>
                                    <td>Injeksi</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_4"></td>
                                    <td><input type="time" class="form-control" name="tk_4_dt"></td>
                                    <td>NGT / OGT</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_5"></td>
                                    <td><input type="time" class="form-control" name="tk_5_dt"></td>
                                    <td>Kateter Urin (DC)</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_6"></td>
                                    <td><input type="time" class="form-control" name="tk_6_dt"></td>
                                    <td>EKG</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_7"></td>
                                    <td><input type="time" class="form-control" name="tk_7_dt"></td>
                                    <td>Suction</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_8"></td>
                                    <td><input type="time" class="form-control" name="tk_8_dt"></td>
                                    <td>OPA</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_9"></td>
                                    <td><input type="time" class="form-control" name="tk_9_dt"></td>
                                    <td>Collar Neck</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_10"></td>
                                    <td><input type="time" class="form-control" name="tk_10_dt"></td>
                                    <td>Resusitasi</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_11"></td>
                                    <td><input type="time" class="form-control" name="tk_11_dt"></td>
                                    <td>Nebulizer</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_12"></td>
                                    <td><input type="time" class="form-control" name="tk_12_dt"></td>
                                    <td>Medikasi</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_13"></td>
                                    <td><input type="time" class="form-control" name="tk_13_dt"></td>
                                    <td>Ekstraksi Corpal</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_14"></td>
                                    <td><input type="time" class="form-control" name="tk_14_dt"></td>
                                    <td>Hecting</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_15"></td>
                                    <td><input type="time" class="form-control" name="tk_15_dt"></td>
                                    <td>Bilas Lambung</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_16"></td>
                                    <td><input type="time" class="form-control" name="tk_16_dt"></td>
                                    <td>Bidai</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_18"></td>
                                    <td><input type="time" class="form-control" name="tk_18_dt"></td>
                                    <td>Tampon</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_99"></td>
                                    <td><input type="time" class="form-control" name="tk_99_dt"></td>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Lain-lain</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_99_lain"></div></td>
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
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#gd_perawat'])
                {{-- <h6 class="mb-2">Kriteria Perencanaan Pulang (Discharge Planning)</h6>
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
                </div> --}}
            </div>
        </div>
    </div>
    <div class="form-footer d-flex justify-content-between">
        <button type="button" class="btn btn-subtle-info btnLihatCPPT" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat CPPT" onclick="showCppt('{{ $list['kunjungan'] }}')">
            <i class="ri-booklet-line me-1"></i> Lihat CPPT
        </button>
        <button class="btn btn-success" onclick="triggerSaveSubDataPengkajianGdP(); saveDataPengkajianGdP(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    var $sectionGdP = $('#gd_perawat');
    $(document).ready(function() {
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

        // HITUNG GCS TANDA VITAL
        $sectionGdP.on(
            'input',
            "input[name='p_gcs_e'], input[name='p_gcs_v'], input[name='p_gcs_m']",
            function () {
                FormHelper.hitungGCS($sectionGdP, 'p_gcs');
            }
        );

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
        // $sectionGdP.on('change', '.single-checkbox-bos', function () {

        //     // Hanya proses checkbox yang sedang checked
        //     if (!this.checked) {
        //         return;
        //     }

        //     const name = this.name;
        //     const value = String(this.value);

        //     // Hanya dp_1, dp_2, dp_3, dst.
        //     if (!/^dp_\d+$/.test(name)) {
        //         return;
        //     }

        //     const $target = $sectionGdP.find('#tampil_' + name);

        //     if (value === '1') {

        //         // ADA
        //         $target.prop('hidden', false);

        //     } else {

        //         // TIDAK ADA
        //         $target.prop('hidden', true);

        //         resetDischargePlanningDetail($sectionGdP, name);
        //     }
        // });

        // INIT FUNCTION ------------------------------------------------------------------------------
        resetDatangCara($sectionGdP);
        resetJenisKasus($sectionGdP);

        getDataPengkajianGdP();
    })

    function triggerSaveSubDataPengkajianGdP() {
        $sectionGdP.find('.btn-save-sub-pengkajian').each(function () {
            $(this).trigger('click');
        });
    }

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

    function resetStatusKehamilan($sectionGdP) {
        // Reset input
        $sectionGdP.find('input[name="sh_g"]').val('');
        $sectionGdP.find('input[name="sh_p"]').val('');
        $sectionGdP.find('input[name="sh_a"]').val('');
        $sectionGdP.find('input[name="sh_h"]').val('');
    }

    // function resetDischargePlanningDetail($sectionGdP, name) {
    //     const $target = $sectionGdP.find('#tampil_' + name);

    //     // Reset semua checkbox di dalam card
    //     $target.find('input[type="checkbox"]')
    //         .prop('checked', false);

    //     // Reset semua input text
    //     $target.find('input[type="text"]')
    //         .val('');

    //     // Reset semua select
    //     $target.find('select')
    //         .val('');

    //     // Reset textarea
    //     $target.find('textarea')
    //         .val('');

    //     // Pastikan pilihan utama kembali ke "Tidak Ada"
    //     $sectionGdP.find(`[name="${name}"][value="0"]`)
    //         .prop('checked', true);

    //     $sectionGdP.find(`[name="${name}"][value="1"]`)
    //         .prop('checked', false);
    // }

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
                    // const tandaVital = triage.TANDA_VITAL;

                    // if (tandaVital) {
                    //     FormHelper.setValue($form,'tv_sh', tandaVital.SUHU);
                    //     FormHelper.setValue($form,'tv_up', tandaVital.SISTOLE);
                    //     FormHelper.setValue($form,'tv_down', tandaVital.DIASTOLE);
                    //     FormHelper.setValue($form,'tv_nadi', tandaVital.FREK_NADI);
                    //     FormHelper.setValue($form,'tv_fr', tandaVital.FREK_NAFAS);
                    //     FormHelper.setValue($form,'tv_mu', tandaVital.METODE_UKUR);
                    //     FormHelper.setValue($form,'tv_sn', tandaVital.SKALA_NYERI);
                    // }
                    const tandaVital = data.tanda_vital || {};

                    // Keadaan Umum
                    FormHelper.setValue(
                        $form,
                        'p_keu',
                        tandaVital.KEADAAN_UMUM
                    );

                    // Tingkat Kesadaran
                    FormHelper.setValue(
                        $form,
                        'p_tks',
                        tandaVital.TINGKAT_KESADARAN
                    );

                    // Frekuensi Nafas
                    FormHelper.setValue(
                        $form,
                        'p_fr',
                        tandaVital.FREKUENSI_NAFAS
                    );

                    // Frekuensi Nafas - Simetris / Asimetris
                    // if (tandaVital.FREKUENSI_NAFAS_CB !== null) {
                    //     $('input[name="fr_cb"]')
                    //         .prop('checked', false);

                    //     $(`input[name="fr_cb"][value="${tandaVital.FREKUENSI_NAFAS_CB}"]`)
                    //         .prop('checked', true);
                    // }
                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_fr_cb',
                        tandaVital.FREKUENSI_NAFAS_CB
                    );

                    // Frekuensi Nadi
                    FormHelper.setValue(
                        $form,
                        'p_nadi',
                        tandaVital.FREKUENSI_NADI
                    );
                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_fr_nadi',
                        tandaVital.FREKUENSI_NADI_CB
                    )

                    // Tekanan Darah
                    FormHelper.setValue(
                        $form,
                        'p_td_up',
                        tandaVital.SISTOLIK
                    );

                    FormHelper.setValue(
                        $form,
                        'p_td_down',
                        tandaVital.DISTOLIK
                    );

                    // Suhu
                    FormHelper.setValue(
                        $form,
                        'p_suhu',
                        tandaVital.SUHU
                    );

                    // SpO2
                    FormHelper.setValue(
                        $form,
                        'p_spo2',
                        tandaVital.SATURASI_O2
                    );

                    // GCS
                    FormHelper.setValue(
                        $form,
                        'p_gcs_e',
                        tandaVital.EYE
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_v',
                        tandaVital.VERBAL
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_m',
                        tandaVital.MOTORIK
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_t',
                        tandaVital.GCS
                    );


                    // VAS
                    FormHelper.setValue(
                        $form,
                        'p_vas',
                        tandaVital.VAS
                    );


                    // Jalan Nafas
                    if (tandaVital.JALAN_NAFAS !== null) {
                        $('input[name="p_jn"]')
                            .prop('checked', false);

                        $(`input[name="p_jn"][value="${tandaVital.JALAN_NAFAS}"]`)
                            .prop('checked', true);
                    }


                    // Alat Bantu Nafas
                    if (tandaVital.ALAT_BANTU_NAFAS !== null) {
                        $('input[name="p_abn"]')
                            .prop('checked', false);

                        $(`input[name="p_abn"][value="${tandaVital.ALAT_BANTU_NAFAS}"]`)
                            .prop('checked', true);
                    }


                    // Kulit
                    if (tandaVital.KULIT !== null) {
                        $('input[name="p_kulit"]')
                            .prop('checked', false);

                        $(`input[name="p_kulit"][value="${tandaVital.KULIT}"]`)
                            .prop('checked', true);
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
                // const sosial = data.kondisi_sosial;

                // if (sosial) {
                //     FormHelper.setCheckbox($form,'tak', sosial.TIDAK_ADA_KELAINAN);
                //     FormHelper.setCheckbox($form,'marah', sosial.MARAH);
                //     FormHelper.setCheckbox($form,'cemas', sosial.CEMAS);
                //     FormHelper.setCheckbox($form,'takut', sosial.TAKUT);
                //     FormHelper.setCheckbox($form,'sedih', sosial.SEDIH);
                //     FormHelper.setCheckbox($form,'bundir', sosial.BUNUH_DIRI);

                //     FormHelper.setValue($form,'pse_lain', sosial.LAINNYA);

                //     FormHelper.setSingleCheckbox($form,
                //         'sm',
                //         sosial.STATUS_MENTAL
                //     );

                //     FormHelper.setValue($form,
                //         'sm2_lain',
                //         sosial.MASALAH_PERILAKU
                //     );

                //     FormHelper.setValue($form,
                //         'sm3_lain',
                //         sosial.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'hub',
                //         sosial.HUBUNGAN_PASIEN_DENGAN_KELUARGA
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'tinggal',
                //         sosial.TEMPAT_TINGGAL
                //     );

                //     FormHelper.setValue($form,
                //         'tinggal_lain',
                //         sosial.TEMPAT_TINGGAL_LAINNYA
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'kbt',
                //         sosial.KEBIASAAN_BERIBADAH_TERATUR
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'nk',
                //         sosial.NILAI_KEPERCAYAAN
                //     );

                //     FormHelper.setValue($form,
                //         'nk_lain',
                //         sosial.NILAI_KEPERCAYAAN_DESKRIPSI
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'pk',
                //         sosial.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA
                //     );

                //     FormHelper.setValue($form,
                //         'hasil',
                //         sosial.PENGHASILAN_PERBULAN
                //     );
                // }

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
                    FormHelper.setCheckbox($form,'tk_99', tinkolab.TK99);

                    FormHelper.setValue($form,'tk_99_lain', tinkolab.TK99_LAIN);

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
                    FormHelper.setValue($form,'tk_99_dt', tinkolab.TK99_TIME);
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
                    FormHelper.setCheckbox($form,'dmk_8', masalah.HIPERTERMI);
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
                // const dpFaktor = data.discharge_faktor_risiko;

                // if (dpFaktor) {
                //     FormHelper.setSingleCheckbox($form,
                //         'dp_1',
                //         dpFaktor.PASIEN_TINGGAL_SENDIRI
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_2',
                //         dpFaktor.PASIEN_KHAWATIR_KETIKA_DIRUMAH
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_3',
                //         dpFaktor.PASIEN_TAK_ADA_YANG_MERAWAT
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_4',
                //         dpFaktor.PASIEN_DILANTAI_ATAS
                //     );
                // }

                // ==========================================
                // DISCHARGE PLANNING - SKRINING
                // ==========================================
                // const dpSkrining = data.discharge_skrining;

                // if (dpSkrining) {

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_5',
                //         dpSkrining.PERAWATAN_LANJUTAN_MEDIS
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_5_1',
                //         dpSkrining.PLM_KATETER_URIN
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_5_2',
                //         dpSkrining.PLM_TRAECHOSTOMY
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_5_3',
                //         dpSkrining.PLM_NGT
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_5_4',
                //         dpSkrining.PLM_COLOSTOMY
                //     );

                //     // dp_5_lain -> KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA
                //     FormHelper.setValue($form,
                //         'dp_5_lain',
                //         dpSkrining.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA
                //     );

                //     // Kriteria dasar
                //     FormHelper.setSingleCheckbox($form,
                //         'dp_6',
                //         dpSkrining.PASIEN_PULANG
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_7',
                //         dpSkrining.PASIEN_MENGAJUKAN
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_8',
                //         dpSkrining.TIDAK_ADA_KRITERIA
                //     );

                //     // KPB
                //     FormHelper.setSingleCheckbox($form,
                //         'dp_9',
                //         dpSkrining.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_1',
                //         dpSkrining.KPB_RAWAT_LUKA
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_2',
                //         dpSkrining.KPB_TB
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_3',
                //         dpSkrining.KPB_DM_TERAPI_INSULIN
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_4',
                //         dpSkrining.KPB_PPOK
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_5',
                //         dpSkrining.KPB_PASIEN_KEMO
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_6',
                //         dpSkrining.KPB_HIV
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_7',
                //         dpSkrining.KPB_DM
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_8',
                //         dpSkrining.KPB_STROKE
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_9_9',
                //         dpSkrining.KPB_CKD
                //     );

                //     // Penggunaan alat medis
                //     FormHelper.setSingleCheckbox($form,
                //         'dp_10',
                //         dpSkrining.PENGGUNAAN_ALAT_MEDIS_PAM
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_10_1',
                //         dpSkrining.PAM_KATETER_URIN
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_10_2',
                //         dpSkrining.PAM_TRAECHOSTOMY
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_10_3',
                //         dpSkrining.PAM_NGT
                //     );

                //     FormHelper.setCheckbox($form,
                //         'dp_10_4',
                //         dpSkrining.PAM_COLOSTOMY
                //     );

                //     FormHelper.setValue($form,
                //         'dp_10_lain',
                //         dpSkrining.PAM_LAINNYA
                //     );

                //     // Skrining lanjutan
                //     FormHelper.setSingleCheckbox($form,
                //         'dp_11',
                //         dpSkrining.SKRINING_LANJUTAN
                //     );

                //     FormHelper.setSingleCheckbox($form,
                //         'dp_11_skrining',
                //         dpSkrining.SKRINING
                //     );
                // }

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
