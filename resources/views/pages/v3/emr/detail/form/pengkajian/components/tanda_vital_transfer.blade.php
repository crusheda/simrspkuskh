@php
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI COMPONENT
    |--------------------------------------------------------------------------
    |
    | page:
    | - perawat = semua field dapat diedit dan autosave aktif
    | - dokter   = default semua readonly
    |
    | editableFields:
    | - Digunakan khusus untuk page dokter
    | - Berisi name input yang boleh diedit oleh dokter
    |
    */

    $page = strtolower($page ?? 'perawat');
    $editableFields = $editableFields ?? [];

    if (!is_array($editableFields)) {
        $editableFields = [];
    }

    // 1 = Sebelum Transfer
    // 2 = Sesudah Transfer
    $transfer = (int) ($transfer ?? 1);

    // Validasi agar hanya 1 atau 2
    if (!in_array($transfer, [1, 2], true)) {
        $transfer = 1;
    }
@endphp


<div
    class="form-group"
    id="form_tanda_vital_transfer_{{ $transfer }}"
>

    <h4 class="text-danger">
        Tanda Vital
    </h4>

    <div class="row">

        {{-- ==========================================================
            KOLOM KIRI
        =========================================================== --}}
        <div class="col-md-6">

            {{-- KEADAAN UMUM --}}
            <div class="form-group">
                <div class="form-group mb-3">

                    <label class="form-label">
                        Keadaan Umum
                    </label>

                    <textarea
                        class="form-control"
                        name="tv_keu_tf"
                        rows="1"
                    ></textarea>

                </div>
            </div>


            {{-- GCS --}}
            <div class="form-group mb-3">

                <label class="form-label">
                    GCS (<i>Glasgow Coma Scale</i>)
                </label>

                <div class="d-flex align-items-center column-gap-3 row-gap-3 flex-wrap">

                    {{-- EYE --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                        <label class="form-check-label">
                            Eye
                        </label>

                        <input
                            type="number"
                            class="form-control form-control-sm"
                            name="tv_gcs_e_tf"
                            min="1"
                            max="4"
                            style="width: 70px; flex: 0 0 60px;"
                        >

                    </div>


                    {{-- VERBAL --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                        <label class="form-check-label">
                            Verbal
                        </label>

                        <input
                            type="number"
                            class="form-control form-control-sm"
                            name="tv_gcs_v_tf"
                            min="1"
                            max="5"
                            style="width: 70px; flex: 0 0 60px;"
                        >

                    </div>


                    {{-- MOTOR --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                        <label class="form-check-label">
                            Move
                        </label>

                        <input
                            type="number"
                            class="form-control form-control-sm"
                            name="tv_gcs_m_tf"
                            min="1"
                            max="6"
                            style="width: 70px; flex: 0 0 60px;"
                        >

                    </div>


                    {{-- TOTAL --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                        <label class="form-check-label">
                            Total
                        </label>

                        <input
                            type="number"
                            class="form-control form-control-sm"
                            name="tv_gcs_t_tf"
                            style="width: 70px; flex: 0 0 60px;"
                            readonly
                        >

                    </div>

                </div>
            </div>


            {{-- TEKANAN DARAH --}}
            <div class="form-group mb-3">

                <label class="form-label">
                    Tekanan Darah
                </label>

                <div class="input-group">

                    <input
                        type="number"
                        class="form-control"
                        name="tv_td_up_tf"
                    >

                    <div class="input-group-text">
                        /
                    </div>

                    <input
                        type="number"
                        class="form-control"
                        name="tv_td_down_tf"
                    >

                    <div class="input-group-text">
                        mmHg
                    </div>

                </div>

            </div>


            {{-- FREKUENSI NADI --}}
            <div class="form-group mb-3">

                <label class="form-label">
                    Frekuensi Nadi
                </label>

                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <div
                        class="input-group flex-grow-1"
                        style="flex: 1 1 100px; min-width: 50px;"
                    >

                        <input
                            type="number"
                            class="form-control"
                            name="tv_nadi_tf"
                        >

                        <span class="input-group-text">
                            X/menit
                        </span>

                    </div>


                    {{-- REGULER --}}
                    <div class="form-check m-0 flex-shrink-0">

                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="tv_nafas_nadi_tf"
                            value="1"
                            checked
                        >

                        <label class="form-check-label">
                            Reguler
                        </label>

                    </div>


                    {{-- IREGULER --}}
                    <div class="form-check m-0 flex-shrink-0">

                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="tv_nafas_nadi_tf"
                            value="2"
                        >

                        <label class="form-check-label">
                            Ireguler
                        </label>

                    </div>

                </div>

            </div>

        </div>


        {{-- ==========================================================
            KOLOM KANAN
        =========================================================== --}}
        <div class="col-md-6">

            {{-- FREKUENSI NAFAS --}}
            <div class="form-group mb-3">

                <label class="form-label">
                    Frekuensi Nafas
                </label>

                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <div
                        class="input-group"
                        style="flex: 1 1 100px; min-width: 50px;"
                    >

                        <input
                            type="number"
                            class="form-control"
                            name="tv_nafas_tf"
                        >

                        <span class="input-group-text">
                            X/menit
                        </span>

                    </div>


                    {{-- SIMETRIS --}}
                    <div class="form-check m-0 flex-shrink-0">

                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="tv_nafas_cb_tf"
                            value="1"
                            checked
                        >

                        <label class="form-check-label">
                            Simetris
                        </label>

                    </div>


                    {{-- ASIMETRIS --}}
                    <div class="form-check m-0 flex-shrink-0">

                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="tv_nafas_cb_tf"
                            value="2"
                        >

                        <label class="form-check-label">
                            Asimetris
                        </label>

                    </div>

                </div>

            </div>


            {{-- SUHU + SPO2 --}}
            <div class="row">

                {{-- SUHU --}}
                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label class="form-label">
                            Suhu
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                name="tv_suhu_tf"
                            >

                            <div class="input-group-text">
                                °C
                            </div>

                        </div>

                    </div>

                </div>


                {{-- SPO2 --}}
                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label class="form-label">
                            SpO2
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                name="tv_spo2_tf"
                            >

                            <div class="input-group-text">
                                %
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- BB + TB --}}
            <div class="row">

                {{-- BB --}}
                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label class="form-label">
                            BB
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                name="tv_bb_tf"
                                step="0.1"
                                min="0"
                            >

                            <div class="input-group-text">
                                Kg
                            </div>

                        </div>

                    </div>

                </div>


                {{-- TB --}}
                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label class="form-label">
                            TB
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                name="tv_tb_tf"
                                step="0.1"
                                min="0"
                            >

                            <div class="input-group-text">
                                Cm
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- IMT --}}
            <div class="form-group mt-2">

                <input
                    type="text"
                    class="form-control"
                    name="gizi_imt_tf"
                    hidden
                >

                <div
                    id="hasil_imt_transfer_{{ $transfer }}_tf"
                    class="alert alert-danger d-flex align-items-center d-none"
                    role="alert"
                >
                    <i class="ri-spam-line me-2"></i>

                    <span
                        id="hasil_imt_transfer_{{ $transfer }}_text_tf"
                    ></span>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';

    // ==============================================================
    // CONFIGURATION
    // ==============================================================

    const $section = $(@json($section));

    const page = @json($page);

    const editableFields = @json($editableFields);

    const transfer = @json($transfer);


    // ==============================================================
    // FORM TTV TRANSFER
    // ==============================================================

    const $formTTV = $section.find(
        '#form_tanda_vital_transfer_{{ $transfer }}'
    );


    // Debug jika diperlukan
    console.log(
        'TTV Transfer',
        transfer,
        'section:',
        $section.length,
        'form:',
        $formTTV.length
    );


    // ==============================================================
    // STATE
    // ==============================================================

    let isTandaVitalDirty = false;


    // ==============================================================
    // CEK APAKAH FIELD BOLEH DIEDIT
    // ==============================================================

    function bolehEditTandaVital(name) {

        if (!name) {
            return false;
        }


        // ----------------------------------------------------------
        // PERAWAT
        // ----------------------------------------------------------

        if (page === 'perawat') {
            return true;
        }


        // ----------------------------------------------------------
        // DOKTER
        // ----------------------------------------------------------

        if (page === 'dokter') {
            return editableFields.includes(name);
        }


        // ----------------------------------------------------------
        // PAGE TIDAK DIKENAL
        // ----------------------------------------------------------

        return false;
    }


    // ==============================================================
    // APPLY ACCESS / READONLY
    // ==============================================================

    function applyTandaVitalAccess() {

        const $form = $formTTV;

        if (!$form.length) {
            return;
        }

        $form
            .find('input, textarea, select')
            .each(function () {

                const $input = $(this);

                const name = $input.attr('name');

                if (!name) {
                    return;
                }


                // --------------------------------------------------
                // FIELD HASIL PERHITUNGAN
                // --------------------------------------------------

                if (
                    name === 'tv_gcs_t_tf' ||
                    name === 'gizi_imt_tf'
                ) {

                    $input.prop('readonly', true);

                    return;
                }


                // --------------------------------------------------
                // FIELD BOLEH EDIT
                // --------------------------------------------------

                if (bolehEditTandaVital(name)) {

                    if (
                        $input.is(':checkbox') ||
                        $input.is(':radio')
                    ) {

                        $input.prop('disabled', false);

                    }
                    else if ($input.is('select')) {

                        $input.prop('disabled', false);

                    }
                    else {

                        $input.prop('readonly', false);

                    }

                    return;
                }


                // --------------------------------------------------
                // FIELD READONLY
                // --------------------------------------------------

                if (
                    $input.is(':checkbox') ||
                    $input.is(':radio')
                ) {

                    $input.prop('disabled', true);

                }
                else if ($input.is('select')) {

                    $input.prop('disabled', true);

                }
                else {

                    $input.prop('readonly', true);

                }

            });
    }


    // ==============================================================
    // HITUNG GCS
    // ==============================================================

    function hitungGCS() {

        const eye = parseInt(
            $formTTV
                .find('[name="tv_gcs_e_tf"]')
                .val(),
            10
        );

        const verbal = parseInt(
            $formTTV
                .find('[name="tv_gcs_v_tf"]')
                .val(),
            10
        );

        const motorik = parseInt(
            $formTTV
                .find('[name="tv_gcs_m_tf"]')
                .val(),
            10
        );


        // ----------------------------------------------------------
        // JIKA SALAH SATU BELUM DIISI
        // ----------------------------------------------------------

        if (
            isNaN(eye) ||
            isNaN(verbal) ||
            isNaN(motorik)
        ) {

            $formTTV
                .find('[name="tv_gcs_t_tf"]')
                .val('');

            return;
        }


        // ----------------------------------------------------------
        // VALIDASI RANGE
        // ----------------------------------------------------------

        if (
            eye < 1 ||
            eye > 4 ||
            verbal < 1 ||
            verbal > 5 ||
            motorik < 1 ||
            motorik > 6
        ) {

            $formTTV
                .find('[name="tv_gcs_t_tf"]')
                .val('');

            return;
        }


        // ----------------------------------------------------------
        // HITUNG TOTAL
        // ----------------------------------------------------------

        const total =
            eye +
            verbal +
            motorik;


        $formTTV
            .find('[name="tv_gcs_t_tf"]')
            .val(total);
    }


    // ==============================================================
    // HITUNG IMT
    // ==============================================================

    function hitungIMT() {

        const bb = parseFloat(
            $formTTV
                .find('[name="tv_bb_tf"]')
                .val()
        );

        const tbCm = parseFloat(
            $formTTV
                .find('[name="tv_tb_tf"]')
                .val()
        );


        const $hasil = $formTTV.find(
            '#hasil_imt_transfer_{{ $transfer }}_tf'
        );

        const $text = $formTTV.find(
            '#hasil_imt_transfer_{{ $transfer }}_text_tf'
        );


        // ----------------------------------------------------------
        // BB / TB BELUM VALID
        // ----------------------------------------------------------

        if (
            isNaN(bb) ||
            isNaN(tbCm) ||
            bb <= 0 ||
            tbCm <= 0
        ) {

            $hasil.addClass('d-none');

            $text.text('');

            $formTTV
                .find('[name="gizi_imt_tf"]')
                .val('');

            return;
        }


        // ----------------------------------------------------------
        // KONVERSI TB
        // ----------------------------------------------------------

        const tbMeter = tbCm / 100;


        // ----------------------------------------------------------
        // RUMUS IMT
        // ----------------------------------------------------------

        const imt =
            bb /
            (tbMeter * tbMeter);


        // ----------------------------------------------------------
        // SIMPAN NILAI IMT
        // ----------------------------------------------------------

        $formTTV
            .find('[name="gizi_imt_tf"]')
            .val(imt);


        // ----------------------------------------------------------
        // KLASIFIKASI
        // ----------------------------------------------------------

        let kategori = '';

        let alertClass = '';

        let icon = '';


        if (imt < 18.5) {

            kategori =
                'Berat Badan Kurang (Underweight)';

            alertClass =
                'alert-danger';

            icon =
                'ri-spam-line';

        }
        else if (
            imt >= 18.5 &&
            imt <= 22.9
        ) {

            kategori =
                'Berat Badan Normal';

            alertClass =
                'alert-success';

            icon =
                'ri-checkbox-circle-line';

        }
        else if (
            imt >= 23 &&
            imt <= 24.9
        ) {

            kategori =
                'Kelebihan Berat Badan (Overweight) dengan Risiko';

            alertClass =
                'alert-warning';

            icon =
                'ri-alert-line';

        }
        else if (
            imt >= 25 &&
            imt <= 29.9
        ) {

            kategori =
                'Obesitas I';

            alertClass =
                'alert-danger';

            icon =
                'ri-spam-line';

        }
        else {

            kategori =
                'Obesitas II';

            alertClass =
                'alert-danger';

            icon =
                'ri-spam-line';
        }


        // ----------------------------------------------------------
        // RESET ALERT
        // ----------------------------------------------------------

        $hasil.removeClass(
            'alert-success alert-danger alert-warning alert-info'
        );


        // ----------------------------------------------------------
        // SET ALERT
        // ----------------------------------------------------------

        $hasil.addClass(alertClass);


        // ----------------------------------------------------------
        // UPDATE HASIL
        // ----------------------------------------------------------

        $hasil.html(`
            <i class="${icon} me-2"></i>
            IMT&nbsp;:&nbsp;<strong>${imt.toFixed(2)}</strong>
            &nbsp;—&nbsp;
            ${kategori}
        `);


        // ----------------------------------------------------------
        // TAMPILKAN
        // ----------------------------------------------------------

        $hasil.removeClass('d-none');
    }


    // ==============================================================
    // SET SINGLE CHECKBOX
    // ==============================================================

    function setSingleCheckbox(name, value) {

        const normalizedValue =
            String(value).trim();


        $formTTV
            .find(`[name="${name}"]`)
            .each(function () {

                const $checkbox = $(this);

                $checkbox.prop(
                    'checked',
                    String($checkbox.val()).trim() ===
                    normalizedValue
                );

            });
    }


    // ==============================================================
    // GET DATA TANDA VITAL
    // ==============================================================

    function getTandaVital() {

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/tandavitaltf/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            data: {
                transfer: transfer
            },

            beforeSend: function () {
            },

            success: function (res) {

                const ttv = res.data;


                if (!ttv) {

                    applyTandaVitalAccess();

                    return;
                }


                // --------------------------------------------------
                // KEADAAN UMUM
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.KEADAAN_UMUM
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_keu_tf',
                        ttv.KEADAAN_UMUM
                    );
                }


                // --------------------------------------------------
                // GCS EYE
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.EYE
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_gcs_e_tf',
                        ttv.EYE
                    );
                }


                // --------------------------------------------------
                // GCS VERBAL
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.VERBAL
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_gcs_v_tf',
                        ttv.VERBAL
                    );
                }


                // --------------------------------------------------
                // GCS MOTORIK
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.MOTORIK
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_gcs_m_tf',
                        ttv.MOTORIK
                    );
                }


                // --------------------------------------------------
                // GCS TOTAL
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.GCS
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_gcs_t_tf',
                        ttv.GCS
                    );

                }
                else {

                    hitungGCS();
                }


                // --------------------------------------------------
                // TEKANAN DARAH
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.SISTOLIK
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_td_up_tf',
                        ttv.SISTOLIK
                    );
                }


                if (
                    FormHelper.hasValueNot0(
                        ttv.DISTOLIK
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_td_down_tf',
                        ttv.DISTOLIK
                    );
                }


                // --------------------------------------------------
                // NADI
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.FREKUENSI_NADI
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_nadi_tf',
                        ttv.FREKUENSI_NADI
                    );
                }


                if (
                    FormHelper.hasValueNot0(
                        ttv.FREKUENSI_NADI_CB
                    )
                ) {

                    setSingleCheckbox(
                        'tv_nafas_nadi_tf',
                        ttv.FREKUENSI_NADI_CB
                    );
                }


                // --------------------------------------------------
                // NAFAS
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.FREKUENSI_NAFAS
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_nafas_tf',
                        ttv.FREKUENSI_NAFAS
                    );
                }


                if (
                    FormHelper.hasValueNot0(
                        ttv.FREKUENSI_NAFAS_CB
                    )
                ) {

                    setSingleCheckbox(
                        'tv_nafas_cb_tf',
                        ttv.FREKUENSI_NAFAS_CB
                    );
                }


                // --------------------------------------------------
                // SUHU
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.SUHU
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_suhu_tf',
                        ttv.SUHU
                    );
                }


                // --------------------------------------------------
                // SPO2
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.SATURASI_O2
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_spo2_tf',
                        ttv.SATURASI_O2
                    );
                }


                // --------------------------------------------------
                // BB
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.BERAT_BADAN
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_bb_tf',
                        ttv.BERAT_BADAN
                    );
                }


                // --------------------------------------------------
                // TB
                // --------------------------------------------------

                if (
                    FormHelper.hasValueNot0(
                        ttv.TINGGI_BADAN
                    )
                ) {

                    FormHelper.setValue(
                        $formTTV,
                        'tv_tb_tf',
                        ttv.TINGGI_BADAN
                    );
                }


                // --------------------------------------------------
                // IMT
                // --------------------------------------------------

                if (
                    FormHelper.hasValue(
                        ttv.BERAT_BADAN
                    ) &&
                    FormHelper.hasValue(
                        ttv.TINGGI_BADAN
                    )
                ) {

                    if (
                        FormHelper.hasValue(
                            ttv.INDEX_MASSA_TUBUH
                        )
                    ) {

                        FormHelper.setValue(
                            $formTTV,
                            'gizi_imt_tf',
                            ttv.INDEX_MASSA_TUBUH
                        );

                    }

                    hitungIMT();
                }


                // --------------------------------------------------
                // APPLY ACCESS
                // --------------------------------------------------

                applyTandaVitalAccess();
            },


            error: function (
                xhr,
                status,
                error
            ) {

                console.error(
                    'Error Tanda Vital:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Tanda Vital.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                console.warn(message);
            },


            complete: function () {
            }

        });
    }


    // ==============================================================
    // SIMPAN TANDA VITAL
    // ==============================================================

    function simpanTandaVital() {

        // ----------------------------------------------------------
        // SECURITY CHECK LEVEL JS
        // ----------------------------------------------------------

        if (page !== 'perawat') {

            if (page !== 'dokter') {
                return;
            }


            if (
                !Array.isArray(editableFields) ||
                editableFields.length === 0
            ) {

                return;
            }
        }


        // ----------------------------------------------------------
        // FORM TTV TRANSFER
        // ----------------------------------------------------------

        if (!$formTTV.length) {
            return;
        }


        // ----------------------------------------------------------
        // AMBIL DATA
        // ----------------------------------------------------------

        const data = getFormDataByName(
            $formTTV,
            {
                NOKUNJ: kunjungan,
                transfer: transfer
            }
        );


        // ----------------------------------------------------------
        // POST
        // ----------------------------------------------------------

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/tandavitaltf/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
            },

            success: function (res) {

                // iziToast.success({
                //     title: 'Pesan System!',
                //     message: res.message,
                //     position: 'topRight'
                // });

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

                    title:
                        'Validasi Gagal!',

                    message:
                        message,

                    position:
                        'topRight'

                });

            },

            complete: function () {
            }

        });
    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================

    $(function () {

        // ==========================================================
        // VALIDASI FORM
        // ==========================================================

        if (!$formTTV.length) {

            console.warn(
                'Form TTV Transfer tidak ditemukan:',
                transfer
            );

            return;
        }


        // ==========================================================
        // GET DATA
        // ==========================================================

        getTandaVital();


        // ==========================================================
        // HITUNG GCS
        // ==============================================================

        $formTTV.on(

            'input',

            '[name="tv_gcs_e_tf"], [name="tv_gcs_v_tf"], [name="tv_gcs_m_tf"]',

            function () {

                if (
                    !bolehEditTandaVital(
                        $(this).attr('name')
                    )
                ) {

                    return;
                }


                hitungGCS();

            }
        );


        // ==========================================================
        // USER MENGUBAH INPUT
        // ==============================================================

        $formTTV.on(

            'input',

            'input:not([type="checkbox"]):not([type="radio"]), textarea',

            function () {

                const name =
                    $(this).attr('name');


                // --------------------------------------------------
                // FIELD TIDAK BOLEH EDIT
                // --------------------------------------------------

                if (
                    !bolehEditTandaVital(name)
                ) {

                    return;
                }


                // --------------------------------------------------
                // MARK DIRTY
                // --------------------------------------------------

                isTandaVitalDirty = true;


                // --------------------------------------------------
                // HITUNG IMT
                // --------------------------------------------------

                if (
                    name === 'tv_bb_tf' ||
                    name === 'tv_tb_tf'
                ) {

                    hitungIMT();
                }

            }
        );


        // ==========================================================
        // USER MENINGGALKAN INPUT
        // ==============================================================

        $formTTV.on(

            'blur',

            'input:not([type="checkbox"]):not([type="radio"]), textarea, select',

            function () {

                const name =
                    $(this).attr('name');


                // --------------------------------------------------
                // FIELD TIDAK BOLEH EDIT
                // --------------------------------------------------

                if (
                    !bolehEditTandaVital(name)
                ) {

                    return;
                }


                // --------------------------------------------------
                // TIDAK ADA PERUBAHAN
                // --------------------------------------------------

                if (!isTandaVitalDirty) {

                    return;
                }


                // --------------------------------------------------
                // SIMPAN
                // --------------------------------------------------

                simpanTandaVital();


                // --------------------------------------------------
                // RESET DIRTY
                // --------------------------------------------------

                isTandaVitalDirty = false;

            }
        );


        // ==========================================================
        // CHECKBOX / RADIO
        // ==============================================================

        $formTTV.on(

            'change',

            'input[type="checkbox"], input[type="radio"]',

            function (e) {

                const name =
                    $(this).attr('name');


                // --------------------------------------------------
                // FIELD TIDAK BOLEH EDIT
                // --------------------------------------------------

                if (
                    !bolehEditTandaVital(name)
                ) {

                    return;
                }


                // --------------------------------------------------
                // HANYA USER ACTION
                // --------------------------------------------------

                if (e.originalEvent) {

                    isTandaVitalDirty = true;


                    // ------------------------------------------------
                    // SIMPAN
                    // ------------------------------------------------

                    simpanTandaVital();


                    // ------------------------------------------------
                    // RESET DIRTY
                    // ------------------------------------------------

                    isTandaVitalDirty = false;

                }

            }
        );

    });

})();
</script>
