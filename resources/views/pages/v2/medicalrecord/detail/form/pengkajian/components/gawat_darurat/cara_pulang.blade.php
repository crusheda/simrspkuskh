<div id="form_gd_dokter_cara_pulang">

    <div class="row">

        <div class="col-md-6 mb-3">

            <div class="form-group">

                <label class="form-label fw-bold">Cara Keluar</label>

                <select
                    class="form-control"
                    name="tla_ck">

                    <option value="">Pilih</option>

                </select>

            </div>

        </div>


        <div class="col-md-6 mb-3">

            <div class="form-group">

                <label class="form-label fw-bold">Keadaan Keluar</label>

                <select
                    class="form-control"
                    name="tla_kk">

                    <option value="">Pilih</option>

                </select>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';

    const $form =
        $('#form_gd_dokter_cara_pulang');

    const kunjungan = @json(
        $kunjungan ?? $list['kunjungan']
    );

    let isDataLoading = false;
    let isDataSaving = false;


    // ==========================================================
    // LOAD MASTER CARA KELUAR & KEADAAN KELUAR
    // ==========================================================

    function loadMaster() {

        return $.ajax({

            url:
                `/api/v2/emr/pengkajian/master/carapulang`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const caraKeluar =
                    res.data?.cara_keluar || [];

                const keadaanKeluar =
                    res.data?.keadaan_keluar || [];


                // ==================================================
                // CARA KELUAR
                // ==================================================

                const $caraKeluar =
                    $form.find('select[name="tla_ck"]');

                $caraKeluar
                    .empty()
                    .append(
                        '<option value="">Pilih</option>'
                    );

                $.each(
                    caraKeluar,
                    function (index, item) {

                        $caraKeluar.append(
                            $('<option>', {
                                value: item.ID,
                                text: item.DESKRIPSI
                            })
                        );

                    }
                );


                // ==================================================
                // KEADAAN KELUAR
                // ==================================================

                const $keadaanKeluar =
                    $form.find('select[name="tla_kk"]');

                $keadaanKeluar
                    .empty()
                    .append(
                        '<option value="">Pilih</option>'
                    );

                $.each(
                    keadaanKeluar,
                    function (index, item) {

                        $keadaanKeluar.append(
                            $('<option>', {
                                value: item.ID,
                                text: item.DESKRIPSI
                            })
                        );

                    }
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil master Cara Keluar / Keadaan Keluar:',
                    xhr.responseText
                );

            }

        });

    }


    // ==========================================================
    // GET DATA PASIEN PULANG
    // ==========================================================

    function getData() {

        if (!$form.length) {

            console.warn(
                'Form Cara Pulang tidak ditemukan.'
            );

            return $.Deferred()
                .reject()
                .promise();
        }

        isDataLoading = true;

        return $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/cp/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data =
                    res.data || {};


                // ==================================================
                // CARA KELUAR
                // ==================================================

                FormHelper.setValue(
                    $form,
                    'tla_ck',
                    data.CARA
                );


                // ==================================================
                // KEADAAN KELUAR
                // ==================================================

                FormHelper.setValue(
                    $form,
                    'tla_kk',
                    data.KEADAAN
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data Cara Pulang:',
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
                `/api/v2/emr/pengkajian/gd/cp/${kunjungan}/simpan`,

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
                    'Gagal menyimpan Cara Pulang:',
                    xhr.responseText
                );

            },

            complete: function () {

                isDataSaving = false;

            }

        });

    }


    // ==========================================================
    // AUTO SAVE SELECT
    // ==========================================================

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
    // AUTO SAVE INPUT / TEXTAREA
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


    // ==========================================================
    // INIT
    // ==========================================================

    function init() {

        if (!$form.length) {
            return;
        }

        isDataLoading = true;

        // Master harus selesai terlebih dahulu,
        // baru data pasien dimasukkan ke select.
        loadMaster()
            .done(function () {

                getData();

            })
            .fail(function () {

                isDataLoading = false;

            });

    }


    init();

})();
</script>
