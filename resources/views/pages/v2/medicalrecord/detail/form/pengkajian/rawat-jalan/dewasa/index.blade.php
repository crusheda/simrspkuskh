<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokter"
        data-bs-target="#gd_dokter" aria-expanded="false" aria-controls="gd_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawat"
        data-bs-target="#gd_perawat" aria-expanded="false" aria-controls="gd_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="gdAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#gdAccordion" id="gd_dokter">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_dokter')
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#gdAccordion" id="gd_perawat">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_perawat')
    </div>
</div>


<script>
    $(document).ready(function () {

        // Jalankan saat pertama kali halaman dibuka
        updateButton();

        // Saat collapse dibuka
        $('.multi-collapse').on('shown.bs.collapse', function () {
            updateButton();
        });

        // Saat collapse ditutup
        $('.multi-collapse').on('hidden.bs.collapse', function () {
            updateButton();
        });

    });

    // update btn collapse
    function updateButton() {
        $('#btnDokter').prop('disabled', $('#gd_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled', $('#gd_perawat').hasClass('show'));
    };
</script>
