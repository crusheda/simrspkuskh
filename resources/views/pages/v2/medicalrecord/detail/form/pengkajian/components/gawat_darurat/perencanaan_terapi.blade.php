<div id="form_gd_dokter_perencanaan_terapi">

    <div class="form-group">

        <h6>Perencanaan Terapi</h6>

        <textarea
            class="form-control"
            name="pt"
            rows="2"></textarea>

    </div>

</div>

<script>
(function () {

    'use strict';

    const $form =
        $('#form_gd_dokter_perencanaan_terapi');

    const kunjungan = @json(
        $kunjungan ?? $list['kunjungan']
    );

    let isDataLoading = false;
    let isDataSaving = false;


    // ==========================================================
    // GET DATA
    // ==========================================================

    function getData() {

        if (!$form.length) {
            console.warn(
                'Form Perencanaan Terapi tidak ditemukan.'
            );
            return;
        }

        isDataLoading = true;

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/pt/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data =
                    res.data || {};

                FormHelper.setValue(
                    $form,
                    'pt',
                    data.DESKRIPSI
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data Perencanaan Terapi:',
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
            !$form.length ||
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
                `/api/v2/emr/pengkajian/gd/pt/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

                // Berhasil disimpan.

            },

            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Perencanaan Terapi:',
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
    // ==========================================================

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


    // ==========================================================
    // INIT
    // ==========================================================

    getData();

})();
</script>
