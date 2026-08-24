<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN MEDIS <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Anamnesis</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1">
                            <label class="form-check-label">
                                Autoanamnesis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2" checked>
                            <label class="form-check-label">
                                Alloanamnesis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" name="anamnesis_oleh" id="anamnesis_oleh" placeholder="Oleh.....">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Keluhan Utama</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Sekarang</h6>
                            <textarea class="form-control" name="rps" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Dahulu</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Keluarga</h6>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_h">
                                <label class="form-check-label"> Hipertensi </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_d">
                                <label class="form-check-label"> Diabetes Melitus </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_p">
                                <label class="form-check-label"> Penyakit Jantung </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_a">
                                <label class="form-check-label"> Asma </label>
                            </div>
                            <textarea class="form-control" name="rpk_lain" rows="1" placeholder="Lainnya..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rio_dokter',
                        'page' => 'dokter',
                        // 'editableFields' => [
                        //     'tv_keu',
                        //     'tv_gcs_e',
                        //     'tv_gcs_v',
                        //     'tv_gcs_m',
                        //     'tv_bb',
                        //     'tv_tb',
                        // ],
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                <h4 class="mb-3 text-danger">Pemeriksaan</h4>
                <div class="mb-3">
                    <h6>Pemeriksaan Fisik</h6>
                    <textarea class="form-control" name="pfisik" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <div class="mb-3">
                    <h6>Pemeriksaan Obstetri</h6>
                    <textarea class="form-control" name="pobs" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <div class="mb-3">
                    <h6>Pemeriksaan Gynekologi</h6>
                    <textarea class="form-control" name="pgyn" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                </div>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <h4 class="text-warning">Pemeriksaan USG</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Hasil Pemeriksaan</h6>
                        <textarea class="form-control" name="usg_hasil" rows="3"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Kesimpulan</h6>
                        <textarea class="form-control" name="usg_kesimpulan" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <div class="mb-3">
                    <h6>Pemeriksaan Penunjang Lainnya</h6>
                    <textarea class="form-control" name="plain" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <h4 class="text-danger">Perencanaan / Program Kerja</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="mb-2">
                            <h6>Rencana Kerja dan Terapi</h6>
                            <textarea class="form-control" name="renc_terapi" rows="3"></textarea>
                        </div>
                        <div class="mb-2">
                            <h6>Target Terapi</h6>
                            <textarea class="form-control" name="target_terapi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="mb-2">
                            <h6>Rencana Konsultasi</h6>
                            <textarea class="form-control" name="renc_konsul" rows="3"></textarea>
                        </div>
                        <div class="mb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Kriteria Pulang</h6>
                                </div>
                                <div class="col-md-12">
                                    <!-- Sudah bisa ditetapkan -->
                                    <div class="d-flex align-items-center flex-wrap mb-2">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="perkiraan_lama_rawat" value="sudah_bisa" id="lama_rawat_sudah">
                                            <label class="form-check-label" for="lama_rawat_sudah">
                                                Sudah bisa ditetapkan :
                                            </label>
                                        </div>
                                        <input type="number" class="form-control form-control-sm mx-1" name="lama_rawat_hari" style="width: 80px;">
                                        <span>hari</span>
                                    </div>
                                    <!-- Belum bisa ditetapkan -->
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="perkiraan_lama_rawat" value="belum_bisa" id="lama_rawat_belum">
                                            <label class="form-check-label" for="lama_rawat_belum">
                                                Belum bisa ditetapkan, karena :
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm ms-2" name="alasan_lama_rawat" style="max-width: 400px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <h4 class="text-danger">Terapi / Tindakan</h4>
                <div class="mb-2">
                    <textarea class="form-control" name="terapi" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
