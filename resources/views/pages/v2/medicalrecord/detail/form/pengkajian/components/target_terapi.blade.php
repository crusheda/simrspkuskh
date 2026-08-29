<div class="row align-items-center" id="form_target_terapi">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h4 class="mb-0 text-danger">
                Target Terapi
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <textarea
                class="form-control"
                name="target_terapi"
                rows="1"
                placeholder="Masukkan target terapi"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_target_terapi');

    let isTargetTerapiLoading = false;
    let isTargetTerapiSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getTargetTerapi() {

        if (!$form.length) {
            console.warn('Form Target Terapi tidak ditemukan.');
            return;
        }

        isTargetTerapiLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/targetterapi/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.DESKRIPSI)) {
                    FormHelper.setValue(
                        $section,
                        'target_terapi',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Target Terapi:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Target Terapi.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isTargetTerapiLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanTargetTerapi() {

        if (
            !$form.length ||
            isTargetTerapiLoading ||
            isTargetTerapiSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isTargetTerapiSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/targetterapi/${kunjungan}/simpan`,
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
                    'Data Target Terapi gagal disimpan.';

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
                isTargetTerapiSaving = false;
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

        getTargetTerapi();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isTargetTerapiLoading) {
                    return;
                }

                simpanTargetTerapi();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isTargetTerapiLoading) {
                    return;
                }

                simpanTargetTerapi();
            }
        );
    });

})();
</script>
