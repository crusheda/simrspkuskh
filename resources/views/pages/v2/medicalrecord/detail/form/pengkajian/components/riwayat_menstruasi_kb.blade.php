<div class="form-group" id="form_riwayat_kb_menstruasi">

    <div class="col-md-12 mb-1">
        <div class="card card-body border border-dashed border-primary">

            <label class="form-label fw-bold">
                Riwayat KB dan Menstruasi
            </label>
            <div class="row">
                <div class="col-md-6 mb-1">
                    {{-- ==========================================================
                        RIWAYAT KB
                    =========================================================== --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Riwayat KB
                        </label>

                        <div class="row">

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_suntik" id="kb_suntik" value="1" >
                                    <label class="form-check-label" for="kb_suntik">
                                        Suntik
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_iud" id="kb_iud" value="1" >
                                    <label class="form-check-label" for="kb_iud">
                                        IUD
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_pil" id="kb_pil" value="1" >
                                    <label class="form-check-label" for="kb_pil">
                                        Pil
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_kondom" id="kb_kondom" value="1" >
                                    <label class="form-check-label" for="kb_kondom">
                                        Kondom
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_kalender" id="kb_kalender" value="1" >
                                    <label class="form-check-label" for="kb_kalender">
                                        Kalender
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_mow" id="kb_mow" value="1" >
                                    <label class="form-check-label" for="kb_mow">
                                        MOW
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_mop" id="kb_mop" value="1" >
                                    <label class="form-check-label" for="kb_mop">
                                        MOP
                                    </label>
                                </div>
                            </div>

                            <div class="col mb-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="kb_implan" id="kb_implan" value="1" >
                                    <label class="form-check-label" for="kb_implan">
                                        Implan
                                    </label>
                                </div>
                            </div>

                        </div>

                        {{-- KELUHAN KB --}}
                        <div class="mt-2">

                            <label class="form-label">
                                Keluhan
                            </label>

                            <textarea class="form-control" name="kb_keluhan" id="kb_keluhan" rows="2" placeholder="Keluhan terkait penggunaan KB..." ></textarea>

                        </div>

                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    {{-- ==========================================================
                        RIWAYAT MENSTRUASI
                    =========================================================== --}}
                    <div>

                        <label class="form-label fw-semibold">
                            Riwayat Menstruasi
                        </label>

                        <div class="row align-items-center">

                            <div class="col-md-6">
                                <label class="form-label mb-0">
                                    Menstruasi Teratur
                                </label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="menstruasi_teratur" id="menstruasi_teratur_ya" value="1" >

                                    <label class="form-check-label"
                                        for="menstruasi_teratur_ya" >
                                        Ya
                                    </label>

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="menstruasi_teratur" id="menstruasi_teratur_tidak" value="0" >

                                    <label class="form-check-label"
                                        for="menstruasi_teratur_tidak" >
                                        Tidak
                                    </label>

                                </div>
                            </div>

                        </div>


                        {{-- KELUHAN MENSTRUASI --}}
                        <div class="mt-2">

                            <label class="form-label">
                                Keluhan
                            </label>

                            <textarea class="form-control" name="menstruasi_keluhan" id="menstruasi_keluhan" rows="2" placeholder="Keluhan menstruasi..." ></textarea>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
(function () {

    'use strict';


    // ==============================================================
    // STATE
    // ==============================================================

    let isRiwayatKbMenstruasiDirty = false;


    // ==============================================================
    // GET DATA
    // ==============================================================

    function getRiwayatKbMenstruasi() {

        $.ajax({

            url: `/api/v2/emr/pengkajian/riwayat_kb_mens/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data = res.riw_kb;

                if (!data) {
                    return;
                }


                // ==================================================
                // RIWAYAT KB
                // ==================================================

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_suntik"]')
                    .prop('checked', data.KB_SUNTIK == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_iud"]')
                    .prop('checked', data.KB_IUD == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_pil"]')
                    .prop('checked', data.KB_PIL == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_kondom"]')
                    .prop('checked', data.KB_KONDOM == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_kalender"]')
                    .prop('checked', data.KB_KALENDER == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_mow"]')
                    .prop('checked', data.KB_MOW == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_mop"]')
                    .prop('checked', data.KB_MOP == 1);

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_implan"]')
                    .prop('checked', data.KB_IMPLAN == 1);


                // ==================================================
                // KELUHAN KB
                // ==================================================

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="kb_keluhan"]')
                    .val(data.KB_KELUHAN ?? '');


                // ==================================================
                // MENSTRUASI
                // ==================================================

                $('#form_riwayat_kb_menstruasi')
                    .find(
                        `[name="menstruasi_teratur"][value="${data.MENSTRUASI_TERATUR}"]`
                    )
                    .prop('checked', true);


                // ==================================================
                // KELUHAN MENSTRUASI
                // ==================================================

                $('#form_riwayat_kb_menstruasi')
                    .find('[name="menstruasi_keluhan"]')
                    .val(data.MENSTRUASI_KELUHAN ?? '');

            },


            error: function (xhr, status, error) {

                console.error(
                    'Error Riwayat KB dan Menstruasi:',
                    xhr.responseText || error
                );

            }

        });

    }


    // ==============================================================
    // SIMPAN DATA
    // ==============================================================

    function simpanRiwayatKbMenstruasi() {

        const $form =
            $('#form_riwayat_kb_menstruasi');


        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );


        $.ajax({

            url: `/api/v2/emr/pengkajian/riwayat_kb_mens/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            success: function (res) {

                console.log(
                    'Riwayat KB dan Menstruasi berhasil disimpan.',
                    res
                );

            },


            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    message =
                        Object.values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('&nbsp;');

                }

                else if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                iziToast.error({

                    title: 'Validasi Gagal!',

                    message: message,

                    position: 'topRight'

                });

            }

        });

    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================

    $(function () {

        const $form =
            $('#form_riwayat_kb_menstruasi');


        // ==========================================================
        // LOAD DATA
        // ==========================================================

        getRiwayatKbMenstruasi();


        // ==========================================================
        // INPUT
        // ==========================================================

        $form.on(
            'input',
            'textarea, input:not([type="checkbox"]):not([type="radio"])',
            function () {

                isRiwayatKbMenstruasiDirty = true;

            }
        );


        // ==========================================================
        // BLUR
        // ==========================================================

        $form.on(
            'blur',
            'textarea, input:not([type="checkbox"]):not([type="radio"])',
            function () {

                if (!isRiwayatKbMenstruasiDirty) {
                    return;
                }


                simpanRiwayatKbMenstruasi();


                isRiwayatKbMenstruasiDirty = false;

            }
        );


        // ==========================================================
        // CHECKBOX / RADIO
        // ==========================================================

        $form.on(
            'change',
            'input[type="checkbox"], input[type="radio"]',
            function (e) {

                // Hindari save saat proses GET mengisi form
                if (!e.originalEvent) {
                    return;
                }


                simpanRiwayatKbMenstruasi();

            }
        );

    });

})();
</script>