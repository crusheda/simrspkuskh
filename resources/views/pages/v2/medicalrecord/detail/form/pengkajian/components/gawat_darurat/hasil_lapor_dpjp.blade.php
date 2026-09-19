<div id="form_gd_dokter_hasil_lapor_dpjp">

    <div class="form-group">

        <h6>Hasil Lapor DPJP</h6>

        <textarea
            class="form-control"
            name="hld"
            rows="2"></textarea>

    </div>

</div>

<script>
(function () {

    'use strict';

    const $form =
        $('#form_gd_dokter_hasil_lapor_dpjp');

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
                'Form Hasil Lapor DPJP tidak ditemukan.'
            );
            return;
        }

        isDataLoading = true;

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/hld/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data =
                    res.data || {};

                FormHelper.setValue(
                    $form,
                    'hld',
                    data.DESKRIPSI
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data Hasil Lapor DPJP:',
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
                `/api/v2/emr/pengkajian/gd/hld/${kunjungan}/simpan`,

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
                    'Gagal menyimpan Hasil Lapor DPJP:',
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
