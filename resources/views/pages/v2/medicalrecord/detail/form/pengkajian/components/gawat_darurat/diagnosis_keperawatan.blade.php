<div
    id="form_diagnosis_keperawatan_gd"
    data-kunjungan="{{ $kunjungan }}"
>

    <div class="card card-body border border-dashed border-danger mb-3">

        <h6 class="mb-3">
            Diagnosis Keperawatan
        </h6>


        <div class="row">

            <div class="col-md-6">

                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_1"
                    >

                    <label class="form-check-label">
                        Nyeri
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_2"
                    >

                    <label class="form-check-label">
                        Cemas
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_3"
                    >

                    <label class="form-check-label">
                        Perubahan Nutrisi
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_4"
                    >

                    <label class="form-check-label">
                        Gangguan Pernafasan
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_5"
                    >

                    <label class="form-check-label">
                        Gangguan Perfusi Jaringan
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_6"
                    >

                    <label class="form-check-label">
                        Gangguan Volume Cairan
                    </label>

                </div>

            </div>


            <div class="col-md-6">

                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_7"
                    >

                    <label class="form-check-label">
                        Potensi Infeksi
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_8"
                    >

                    <label class="form-check-label">
                        Hipertermi
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_9"
                    >

                    <label class="form-check-label">
                        Takut (Pada Anak)
                    </label>

                </div>


                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="dmk_10"
                    >

                    <label class="form-check-label">
                        Ketidak Efektifan Pola Makan
                    </label>

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Masalah Lain
                    </label>

                    <textarea
                        class="form-control"
                        name="dmk_lain"
                        rows="1"
                    ></textarea>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';


    const $form =
        $('#form_diagnosis_keperawatan_gd');


    if (!$form.length) {
        return;
    }


    const kunjungan =
        $form.data('kunjungan');


    let isDataLoading = false;
    let isDataSaving = false;


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
                `/api/v2/emr/pengkajian/gd/dk/${kunjungan}`,

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


                const mapping = {

                    dmk_1:
                        data.NYERI,

                    dmk_2:
                        data.CEMAS,

                    dmk_3:
                        data.PERUBAHAN_NUTRISI,

                    dmk_4:
                        data.GANGGUAN_PERNAFASAN,

                    dmk_5:
                        data.GANGGUAN_PERFUSI_JARINGAN,

                    dmk_6:
                        data.GANGGUAN_VOLUME_CAIRAN,

                    dmk_7:
                        data.POTENSI_INFEKSI,

                    dmk_8:
                        data.HIPERTERMI,

                    dmk_9:
                        data.TAKUT,

                    dmk_10:
                        data.KETIDAKEFEKTIFAN_POLA_MAKAN

                };


                Object.keys(mapping).forEach(
                    function (name) {

                        FormHelper.setCheckbox(
                            $form,
                            name,
                            mapping[name]
                        );

                    }
                );


                FormHelper.setValue(
                    $form,
                    'dmk_lain',
                    data.MASALAH_LAIN
                );

            },


            error: function (xhr) {

                console.error(
                    'Gagal mengambil Diagnosis Keperawatan:',
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
    // SIMPAN
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
                `/api/v2/emr/pengkajian/gd/dk/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Diagnosis Keperawatan:',
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


    getData();


})();
</script>
