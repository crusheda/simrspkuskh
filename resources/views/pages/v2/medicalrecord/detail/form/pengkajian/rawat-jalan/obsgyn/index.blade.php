<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokterRJO"
        data-bs-target="#rjo_dokter" aria-expanded="false" aria-controls="rjo_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawatRJO"
        data-bs-target="#rjo_perawat" aria-expanded="false" aria-controls="rjo_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="rjoAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#rjoAccordion" id="rjo_dokter" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_dokter')
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#rjoAccordion" id="rjo_perawat" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_perawat')
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

        // Hanya memperbolehkan checkbox dipilih salah satu saja
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            $('input.single-checkbox[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });

    });

    // update btn collapse
    function updateButton() {
        $('#btnDokterRJO').prop('disabled', $('#rjo_dokter').hasClass('show'));
        $('#btnPerawatRJO').prop('disabled', $('#rjo_perawat').hasClass('show'));
    };
</script>
