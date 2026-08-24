<div class="row align-items-center" id="form_hubungan_status_psikososial">
    <div class="col-md-12 mb-1">
        <div class="form-group">
            <h5 class="border-bottom pb-2 text-bold text-success">
                <strong>Psikologi - Sosial - Ekonomi - Spiritual - Fungsional</strong>
            </h5>
        </div>
    </div>

    {{-- ==========================================================
        STATUS PSIKOLOGI
    =========================================================== --}}
    <div class="col-md-12 mb-2">
        <div class="row">
            <label class="form-label fw-bold">Status Psikologi</label>

            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="tak" id="hsp_tak">
                    <label class="form-check-label" for="hsp_tak">Tidak ada kelainan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="marah" id="hsp_marah">
                    <label class="form-check-label" for="hsp_marah">Marah</label>
                </div>
            </div>

            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="cemas" id="hsp_cemas">
                    <label class="form-check-label" for="hsp_cemas">Cemas</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="takut" id="hsp_takut">
                    <label class="form-check-label" for="hsp_takut">Takut</label>
                </div>
            </div>

            <div class="col">
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="sedih" id="hsp_sedih">
                    <label class="form-check-label" for="hsp_sedih">Sedih</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input check-primary" type="checkbox" name="bundir" id="hsp_bundir">
                    <label class="form-check-label" for="hsp_bundir">Kecenderungan bunuh diri</label>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-center gap-2 mb-0">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input check-primary" type="checkbox" name="psel" id="hsp_psel">
                        <label class="form-check-label" for="hsp_psel">Lainnya</label>
                    </div>
                    <input type="text" class="form-control form-control-sm flex-grow-1" name="pse_lain" id="hsp_pse_lain">
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- ==========================================================
        STATUS MENTAL
    =========================================================== --}}
    <div class="col-md-12 mb-2">
        <label class="form-label fw-bold">Status Mental</label>

        <div class="form-check mb-2">
            <input class="form-check-input single-checkbox" type="checkbox" name="sm" value="1" id="hsp_sm_1">
            <label class="form-check-label" for="hsp_sm_1">
                Sadar dan orientasi baik
            </label>
        </div>

        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="form-check mb-0 flex-shrink-0">
                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sm" value="2" id="hsp_sm_2">
                <label class="form-check-label" for="hsp_sm_2">
                    Ada masalah perilaku
                </label>
            </div>
            <input type="text" class="form-control form-control-sm flex-grow-1" name="sm2_lain" id="hsp_sm2_lain">
        </div>

        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="form-check mb-0 flex-shrink-0">
                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sm" value="3" id="hsp_sm_3">
                <label class="form-check-label" for="hsp_sm_3">
                    Perilaku kekerasan yang dialami pasien sebelumnya
                </label>
            </div>
            <input type="text" class="form-control form-control-sm flex-grow-1" name="sm3_lain" id="hsp_sm3_lain">
        </div>
    </div>

    <hr>

    {{-- ==========================================================
        HUBUNGAN SOSIAL
    =========================================================== --}}
    <div class="col-md-12 mb-2">
        <label class="form-label fw-bold">Hubungan Sosial</label>

        <div class="row align-items-center">
            <label class="col-md-3 col-form-label">
                Hubungan Pasien dan anggota keluarga
            </label>

            <div class="col-md-9">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hub" value="1" id="hsp_hub_1">
                    <label class="form-check-label" for="hsp_hub_1">Baik</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hub" value="2" id="hsp_hub_2">
                    <label class="form-check-label" for="hsp_hub_2">Tidak baik</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Pasien tinggal di</label>

            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="1" id="hsp_tinggal_1">
                    <label class="form-check-label" for="hsp_tinggal_1">Rumah</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="2" id="hsp_tinggal_2">
                    <label class="form-check-label" for="hsp_tinggal_2">Panti</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tinggal" value="3" id="hsp_tinggal_3">
                    <label class="form-check-label" for="hsp_tinggal_3">Lainnya</label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="text" class="form-control form-control-sm" name="tinggal_lain" id="hsp_tinggal_lain" placeholder="Sebutkan">
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- ==========================================================
        HUBUNGAN SPIRITUAL
    =========================================================== --}}
    <div class="col-md-12 mb-1">
        <label class="form-label fw-bold">Hubungan Spiritual</label>

        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Agama</label>

            <div class="col-md-10">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    name="agama"
                    value="{{ $list['pasien']->AGAMA ?? '' }}"
                    placeholder="Otomatis terisi oleh sistem"
                    readonly
                >
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Kebiasaan beribadah teratur</label>

            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="kbt" value="1" id="hsp_kbt_1">
                    <label class="form-check-label" for="hsp_kbt_1">Ya</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="kbt" value="2" id="hsp_kbt_2">
                    <label class="form-check-label" for="hsp_kbt_2">Tidak</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Nilai-nilai Kepercayaan</label>

            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="nk" value="1" id="hsp_nk_1">
                    <label class="form-check-label" for="hsp_nk_1">Tidak ada</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="nk" value="2" id="hsp_nk_2">
                    <label class="form-check-label" for="hsp_nk_2">Ada</label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="text" class="form-control form-control-sm" name="nk_lain" id="hsp_nk_lain" placeholder="Sebutkan">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Pengambil keputusan dalam keluarga</label>

            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="pk">
            </div>
        </div>
    </div>

    <hr>

    {{-- ==========================================================
        EKONOMI
    =========================================================== --}}
    <div class="col-md-12 mb-1">
        <label class="form-label fw-bold">Ekonomi</label>

        <div class="row mb-1 align-items-center">
            <label class="col-md-2 col-form-label">Pekerjaan</label>

            <div class="col-md-10">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    name="kerja"
                    value="{{ $list['pasien']->PEKERJAAN ?? '' }}"
                    placeholder="Otomatis terisi oleh sistem"
                    readonly
                >
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div class="row align-items-center">
            <label class="col-md-2 col-form-label">Penghasilan per bulan</label>

            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="1" id="hsp_hasil_1">
                    <label class="form-check-label" for="hsp_hasil_1">
                        &lt; Rp. 5.000.000
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="2" id="hsp_hasil_2">
                    <label class="form-check-label" for="hsp_hasil_2">
                        Rp. 5.000.000 - Rp. 10.000.000
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input single-checkbox" type="checkbox" name="hasil" value="3" id="hsp_hasil_3">
                    <label class="form-check-label" for="hsp_hasil_3">
                        &gt; Rp. 10.000.000
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
    const $form = $section.find('#form_hubungan_status_psikososial');

    let isHSPLoading = false;
    let isHSPSaving = false;

    // ==============================================================
    // UPDATE FIELD TAMBAHAN
    // ==============================================================
    function updateAdditionalFieldState() {
        // ----------------------------------------------------------
        // Status Psikologi - Lainnya
        // ----------------------------------------------------------
        const psel = $form
            .find('input[name="psel"]:checked')
            .length > 0;

        const $pseLain = $form.find('input[name="pse_lain"]');

        if (psel) {
            $pseLain.prop('disabled', false);
        } else {
            $pseLain.val('').prop('disabled', true);
        }

        // ----------------------------------------------------------
        // Status Mental - Masalah Perilaku
        // ----------------------------------------------------------
        const sm = $form
            .find('input[name="sm"]:checked')
            .val();

        const $sm2Lain = $form.find('input[name="sm2_lain"]');
        const $sm3Lain = $form.find('input[name="sm3_lain"]');

        if (String(sm) === '2') {
            $sm2Lain.prop('disabled', false);
        } else {
            $sm2Lain.val('').prop('disabled', true);
        }

        if (String(sm) === '3') {
            $sm3Lain.prop('disabled', false);
        } else {
            $sm3Lain.val('').prop('disabled', true);
        }

        // ----------------------------------------------------------
        // Tempat Tinggal - Lainnya
        // ----------------------------------------------------------
        const tinggal = $form
            .find('input[name="tinggal"]:checked')
            .val();

        const $tinggalLain = $form.find('input[name="tinggal_lain"]');

        if (String(tinggal) === '3') {
            $tinggalLain.prop('disabled', false);
        } else {
            $tinggalLain.val('').prop('disabled', true);
        }

        // ----------------------------------------------------------
        // Nilai Kepercayaan - Ada
        // ----------------------------------------------------------
        const nk = $form
            .find('input[name="nk"]:checked')
            .val();

        const $nkLain = $form.find('input[name="nk_lain"]');

        if (String(nk) === '2') {
            $nkLain.prop('disabled', false);
        } else {
            $nkLain.val('').prop('disabled', true);
        }
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
    function getHubunganStatusPsikososial() {
        if (!$form.length) {
            console.warn(
                'Form Hubungan Status Psikososial tidak ditemukan.'
            );
            return;
        }

        isHSPLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/hubunganstatuspsikososial/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {
                const anam = res.data;

                if (!anam) {
                    updateAdditionalFieldState();
                    return;
                }

                // ==================================================
                // STATUS PSIKOLOGI
                // ==================================================
                setCheckboxValue(
                    'tak',
                    anam.TIDAK_ADA_KELAINAN
                );

                setCheckboxValue(
                    'marah',
                    anam.MARAH
                );

                setCheckboxValue(
                    'cemas',
                    anam.CEMAS
                );

                setCheckboxValue(
                    'takut',
                    anam.TAKUT
                );

                setCheckboxValue(
                    'sedih',
                    anam.SEDIH
                );

                setCheckboxValue(
                    'bundir',
                    anam.BUNUH_DIRI
                );

                if (FormHelper.hasValue(anam.LAINNYA)) {
                    FormHelper.setValue(
                        $section,
                        'pse_lain',
                        anam.LAINNYA
                    );

                    $form
                        .find('input[name="psel"]')
                        .prop('checked', true);
                }

                // ==================================================
                // STATUS MENTAL
                // ==================================================
                if (FormHelper.hasValue(anam.STATUS_MENTAL)) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'sm',
                        anam.STATUS_MENTAL
                    );
                }

                if (FormHelper.hasValue(anam.MASALAH_PERILAKU)) {
                    FormHelper.setValue(
                        $section,
                        'sm2_lain',
                        anam.MASALAH_PERILAKU
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA
                    )
                ) {
                    FormHelper.setValue(
                        $section,
                        'sm3_lain',
                        anam.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA
                    );
                }

                // ==================================================
                // HUBUNGAN SOSIAL
                // ==================================================
                if (
                    FormHelper.hasValue(
                        anam.HUBUNGAN_PASIEN_DENGAN_KELUARGA
                    )
                ) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'hub',
                        anam.HUBUNGAN_PASIEN_DENGAN_KELUARGA
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.TEMPAT_TINGGAL
                    )
                ) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'tinggal',
                        anam.TEMPAT_TINGGAL
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.TEMPAT_TINGGAL_LAINNYA
                    )
                ) {
                    FormHelper.setValue(
                        $section,
                        'tinggal_lain',
                        anam.TEMPAT_TINGGAL_LAINNYA
                    );
                }

                // ==================================================
                // SPIRITUAL
                // ==================================================
                if (
                    FormHelper.hasValue(
                        anam.KEBIASAAN_BERIBADAH_TERATUR
                    )
                ) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'kbt',
                        anam.KEBIASAAN_BERIBADAH_TERATUR
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.NILAI_KEPERCAYAAN
                    )
                ) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'nk',
                        anam.NILAI_KEPERCAYAAN
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.NILAI_KEPERCAYAAN_DESKRIPSI
                    )
                ) {
                    FormHelper.setValue(
                        $section,
                        'nk_lain',
                        anam.NILAI_KEPERCAYAAN_DESKRIPSI
                    );
                }

                if (
                    FormHelper.hasValue(
                        anam.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA
                    )
                ) {
                    FormHelper.setValue(
                        $section,
                        'pk',
                        anam.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA
                    );
                }

                // ==================================================
                // EKONOMI
                // ==================================================
                if (
                    FormHelper.hasValue(
                        anam.PENGHASILAN_PERBULAN
                    )
                ) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'hasil',
                        anam.PENGHASILAN_PERBULAN
                    );
                }

                updateAdditionalFieldState();
            },

            error: function (xhr, status, error) {
                console.error(
                    'Error Hubungan Status Psikososial:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Hubungan Status Psikososial.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isHSPLoading = false;
                updateAdditionalFieldState();
            }
        });
    }

    // ==============================================================
    // SIMPAN DATA
    // ==============================================================
    function simpanHubunganStatusPsikososial() {
        if (
            !$form.length ||
            isHSPLoading ||
            isHSPSaving
        ) {
            return;
        }

        updateAdditionalFieldState();

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isHSPSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/hubunganstatuspsikososial/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {
                console.log(
                    'Hubungan Status Psikososial berhasil disimpan.'
                );
            },

            error: function (xhr) {
                let message =
                    'Data Hubungan Status Psikososial gagal disimpan.';

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
                isHSPSaving = false;
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

        updateAdditionalFieldState();
        getHubunganStatusPsikososial();

        // ==========================================================
        // STATUS PSIKOLOGI
        // ==========================================================
        $section
            .off(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="tak"],' +
                '#form_hubungan_status_psikososial input[name="marah"],' +
                '#form_hubungan_status_psikososial input[name="cemas"],' +
                '#form_hubungan_status_psikososial input[name="takut"],' +
                '#form_hubungan_status_psikososial input[name="sedih"],' +
                '#form_hubungan_status_psikososial input[name="bundir"],' +
                '#form_hubungan_status_psikososial input[name="psel"]'
            )
            .on(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="tak"],' +
                '#form_hubungan_status_psikososial input[name="marah"],' +
                '#form_hubungan_status_psikososial input[name="cemas"],' +
                '#form_hubungan_status_psikososial input[name="takut"],' +
                '#form_hubungan_status_psikososial input[name="sedih"],' +
                '#form_hubungan_status_psikososial input[name="bundir"],' +
                '#form_hubungan_status_psikososial input[name="psel"]',
                function () {
                    if (isHSPLoading) {
                        return;
                    }

                    updateAdditionalFieldState();
                    simpanHubunganStatusPsikososial();
                }
            );

        // ==========================================================
        // STATUS MENTAL
        // ==========================================================
        $section
            .off(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="sm"]'
            )
            .on(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="sm"]',
                function () {
                    if (isHSPLoading) {
                        return;
                    }

                    updateAdditionalFieldState();
                    simpanHubunganStatusPsikososial();
                }
            );

        // ==========================================================
        // HUBUNGAN SOSIAL
        // ==========================================================
        $section
            .off(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="hub"],' +
                '#form_hubungan_status_psikososial input[name="tinggal"]'
            )
            .on(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="hub"],' +
                '#form_hubungan_status_psikososial input[name="tinggal"]',
                function () {
                    if (isHSPLoading) {
                        return;
                    }

                    updateAdditionalFieldState();
                    simpanHubunganStatusPsikososial();
                }
            );

        // ==========================================================
        // SPIRITUAL & EKONOMI
        // ==========================================================
        $section
            .off(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="kbt"],' +
                '#form_hubungan_status_psikososial input[name="nk"],' +
                '#form_hubungan_status_psikososial input[name="hasil"]'
            )
            .on(
                'change.hsp',
                '#form_hubungan_status_psikososial input[name="kbt"],' +
                '#form_hubungan_status_psikososial input[name="nk"],' +
                '#form_hubungan_status_psikososial input[name="hasil"]',
                function () {
                    if (isHSPLoading) {
                        return;
                    }

                    updateAdditionalFieldState();
                    simpanHubunganStatusPsikososial();
                }
            );

        // ==========================================================
        // INPUT TEXT
        // ==========================================================
        $section
            .off(
                'blur.hsp',
                '#form_hubungan_status_psikososial input[type="text"]'
            )
            .on(
                'blur.hsp',
                '#form_hubungan_status_psikososial input[type="text"]',
                function () {
                    if (isHSPLoading) {
                        return;
                    }

                    if ($(this).prop('disabled')) {
                        return;
                    }

                    simpanHubunganStatusPsikososial();
                }
            );
    });
})();
</script>
