<div class="row" id="form_discharge_planning">
    <div class="col-md-12 mb-1">
        <div class="form-group">
            <h5 class="border-bottom pb-2 text-bold text-success">
                <strong>Discharge Planning</strong>
            </h5>
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien tinggal sendiri :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_1" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_1" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien/keluarga khawatir ketika kembali di rumah :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_2" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_2" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien di rumah tidak ada yang merawat :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_3" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_3" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien tinggal di lantai atas rumah :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_4" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_4" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien masih ada perawatan lanjutan / penggunaan alat medis yang dilakukan di rumah :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_5" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_5" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>

            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_5" hidden>
                <div class="row">
                    <h6>Jika Ada, sebutkan :</h6>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_5_1" value="1">
                            <label class="form-check-label">Kateter Urin</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_5_2" value="1">
                            <label class="form-check-label">Traechostomy</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_5_3" value="1">
                            <label class="form-check-label">NGT</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_5_4" value="1">
                            <label class="form-check-label">Colostomy</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label class="form-check-label flex-shrink-0">Lainnya</label>
                            <input type="text" class="form-control form-control-sm" name="dp_5_lain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Kebutuhan pelayanan berkelanjutan :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_9" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_9" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>

            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_9" hidden>
                <div class="row">
                    <h6>Jika Ada, sebutkan :</h6>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_1" value="1">
                            <label class="form-check-label">Rawat luka</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_2" value="1">
                            <label class="form-check-label">TB</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_3" value="1">
                            <label class="form-check-label">DM dengan terapi insulin</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_4" value="1">
                            <label class="form-check-label">PPOK</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_5" value="1">
                            <label class="form-check-label">Pasien kemoterapi</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_6" value="1">
                            <label class="form-check-label">HIV / AIDS</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_7" value="1">
                            <label class="form-check-label">DM</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_8" value="1">
                            <label class="form-check-label">Stroke</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_9_9" value="1">
                            <label class="form-check-label">CKD</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien pulang dengan jumlah obat &gt; 6 :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_6" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_6" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Pasien mengajukan permohonan pendampingan ke Rumah Sakit :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_7" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_7" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Tidak ada kriteria Pasien :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_8" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_8" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Penggunaan alat medis/bantu :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_10" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_10" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>

            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_10" hidden>
                <div class="row">
                    <h6>Jika Ada, sebutkan :</h6>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_10_1" value="1">
                            <label class="form-check-label">Kateter Urin</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_10_2" value="1">
                            <label class="form-check-label">Traechostomy</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_10_3" value="1">
                            <label class="form-check-label">NGT</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="dp_10_4" value="1">
                            <label class="form-check-label">Colostomy</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label class="form-check-label flex-shrink-0">Lainnya</label>
                            <input type="text" class="form-control form-control-sm" name="dp_10_lain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <label class="form-label">Skrining lanjutan :</label>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_11" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input single-checkbox-bos" type="checkbox" name="dp_11" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>
            </div>

            <div class="card card-body border border-dashed border-light shadow-sm mb-3 mt-2" id="tampil_dp_11" hidden>
                <div class="row">
                    <h6>Jika Ada :</h6>
                    <div class="col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input single-checkbox" type="checkbox" name="dp_11_skrining" value="1">
                            <label class="form-check-label">Konsul MPP</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input single-checkbox" type="checkbox" name="dp_11_skrining" value="2">
                            <label class="form-check-label">Edukasi</label>
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
    const $form = $section.find('#form_discharge_planning');

    let isDischargePlanningLoading = false;
    let isDischargePlanningSaving = false;

    function updateAdditionalFieldState() {
        if (!$form.length) return;

        const dp5 = $form.find('input[name="dp_5"]:checked').val();
        const dp9 = $form.find('input[name="dp_9"]:checked').val();
        const dp10 = $form.find('input[name="dp_10"]:checked').val();
        const dp11 = $form.find('input[name="dp_11"]:checked').val();

        const showDp5 = dp5 === '1';
        const showDp9 = dp9 === '1';
        const showDp10 = dp10 === '1';
        const showDp11 = dp11 === '1';

        $form.find('#tampil_dp_5').prop('hidden', !showDp5);
        $form.find('#tampil_dp_9').prop('hidden', !showDp9);
        $form.find('#tampil_dp_10').prop('hidden', !showDp10);
        $form.find('#tampil_dp_11').prop('hidden', !showDp11);

        const $dp5Fields = $form.find(
            'input[name^="dp_5_"]'
        );
        const $dp9Fields = $form.find(
            'input[name^="dp_9_"]'
        );
        const $dp10Fields = $form.find(
            'input[name^="dp_10_"]'
        );
        const $dp11Fields = $form.find(
            'input[name="dp_11_skrining"]'
        );

        $dp5Fields.prop('disabled', !showDp5);
        $dp9Fields.prop('disabled', !showDp9);
        $dp10Fields.prop('disabled', !showDp10);
        $dp11Fields.prop('disabled', !showDp11);

        if (!showDp5) {
            $dp5Fields.prop('checked', false);
            $form.find('input[name="dp_5_lain"]').val('');
        }

        if (!showDp9) {
            $dp9Fields.prop('checked', false);
        }

        if (!showDp10) {
            $dp10Fields.prop('checked', false);
            $form.find('input[name="dp_10_lain"]').val('');
        }

        if (!showDp11) {
            $dp11Fields.prop('checked', false);
        }
    }

    function getDischargePlanning() {
        if (!$form.length) {
            console.warn('Form Discharge Planning tidak ditemukan.');
            return;
        }

        isDischargePlanningLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/dischargeplanning/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                const dc1 = res.data?.dc1;
                const dc2 = res.data?.dc2;

                if (dc1) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_1',
                        dc1.PASIEN_TINGGAL_SENDIRI
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_2',
                        dc1.PASIEN_KHAWATIR_KETIKA_DIRUMAH
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_3',
                        dc1.PASIEN_TAK_ADA_YANG_MERAWAT
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_4',
                        dc1.PASIEN_DILANTAI_ATAS
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_7',
                        dc1.PENGAJUAN_PENDAMPINGAN_PASIEN
                    );
                }

                if (dc2) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_5',
                        dc2.PERAWATAN_LANJUTAN_MEDIS
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_6',
                        dc2.PASIEN_PULANG
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_7',
                        dc2.PASIEN_MENGAJUKAN
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_8',
                        dc2.TIDAK_ADA_KRITERIA
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_9',
                        dc2.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_10',
                        dc2.PENGGUNAAN_ALAT_MEDIS_PAM
                    );

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_11',
                        dc2.SKRINING_LANJUTAN
                    );

                    $form.find('input[name="dp_5_1"]').prop(
                        'checked',
                        FormHelper.hasValue(dc2.PLM_KATETER_URIN) &&
                        Number(dc2.PLM_KATETER_URIN) === 1
                    );

                    $form.find('input[name="dp_5_2"]').prop(
                        'checked',
                        FormHelper.hasValue(dc2.PLM_TRAECHOSTOMY) &&
                        Number(dc2.PLM_TRAECHOSTOMY) === 1
                    );

                    $form.find('input[name="dp_5_3"]').prop(
                        'checked',
                        FormHelper.hasValue(dc2.PLM_NGT) &&
                        Number(dc2.PLM_NGT) === 1
                    );

                    $form.find('input[name="dp_5_4"]').prop(
                        'checked',
                        FormHelper.hasValue(dc2.PLM_COLOSTOMY) &&
                        Number(dc2.PLM_COLOSTOMY) === 1
                    );

                    if (FormHelper.hasValue(dc2.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA)) {
                        FormHelper.setValue(
                            $section,
                            'dp_5_lain',
                            dc2.KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA
                        );
                    }

                    $form.find('input[name="dp_9_1"]').prop(
                        'checked',
                        Number(dc2.KPB_RAWAT_LUKA) === 1
                    );

                    $form.find('input[name="dp_9_2"]').prop(
                        'checked',
                        Number(dc2.KPB_TB) === 1
                    );

                    $form.find('input[name="dp_9_3"]').prop(
                        'checked',
                        Number(dc2.KPB_DM_TERAPI_INSULIN) === 1
                    );

                    $form.find('input[name="dp_9_4"]').prop(
                        'checked',
                        Number(dc2.KPB_PPOK) === 1
                    );

                    $form.find('input[name="dp_9_5"]').prop(
                        'checked',
                        Number(dc2.KPB_PASIEN_KEMO) === 1
                    );

                    $form.find('input[name="dp_9_6"]').prop(
                        'checked',
                        Number(dc2.KPB_HIV) === 1
                    );

                    $form.find('input[name="dp_9_7"]').prop(
                        'checked',
                        Number(dc2.KPB_DM) === 1
                    );

                    $form.find('input[name="dp_9_8"]').prop(
                        'checked',
                        Number(dc2.KPB_STROKE) === 1
                    );

                    $form.find('input[name="dp_9_9"]').prop(
                        'checked',
                        Number(dc2.KPB_CKD) === 1
                    );

                    $form.find('input[name="dp_10_1"]').prop(
                        'checked',
                        Number(dc2.PAM_KATETER_URIN) === 1
                    );

                    $form.find('input[name="dp_10_2"]').prop(
                        'checked',
                        Number(dc2.PAM_TRAECHOSTOMY) === 1
                    );

                    $form.find('input[name="dp_10_3"]').prop(
                        'checked',
                        Number(dc2.PAM_NGT) === 1
                    );

                    $form.find('input[name="dp_10_4"]').prop(
                        'checked',
                        Number(dc2.PAM_COLOSTOMY) === 1
                    );

                    if (FormHelper.hasValue(dc2.PAM_LAINNYA)) {
                        FormHelper.setValue(
                            $section,
                            'dp_10_lain',
                            dc2.PAM_LAINNYA
                        );
                    }

                    FormHelper.setSingleCheckbox(
                        $section,
                        'dp_11_skrining',
                        dc2.SKRINING
                    );
                }

                updateAdditionalFieldState();
            },
            error: function (xhr, status, error) {
                console.error(
                    'Error Discharge Planning:',
                    xhr.responseText || error
                );

                let message = 'Gagal mengambil data Discharge Planning.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },
            complete: function () {
                isDischargePlanningLoading = false;
                updateAdditionalFieldState();
            }
        });
    }

    function simpanDischargePlanning() {
        if (
            !$form.length ||
            isDischargePlanningLoading ||
            isDischargePlanningSaving
        ) {
            return;
        }

        updateAdditionalFieldState();

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDischargePlanningSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/dischargeplanning/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log('Discharge Planning berhasil disimpan.');
            },
            error: function (xhr) {
                let message = 'Data Discharge Planning gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
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
                isDischargePlanningSaving = false;
            }
        });
    }

    $(function () {
        if (!$form.length) return;

        getDischargePlanning();

        $form.on('change', 'input[type="checkbox"]', function () {
            updateAdditionalFieldState();
            simpanDischargePlanning();
        });

        $form.on('blur', 'input[type="text"]', function () {
            simpanDischargePlanning();
        });
    });
})();
</script>
