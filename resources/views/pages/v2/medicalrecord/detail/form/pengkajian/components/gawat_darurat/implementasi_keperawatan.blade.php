<div
    id="form_implementasi_keperawatan_gd"
    data-kunjungan="{{ $kunjungan }}"
>

    <div class="card card-body border border-dashed border-success mb-3">

        <h6 class="mb-3">
            Implementasi Keperawatan
        </h6>


        <div class="table-responsive">

            <table class="table table-bordered table-display">

                <colgroup>
                    <col style="width: 1%;">
                    <col>
                </colgroup>


                <thead class="text-uppercase">

                    <tr class="table-light">

                        <th class="text-center">
                            ✔
                        </th>

                        <th class="text-center">
                            Implementasi Keperawatan
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_1"
                            >
                        </td>
                        <td>
                            Melakukan observasi TTV
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_2"
                            >
                        </td>
                        <td>
                            Melakukan observasi keadaan umum pasien
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_3"
                            >
                        </td>
                        <td>
                            Memonitor intake output
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_4"
                            >
                        </td>
                        <td>
                            Memonitor pernafasan : irama, pengembangan dinding dada, penggunaan otot tambahan pernafasan bunyi nafas
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_5"
                            >
                        </td>
                        <td>
                            Melakukan pemasangan Oximetri
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_6"
                            >
                        </td>
                        <td>
                            Mengobservasi produk sputum, jumlah, warna, dan kekentalan
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_7"
                            >
                        </td>
                        <td>
                            Memberikan posisi semi fowler atau posisi miring yang nyaman
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_8"
                            >
                        </td>
                        <td>
                            Melakukan pemasangan OPA
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_9"
                            >
                        </td>
                        <td>
                            Melakukan Suction bila perlu
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_10"
                            >
                        </td>
                        <td>
                            Mengajarkan pasien untuk nafas dalam dan batuk efektif
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_11"
                            >
                        </td>

                        <td>

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    Memberikan oksigen
                                </div>

                                <input
                                    type="text"
                                    class="form-control form-control-sm w-auto"
                                    name="ik_11_lain"
                                >

                                <div class="flex-shrink-0">
                                    liter/menit
                                </div>

                            </div>

                        </td>

                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_12"
                            >
                        </td>
                        <td>
                            Mengimobilisasikan daerah cedera : memasang bidai / spalk / sling
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_13"
                            >
                        </td>
                        <td>
                            Melakukan perawatan luka
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_14"
                            >
                        </td>
                        <td>
                            Mengajarkan manajemen pengelolaan nyeri
                        </td>
                    </tr>


                    <tr>
                        <td class="text-center">
                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="ik_15"
                            >
                        </td>
                        <td>
                            Melakukan tindakan dengan teknik aseptic
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';


    const $form =
        $('#form_implementasi_keperawatan_gd');


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
                `/api/v2/emr/pengkajian/gd/ik/${kunjungan}`,

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


                for (
                    let i = 1;
                    i <= 15;
                    i++
                ) {

                    FormHelper.setCheckbox(
                        $form,
                        `ik_${i}`,
                        data[`IK${i}`]
                    );

                }


                FormHelper.setValue(
                    $form,
                    'ik_11_lain',
                    data.IK11_LAIN
                );

            },


            error: function (xhr) {

                console.error(
                    'Gagal mengambil Implementasi Keperawatan:',
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
                `/api/v2/emr/pengkajian/gd/ik/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Implementasi Keperawatan:',
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


    // ==========================================================
    // GET FIRST
    // ==========================================================

    getData();


})();
</script>
