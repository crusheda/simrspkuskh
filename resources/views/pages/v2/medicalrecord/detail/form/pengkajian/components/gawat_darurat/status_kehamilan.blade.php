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

            <div class="col-md-12 mb-3">
                <h6>Khusus Obgyn</h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Usia Gestasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ko_ug">
                                <div class="input-group-text">Minggu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Kontrasi Uterus</label>
                            <input type="text" class="form-control" name="ko_ku">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label class="form-label">Detak Jantung Janin</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ko_dj">
                                <div class="input-group-text">X/menit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Dilatasi Serviks</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ko_ds">
                                <div class="input-group-text">cm</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Kebutuhan Khusus</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label class="form-label">Airbone</label>
                            <input type="text" class="form-control" name="kk_a">
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label class="form-label">Dekontaminan</label>
                            <input type="text" class="form-control" name="kk_d">
                        </div>
                    </div>
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
        $form
            .find('[name="ko_ug"]')
            .val('');
        $form
            .find('[name="ko_ku"]')
            .val('');
        $form
            .find('[name="ko_dj"]')
            .val('');
        $form
            .find('[name="ko_ds"]')
            .val('');
        $form
            .find('[name="kk_a"]')
            .val('');
        $form
            .find('[name="kk_d"]')
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


                const sk = res.data.status_kehamilan ?? '';
                const tg = res.data.triage ?? '';

                /*
                * =====================================================
                * PARSE JSON DARI DATABASE
                * =====================================================
                */
                let obgyn = {};
                let kebutuhan_khusus = {};

                try {
                    obgyn = tg.OBGYN
                        ? JSON.parse(tg.OBGYN)
                        : {};
                } catch (e) {
                    console.error('Gagal parse OBGYN:', e);
                }

                try {
                    kebutuhan_khusus = tg.KEBUTUHAN_KHUSUS
                        ? JSON.parse(tg.KEBUTUHAN_KHUSUS)
                        : {};
                } catch (e) {
                    console.error('Gagal parse KEBUTUHAN_KHUSUS:', e);
                }

                if (FormHelper.hasValue(sk.STATUS_REPRODUKSI)) {
                    FormHelper.setSingleCheckbox(
                        $form,
                        'sh',
                        sk.STATUS_REPRODUKSI
                    );
                }


                FormHelper.setValue(
                    $form,
                    'sh_g',
                    sk.HAMIL_GRAVIDA
                );


                FormHelper.setValue(
                    $form,
                    'sh_p',
                    sk.HAMIL_PARITAS
                );


                FormHelper.setValue(
                    $form,
                    'sh_a',
                    sk.HAMIL_ABORTUS
                );


                FormHelper.setValue(
                    $form,
                    'sh_h',
                    sk.HPHT
                );

                FormHelper.setValue($form, 'ko_ug', obgyn.USIA_GESTASI);
                FormHelper.setValue($form, 'ko_ku', obgyn.KONTRAKSI_UTERUS);
                FormHelper.setValue($form, 'ko_dj', obgyn.DETAK_JANTUNG);
                FormHelper.setValue($form, 'ko_ds', obgyn.DILATASI_SERVIKS);
                FormHelper.setValue($form, 'kk_a', kebutuhan_khusus.AIRBONE);
                FormHelper.setValue($form, 'kk_d', kebutuhan_khusus.DEKONTAMINAN);

                const status =
                    String(
                        sk.STATUS_REPRODUKSI ?? ''
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
