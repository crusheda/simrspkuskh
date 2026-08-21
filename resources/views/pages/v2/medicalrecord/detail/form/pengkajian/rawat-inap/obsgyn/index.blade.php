<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokter"
        data-bs-target="#rio_dokter" aria-expanded="false" aria-controls="rio_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawat"
        data-bs-target="#rio_perawat" aria-expanded="false" aria-controls="rio_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="rioAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#rioAccordion" id="rio_dokter" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_dokter')
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#rioAccordion" id="rio_perawat" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_perawat')
    </div>
</div>

<script>
    $(document).ready(function() {
        // Jalankan saat pertama kali halaman dibuka
        updateButton();

        // Saat collapse dibuka
        $('.multi-collapse').on('shown.bs.collapse', function () {
            updateButton();
            $(this).find('.form-content').scrollTop(0);
        });

        // Saat collapse ditutup
        $('.multi-collapse').on('hidden.bs.collapse', function () {
            updateButton();
            $(this).find('.form-content').scrollTop(0);
        });

        // Hanya memperbolehkan checkbox dipilih salah satu saja
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            $('input.single-checkbox[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
        $('.single-checkbox-bos').on('change', function () {
            // Jika checkbox di-uncheck, langsung kembalikan ke checked
            if (!this.checked) {
                this.checked = true;
                return;
            }
            // Uncheck pilihan lain dengan name yang sama
            $('input.single-checkbox-bos[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
    })

    // update btn collapse
    function updateButton() {
        $('#btnDokter').prop('disabled', $('#rio_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled', $('#rio_perawat').hasClass('show'));
    }
</script>
