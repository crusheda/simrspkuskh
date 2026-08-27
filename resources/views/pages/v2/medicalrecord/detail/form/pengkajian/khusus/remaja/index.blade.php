<div class="form-wrapper" id="form_khusus_remaja">
    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium"><center>PENGKAJIAN <b class="text-primary">PASIEN REMAJA</b></center></h1>
    <div class="form-content">

        <!-- KELOMPOK PEREMPUAN -->
        <div class="p-3 mb-3 border border-dashed rounded">
            <h6 class="fw-bold text-secondary mb-3">Khusus Pasien Perempuan</h6>
            
            <!-- Menstruasi -->
            <div class="row align-items-center mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Apakah sudah pernah menstruasi?</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_perempuan_menstruasi" value="Sudah" id="pm_menstruasi_sudah">
                            <label class="form-check-label" for="pm_menstruasi_sudah">Sudah</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_perempuan_menstruasi" value="Belum" id="pm_menstruasi_belum">
                            <label class="form-check-label" for="pm_menstruasi_belum">Belum</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Haid Pertama Kali -->
            <div class="row align-items-center mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Haid pertama kali</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control form-control-sm" name="pubertas_haid_pertama_usia" id="pubertas_haid_pertama_usia" placeholder="Usia" style="width: 90px;">
                        <span class="text-muted">Tahun</span>
                    </div>
                </div>
            </div>

            <!-- Siklus Haid -->
            <div class="row align-items-start mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Siklus haid</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex flex-wrap gap-3 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_siklus_haid" value="Teratur" id="pm_siklus_teratur">
                            <label class="form-check-label" for="pm_siklus_teratur">Teratur</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_siklus_haid" value="Tidak" id="pm_siklus_tidak">
                            <label class="form-check-label" for="pm_siklus_tidak">Tidak</label>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 70px;">Lamanya</span>
                            <input type="number" class="form-control form-control-sm" name="pubertas_lama_haid" id="pubertas_lama_haid" placeholder="Lama" style="width: 90px;">
                            <span class="text-muted">Hari</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 70px;">Siklus</span>
                            <input type="number" class="form-control form-control-sm" name="pubertas_jarak_siklus" id="pubertas_jarak_siklus" placeholder="Siklus" style="width: 90px;">
                            <span class="text-muted">Hari</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keadaan Emosional -->
            <div class="row align-items-start mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Keadaan emosional ketika menstruasi</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pubertas_emosi_haid[]" value="Sedikit pemarah" id="pm_emosi_pemarah">
                            <label class="form-check-label" for="pm_emosi_pemarah">Sedikit pemarah</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pubertas_emosi_haid[]" value="Marah" id="pm_emosi_marah">
                            <label class="form-check-label" for="pm_emosi_marah">Marah</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pubertas_emosi_haid[]" value="Menangis" id="pm_emosi_menangis">
                            <label class="form-check-label" for="pm_emosi_menangis">Menangis</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sumber Informasi Pubertas -->
            <div class="row align-items-center mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Sumber informasi tentang pubertas</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control form-control-sm" name="pubertas_sumber_info" id="pubertas_sumber_info" placeholder="Sebutkan sumber informasi...">
                </div>
            </div>

            <!-- KONDISI 1: Keluhan Saat Menstruasi (Aktif jika YA) -->
            <div class="row align-items-start">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Adakah keluhan saat menstruasi</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_keluhan_haid" value="Tidak" id="pm_keluhan_tidak" onchange="toggleSubOptions('pm_keluhan_ya', 'sub-keluhan')">
                                <label class="form-check-label" for="pm_keluhan_tidak">Tidak</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_keluhan_haid" value="Ya" id="pm_keluhan_ya" onchange="toggleSubOptions('pm_keluhan_ya', 'sub-keluhan')">
                                <label class="form-check-label" for="pm_keluhan_ya">Ya, Apa yang dilakukan:</label>
                            </div>
                        </div>
                        <div class="ms-4 d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input sub-keluhan" name="pubertas_tindakan_haid[]" value="Minum Obat" id="pm_tindakan_obat" disabled>
                                <label class="form-check-label" for="pm_tindakan_obat">Minum Obat</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input sub-keluhan" name="pubertas_tindakan_haid[]" value="Minum Jamu" id="pm_tindakan_jamu" disabled>
                                <label class="form-check-label" for="pm_tindakan_jamu">Minum Jamu</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input sub-keluhan" name="pubertas_tindakan_haid[]" value="Dibiarkan" id="pm_tindakan_dibiarkan" disabled>
                                <label class="form-check-label" for="pm_tindakan_dibiarkan">Dibiarkan</label>
                            </div>
                        </div>
                        <div class="ms-4 mt-1">
                            <input type="text" class="form-control form-control-sm sub-keluhan" name="pubertas_tindakan_haid_lainnya" id="pubertas_tindakan_haid_lainnya" placeholder="Lain-lain, sebutkan..." disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KELOMPOK LAKI-LAKI -->
        <div class="p-3 mb-3 border border-dashed rounded ">
            <h6 class="fw-bold text-secondary mb-3">Khusus Pasien Laki-laki</h6>
            
            <div class="row align-items-center mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Apakah sudah mimpi basah?</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_laki_mimpibasah" value="Sudah" id="pm_mimpi_sudah">
                            <label class="form-check-label" for="pm_mimpi_sudah">Sudah</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_laki_mimpibasah" value="Belum" id="pm_mimpi_belum">
                            <label class="form-check-label" for="pm_mimpi_belum">Belum</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1">Mimpi basah pertama kali</label>
                </div>
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control form-control-sm" name="pubertas_mimpi_pertama_usia" id="pubertas_mimpi_pertama_usia" placeholder="Usia" style="width: 90px;">
                        <span class="text-muted">Tahun</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONDISI 2: Pernikahan Dini (Aktif jika TIDAK) -->
        <div class="row align-items-start mb-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">Apakah pasien setuju dengan pernikahan dini?</label>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_nikah_dini" value="Ya" id="pm_nikah_ya" onchange="toggleSubOptions('pm_nikah_tidak', 'sub-nikah-dini')">
                            <label class="form-check-label" for="pm_nikah_ya">Ya</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_nikah_dini" value="Tidak" id="pm_nikah_tidak" onchange="toggleSubOptions('pm_nikah_tidak', 'sub-nikah-dini')">
                            <label class="form-check-label" for="pm_nikah_tidak">Tidak, alasan:</label>
                        </div>
                    </div>
                    <div class="ms-4 d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-nikah-dini" name="pubertas_alasan_nikah_dini[]" value="Malu" id="pm_alasan_malu" disabled>
                            <label class="form-check-label" for="pm_alasan_malu">Malu</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-nikah-dini" name="pubertas_alasan_nikah_dini[]" value="Dilarang orang tua" id="pm_alasan_orangtua" disabled>
                            <label class="form-check-label" for="pm_alasan_orangtua">Dilarang orang tua</label>
                        </div>
                    </div>
                    <div class="ms-4 mt-1">
                        <input type="text" class="form-control form-control-sm sub-nikah-dini" name="pubertas_alasan_nikah_dini_lainnya" id="pubertas_alasan_nikah_dini_lainnya" placeholder="Lain-lain, sebutkan..." disabled>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONDISI 3: Penyakit Menular Seksual (Aktif jika YA) -->
        <div class="row align-items-start mb-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">Apakah pasien mengetahui tentang Penyakit Menular Seksual (PMS)?</label>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_pengetahuan_pms" value="Tidak" id="pm_pms_tidak" onchange="toggleSubOptions('pm_pms_ya', 'sub-pms-info')">
                            <label class="form-check-label" for="pm_pms_tidak">Tidak</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_pengetahuan_pms" value="Ya" id="pm_pms_ya" onchange="toggleSubOptions('pm_pms_ya', 'sub-pms-info')">
                            <label class="form-check-label" for="pm_pms_ya">Ya, Darimana:</label>
                        </div>
                    </div>
                    <div class="ms-4 d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-pms-info" name="pubertas_sumber_pms[]" value="Sekolah" id="pm_sumber_pms_sekolah" disabled>
                            <label class="form-check-label" for="pm_sumber_pms_sekolah">Sekolah</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-pms-info" name="pubertas_sumber_pms[]" value="Orangtua" id="pm_sumber_pms_orangtua" disabled>
                            <label class="form-check-label" for="pm_sumber_pms_orangtua">Orangtua</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-pms-info" name="pubertas_sumber_pms[]" value="Internet" id="pm_sumber_pms_internet" disabled>
                            <label class="form-check-label" for="pm_sumber_pms_internet">Internet</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input sub-pms-info" name="pubertas_sumber_pms[]" value="Teman" id="pm_sumber_pms_teman" disabled>
                            <label class="form-check-label" for="pm_sumber_pms_teman">Teman</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pencegahan PMS -->
        <div class="row align-items-center mb-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">Apakah pasien mengetahui cara pencegahan PMS?</label>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_pencegahan_pms" value="Ya" id="pm_cegah_pms_ya">
                        <label class="form-check-label" for="pm_cegah_pms_ya">Ya</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pubertas_pencegahan_pms" value="Tidak" id="pm_cegah_pms_tidak">
                        <label class="form-check-label" for="pm_cegah_pms_tidak">Tidak</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_khusus_remaja');

    let isDataLoading = false;
    let isDataSaving = false;

    // DAFTARKAN FUNGSI KE WINDOW (Agar bisa dipanggil oleh onchange="..." di HTML)
    window.toggleSubOptions = function (triggerId, targetClass) {
        const triggerCheckbox = document.getElementById(triggerId);
        const targetElements = document.querySelectorAll('.' + targetClass);

        if (!triggerCheckbox) return;

        targetElements.forEach(element => {
            // Aktifkan jika trigger true, matikan jika trigger false
            element.disabled = !triggerCheckbox.checked;

            // Reset value jika di-disabled
            if (!triggerCheckbox.checked) {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = false;
                } else if (element.type === 'text' || element.type === 'number') {
                    element.value = '';
                }
            }
        });
    };

    $(document).ready(function() {
        // Hanya memperbolehkan checkbox dipilih salah satu saja
        // $('.single-checkbox').on('change', function () {
        //     if (!this.checked) return;
        //     $('input.single-checkbox[name="' + this.name + '"]')
        //         .not(this)
        //         .prop('checked', false);
        // });
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            
            const $otherCheckboxes = $('input.single-checkbox[name="' + this.name + '"]').not(this);
            
            $otherCheckboxes.each(function() {
                if (this.checked) {
                    this.checked = false;
                    // Pemicu manual agar fungsi onchange="toggleSubOptions(...)" di HTML ikut berjalan
                    $(this).trigger('change'); 
                }
            });
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
    });

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
            url: `/api/v2/emr/form/pengkajian/khu/remaja/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                // ==========================================================
                // MENSTRUASI
                // ==========================================================
                $form
                    .find('input[name="pubertas_perempuan_menstruasi"]')
                    .prop('checked', false);

                if (tlt.MENSTRUASI !== null) {

                    let value = tlt.MENSTRUASI;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_perempuan_menstruasi"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // USIA HAID PERTAMA
                // ==========================================================
                $form
                    .find('input[name="pubertas_haid_pertama_usia"]')
                    .val(tlt.HAID_PERTAMA_USIA ?? '');


                // ==========================================================
                // SIKLUS HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_siklus_haid"]')
                    .prop('checked', false);

                if (tlt.SIKLUS_HAID !== null) {

                    let value = tlt.SIKLUS_HAID;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_siklus_haid"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // LAMA HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_lama_haid"]')
                    .val(tlt.LAMA_HAID ?? '');


                // ==========================================================
                // JARAK SIKLUS HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_jarak_siklus"]')
                    .val(tlt.JARAK_SIKLUS_HAID ?? '');


                // ==========================================================
                // EMOSI SAAT HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_emosi_haid[]"]')
                    .prop('checked', false);

                if (tlt.EMOSI_HAID) {

                    let values = tlt.EMOSI_HAID;

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
                        .find('input[name="pubertas_emosi_haid[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // SUMBER INFORMASI PUBERTAS
                // ==========================================================
                $form
                    .find('input[name="pubertas_sumber_info"]')
                    .val(tlt.SUMBER_INFO_PUBERTAS ?? '');


                // ==========================================================
                // KELUHAN HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_keluhan_haid"]')
                    .prop('checked', false);

                if (tlt.KELUHAN_HAID !== null) {

                    let value = tlt.KELUHAN_HAID;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_keluhan_haid"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true)
                        .trigger('change');
                }


                // ==========================================================
                // TINDAKAN SAAT KELUHAN HAID
                // ==========================================================
                $form
                    .find('input[name="pubertas_tindakan_haid[]"]')
                    .prop('checked', false);

                if (tlt.TINDAKAN_HAID) {

                    let values = tlt.TINDAKAN_HAID;

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
                        .find('input[name="pubertas_tindakan_haid[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // TINDAKAN HAID LAINNYA
                // ==========================================================
                $form
                    .find('#pubertas_tindakan_haid_lainnya')
                    .val(
                        tlt.TINDAKAN_HAID_LAINNYA ?? ''
                    );


                // ==========================================================
                // MIMPI BASAH
                // ==========================================================
                $form
                    .find('input[name="pubertas_laki_mimpibasah"]')
                    .prop('checked', false);

                if (tlt.MIMPI_BASAH !== null) {

                    let value = tlt.MIMPI_BASAH;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_laki_mimpibasah"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }


                // ==========================================================
                // USIA MIMPI BASAH PERTAMA
                // ==========================================================
                $form
                    .find('input[name="pubertas_mimpi_pertama_usia"]')
                    .val(
                        tlt.MIMPI_BASAH_PERTAMA_USIA ?? ''
                    );


                // ==========================================================
                // PERNIKAHAN DINI
                // ==========================================================
                $form
                    .find('input[name="pubertas_nikah_dini"]')
                    .prop('checked', false);

                if (tlt.NIKAH_DINI !== null) {

                    let value = tlt.NIKAH_DINI;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_nikah_dini"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true)
                        .trigger('change');
                }


                // ==========================================================
                // ALASAN PERNIKAHAN DINI
                // ==========================================================
                $form
                    .find('input[name="pubertas_alasan_nikah_dini[]"]')
                    .prop('checked', false);

                if (tlt.ALASAN_NIKAH_DINI) {

                    let values = tlt.ALASAN_NIKAH_DINI;

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
                        .find('input[name="pubertas_alasan_nikah_dini[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // ALASAN PERNIKAHAN DINI LAINNYA
                // ==========================================================
                $form
                    .find('#pubertas_alasan_nikah_dini_lainnya')
                    .val(
                        tlt.ALASAN_NIKAH_DINI_LAINNYA ?? ''
                    );


                // ==========================================================
                // PENGETAHUAN PMS
                // ==========================================================
                $form
                    .find('input[name="pubertas_pengetahuan_pms"]')
                    .prop('checked', false);

                if (tlt.PENGETAHUAN_PMS !== null) {

                    let value = tlt.PENGETAHUAN_PMS;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_pengetahuan_pms"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true)
                        .trigger('change');
                }


                // ==========================================================
                // SUMBER PENGETAHUAN PMS
                // ==========================================================
                $form
                    .find('input[name="pubertas_sumber_pms[]"]')
                    .prop('checked', false);

                if (tlt.SUMBER_PENGETAHUAN_PMS) {

                    let values = tlt.SUMBER_PENGETAHUAN_PMS;

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
                        .find('input[name="pubertas_sumber_pms[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // PENCEGAHAN PMS
                // ==========================================================
                $form
                    .find('input[name="pubertas_pencegahan_pms"]')
                    .prop('checked', false);

                if (tlt.PENCEGAHAN_PMS !== null) {

                    let value = tlt.PENCEGAHAN_PMS;

                    if (typeof value === 'string') {
                        try {
                            value = JSON.parse(value);
                        } catch (e) {}
                    }

                    $form
                        .find('input[name="pubertas_pencegahan_pms"]')
                        .filter(function () {
                            return $(this).val() == value;
                        })
                        .prop('checked', true);
                }

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
            url: `/api/v2/emr/form/pengkajian/khu/remaja/${kunjungan}/simpan`,
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
