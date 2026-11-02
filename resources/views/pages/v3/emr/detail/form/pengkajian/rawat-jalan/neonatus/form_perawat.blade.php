<div class="form-wrapper" id="form_rajal_neonatus_perawat">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">NEONATUS</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            {{-- <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.keluhan_utama',['section' => '#rjn_perawat'])
            </div> --}}
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#rjn_perawat',
                        'anak' => 'true',
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.riwayat_perinatal',['section' => '#rjn_perawat'])
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rjn_perawat',
                        'page' => 'perawat',
                        'neonatus' => 'true',
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
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjn_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#rjn_perawat'])
            </div>
             <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rjn_perawat',
                        'metodeNyeri' => ['nrs', 'vas', 'nips']
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rjn_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rjn_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.masalah_keperawatan_rj',
                    [
                        'section' => '#rjn_perawat',
                        'form' => 'anak' // pilihan = 'dewasa' / 'anak' / 'psikiatri' / 'obsgyn'
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.perencanaan_tindakan',
                    [
                        'section' => '#rjn_perawat',
                        'form' => 'umum' // pilihan = 'umum' / 'psikiatri'
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.asuhan_keperawatan',['section' => '#rjn_perawat'])
            </div>
        </div>
    </div>
    @include('pages.v2.medicalrecord.detail.form.finalisasi', [
        'jenis' => 'rajal_neonatus',
        'role' => 'perawat',
        'formKey' => 'rjn_perawat',
        'form' => 'pengkajian-rajal-neonatus',
        'sub' => 'PERAWAT',
        'kunjungan' => $list['kunjungan'],
    ])
</div>

