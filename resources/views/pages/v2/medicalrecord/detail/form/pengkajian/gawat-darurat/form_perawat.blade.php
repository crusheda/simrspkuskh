<div class="form-wrapper position-relative" id="form_gawat_darurat_perawat">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-success">RAWAT DARURAT</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.triageperawat',
                    [
                        'section' => '#gd_perawat',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                    ]
                )
                @include( 'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.status_kehamilan', ['section' => '#gd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial', ['section' => '#gd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-success mb-3">
                    <h6 class="mb-3">SKRINING NYERI</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                        [
                            'section' => '#gd_perawat',
                            'metodeNyeri' => ['nrs', 'bps', 'nips', 'flacc', 'vas']
                        ]
                    )
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-secondary mb-3">
                    <h6>SKRINING RESIKO JATUH</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_epfra', ['section' => '#gd_perawat'])
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary mb-3">
                    <h6 class="mb-3">SKRINING GIZI</h6>
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#gd_perawat'])
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#gd_perawat'])
                </div>
            </div>
            <div class="col-md-12">
                @include( 'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.implementasi_keperawatan', ['section' => '#gd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
                @include( 'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.tindakan_kolaborasi', ['section' => '#gd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
                @include( 'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.diagnosis_keperawatan', ['section' => '#gd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#gd_perawat'])
            </div>
        </div>
    </div>

    @include(
        'pages.v2.medicalrecord.detail.form.finalisasi',
        [
            'jenis' => 'gawat_darurat',
            'role' => 'perawat',
            'sub' => 'PERAWAT',
            'formKey' => 'gd_perawat',
            'kunjungan' => $kunjungan ?? $list['kunjungan'],
        ]
    )

</div>

<script>
    var $sectionGdP = $('#gd_perawat');
    $(document).ready(function() {

    })
</script>
