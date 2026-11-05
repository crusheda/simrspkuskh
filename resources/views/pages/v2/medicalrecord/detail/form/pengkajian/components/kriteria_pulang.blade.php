<div id="form_kriteria_pulang">

    {{-- TITLE --}}
    <div class="mb-2">
        <h4 class="mb-0 text-danger">
            Kriteria Pulang
        </h4>
    </div>

    {{-- PERKIRAAN LAMA RAWAT --}}
    <div class="d-flex align-items-center flex-wrap gap-2">

        <label class="fw-bold text-nowrap">
            Perkiraan Lama Rawat :
        </label>

        <input
            type="number"
            class="form-control form-control-sm"
            name="kp_plr_hari"
            min="1"
            style="width: 80px;"
            disabled
        >

        <span class="text-nowrap">Hari</span>

    </div>

</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_kriteria_pulang');

    let isKriteriaPulangLoading = false;
    let isKriteriaPulangSaving = false;

    // ==============================================================
    // GET DATA
    // ==============================================================
    function getKriteriaPulang() {

        if (!$form.length) {
            console.warn(
                'Form Kriteria Pulang tidak ditemukan.'
            );

            return;
        }

        isKriteriaPulangLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/kriteriapulang/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const kpl = res.data;

                if (!kpl) {
                    return;
                }

                // ==================================================
                // HARI
                // ==================================================
                if (FormHelper.hasValue(kpl.HARI)) {

                    FormHelper.setValue(
                        $section,
                        'kp_plr_hari',
                        kpl.HARI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Kriteria Pulang:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Kriteria Pulang.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {

                isKriteriaPulangLoading = false;
            }
        });
    }


    // ==============================================================
    // SIMPAN DATA
    // ==============================================================
    function simpanKriteriaPulang() {

        if (
            !$form.length ||
            isKriteriaPulangLoading ||
            isKriteriaPulangSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isKriteriaPulangSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/kriteriapulang/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {
                console.log(
                    'Kriteria Pulang berhasil disimpan.'
                );
            },

            error: function (xhr) {

                let message =
                    'Data Kriteria Pulang gagal disimpan.';


                // ==================================================
                // VALIDATION ERROR
                // ==================================================
                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');

                }

                // ==================================================
                // GENERAL ERROR
                // ==================================================
                else if (xhr.responseJSON?.message) {

                    message =
                        xhr.responseJSON.message;
                }


                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {

                isKriteriaPulangSaving = false;
            }
        });
    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {

        // ==========================================================
        // GET DATA TERLEBIH DAHULU
        // ==========================================================
        getKriteriaPulang();

        // ==========================================================
        // INPUT HARI
        //
        // Simpan ketika user selesai mengisi / keluar dari input
        // ==========================================================
        $section
            .off(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_hari"]'
            )
            .on(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_hari"]',
                function () {

                    if (isKriteriaPulangLoading) {
                        return;
                    }

                    simpanKriteriaPulang();
                }
            );
    });

})();
</script>
