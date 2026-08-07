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
                            <input type="date" class="form-control form-control-sm" name="tgl_ck">
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
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_kll" value="1">
                                    <label class="form-check-label"> Kecelakaan Lalu Lintas </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_kk" value="2">
                                    <label class="form-check-label"> Kecelakaan Kerja </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_uppa" value="3">
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
                                        <input class="form-check-input check-primary" type="checkbox" name="jks_end" value="1">
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
                <h6>Anamnese</h6>
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
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tv_up">
                                <div class="input-group-text"> / </div>
                                <input type="text" class="form-control" name="tv_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nafas (RR)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tv_fr">
                                <span class="input-group-text">X/menit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nadi (HR)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tv_nadi">
                                <div class="input-group-text">X/menit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Suhu (OC)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tv_sh">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="form-label">Skala Nyeri</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tv_sn">
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
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Khusus Obgyn</h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Usia Gestasi</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="ko_ug">
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
                                <input type="text" class="form-control" name="ko_dj">
                                <div class="input-group-text">X/menit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Dilatasi Serviks</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="ko_ds">
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
                                <input type="range" class="form-range" min="0" max="10" step="1" value="0" id="sn_nrs">
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
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>1 = rileks</h6>
                                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Ekspresi Wajah</th>
                                            <td>
                                                <h6>1 = rileks</h6>
                                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
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
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto"  min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Tangisan</th>
                                            <td>
                                                <h6>0 = Tidak menangis</h6>
                                                <h6>1 = Meringis</h6>
                                                <h6>2 = Menangis kuat</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Gerakan Lengan</th>
                                            <td>
                                                <h6>0 = Relaksasi</h6>
                                                <h6>1 = Fleksi / ekstensi</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Gerakan Tungkai</th>
                                            <td>
                                                <h6>0 = relaksasi</h6>
                                                <h6>1 = Fleksi / ekstensi</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Status Terjaga</th>
                                            <td>
                                                <h6>0 = Tidur / bangun</h6>
                                                <h6>1 = Rewel</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Pola Nafas</th>
                                            <td>
                                                <h6>0 = Relaksasi</h6>
                                                <h6>1 = Perubahan pola nafas</h6>
                                            </td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
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
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Kaki</th>
                                            <td>Gerakan normal / relaksasi</td>
                                            <td>Tidak tenang</td>
                                            <td>Kaki dibuat menendang / menarik diri</td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Aktivitas</th>
                                            <td>Tidur, posisi normal mudah bergerak</td>
                                            <td>Gerakan menggeliat, berguling, kaku</td>
                                            <td>Melengkungkan punggung / kaku / menghentak</td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Menangis</th>
                                            <td>Tidak menangis (bangung / tidur)</td>
                                            <td>Mengerang, merengek-rengek</td>
                                            <td>Menangis terus-menerus, terisak, menjerit</td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                        </tr>
                                        <tr>
                                            <th>Bersuara</th>
                                            <td>Bersuara normal, tenang</td>
                                            <td>Tenang bila dipeluk, digendong, atau diajak bicara</td>
                                            <td>Sulit untuk ditenangkan</td>
                                            <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
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
                                <input type="range" class="form-range" min="0" max="10" step="1" value="0" id="sn_vas">
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
                            <thead class="text-uppercase">
                                <tr class="table-light">
                                    <th class="text-center">Implementasi Keperawatan</th>
                                    <th class="text-center">✔</th>
                                    <th class="text-center">TGL / JAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Melakukan observasi TTV</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_1"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_1_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan observasi keadaan umum pasien</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_2"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_2_dt"></td>
                                </tr>
                                <tr>
                                    <td>Memonitor intake output</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_3"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_3_dt"></td>
                                </tr>
                                <tr>
                                    <td>Memonitor pernafasan : irama, pengembangan dinding dada, penggunaan otot tambahan pernafasan bunyi nafas</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_4"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_4_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan pemasangan Oximetri</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_5"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_5_dt"></td>
                                </tr>
                                <tr>
                                    <td>Mengobservasi produk sputum, jumlah, warna, dan kekentalan</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_6"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_6_dt"></td>
                                </tr>
                                <tr>
                                    <td>Memberikan posisi semi fowler atau posisi miring yang nyaman</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_7"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_7_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan pemasangan OPA</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_8"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_8_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan Suction bila perlu</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_9"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_9_dt"></td>
                                </tr>
                                <tr>
                                    <td>Mengajarkan pasien untuk nafas dalam dan batuk efektif</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_10"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_10_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Memberikan oksigen</div> <input type="text" class="form-control form-control-sm w-auto" name="ik_11_inp"> <div class="flex-shrink-0">liter/menit</div></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_11"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_11_dt"></td>
                                </tr>
                                <tr>
                                    <td>Mengibolisasikan daerah cedera : memasang bidai / spalk / sling</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_12"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_12_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan perawatan luka</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_13"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_13_dt"></td>
                                </tr>
                                <tr>
                                    <td>Mengajarkan manajemen pengelolaan nyeri</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_14"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_14_dt"></td>
                                </tr>
                                <tr>
                                    <td>Melakukan tindakan dengan teknik aseptic</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="ik_15"></td>
                                    <td><input type="datetime-local" class="form-control" name="ik_15_dt"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h6 class="mb-3">Tindakan Kolaborasi</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-display">
                            <thead class="text-uppercase">
                                <tr class="table-light">
                                    <th class="text-center">Tindakan Kolaborasi</th>
                                    <th class="text-center">✔</th>
                                    <th class="text-center">TGL / JAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>OPA</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_1"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_1_dt"></td>
                                </tr>
                                <tr>
                                    <td>O2</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_2"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_2_dt"></td>
                                </tr>
                                <tr>
                                    <td>Suction</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_3"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_3_dt"></td>
                                </tr>
                                <tr>
                                    <td>Nasal kanul</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_4"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_4_dt"></td>
                                </tr>
                                <tr>
                                    <td>Coliat neck</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_5"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_5_dt"></td>
                                </tr>
                                <tr>
                                    <td>Masker</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_6"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_6_dt"></td>
                                </tr>
                                <tr>
                                    <td>Resusitasi</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_7"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_7_dt"></td>
                                </tr>
                                <tr>
                                    <td>Nebiizer</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_8"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_8_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Infus</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_9_inp"></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_9"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_9_dt"></td>
                                </tr>
                                <tr>
                                    <td>Kateter urine</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_10"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_10_dt"></td>
                                </tr>
                                <tr>
                                    <td>NGT / OGT</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_11"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_11_dt"></td>
                                </tr>
                                <tr>
                                    <td>Bilas lambung</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_12"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_12_dt"></td>
                                </tr>
                                <tr>
                                    <td>Jahitan</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_13"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_13_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Rontgen</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_14_inp"></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_14"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_14_dt"></td>
                                </tr>
                                <tr>
                                    <td>CT Scan</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_15"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_15_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Lab. Darah</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_16_inp"></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_16"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_16_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Lain-lain</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_17_inp"></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_17"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_17_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Hecting permanen</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_18_inp"></div> <div class="flex-shrink-0">jahitan</div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_18"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_18_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">Hecting situasi</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_19_inp"></div> <div class="flex-shrink-0">jahitan</div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_19"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_19_dt"></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex gap-3"><div class="flex-shrink-0">ATS, skin test, hasil</div> <input type="text" class="form-control form-control-sm w-auto" name="tk_20_inp"></div></td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_20"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_20_dt"></td>
                                </tr>
                                <tr>
                                    <td>Debridemen</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_21"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_21_dt"></td>
                                </tr>
                                <tr>
                                    <td>Corpus alianum</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_22"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_22_dt"></td>
                                </tr>
                                <tr>
                                    <td>Combustio</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_23"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_23_dt"></td>
                                </tr>
                                <tr>
                                    <td>Hidung</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_24"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_24_dt"></td>
                                </tr>
                                <tr>
                                    <td>Dekubitus</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_25"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_25_dt"></td>
                                </tr>
                                <tr>
                                    <td>Mulut</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_26"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_26_dt"></td>
                                </tr>
                                <tr>
                                    <td>Telinga</td>
                                    <td class="text-center"><input class="form-check-input check-primary" type="checkbox" name="tk_27"></td>
                                    <td><input type="datetime-local" class="form-control" name="tk_27_dt"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h6 class="mb-3">Daftar Masalah Keperawatan</h6>
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_1" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_2" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_3" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_4" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_5" value="0" checked="">
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
                                            <input class="form-check-input" type="checkbox" name="dp_5_1" value="1">
                                            <label class="form-check-label">
                                                Kateter Urin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_2" value="1">
                                            <label class="form-check-label">
                                                Traechostomy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_3" value="1">
                                            <label class="form-check-label">
                                                NGT
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_5_4" value="1">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_9" value="0" checked="">
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
                                            <input class="form-check-input" type="checkbox" name="dp_9_1" value="1">
                                            <label class="form-check-label">
                                                Rawat luka
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_2" value="1">
                                            <label class="form-check-label">
                                                TB
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_3" value="1">
                                            <label class="form-check-label">
                                                DM dengan terapi insulin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_4" value="1">
                                            <label class="form-check-label">
                                                PPOK
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_5" value="1">
                                            <label class="form-check-label">
                                                Pasien kemoterapi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_6" value="1">
                                            <label class="form-check-label">
                                                HIV / AIDS
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_7" value="1">
                                            <label class="form-check-label">
                                                DM
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_8" value="1">
                                            <label class="form-check-label">
                                                Stroke
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="dp_9_9" value="1">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_6" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_7" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_8" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_10" value="0" checked="">
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
                                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_11" value="0" checked="">
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
    <div class="form-footer">
        {{-- <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button> --}}
        <button class="btn btn-success" onclick="saveDataPengkajianGdP(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        const $section = $('#gd_perawat');

        $section.on('input', 'input[type="number"][min][max]', function () {

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
        $section.on('change', '[name="dd_ck"]', function () {

            const value = $(this).val();

            // dd_ck tidak boleh di-uncheck
            if (!this.checked) {
                $(this).prop('checked', true);
                return;
            }

            // Hanya satu pilihan dd_ck
            $section
                .find('[name="dd_ck"]')
                .not(this)
                .prop('checked', false);

            const $pengantar = $section.find('[name="dd_ck_p"]');
            const $rujukan   = $section.find('[name="dd_ck_k"]');
            const $polisi    = $section.find('[name="dd_ck_a"]');
            const $visum     = $section.find('[name="dd_ck_a_v"]');

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
        $section.on('change', 'input[name="jks"]', function () {

            const $jks = $section.find('input[name="jks"]:checked');

            const $trauma = $section.find(
                '[name="jks_kll"], [name="jks_kk"], [name="jks_uppa"]'
            );

            const $nonTrauma = $section.find(
                '[name="jks_end"], [name="jks_end_dm"]'
            );

            // Tidak ada JKS yang dipilih
            if (!$jks.length) {

                resetJenisKasus($section);

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
                $section.find('[name="jks_end"]')
                    .prop('checked', false)
                    .prop('disabled', true);

                $section.find('[name="jks_end_dm"]')
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
                $section.find('[name="jks_end"], [name="jks_end_dm"]')
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
        $section.on('change', 'input[name="sn_metode"]', function () {

            const $this = $(this);
            const metode = $this.val();

            // ==========================================
            // JIKA METODE DIPILIH
            // ==========================================
            if ($this.is(':checked')) {

                // Pastikan hanya satu metode yang aktif
                $section
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
        $section.on('change input', '[data-hd-required]', function () {
            hitungSkorHumptyDumpty($section);
        });
        $section.on('change', '[data-sm-required]', function () {
            hitungSkorMorse($section);
        });
        $section.on('change', '[data-epfra-required]', function () {
            hitungSkorEPFRA($section);
        });
        $section.on('change input', '[name="sgd1"], [name="sgd1_c"], [name="sgd2"]', function () {
            hitungSkorMUST($section);
        });
        $section.on('change', 'input[name="sga1"], input[name="sga2"], input[name="sga3"], input[name="sga4"]', function () {
            hitungSkorStrongKid($section);
        });

        // CB - SKRINING RESIKO JATUH
        $section.on('change', '#srj_hd', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Humpty Dumpty
                $section.find('#tampil_srj_hd').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_hd').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetHumptyDumpty($section);
            }
        });
        $section.on('change', '#srj_sm', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Morse
                $section.find('#tampil_srj_sm').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_sm').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetMorse($section);
            }
        });
        $section.on('change', '#srj_epfra', function () {
            if ($(this).is(':checked')) {
                $section.find('#tampil_srj_epfra') .prop('hidden', false);
            } else {
                $section.find('#tampil_srj_epfra') .prop('hidden', true);
                resetEPFRA($section);
            }
        });

        // CB - SKRINING GIZI
        $section.on('change', '#sg_must', function () {
            if ($(this).is(':checked')) {
                // Tampilkan MUST
                $section.find('#tampil_sg_must').prop('hidden', false);
            } else {
                // Sembunyikan MUST
                $section.find('#tampil_sg_must').prop('hidden', true);
                // Reset data MUST
                resetSkriningMUST($section);
            }
        });
        $section.on('change', '#sg_sk', function () {
            if ($(this).is(':checked')) {
                // Tampilkan STRONG KID
                $section.find('#tampil_sg_sk').prop('hidden', false);
            } else {
                // Sembunyikan STRONG KID
                $section.find('#tampil_sg_sk').prop('hidden', true);
                // Reset data STRONG KID
                resetSkriningStrongKid($section);
            }
        });

        // CB - Status Kehamilan
        $section.on('change', '[name="sh"]', function () {
            const nilai = $section.find('[name="sh"]:checked').val();
            if (nilai === '1') {
                // Ya -> tampilkan detail kehamilan
                $section.find('#tampil_sh_ya').prop('hidden', false);
            } else {
                // Tidak -> sembunyikan detail
                $section.find('#tampil_sh_ya').prop('hidden', true);
                // Reset detail kehamilan
                resetStatusKehamilan($section);
            }
        });

        // CB - Discharge Planning
        $section.on('change', '[name^="dp_"]', function () {

            const name = $(this).attr('name');

            // Hanya proses dp_1, dp_2, dp_3, dst.
            // Tidak memproses dp_5_1, dp_9_1, dp_11_skrining, dll.
            if (!/^dp_\d+$/.test(name)) {
                return;
            }

            const targetId = '#tampil_' + name;

            if ($(this).is(':checked') && $(this).val() === '1') {

                // ADA
                $section.find(targetId).prop('hidden', false);

            } else if ($(this).is(':checked') && $(this).val() === '0') {

                // TIDAK ADA
                $section.find(targetId).prop('hidden', true);

                // Reset semua isi detail
                resetDischargePlanningDetail($section, name);
            }
        });

        // INIT FUNCTION ------------------------------------------------------------------------------
        resetDatangCara($section);
        resetJenisKasus($section);

        // getDataPengkajianGdP();
        hitungSkorHumptyDumpty($section);
        hitungSkorMorse($section);
        hitungSkorEPFRA($section);
        hitungSkorMUST($section);
        hitungSkorStrongKid($section);
    })

    // FUNCTION-FUNCTION AREA  /////////////////////////////////////////////////////

    // RESET CARA KEDATANGAN
    function resetDatangCara($section) {

        // Reset pilihan utama
        $section.find('[name="dd_ck"]')
            .prop('checked', false);

        // Reset dan disable input
        $section.find('[name="dd_ck_p"]')
            .val('')
            .prop('disabled', true);

        $section.find('[name="dd_ck_k"]')
            .val('')
            .prop('disabled', true);

        $section.find('[name="dd_ck_a"]')
            .val('')
            .prop('disabled', true);

        $section.find('[name="dd_ck_a_v"]')
            .prop('checked', false)
            .prop('disabled', true);
    }

    // RESET JENIS KASUS
    function resetJenisKasus($section) {

        // Reset pilihan Trauma
        $section.find(
            '[name="jks_kll"], [name="jks_kk"], [name="jks_uppa"]'
        )
            .prop('checked', false)
            .prop('disabled', true);

        // Reset Non Trauma
        $section.find('[name="jks_end"]')
            .prop('checked', false)
            .prop('disabled', true);

        // Reset keterangan
        $section.find('[name="jks_end_dm"]')
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
    function hitungSkorHumptyDumpty($section) {
        const $requiredFields = $section.find('[data-hd-required]');
        const $scoreFields = $section.find('[data-hd-score]');

        // Selector selalu dibatasi di dalam #gd_perawat
        const $scoreBox = $section.find('#skor_rj_hd');
        const $nilai = $section.find('#nilai_rj_hd');
        const $kategori = $section.find('#kategori_rj_hd');
        const $keterangan = $section.find('#keterangan_rj_hd');

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
        $section.find('input[name="skor_rj_hd"]').val(skor);

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

    function resetHumptyDumpty($section) {

        // Reset semua pilihan
        $section.find('[data-hd-required]').val('');

        // Reset nilai skor
        $section.find('[name="skor_rj_hd"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_rj_hd').text(0);

        // Sembunyikan hasil skor
        $section.find('#skor_rj_hd').prop('hidden', true);

        // Kembalikan tampilan kategori
        $section.find('#kategori_rj_hd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $section.find('#keterangan_rj_hd').text('');

        // Kembalikan alert ke default
        $section.find('#skor_rj_hd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorMorse($section) {
        const $fields = $section.find('[data-sm-required]');
        const $scoreBox = $section.find('#skor_rj_sm');

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

        $section.find('input[name="skor_rj_sm"]').val(skor);

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

        $section.find('#nilai_rj_sm').text(skor);

        $section.find('#kategori_rj_sm')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_rj_sm').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetMorse($section) {

        // Reset semua pilihan
        $section.find('[data-sm-required]').val('');

        // Reset nilai skor
        $section.find('[name="skor_rj_sm"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_rj_sm').text(0);

        // Sembunyikan hasil skor
        $section.find('#skor_rj_sm').prop('hidden', true);

        // Kembalikan tampilan kategori
        $section.find('#kategori_rj_sm')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $section.find('#keterangan_rj_sm').text('');

        // Kembalikan alert ke default
        $section.find('#skor_rj_sm .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorEPFRA($section) {

        const $fields = $section.find('[data-epfra-required]');
        const $scoreBox = $section.find('#skor_rj_epfra');

        // Semua field harus sudah dipilih
        const semuaTerisi = $fields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '' && $(field).val() !== '0';
        });

        // Jika belum lengkap, sembunyikan hasil
        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);

            // Reset skor
            $section.find('input[name="skor_rj_epfra"]').val(0);
            $section.find('#nilai_rj_epfra').text(0);

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
        $section
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

        $section
            .find('#nilai_rj_epfra')
            .text(skor);

        $section
            .find('#kategori_rj_epfra')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section
            .find('#keterangan_rj_epfra')
            .text(keterangan);

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetEPFRA($section) {

        // Reset semua pilihan EPFRA
        $section.find('[data-epfra-required]').each(function () {
            $(this).val('');
        });

        // Reset skor hidden
        $section.find('[name="skor_rj_epfra"]').val(0);

        // Reset nilai skor
        $section.find('#nilai_rj_epfra').text(0);

        // Reset kategori
        $section.find('#kategori_rj_epfra')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_rj_epfra').text('');

        // Sembunyikan kotak hasil
        $section.find('#skor_rj_epfra').prop('hidden', true);

        // Kembalikan warna alert ke default
        $section.find('#skor_rj_epfra .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorMUST($section) {
        const sgd1 = $section.find('input[name="sgd1"]:checked').val();
        const sgd1c = $section.find('input[name="sgd1_c"]:checked').val();
        const sgd2 = $section.find('input[name="sgd2"]:checked').val();
        const sgd3 = $.trim($section.find('[name="sgd3"]').val());

        const $scoreBox = $section.find('#skor_sgd');

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
        $section.find('input[name="skor_sgd"]').val(skor);

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

        $section.find('#nilai_sgd').text(skor);

        $section.find('#kategori_sgd')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_sgd').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningMUST($section) {

        // Reset checkbox
        $section.find('input[name="sgd1"]').prop('checked', false);
        $section.find('input[name="sgd1_c"]').prop('checked', false);
        $section.find('input[name="sgd2"]').prop('checked', false);

        // Reset input
        $section.find('[name="sgd3"]').val('');

        // Reset skor
        $section.find('[name="skor_sgd"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_sgd').text(0);

        // Reset kategori
        $section.find('#kategori_sgd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_sgd').text('');

        // Sembunyikan hasil skor
        $section.find('#skor_sgd').prop('hidden', true);

        // Reset alert
        $section.find('#skor_sgd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function hitungSkorStrongKid($section) {
        const fieldNames = ['sga1', 'sga2', 'sga3', 'sga4'];
        const $scoreBox = $section.find('#skor_sga');

        const nilai = fieldNames.map(function (name) {
            return $section.find('input[name="' + name + '"]:checked').val();
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

        $section.find('input[name="skor_sga"]').val(skor);

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

        $section.find('#nilai_sga').text(skor);

        $section.find('#kategori_sga')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_sga').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningStrongKid($section) {
        // Reset checkbox
        $section.find('input[name="sga1"]').prop('checked', false);
        $section.find('input[name="sga2"]').prop('checked', false);
        $section.find('input[name="sga3"]').prop('checked', false);
        $section.find('input[name="sga4"]').prop('checked', false);

        // Reset skor
        $section.find('[name="skor_sga"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_sga').text(0);

        // Reset kategori
        $section.find('#kategori_sga')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_sga').text('');

        // Sembunyikan hasil skor
        $section.find('#skor_sga').prop('hidden', true);

        // Reset alert
        $section.find('#skor_sga .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function resetStatusKehamilan($section) {
        // Reset input
        $section.find('input[name="sh_g"]').val('');
        $section.find('input[name="sh_p"]').val('');
        $section.find('input[name="sh_a"]').val('');
        $section.find('input[name="sh_h"]').val('');
    }

    function resetDischargePlanningDetail($section, name) {
        const $target = $section.find('#tampil_' + name);

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
        $section.find(`[name="${name}"][value="0"]`)
            .prop('checked', true);

        $section.find(`[name="${name}"][value="1"]`)
            .prop('checked', false);
    }

    function getDataPengkajianGdP() {
        $.ajax({
            url: `/api/v2/emr/form/pengkajian/gd/pr/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {

            },
            success: function (res) {

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

            }
        });
    }

    function saveDataPengkajianGdP(btn) {
        const $button = $(btn);
        const $section = $('#gd_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/gd/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                // $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (response) {
                alert(response.message || 'Data berhasil disimpan.');
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
                // $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    }
</script>
