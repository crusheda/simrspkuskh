<div class="row align-items-center" id="form_tu_terapi">
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">Tolok Ukur / Sasaran yang Dicapai</label>
        <textarea class="form-control" name="tu" id="tu" rows="3"></textarea>
    </div>
    <div class="col-md-12">
        <label class="form-label fw-bold">Terapi / Tindakan</label>
        <textarea class="form-control" name="terapi_tind" id="terapi_tind" rows="3"></textarea>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_tu_terapi');

    let isTuTerapiLoading = false;
    let isTuTerapiSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getTuTerapi() {

        if (!$form.length) {
            console.warn('Form Tolok Ukur Terapi tidak ditemukan.');
            return;
        }

        isTuTerapiLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rajal/tu_terapi/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.TOLAK_UKUR)) {
                    FormHelper.setValue(
                        $section,
                        'tu',
                        tlt.TOLAK_UKUR
                    );
                }

                if (FormHelper.hasValue(tlt.DESKRIPSI)) {
                    FormHelper.setValue(
                        $section,
                        'terapi_tind',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Tolok Ukur Terapi:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Tolok Ukur Terapi.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isTuTerapiLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanTuTerapi() {

        if (
            !$form.length ||
            isTuTerapiLoading ||
            isTuTerapiSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isTuTerapiSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rajal/tu_terapi/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data Tolok Ukur Terapi gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isTuTerapiSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        getTuTerapi();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isTuTerapiLoading) {
                    return;
                }
                console.log('Memanggil simpanTuTerapi()');
                simpanTuTerapi();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isTuTerapiLoading) {
                    return;
                }

                simpanTuTerapi();
            }
        );
    });

})();
</script>
