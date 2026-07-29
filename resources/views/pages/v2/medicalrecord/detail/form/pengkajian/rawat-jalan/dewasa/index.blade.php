<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokter"
        data-bs-target="#gd_dokter" aria-expanded="false" aria-controls="gd_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawat"
        data-bs-target="#gd_perawat" aria-expanded="false" aria-controls="gd_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="gdAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#gdAccordion" id="gd_dokter">
        <div class="form-wrapper">
            <div class="form-content">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <div class="form-group mb-2">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <strong>S : </strong>
                                </h5>
                            </div>
                            <div class="row align-items-center mb-3" id="anamnesis_diperoleh">
                                <div class="col-md-12">
                                    <label class="form-label">Anamnesis</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="an_auto">
                                        <label class="form-check-label" for="an_auto">
                                            Autoanamnesis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="an_allo">
                                        <label class="form-check-label" for="an_allo">
                                            Alloanamnesis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="anamnesis_oleh" placeholder="Oleh.....">
                                </div>
                            </div>
                            <div class="row align-items-center" id="anamnesis">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keluhan Utama</label>
                                    <textarea class="form-control" id="keluhan_utama" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Riwayat Penyakit Sekarang</label>
                                    <textarea class="form-control" id="rps" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Riwayat Penyakit Dahulu</label>
                                    <textarea class="form-control" id="rpd" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Riwayat Alergi</label>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ra" id="ra_tidak" value="0">
                                                <label class="form-check-label" for="ra_tidak">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ra" id="ra_ya" value="1">
                                                <label class="form-check-label" for="ra_ya">
                                                    Ya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control" id="ra_des" rows="3" placeholder="Sebutkan...."></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Riwayat Penggunaan Obat</label>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rpo" id="rpo_tidak" value="0">
                                                <label class="form-check-label" for="rpo_tidak">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rpo" id="rpo_ya" value="1">
                                                <label class="form-check-label" for="rpo_ya">
                                                    Ya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control" id="rpo_des" rows="3" placeholder="Sebutkan...."></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <strong>O : </strong>
                                </h5>
                            </div>
                            <div class="row align-items-center" id="pemeriksaan_fisik">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Pemeriksaan Fisik</label>
                                    <textarea class="form-control" id="pfisik" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row align-items-center" id="pemeriksaan_penunjang">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Pemeriksaan Penunjang</label>
                                    <textarea class="form-control" id="penunjang" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <strong>A : </strong>
                                </h5>
                            </div>
                            <div class="row align-items-center" id="diagnosis">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Diagnosis</label>
                                    <textarea class="form-control" id="diagnosis" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row align-items-center" id="tolok_ukur">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tolok Ukur / Sasaran yang Dicapai</label>
                                    <textarea class="form-control" id="tu" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <strong>P : </strong>
                                </h5>
                            </div>
                            <div class="row align-items-center" id="terapi_tindakan">
                                <div class="col-md-12">
                                    <label class="form-label">Terapi / Tindakan</label>
                                    <textarea class="form-control" id="terapi" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="card card-body border border-dashed border-warning mb-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Materi Edukasi</label>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="me_1">
                                                    <label class="form-check-label" for="checkPrimary"> Tanda dan gejala suatu penyakit </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="me_2">
                                                    <label class="form-check-label" for="checkPrimary"> Hasil pemeriksaan </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="me_3">
                                                    <label class="form-check-label" for="checkPrimary"> Diagnosis </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="me_4">
                                                    <label class="form-check-label" for="checkPrimary"> Rencana penatalaksanaan penyakit </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="me_5">
                                                    <label class="form-check-label" for="checkPrimary"> Tindakan dan tujuan terapi </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Sarana Informasi / Edukasi</label>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="sie_1">
                                                    <label class="form-check-label" for="checkPrimary"> Leaflet </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="sie_2">
                                                    <label class="form-check-label" for="checkPrimary"> Lisan </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Evaluasi</label>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="eval_1">
                                                    <label class="form-check-label" for="checkPrimary"> Sudah Mengerti </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" id="eval_2">
                                                    <label class="form-check-label" for="checkPrimary"> Re - Edukasi </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="card card-body border border-dashed border-success mb-1">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tindak Lanjut :</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="0">
                                        <label class="form-check-label" for="tl_mrs">
                                            MRS
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="1">
                                        <label class="form-check-label" for="tl_pulang">
                                            Pulang
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body card-header border border-dashed border-primary" id="pri">
                                <div class="card-header fw-bold">
                                    Perencanaan Rawat Inap
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Ruang Perawatan</label>
                                                <select class="form-control" id="pri_ruang">
                                                    <option value="">Pilih Jenis Ruang Perawatan</option>
                                                    <option value="1">Perawatan Biasa</option>
                                                    <option value="2">Perawatan Intensive</option>
                                                    <option value="3">Perawatan Isolasi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Perawatan</label>
                                                <select class="form-control" id="pri_perawatan">
                                                    <option value="">Pilih Jenis Perawatan</option>
                                                    <option value="1">Preventif (Mencegah)</option>
                                                    <option value="2">Kuratif (Menolong)</option>
                                                    <option value="3">Rehabilitatif (Rehabilitasi)</option>
                                                    <option value="4">Paliatif (Meredakan)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="flatpickr_basic">Tanggal</label>
                                            <div class="input-group">
                                                <input type="text" id="pri_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                                                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Indikasi</label>
                                            <textarea class="form-control" id="pri_indikasi" rows="3"></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea class="form-control" id="pri_ket" rows="3"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">DPJP</label>
                                            <input type="text" class="form-control" id="pri_dpjp" placeholder="Dokter Otomatis">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Dirujuk Ke</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rujuk" id="rujuk_gizi" value="0">
                                                <label class="form-check-label" for="rujuk_gizi">
                                                    Ahli Gizi
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rujuk" id="rujuk_rehab" value="1">
                                                <label class="form-check-label" for="rujuk_rehab">
                                                    Rehabilitasi Medik
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rujuk" id="rujuk_sp" value="1">
                                                <label class="form-check-label" for="rujuk_sp">
                                                    Klinik Spesialis
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rujuk" id="rujuk_lain" value="1">
                                                <label class="form-check-label" for="rujuk_lain">
                                                    Lainnya
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="rujuk_mana" placeholder="Sebutkan....">
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
                <button class="btn btn-danger">
                    <i class="ri-save-line me-1"></i> Simpan Pengkajian
                </button>
            </div>
        </div>
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#gdAccordion" id="gd_perawat">
        <div class="form-wrapper">
            <div class="form-content">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">Keluhan</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="keluhan">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" name="td_up">
                                <div class="input-group-text"> / </div>
                                <input type="text" class="form-control" name="td_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nadi</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" name="nadi">
                                <div class="input-group-text">X/menit, Reguler / Ireguler</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label">Nafas</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="input-group input-group-sm flex-grow-1">
                                    <input type="text" class="form-control" name="nafas">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Suhu</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" name="suhu">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label">SpO2</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" name="spo2">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berat Badan</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" name="bb">
                                <div class="input-group-text">Kg</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="card card-body border border-dashed border-primary mb-1">
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
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_1">
                                            <label class="form-check-label" for="checkPrimary"> Takut terhadap tindakan lingkungan </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_2">
                                            <label class="form-check-label" for="checkPrimary"> Tidak mampu menahan diri </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_3">
                                            <label class="form-check-label" for="checkPrimary"> Cemas </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_4">
                                            <label class="form-check-label" for="checkPrimary"> Gelisah </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_5">
                                            <label class="form-check-label" for="checkPrimary"> Marah/tegang </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_6">
                                            <label class="form-check-label" for="checkPrimary"> Rendah diri </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_7">
                                            <label class="form-check-label" for="checkPrimary"> Sedih </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_8">
                                            <label class="form-check-label" for="checkPrimary"> Tenang </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_9">
                                            <label class="form-check-label" for="checkPrimary"> Menangis </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_10">
                                            <label class="form-check-label" for="checkPrimary"> Mudah tersinggung </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="pse_11">
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
                                            <input class="form-check-input" type="radio" name="tinggal" id="tinggal_sendiri" value="0">
                                            <label class="form-check-label" for="tinggal_sendiri">
                                                Rumah sendiri
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tinggal" id="tinggal_ortu" value="1">
                                            <label class="form-check-label" for="tinggal_ortu">
                                                Rumah orang tua
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tinggal" id="tinggal_kontrak" value="2">
                                            <label class="form-check-label" for="tinggal_kontrak">
                                                Kost / kontrak
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tinggal" id="tinggal_lain" value="3">
                                            <label class="form-check-label" for="tinggal_lain">
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
                                            <input class="form-check-input" type="radio" name="kbs" id="kbs_alt" value="0">
                                            <label class="form-check-label" for="kbs_alt">
                                                Pengobatan alternatif
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kbs" id="kbs_faskes" value="1">
                                            <label class="form-check-label" for="kbs_faskes">
                                                Pelayanan kesehatan
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kbs" id="kbs_beli" value="2">
                                            <label class="form-check-label" for="kbs_beli">
                                                Beli obat di warung
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="row mb-3 align-items-center">
                                    <label for="agama" class="col-md-2 col-form-label">Agama</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="agama" name="agama" placeholder="Agama otomatis">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="row align-items-center">
                                    <label class="col-md-2 col-form-label">Nilai Kepercayaan</label>
                                    <div class="col-md-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="percaya" id="percaya_vegan" value="0">
                                            <label class="form-check-label" for="percaya_vegan">
                                                Vegetarian
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="percaya" id="percaya_makan" value="1">
                                            <label class="form-check-label" for="percaya_makan">
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
                                            <input class="form-check-input" type="radio" name="hub" id="hub_baik" value="0">
                                            <label class="form-check-label" for="hub_baik">
                                                Baik
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hub" id="hub_tidak" value="1">
                                            <label class="form-check-label" for="hub_tidak">
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
                                            <input class="form-check-input" type="radio" name="bantu" id="bantu_tidak" value="0">
                                            <label class="form-check-label" for="bantu_tidak">
                                                Tidak
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bantu" id="bantu_dengar" value="1">
                                            <label class="form-check-label" for="bantu_dengar">
                                                Alat bantu dengar
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bantu" id="bantu_km" value="2">
                                            <label class="form-check-label" for="bantu_km">
                                                Kacamata/kontak lensa
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bantu" id="bantu_gigi" value="3">
                                            <label class="form-check-label" for="bantu_gigi">
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
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_mandi">
                                                    <label class="form-check-label" for="bantuan_mandi">Mandi</label>
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_makan">
                                                    <label class="form-check-label" for="bantuan_makan">Makan</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_bab">
                                                    <label class="form-check-label" for="bantuan_bab">BAB/BAK</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_luka">
                                                    <label class="form-check-label" for="bantuan_luka">Perawatan Luka</label>
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_jalan">
                                                    <label class="form-check-label" for="bantuan_jalan">Berjalan/ambulansi</label>
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_obat">
                                                    <label class="form-check-label" for="bantuan_obat">Pemberian Obat</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input check-primary" type="checkbox" name="bantuan[]" id="bantuan_kel">
                                                    <label class="form-check-label" for="bantuan_kel">Keluarga/orang yang membantu di rumah</label>
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
                <button class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Simpan Pengkajian
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        // Jalankan saat pertama kali halaman dibuka
        updateButton();

        // Saat collapse dibuka
        $('.multi-collapse').on('shown.bs.collapse', function () {
            updateButton();
        });

        // Saat collapse ditutup
        $('.multi-collapse').on('hidden.bs.collapse', function () {
            updateButton();
        });

        // Sembunyikan textarea saat pertama kali
        $('#ra_des').hide();
        $('#rpo_des').hide();
        $('#pri').hide();
        $('#rujuk_mana').hide();

        // Riwayat Alergi
        $('input[name="ra"]').change(function () {
            if ($('#ra_ya').is(':checked')) {
                $('#ra_des').slideDown();
            } else {
                $('#ra_des').slideUp().val('');
            }
        });

        // Riwayat Penggunaan Obat
        $('input[name="rpo"]').change(function () {
            if ($('#rpo_ya').is(':checked')) {
                $('#rpo_des').slideDown();
            } else {
                $('#rpo_des').slideUp().val('');
            }
        });

        // Perencanaan Rawat Inap
        $('input[name="tl"]').change(function () {
            if ($('#tl_mrs').is(':checked')) {
                $('#pri').slideDown();
            } else {
                $('#pri').slideUp();
            }
        });

        // Dirujuk Ke
        $('input[name="rujuk"]').change(function () {
            if ($('#rujuk_lain').is(':checked')) {
                $('#rujuk_mana').slideDown();
            } else {
                $('#rujuk_mana').slideUp().val('');
            }
        });

        // FLATPICKR DATE
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        $("#pri_tgl").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                mode: 'single',
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: [today]
            }
        );

    });

    // update btn collapse
    function updateButton() {
        $('#btnDokter').prop('disabled', $('#gd_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled', $('#gd_perawat').hasClass('show'));
    };
</script>
