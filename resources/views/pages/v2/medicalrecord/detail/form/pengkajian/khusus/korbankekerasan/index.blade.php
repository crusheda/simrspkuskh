<div class="form-wrapper" id="form_khusus_korbankekerasan">
    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium"><center>PENGKAJIAN <b class="text-primary">PASIEN DENGAN KEKERASAN ATAU KESEWENANGAN</b></center></h1>
    <div class="form-content">
        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Apakah pasien mengalami luka kekerasan atau penganiayaan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="kp_mengalami_kekerasan" value="Ya" id="kp_mengalami_kekerasan_ya">
                        <label class="form-check-label" for="kp_mengalami_kekerasan_ya">
                            Ya
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="kp_mengalami_kekerasan" value="Tidak" id="kp_mengalami_kekerasan_tidak">
                        <label class="form-check-label" for="kp_mengalami_kekerasan_tidak">
                            Tidak
                        </label>
                    </div>

                </div>

            </div>
        </div>

        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Jenis kekerasan atau penganiayaan yang dialami
                </label>
            </div>

            <div class="col-12 col-md-6">

                <input type="text" class="form-control form-control-sm w-100" name="kp_jenis_kekerasan" id="kp_jenis_kekerasan" placeholder="Jenis kekerasan atau penganiayaan">

            </div>
        </div>


        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Sudah berapa lama mengalami tindak kekerasan atau penganiayaan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <input type="text" class="form-control form-control-sm w-100" name="kp_lama_kekerasan" id="kp_lama_kekerasan" placeholder="Lama mengalami kekerasan atau penganiayaan">

            </div>
        </div>

        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Seberapa sering mengalami kekerasan atau penganiayaan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <input type="text" class="form-control form-control-sm w-100" name="kp_frekuensi_kekerasan" id="kp_frekuensi_kekerasan" placeholder="Frekuensi kekerasan atau penganiayaan">

            </div>
        </div>

        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Siapa yang melakukan kekerasan atau penganiayaan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <input type="text" class="form-control form-control-sm w-100" name="kp_pelaku_kekerasan" id="kp_pelaku_kekerasan" placeholder="Sebutkan siapa yang melakukan kekerasan atau penganiayaan">

            </div>
        </div>

        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Apakah korban memerlukan pendampingan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="kp_memerlukan_pendampingan" value="Ya" id="kp_memerlukan_pendampingan_ya">
                        <label class="form-check-label" for="kp_memerlukan_pendampingan_ya">
                            Ya
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="kp_memerlukan_pendampingan" value="Tidak" id="kp_memerlukan_pendampingan_tidak">
                        <label class="form-check-label" for="kp_memerlukan_pendampingan_tidak">
                            Tidak
                        </label>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_khusus_korbankekerasan');

    let isDataLoading = false;
    let isDataSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getData() {

        if (!$form.length) {
            console.warn('Form Data tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/korbankekerasan/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                // ==========================================================
                // MENGALAMI KEKERASAN / PENGANIAYAAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.MENGALAMI_KEKERASAN)) {

                    $form
                        .find(
                            'input[name="kp_mengalami_kekerasan"]'
                        )
                        .prop(
                            'checked',
                            false
                        );

                    $form
                        .find(
                            'input[name="kp_mengalami_kekerasan"][value="' +
                            tlt.MENGALAMI_KEKERASAN +
                            '"]'
                        )
                        .prop(
                            'checked',
                            true
                        );
                }


                // ==========================================================
                // JENIS KEKERASAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.JENIS_KEKERASAN)) {

                    FormHelper.setValue(
                        $form,
                        'kp_jenis_kekerasan',
                        tlt.JENIS_KEKERASAN
                    );
                }


                // ==========================================================
                // LAMA KEKERASAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.LAMA_KEKERASAN)) {

                    FormHelper.setValue(
                        $form,
                        'kp_lama_kekerasan',
                        tlt.LAMA_KEKERASAN
                    );
                }


                // ==========================================================
                // FREKUENSI KEKERASAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.FREKUENSI_KEKERASAN)) {

                    FormHelper.setValue(
                        $form,
                        'kp_frekuensi_kekerasan',
                        tlt.FREKUENSI_KEKERASAN
                    );
                }


                // ==========================================================
                // PELAKU KEKERASAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.PELAKU_KEKERASAN)) {

                    FormHelper.setValue(
                        $form,
                        'kp_pelaku_kekerasan',
                        tlt.PELAKU_KEKERASAN
                    );
                }


                // ==========================================================
                // MEMERLUKAN PENDAMPINGAN
                // ==========================================================
                if (FormHelper.hasValue(tlt.MEMERLUKAN_PENDAMPINGAN)) {

                    $form
                        .find(
                            'input[name="kp_memerlukan_pendampingan"]'
                        )
                        .prop(
                            'checked',
                            false
                        );

                    $form
                        .find(
                            'input[name="kp_memerlukan_pendampingan"][value="' +
                            tlt.MEMERLUKAN_PENDAMPINGAN +
                            '"]'
                        )
                        .prop(
                            'checked',
                            true
                        );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
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
            !$form.length ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDataSaving = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/korbankekerasan/${kunjungan}/simpan`,
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
                    'Data gagal disimpan.';

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
                isDataSaving = false;
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

        getData();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );
    });

})();
</script>
