@php
    $instance = ltrim($section, '#'); // riD_dokter atau riD_perawat
@endphp

<div class="row align-items-center" data-anamnesis-form>
    <div class="col-md-12">
        <h4 class="text-danger">Anamnesis</h4>

        <div class="row">
            {{-- ==========================================================
                ANAMNESIS DIPEROLEH
            =========================================================== --}}
            <div class="col-md-12">
                <h6>Anamnesis Diperoleh</h6>

                <div class="form-group mb-3">
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input check-primary single-checkbox"
                            type="checkbox"
                            name="anam"
                            value="1"
                            id="{{ $instance }}_anam_1"
                        >
                        <label class="form-check-label" for="{{ $instance }}_anam_1">
                            Autoanamnesis
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input check-primary single-checkbox"
                            type="checkbox"
                            name="anam"
                            value="2"
                            id="{{ $instance }}_anam_2"
                        >
                        <label class="form-check-label" for="{{ $instance }}_anam_2">
                            Alloanamnesis
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6>Keluhan Utama</h6>
                    <textarea class="form-control" name="ku" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6>Riwayat Penyakit Sekarang</h6>
                    <textarea class="form-control" name="rps" rows="3"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <h6>Riwayat Penyakit Dahulu</h6>
                    <textarea class="form-control" name="rpd" rows="2"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <h6>Riwayat Penyakit Keluarga</h6>

                    <div class="form-check form-check-inline mb-2">
                        <input
                            class="form-check-input check-primary"
                            type="checkbox"
                            name="rpk_h"
                            value="1"
                            id="{{ $instance }}_rpk_h"
                        >
                        <label class="form-check-label" for="{{ $instance }}_rpk_h">
                            Hipertensi
                        </label>
                    </div>

                    <div class="form-check form-check-inline mb-2">
                        <input
                            class="form-check-input check-primary"
                            type="checkbox"
                            name="rpk_d"
                            value="1"
                            id="{{ $instance }}_rpk_d"
                        >
                        <label class="form-check-label" for="{{ $instance }}_rpk_d">
                            Diabetes Melitus
                        </label>
                    </div>

                    <div class="form-check form-check-inline mb-2">
                        <input
                            class="form-check-input check-primary"
                            type="checkbox"
                            name="rpk_p"
                            value="1"
                            id="{{ $instance }}_rpk_p"
                        >
                        <label class="form-check-label" for="{{ $instance }}_rpk_p">
                            Penyakit Jantung
                        </label>
                    </div>

                    <div class="form-check form-check-inline mb-2">
                        <input
                            class="form-check-input check-primary"
                            type="checkbox"
                            name="rpk_a"
                            value="1"
                            id="{{ $instance }}_rpk_a"
                        >
                        <label class="form-check-label" for="{{ $instance }}_rpk_a">
                            Asma
                        </label>
                    </div>

                    <textarea
                        class="form-control"
                        name="rpk_lain"
                        rows="1"
                        placeholder="Lainnya..."
                    ></textarea>
                </div>
            </div>

            {{-- ==========================================================
                RIWAYAT ALERGI
            =========================================================== --}}
            <div class="col-md-6 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
            </div>

            {{-- ==========================================================
                RIWAYAT PENGGUNAAN OBAT
            =========================================================== --}}
            <div class="col-md-6 mb-2">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
            </div>

            {{-- ==========================================================
                ANAMNESIS ANAK
            =========================================================== --}}
            <div class="col-md-12" data-anamnesis-anak>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label class="form-label">Riwayat Tumbuh Kembang</label>
                            <textarea class="form-control" name="anam_rtk" rows="1"></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Riwayat Kelahiran</label>
                            <textarea class="form-control" name="anam_k" rows="1"></textarea>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="form-label">Usia Kehamilan</label>

                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control"
                                    name="anam_uk"
                                    min="1"
                                    max="1000"
                                >
                                <span class="input-group-text">Minggu</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="form-group mb-2">
                            <label class="form-label mb-2">Persalinan</label>

                            <div class="d-flex flex-wrap gap-4 align-items-center">
                                <div class="form-check">
                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="anam_p"
                                        value="1"
                                        id="{{ $instance }}_anam_p_1"
                                    >
                                    <label class="form-check-label" for="{{ $instance }}_anam_p_1">
                                        Sectio Caesarea
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="anam_p"
                                        value="2"
                                        id="{{ $instance }}_anam_p_2"
                                    >
                                    <label class="form-check-label" for="{{ $instance }}_anam_p_2">
                                        Spontan
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="anam_p"
                                        value="3"
                                        id="{{ $instance }}_anam_p_3"
                                    >
                                    <label class="form-check-label" for="{{ $instance }}_anam_p_3">
                                        Vacum
                                    </label>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input check-primary single-checkbox"
                                            type="checkbox"
                                            name="anam_p"
                                            value="4"
                                            id="{{ $instance }}_anam_p_4"
                                        >
                                        <label class="form-check-label" for="{{ $instance }}_anam_p_4">
                                            Lainnya
                                        </label>
                                    </div>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="anam_p_lain"
                                        placeholder="Tuliskan"
                                        disabled
                                    >
                                </div>
                            </div>
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

    const $section = $(@json($section));
    const $form = $section.find('[data-anamnesis-form]');

    const isAnamnesisAnak =
        String(@json($anak ?? 'false')).toLowerCase() === 'true';

    let isAnamnesisRILoading = false;
    let isAnamnesisRISaving = false;

    // ==============================================================
    // UPDATE STATE ANAMNESIS ANAK
    // ==============================================================
    function updateAnamnesisAnakState() {
        const $anak = $form.find('[data-anamnesis-anak]');

        if (!$anak.length) {
            return;
        }

        if (!isAnamnesisAnak) {
            $anak
                .hide()
                .find('input, textarea, select')
                .prop('disabled', true);

            return;
        }

        $anak
            .show()
            .find('input, textarea, select')
            .prop('disabled', false);

        updatePersalinanState();
    }

    // ==============================================================
    // UPDATE STATE PERSALINAN
    // ==============================================================
    function updatePersalinanState() {
        const $persalinanLain = $form.find(
            'input[name="anam_p_lain"]'
        );

        if (!$persalinanLain.length) {
            return;
        }

        const selectedValue = $form
            .find('input[name="anam_p"]:checked')
            .val();

        if (String(selectedValue) === '4') {
            $persalinanLain.prop('disabled', false);
            return;
        }

        $persalinanLain
            .val('')
            .prop('disabled', true);
    }

    // ==============================================================
    // SET CHECKBOX BOOLEAN
    // ==============================================================
    function setCheckboxValue(name, value) {
        const $checkbox = $form.find(
            `input[name="${name}"][type="checkbox"]`
        );

        if (!$checkbox.length) {
            return;
        }

        const checked =
            value === true ||
            value === 1 ||
            String(value) === '1' ||
            String(value).toLowerCase() === 'true';

        $checkbox.prop('checked', checked);
    }

    // ==============================================================
    // GET DATA
    // ==============================================================
    function getAnamnesisRI() {
        if (!$form.length) {
            console.warn('Form Anamnesis tidak ditemukan.');
            return;
        }

        isAnamnesisRILoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/anam_prwt/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {
                const anam = res.data;

                if (!anam) {
                    updateAnamnesisAnakState();
                    return;
                }

                const anam1 = anam.anam1;
                const anam2 = anam.anam2;

                // ==================================================
                // ANAMNESIS DIPEROLEH
                // ==================================================
                if (anam1) {
                    let anamnesisDiperoleh = null;

                    if (String(anam1.AUTOANAMNESIS) === '1') {
                        anamnesisDiperoleh = '1';
                    } else if (String(anam1.ALLOANAMNESIS) === '1') {
                        anamnesisDiperoleh = '2';
                    }

                    if (anamnesisDiperoleh !== null) {
                        const $anamCheckboxes = $form.find(
                            'input[type="checkbox"][name="anam"]'
                        );

                        // Hanya reset checkbox pada form dokter/perawat ini saja.
                        $anamCheckboxes.prop('checked', false);

                        // Centang sesuai value hanya pada form ini.
                        $anamCheckboxes
                            .filter(`[value="${anamnesisDiperoleh}"]`)
                            .prop('checked', true);
                    }
                }

                // ==================================================
                // KELUHAN UTAMA
                // ==================================================
                if (
                    anam1 &&
                    FormHelper.hasValue(anam1.KELUHAN_UTAMA)
                ) {
                    FormHelper.setValue(
                        $section,
                        'ku',
                        anam1.KELUHAN_UTAMA
                    );
                }

                // ==================================================
                // RIWAYAT PENYAKIT SEKARANG
                // ==================================================
                if (
                    anam1 &&
                    FormHelper.hasValue(anam1.RPS)
                ) {
                    FormHelper.setValue(
                        $section,
                        'rps',
                        anam1.RPS
                    );
                }

                // ==================================================
                // RIWAYAT PENYAKIT DAHULU
                // ==================================================
                if (
                    anam1 &&
                    FormHelper.hasValue(anam1.RPD)
                ) {
                    FormHelper.setValue(
                        $section,
                        'rpd',
                        anam1.RPD
                    );
                }

                // ==================================================
                // RIWAYAT PENYAKIT KELUARGA
                // ==================================================
                if (anam1) {
                    setCheckboxValue(
                        'rpk_h',
                        anam1.HIPERTENSI
                    );

                    setCheckboxValue(
                        'rpk_d',
                        anam1.DIABETES_MELITUS
                    );

                    setCheckboxValue(
                        'rpk_p',
                        anam1.PENYAKIT_JANTUNG
                    );

                    setCheckboxValue(
                        'rpk_a',
                        anam1.ASMA
                    );

                    if (FormHelper.hasValue(anam1.LAINNYA)) {
                        FormHelper.setValue(
                            $section,
                            'rpk_lain',
                            anam1.LAINNYA
                        );
                    }
                }

                // ==================================================
                // DATA ANAK
                // ==================================================
                if (isAnamnesisAnak && anam2) {
                    if (
                        FormHelper.hasValue(
                            anam2.RIWAYAT_TUMBUH_KEMBANG
                        )
                    ) {
                        FormHelper.setValue(
                            $section,
                            'anam_rtk',
                            anam2.RIWAYAT_TUMBUH_KEMBANG
                        );
                    }

                    if (
                        FormHelper.hasValue(
                            anam2.RIWAYAT_KELAHIRAN
                        )
                    ) {
                        FormHelper.setValue(
                            $section,
                            'anam_k',
                            anam2.RIWAYAT_KELAHIRAN
                        );
                    }

                    if (
                        FormHelper.hasValue(
                            anam2.USIA_KEHAMILAN
                        )
                    ) {
                        FormHelper.setValue(
                            $section,
                            'anam_uk',
                            anam2.USIA_KEHAMILAN
                        );
                    }

                    FormHelper.setSingleCheckbox(
                        $section,
                        'anam_p',
                        anam2.PERSALINAN
                    );

                    if (
                        FormHelper.hasValue(
                            anam2.PERSALINAN_LAINNYA
                        )
                    ) {
                        FormHelper.setValue(
                            $section,
                            'anam_p_lain',
                            anam2.PERSALINAN_LAINNYA
                        );
                    }
                }

                updateAnamnesisAnakState();
                updatePersalinanState();
            },

            error: function (xhr, status, error) {
                console.error(
                    'Error Anamnesis:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Anamnesis.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isAnamnesisRILoading = false;

                updateAnamnesisAnakState();
                updatePersalinanState();
            }
        });
    }

    // ==============================================================
    // SIMPAN DATA
    // ==============================================================
    function simpanAnamnesisRI() {
        if (
            !$form.length ||
            isAnamnesisRILoading ||
            isAnamnesisRISaving
        ) {
            return;
        }

        updateAnamnesisAnakState();
        updatePersalinanState();

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isAnamnesisRISaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/anam_prwt/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {
                console.log(
                    'Anamnesis berhasil disimpan.'
                );
            },

            error: function (xhr) {
                let message =
                    'Data Anamnesis gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isAnamnesisRISaving = false;
            }
        });
    }

    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {
        if (!$form.length) {
            return;
        }

        updateAnamnesisAnakState();
        updatePersalinanState();

        getAnamnesisRI();

        const formSelector = '[data-anamnesis-form]';

        // ANAMNESIS DIPEROLEH
        $section
            .off('change.anam', `${formSelector} input[name="anam"]`)
            .on('change.anam', `${formSelector} input[name="anam"]`, function () {
                if (isAnamnesisRILoading) {
                    return;
                }

                simpanAnamnesisRI();
            });

        // RIWAYAT PENYAKIT KELUARGA
        const rpkSelector =
            `${formSelector} input[name="rpk_h"], ` +
            `${formSelector} input[name="rpk_d"], ` +
            `${formSelector} input[name="rpk_p"], ` +
            `${formSelector} input[name="rpk_a"]`;

        $section
            .off('change.anam', rpkSelector)
            .on('change.anam', rpkSelector, function () {
                if (isAnamnesisRILoading) {
                    return;
                }

                simpanAnamnesisRI();
            });

        // PERSALINAN
        $section
            .off('change.anam', `${formSelector} input[name="anam_p"]`)
            .on('change.anam', `${formSelector} input[name="anam_p"]`, function () {
                if (isAnamnesisRILoading) {
                    return;
                }

                updatePersalinanState();
                simpanAnamnesisRI();
            });

        // TEXTAREA & INPUT
        const textSelector =
            `${formSelector} textarea, ` +
            `${formSelector} input[type="text"], ` +
            `${formSelector} input[type="number"]`;

        $section
            .off('blur.anam', textSelector)
            .on('blur.anam', textSelector, function () {
                if (isAnamnesisRILoading || $(this).prop('disabled')) {
                    return;
                }

                simpanAnamnesisRI();
            });
    });
})();
</script>
