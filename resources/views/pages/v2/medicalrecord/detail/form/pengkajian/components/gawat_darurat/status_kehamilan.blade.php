<div
    id="form_status_kehamilan_gd"
    data-kunjungan="{{ $kunjungan }}"
>

    <div class="card card-body border border-dashed border-warning mb-3">

        <h6 class="mb-3">
            Status Kehamilan
        </h6>

        <div class="d-flex align-items-center gap-4">

            <label class="form-label flex-shrink-0">
                Hamil ?
            </label>

            <div class="form-check mb-0">

                <input
                    class="form-check-input check-danger single-checkbox"
                    type="checkbox"
                    name="sh"
                    value="0"
                >

                <label class="form-check-label">
                    Tidak
                </label>

            </div>

            <div class="form-check mb-0">

                <input
                    class="form-check-input check-primary single-checkbox"
                    type="checkbox"
                    name="sh"
                    value="1"
                >

                <label class="form-check-label">
                    Ya
                </label>

            </div>

        </div>


        <div
            class="row mt-3"
            id="tampil_sh_ya"
            hidden
        >

            <div class="col-md-6">

                <div class="d-flex align-items-center gap-4 mb-3">

                    <label class="form-label fw-bold flex-shrink-0">
                        G (Gravida)
                    </label>

                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="sh_g"
                    >

                </div>

            </div>


            <div class="col-md-6">

                <div class="d-flex align-items-center gap-4 mb-3">

                    <label class="form-label fw-bold flex-shrink-0">
                        P (Para)
                    </label>

                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="sh_p"
                    >

                </div>

            </div>


            <div class="col-md-6">

                <div class="d-flex align-items-center gap-4 mb-3">

                    <label class="form-label fw-bold flex-shrink-0">
                        A (Abortus)
                    </label>

                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="sh_a"
                    >

                </div>

            </div>


            <div class="col-md-6">

                <div class="d-flex align-items-center gap-4 mb-3">

                    <label class="form-label fw-bold flex-shrink-0">
                        HPHT (Hari Pertama Haid Terakhir)
                    </label>

                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="sh_h"
                    >

                </div>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';


    const $form =
        $('#form_status_kehamilan_gd');


    if (!$form.length) {
        return;
    }


    const kunjungan =
        $form.data('kunjungan');


    let isDataLoading = false;
    let isDataSaving = false;


    // ==========================================================
    // STATUS KEHAMILAN
    // ==========================================================

    $form.on(
        'change',
        '[name="sh"]',
        function () {

            const nilai =
                $form
                    .find('[name="sh"]:checked')
                    .val();


            if (nilai === '1') {

                $form
                    .find('#tampil_sh_ya')
                    .prop('hidden', false);

            } else {

                $form
                    .find('#tampil_sh_ya')
                    .prop('hidden', true);

                resetStatusKehamilan();

            }

        }
    );


    function resetStatusKehamilan() {

        $form
            .find('[name="sh_g"]')
            .val('');

        $form
            .find('[name="sh_p"]')
            .val('');

        $form
            .find('[name="sh_a"]')
            .val('');

        $form
            .find('[name="sh_h"]')
            .val('');

    }


    // ==========================================================
    // GET DATA
    // ==========================================================

    function getData() {

        if (!kunjungan) {
            return;
        }


        isDataLoading = true;


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/sk/${kunjungan}`,

            type: 'GET',

            dataType: 'json',


            success: function (res) {

                if (
                    !res ||
                    !res.status ||
                    !res.data
                ) {
                    return;
                }


                const data =
                    res.data;


                FormHelper.setSingleCheckbox(
                    $form,
                    'sh',
                    data.STATUS_REPRODUKSI
                );


                FormHelper.setValue(
                    $form,
                    'sh_g',
                    data.HAMIL_GRAVIDA
                );


                FormHelper.setValue(
                    $form,
                    'sh_p',
                    data.HAMIL_PARITAS
                );


                FormHelper.setValue(
                    $form,
                    'sh_a',
                    data.HAMIL_ABORTUS
                );


                FormHelper.setValue(
                    $form,
                    'sh_h',
                    data.HPHT
                );


                const status =
                    String(
                        data.STATUS_REPRODUKSI ?? ''
                    );


                if (status === '1') {

                    $form
                        .find('#tampil_sh_ya')
                        .prop('hidden', false);

                } else {

                    $form
                        .find('#tampil_sh_ya')
                        .prop('hidden', true);

                }

            },


            error: function (xhr) {

                console.error(
                    'Gagal mengambil Status Kehamilan:',
                    xhr.responseJSON ||
                    xhr.responseText
                );

            },


            complete: function () {

                isDataLoading = false;

            }

        });

    }


    // ==========================================================
    // SIMPAN DATA
    // ==========================================================

    function simpanData() {

        if (
            !kunjungan ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }


        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );


        isDataSaving = true;


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/sk/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Status Kehamilan:',
                    xhr.responseJSON ||
                    xhr.responseText
                );

            },


            complete: function () {

                isDataSaving = false;

            }

        });

    }


    // ==========================================================
    // AUTO SAVE
    //
    // TEXT / NUMBER / TEXTAREA
    // -> BLUR
    //
    // CHECKBOX / RADIO / SELECT
    // -> CHANGE
    // ==========================================================

    $form.on(
        'blur',
        'input:not([type="checkbox"]):not([type="radio"]), textarea',
        function () {

            if (isDataLoading) {
                return;
            }

            simpanData();

        }
    );


    $form.on(
        'change',
        'select, input[type="checkbox"], input[type="radio"]',
        function () {

            if (isDataLoading) {
                return;
            }

            simpanData();

        }
    );


    // ==========================================================
    // LOAD DATA PERTAMA
    // ==========================================================

    getData();


})();
</script>
