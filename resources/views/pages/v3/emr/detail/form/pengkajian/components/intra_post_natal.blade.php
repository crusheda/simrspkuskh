<div class="col-md-12 mb-3" id="form_natal">

    {{-- INTRA NATAL --}}
    <div class="fw-bold mb-1">
        <h6 class="fw-bold mb-1">
            INTRA NATAL
        </h6>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Umur Kehamilan</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="umur" value="1">
                    <label class="form-check-label">
                        Kurang Bulan
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="umur" value="2">
                    <label class="form-check-label">
                        Cukup Bulan
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="umur" value="3">
                    <label class="form-check-label">
                        Sesuai Kelahiran
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="umur" value="4">
                    <label class="form-check-label">
                        Serotinus
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Jenis Persalinan</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="jenis_per" value="1">
                    <label class="form-check-label">
                        Spontan
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="jenis_per" value="2">
                    <label class="form-check-label">
                        Forcep
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="jenis_per" value="3">
                    <label class="form-check-label">
                        VE
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="jenis_per" value="4">
                    <label class="form-check-label">
                        SC
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Penyulit Persalinan</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="penyulit" value="1">
                    <label class="form-check-label">
                        Preskep
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="penyulit" value="2">
                    <label class="form-check-label">
                        Lintang
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="penyulit" value="3">
                    <label class="form-check-label">
                        Gemelli
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="penyulit" value="4">
                    <input type="text" class="form-control form-control-sm" name="penyulit_lain" id="penyulit_lain" placeholder="Lainnya">
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Komplikasi Persalinan</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="komplikasi" value="1">
                    <label class="form-check-label">
                        KPD
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="komplikasi" value="2">
                    <label class="form-check-label">
                        Eklamsia/Preeklamsia
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="komplikasi" value="3">
                    <label class="form-check-label">
                        Solusio Plasenta
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="komplikasi" value="4">
                    <label class="form-check-label">
                        Placenta Previa
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input single-checkbox" type="checkbox" name="komplikasi" value="5">
                    <input type="text" class="form-control form-control-sm" name="komplikasi_lain" id="komplikasi_lain" placeholder="Lainnya">
                </div>
            </div>
        </div>
    </div>

    {{-- POST NATAL --}}
    <div class="fw-bold mt-2 mb-1">
        <h6 class="fw-bold mb-1">
            POST NATAL
        </h6>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">BBL</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="bbl" >
                <span class="input-group-text">
                    gr
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-0">PBL</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="pbl" >
                <span class="input-group-text">
                    cm
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-0">LK</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="lk" >
                <span class="input-group-text">
                    cm
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-0">LD</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="ld" >
                <span class="input-group-text">
                    cm
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-0">LP</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="lp" >
                <span class="input-group-text">
                    cm
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-0">LILA</label>
            <div class="input-group flex-grow-1">
                <input type="number" class="form-control" name="lila" >
                <span class="input-group-text">
                    cm
                </span>
            </div>
        </div>
    </div>
    <div class="row align-items-center mb-2">
        <div class="col-md-6">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Trauma Lahir</span>
                <div class="form-check">
                    <input class="form-check-input single-checkbox single-checkbox" type="checkbox" name="trauma" value="1">
                    <label class="form-check-label">
                        Tidak Ada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input single-checkbox single-checkbox" type="checkbox" name="trauma" value="2">
                    <label class="form-check-label">
                        Ada
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Usaha Nafas</span>
                <div class="form-check">
                    <input class="form-check-input single-checkbox single-checkbox" type="checkbox" name="nafas" value="1">
                    <label class="form-check-label">
                        Spontan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input single-checkbox single-checkbox" type="checkbox" name="nafas" value="2">
                    <label class="form-check-label">
                        Dengan Bantuan
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div>
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

</div>

<script>
(function () {

    'use strict';

    const $form = $('#form_natal');

    let isLoadingApgar = false;


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

                const data =
                    res.data || null;


                if (!data) {

                    isLoadingApgar = false;

                    return;

                }


                // ==================================================
                // INTRA NATAL
                // ==================================================

                setSingleCheckbox(
                    'umur',
                    data.UMUR_KEHAMILAN
                );


                setSingleCheckbox(
                    'jenis_per',
                    data.JENIS_PERSALINAN
                );


                setSingleCheckbox(
                    'penyulit',
                    data.PENYULIT_PERSALINAN
                );


                setSingleCheckbox(
                    'komplikasi',
                    data.KOMPLIKASI_PERSALINAN
                );


                $form
                    .find('[name="penyulit_lain"]')
                    .val(
                        data.PENYULIT_LAIN ?? ''
                    );


                $form
                    .find('[name="komplikasi_lain"]')
                    .val(
                        data.KOMPLIKASI_LAIN ?? ''
                    );


                // ==================================================
                // POST NATAL
                // ==================================================

                $form.find('[name="bbl"]').val(formatNumber(data.BBL));
                $form.find('[name="pbl"]').val(formatNumber(data.PBL));
                $form.find('[name="lk"]').val(formatNumber(data.LK));
                $form.find('[name="ld"]').val(formatNumber(data.LD));
                $form.find('[name="lp"]').val(formatNumber(data.LP));
                $form.find('[name="lila"]').val(formatNumber(data.LILA));


                setSingleCheckbox(
                    'trauma',
                    data.TRAUMA_LAHIR
                );


                setSingleCheckbox(
                    'nafas',
                    data.USAHA_NAFAS
                );


                // ==================================================
                // STATUS BAYI
                // ==================================================

                setSingleCheckbox(
                    'apgar_status_bayi',
                    data.APGAR_STATUS_BAYI
                );


                // ==================================================
                // APGAR 1 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_1_menit_denyut"]')
                    .val(
                        data.APGAR_1_MENIT_DENYUT ?? ''
                    );


                $form
                    .find('[name="apgar_1_menit_pernafasan"]')
                    .val(
                        data.APGAR_1_MENIT_PERNAFASAN ?? ''
                    );


                $form
                    .find('[name="apgar_1_menit_tonus"]')
                    .val(
                        data.APGAR_1_MENIT_TONUS ?? ''
                    );


                $form
                    .find('[name="apgar_1_menit_rangsang"]')
                    .val(
                        data.APGAR_1_MENIT_RANGSANG ?? ''
                    );


                $form
                    .find('[name="apgar_1_menit_warna"]')
                    .val(
                        data.APGAR_1_MENIT_WARNA ?? ''
                    );


                // ==================================================
                // APGAR 5 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_5_menit_denyut"]')
                    .val(
                        data.APGAR_5_MENIT_DENYUT ?? ''
                    );


                $form
                    .find('[name="apgar_5_menit_pernafasan"]')
                    .val(
                        data.APGAR_5_MENIT_PERNAFASAN ?? ''
                    );


                $form
                    .find('[name="apgar_5_menit_tonus"]')
                    .val(
                        data.APGAR_5_MENIT_TONUS ?? ''
                    );


                $form
                    .find('[name="apgar_5_menit_rangsang"]')
                    .val(
                        data.APGAR_5_MENIT_RANGSANG ?? ''
                    );


                $form
                    .find('[name="apgar_5_menit_warna"]')
                    .val(
                        data.APGAR_5_MENIT_WARNA ?? ''
                    );


                // ==================================================
                // APGAR 10 MENIT
                // ==================================================

                $form
                    .find('[name="apgar_10_menit_denyut"]')
                    .val(
                        data.APGAR_10_MENIT_DENYUT ?? ''
                    );


                $form
                    .find('[name="apgar_10_menit_pernafasan"]')
                    .val(
                        data.APGAR_10_MENIT_PERNAFASAN ?? ''
                    );


                $form
                    .find('[name="apgar_10_menit_tonus"]')
                    .val(
                        data.APGAR_10_MENIT_TONUS ?? ''
                    );


                $form
                    .find('[name="apgar_10_menit_rangsang"]')
                    .val(
                        data.APGAR_10_MENIT_RANGSANG ?? ''
                    );


                $form
                    .find('[name="apgar_10_menit_warna"]')
                    .val(
                        data.APGAR_10_MENIT_WARNA ?? ''
                    );


                // ==================================================
                // TOTAL APGAR
                // ==================================================

                $form
                    .find('[name="apgar_total_1_menit"]')
                    .val(
                        data.APGAR_TOTAL_1_MENIT ?? ''
                    );


                $form
                    .find('[name="apgar_total_5_menit"]')
                    .val(
                        data.APGAR_TOTAL_5_MENIT ?? ''
                    );


                $form
                    .find('[name="apgar_total_10_menit"]')
                    .val(
                        data.APGAR_TOTAL_10_MENIT ?? ''
                    );


                // ==================================================
                // RESUSITASI
                // ==================================================

                setSingleCheckbox(
                    'apgar_resusitasi',
                    data.APGAR_RESUSITASI
                );


                setCheckbox(
                    'apgar_langkah_awal',
                    data.APGAR_LANGKAH_AWAL
                );


                $form
                    .find('[name="apgar_langkah_awal_detik"]')
                    .val(
                        data.APGAR_LANGKAH_AWAL_DETIK ?? ''
                    );


                setCheckbox(
                    'apgar_vtp',
                    data.APGAR_VTP
                );


                $form
                    .find('[name="apgar_vtp_detik"]')
                    .val(
                        data.APGAR_VTP_DETIK ?? ''
                    );


                setCheckbox(
                    'apgar_kompresi_dada',
                    data.APGAR_KOMPRESI_DADA
                );


                $form
                    .find('[name="apgar_kompresi_dada_detik"]')
                    .val(
                        data.APGAR_KOMPRESI_DADA_DETIK ?? ''
                    );


                setCheckbox(
                    'apgar_ett',
                    data.APGAR_ETT
                );


                setCheckbox(
                    'apgar_resusitasi_dihentikan',
                    data.APGAR_RESUSITASI_DIHENTIKAN
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
                // HITUNG ULANG APGAR
                // ==================================================

                hitungSemuaApgar();


                isLoadingApgar = false;

            },


            error: function (xhr) {

                console.error(
                    'GET Penilaian Awal Bayi:',
                    xhr.responseText || xhr.statusText
                );


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


        // ======================================================
        // HITUNG TOTAL APGAR
        // ======================================================

        hitungSemuaApgar();


        // ======================================================
        // AMBIL DATA
        // ======================================================

        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );


        // ======================================================
        // AJAX SIMPAN
        // ======================================================

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
    // SET SINGLE CHECKBOX
    // ==========================================================

    function setSingleCheckbox(name, value) {

        $form
            .find(`input[name="${name}"]`)
            .prop('checked', false);


        if (
            value !== null &&
            value !== undefined &&
            value !== ''
        ) {

            $form
                .find(
                    `input[name="${name}"][value="${value}"]`
                )
                .prop('checked', true);

        }

    }


    // ==========================================================
    // SET CHECKBOX BOOLEAN
    // ==========================================================

    function setCheckbox(name, value) {

        $form
            .find(`[name="${name}"]`)
            .prop(
                'checked',
                parseInt(value, 10) === 1
            );

    }

    function formatNumber(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }

        const num = Number(value);

        return Number.isInteger(num) ? String(num) : String(num);
    }

    // ==========================================================
    // DOCUMENT READY
    // ==========================================================

    $(function () {


        // ======================================================
        // GET DATA
        // ======================================================

        getPenilaianAwalBayi();


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
        // RADIO / CHECKBOX
        // ======================================================

        $form.on(
            'change',
            'input[type="radio"], input[type="checkbox"]',
            function () {

                if (isLoadingApgar) {
                    return;
                }


                simpanPenilaianAwalBayi();

            }
        );

    });

})();
</script>
