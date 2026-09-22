<div id="form_gd_dokter_secondary_survey">

    <h5>
        II. Secondary <b class="text-success">Survey</b>
    </h5>

    <div class="row">

        {{-- ==========================================================
            ANAMNESIS DIPEROLEH
        =========================================================== --}}
        <div class="col-md-12">
            <h6>Anamnesis Diperoleh</h6>

            <div class="form-group d-flex align-items-center mb-3">
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="anam1"
                        value="1"
                        id="anam_1"
                    >
                    <label class="form-check-label" for="anam_1">
                        Autoanamnesis
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="anam2"
                        value="1"
                        id="anam_2"
                    >
                    <label class="form-check-label" for="anam_2">
                        Alloanamnesis
                    </label>
                </div>
                <input type="text" class="form-control" name="anamnesis_dari" placeholder="Dari ...">
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <div class="form-group">
                <h6>Keluhan Utama</h6>
                <textarea class="form-control" name="ku" rows="1"></textarea>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <div class="form-group">
                <h6>Riwayat Penyakit Sekarang</h6>
                <textarea class="form-control" name="rps" rows="4"></textarea>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <div class="form-group">
                <h6>Riwayat Penyakit Dahulu</h6>
                <textarea class="form-control" name="rpd" rows="1"></textarea>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
        </div>
        <div class="col-md-6 mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
        </div>

        <div class="col-md-12">
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
                // ANAMNESIS DIPEROLEH
                // ==================================================
                const anamnesis_diperoleh = data.anam || {};

                if (anamnesis_diperoleh) {
                    if (String(anamnesis_diperoleh.AUTOANAMNESIS) === '1') {
                        const $anamCheckboxes1 = $form.find(
                            'input[type="checkbox"][name="anam1"]'
                        );

                        // Hanya reset checkbox pada form dokter/perawat ini saja.
                        $anamCheckboxes1.prop('checked', false);

                        // Centang sesuai value hanya pada form ini.
                        $anamCheckboxes1.prop('checked', true);
                    }
                    if (String(anamnesis_diperoleh.ALLOANAMNESIS) === '1') {
                        const $anamCheckboxes2 = $form.find(
                            'input[type="checkbox"][name="anam2"]'
                        );

                        // Hanya reset checkbox pada form dokter/perawat ini saja.
                        $anamCheckboxes2.prop('checked', false);

                        // Centang sesuai value hanya pada form ini.
                        $anamCheckboxes2.prop('checked', true);
                    }

                    // PENGISIAN dari
                    if (
                        anamnesis_diperoleh.DARI &&
                        FormHelper.hasValue(anamnesis_diperoleh.DARI)
                    ) {
                        FormHelper.setValue(
                            $form,
                            'anamnesis_dari',
                            anamnesis_diperoleh.DARI
                        );
                    }
                }

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
    // ANAMNESIS DIPEROLEH
    // ==========================================================
    $form
        .off('change.anam1', `input[name="anam1"]`)
        .on('change.anam1', `input[name="anam1"]`, function () {
            if (isDataLoading) {
                return;
            }

            const $this = $(this);

            setTimeout(function () {
                simpanData();
            }, 0);
        });
    $form
        .off('change.anam2', `input[name="anam2"]`)
        .on('change.anam2', `input[name="anam2"]`, function () {
            if (isDataLoading) {
                return;
            }

            const $this = $(this);

            setTimeout(function () {
                simpanData();
            }, 0);
        });

    // ==========================================================
    // AUTO SAVE FIELD LAIN
    // ==========================================================

    $form
        .off('change.autosave', 'textarea,input,select')
        .on('change.autosave', 'textarea,input,select', function () {

            if (isDataLoading) {
                return;
            }

            // Jangan proses checkbox anam di sini
            if ($(this).is('input[name="anam"]')) {
                return;
            }

            simpanData();
        });

    // ==========================================================
    // INIT
    // ==========================================================

    getData();

})();
</script>
