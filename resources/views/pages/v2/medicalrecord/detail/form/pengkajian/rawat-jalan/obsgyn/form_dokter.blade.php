<div class="form-wrapper" id="form_rajal_obsgyn_dokter">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL MEDIS <b class="">RAWAT JALAN</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                                [
                                    'section' => '#rjo_dokter',
                                    'anak' => 'false',
                                ]
                            )
                        </div>
                        <div class="col-md-12">
                            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="col-md-12 mb-2">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                            [
                                'section' => '#rjo_dokter',
                                'page' => 'dokter',
                                'editableFields' => [
                                    'tv_keu',
                                    'tv_gcs_e',
                                    'tv_gcs_v',
                                    'tv_gcs_m',
                                    'tv_td_up',
                                    'tv_td_down',
                                    'tv_nadi',
                                    'tv_nadi_cb',
                                    'tv_nafas',
                                    'tv_nafas_cb',
                                    'tv_suhu',
                                    'tv_spo2',
                                    'tv_bb',
                                    'tv_tb',
                                ],
                            ]
                        )
                    </div>
                    <div class="col-md-12 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.pemeriksaan_fisik_obsgyn_rj',['section' => '#rjo_dokter'])
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
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                            <div class="mb-3">
                                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="col-md-12 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.tolok_ukur_terapi',['section' => '#rjo_dokter'])
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#rjo_dokter'])
            </div>
        </div>
    </div>
    @include('pages.v2.medicalrecord.detail.form.finalisasi', [
        'jenis' => 'rajal_obsgyn',
        'role' => 'dokter',
        'formKey' => 'rjo_dokter',
        'form' => 'pengkajian-rajal-obsgyn',
        'sub' => 'DOKTER',
        'kunjungan' => $list['kunjungan'],
    ])
</div>
