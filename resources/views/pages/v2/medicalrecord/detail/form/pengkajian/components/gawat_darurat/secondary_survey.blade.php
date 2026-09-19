<div id="form_gd_dokter_secondary_survey">

    <h5>
        II. Secondary <b class="text-success">Survey</b>
    </h5>

    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <h6>Keluhan Utama</h6>
                <textarea class="form-control" name="ku" rows="1"></textarea>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <h6>Riwayat Penyakit Dahulu</h6>
                <textarea class="form-control" name="rpd" rows="1"></textarea>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <h6>Riwayat Penyakit Sekarang</h6>
                <textarea class="form-control" name="rps" rows="4"></textarea>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <h6>Pemeriksaan Fisik</h6>
                <textarea class="form-control" name="pf" rows="4"></textarea>
            </div>
        </div>

    </div>

</div>

<script>
(function () {

    'use strict';

    const $form = $('#form_gd_dokter_secondary_survey');

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
            console.warn('Form Secondary Survey tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({

            url: `/api/v2/emr/pengkajian/gd/ss/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data = res.data || {};

                // ==================================================
                // KELUHAN UTAMA
                // ==================================================

                const keluhanUtama =
                    data.keluhan_utama || {};

                FormHelper.setValue(
                    $form,
                    'ku',
                    keluhanUtama.DESKRIPSI
                );


                // ==================================================
                // RIWAYAT PENYAKIT SEKARANG
                // ==================================================

                const anamnesis =
                    data.anamnesis || {};

                FormHelper.setValue(
                    $form,
                    'rps',
                    anamnesis.DESKRIPSI
                );


                // ==================================================
                // RIWAYAT PENYAKIT DAHULU
                // ==================================================

                const rpp =
                    data.rpp || {};

                FormHelper.setValue(
                    $form,
                    'rpd',
                    rpp.DESKRIPSI
                );


                // ==================================================
                // PEMERIKSAAN FISIK
                // ==================================================

                const pemeriksaanFisik =
                    data.pemeriksaan_fisik || {};

                FormHelper.setValue(
                    $form,
                    'pf',
                    pemeriksaanFisik.DESKRIPSI
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data Secondary Survey:',
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

        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );

        isDataSaving = true;

        $.ajax({

            url: `/api/v2/emr/pengkajian/gd/ss/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

                // Data berhasil disimpan.

            },

            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Secondary Survey:',
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
