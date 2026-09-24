<div class="form-wrapper" id="form_rajal_anak_perawat">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">ANAK</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            {{-- <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.keluhan_utama',['section' => '#rja_perawat'])
            </div> --}}
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#rja_perawat',
                        'anak' => 'true',
                    ]
                )
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rja_perawat',
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
            <div class="col-md-12 mb-1">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rja_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#rja_perawat'])
            </div>
             <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rja_perawat',
                        'metodeNyeri' => ['nrs', 'vas', 'flacc']
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rja_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.riwayat_perinatal',['section' => '#rja_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rja_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.masalah_keperawatan_rj',
                    [
                        'section' => '#rja_perawat',
                        'form' => 'anak' // pilihan = 'dewasa' / 'anak' / 'psikiatri' / 'obsgyn'
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.perencanaan_tindakan',
                    [
                        'section' => '#rja_perawat',
                        'form' => 'umum' // pilihan = 'umum' / 'psikiatri'
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.asuhan_keperawatan',['section' => '#rja_perawat'])
            </div>
        </div>
    </div>
    @include('pages.v2.medicalrecord.detail.form.finalisasi', [
        'jenis' => 'rajal_anak',
        'formKey' => 'rja_perawat',
        'form' => 'pengkajian-rajal-anak',
        'sub' => 'PERAWAT',
        'kunjungan' => $list['kunjungan'],
    ])
</div>

