<div class="form-wrapper" id="form_rajal_obsgyn_perawat">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Bidan</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold"> Keluhan Utama </label>
                    <input class="form-control form-control" type="text" name="anm_ku" id="anm_ku">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rjo_perawat',
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
        </div>
        <div class="col-md-12 mb-1">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
        </div>
        <div class="col-md-12">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_menstruasi_kb')
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjo_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
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
                        'metodeNyeri' => ['nrs', 'vas', 'bps']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                {{-- @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#rjo_perawat']) --}}
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#rjo_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rjo_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.masalah_keperawatan_rj',
                    [
                        'section' => '#rjo_perawat',
                        'form' => 'obsgyn' // pilihan = 'dewasa' / 'anak' / 'neonatus' / 'obsgyn'
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.asuhan_keperawatan',['section' => '#rjo_perawat'])
            </div>
        </div>
    </div>
</div>

