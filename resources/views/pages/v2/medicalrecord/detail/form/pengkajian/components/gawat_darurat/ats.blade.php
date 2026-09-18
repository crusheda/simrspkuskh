<div id="form_ats"
     class="form-wrapper"
     data-kunjungan="{{ $kunjungan }}">

    {{-- ========================================================= --}}
    {{-- ATS --}}
    {{-- ========================================================= --}}

    <div class="row">

        <div class="col-md-12 mb-3">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>
                        <tr>
                            <th class="text-center">
                                KRITERIA
                            </th>

                            <th class="text-center">
                                P1
                            </th>

                            <th class="text-center">
                                P2
                            </th>

                            <th class="text-center">
                                P3
                            </th>

                            <th class="text-center">
                                P4
                            </th>

                            <th class="text-center">
                                P5
                            </th>

                            <th class="text-center">
                                DOA
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        {{-- ================================================= --}}
                        {{-- KESADARAN --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="fw-bold">
                                KESADARAN
                            </th>

                            <td>
                                Tidak Sadar
                            </td>

                            <td>
                                Tidak Sadar
                            </td>

                            <td>
                                Sadar
                            </td>

                            <td>
                                Sadar
                            </td>

                            <td>
                                Sadar
                            </td>

                            <td>
                                Pupil Midriasis Total Kaku Mayat
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- JALAN NAFAS --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="fw-bold">
                                JALAN NAFAS (<i>Airway</i>)
                            </th>

                            {{-- P1 --}}
                            <td>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_1"
                                    >

                                    <label class="form-check-label">
                                        Sumbatan jalan nafas total
                                    </label>

                                </div>

                            </td>

                            {{-- P2 --}}
                            <td>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_2"
                                    >

                                    <label class="form-check-label">
                                        Sumbatan jalan nafas parsial
                                    </label>

                                </div>

                            </td>

                            {{-- P3 --}}
                            <td>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_3_1"
                                    >

                                    <label class="form-check-label">
                                        Jalan nafas bebas
                                    </label>

                                </div>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_3_2"
                                    >

                                    <label class="form-check-label">
                                        Corpus allienum tanda2 gangguan napas
                                    </label>

                                </div>

                            </td>

                            {{-- P4 --}}
                            <td>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_4"
                                    >

                                    <label class="form-check-label">
                                        Jalan nafas bebas
                                    </label>

                                </div>

                            </td>

                            {{-- P5 --}}
                            <td>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_jn_5"
                                    >

                                    <label class="form-check-label">
                                        Jalan nafas bebas
                                    </label>

                                </div>

                            </td>

                            <td></td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERNAFASAN --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="fw-bold">
                                PERNAFASAN (<i>Breathing</i>)
                            </th>

                            {{-- P1 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_1_1"
                                    >
                                    <label class="form-check-label">
                                        Henti nafas
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_1_2"
                                    >
                                    <label class="form-check-label">
                                        Napas tidak adekuat > 40 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_1_3"
                                    >
                                    <label class="form-check-label">
                                        Gasping < 12 x/menit
                                    </label>
                                </div>

                            </td>

                            {{-- P2 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_2_1"
                                    >
                                    <label class="form-check-label">
                                        Distress pernapasan
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_2_2"
                                    >
                                    <label class="form-check-label">
                                        Frekuensi pernapasan 24 sampai 31 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_2_3"
                                    >
                                    <label class="form-check-label">
                                        Wheezing
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_2_4"
                                    >
                                    <label class="form-check-label">
                                        Ronchi
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_2_5"
                                    >
                                    <label class="form-check-label">
                                        Gurgling
                                    </label>
                                </div>

                            </td>

                            {{-- P3 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_3_1"
                                    >
                                    <label class="form-check-label">
                                        Retraksi atau napas cuping hidung
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_3_2"
                                    >
                                    <label class="form-check-label">
                                        Frekuensi pernapasan 24 sampai 31 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_3_3"
                                    >
                                    <label class="form-check-label">
                                        Wheezing
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_3_4"
                                    >
                                    <label class="form-check-label">
                                        Ronchi
                                    </label>
                                </div>

                            </td>

                            {{-- P4 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_4_1"
                                    >
                                    <label class="form-check-label">
                                        Retraksi atau napas cuping hidung
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_4_2"
                                    >
                                    <label class="form-check-label">
                                        Frekuensi pernapasan 21 sampai 23 x/menit
                                    </label>
                                </div>

                            </td>

                            {{-- P5 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_5_1"
                                    >
                                    <label class="form-check-label">
                                        Tidak ada retraksi
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_pf_5_2"
                                    >
                                    <label class="form-check-label">
                                        Frekuensi pernapasan 12 sampai 20 x/menit
                                    </label>
                                </div>

                            </td>

                            <td></td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- SIRKULASI --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="fw-bold">
                                SIRKULASI (<i>Circulation</i>)
                            </th>

                            {{-- P1 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_1"
                                    >
                                    <label class="form-check-label">
                                        Nadi tidak teraba
                                    </label>
                                </div>

                            </td>

                            {{-- P2 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_1"
                                    >
                                    <label class="form-check-label">
                                        Nadi sangat lemah
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_2"
                                    >
                                    <label class="form-check-label">
                                        Nyeri berat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_3"
                                    >
                                    <label class="form-check-label">
                                        Irama nadi tidak teratur
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_4"
                                    >
                                    <label class="form-check-label">
                                        Nadi < 50 atau > 150 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_5"
                                    >
                                    <label class="form-check-label">
                                        Sianotik
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_6"
                                    >
                                    <label class="form-check-label">
                                        Pucat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_7"
                                    >
                                    <label class="form-check-label">
                                        Akral dingin
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_8"
                                    >
                                    <label class="form-check-label">
                                        Keringat dingin
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_9"
                                    >
                                    <label class="form-check-label">
                                        TDS < 80 atau > 180 mmHg
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_2_10"
                                    >
                                    <label class="form-check-label">
                                        SpO2 < 90%
                                    </label>
                                </div>

                            </td>

                            {{-- P3 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_1"
                                    >
                                    <label class="form-check-label">
                                        Nadi teraba lemah
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_2"
                                    >
                                    <label class="form-check-label">
                                        Nyeri sedang
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_3"
                                    >
                                    <label class="form-check-label">
                                        Irama nadi tidak teratur
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_4"
                                    >
                                    <label class="form-check-label">
                                        Nadi 50–59 atau 101–150 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_5"
                                    >
                                    <label class="form-check-label">
                                        Warna kulit normal
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_6"
                                    >
                                    <label class="form-check-label">
                                        TDS 80–100 atau 150–180 mmHg
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_3_7"
                                    >
                                    <label class="form-check-label">
                                        SpO2 > 95%
                                    </label>
                                </div>

                            </td>

                            {{-- P4 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_1"
                                    >
                                    <label class="form-check-label">
                                        Nadi teraba kuat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_2"
                                    >
                                    <label class="form-check-label">
                                        Nyeri ringan
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_3"
                                    >
                                    <label class="form-check-label">
                                        Irama nadi teratur
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_4"
                                    >
                                    <label class="form-check-label">
                                        Nadi 60–100 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_5"
                                    >
                                    <label class="form-check-label">
                                        Akral hangat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_6"
                                    >
                                    <label class="form-check-label">
                                        TDS diatas 100 atau dibawah 150 mmHg
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_4_7"
                                    >
                                    <label class="form-check-label">
                                        SpO2 > 95%
                                    </label>
                                </div>

                            </td>

                            {{-- P5 --}}
                            <td>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_1"
                                    >
                                    <label class="form-check-label">
                                        Nadi teraba kuat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_2"
                                    >
                                    <label class="form-check-label">
                                        Tidak ada nyeri
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_3"
                                    >
                                    <label class="form-check-label">
                                        Irama nadi teratur
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_4"
                                    >
                                    <label class="form-check-label">
                                        Nadi 60–100 x/menit
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_5"
                                    >
                                    <label class="form-check-label">
                                        Akral hangat
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_6"
                                    >
                                    <label class="form-check-label">
                                        TDS 100, 150 mmHg
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="ats_sr_5_7"
                                    >
                                    <label class="form-check-label">
                                        SpO2 > 95%
                                    </label>
                                </div>

                            </td>

                            <td></td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PLAN --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="fw-bold">
                                PLAN
                            </th>

                            <td
                                colspan="2"
                                class="text-center table-danger"
                            >

                                <h6>ZONA MERAH</h6>

                                <div class="form-group">

                                    <div class="form-check form-check-inline mb-2">

                                        <input
                                            class="form-check-input check-primary single-checkbox"
                                            type="checkbox"
                                            name="ats_p"
                                            value="1"
                                        >

                                        <label class="form-check-label">
                                            Ruang Resusitasi
                                        </label>

                                    </div>

                                    <div class="form-check form-check-inline mb-2">

                                        <input
                                            class="form-check-input check-primary single-checkbox"
                                            type="checkbox"
                                            name="ats_p"
                                            value="2"
                                        >

                                        <label class="form-check-label">
                                            Ruang Kritis
                                        </label>

                                    </div>

                                </div>

                            </td>

                            <td class="text-center align-middle table-warning">

                                <div class="form-check d-flex justify-content-center align-items-center gap-2 mb-0">

                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="ats_p"
                                        value="3"
                                    >

                                    <label class="form-check-label mb-0">

                                        <h6 class="mb-0">
                                            ZONA KUNING
                                        </h6>

                                    </label>

                                </div>

                            </td>

                            <td
                                colspan="2"
                                class="text-center align-middle table-success"
                            >

                                <div class="form-check d-flex justify-content-center align-items-center gap-2 mb-0">

                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="ats_p"
                                        value="4"
                                    >

                                    <label class="form-check-label mb-0">

                                        <h6 class="mb-0">
                                            ZONA HIJAU
                                        </h6>

                                    </label>

                                </div>

                            </td>

                            <td class="text-center align-middle table-secondary">

                                <div class="form-check d-flex justify-content-center align-items-center gap-2 mb-0">

                                    <input
                                        class="form-check-input check-primary single-checkbox"
                                        type="checkbox"
                                        name="ats_p"
                                        value="5"
                                    >

                                    <label class="form-check-label mb-0">

                                        <h6 class="mb-0">
                                            ZONA HITAM
                                        </h6>

                                    </label>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- KRITERIA ATS --}}
        {{-- ========================================================= --}}

        <div class="col-md-12 mb-3">

            <div class="form-group">

                <h6>
                    Kriteria ATS
                    (<i>Australasian Triage Scale</i>)
                </h6>

                <input
                    type="text"
                    class="form-control"
                    name="ats"
                    placeholder="Masukkan Kriteria ATS"
                >

            </div>

        </div>

    </div>

</div>
<script>
(function () {

    'use strict';


    // ==========================================================
    // FORM
    // ==========================================================

    const $form = $('#form_ats');

    if (!$form.length) {
        return;
    }


    const kunjungan = $form.data('kunjungan');


    // ==========================================================
    // STATE
    // ==========================================================

    let isDataLoading = false;
    let isDataSaving = false;


    // ==========================================================
    // DAFTAR CHECKBOX ATS
    // ==========================================================

    const atsCheckboxes = [

        // P1
        'ats_jn_1',
        'ats_pf_1_1',
        'ats_pf_1_2',
        'ats_pf_1_3',
        'ats_sr_1',

        // P2
        'ats_jn_2',
        'ats_pf_2_1',
        'ats_pf_2_2',
        'ats_pf_2_3',
        'ats_pf_2_4',
        'ats_pf_2_5',
        'ats_sr_2_1',
        'ats_sr_2_2',
        'ats_sr_2_3',
        'ats_sr_2_4',
        'ats_sr_2_5',
        'ats_sr_2_6',
        'ats_sr_2_7',
        'ats_sr_2_8',
        'ats_sr_2_9',
        'ats_sr_2_10',

        // P3
        'ats_jn_3_1',
        'ats_jn_3_2',
        'ats_pf_3_1',
        'ats_pf_3_2',
        'ats_pf_3_3',
        'ats_pf_3_4',
        'ats_sr_3_1',
        'ats_sr_3_2',
        'ats_sr_3_3',
        'ats_sr_3_4',
        'ats_sr_3_5',
        'ats_sr_3_6',
        'ats_sr_3_7',

        // P4
        'ats_jn_4',
        'ats_pf_4_1',
        'ats_pf_4_2',
        'ats_sr_4_1',
        'ats_sr_4_2',
        'ats_sr_4_3',
        'ats_sr_4_4',
        'ats_sr_4_5',
        'ats_sr_4_6',
        'ats_sr_4_7',

        // P5
        'ats_jn_5',
        'ats_pf_5_1',
        'ats_pf_5_2',
        'ats_sr_5_1',
        'ats_sr_5_2',
        'ats_sr_5_3',
        'ats_sr_5_4',
        'ats_sr_5_5',
        'ats_sr_5_6',
        'ats_sr_5_7',
    ];


    // ==========================================================
    // SET CHECKBOX
    // ==========================================================

    function setCheckbox(name, value) {

        const checked =
            value == 1 ||
            value === true ||
            value === '1' ||
            value === 'true';

        $form
            .find(`input[name="${name}"]`)
            .prop('checked', checked);
    }


    // ==========================================================
    // RESET ATS
    // ==========================================================

    function resetATS() {

        $form
            .find('input[type="checkbox"]')
            .prop('checked', false);

        $form
            .find('input[name="ats"]')
            .val('');

    }


    // ==========================================================
    // GET DATA ATS
    // ==========================================================

    function getATS() {

        if (!$form.length) {
            return;
        }

        if (!kunjungan) {
            console.warn(
                'Nomor kunjungan ATS tidak ditemukan.'
            );
            return;
        }


        isDataLoading = true;


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/gd/ats/${kunjungan}`,

            type: 'GET',

            dataType: 'json',


            success: function (response) {

                if (
                    !response ||
                    response.status !== true
                ) {

                    console.warn(
                        response?.message ||
                        'Data ATS tidak ditemukan.'
                    );

                    return;
                }


                const triage =
                    response.data?.triage || {};


                // ==================================================
                // RESET
                // ==================================================

                resetATS();

                // ==================================================
                // KATEGORI PEMERIKSAAN / ATS P
                // ==================================================
                //
                // Mapping:
                // RESUSITASI  CHECKED = 1 -> ats_p = 1
                // EMERGENCY   CHECKED = 1 -> ats_p = 2
                // URGENT      CHECKED = 1 -> ats_p = 3
                // LESS_URGENT CHECKED = 1 -> ats_p = 4
                // NON_URGENT  CHECKED = 1 -> ats_p = 5
                // DOA         CHECKED = 1 -> ats_p = 6
                //
                // Prioritas mengikuti urutan kategori di atas.
                // ==================================================

                const kategoriATS = [
                    {
                        value: '1',
                        data: triage.RESUSITASI
                    },
                    {
                        value: '2',
                        data: triage.EMERGENCY
                    },
                    {
                        value: '3',
                        data: triage.URGENT
                    },
                    {
                        value: '4',
                        data: triage.LESS_URGENT
                    },
                    {
                        value: '5',
                        data: triage.NON_URGENT
                    },
                    {
                        value: '6',
                        data: triage.DOA
                    }
                ];

                // Reset semua pilihan ATS P terlebih dahulu
                $form
                    .find('input[name="ats_p"]')
                    .prop('checked', false);

                // Cari kategori yang CHECKED = 1
                const kategoriChecked = kategoriATS.find(function (item) {

                    return (
                        item.data &&
                        (
                            item.data.CHECKED == 1 ||
                            item.data.CHECKED === true ||
                            item.data.CHECKED === '1'
                        )
                    );

                });

                // Centang sesuai kategori yang CHECKED
                if (kategoriChecked) {

                    $form
                        .find('input[name="ats_p"]')
                        .filter(function () {

                            return (
                                $(this).val() ==
                                kategoriChecked.value
                            );

                        })
                        .prop('checked', true);
                }

                // ==================================================
                // RESUSITASI / P1
                // ==================================================

                const resusitasi =
                    triage.RESUSITASI || {};

                const resusJalanNapas =
                    resusitasi.JALAN_NAPAS || {};

                const resusPernapasan =
                    resusitasi.PERNAPASAN || {};

                const resusSirkulasi =
                    resusitasi.SIRKULASI || {};


                setCheckbox(
                    'ats_jn_1',
                    resusJalanNapas.SUMBATAN_JALAN_NAPAS_TOTAL
                );

                setCheckbox(
                    'ats_pf_1_1',
                    resusPernapasan.HENTI_NAFAS
                );

                setCheckbox(
                    'ats_pf_1_2',
                    resusPernapasan.NAPAS_TIDAK_ADEKUAT_DIATAS_40_X_PER_MENIT
                );

                setCheckbox(
                    'ats_pf_1_3',
                    resusPernapasan.GASPING_DIBAWAH_12_X_PER_MENIT
                );

                setCheckbox(
                    'ats_sr_1',
                    resusSirkulasi.NADI_TIDAK_TERABA
                );


                // ==================================================
                // EMERGENCY / P2
                // ==================================================

                const emergency =
                    triage.EMERGENCY || {};

                const emergencyJalanNapas =
                    emergency.JALAN_NAPAS || {};

                const emergencyPernapasan =
                    emergency.PERNAPASAN || {};

                const emergencySirkulasi =
                    emergency.SIRKULASI || {};


                setCheckbox(
                    'ats_jn_2',
                    emergencyJalanNapas.SUMBATAN_JALAN_NAPAS_PARSIAL
                );

                setCheckbox(
                    'ats_pf_2_1',
                    emergencyPernapasan.DISTRESS_PERNAPASAN
                );

                setCheckbox(
                    'ats_pf_2_2',
                    emergencyPernapasan.FREKUENSI_PERNAPASAN_24_SAMPAI_31_X_PER_MENIT
                );

                setCheckbox(
                    'ats_pf_2_3',
                    emergencyPernapasan.WHEEZING
                );

                setCheckbox(
                    'ats_pf_2_4',
                    emergencyPernapasan.RONCHI
                );

                setCheckbox(
                    'ats_pf_2_5',
                    emergencyPernapasan.GURGLING
                );


                setCheckbox(
                    'ats_sr_2_1',
                    emergencySirkulasi.NADI_SANGAT_LEMAH
                );

                setCheckbox(
                    'ats_sr_2_2',
                    emergencySirkulasi.NYERI_BERAT
                );

                setCheckbox(
                    'ats_sr_2_3',
                    emergencySirkulasi.IRAMA_NADI_TIDAK_TERATUR
                );

                setCheckbox(
                    'ats_sr_2_4',
                    emergencySirkulasi.NADI_DIBAWAH_50_ATAU_DIATAS_150_X_PER_MENIT
                );

                setCheckbox(
                    'ats_sr_2_5',
                    emergencySirkulasi.SIANOTIK
                );

                setCheckbox(
                    'ats_sr_2_6',
                    emergencySirkulasi.PUCAT
                );

                setCheckbox(
                    'ats_sr_2_7',
                    emergencySirkulasi.AKRAL_DINGIN
                );

                setCheckbox(
                    'ats_sr_2_8',
                    emergencySirkulasi.KERINGAT_DINGIN
                );

                setCheckbox(
                    'ats_sr_2_9',
                    emergencySirkulasi.TDS_DIBAWAH_80_ATAU_DIATAS_180_MMHG
                );

                setCheckbox(
                    'ats_sr_2_10',
                    emergencySirkulasi.SPO2_DIBAWAH_90_PERSEN
                );


                // ==================================================
                // URGENT / P3
                // ==================================================

                const urgent =
                    triage.URGENT || {};

                const urgentJalanNapas =
                    urgent.JALAN_NAPAS || {};

                const urgentPernapasan =
                    urgent.PERNAPASAN || {};

                const urgentSirkulasi =
                    urgent.SIRKULASI || {};


                setCheckbox(
                    'ats_jn_3_1',
                    urgentJalanNapas.JALAN_NAPAS_BEBAS
                );

                setCheckbox(
                    'ats_jn_3_2',
                    urgentJalanNapas.CORPUS_ALLIENUM_TANDA2_GANGUAN_NAPAS
                );


                setCheckbox(
                    'ats_pf_3_1',
                    urgentPernapasan.RETRAKSI_ATAU_NAPAS_CUPING_HIDUNG
                );

                setCheckbox(
                    'ats_pf_3_2',
                    urgentPernapasan.FREKUENSI_PERNAPASAN_24_SAMPAI_31_X_PER_MENIT
                );

                setCheckbox(
                    'ats_pf_3_3',
                    urgentPernapasan.WHEEZING
                );

                setCheckbox(
                    'ats_pf_3_4',
                    urgentPernapasan.RONCHI
                );


                setCheckbox(
                    'ats_sr_3_1',
                    urgentSirkulasi.NADI_TERABA_LEMAH
                );

                setCheckbox(
                    'ats_sr_3_2',
                    urgentSirkulasi.NYERI_SEDANG
                );

                setCheckbox(
                    'ats_sr_3_3',
                    urgentSirkulasi.IRAMA_NADI_TIDAK_TERATUR
                );

                setCheckbox(
                    'ats_sr_3_4',
                    urgentSirkulasi.NADI_50_SAMPAI_59_ATAU_101_SAMPAI_150_X_PER_MENIT
                );

                setCheckbox(
                    'ats_sr_3_5',
                    urgentSirkulasi.WARNA_KULIT_NORMAL
                );

                setCheckbox(
                    'ats_sr_3_6',
                    urgentSirkulasi.TDS_80_SAMPAI_100_ATAU_150_SAMPAI_180_MMHG
                );

                setCheckbox(
                    'ats_sr_3_7',
                    urgentSirkulasi.SPO2_DIATAS_95_PERSEN
                );


                // ==================================================
                // LESS URGENT / P4
                // ==================================================

                const lessUrgent =
                    triage.LESS_URGENT || {};

                const lessUrgentJalanNapas =
                    lessUrgent.JALAN_NAPAS || {};

                const lessUrgentPernapasan =
                    lessUrgent.PERNAPASAN || {};

                const lessUrgentSirkulasi =
                    lessUrgent.SIRKULASI || {};


                setCheckbox(
                    'ats_jn_4',
                    lessUrgentJalanNapas.JALAN_NAPAS_BEBAS
                );


                setCheckbox(
                    'ats_pf_4_1',
                    lessUrgentPernapasan.RETRAKSI_ATAU_NAPAS_CUPING_HIDUNG
                );

                setCheckbox(
                    'ats_pf_4_2',
                    lessUrgentPernapasan.FREKUENSI_PERNAPASAN_21_SAMPAI_23_X_PER_MENIT
                );


                setCheckbox(
                    'ats_sr_4_1',
                    lessUrgentSirkulasi.NADI_TERABA_KUAT
                );

                setCheckbox(
                    'ats_sr_4_2',
                    lessUrgentSirkulasi.NYERI_RINGAN
                );

                setCheckbox(
                    'ats_sr_4_3',
                    lessUrgentSirkulasi.IRAMA_NADI_TERATUR
                );

                setCheckbox(
                    'ats_sr_4_4',
                    lessUrgentSirkulasi.NADI_60_SAMPAI_100_X_PER_MENIT
                );

                setCheckbox(
                    'ats_sr_4_5',
                    lessUrgentSirkulasi.AKRAL_HANGAT
                );

                setCheckbox(
                    'ats_sr_4_6',
                    lessUrgentSirkulasi.TDS_DIATAS_100_ATAU_DIBAWAH_150_MMHG
                );

                setCheckbox(
                    'ats_sr_4_7',
                    lessUrgentSirkulasi.SPO2_DIATAS_95_PERSEN
                );


                // ==================================================
                // NON URGENT / P5
                // ==================================================

                const nonUrgent =
                    triage.NON_URGENT || {};

                const nonUrgentJalanNapas =
                    nonUrgent.JALAN_NAPAS || {};

                const nonUrgentPernapasan =
                    nonUrgent.PERNAPASAN || {};

                const nonUrgentSirkulasi =
                    nonUrgent.SIRKULASI || {};


                setCheckbox(
                    'ats_jn_5',
                    nonUrgentJalanNapas.JALAN_NAPAS_BEBAS
                );


                setCheckbox(
                    'ats_pf_5_1',
                    nonUrgentPernapasan.TIDAK_ADA_RETRAKSI
                );

                setCheckbox(
                    'ats_pf_5_2',
                    nonUrgentPernapasan.FREKUENSI_PERNAPASAN_12_SAMPAI_20_X_PER_MENIT
                );


                setCheckbox(
                    'ats_sr_5_1',
                    nonUrgentSirkulasi.NADI_TERABA_KUAT
                );

                setCheckbox(
                    'ats_sr_5_2',
                    nonUrgentSirkulasi.TIDAK_ADA_NYERI
                );

                setCheckbox(
                    'ats_sr_5_3',
                    nonUrgentSirkulasi.IRAMA_NADI_TERATUR
                );

                setCheckbox(
                    'ats_sr_5_4',
                    nonUrgentSirkulasi.NADI_60_SAMPAI_100_X_PER_MENIT
                );

                setCheckbox(
                    'ats_sr_5_5',
                    nonUrgentSirkulasi.AKRAL_HANGAT
                );

                setCheckbox(
                    'ats_sr_5_6',
                    nonUrgentSirkulasi.TDS_100_KOMA_150_MMHG
                );

                setCheckbox(
                    'ats_sr_5_7',
                    nonUrgentSirkulasi.SPO2_DIATAS_95_PERSEN
                );


                // ==================================================
                // KRITERIA ATS
                // ==================================================

                $form
                    .find('input[name="ats"]')
                    .val(
                        triage.KRITERIA ?? ''
                    );

            },


            error: function (xhr) {

                console.error(
                    'Gagal mengambil data ATS:',
                    xhr
                );

            },


            complete: function () {

                isDataLoading = false;

            }

        });
    }


    // ==========================================================
    // SIMPAN ATS
    // ==========================================================

    function simpanATS() {

        if (
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }


        if (!kunjungan) {
            return;
        }


        isDataSaving = true;


        const data = {

            _token:
                $('meta[name="csrf-token"]').attr('content'),

            NOKUNJ:
                kunjungan,

            ats:
                $form
                    .find('input[name="ats"]')
                    .val() || '',

            ats_p:
                $form
                    .find('input[name="ats_p"]:checked')
                    .val() || '',
        };


        // ==========================================================
        // CHECKBOX
        // ==========================================================

        atsCheckboxes.forEach(function (name) {

            data[name] =
                $form
                    .find(`input[name="${name}"]`)
                    .is(':checked')
                    ? 1
                    : 0;

        });


        // ==========================================================
        // AJAX
        // ==========================================================

        $.ajax({

            url: `/api/v2/emr/pengkajian/gd/ats/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            success: function (response) {

                if (
                    response &&
                    response.status === true
                ) {

                    console.log(
                        'ATS berhasil disimpan.'
                    );

                    return;
                }


                if (
                    typeof iziToast !== 'undefined'
                ) {

                    iziToast.error({
                        title: 'Gagal',
                        message:
                            response?.message ||
                            'Data ATS gagal disimpan.'
                    });

                }

            },


            error: function (xhr) {

                if (
                    typeof iziToast !== 'undefined'
                ) {

                    iziToast.error({

                        title: 'Gagal',

                        message:
                            xhr.responseJSON?.message ||
                            'Data ATS gagal disimpan.'
                    });

                }

            },


            complete: function () {

                isDataSaving = false;

            }

        });
    }


    // ==========================================================
    // SINGLE CHECKBOX ATS P
    // ==========================================================

    $form.on(
        'change',
        'input.single-checkbox[name="ats_p"]',
        function () {

            if (!this.checked) {

                return;
            }


            $form
                .find(
                    'input.single-checkbox[name="ats_p"]'
                )
                .not(this)
                .prop('checked', false);


            simpanATS();

        }
    );


    // ==========================================================
    // CHECKBOX ATS
    // ==========================================================

    $form.on(
        'change',
        'input[type="checkbox"]:not([name="ats_p"])',
        function () {

            simpanATS();

        }
    );


    // ==========================================================
    // KRITERIA ATS
    // ==========================================================

    $form.on(
        'blur',
        'input[name="ats"]',
        function () {

            simpanATS();

        }
    );


    // ==========================================================
    // INIT
    // ==========================================================

    getATS();

})();
</script>
