<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-danger">RAWAT INAP</b> <b class="text-warning">NEONATUS</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Bidan</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#rin_perawat',
                        'anak' => 'true',
                    ]
                )
            </div>
        </div>
        <div class="col-md-12 mb-3">
            @include(
                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                [
                    'section' => '#rin_perawat',
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
            <div class="form-group mb-2">
                <h5 class="mb-0 text-success">
                    <strong>SKRINING GIZI</strong>
                </h5>
            </div>
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#rin_perawat'])
        </div>
        <div class="col-md-12 mb-3">
            <div class="form-group mb-2">
                <h5 class="mb-0 text-success">
                    <strong>SKRINING RESIKO JATUH</strong>
                </h5>
            </div>
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#rin_perawat'])
        </div>
        <div class="col-md-12 mb-3">
            <div class="form-group mb-2">
                <h5 class="mb-0 text-success">
                    <strong>SKRINING NYERI</strong>
                </h5>
            </div>
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                [
                    'section' => '#rin_perawat',
                    'metodeNyeri' => ['nrs','flacc','vas']
                ]
            )
        </div>
        <div class="col-md-12 mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rin_perawat'])
        </div>
        <div class="col-md-12 mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_penunjang',['section' => '#rin_perawat'])
        </div>
        <div class="col-md-12 mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.masalah_keperawatan',['section' => '#rin_perawat'])
        </div>
        <div class="col-md-12">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#rin_perawat'])
        </div>
    </div>
</div>
