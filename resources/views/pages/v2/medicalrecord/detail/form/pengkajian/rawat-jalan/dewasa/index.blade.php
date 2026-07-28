<div class="card">
    <div class="card-header">
        <label class="form-label"><b>PENGKAJIAN MEDIS (OLEH DOKTER)</b></label>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-4">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong>S : Anamnesis</strong>
                        </h5>
                    </div>
                    <div class="row align-items-center mb-3" id="anamnesis_diperoleh">
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
                                        <input class="form-check-input" type="checkbox" id="ra_tidak">
                                        <label class="form-check-label" for="ra_tidak">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ra_ya">
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
                                        <input class="form-check-input" type="checkbox" id="rpo_tidak">
                                        <label class="form-check-label" for="rpo_tidak">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rpo_ya">
                                        <label class="form-check-label" for="rpo_ya">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control" id="rpo_des" rows="3" placeholder="Sebutkan...."></textarea>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong>O : Pemeriksaan Fisik</strong>
                        </h5>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_fisik">
                        <div class="col-md-12 mb-3">
                            <textarea class="form-control" id="pfisik" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_penunjang">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Pemeriksaan Penunjang</label>
                            <textarea class="form-control" id="penunjang" rows="3"></textarea>
                        </div>
                    </div>

                    <h4 class="form-label"><b>A</b> : </h4>
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

                    <h4 class="form-label"><b>P</b> : </h4>
                    <div class="row align-items-center" id="terapi_tindakan">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Terapi / Tindakan</label>
                            <textarea class="form-control" id="terapi" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label">Kriteria ATS (Australasian Triage Scale)</label>
                    <select class="form-control" id="kriteria_ats">
                        <option value="">Pilih Kriteria</option>
                        <option value="1">Semua Kunjungan</option>
                        <option value="2">Batal Kunjungan</option>
                        <option value="3">Sedang Dilayani</option>
                        <option value="4">Selesai Kunjungan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-3" id="ddats1">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" id="checkPrimary" checked="">
                    <label class="form-check-label" for="checkPrimary"> Checkbox Primary </label>
                </div>
            </div>
            <div class="col-md-6 mb-3" id="ddats2">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="radio" id="radioPrimary" checked="">
                    <label class="form-check-label" for="radioPrimary"> Checkbox Primary </label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">

    </div>
</div>

