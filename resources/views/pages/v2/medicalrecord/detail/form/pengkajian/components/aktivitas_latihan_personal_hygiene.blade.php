<div class="row align-items-center" id="form_aktivitas_latihan_personal_hygiene">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h5 class="mb-0">
                <strong>Aktivitas Dan Latihan, Personal Hygiene</strong>
            </h5>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label mb-2">
                Tingkat Ketergantungan
            </label>
            <div class="d-flex flex-wrap gap-4">
                <div class="form-check">
                    <input
                        class="form-check-input check-primary single-checkbox"
                        type="checkbox"
                        name="ph_tk"
                        value="1"
                    >
                    <label class="form-check-label">
                        Total
                    </label>
                </div>
                <div class="form-check">
                    <input
                        class="form-check-input check-primary single-checkbox"
                        type="checkbox"
                        name="ph_tk"
                        value="2"
                    >
                    <label class="form-check-label">
                        Bantuan Sebagian
                    </label>
                </div>
                <div class="form-check">
                    <input
                        class="form-check-input check-primary single-checkbox"
                        type="checkbox"
                        name="ph_tk"
                        value="3"
                    >
                    <label class="form-check-label">
                        Bantuan Minimal
                    </label>
                </div>
                <div class="form-check">
                    <input
                        class="form-check-input check-primary single-checkbox"
                        type="checkbox"
                        name="ph_tk"
                        value="4"
                    >
                    <label class="form-check-label">
                        Mandiri
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <div class="d-flex align-items-center column-gap-4 row-gap-4 flex-wrap">
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label flex-shrink-0">Mandi</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control form-control-sm" name="ph_m" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                        <span class="input-group-text">X/hari</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label flex-shrink-0">Ganti Pakaian</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control form-control-sm" name="ph_gp" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                        <span class="input-group-text">X/hari</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label flex-shrink-0">Keramas</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control form-control-sm" name="ph_k" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                        <span class="input-group-text">X/hari</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label flex-shrink-0">Gosok Gigi</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control form-control-sm" name="ph_gg" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                        <span class="input-group-text">X/hari</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row align-items-center align-middle">
            <div class="col">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center gap-4">
                        <label class="form-label flex-shrink-0">Memotong Kuku</label>
                        <div class="form-check mb-0">
                            <input class="form-check-input check-danger single-checkbox" type="checkbox" name="ph_mk" value="0">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="ph_mk" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center gap-3 flex-shrink-0">
                        <label class="form-label flex-shrink-0">Tidur Siang</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control form-control-sm" name="ph_ts" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                            <span class="input-group-text">Jam</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center gap-3 flex-shrink-0">
                        <label class="form-label flex-shrink-0">Tidur Malam</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control form-control-sm" name="ph_tm" min="1" max="20" style="width: 100px; flex: 0 0 90px;" placeholder="">
                            <span class="input-group-text">Jam</span>
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
    const $form = $section.find('#form_aktivitas_latihan_personal_hygiene');
    let isALPHLoading = false;
    let isALPHSaving = false;

    // ==============================================================
    // GET DATA
    // ==============================================================
    function getALPH() {
        if (!$form.length) {
            console.warn('Form Aktivitas Latihan Personal Hygiene tidak ditemukan.');
            return;
        }

        isALPHLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/alph/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                const alph = res.data;

                if (!alph) {
                    return;
                }

                // Tingkat Ketergantungan
                if (FormHelper.hasValue(alph.TINGKAT_KETERGANTUNGAN)) {
                    FormHelper.setSingleCheckbox(
                        $section,
                        'ph_tk',
                        alph.TINGKAT_KETERGANTUNGAN
                    );
                }

                // Mandi
                if (FormHelper.hasValue(alph.MANDI)) {
                    $form.find('[name="ph_m"]').val(alph.MANDI);
                }

                // Ganti Pakaian
                if (FormHelper.hasValue(alph.GANTI_PAKAIAN)) {
                    $form.find('[name="ph_gp"]').val(alph.GANTI_PAKAIAN);
                }

                // Keramas
                if (FormHelper.hasValue(alph.KERAMAS)) {
                    $form.find('[name="ph_k"]').val(alph.KERAMAS);
                }

                // Gosok Gigi
                if (FormHelper.hasValue(alph.GOSOK_GIGI)) {
                    $form.find('[name="ph_gg"]').val(alph.GOSOK_GIGI);
                }

                // Memotong Kuku
                FormHelper.setSingleCheckbox(
                    $section,
                    'ph_mk',
                    alph.MEMOTONG_KUKU
                );

                // Tidur Siang
                if (FormHelper.hasValue(alph.TIDUR_SIANG)) {
                    $form.find('[name="ph_ts"]').val(alph.TIDUR_SIANG);
                }

                // Tidur Malam
                if (FormHelper.hasValue(alph.TIDUR_MALAM)) {
                    $form.find('[name="ph_tm"]').val(alph.TIDUR_MALAM);
                }
            },
            error: function (xhr, status, error) {
                console.error(
                    'Error Aktivitas Latihan Personal Hygiene:',
                    xhr.responseText || error
                );

                let message = 'Gagal mengambil data Aktivitas Latihan Personal Hygiene.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },
            complete: function () {
                isALPHLoading = false;
            }
        });
    }

    // ==============================================================
    // SIMPAN DATA
    // ==============================================================
    function simpanALPH() {
        if (!$form.length || isALPHLoading || isALPHSaving) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isALPHSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/alph/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log('Aktivitas Latihan Personal Hygiene berhasil disimpan.');
            },
            error: function (xhr) {
                let message = 'Data Aktivitas Latihan Personal Hygiene gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
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
                isALPHSaving = false;
            }
        });
    }

    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {
        getALPH();

        // Checkbox -> langsung simpan ketika berubah
        $section
            .off(
                'change.alph',
                '#form_aktivitas_latihan_personal_hygiene input[type="checkbox"]'
            )
            .on(
                'change.alph',
                '#form_aktivitas_latihan_personal_hygiene input[type="checkbox"]',
                function () {
                    if (isALPHLoading) {
                        return;
                    }

                    simpanALPH();
                }
            );

        // Input angka -> simpan ketika user selesai input / keluar dari input
        $section
            .off(
                'blur.alph',
                '#form_aktivitas_latihan_personal_hygiene input[type="number"]'
            )
            .on(
                'blur.alph',
                '#form_aktivitas_latihan_personal_hygiene input[type="number"]',
                function () {
                    if (isALPHLoading) {
                        return;
                    }

                    simpanALPH();
                }
            );
    });

})();
</script>
