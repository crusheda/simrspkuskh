<div class="form-wrapper" data-rio-form>
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Bidan</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="row mb-2">
                    <div class="col-md-12 mb-3">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis_keperawatan',
                            [
                                'section' => '#rio_perawat',
                                'anak' => 'false',
                            ]
                        )
                    </div>
                    <div class="col-md-12 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_menstruasi_kb')
                    </div>
                    <div class="col-md-12 mb-1">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_pernikahan')
                    </div>
                    <div class="col-md-12 mb-1">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
                    </div>
                    <div class="col-md-12 mb-3">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                            [
                                'section' => '#rio_perawat',
                                'page' => 'perawat',
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
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.pemeriksaan_fisik_obsgyn')
                    </div>
                    <div class="col-md-12 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.pemeriksaan_khusus_obsgyn')
                    </div>
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
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_penunjang',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rio_perawat',
                        'metodeNyeri' => ['nrs', 'bps', 'vas']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.masalah_keperawatan',
                    [
                        'section' => '#rio_perawat',
                        'form' => 'obsgyn' // pilihan = 'dewasa' / 'anak' / 'neonatus' / 'obsgyn'
                    ]
                )
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#rio_perawat'])
            </div>
        </div>
    </div>
</div>

