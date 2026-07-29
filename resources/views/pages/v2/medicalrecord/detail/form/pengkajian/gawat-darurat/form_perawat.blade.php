<div class="form-wrapper">
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <h6 class="mb-0 flex-shrink-0">Cara Datang</h6>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="cd" value="1">
                        <label class="form-check-label">
                            Non Rujukan
                        </label>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="cd" value="2">
                            <label class="form-check-label">
                                Rujukan
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" style="width:250px;" name="cd_dari">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6>Transport ke IGD</h6>
                    <div class="row">
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="1">
                                <label class="form-check-label" for="checkPrimary"> Ambulance </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="2">
                                <label class="form-check-label" for="checkPrimary"> Kendaraan Umum </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="3">
                                <label class="form-check-label" for="checkPrimary"> Kendaraan Pribadi </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="4">
                                <label class="form-check-label" for="checkPrimary"> Jalan </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="5">
                                <label class="form-check-label" for="checkPrimary"> Brankad </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tki" value="6">
                                <label class="form-check-label" for="checkPrimary"> Kursi Roda </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6>Cara Masuk</h6>
                    <div class="row">
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="cm" value="1">
                                <label class="form-check-label" for="checkPrimary"> Kecelakaan Lalu Lintas </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="cm" value="2">
                                <label class="form-check-label" for="checkPrimary"> Kecelakaan Kerja </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="cm" value="3">
                                <label class="form-check-label" for="checkPrimary"> Kecelakaan Rumah Tangga </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="cm" value="4">
                                    <label class="form-check-label ms-1" for="cm4">
                                        Kecelakaan Lain-lain
                                    </label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="cm_lain_4" placeholder="">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="cm" value="5">
                                    <label class="form-check-label ms-1" for="cm4">
                                        Bencana
                                    </label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="cm_lain_5" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <h6 class="mb-0 flex-shrink-0">Tindakan Pra Hospital</h6>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="tph" value="1">
                        <label class="form-check-label">
                            RJP
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="tph" value="2">
                        <label class="form-check-label">
                            Oksigen
                        </label>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="tph" value="3">
                            <label class="form-check-label">
                                Tindakan Medis
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="tph_lain">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6>Riwayat Alergi</h6>
                    <textarea class="form-control" name="ra_p" rows="2"></textarea>
                </div>
                <div class="form-group mb-3">
                    <h6>Hambatan Pasien</h6>
                    <div class="row">
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="1">
                                <label class="form-check-label" for="checkPrimary"> Tidak Ada </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="2">
                                <label class="form-check-label" for="checkPrimary"> Ada </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="3">
                                <label class="form-check-label" for="checkPrimary"> Bahasa </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="4">
                                <label class="form-check-label" for="checkPrimary"> Fisik </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="5">
                                <label class="form-check-label" for="checkPrimary"> Tuli </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="6">
                                <label class="form-check-label" for="checkPrimary"> Bisu </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="7">
                                <label class="form-check-label" for="checkPrimary"> Buta </label>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hpx" value="8">
                                    <label class="form-check-label ms-1" for="cm4">
                                        Lain-lain
                                    </label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="hpx_lain" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <h6>Keluhan Utama</h6>
                    <textarea class="form-control" name="ku_p" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-warning mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h6 class="mb-0">Airway</h6>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="ttv_airway" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="ttv_airway" value="2">
                                        <label class="form-check-label">
                                            Masalah
                                        </label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="ttv_airway_lain">
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Breathing</h6>
                                <div class="form-group">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <span class="input-group-text">RR</span>
                                        <input type="text" class="form-control" name="ttv_br">
                                        <span class="input-group-text">X/menit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Pola Pernapasan</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="ttv_pnafas" value="1">
                                        <label class="form-check-label">
                                            Normal
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                        <div class="form-check mb-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="ttv_pnafas" value="2">
                                            <label class="form-check-label">
                                                Tidak, jelaskan
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ttv_pnafas_lain">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Circulation</h6>
                                <div class="form-group">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <span class="input-group-text">TD</span>
                                        <input type="text" class="form-control" name="ttv_cr">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Nadi</h6>
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="input-group input-group-sm flex-grow-1">
                                            <input type="text" class="form-control" name="ttv_nadi">
                                            <span class="input-group-text">X/menit</span>
                                        </div>
                                        <div class="form-check flex-shrink-0 m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="ttv_nadi_cb" value="1">
                                            <label class="form-check-label text-nowrap">
                                                Teratur
                                            </label>
                                        </div>
                                        <div class="form-check flex-shrink-0 m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="ttv_nadi_cb" value="2">
                                            <label class="form-check-label text-nowrap">
                                                Tidak Teratur
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h6 class="mb-0">Suhu</h6>
                                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <input type="text" class="form-control" name="ttv_sh">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <h6 class="mb-0">Akral</h6>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="ttv_akral" value="1">
                                        <label class="form-check-label"> Hangat </label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="ttv_akral" value="2">
                                        <label class="form-check-label"> Dingin </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h6>Perdarahan/Kehilangan Cairan</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="ttv_phc" value="1">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                                        <div class="form-check mb-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="ttv_phc" value="2">
                                            <label class="form-check-label">
                                                Ada, jelaskan
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ttv_phc_lain">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Capilary</h6>
                                <div class="form-group">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <input type="text" class="form-control" name="ttv_cp">
                                        <span class="input-group-text">detik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h6>SpO2</h6>
                                <div class="form-group">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <input type="text" class="form-control" name="ttv_spo2">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-info mb-3">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="form-group">
                                <h5 class="border-bottom pb-2 text-bold">
                                    <strong>PSIKOLOGI - SOSIAL - EKONOMI - SPIRITUAL - FUNGSIONAL </strong>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row">
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="1">
                                        <label class="form-check-label" for="checkPrimary"> Takut terhadap tindakan lingkungan </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="2">
                                        <label class="form-check-label" for="checkPrimary"> Tidak mampu menahan diri </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="3">
                                        <label class="form-check-label" for="checkPrimary"> Cemas </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="4">
                                        <label class="form-check-label" for="checkPrimary"> Gelisah </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="5">
                                        <label class="form-check-label" for="checkPrimary"> Marah/tegang </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="6">
                                        <label class="form-check-label" for="checkPrimary"> Rendah diri </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="7">
                                        <label class="form-check-label" for="checkPrimary"> Sedih </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="8">
                                        <label class="form-check-label" for="checkPrimary"> Tenang </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="9">
                                        <label class="form-check-label" for="checkPrimary"> Menangis </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="10">
                                        <label class="form-check-label" for="checkPrimary"> Mudah tersinggung </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pse" value="11">
                                        <label class="form-check-label" for="checkPrimary"> Senang </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-2 col-form-label">Pasien tinggal di</label>
                                <div class="col-md-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tinggal" value="1">
                                        <label class="form-check-label">
                                            Rumah sendiri
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tinggal" value="2">
                                        <label class="form-check-label">
                                            Rumah orang tua
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tinggal" value="3">
                                        <label class="form-check-label">
                                            Kost / kontrak
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tinggal" value="4">
                                        <label class="form-check-label">
                                            Lainnya
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-2 col-form-label">Kebiasaan bila sakit</label>
                                <div class="col-md-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kbs" value="1">
                                        <label class="form-check-label">
                                            Pengobatan alternatif
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kbs" value="2">
                                        <label class="form-check-label">
                                            Pelayanan kesehatan
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kbs" value="3">
                                        <label class="form-check-label">
                                            Beli obat di warung
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-2 col-form-label">Nilai Kepercayaan</label>
                                <div class="col-md-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="percaya" value="1">
                                        <label class="form-check-label">
                                            Vegetarian
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="percaya" value="2">
                                        <label class="form-check-label">
                                            Makanan / minuman
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-4 col-form-label">Hubungan Pasien dan anggota keluarga</label>
                                <div class="col-md-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hub" value="1">
                                        <label class="form-check-label">
                                            Baik
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hub" value="2">
                                        <label class="form-check-label">
                                            Tidak baik
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-3 col-form-label">Penggunaan alat bantu diri</label>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantu" value="1">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantu" value="2">
                                        <label class="form-check-label">
                                            Alat bantu dengar
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantu" value="3">
                                        <label class="form-check-label">
                                            Kacamata/kontak lensa
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantu" value="4">
                                        <label class="form-check-label">
                                            Gigi palsu
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="row align-items-center">
                                <label class="col-md-3 col-form-label">Bantuan yang dibutuhkan pasien di rumah</label>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Mandi</label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Makan</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">BAB/BAK</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Perawatan Luka</label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Berjalan/ambulansi</label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Pemberian Obat</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="bantuan">
                                                <label class="form-check-label">Keluarga/orang yang membantu di rumah</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6>Kriteria Perencanaan Pulang</h6>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label class="form-label flex-shrink-0">Umur lebih dari 65 tahun</label>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_1" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_1" value="2">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label class="form-label flex-shrink-0">Keterbatasan mobilitas</label>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_2" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_2" value="2">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label class="form-label flex-shrink-0">Perawatan atau pengobatan</label>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_3" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="dp_3" value="2">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {

    })
</script>
