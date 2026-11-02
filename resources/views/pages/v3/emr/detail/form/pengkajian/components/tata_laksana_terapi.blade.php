<div class="row align-items-center" id="form_tata_laksana_terapi">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h4 class="mb-0 text-danger">
                Tata Laksana Terapi
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <textarea
                class="form-control"
                name="tatalaksana_terapi"
                rows="3"
                placeholder="Masukkan tata laksana terapi"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_tata_laksana_terapi');

    let isTataLaksanaTerapiLoading = false;
    let isTataLaksanaTerapiSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getTataLaksanaTerapi() {

        if (!$form.length) {
            console.warn('Form Tata Laksana Terapi tidak ditemukan.');
            return;
        }

        isTataLaksanaTerapiLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/tatalaksanaterapi/${kunjungan}`,
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
                        'tatalaksana_terapi',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Tata Laksana Terapi:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Tata Laksana Terapi.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isTataLaksanaTerapiLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanTataLaksanaTerapi() {

        if (
            !$form.length ||
            isTataLaksanaTerapiLoading ||
            isTataLaksanaTerapiSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isTataLaksanaTerapiSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/tatalaksanaterapi/${kunjungan}/simpan`,
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
                    'Data Tata Laksana Terapi gagal disimpan.';

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
                isTataLaksanaTerapiSaving = false;
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

        getTataLaksanaTerapi();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isTataLaksanaTerapiLoading) {
                    return;
                }

                simpanTataLaksanaTerapi();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isTataLaksanaTerapiLoading) {
                    return;
                }

                simpanTataLaksanaTerapi();
            }
        );
    });

})();
</script>
