<div class="row" id="form_kebutuhan_edukasi">
    <div class="col-md-12">
        <div class="form-group">
            <h5 class="mb-0">
                <strong>Edukasi</strong>
            </h5>
        </div>
    </div>

    {{-- ==========================================================
        EDUKASI AWAL
    =========================================================== --}}
    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            {{-- KESEDIAAN --}}
            <label class="col-md-5 col-form-label">
                Kesediaan pasien/keluarga menerima informasi
            </label>
            <div class="col-md-7">
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input single-checkbox"
                        type="checkbox"
                        name="edu_1"
                        value="1"
                    >
                    <label class="form-check-label">
                        Ya
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input single-checkbox"
                        type="checkbox"
                        name="edu_1"
                        value="0"
                    >
                    <label class="form-check-label">
                        Tidak
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">

        <div class="row align-items-center">

            {{-- HAMBATAN --}}
            <label class="col-md-5 col-form-label">
                Terdapat hambatan dalam edukasi
            </label>

            <div class="col-md-7">

                {{-- YA --}}
                <div class="form-check form-check-inline">

                    <input
                        class="form-check-input single-checkbox"
                        type="checkbox"
                        name="edu_2"
                        value="1"
                    >

                    <label class="form-check-label">
                        Ya
                    </label>

                </div>


                {{-- TIDAK --}}
                <div class="form-check form-check-inline">

                    <input
                        class="form-check-input single-checkbox"
                        type="checkbox"
                        name="edu_2"
                        value="0"
                    >

                    <label class="form-check-label">
                        Tidak
                    </label>

                </div>

            </div>

        </div>


        {{-- ==========================================================
            DETAIL HAMBATAN
        =========================================================== --}}
        <div
            class="row d-none"
            id="detail_hambatan_edukasi"
        >

            <div class="col-md-12">

                <div class="card card-body border border-dashed border-secondary mb-0">

                    <div class="row">

                        <label class="form-label fw-bold mb-3 mt-2">
                            Jika terdapat hambatan, sebutkan
                        </label>


                        {{-- PENDENGARAN --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_1"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Pendengaran
                                </label>

                            </div>

                        </div>


                        {{-- PENGLIHATAN --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_2"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Penglihatan
                                </label>

                            </div>

                        </div>


                        {{-- KOGNITIF --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_3"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Kognitif
                                </label>

                            </div>

                        </div>


                        {{-- FISIK --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_4"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Fisik
                                </label>

                            </div>

                        </div>


                        {{-- BUDAYA --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_5"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Budaya
                                </label>

                            </div>

                        </div>


                        {{-- EMOSI --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_6"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Emosi
                                </label>

                            </div>

                        </div>


                        {{-- BAHASA --}}
                        <div class="col">

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary"
                                    type="checkbox"
                                    name="hb_edu_7"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Bahasa
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">

            <label class="col-md-5 col-form-label">
                Dibutuhkan penerjemah
            </label>

            <div class="col-md-7">

                <div class="d-flex align-items-center gap-3">

                    {{-- YA --}}
                    <div class="form-check mb-0 flex-shrink-0">
                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="edu_3"
                            value="1"
                        >
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>

                    {{-- TIDAK --}}
                    <div class="form-check mb-0 flex-shrink-0">
                        <input
                            class="form-check-input single-checkbox"
                            type="checkbox"
                            name="edu_3"
                            value="0"
                        >
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>

                    {{-- KETERANGAN --}}
                    <input
                        type="text"
                        class="form-control form-control-sm d-none"
                        name="edu_3_lain"
                        placeholder="Sebutkan penerjemah"
                        disabled
                    >

                </div>

            </div>

        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
        {{-- ======================================================
            KEBUTUHAN EDUKASI
        ======================================================= --}}
            <label class="form-label fw-bold mb-3 mt-2">
                Kebutuhan Edukasi
            </label>

            {{-- ==================================================
                KOLOM 1
            =================================================== --}}
            <div class="col">
                {{-- 1 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_1"
                        value="1"
                    >
                    <label class="form-check-label">
                        Kondisi kesehatan dan diagnosa pasti dan penatalaksanaannya
                    </label>
                </div>

                {{-- 2 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_2"
                        value="1"
                    >
                    <label class="form-check-label">
                        Teknik rehabilitasi
                    </label>
                </div>

                {{-- 3 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_3"
                        value="1"
                    >
                    <label class="form-check-label">
                        Hak dan Kewajiban Pasien
                    </label>
                </div>

                {{-- 4 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_4"
                        value="1"
                    >
                    <label class="form-check-label">
                        Proses pemberian informed consent
                    </label>
                </div>

                {{-- 5 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_5"
                        value="1"
                    >
                    <label class="form-check-label">
                        Cuci tangan dengan benar
                    </label>
                </div>

                {{-- 6 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_6"
                        value="1"
                    >
                    <label class="form-check-label">
                        Edukasi perencanaan pulang
                    </label>
                </div>
            </div>

            {{-- ==================================================
                KOLOM 2
            =================================================== --}}
            <div class="col">
                {{-- 7 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_7"
                        value="1"
                    >
                    <label class="form-check-label">
                        Penggunaan obat secara efektif dan efek samping interaksinya
                    </label>
                </div>

                {{-- 8 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_8"
                        value="1"
                    >
                    <label class="form-check-label">
                        Manajemen Nyeri
                    </label>
                </div>

                {{-- 9 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_9"
                        value="1"
                    >
                    <label class="form-check-label">
                        Hak untuk berpartisipasi pada pelayanan
                    </label>
                </div>

                {{-- 10 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_10"
                        value="1"
                    >
                    <label class="form-check-label">
                        Penundaan pelayanan
                    </label>
                </div>

                {{-- 11 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_11"
                        value="1"
                    >
                    <label class="form-check-label">
                        Bahaya merokok
                    </label>
                </div>

                {{-- 17 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_17"
                        value="1"
                    >
                    <label class="form-check-label">
                        Edukasi rujukan pasien
                    </label>
                </div>
            </div>

            {{-- ==================================================
                KOLOM 3
            =================================================== --}}
            <div class="col">
                {{-- 13 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_13"
                        value="1"
                    >
                    <label class="form-check-label">
                        Diet dan Nutrisi
                    </label>
                </div>

                {{-- 14 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_14"
                        value="1"
                    >
                    <label class="form-check-label">
                        Penggunaan alat medis yang aman
                    </label>
                </div>

                {{-- 15 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_15"
                        value="1"
                    >
                    <label class="form-check-label">
                        Prosedur pemeriksaan penunjang
                    </label>
                </div>

                {{-- 16 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_16"
                        value="1"
                    >
                    <label class="form-check-label">
                        Keterlambatan pelayanan
                    </label>
                </div>

                {{-- 12 --}}
                <div class="form-check mb-2">
                    <input
                        class="form-check-input check-primary"
                        type="checkbox"
                        name="kb_edu_12"
                        value="1"
                    >
                    <label class="form-check-label">
                        Lain-lainnya
                    </label>
                </div>

                {{-- LAIN-LAIN --}}
                <div class="form-check mb-2">
                    <input
                        type="text"
                        class="form-control"
                        name="kb_edu_lain"
                        placeholder="Sebutkan"
                        disabled
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================================
        DESKRIPSI EDUKASI
    =========================================================== --}}
    <div class="col-md-12">
        <label class="form-label">
            Emergency
        </label>
        <textarea
            class="form-control form-control-sm"
            name="kb_edu_deskripsi"
            rows="3"
            placeholder="Masukkan Edukasi / Follow Up"
        ></textarea>
    </div>
</div>

<script>
(function () {

    'use strict';

    // ==============================================================
    // CONFIG
    // ==============================================================
    const $section = $(@json($section));

    // ==============================================================
    // STATE
    // ==============================================================
    let isKebutuhanEdukasiDirty = false;

    // ==============================================================
    // HELPER
    // CEK KB EDU 12
    // ==============================================================
    function isLainLainChecked() {
        return $section
            .find('[name="kb_edu_12"]')
            .is(':checked');
    }

    // ==============================================================
    // ENABLE / DISABLE DESKRIPSI
    // ==============================================================
    function updateDeskripsiEdukasiState() {

        const isChecked = isLainLainChecked();

        const $deskripsi =
            $section.find('[name="kb_edu_lain"]');

        /*
        |--------------------------------------------------------------------------
        | Jika kb_edu_12 dicentang
        |--------------------------------------------------------------------------
        | kb_edu_lain enabled
        |
        | Jika tidak dicentang
        |--------------------------------------------------------------------------
        | kb_edu_lain disabled
        */

        $deskripsi.prop(
            'disabled',
            !isChecked
        );
    }

    function updateEdu3Lain() {

        const value = $section
            .find('[name="edu_3"]:checked')
            .val();

        const $input = $section
            .find('[name="edu_3_lain"]');

        // Hanya tampil jika edu_3 = 1
        if (value === '1') {

            $input
                .removeClass('d-none')
                .prop('disabled', false);

        } else {

            // Nilai 0 atau belum dipilih
            $input
                .addClass('d-none')
                .prop('disabled', true)
                .val('');

        }
    }

    function updateHambatanEdukasi() {

        const value = $section
            .find('[name="edu_2"]:checked')
            .val();

        const $detail = $section
            .find('#detail_hambatan_edukasi');

        // ==========================================================
        // EDU_2 = 1 → TAMPIL
        // EDU_2 = 0 → HIDDEN + HAPUS PILIHAN
        // EDU_2 BELUM DIPILIH → HIDDEN
        // ==========================================================

        if (String(value) === '1') {

            $detail.removeClass('d-none');

        } else {

            $detail.addClass('d-none');

            // Hanya hapus pilihan hambatan jika
            // user/data memang menyatakan TIDAK ADA hambatan.
            if (String(value) === '0') {

                $section
                    .find('[name^="hb_edu_"]')
                    .prop('checked', false);

            }

        }
    }

    // ==============================================================
    // GET DATA
    // ==============================================================
    function getKebutuhanEdukasi() {

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/edukasi/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            beforeSend: function () {

                // Jangan tandai dirty ketika proses GET
                isKebutuhanEdukasiDirty = false;

            },

            success: function (res) {

                const edukasi = res.data;

                if (edukasi) {

                    // ==================================================
                    // EDUKASI AWAL
                    // ==================================================

                    if (
                        FormHelper.hasValue(
                            edukasi.KESEDIAAN
                        )
                    ) {

                        FormHelper.setSingleCheckbox(
                            $section,
                            'edu_1',
                            edukasi.KESEDIAAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN
                        )
                    ) {

                        FormHelper.setSingleCheckbox(
                            $section,
                            'edu_2',
                            edukasi.HAMBATAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_PENDENGARAN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_1',
                            edukasi.HAMBATAN_PENDENGARAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_PENGLIHATAN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_2',
                            edukasi.HAMBATAN_PENGLIHATAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_KOGNITIF
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_3',
                            edukasi.HAMBATAN_KOGNITIF
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_FISIK
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_4',
                            edukasi.HAMBATAN_FISIK
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_BUDAYA
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_5',
                            edukasi.HAMBATAN_BUDAYA
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_EMOSI
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_6',
                            edukasi.HAMBATAN_EMOSI
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.HAMBATAN_BAHASA
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'hb_edu_7',
                            edukasi.HAMBATAN_BAHASA
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.PENERJEMAH
                        )
                    ) {

                        FormHelper.setSingleCheckbox(
                            $section,
                            'edu_3',
                            edukasi.PENERJEMAH
                        );

                    }

                    // Jika Anda sudah menambahkan field ini di controller/getData
                    if (
                        FormHelper.hasValue(
                            edukasi.PENERJEMAH_LAIN
                        )
                    ) {

                        FormHelper.setValue(
                            $section,
                            'edu_3_lain',
                            edukasi.PENERJEMAH_LAIN
                        );

                    }

                    // ==================================================
                    // KEBUTUHAN EDUKASI
                    // ==================================================

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_DIAGNOSA
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_1',
                            edukasi.EDUKASI_DIAGNOSA
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_REHAB_MEDIK
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_2',
                            edukasi.EDUKASI_REHAB_MEDIK
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_HKP
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_3',
                            edukasi.EDUKASI_HKP
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_PEMBERIAN_INFORMED_CONSENT
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_4',
                            edukasi.EDUKASI_PEMBERIAN_INFORMED_CONSENT
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_CUCI_TANGAN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_5',
                            edukasi.EDUKASI_CUCI_TANGAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_PERENCANAAN_PULANG
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_6',
                            edukasi.EDUKASI_PERENCANAAN_PULANG
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_OBAT
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_7',
                            edukasi.EDUKASI_OBAT
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_NYERI
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_8',
                            edukasi.EDUKASI_NYERI
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_HAK_BERPARTISIPASI
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_9',
                            edukasi.EDUKASI_HAK_BERPARTISIPASI
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_PENUNDAAN_PELAYANAN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_10',
                            edukasi.EDUKASI_PENUNDAAN_PELAYANAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_BAHAYA_MEROKO
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_11',
                            edukasi.EDUKASI_BAHAYA_MEROKO
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.STATUS_LAIN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_12',
                            edukasi.STATUS_LAIN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_NUTRISI
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_13',
                            edukasi.EDUKASI_NUTRISI
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_PENGGUNAAN_ALAT
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_14',
                            edukasi.EDUKASI_PENGGUNAAN_ALAT
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_PROSEDURE_PENUNJANG
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_15',
                            edukasi.EDUKASI_PROSEDURE_PENUNJANG
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_KELAMBATAN_PELAYANAN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_16',
                            edukasi.EDUKASI_KELAMBATAN_PELAYANAN
                        );

                    }

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI_RUJUKAN_PASIEN
                        )
                    ) {

                        FormHelper.setCheckbox(
                            $section,
                            'kb_edu_17',
                            edukasi.EDUKASI_RUJUKAN_PASIEN
                        );

                    }

                    // ==================================================
                    // LAIN-LAIN
                    // ==================================================

                    if (
                        FormHelper.hasValue(
                            edukasi.DESKRIPSI_LAINYA
                        )
                    ) {

                        FormHelper.setValue(
                            $section,
                            'kb_edu_lain',
                            edukasi.DESKRIPSI_LAINYA
                        );

                    }

                    // ==================================================
                    // DESKRIPSI EDUKASI
                    // ==================================================

                    if (
                        FormHelper.hasValue(
                            edukasi.EDUKASI
                        )
                    ) {

                        FormHelper.setValue(
                            $section,
                            'kb_edu_deskripsi',
                            edukasi.EDUKASI
                        );

                    }

                }

                // ==================================================
                // UPDATE STATUS DESKRIPSI
                // ==================================================

                updateDeskripsiEdukasiState();

                // ==================================================
                // RESET DIRTY
                // ==================================================

                updateEdu3Lain();

                updateHambatanEdukasi();

                isKebutuhanEdukasiDirty = false;

            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Kebutuhan Edukasi:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Kebutuhan Edukasi.';

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
    // SIMPAN DATA
    // ==============================================================
    function simpanKebutuhanEdukasi() {

        const $form =
            $section.find('#form_kebutuhan_edukasi');

        // ==========================================================
        // AMBIL DATA FORM
        // ==========================================================

        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );

        // ==========================================================
        // AJAX SAVE
        // ==========================================================

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/edukasi/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
            },

            success: function (res) {
                // Tidak perlu toast success setiap autosave.
                //
                // Jika nanti ingin diberikan indikator tersimpan,
                // bisa ditambahkan di sini.
            },

            error: function (xhr) {

                let message =
                    'Data Kebutuhan Edukasi gagal disimpan.';

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

    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {

        // ==========================================================
        // GET DATA
        // ==========================================================

        getKebutuhanEdukasi();

        updateEdu3Lain();

        updateHambatanEdukasi();

        // ==========================================================
        // FORM
        // ==========================================================

        const $form =
            $section.find('#form_kebutuhan_edukasi');

        // ==========================================================
        // CHECKBOX / RADIO
        //
        // PENTING:
        // HANYA ADA SATU HANDLER CHANGE
        // ==========================================================

        $form.off(
            'change.kebutuhanEdukasi',
            'input[type="checkbox"], input[type="radio"]'
        );

        $form.on(
            'change.kebutuhanEdukasi',
            'input[type="checkbox"], input[type="radio"]',
            function (e) {

                // ======================================================
                // EDU 2 - HAMBATAN
                // ======================================================

                if (
                    $(this).attr('name') === 'edu_2'
                ) {

                    updateHambatanEdukasi();

                }

                // ======================================================
                // EDU 3 - PENERJEMAH
                // ======================================================

                if (
                    $(this).attr('name') === 'edu_3'
                ) {

                    updateEdu3Lain();

                }

                // ======================================================
                // EDU 12 - LAIN-LAIN
                // ======================================================

                if (
                    $(this).attr('name') === 'kb_edu_12'
                ) {

                    updateDeskripsiEdukasiState();

                }

                // ======================================================
                // HANYA USER ACTION
                // ======================================================

                if (!e.originalEvent) {

                    return;

                }

                // ======================================================
                // CHECKBOX / RADIO LANGSUNG SIMPAN
                // ======================================================

                isKebutuhanEdukasiDirty = true;

                simpanKebutuhanEdukasi();

                isKebutuhanEdukasiDirty = false;

            }
        );

        // ==========================================================
        // INPUT TEXT / TEXTAREA
        // ==========================================================

        $form.off(
            'input.kebutuhanEdukasi',
            'input:not([type="checkbox"]):not([type="radio"]), textarea'
        );

        $form.on(
            'input.kebutuhanEdukasi',
            'input:not([type="checkbox"]):not([type="radio"]), textarea',
            function () {

                isKebutuhanEdukasiDirty = true;

            }
        );

        // ==========================================================
        // BLUR INPUT
        // ==========================================================

        $form.off(
            'blur.kebutuhanEdukasi',
            'input:not([type="checkbox"]):not([type="radio"]), textarea, select'
        );

        $form.on(
            'blur.kebutuhanEdukasi',
            'input:not([type="checkbox"]):not([type="radio"]), textarea, select',
            function () {

                if (
                    !isKebutuhanEdukasiDirty
                ) {

                    return;

                }

                // ------------------------------------------------
                // SIMPAN
                // ------------------------------------------------

                simpanKebutuhanEdukasi();

                // ------------------------------------------------
                // RESET DIRTY
                // ------------------------------------------------

                isKebutuhanEdukasiDirty = false;

            }
        );

    });

})();
</script>
