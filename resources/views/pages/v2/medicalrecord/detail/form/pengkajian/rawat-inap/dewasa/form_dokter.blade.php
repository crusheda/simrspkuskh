<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN MEDIS <b class="text-danger">RAWAT INAP</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Anamnesis</h4>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Anamnesis Diperoleh</h6>
                        <div class="form-group mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1">
                                <label class="form-check-label"> Autoanamnesis </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2">
                                <label class="form-check-label"> Alloanamnesis </label>
                            </div>
                        </div>
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
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-6 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
                    </div>
                    <div class="col-md-6 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#riD_dokter',
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
            <div class="col-md-12 mb-3">
                <h4 class="mb-3 text-danger">Pemeriksaan Fisik</h4>
                <div class="mb-3">
                    @include(
                        'pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_anatomi',
                        [
                            'section' => '#riD_dokter',
                            'metodePemeriksaan' => [
                                'mata',
                                'tenggorokan',
                                'leher',
                                'dada',
                                'perut'
                            ]
                        ]
                    )
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
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h4 class="form-label fw-bold text-danger mb-3">Rencana Kerja dan Terapi</h4>
                    <textarea class="form-control" name="pt" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer d-flex justify-content-between">
        <button type="button" class="btn btn-subtle-info btnLihatCPPT" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat CPPT" onclick="showCppt('{{ $list['kunjungan'] }}')">
            <i class="ri-booklet-line me-1"></i> Lihat CPPT
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRiDd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    var $sectionRiDd = $('#riD_dokter');
    $(document).ready(function() {

    })
</script>
