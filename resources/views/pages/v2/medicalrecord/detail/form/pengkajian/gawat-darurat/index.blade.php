<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokter"
        data-bs-target="#gd_dokter" aria-expanded="false" aria-controls="gd_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawat"
        data-bs-target="#gd_perawat" aria-expanded="false" aria-controls="gd_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="gdAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#gdAccordion" id="gd_dokter" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_dokter')
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#gdAccordion" id="gd_perawat" data-kunjungan="{{ $list['kunjungan'] }}">
        @include('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_perawat')
    </div>
</div>

<script>
    // window.NOKUNJ = @json($list['kunjungan']);
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
        $('#btnDokter').prop('disabled', $('#gd_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled', $('#gd_perawat').hasClass('show'));
    }


    // ============================================================
    // HELPER
    // ============================================================

    // Set value input / textarea / select
    function setValue(name, value) {
        const $el = $form.find(`[name="${name}"]`);

        if (!$el.length) return;

        if (value === null || value === undefined || value === '') {
            $el.val('');
        } else {
            // Normalisasi nilai angka
            if ($el.attr('type') === 'number') {
                const normalized = String(value).replace(',', '.');
                const number = Number(normalized);

                if (!isNaN(number)) {
                    value = number;
                }
            }

            $el.val(value);
        }

        // Trigger jika ada plugin / event yang bergantung pada change
        $el.trigger('change');
    }

    // Checkbox biasa berdasarkan boolean / 0 / 1
    function setCheckbox(name, checked) {
        const $el = $form.find(`input[name="${name}"]`);

        if (!$el.length) return;

        $el.prop('checked', Number(checked) === 1);
        $el.trigger('change');
    }

    // Checkbox group berdasarkan value
    function setCheckboxValue(name, value) {
        const $el = $form.find(`input[name="${name}"][value="${value}"]`);

        if (!$el.length) return;

        $el.prop('checked', true);
        $el.trigger('change');
    }

    // Checkbox group single choice
    function setSingleCheckbox(name, value) {
        const $group = $form.find(`input[name="${name}"]`);

        if (!$group.length) return;

        $group.prop('checked', false);

        if (
            value !== null &&
            value !== undefined &&
            value !== ''
        ) {
            $group
                .filter(`[value="${value}"]`)
                .prop('checked', true)
                .trigger('change');
        }
    }

    function formatDateTimeLocal(value) {
        if (!value) return '';

        return String(value)
            .replace(' ', 'T')
            .substring(0, 16);
    }
</script>
