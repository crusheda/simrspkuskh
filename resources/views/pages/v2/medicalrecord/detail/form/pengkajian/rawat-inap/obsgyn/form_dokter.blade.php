<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN MEDIS <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#rio_dokter',
                        'anak' => 'false',
                    ]
                )
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
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_obsgyn',['section' => '#rio_dokter'])
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
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_penunjang',['section' => '#rio_dokter'])
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.tata_laksana_terapi',['section' => '#rio_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.target_terapi',['section' => '#rio_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rencana_konsultasi',['section' => '#rio_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kriteria_pulang',['section' => '#rio_dokter'])
            </div>
        </div>
    </div>
</div>
