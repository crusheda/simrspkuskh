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
@endphp

<div class="form-group" id="form_tanda_vital">
    <h4 class="text-danger">Tanda Vital</h4>
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
                        name="tv_keu"
                        rows="1"
                    ></textarea>
                </div>
            </div>

            <div class="form-group mb-3 d-none" data-kesadaran-neonatus>
                <label class="form-label">
                    Kesadaran Neonatus
                </label>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Pilih</th>
                                <th style="width: 80px;">Kode</th>
                                <th>Mata</th>
                                <th>Menangis</th>
                                <th>Gerak</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- S1 --}}
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input single-checkbox" name="kesadaran_neonatus" value="1" id="kesadaran_neonatus">
                                </td>
                                <td>
                                    <strong>S1</strong>
                                </td>
                                <td>Tertutup</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            {{-- S2 --}}
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input single-checkbox" name="kesadaran_neonatus" value="2" id="kesadaran_neonatus">
                                </td>
                                <td>
                                    <strong>S2</strong>
                                </td>
                                <td>Tertutup</td>
                                <td>-</td>
                                <td>+</td>
                            </tr>

                            {{-- S3 --}}
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input single-checkbox" name="kesadaran_neonatus" value="3" id="kesadaran_neonatus">
                                </td>
                                <td>
                                    <strong>S3</strong>
                                </td>
                                <td>Terbuka</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            {{-- S4 --}}
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input single-checkbox" name="kesadaran_neonatus" value="4" id="kesadaran_neonatus">
                                </td>
                                <td>
                                    <strong>S4</strong>
                                </td>
                                <td>Terbuka</td>
                                <td>-</td>
                                <td>+</td>
                            </tr>

                            {{-- S5 --}}
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input single-checkbox" name="kesadaran_neonatus" value="5" id="kesadaran_neonatus">
                                </td>
                                <td>
                                    <strong>S5</strong>
                                </td>
                                <td>Terbuka</td>
                                <td>+</td>
                                <td>+</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- GCS --}}
            <div class="form-group mb-3" data-ttv-neonatus>
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
                            name="tv_gcs_e"
                            min="1"
                            max="4"
                            style="width: 70px; flex: 0 0 60px;"
                            placeholder=""
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
                            name="tv_gcs_v"
                            min="1"
                            max="5"
                            style="width: 70px; flex: 0 0 60px;"
                            placeholder=""
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
                            name="tv_gcs_m"
                            min="1"
                            max="6"
                            style="width: 70px; flex: 0 0 60px;"
                            placeholder=""
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
                            name="tv_gcs_t"
                            style="width: 70px; flex: 0 0 60px;"
                            placeholder=""
                            readonly
                        >
                    </div>
                </div>
            </div>

            {{-- TEKANAN DARAH --}}
            <div class="form-group mb-3" data-ttv-neonatus>
                <label class="form-label">
                    Tekanan Darah
                </label>
                <div class="input-group">
                    <input
                        type="number"
                        class="form-control"
                        name="tv_td_up"
                    >
                    <div class="input-group-text">
                        /
                    </div>
                    <input
                        type="number"
                        class="form-control"
                        name="tv_td_down"
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
                    <div class="input-group flex-grow-1" style="flex: 1 1 100px; min-width: 50px;">
                        <input
                            type="number"
                            class="form-control"
                            name="tv_nadi"
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
                            name="tv_nadi_cb"
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
                            name="tv_nadi_cb"
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

                    {{-- INPUT FREKUENSI --}}
                    <div class="input-group" style="flex: 1 1 100px; min-width: 50px;">
                        <input
                            type="number"
                            class="form-control"
                            name="tv_nafas"
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
                            name="tv_nafas_cb"
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
                            name="tv_nafas_cb"
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
                            <input type="number" class="form-control" name="tv_suhu">
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
                            <input type="number" class="form-control" name="tv_spo2">
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
                            <input type="number" class="form-control" name="tv_bb"
                                step="0.1"
                                min="0">
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
                            <input type="number" class="form-control" name="tv_tb"
                                step="0.1"
                                min="0">
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
                    name="gizi_imt"
                    hidden
                >
                <div
                    id="hasil_imt"
                    class="alert alert-danger d-flex align-items-center d-none"
                    role="alert"
                >
                    <i class="ri-spam-line me-2"></i>
                    <span id="hasil_imt_text"></span>
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

    const $form = $section.find('[data-ttv-form]');

    function updateTtvNeonatusState() {
        const $neonatus = $section.find('[data-ttv-neonatus]');
        const $kesadaranNeonatus = $section.find('[data-kesadaran-neonatus]');

        if (!$neonatus.length || !$kesadaranNeonatus.length) {
            return;
        }

        // ==========================================================
        // NORMALISASI NILAI NEONATUS
        // ==========================================================
        const neonatusValue = String(@json($neonatus ?? false))
            .toLowerCase()
            .trim();

        const isNeonatus =
            neonatusValue === 'true' ||
            neonatusValue === '1' ||
            neonatusValue === 'yes' ||
            neonatusValue === 'ya';

        // ==========================================================
        // NEONATUS
        // ==========================================================
        if (isNeonatus) {

            // ------------------------------------------------------
            // Sembunyikan TTV biasa
            // ------------------------------------------------------
            $neonatus
                .stop(true, true)
                .hide()
                .find('input, textarea, select')
                .prop('disabled', true);

            // ------------------------------------------------------
            // Tampilkan Kesadaran Neonatus
            // ------------------------------------------------------
            $kesadaranNeonatus
                .stop(true, true)
                .removeClass('d-none')
                .show();

            // ------------------------------------------------------
            // Hak akses kesadaran neonatus
            // ------------------------------------------------------
            $kesadaranNeonatus
                .find('input, textarea, select')
                .each(function () {

                    const $input = $(this);
                    const name = $input.attr('name');

                    if (!name) {
                        return;
                    }

                    // Dokter hanya boleh edit jika ada di editableFields
                    if (bolehEditTandaVital(name)) {
                        $input.prop('disabled', false);
                    } else {
                        $input.prop('disabled', true);
                    }
                });

        } else {

            // ======================================================
            // BUKAN NEONATUS
            // ======================================================

            $neonatus
                .stop(true, true)
                .show();

            // Hak akses TTV biasa diatur oleh applyTandaVitalAccess()
            $neonatus
                .find('input, textarea, select')
                .each(function () {

                    const $input = $(this);
                    const name = $input.attr('name');

                    if (!name) {
                        return;
                    }

                    if (bolehEditTandaVital(name)) {
                        $input.prop('disabled', false);
                    } else {
                        $input.prop('disabled', true);
                    }
                });

            // Sembunyikan kesadaran neonatus
            $kesadaranNeonatus
                .stop(true, true)
                .addClass('d-none')
                .hide()
                .find('input, textarea, select')
                .prop('disabled', true);
        }
    }

    // ==============================================================
    // STATE
    // ==============================================================
    let isTandaVitalDirty = false;

    // ==============================================================
    // HELPER:
    // CEK APAKAH FIELD BOLEH DIEDIT
    // ==============================================================
    function bolehEditTandaVital(name) {
        if (!name) {
            return false;
        }

        // ----------------------------------------------------------
        // PERAWAT
        // ----------------------------------------------------------
        //
        // Perawat boleh mengedit semua field.
        //
        if (page === 'perawat') {
            return true;
        }

        // ----------------------------------------------------------
        // DOKTER
        // ----------------------------------------------------------
        //
        // Dokter hanya boleh mengedit field yang ada di
        // editableFields.
        //
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
        const $form = $section.find('#form_tanda_vital');

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
                //
                // GCS Total dan IMT tidak boleh diinput manual.
                //
                if (
                    name === 'tv_gcs_t' ||
                    name === 'gizi_imt'
                ) {
                    $input.prop('readonly', true);
                    return;
                }

                // --------------------------------------------------
                // FIELD BOLEH EDIT
                // --------------------------------------------------
                if (bolehEditTandaVital(name)) {
                    // Checkbox / Radio
                    if (
                        $input.is(':checkbox') ||
                        $input.is(':radio')
                    ) {
                        $input.prop('disabled', false);
                    }

                    // Select
                    else if ($input.is('select')) {
                        $input.prop('disabled', false);
                    }

                    // Input / Textarea
                    else {
                        $input.prop('readonly', false);
                    }
                    return;
                }

                // --------------------------------------------------
                // FIELD READONLY
                // --------------------------------------------------
                // Checkbox / Radio
                if (
                    $input.is(':checkbox') ||
                    $input.is(':radio')
                ) {
                    $input.prop('disabled', true);
                }

                // Select
                else if ($input.is('select')) {
                    $input.prop('disabled', true);
                }

                // Input / Textarea
                else {
                    $input.prop('readonly', true);
                }
            });
    }

    // ==============================================================
    // HITUNG GCS
    // ==============================================================
    function hitungGCS() {
        /*
        |--------------------------------------------------------------------------
        | Gunakan FormHelper yang sudah ada di project
        |--------------------------------------------------------------------------
        */
        FormHelper.hitungGCS(
            $section,
            'tv_gcs'
        );
    }

    // ==============================================================
    // HITUNG IMT
    // ==============================================================
    function hitungIMT() {
        // ----------------------------------------------------------
        // AMBIL BB DAN TB
        // ----------------------------------------------------------
        const bb = parseFloat(
            $section
                .find('[name="tv_bb"]')
                .val()
        );
        const tbCm = parseFloat(
            $section
                .find('[name="tv_tb"]')
                .val()
        );
        const $hasil = $section.find('#hasil_imt');
        const $text = $section.find('#hasil_imt_text');

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
            $section
                .find('[name="gizi_imt"]')
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
        //
        // IMT = BB / (TB dalam meter ^ 2)
        //
        const imt = bb / (tbMeter * tbMeter);

        // ----------------------------------------------------------
        // SIMPAN NILAI IMT
        // ----------------------------------------------------------
        $section
            .find('[name="gizi_imt"]')
            .val(imt);

        // ----------------------------------------------------------
        // KLASIFIKASI
        // ----------------------------------------------------------
        let kategori = '';
        let alertClass = '';
        let icon = '';

        if (imt < 18.5) {
            kategori = 'Berat Badan Kurang (Underweight)';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';
        }
        else if (
            imt >= 18.5 &&
            imt <= 22.9
        ) {
            kategori = 'Berat Badan Normal';
            alertClass = 'alert-success';
            icon = 'ri-checkbox-circle-line';
        }
        else if (
            imt >= 23 &&
            imt <= 24.9
        ) {
            kategori =
                'Kelebihan Berat Badan (Overweight) dengan Risiko';
            alertClass = 'alert-warning';
            icon = 'ri-alert-line';
        }
        else if (
            imt >= 25 &&
            imt <= 29.9
        ) {
            kategori = 'Obesitas I';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';
        }
        else {
            kategori = 'Obesitas II';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';
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
    // GET DATA TANDA VITAL
    // ==============================================================
    function getTandaVital() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/tandavital/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (res) {

                const ttv = res.data;

                if (!ttv) {
                    updateTtvNeonatusState();
                    return;
                }

                if (ttv) {
                    // --------------------------------------------------
                    // KEADAAN UMUM
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValue(ttv.KEADAAN_UMUM)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_keu',
                            ttv.KEADAAN_UMUM
                        );
                    }

                    // --------------------------------------------------
                    // GCS
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValue(ttv.EYE)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_gcs_e',
                            ttv.EYE
                        );
                    }

                    if (
                        FormHelper.hasValue(ttv.VERBAL)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_gcs_v',
                            ttv.VERBAL
                        );
                    }

                    if (
                        FormHelper.hasValue(ttv.MOTORIK)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_gcs_m',
                            ttv.MOTORIK
                        );
                    }

                    if (
                        FormHelper.hasValue(ttv.GCS)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_gcs_t',
                            ttv.GCS
                        );
                    }

                    // --------------------------------------------------
                    // TEKANAN DARAH
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.SISTOLIK)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_td_up',
                            ttv.SISTOLIK
                        );
                    }

                    if (
                        FormHelper.hasValueNot0(ttv.DISTOLIK)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_td_down',
                            ttv.DISTOLIK
                        );
                    }

                    // --------------------------------------------------
                    // NADI
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.FREKUENSI_NADI)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_nadi',
                            ttv.FREKUENSI_NADI
                        );
                    }

                    if (FormHelper.hasValueNot0(ttv.FREKUENSI_NADI_CB)) {
                        setSingleCheckbox(
                            'tv_nadi_cb',
                            ttv.FREKUENSI_NADI_CB
                        );
                    }

                    // --------------------------------------------------
                    // NAFAS
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.FREKUENSI_NAFAS)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_nafas',
                            ttv.FREKUENSI_NAFAS
                        );
                    }

                    if (FormHelper.hasValueNot0(ttv.FREKUENSI_NAFAS_CB)) {
                        setSingleCheckbox(
                            'tv_nafas_cb',
                            ttv.FREKUENSI_NAFAS_CB
                        );
                    }

                    // --------------------------------------------------
                    // SUHU
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.SUHU)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_suhu',
                            ttv.SUHU
                        );
                    }

                    // --------------------------------------------------
                    // SPO2
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.SATURASI_O2)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_spo2',
                            ttv.SATURASI_O2
                        );
                    }

                    // --------------------------------------------------
                    // BB
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.BERAT_BADAN)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_bb',
                            ttv.BERAT_BADAN
                        );
                    }

                    // --------------------------------------------------
                    // TB
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValueNot0(ttv.TINGGI_BADAN)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'tv_tb',
                            ttv.TINGGI_BADAN
                        );
                    }

                    // --------------------------------------------------
                    // IMT
                    // --------------------------------------------------
                    if (
                        FormHelper.hasValue(ttv.BERAT_BADAN) &&
                        FormHelper.hasValue(ttv.TINGGI_BADAN)
                    ) {
                        FormHelper.setValue(
                            $section,
                            'gizi_imt',
                            ttv.INDEX_MASSA_TUBUH
                        );
                        hitungIMT();
                    }

                    if (FormHelper.hasValue(ttv.KESADARAN_NEONATUS)) {
                        setSingleCheckbox(
                            'kesadaran_neonatus',
                            ttv.KESADARAN_NEONATUS
                        );
                    }
                }
                updateTtvNeonatusState();
                // ------------------------------------------------------
                // APPLY ACCESS SETELAH DATA SELESAI DIMASUKKAN
                // ------------------------------------------------------
                applyTandaVitalAccess();
            },
            error: function (xhr, status, error) {
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
                updateTtvNeonatusState();
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
        //
        // Dokter hanya boleh menyimpan apabila ada field editable.
        //
        if (page !== 'perawat') {
            if (page !== 'dokter') {
                return;
            }

            // Jika dokter tidak mempunyai field editable,
            // jangan melakukan request sama sekali.
            if (
                !Array.isArray(editableFields) ||
                editableFields.length === 0
            ) {
                return;
            }
        }

        updateTtvNeonatusState();

        // ----------------------------------------------------------
        // AMBIL FORM
        // ----------------------------------------------------------
        const $sectionTandaVital =
            $section.find('#form_tanda_vital');

        // ----------------------------------------------------------
        // AMBIL DATA
        // ----------------------------------------------------------
        const data = getFormDataByName(
            $sectionTandaVital,
            {
                NOKUNJ: kunjungan
            }
        );

        // ----------------------------------------------------------
        // POST
        // ----------------------------------------------------------
        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/tandavital/${kunjungan}/simpan`,
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
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function () {
            }
        });
    }

    function setSingleCheckbox(name, value) {

        const normalizedValue = String(value).trim();

        $section
            .find(`[name="${name}"]`)
            .each(function () {

                const $checkbox = $(this);

                $checkbox.prop(
                    'checked',
                    String($checkbox.val()).trim() === normalizedValue
                );
            });
    }

    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {
        // ==========================================================
        // GET DATA
        // ==========================================================
        getTandaVital();

        updateTtvNeonatusState();

        // ==========================================================
        // HITUNG GCS
        // ==========================================================
        $section
            .find('#form_tanda_vital')
            .on(
                'input',
                '[name="tv_gcs_e"], [name="tv_gcs_v"], [name="tv_gcs_m"]',
                function () {
                    // Hanya proses jika field ini editable
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
        // ==========================================================
        $section
            .find('#form_tanda_vital')
            .on(
                'input',
                'input:not([type="checkbox"]):not([type="radio"]), textarea',
                function () {
                    const name = $(this).attr('name');
                    // ------------------------------------------------
                    // FIELD TIDAK BOLEH EDIT
                    // ------------------------------------------------
                    if (
                        !bolehEditTandaVital(name)
                    ) {
                        return;
                    }

                    // ------------------------------------------------
                    // MARK DIRTY
                    // ------------------------------------------------
                    isTandaVitalDirty = true;

                    // ------------------------------------------------
                    // HITUNG IMT
                    // ------------------------------------------------
                    if (
                        name === 'tv_bb' ||
                        name === 'tv_tb'
                    ) {
                        hitungIMT();
                    }
                }
            );

        // ==========================================================
        // USER MENINGGALKAN INPUT
        // ==========================================================
        $section
            .find('#form_tanda_vital')
            .on(
                'blur',
                'input:not([type="checkbox"]):not([type="radio"]), textarea, select',
                function () {
                    const name =$(this).attr('name');
                    // ------------------------------------------------
                    // FIELD TIDAK BOLEH EDIT
                    // ------------------------------------------------
                    if (
                        !bolehEditTandaVital(name)
                    ) {
                        return;
                    }

                    // ------------------------------------------------
                    // TIDAK ADA PERUBAHAN
                    // ------------------------------------------------
                    if (!isTandaVitalDirty) {
                        return;
                    }

                    // ------------------------------------------------
                    // SIMPAN
                    // ------------------------------------------------
                    simpanTandaVital();

                    // ------------------------------------------------
                    // RESET DIRTY
                    // ------------------------------------------------
                    isTandaVitalDirty = false;
                }
            );

        // ==========================================================
        // CHECKBOX / RADIO
        // ==========================================================
        $section
            .find('#form_tanda_vital')
            .on(
                'change',
                'input[type="checkbox"], input[type="radio"]',
                function (e) {
                    const name = $(this).attr('name');
                    // ------------------------------------------------
                    // FIELD TIDAK BOLEH EDIT
                    // ------------------------------------------------
                    if (
                        !bolehEditTandaVital(name)
                    ) {
                        return;
                    }

                    // ------------------------------------------------
                    // HANYA USER ACTION
                    // ------------------------------------------------
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
