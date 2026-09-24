<div class="col-md-12 mb-3" id="asesmen_geriatri">

    <label class="form-label fw-bold fs-5 mb-3">
        ASSESMEN SINDROM GERIATRI
    </label>


    <!-- ================================================= -->
    <!-- 1. PENAPISAN STATUS FUNGSIONAL -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            1. Penapisan Status Fungsional
        </label>


        <!-- A. Activity Daily Living -->
        <div class="ms-3 mb-3">

            <label class="form-label fw-semibold">
                a. Activity Daily Living (ADL) Barthel
            </label>
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.skrining_barthel', ['section' => '#rjg_perawat'])
        </div>


        <!-- B. Instrumental ADL -->
        <div class="ms-3 mb-3">

            <label class="form-label fw-semibold">
                b. Instrumental ADL (IADL)
            </label>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_iadl"
                            id="geriatri_iadl_1"
                            value="1">

                        <label class="form-check-label"
                            for="geriatri_iadl_1">
                            Independen (0)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_iadl"
                            id="geriatri_iadl_2"
                            value="2">

                        <label class="form-check-label"
                            for="geriatri_iadl_2">
                            Kadang-kadang perlu bantuan (1)
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_iadl"
                            id="geriatri_iadl_3"
                            value="3">

                        <label class="form-check-label"
                            for="geriatri_iadl_3">
                            Perlu bantuan sepanjang waktu (2)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_iadl"
                            id="geriatri_iadl_4"
                            value="4">

                        <label class="form-check-label"
                            for="geriatri_iadl_4">
                            Tidak beraktivitas / dikerjakan oleh orang lain (3–8)
                        </label>
                    </div>
                </div>

            </div>

        </div>


        <!-- C. Delirium -->
        <div class="ms-3">

            <label class="form-label fw-semibold">
                c. Penapisan ACS (Acute Confusional State) / Sindrom Delirium Akut
            </label>

            <div class="d-flex gap-4">

                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_acs"
                        id="geriatri_acs_ya"
                        value="1">

                    <label class="form-check-label">
                        Ya
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_acs"
                        id="geriatri_acs_tidal"
                        value="0">

                    <label class="form-check-label">
                        Tidak
                    </label>
                </div>

            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 2. PENILAIAN STATUS NUTRISI -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            2. Penilaian Status Nutrisi (MNA)
        </label>

        <div class="row ms-3">

            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_nutrisi"
                        id="geriatri_nutrisi_normal"
                        value="1">

                    <label class="form-check-label">
                        Normal
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_nutrisi"
                        id="geriatri_nutrisi_risiko"
                        value="2">

                    <label class="form-check-label">
                        Risiko malnutrisi
                    </label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_nutrisi"
                        id="geriatri_nutrisi_kemungkinan"
                        value="3">

                    <label class="form-check-label">
                        Kemungkinan malnutrisi
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_nutrisi"
                        id="geriatri_nutrisi_malnutrisi"
                        value="4">

                    <label class="form-check-label">
                        Malnutrisi (&lt; 17)
                    </label>
                </div>
            </div>
        </div>

    </div>


    <!-- ================================================= -->
    <!-- 3. PENAPISAN KOGNITIF -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            3. Penapisan Kognitif
        </label>

        <div class="ms-3">

            <label class="form-label fw-semibold">
                MMSE (Mini Mental State Examination)
            </label>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_kognitif"
                            id="geriatri_kognitif_normal"
                            value="1">

                        <label class="form-check-label">
                            Normal (24–30)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_kognitif"
                            id="geriatri_kognitif_ringan"
                            value="2">

                        <label class="form-check-label">
                            Gangguan kognitif ringan (MCI 17–23)
                        </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_kognitif"
                            id="geriatri_kognitif_berat"
                            value="3">

                        <label class="form-check-label">
                            Gangguan kognitif pasti ≤16
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary"
                            type="radio"
                            name="geriatri_kognitif"
                            id="geriatri_kognitif_belum"
                            value="4">

                        <label class="form-check-label">
                            Belum dapat dievaluasi
                        </label>
                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 4. PENAPISAN DEPRESI -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            4. Penapisan Depresi GDS (Geriatri Depresi Scale)
        </label>

        <div class="row ms-3">

            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_depresi"
                        id="geriatri_depresi_normal"
                        value="1">

                    <label class="form-check-label">
                        Normal (0–5)
                    </label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_depresi"
                        id="geriatri_depresi_risiko"
                        value="2">

                    <label class="form-check-label">
                        Risiko depresi (6–10)
                    </label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_depresi"
                        id="geriatri_depresi_depresi"
                        value="3">

                    <label class="form-check-label">
                        ≥ 10
                    </label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_depresi"
                        id="geriatri_depresi_belum"
                        value="4">

                    <label class="form-check-label">
                        Belum dapat dievaluasi
                    </label>
                </div>
            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 5. PENAPISAN INKONTINENSIA -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            5. Penapisan Inkontinensia
        </label>

        <div class="row ms-3">

            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_inkontinensia"
                        id="geriatri_inkontinensia_tidak"
                        value="0">

                    <label class="form-check-label"
                        for="geriatri_inkontinensia_tidak">
                        Tidak Inkontinensia
                    </label>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_inkontinensia"
                        id="geriatri_inkontinensia_ada"
                        value="1">

                    <label class="form-check-label"
                        for="geriatri_inkontinensia_ada">
                        Ada Inkontinensia: akut / kronik, jenis
                    </label>
                </div>
            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 6. DVT / EMBOLI PARU -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            6. Penapisan Iromboemboli Vena (DVT dan emboli paru)
            pada imobilisasi (Prediksi Klinis Wells)
        </label>

        <div class="row ms-3">

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_dvt"
                        id="geriatri_dvt_rendah"
                        value="1">

                    <label class="form-check-label"
                        for="geriatri_dvt_rendah">
                        Risiko rendah (&lt; 1)
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_dvt"
                        id="geriatri_dvt_sedang"
                        value="2">

                    <label class="form-check-label"
                        for="geriatri_dvt_sedang">
                        Risiko sedang (1–2)
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_dvt"
                        id="geriatri_dvt_tinggi"
                        value="3">

                    <label class="form-check-label"
                        for="geriatri_dvt_tinggi">
                        Risiko tinggi (&gt; 3)
                    </label>
                </div>
            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 7. RISIKO JATUH -->
    <!-- ================================================= -->

    <div class="mb-4">

        <label class="form-label fw-semibold">
            7. Penapisan Risiko Jatuh pada Imobilisasi
            (Skala Norton)
        </label>

        <div class="row ms-3">

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_ulkus"
                        id="geriatri_ulkus_rendah"
                        value="1">

                    <label class="form-check-label">
                        Risiko rendah (14)
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_ulkus"
                        id="geriatri_ulkus_sedang"
                        value="2">

                    <label class="form-check-label">
                        Risiko sedang (12–13)
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_ulkus"
                        id="geriatri_ulkus_tinggi"
                        value="3">

                    <label class="form-check-label">
                        Risiko tinggi
                    </label>
                </div>
            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- 8. INSOMNIA -->
    <!-- ================================================= -->

    <div class="mb-2">

        <label class="form-label fw-semibold">
            8. Penapisan Insomnia
        </label>

        <div class="row ms-3">

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_insomnia"
                        id="geriatri_insomnia_tidak"
                        value="0">

                    <label class="form-check-label"
                        for="geriatri_insomnia_tidak">
                        Tidak ada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_insomnia"
                        id="geriatri_insomnia_general"
                        value="1">

                    <label class="form-check-label"
                        for="geriatri_insomnia_general">
                        General insomnia
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_insomnia"
                        id="geriatri_insomnia_initial"
                        value="2">

                    <label class="form-check-label"
                        for="geriatri_insomnia_initial">
                        Initial insomnia
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_insomnia"
                        id="geriatri_insomnia_middle"
                        value="3">

                    <label class="form-check-label"
                        for="geriatri_insomnia_middle">
                        Middle insomnia
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input check-primary"
                        type="radio"
                        name="geriatri_insomnia"
                        id="geriatri_insomnia_late"
                        value="4">

                    <label class="form-check-label"
                        for="geriatri_insomnia_late">
                        Late insomnia
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#asesmen_geriatri');

    let isAsesmenGeriatriLoading = false;
    let isAsesmenGeriatriSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getAsesmenGeriatri() {

        if (!$form.length) {
            console.warn('Form Asesmen Geriatri tidak ditemukan.');
            return;
        }

        isAsesmenGeriatriLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/asesmengeriatri/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function(res) {

                const data = res.data;

                if (!data) {
                    return;
                }

                // =========================
                // IADL
                // =========================
                $form
                    .find('[name="geriatri_iadl"]')
                    .prop('checked', false);

                if (data.IADL !== null && data.IADL !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_iadl"][value="${data.IADL}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // ACS
                // =========================
                $form
                    .find('[name="geriatri_acs"]')
                    .prop('checked', false);

                if (data.ACS !== null && data.ACS !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_acs"][value="${data.ACS}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // NUTRISI
                // =========================
                $form
                    .find('[name="geriatri_nutrisi"]')
                    .prop('checked', false);

                if (data.NUTRISI !== null && data.NUTRISI !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_nutrisi"][value="${data.NUTRISI}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // KOGNITIF
                // =========================
                $form
                    .find('[name="geriatri_kognitif"]')
                    .prop('checked', false);

                if (data.KOGNITIF !== null && data.KOGNITIF !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_kognitif"][value="${data.KOGNITIF}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // DEPRESI
                // =========================
                $form
                    .find('[name="geriatri_depresi"]')
                    .prop('checked', false);

                if (data.DEPRESI !== null && data.DEPRESI !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_depresi"][value="${data.DEPRESI}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // INKONTINENSIA
                // =========================
                $form
                    .find('[name="geriatri_inkontinensia"]')
                    .prop('checked', false);

                if (
                    data.INKONTINENSIA !== null &&
                    data.INKONTINENSIA !== undefined
                ) {
                    $form
                        .find(
                            `[name="geriatri_inkontinensia"][value="${data.INKONTINENSIA}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // DVT
                // =========================
                $form
                    .find('[name="geriatri_dvt"]')
                    .prop('checked', false);

                if (data.DVT !== null && data.DVT !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_dvt"][value="${data.DVT}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // ULKUS / RISIKO JATUH
                // =========================
                $form
                    .find('[name="geriatri_ulkus"]')
                    .prop('checked', false);

                if (data.ULKUS !== null && data.ULKUS !== undefined) {
                    $form
                        .find(
                            `[name="geriatri_ulkus"][value="${data.ULKUS}"]`
                        )
                        .prop('checked', true);
                }

                // =========================
                // INSOMNIA
                // =========================
                $form
                    .find('[name="geriatri_insomnia"]')
                    .prop('checked', false);

                if (
                    data.INSOMNIA !== null &&
                    data.INSOMNIA !== undefined
                ) {
                    $form
                        .find(
                            `[name="geriatri_insomnia"][value="${data.INSOMNIA}"]`
                        )
                        .prop('checked', true);
                }
            },

            error: function(xhr, status, error) {

                console.error(
                    'Error Asesmen Geriatri:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Asesmen Geriatri.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function() {
                isAsesmenGeriatriLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanAsesmenGeriatri() {

        if (
            !$form.length ||
            isAsesmenGeriatriLoading ||
            isAsesmenGeriatriSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isAsesmenGeriatriSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/asesmengeriatri/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data Asesmen Geriatri gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isAsesmenGeriatriSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function() {

        if (!$form.length) {
            return;
        }

        getAsesmenGeriatri();

        // Input text / textarea
        $form.on(
            'blur',
            'textarea, input:not([type="radio"]):not([type="checkbox"])',
            function() {

                if (isAsesmenGeriatriLoading) {
                    return;
                }

                simpanAsesmenGeriatri();
            }
        );

        // Radio / checkbox
        $form.on(
            'change',
            'input[type="radio"], input[type="checkbox"], select',
            function() {

                if (isAsesmenGeriatriLoading) {
                    return;
                }

                simpanAsesmenGeriatri();
            }
        );
    });

})();
</script>