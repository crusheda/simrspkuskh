<form id="form_penilaian_awal_bayi">
    <div class="col-md-12 mb-3">
        <div>
            <h6 class="fw-bold mb-1">
                PENILAIAN AWAL BAYI BARU LAHIR
            </h6>
        </div>
        {{-- Bayi Bugar --}}
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="apgar_status_bayi" id="apgar_bayi_bugar" value="bugar">
            <label class="form-check-label" for="apgar_bayi_bugar">
                Bayi Bugar
            </label>
        </div>
        <div id="section_apgar">
            <div class="mb-2">
                <h6 class="fw-bold mb-1">
                    PENILAIAN APGAR SCORE
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center align-middle mb-3" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th style="width: 16%;">0</th>
                            <th style="width: 16%;">1</th>
                            <th style="width: 16%;">2</th>
                            <th style="width: 16%;">APGAR SCORE</th>
                            <th style="width: 12%;">1 Menit</th>
                            <th style="width: 12%;">5 Menit</th>
                            <th style="width: 12%;">10 Menit</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DENYUT JANTUNG --}}
                        <tr>
                            <td>Tidak ada</td>
                            <td>&lt; 100</td>
                            <td>&gt; 100</td>
    
                            <td class="text-start">
                                Denyut Jantung
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_1_menit_denyut" data-waktu="1">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_5_menit_denyut" data-waktu="5">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_10_menit_denyut" data-waktu="10">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                        </tr>
                        {{-- PERNAFASAN --}}
                        <tr>
                            <td>Tidak teratur</td>
                            <td>Tidak teratur</td>
                            <td>Baik</td>
                            <td class="text-start">
                                Pernapasan
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_1_menit_pernafasan" data-waktu="1">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_5_menit_pernafasan" data-waktu="5">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_10_menit_pernafasan" data-waktu="10">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                        </tr>
                        {{-- TONUS OTOT --}}
                        <tr>
                            <td>Lemah</td>
                            <td>Sedang</td>
                            <td>Baik</td>
                            <td class="text-start">
                                Tonus Otot
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_1_menit_tonus" data-waktu="1">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_5_menit_tonus" data-waktu="5">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_10_menit_tonus" data-waktu="10">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                        </tr>
                        {{-- PEKA RANGSANG --}}
                        <tr>
                            <td>Tidak ada</td>
                            <td>Meringis</td>
                            <td>Menangis</td>
                            <td class="text-start">
                                Peka Rangsang
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_1_menit_rangsang" data-waktu="1">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_5_menit_rangsang" data-waktu="5">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_10_menit_rangsang" data-waktu="10">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                        </tr>
                        {{-- WARNA --}}
                        <tr>
                            <td>Biru/Putih</td>
                            <td>Ujung-ujung Biru</td>
                            <td>Merah Jambu</td>
                            <td class="text-start">
                                Warna
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_1_menit_warna" data-waktu="1">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_5_menit_warna" data-waktu="5">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm apgar-score" name="apgar_10_menit_warna" data-waktu="10">
                                    <option value="">-</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                        </tr>
                        {{-- TOTAL --}}
                        <tr>
                            <td colspan="4" class="text-end fw-bold">
                                TOTAL
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm fw-bold text-center" name="apgar_total_1_menit" id="apgar_total_1_menit" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm fw-bold text-center" name="apgar_total_5_menit" id="apgar_total_5_menit" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm fw-bold text-center" name="apgar_total_10_menit" id="apgar_total_10_menit" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- BAYI TIDAK BUGAR --}}
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="apgar_status_bayi" id="apgar_bayi_tidak_bugar" value="tidak_bugar">
            <label class="form-check-label" for="apgar_bayi_tidak_bugar">
                Bayi Tidak Bugar
            </label>
        </div>
        <div id="section_resusitasi">
            {{-- RESUSITASI --}}
            <div class="row mb-2">
                <div class="col-md-12">
                    <h6 class="fw-bold mb-1">
                        RESUSITASI
                    </h6>
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="apgar_resusitasi" value="dilakukan" id="resusitasi_dilakukan">
                            <label class="form-check-label" for="resusitasi_dilakukan">
                                Dilakukan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="apgar_resusitasi" value="tidak_dilakukan" id="resusitasi_tidak_dilakukan">
                            <label class="form-check-label" for="resusitasi_tidak_dilakukan">
                                Tidak dilakukan
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            {{-- DETAIL RESUSITASI --}}
            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-md-6">
                    {{-- Langkah awal --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" name="apgar_langkah_awal" value="1" id="apgar_langkah_awal">
                            <label class="form-check-label" for="apgar_langkah_awal">
                                Langkah awal selama
                            </label>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="apgar_langkah_awal_detik" style="max-width: 80px;">
                        <span>detik</span>
                    </div>
                    {{-- VTP --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" name="apgar_vtp" value="1" id="apgar_vtp">
                            <label class="form-check-label" for="apgar_vtp">
                                VTP selama
                            </label>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="apgar_vtp_detik" style="max-width: 80px;">
                        <span>detik</span>
                    </div>
                    {{-- Kompresi dada --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" name="apgar_kompresi_dada" value="1" id="apgar_kompresi_dada">
                            <label class="form-check-label" for="apgar_kompresi_dada">
                                Kompresi dada, selama
                            </label>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="apgar_kompresi_dada_detik" style="max-width: 80px;">
                        <span>detik</span>
                    </div>
                </div>
                {{-- Kolom kanan --}}
                <div class="col-md-6">
                    {{-- ETT --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="apgar_ett" value="1" id="apgar_ett">
                        <label class="form-check-label" for="apgar_ett">
                            Pemasangan Endotracheal Tube
                        </label>
                    </div>
                    {{-- Resusitasi dihentikan --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" name="apgar_resusitasi_dihentikan" value="1" id="apgar_resusitasi_dihentikan">
                            <label class="form-check-label" for="apgar_resusitasi_dihentikan">
                                Resusitasi dihentikan setelah
                            </label>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="apgar_resusitasi_dihentikan_menit" style="max-width: 80px;">
                        <span>mnt</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- TANGGAL / JAM / BB SEKARANG --}}
        <div class="row align-items-center mt-3">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0">Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="apgar_tanggal" style="max-width: 150px;">
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0">Jam</label>
                    <input type="time" class="form-control form-control-sm" name="apgar_jam" style="max-width: 110px;">
                    <span>WIB</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0">BB Sekarang</label>
                    <input type="number" class="form-control form-control-sm" name="apgar_bb_sekarang" style="max-width: 120px;">
                    <span>gram</span>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
(function () {

    'use strict';

    let isLoadingApgar = false;

    // ==========================================================
    // ELEMENT
    // ==========================================================

    const $form = $('#form_penilaian_awal_bayi');
    const $sectionApgar = $('#section_apgar');
    const $sectionResusitasi = $('#section_resusitasi');


    // ==========================================================
    // HIDE SEMUA SECTION
    // ==========================================================

    function hideAllSection() {

        $sectionApgar.hide();
        $sectionResusitasi.hide();

    }


    // ==========================================================
    // RESET APGAR
    // ==========================================================

    function resetApgar() {

        $form.find(
            'select[name^="apgar_1_menit_"], ' +
            'select[name^="apgar_5_menit_"], ' +
            'select[name^="apgar_10_menit_"]'
        ).val('');

        $form.find('[name="apgar_total_1_menit"]').val('');
        $form.find('[name="apgar_total_5_menit"]').val('');
        $form.find('[name="apgar_total_10_menit"]').val('');

    }


    // ==========================================================
    // RESET RESUSITASI
    // ==========================================================

    function resetResusitasi() {

        $form.find('[name="apgar_resusitasi"]')
            .prop('checked', false);

        $form.find('[name="apgar_langkah_awal"]')
            .prop('checked', false);

        $form.find('[name="apgar_langkah_awal_detik"]')
            .val('');

        $form.find('[name="apgar_vtp"]')
            .prop('checked', false);

        $form.find('[name="apgar_vtp_detik"]')
            .val('');

        $form.find('[name="apgar_kompresi_dada"]')
            .prop('checked', false);

        $form.find('[name="apgar_kompresi_dada_detik"]')
            .val('');

        $form.find('[name="apgar_ett"]')
            .prop('checked', false);

        $form.find('[name="apgar_resusitasi_dihentikan"]')
            .prop('checked', false);

        $form.find('[name="apgar_resusitasi_dihentikan_menit"]')
            .val('');

    }


    // ==========================================================
    // UPDATE STATUS BAYI
    // ==========================================================

    function updateStatusBayi(status, resetLawan = true) {

        console.log('Update status bayi:', status);

        if (status === 'bugar') {

            $sectionApgar.show();
            $sectionResusitasi.hide();

            if (resetLawan) {
                resetResusitasi();
            }

        }

        else if (status === 'tidak_bugar') {

            $sectionApgar.hide();
            $sectionResusitasi.show();

            if (resetLawan) {
                resetApgar();
            }

        }

        else {

            hideAllSection();

        }

    }


    // ==========================================================
    // HITUNG APGAR
    // ==========================================================

    function hitungApgar(waktu) {

        let total = 0;
        let adaNilai = false;

        $form
            .find(`select[name^="apgar_${waktu}_menit_"]`)
            .each(function () {

                const value = $(this).val();

                if (value !== '' && value !== null) {

                    total += parseInt(value, 10);
                    adaNilai = true;

                }

            });

        $form
            .find(`[name="apgar_total_${waktu}_menit"]`)
            .val(
                adaNilai
                    ? total
                    : ''
            );

    }


    // ==========================================================
    // HITUNG SEMUA APGAR
    // ==========================================================

    function hitungSemuaApgar() {

        hitungApgar(1);
        hitungApgar(5);
        hitungApgar(10);

    }


    // ==========================================================
    // GET DATA
    // ==========================================================

    function getPenilaianAwalBayi() {

        isLoadingApgar = true;

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/penilaian_awal_bayi/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                console.log(
                    'GET Penilaian Awal Bayi:',
                    res
                );


                const data =
                    res.data || null;


                if (!data) {

                    hideAllSection();

                    isLoadingApgar = false;

                    return;

                }


                // ==================================================
                // STATUS BAYI
                // ==================================================

                const statusBayi =
                    data.APGAR_STATUS_BAYI ?? '';


                console.log(
                    'Status Bayi dari DB:',
                    statusBayi
                );


                // ==================================================
                // RADIO STATUS BAYI
                // ==================================================

                $form
                    .find('[name="apgar_status_bayi"]')
                    .prop('checked', false);


                if (statusBayi) {

                    $form
                        .find(
                            `[name="apgar_status_bayi"][value="${statusBayi}"]`
                        )
                        .prop('checked', true);

                }


                // ==================================================
                // APGAR 1 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_1_menit_denyut"]')
                    .val(data.APGAR_1_MENIT_DENYUT ?? '');

                $form
                    .find('[name="apgar_1_menit_pernafasan"]')
                    .val(data.APGAR_1_MENIT_PERNAFASAN ?? '');

                $form
                    .find('[name="apgar_1_menit_tonus"]')
                    .val(data.APGAR_1_MENIT_TONUS ?? '');

                $form
                    .find('[name="apgar_1_menit_rangsang"]')
                    .val(data.APGAR_1_MENIT_RANGSANG ?? '');

                $form
                    .find('[name="apgar_1_menit_warna"]')
                    .val(data.APGAR_1_MENIT_WARNA ?? '');


                // ==================================================
                // APGAR 5 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_5_menit_denyut"]')
                    .val(data.APGAR_5_MENIT_DENYUT ?? '');

                $form
                    .find('[name="apgar_5_menit_pernafasan"]')
                    .val(data.APGAR_5_MENIT_PERNAFASAN ?? '');

                $form
                    .find('[name="apgar_5_menit_tonus"]')
                    .val(data.APGAR_5_MENIT_TONUS ?? '');

                $form
                    .find('[name="apgar_5_menit_rangsang"]')
                    .val(data.APGAR_5_MENIT_RANGSANG ?? '');

                $form
                    .find('[name="apgar_5_menit_warna"]')
                    .val(data.APGAR_5_MENIT_WARNA ?? '');


                // ==================================================
                // APGAR 10 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_10_menit_denyut"]')
                    .val(data.APGAR_10_MENIT_DENYUT ?? '');

                $form
                    .find('[name="apgar_10_menit_pernafasan"]')
                    .val(data.APGAR_10_MENIT_PERNAFASAN ?? '');

                $form
                    .find('[name="apgar_10_menit_tonus"]')
                    .val(data.APGAR_10_MENIT_TONUS ?? '');

                $form
                    .find('[name="apgar_10_menit_rangsang"]')
                    .val(data.APGAR_10_MENIT_RANGSANG ?? '');

                $form
                    .find('[name="apgar_10_menit_warna"]')
                    .val(data.APGAR_10_MENIT_WARNA ?? '');


                // ==================================================
                // TOTAL APGAR
                // ==================================================

                $form
                    .find('[name="apgar_total_1_menit"]')
                    .val(data.APGAR_TOTAL_1_MENIT ?? '');

                $form
                    .find('[name="apgar_total_5_menit"]')
                    .val(data.APGAR_TOTAL_5_MENIT ?? '');

                $form
                    .find('[name="apgar_total_10_menit"]')
                    .val(data.APGAR_TOTAL_10_MENIT ?? '');


                // ==================================================
                // RESUSITASI
                // ==================================================

                $form
                    .find('[name="apgar_resusitasi"]')
                    .prop('checked', false);


                if (data.APGAR_RESUSITASI) {

                    $form
                        .find(
                            `[name="apgar_resusitasi"][value="${data.APGAR_RESUSITASI}"]`
                        )
                        .prop('checked', true);

                }


                // ==================================================
                // LANGKAH AWAL
                // ==================================================

                $form
                    .find('[name="apgar_langkah_awal"]')
                    .prop(
                        'checked',
                        data.APGAR_LANGKAH_AWAL == 1
                    );

                $form
                    .find('[name="apgar_langkah_awal_detik"]')
                    .val(
                        data.APGAR_LANGKAH_AWAL_DETIK ?? ''
                    );


                // ==================================================
                // VTP
                // ==================================================

                $form
                    .find('[name="apgar_vtp"]')
                    .prop(
                        'checked',
                        data.APGAR_VTP == 1
                    );

                $form
                    .find('[name="apgar_vtp_detik"]')
                    .val(
                        data.APGAR_VTP_DETIK ?? ''
                    );


                // ==================================================
                // KOMPRESI DADA
                // ==================================================

                $form
                    .find('[name="apgar_kompresi_dada"]')
                    .prop(
                        'checked',
                        data.APGAR_KOMPRESI_DADA == 1
                    );

                $form
                    .find('[name="apgar_kompresi_dada_detik"]')
                    .val(
                        data.APGAR_KOMPRESI_DADA_DETIK ?? ''
                    );


                // ==================================================
                // ETT
                // ==================================================

                $form
                    .find('[name="apgar_ett"]')
                    .prop(
                        'checked',
                        data.APGAR_ETT == 1
                    );


                // ==================================================
                // RESUSITASI DIHENTIKAN
                // ==================================================

                $form
                    .find('[name="apgar_resusitasi_dihentikan"]')
                    .prop(
                        'checked',
                        data.APGAR_RESUSITASI_DIHENTIKAN == 1
                    );

                $form
                    .find('[name="apgar_resusitasi_dihentikan_menit"]')
                    .val(
                        data.APGAR_RESUSITASI_DIHENTIKAN_MENIT ?? ''
                    );


                // ==================================================
                // TANGGAL / JAM / BB
                // ==================================================

                $form
                    .find('[name="apgar_tanggal"]')
                    .val(
                        data.APGAR_TANGGAL ?? ''
                    );

                $form
                    .find('[name="apgar_jam"]')
                    .val(
                        data.APGAR_JAM ?? ''
                    );

                $form
                    .find('[name="apgar_bb_sekarang"]')
                    .val(
                        data.APGAR_BB_SEKARANG ?? ''
                    );


                // ==================================================
                // TAMPILKAN SESUAI STATUS DB
                //
                // false = JANGAN RESET DATA
                // ==================================================

                updateStatusBayi(
                    statusBayi,
                    false
                );


                // ==================================================
                // HITUNG ULANG APGAR
                // ==================================================

                if (statusBayi === 'bugar') {

                    hitungSemuaApgar();

                }


                console.log(
                    'Status radio:',
                    $form
                        .find(
                            '[name="apgar_status_bayi"]:checked'
                        )
                        .val()
                );


                isLoadingApgar = false;

            },

            error: function (xhr) {

                console.error(
                    'GET Penilaian Awal Bayi:',
                    xhr.responseText || xhr.statusText
                );

                hideAllSection();

                isLoadingApgar = false;

            }

        });

    }


    // ==========================================================
    // SIMPAN
    // ==========================================================

    function simpanPenilaianAwalBayi() {

        if (isLoadingApgar) {
            return;
        }


        // Hitung total APGAR
        hitungSemuaApgar();


        // Ambil data
        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );


        console.log(
            'Data Penilaian Awal Bayi:',
            data
        );


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/penilaian_awal_bayi/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            },

            success: function (res) {

                console.log(
                    'Penilaian Awal Bayi berhasil disimpan.',
                    res
                );

            },

            error: function (xhr) {

                console.error(
                    'Gagal simpan:',
                    xhr.responseText
                );


                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    message =
                        Object
                            .values(xhr.responseJSON.errors)
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

            }

        });

    }


    // ==========================================================
    // DOCUMENT READY
    // ==========================================================

    $(function () {

        hideAllSection();


        // ======================================================
        // GET DATA
        // ======================================================

        getPenilaianAwalBayi();


        // ======================================================
        // STATUS BAYI
        // ======================================================

        $form.on(
            'change',
            '[name="apgar_status_bayi"]',
            function () {

                if (isLoadingApgar) {
                    return;
                }


                const status =
                    $(this).val();


                console.log(
                    'Status bayi dipilih:',
                    status
                );


                // ==================================================
                // BUGAR
                // ==================================================

                if (status === 'bugar') {

                    $sectionApgar.show();
                    $sectionResusitasi.hide();

                    // Kosongkan seluruh resusitasi
                    resetResusitasi();

                    // Simpan
                    simpanPenilaianAwalBayi();

                }


                // ==================================================
                // TIDAK BUGAR
                // ==================================================

                else if (status === 'tidak_bugar') {

                    $sectionApgar.hide();
                    $sectionResusitasi.show();

                    // Kosongkan seluruh APGAR
                    resetApgar();

                    // Simpan
                    simpanPenilaianAwalBayi();

                }

            }
        );


        // ======================================================
        // APGAR SCORE
        // ======================================================

        $form.on(
            'change',
            '.apgar-score',
            function () {

                if (isLoadingApgar) {
                    return;
                }


                hitungSemuaApgar();

                simpanPenilaianAwalBayi();

            }
        );


        // ======================================================
        // INPUT
        // ======================================================

        $form.on(
            'blur',
            'input[type="text"], ' +
            'input[type="number"], ' +
            'input[type="date"], ' +
            'input[type="time"]',
            function () {

                if (isLoadingApgar) {
                    return;
                }


                simpanPenilaianAwalBayi();

            }
        );


        // ======================================================
        // RADIO / CHECKBOX RESUSITASI
        // ======================================================

        $form.on(
            'change',
            'input[type="radio"], input[type="checkbox"]',
            function () {

                if (isLoadingApgar) {
                    return;
                }


                // Status bayi sudah memiliki handler sendiri
                if (
                    $(this).attr('name') ===
                    'apgar_status_bayi'
                ) {

                    return;

                }


                simpanPenilaianAwalBayi();

            }
        );

    });

})();
</script>