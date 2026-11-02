<div class="row align-items-center" id="form_askep">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h6>
                Rencana Asuhan Keperawatan
            </h6>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <textarea
                class="form-control"
                name="rencana_asuhan_keperawatan"
                rows="3"
                placeholder="Masukkan rencana asuhan keperawatan"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_askep');

    let isAskepLoading = false;
    let isAskepSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getAskep() {

        if (!$form.length) {
            console.warn('Form Rencana Asuhan Keperawatan tidak ditemukan.');
            return;
        }

        isAskepLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/askep/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.RENCANA_ASUHAN_KEPERAWATAN)) {
                    FormHelper.setValue(
                        $section,
                        'rencana_asuhan_keperawatan',
                        tlt.RENCANA_ASUHAN_KEPERAWATAN
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Rencana Asuhan Keperawatan:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Rencana Asuhan Keperawatan.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isAskepLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanAskep() {

        if (
            !$form.length ||
            isAskepLoading ||
            isAskepSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isAskepSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/askep/${kunjungan}/simpan`,
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
                    'Data Rencana Asuhan Keperawatan gagal disimpan.';

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
                isAskepSaving = false;
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

        getAskep();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isAskepLoading) {
                    return;
                }

                simpanAskep();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isAskepLoading) {
                    return;
                }

                simpanAskep();
            }
        );
    });

})();
</script>
