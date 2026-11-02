<div
    id="form_tindakan_kolaborasi_gd"
    data-kunjungan="{{ $kunjungan }}"
>

    <div class="card card-body border border-dashed border-success mb-3">

        <h6 class="mb-3">
            Tindakan Kolaborasi
        </h6>


        <div class="table-responsive">

            <table class="table table-bordered table-display">

                <colgroup>
                    <col style="width: 1%;">
                    <col style="width: 1%;">
                    <col>
                </colgroup>


                <thead class="text-uppercase">

                    <tr class="table-light">

                        <th class="text-center">
                            ✔
                        </th>

                        <th class="text-center">
                            Pukul
                        </th>

                        <th class="text-center">
                            Tindakan Kolaborasi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @php
                        $tindakanKolaborasi = [
                            1  => 'Terapi Oksigenasi',
                            2  => 'Infus',
                            3  => 'Injeksi',
                            4  => 'NGT / OGT',
                            5  => 'Kateter Urin (DC)',
                            6  => 'EKG',
                            7  => 'Suction',
                            8  => 'OPA',
                            9  => 'Collar Neck',
                            10 => 'Resusitasi',
                            11 => 'Nebulizer',
                            12 => 'Medikasi',
                            13 => 'Ekstraksi Corpal',
                            14 => 'Hecting',
                            15 => 'Bilas Lambung',
                            16 => 'Bidai',
                            18 => 'Tampon',
                        ];
                    @endphp


                    @foreach($tindakanKolaborasi as $no => $label)

                        <tr>

                            <td class="text-center">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="tk_{{ $no }}"
                                >

                            </td>


                            <td>

                                <input
                                    type="time"
                                    class="form-control"
                                    name="tk_{{ $no }}_dt"
                                >

                            </td>


                            <td>
                                {{ $label }}
                            </td>

                        </tr>

                    @endforeach


                    <tr>

                        <td class="text-center">

                            <input
                                class="form-check-input check-primary"
                                type="checkbox"
                                name="tk_99"
                            >

                        </td>


                        <td>

                            <input
                                type="time"
                                class="form-control"
                                name="tk_99_dt"
                            >

                        </td>


                        <td>

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    Lain-lain
                                </div>

                                <input
                                    type="text"
                                    class="form-control form-control-sm w-auto"
                                    name="tk_99_lain"
                                >

                            </div>

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
        $('#form_tindakan_kolaborasi_gd');


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
                `/api/v2/emr/pengkajian/gd/tk/${kunjungan}`,

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


                const nomor =
                    [
                        1, 2, 3, 4, 5,
                        6, 7, 8, 9, 10,
                        11, 12, 13, 14, 15,
                        16, 17, 18, 99
                    ];


                nomor.forEach(
                    function (no) {

                        FormHelper.setCheckbox(
                            $form,
                            `tk_${no}`,
                            data[`TK${no}`]
                        );


                        FormHelper.setValue(
                            $form,
                            `tk_${no}_dt`,
                            data[`TK${no}_TIME`]
                        );

                    }
                );


                FormHelper.setValue(
                    $form,
                    'tk_99_lain',
                    data.TK99_LAIN
                );

            },


            error: function (xhr) {

                console.error(
                    'Gagal mengambil Tindakan Kolaborasi:',
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
                `/api/v2/emr/pengkajian/gd/tk/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            error: function (xhr) {

                console.error(
                    'Gagal menyimpan Tindakan Kolaborasi:',
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

            // Khusus checkbox Tindakan Kolaborasi
            if ($(this).is(':checkbox')) {

                const name = $(this).attr('name');

                // Hanya checkbox tk_1 s/d tk_18 dan tk_99
                if (/^tk_(\d+)$/.test(name)) {

                    const no = name.match(/^tk_(\d+)$/)[1];

                    const $time = $form.find(
                        `[name="tk_${no}_dt"]`
                    );

                    if ($(this).is(':checked')) {

                        // Centang -> isi jam sekarang
                        const now = new Date();

                        const hours = String(
                            now.getHours()
                        ).padStart(2, '0');

                        const minutes = String(
                            now.getMinutes()
                        ).padStart(2, '0');

                        $time.val(`${hours}:${minutes}`);

                    } else {

                        // Uncheck -> hapus jam
                        $time.val('');

                    }
                }
            }

            simpanData();

        }
    );


    getData();


})();
</script>
