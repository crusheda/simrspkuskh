<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-success">RAWAT INAP</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.aktivitas_latihan_personal_hygiene',['section' => '#riD_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#riD_perawat'])
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kriteria_pulang',['section' => '#riD_perawat'])
            </div>
        </div>
    </div>
    <div class="form-footer d-flex justify-content-between">
        <button type="button" class="btn btn-subtle-info btnLihatCPPT" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat CPPT" onclick="showCppt('{{ $list['kunjungan'] }}')">
            <i class="ri-booklet-line me-1"></i> Lihat CPPT
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRiDp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    var $sectionRiDp = $('#riD_perawat');
    $(document).ready(function() {

    })
</script>
