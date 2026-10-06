<div class="form-wrapper" id="form_discharge_planning">

    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium">
        <center>
            RENCANA PEMULANGAN PASIEN
            <b class="text-success">(DISCHARGE PLANNING CHECKLIST)</b>
        </center>
    </h1>

    <div class="form-content">

        {{-- ==========================================================
            DATA UTAMA
        =========================================================== --}}
        <div id="form_discharge_planning_utama">

            {{-- TANGGAL & JAM --}}
            <div class="row mb-3">

                <div class="col-md-6">
                    <div class="form-group">
                        <h6>Tanggal</h6>
                        <input
                            type="date"
                            class="form-control"
                            name="dp_tanggal"
                            id="dp_tanggal"
                            readonly
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <h6>Jam</h6>
                        <input
                            type="time"
                            class="form-control"
                            name="dp_jam"
                            id="dp_jam"
                            readonly
                        >
                    </div>
                </div>

            </div>


            {{-- ======================================================
                KRITERIA PEMULANGAN KOMPLEKS
            ======================================================= --}}
            <div class="col-md-12 mb-3">

                <div class="form-group">

                    <h4 class="text-danger">
                        KRITERIA PEMULANGAN KOMPLEKS
                    </h4>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead class="text-center">
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Kriteria</th>
                                    <th style="width: 100px;">Ya</th>
                                    <th style="width: 100px;">Tidak</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Umur &gt; 65 Tahun</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_umur"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_umur"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Keterbatasan Mobilitas</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_mobilitas"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_mobilitas"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Perawatan dan pengobatan lanjutan</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_perawatan"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_perawatan"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">4</td>
                                    <td>
                                        Bantuan untuk melakukan aktifitas
                                        (Psikososial, Ekonomi, Nutrisi)
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_bantuan"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_bantuan"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">5</td>
                                    <td>
                                        Status Gizi: risiko malnutrisi
                                        sedang dan malnutrisi berat
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_gizi"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_gizi"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">6</td>
                                    <td>Baru di diagnosa Diabetes</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_diabetes"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_diabetes"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">7</td>
                                    <td>Rawat ICU</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_icu"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_icu"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">8</td>
                                    <td>Kolaborasi dengan &gt; 2 DPJP</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_dpjp"
                                            value="1"
                                        >
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input single-checkbox"
                                            name="dp_kriteria_dpjp"
                                            value="0"
                                        >
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <small class="text-muted">
                        Bila salah satu jawaban "Ya" dari kriteria pemulangan di atas,
                        maka dianggap pemulangan kompleks.
                    </small>

                </div>

            </div>


            {{-- ======================================================
                EDUKASI KESEHATAN
            ======================================================= --}}
            <div class="col-md-12 mb-3">

                <h4 class="text-danger">
                    EDUKASI KESEHATAN
                </h4>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="text-center">
                            <tr>
                                <th style="width: 70px;">Pilih</th>
                                <th>Edukasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_edukasi_jadwal_kontrol"
                                        value="1"
                                    >
                                </td>
                                <td>Jadwal Kontrol</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_edukasi_jadwal_kontrol_text"
                                        placeholder="Jadwal kontrol"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_edukasi_lab"
                                        value="1"
                                    >
                                </td>
                                <td>Pemeriksaan Laboratorium Lanjutan</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_edukasi_lab_text"
                                        placeholder="Keterangan pemeriksaan laboratorium"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_edukasi_obat"
                                        value="1"
                                    >
                                </td>
                                <td>
                                    Pengobatan/obat-obatan yang sudah diresepkan
                                    untuk lanjutan di rumah
                                </td>
                                <td>
                                    <textarea
                                        class="form-control"
                                        name="dp_edukasi_obat_text"
                                        rows="2"
                                        placeholder="Nama obat / aturan penggunaan"
                                    ></textarea>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- ======================================================
                RINCIAN PEMULANGAN
            ======================================================= --}}
            <div class="col-md-12 mb-3">

                <h4 class="text-danger">
                    RINCIAN PEMULANGAN
                </h4>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <tbody>

                            <tr>
                                <td style="width: 50px;" class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_rincian_tanggal"
                                        value="1"
                                    >
                                </td>

                                <td style="width: 300px;">
                                    Tanggal pemulangan
                                </td>

                                <td>
                                    <input
                                        type="date"
                                        class="form-control"
                                        name="dp_tanggal_pulang"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_rincian_pendamping"
                                        value="1"
                                    >
                                </td>

                                <td>
                                    Pendamping saat keluar dari Rumah Sakit
                                </td>

                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_pendamping"
                                        placeholder="Nama / hubungan pendamping"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_rincian_transportasi"
                                        value="1"
                                    >
                                </td>

                                <td>
                                    Transportasi yang digunakan saat keluar RS
                                </td>

                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_transportasi"
                                        placeholder="Jenis transportasi"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_rincian_resume"
                                        value="1"
                                    >
                                </td>

                                <td>
                                    Resume Pasien Pulang
                                </td>

                                <td>
                                    <textarea
                                        class="form-control"
                                        name="dp_resume"
                                        rows="2"
                                        placeholder="Keterangan resume pasien pulang"
                                    ></textarea>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- ======================================================
                MANAJEMEN DISCHARGE PLANNING
            ======================================================= --}}
            <div class="col-md-12 mb-3">

                <h4 class="text-danger">
                    MANAJEMEN DISCHARGE PERENCANAAN PEMULANGAN
                </h4>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="text-center">
                            <tr>
                                <th style="width: 70px;">Pilih</th>
                                <th>Rencana Pemulangan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_perawatan_diri"
                                        value="1"
                                    >
                                </td>
                                <td>Perawatan diri (mandi, bab, bak)</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_perawatan_diri_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_obat"
                                        value="1"
                                    >
                                </td>
                                <td>Pemantauan pemberian obat</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_obat_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_diet"
                                        value="1"
                                    >
                                </td>
                                <td>Pemantauan diet</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_diet_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_luka"
                                        value="1"
                                    >
                                </td>
                                <td>Perawatan luka</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_luka_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_latihan"
                                        value="1"
                                    >
                                </td>
                                <td>Latihan fisik lanjutan</td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_latihan_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_tenaga_khusus"
                                        value="1"
                                    >
                                </td>
                                <td>
                                    Pendampingan tenaga khusus di rumah
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_tenaga_khusus_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_homecare"
                                        value="1"
                                    >
                                </td>
                                <td>
                                    Bantuan medis/perawatan di rumah
                                    (home care)
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_homecare_text"
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_aktivitas"
                                        value="1"
                                    >
                                </td>
                                <td>
                                    Bantuan untuk melakukan aktifitas
                                    (kursi roda, alat bantu jalan)
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_aktivitas_text"
                                    >
                                </td>
                            </tr>

                            {{-- PERAWATAN LANJUTAN --}}
                            <tr>
                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_perawatan_lanjutan"
                                        value="1"
                                    >
                                </td>

                                <td>
                                    <strong>Perawatan lanjutan ke:</strong>
                                </td>

                                <td>

                                    <div class="row g-2">

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input single-checkbox"
                                                    name="dp_lanjutan_ke"
                                                    value="1"
                                                    id="dp_lanjutan_puskesmas"
                                                >
                                                <label
                                                    class="form-check-label"
                                                    for="dp_lanjutan_puskesmas"
                                                >
                                                    Puskesmas
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input single-checkbox"
                                                    name="dp_lanjutan_ke"
                                                    value="2"
                                                    id="dp_lanjutan_dokter"
                                                >
                                                <label
                                                    class="form-check-label"
                                                    for="dp_lanjutan_dokter"
                                                >
                                                    Dokter
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input single-checkbox"
                                                    name="dp_lanjutan_ke"
                                                    value="3"
                                                    id="dp_lanjutan_perawat"
                                                >
                                                <label
                                                    class="form-check-label"
                                                    for="dp_lanjutan_perawat"
                                                >
                                                    Perawat
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input single-checkbox"
                                                    name="dp_lanjutan_ke"
                                                    value="4"
                                                    id="dp_lanjutan_fisioterapi"
                                                >
                                                <label
                                                    class="form-check-label"
                                                    for="dp_lanjutan_fisioterapi"
                                                >
                                                    Fisioterapi
                                                </label>
                                            </div>
                                        </div>

                                        {{-- KETERANGAN --}}
                                        <div class="col-md-12 mt-2">

                                            <input
                                                type="text"
                                                class="form-control"
                                                name="dp_lanjutan_ke_keterangan"
                                                id="dp_lanjutan_ke_keterangan"
                                                placeholder="Keterangan, misalnya nama dokter / nama puskesmas / nama perawat / tempat fisioterapi"
                                            >

                                        </div>

                                    </div>

                                </td>

                            </tr>

                            {{-- LAIN-LAIN --}}
                            <tr>

                                <td class="text-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="dp_manajemen_lain"
                                        value="1"
                                    >
                                </td>

                                <td>
                                    Lain-lain
                                </td>

                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="dp_manajemen_lain_text"
                                        placeholder="Keterangan lainnya"
                                    >
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    'use strict';

    const $form = $('#form_discharge_planning');

    let isDataLoading = false;
    let isDataSaving = false;

    function setDefaultTanggalJam() {

        const $tanggal = $form.find('[name="dp_tanggal"]');
        const $jam = $form.find('[name="dp_jam"]');

        if (!$tanggal.length || !$jam.length) {
            return;
        }

        const now = new Date();

        const tanggal =
            now.getFullYear() +
            '-' +
            String(now.getMonth() + 1).padStart(2, '0') +
            '-' +
            String(now.getDate()).padStart(2, '0');

        const jam =
            String(now.getHours()).padStart(2, '0') +
            ':' +
            String(now.getMinutes()).padStart(2, '0');

        // Hanya default jika kosong
        if (!$tanggal.val()) {
            $tanggal.val(tanggal);
        }

        if (!$jam.val()) {
            $jam.val(jam);
        }

        $tanggal.prop('readonly', true);
        $jam.prop('readonly', true);
    }
    // ==========================================================
    // GET DATA
    // ==========================================================

    function getData() {

        if (!$form.length) {
            console.warn('Form Discharge Planning tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({

            url: `/api/v2/emr/form/lain/dischargeplanning/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const dp = res.data;

                if (!dp) {
                    return;
                }


                // ==================================================
                // TANGGAL DAN JAM
                // ==================================================

                if (dp.TANGGAL_DP) {
                    $form
                        .find('[name="dp_tanggal"]')
                        .val(dp.TANGGAL_DP);
                }

                if (dp.JAM_DP) {
                    $form
                        .find('[name="dp_jam"]')
                        .val(String(dp.JAM_DP).substring(0, 5));
                }

                // Pastikan tetap readonly
                $form
                    .find('[name="dp_tanggal"], [name="dp_jam"]')
                    .prop('readonly', true);
                // ==================================================
                // KRITERIA PEMULANGAN
                // ==================================================

                setCheckboxValue(
                    'dp_kriteria_umur',
                    dp.KRITERIA_UMUR
                );

                setCheckboxValue(
                    'dp_kriteria_mobilitas',
                    dp.KRITERIA_MOBILITAS
                );

                setCheckboxValue(
                    'dp_kriteria_perawatan',
                    dp.KRITERIA_PERAWATAN
                );

                setCheckboxValue(
                    'dp_kriteria_bantuan',
                    dp.KRITERIA_BANTUAN
                );

                setCheckboxValue(
                    'dp_kriteria_gizi',
                    dp.KRITERIA_GIZI
                );

                setCheckboxValue(
                    'dp_kriteria_diabetes',
                    dp.KRITERIA_DIABETES
                );

                setCheckboxValue(
                    'dp_kriteria_icu',
                    dp.KRITERIA_ICU
                );

                setCheckboxValue(
                    'dp_kriteria_dpjp',
                    dp.KRITERIA_DPJP
                );


                // ==================================================
                // EDUKASI
                // ==================================================

                setCheckboxValue(
                    'dp_edukasi_jadwal_kontrol',
                    dp.EDUKASI_JADWAL_KONTROL
                );

                setCheckboxValue(
                    'dp_edukasi_lab',
                    dp.EDUKASI_LAB
                );

                setCheckboxValue(
                    'dp_edukasi_obat',
                    dp.EDUKASI_OBAT
                );

                setValue(
                    'dp_edukasi_jadwal_kontrol_text',
                    dp.EDUKASI_JADWAL_KONTROL_TEXT
                );

                setValue(
                    'dp_edukasi_lab_text',
                    dp.EDUKASI_LAB_TEXT
                );

                setValue(
                    'dp_edukasi_obat_text',
                    dp.EDUKASI_OBAT_TEXT
                );


                // ==================================================
                // RINCIAN PEMULANGAN
                // ==================================================

                setCheckboxValue(
                    'dp_rincian_tanggal',
                    dp.RINCIAN_TANGGAL
                );

                setCheckboxValue(
                    'dp_rincian_pendamping',
                    dp.RINCIAN_PENDAMPING
                );

                setCheckboxValue(
                    'dp_rincian_transportasi',
                    dp.RINCIAN_TRANSPORTASI
                );

                setCheckboxValue(
                    'dp_rincian_resume',
                    dp.RINCIAN_RESUME
                );

                setValue(
                    'dp_tanggal_pulang',
                    dp.TANGGAL_PULANG
                );

                setValue(
                    'dp_pendamping',
                    dp.PENDAMPING
                );

                setValue(
                    'dp_transportasi',
                    dp.TRANSPORTASI
                );

                setValue(
                    'dp_resume',
                    dp.RESUME
                );


                // ==================================================
                // MANAJEMEN
                // ==================================================

                setCheckboxValue(
                    'dp_manajemen_perawatan_diri',
                    dp.MANAJEMEN_PERAWATAN_DIRI
                );

                setCheckboxValue(
                    'dp_manajemen_obat',
                    dp.MANAJEMEN_OBAT
                );

                setCheckboxValue(
                    'dp_manajemen_diet',
                    dp.MANAJEMEN_DIET
                );

                setCheckboxValue(
                    'dp_manajemen_luka',
                    dp.MANAJEMEN_LUKA
                );

                setCheckboxValue(
                    'dp_manajemen_latihan',
                    dp.MANAJEMEN_LATIHAN
                );

                setCheckboxValue(
                    'dp_manajemen_tenaga_khusus',
                    dp.MANAJEMEN_TENAGA_KHUSUS
                );

                setCheckboxValue(
                    'dp_manajemen_homecare',
                    dp.MANAJEMEN_HOMECARE
                );

                setCheckboxValue(
                    'dp_manajemen_aktivitas',
                    dp.MANAJEMEN_AKTIVITAS
                );

                setCheckboxValue(
                    'dp_manajemen_lain',
                    dp.MANAJEMEN_LAIN
                );


                setValue(
                    'dp_manajemen_perawatan_diri_text',
                    dp.MANAJEMEN_PERAWATAN_DIRI_TEXT
                );

                setValue(
                    'dp_manajemen_obat_text',
                    dp.MANAJEMEN_OBAT_TEXT
                );

                setValue(
                    'dp_manajemen_diet_text',
                    dp.MANAJEMEN_DIET_TEXT
                );

                setValue(
                    'dp_manajemen_luka_text',
                    dp.MANAJEMEN_LUKA_TEXT
                );

                setValue(
                    'dp_manajemen_latihan_text',
                    dp.MANAJEMEN_LATIHAN_TEXT
                );

                setValue(
                    'dp_manajemen_tenaga_khusus_text',
                    dp.MANAJEMEN_TENAGA_KHUSUS_TEXT
                );

                setValue(
                    'dp_manajemen_homecare_text',
                    dp.MANAJEMEN_HOMECARE_TEXT
                );

                setValue(
                    'dp_manajemen_aktivitas_text',
                    dp.MANAJEMEN_AKTIVITAS_TEXT
                );

                setValue(
                    'dp_manajemen_lain_text',
                    dp.MANAJEMEN_LAIN_TEXT
                );


                setCheckboxValue(
                    'dp_perawatan_lanjutan',
                    dp.PERAWATAN_LANJUTAN
                );

                setCheckboxValue(
                    'dp_lanjutan_ke',
                    dp.LANJUTAN_KE
                );

                setValue(
                    'dp_lanjutan_ke_keterangan',
                    dp.LANJUTAN_KE_KETERANGAN
                );

            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

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


        const $formUtama =
            $('#form_discharge_planning_utama');


        if (!$formUtama.length) {

            console.warn(
                'Form utama Discharge Planning tidak ditemukan.'
            );

            return;

        }


        const data = getFormDataByName(
            $formUtama,
            {
                NOKUNJ: kunjungan
            }
        );


        isDataSaving = true;


        $.ajax({

            url:
                `/api/v2/emr/form/lain/dischargeplanning/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            },

            success: function (res) {

                // Data berhasil disimpan

            },

            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message =
                        Object
                            .values(xhr.responseJSON.errors)
                            .flat()
                            .join('<br>');

                }
                else if (xhr.responseJSON?.message) {

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

                isDataSaving = false;

            }

        });

    }


    // ==========================================================
    // HELPER SET VALUE
    // ==========================================================

    function setValue(name, value) {

        if (
            value !== null &&
            value !== undefined &&
            value !== ''
        ) {

            FormHelper.setValue(
                $form,
                name,
                value
            );

        }

    }


    // ==========================================================
    // CHECKBOX
    // ==========================================================

    function setCheckboxValue(name, value) {

        const $checkboxes =
            $('input[type="checkbox"][name="' + name + '"]');


        $checkboxes.prop('checked', false);


        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return;
        }


        $checkboxes
            .filter(function () {

                return String($(this).val()) ===
                    String(value);

            })
            .prop('checked', true);

    }


    // ==========================================================
    // INIT
    // ==========================================================

    $(function () {

        if (!$form.length) {
            return;
        }


        // ----------------------------------------------
        // SINGLE CHECKBOX
        // ----------------------------------------------

        $form.on(
            'change',
            '.single-checkbox',
            function () {

                if (!this.checked) {
                    return;
                }


                const $otherCheckboxes =
                    $form
                        .find(
                            'input.single-checkbox[name="' +
                            this.name +
                            '"]'
                        )
                        .not(this);


                $otherCheckboxes.each(function () {

                    if (this.checked) {

                        this.checked = false;

                        $(this).trigger('change');

                    }

                });

            }
        );


        // ----------------------------------------------
        // LOAD DATA
        // ----------------------------------------------

        setDefaultTanggalJam();
        
        getData();


        // ----------------------------------------------
        // AUTO SAVE BLUR
        // ----------------------------------------------

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


        // ----------------------------------------------
        // AUTO SAVE CHANGE
        // ----------------------------------------------

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
