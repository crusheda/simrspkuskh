<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">GERIATRI</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold"> Keluhan Utama </label>
                    <input class="form-control form-control" type="text" name="anm_ku" id="anm_ku">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rjg_perawat',
                        'page' => 'perawat',
                        // 'editableFields' => [
                        //     'tv_keu',
                        //     'tv_gcs_e',
                        //     'tv_gcs_v',
                        //     'tv_gcs_m',
                        //     'tv_bb',
                        //     'tv_tb',
                        // ],
                    ]
                )
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjg_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rio_perawat',
                        'metodeNyeri' => ['nrs', 'vas', 'bps']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rjg_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rjg_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rjg_perawat'])
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label fw-bold">
                    Masalah Keperawatan
                </label>
                <div class="row">
                    <!-- Kolom 1 -->
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_1" id="diag_keperawatan_1">
                            <label class="form-check-label">
                                Nyeri
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_2" id="diag_keperawatan_2">
                            <label class="form-check-label">
                                Gangguan Perfusi Cerebral
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_3" id="diag_keperawatan_3">
                            <label class="form-check-label">
                                Cemas
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_4" id="diag_keperawatan_4">
                            <label class="form-check-label">
                                Sensori Persepsi
                            </label>
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_5" id="diag_keperawatan_5">
                            <label class="form-check-label">
                                Hipertermi
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_6" id="diag_keperawatan_6">
                            <label class="form-check-label">
                                Kerusakan Integritas Kulit
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_7" id="diag_keperawatan_7">
                            <label class="form-check-label">
                                Gangguan Perfusi Jaringan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_8" id="diag_keperawatan_8">
                            <label class="form-check-label">
                                Body Image
                            </label>
                        </div>
                    </div>
                    <!-- Kolom 3 -->
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_9" id="diag_keperawatan_9">
                            <label class="form-check-label">
                                Gangguan Mobilitas Fisik
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_10" id="diag_keperawatan_10">
                            <label class="form-check-label">
                                Kurang Pengetahuan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_11" id="diag_keperawatan_11">
                            <label class="form-check-label">
                                Perubahan Nutrisi Kurang dari Kebutuhan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <label class="form-input-label">Masalah Keperawatan Lainnya</label>
                            <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                        </div>
                    </div>
                </div>
                <div class="mt-1">
                    <label class="form-label fw-semibold">
                        Rencana Asuhan Keperawatan
                    </label>
                    <textarea class="form-control" name="rencana_asuhan_keperawatan" id="rencana_asuhan_keperawatan" rows="3" placeholder="Tuliskan rencana asuhan keperawatan..."></textarea>
                </div>
            </div>
            <!-- ========================================================= -->
            <!-- ASSESMEN SINDROM GERIATRI -->
            <!-- ========================================================= -->

            <div class="col-md-12 mb-3">

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

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="geriatri_adl"
                                        id="geriatri_adl_1"
                                        value="1">

                                    <label class="form-check-label"
                                        for="geriatri_adl_1">
                                        Mandiri (20)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="geriatri_adl"
                                        id="geriatri_adl_2"
                                        value="2">

                                    <label class="form-check-label"
                                        for="geriatri_adl_2">
                                        Ketergantungan ringan (12–19)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="geriatri_adl"
                                        id="geriatri_adl_3"
                                        value="3">

                                    <label class="form-check-label"
                                        for="geriatri_adl_3">
                                        Ketergantungan sedang (9–11)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="geriatri_adl"
                                        id="geriatri_adl_4"
                                        value="4">

                                    <label class="form-check-label"
                                        for="geriatri_adl_4">
                                        Ketergantungan berat (5–8)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="geriatri_adl"
                                        id="geriatri_adl_5"
                                        value="5">

                                    <label class="form-check-label"
                                        for="geriatri_adl_5">
                                        Ketergantungan total (0–4)
                                    </label>
                                </div>
                            </div>

                        </div>

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
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJGp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjg_perawat');
        // Sembunyikan textarea saat pertama kali
        $('#terapi_oral').hide();
        $('#terapi_iv').hide();

        //Terapi Oral
        $('#tin_6').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_oral').show();
            } else {
                $('#terapi_oral').hide();
                $('#terapi_oral').val('');
            }
        });

        //Terapi Iv
        $('#tin_7').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_iv').show();
            } else {
                $('#terapi_iv').hide();
                $('#terapi_iv').val('');
            }
        });

        loadDataPengkajianRJGp();

    });

    function loadDataPengkajianRJGp() {
        const kunjungan = $('#rjg_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjg/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJGp(res);
            }
        });
    }

    function setValIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(value);
        }
    }

    function setNumberIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            }));
        }
    }

    function setSuhuIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 2
            }));
        }
    }

    function setCheckedIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector)
                .prop('checked', Number(value) === 1)
                .trigger('change');
        }
    }

    function setRadioIfExists(name, value) {
        if (value !== null && value !== undefined && value !== '') {
            $('input[name="' + name + '"][value="' + value + '"]')
                .prop('checked', true)
                .trigger('change');
        }
    }

    function isiFormPengkajianRJGp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================

        setValIfExists('#anm_ku', data.anm_ku);

        // ======================================================
        // DIAGNOSIS KEPERAWATAN
        // ======================================================

        for (let i = 1; i <= 11; i++) {
            setCheckedIfExists(
                '#diag_keperawatan_' + i,
                data['diag_keperawatan_' + i]
            );
        }

        // ======================================================
        // RENCANA ASUHAN KEPERAWATAN
        // ======================================================

        setValIfExists(
            '#rencana_asuhan_keperawatan',
            data.rencana_asuhan_keperawatan
        );
        setValIfExists('#diag_lain', data.diag_lain);
        // ======================================================
        // ASSESMEN SINDROM GERIATRI
        // ======================================================

        setRadioIfExists('geriatri_adl', data.geriatri_adl);
        setRadioIfExists('geriatri_iadl', data.geriatri_iadl);
        setRadioIfExists('geriatri_acs', data.geriatri_acs);
        setRadioIfExists('geriatri_nutrisi', data.geriatri_nutrisi);
        setRadioIfExists('geriatri_kognitif', data.geriatri_kognitif);
        setRadioIfExists('geriatri_depresi', data.geriatri_depresi);
        setRadioIfExists('geriatri_inkontinensia', data.geriatri_inkontinensia);
        setRadioIfExists('geriatri_dvt', data.geriatri_dvt);
        setRadioIfExists('geriatri_ulkus', data.geriatri_ulkus);
        setRadioIfExists('geriatri_insomnia', data.geriatri_insomnia);

    }

    function saveDataPengkajianRJGp(btn) {
        const $button = $(btn);
        const $section = $('#rjg_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjg/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                // $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (response) {
                // alert(data.message || 'Data berhasil disimpan.');
                iziToast.success({
                    title: 'Pesan Berhasil!',
                    message: 'Data berhasil disimpan.',
                    position: 'topRight'
                });
            },

            error: function (xhr) {
                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            },

            complete: function () {
                // $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    };

</script>
