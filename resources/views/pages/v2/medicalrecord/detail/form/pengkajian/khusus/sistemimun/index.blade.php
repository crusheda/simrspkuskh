<div class="form-wrapper" id="form_khusus_sistemimun">
    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium"><center>PENGKAJIAN <b class="text-primary">PASIEN DENGAN SISTEM IMUNOLOGI TERGANGGU</b></center></h1>
    <div class="form-content">
        <div class="row align-items-center mb-3">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Pasien mengetahui penyakit saat ini
                </label>
            </div>

            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_pasien_mengetahui" value="Tidak" id="pm_pasien_mengetahui_tidak">
                        <label class="form-check-label" for="pm_pasien_mengetahui_tidak">
                            Tidak
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_pasien_mengetahui" value="Ya" id="pm_pasien_mengetahui_ya">
                        <label class="form-check-label" for="pm_pasien_mengetahui_ya">
                            Ya
                        </label>
                    </div>

                </div>
            </div>
        </div>


        <!-- ================================================= -->
        <!-- SUMBER INFORMASI -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Sumber informasi tentang penyakit diperoleh dari
                </label>
            </div>

            <div class="col-md-7">

                <div class="d-flex flex-wrap gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_sumber_informasi[]" value="Dokter" id="pm_sumber_dokter">
                        <label class="form-check-label" for="pm_sumber_dokter">
                            Dokter
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_sumber_informasi[]" value="Perawat" id="pm_sumber_perawat">
                        <label class="form-check-label" for="pm_sumber_perawat">
                            Perawat
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_sumber_informasi[]" value="Keluarga" id="pm_sumber_keluarga">
                        <label class="form-check-label" for="pm_sumber_keluarga">
                            Keluarga
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_sumber_informasi[]" value="Lain-lain" id="pm_sumber_lain">
                        <label class="form-check-label" for="pm_sumber_lain">
                            Lain-lain
                        </label>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- INFORMASI JANGKA WAKTU PENGOBATAN -->
        <!-- ================================================= -->
        <div class="row align-items-center mb-3">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Menerima informasi jangka waktu pengobatan
                </label>
            </div>

            <div class="col-md-7">

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_info_pengobatan" value="Ya" id="pm_info_pengobatan_ya">
                        <label class="form-check-label" for="pm_info_pengobatan_ya">
                            Ya
                        </label>
                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <input type="number" class="form-control form-control-sm" name="pm_lama_pengobatan" id="pm_lama_pengobatan" min="0" placeholder="Lama" style="width: 90px;">

                        <select class="form-select form-select-sm" name="pm_satuan_pengobatan" id="pm_satuan_pengobatan"
                            style="width: 115px;">
                            <option value="">Satuan</option>
                            <option value="minggu">Minggu</option>
                            <option value="bulan">Bulan</option>
                            <option value="tahun">Tahun</option>
                        </select>

                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_info_pengobatan" value="Tidak" id="pm_info_pengobatan_tidak">
                        <label class="form-check-label" for="pm_info_pengobatan_tidak">
                            Tidak
                        </label>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- PEMERIKSAAN RUTIN -->
        <!-- ================================================= -->
        <div class="row align-items-center mb-3">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Melakukan pemeriksaan rutin
                </label>
            </div>

            <div class="col-md-7">

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_pemeriksaan_rutin" value="Tidak" id="pm_pemeriksaan_rutin_tidak">
                        <label class="form-check-label" for="pm_pemeriksaan_rutin_tidak">
                            Tidak
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_pemeriksaan_rutin" value="Ya" id="pm_pemeriksaan_rutin_ya">
                        <label class="form-check-label" for="pm_pemeriksaan_rutin_ya">
                            Ya
                        </label>
                    </div>

                    <div class="flex-grow-1">

                        <input type="text" class="form-control form-control-sm" name="pm_pemeriksaan_rutin_tempat" id="pm_pemeriksaan_rutin_tempat"
                            placeholder="Tempat pemeriksaan">

                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- CARA PENULARAN -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Cara penularan
                </label>
            </div>

            <div class="col-md-7">

                <div class="d-flex flex-wrap gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_cara_penularan[]" value="Airborne" id="pm_penularan_airborne">
                        <label class="form-check-label" for="pm_penularan_airborne">
                            Airborne
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_cara_penularan[]" value="Droplet" id="pm_penularan_droplet">
                        <label class="form-check-label" for="pm_penularan_droplet">
                            Droplet
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_cara_penularan[]" value="Kontak langsung" id="pm_penularan_kontak">
                        <label class="form-check-label" for="pm_penularan_kontak">
                            Kontak langsung
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="pm_cara_penularan[]" value="Cairan tubuh" id="pm_penularan_cairan">
                        <label class="form-check-label" for="pm_penularan_cairan">
                            Cairan tubuh
                        </label>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- PENYAKIT PENYERTA -->
        <!-- ================================================= -->
        <div class="row align-items-center">

            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">
                    Penyakit penyerta
                </label>
            </div>

            <div class="col-md-7">

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_penyakit_penyerta" value="Tidak" id="pm_penyerta_tidak">
                        <label class="form-check-label" for="pm_penyerta_tidak">
                            Tidak
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pm_penyakit_penyerta" value="Ya" id="pm_penyerta_ya">
                        <label class="form-check-label" for="pm_penyerta_ya">
                            Ya
                        </label>
                    </div>

                    <div class="flex-grow-1">

                        <input type="text" class="form-control form-control-sm" name="pm_penyerta_keterangan" id="pm_penyerta_keterangan"
                            placeholder="Sebutkan penyakit penyerta...">

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_khusus_sistemimun');

    let isDataLoading = false;
    let isDataSaving = false;

    $(document).ready(function() {
        // Hanya memperbolehkan checkbox dipilih salah satu saja
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            $('input.single-checkbox[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
        $('.single-checkbox-bos').on('change', function () {
            // Jika checkbox di-uncheck, langsung kembalikan ke checked
            if (!this.checked) {
                this.checked = true;
                return;
            }
            // Uncheck pilihan lain dengan name yang sama
            $('input.single-checkbox-bos[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
    })

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getData() {

        if (!$form.length) {
            console.warn('Form Data tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/sistemimun/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                // ==========================================================
                // PASIEN MENGETAHUI PENYAKIT
                // ==========================================================
                $form
                    .find('input[name="pm_pasien_mengetahui"]')
                    .prop('checked', false);

                if (tlt.PASIEN_MENGETAHUI !== null) {

                    let value = tlt.PASIEN_MENGETAHUI;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pm_pasien_mengetahui"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // SUMBER INFORMASI
                // ==========================================================
                $form
                    .find('input[name="pm_sumber_informasi[]"]')
                    .prop('checked', false);

                if (tlt.SUMBER_INFORMASI) {

                    let values = tlt.SUMBER_INFORMASI;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {
                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }
                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pm_sumber_informasi[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // INFORMASI JANGKA WAKTU PENGOBATAN
                // ==========================================================
                $form
                    .find('input[name="pm_info_pengobatan"]')
                    .prop('checked', false);

                if (tlt.INFORMASI_PENGOBATAN !== null) {

                    let value = tlt.INFORMASI_PENGOBATAN;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pm_info_pengobatan"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // LAMA PENGOBATAN
                // ==========================================================
                if (tlt.LAMA_PENGOBATAN) {

                    let lama = tlt.LAMA_PENGOBATAN;

                    if (typeof lama === 'string') {

                        try {
                            lama = JSON.parse(lama);
                        } catch (e) {

                            console.warn(
                                'LAMA_PENGOBATAN bukan JSON valid:',
                                lama
                            );

                            lama = null;
                        }
                    }

                    if (lama && typeof lama === 'object') {

                        $form
                            .find('#pm_lama_pengobatan')
                            .val(lama.lama ?? '');

                        $form
                            .find('#pm_satuan_pengobatan')
                            .val(lama.satuan ?? '')
                            .trigger('change');
                    }
                }


                // ==========================================================
                // PEMERIKSAAN RUTIN
                // ==========================================================
                $form
                    .find('input[name="pm_pemeriksaan_rutin"]')
                    .prop('checked', false);

                if (tlt.PEMERIKSAAN_RUTIN !== null) {

                    let value = tlt.PEMERIKSAAN_RUTIN;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pm_pemeriksaan_rutin"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // TEMPAT PEMERIKSAAN RUTIN
                // ==========================================================
                $form
                    .find('#pm_pemeriksaan_rutin_tempat')
                    .val(
                        tlt.TEMPAT_PEMERIKSAAN_RUTIN ?? ''
                    );


                // ==========================================================
                // CARA PENULARAN
                // ==========================================================
                $form
                    .find('input[name="pm_cara_penularan[]"]')
                    .prop('checked', false);

                if (tlt.CARA_PENULARAN) {

                    let values = tlt.CARA_PENULARAN;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {

                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }

                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pm_cara_penularan[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // PENYAKIT PENYERTA
                // ==========================================================
                $form
                    .find('input[name="pm_penyakit_penyerta"]')
                    .prop('checked', false);

                if (tlt.PENYAKIT_PENYERTA !== null) {

                    let value = tlt.PENYAKIT_PENYERTA;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pm_penyakit_penyerta"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // KETERANGAN PENYAKIT PENYERTA
                // ==========================================================
                $form
                    .find('#pm_penyerta_keterangan')
                    .val(
                        tlt.KETERANGAN_PENYAKIT_PENYERTA ?? ''
                    );
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isDataLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanData() {

        if (
            !$form.length ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDataSaving = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/sistemimun/${kunjungan}/simpan`,
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
                    'Data gagal disimpan.';

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
                isDataSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        getData();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );
    });

})();
</script>
