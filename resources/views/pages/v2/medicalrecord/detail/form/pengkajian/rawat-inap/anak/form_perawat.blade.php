<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-success">RAWAT INAP</b> <b class="text-warning">ANAK</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#riA_perawat',
                        'anak' => 'true',
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#riA_perawat',
                        'page' => 'perawat',
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.pemeriksaan_fisik',['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.aktivitas_latihan_personal_hygiene',['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#riA_perawat',
                        'metodeNyeri' => ['nrs','flacc','vas']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.masalah_keperawatan',
                    [
                        'section' => '#riA_perawat',
                        'form' => 'anak' // pilihan = 'dewasa' / 'anak' / 'neonatus' / 'obsgyn'
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#riA_perawat'])
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#riA_perawat'])
            </div>
        </div>
    </div>
    {{-- <div class="form-footer d-flex justify-content-between">
        <button type="button" class="btn btn-subtle-info btnLihatCPPT" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat CPPT" onclick="showCppt('{{ $list['kunjungan'] }}')">
            <i class="ri-booklet-line me-1"></i> Lihat CPPT
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRiDp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div> --}}
</div>

<script>
    var $sectionRiDp = $('#riA_perawat');
    $(document).ready(function() {

    })
</script>
