@php

    $metodeNyeri = $metodeNyeri ?? [
        'nrs',
        'bps',
        'nips',
        'flacc',
        'vas'
    ];

    /*
    |--------------------------------------------------------------------------
    | TRANSFER
    |--------------------------------------------------------------------------
    |
    | 1 = Sebelum Transfer
    | 2 = Sesudah Transfer
    |
    */

    $transfer = (int) ($transfer ?? 1);

    if (!in_array($transfer, [1, 2], true)) {
        $transfer = 1;
    }


    /*
    |--------------------------------------------------------------------------
    | ID UNIK
    |--------------------------------------------------------------------------
    */

    $formId = 'form_skrining_nyeri_transfer_' . $transfer;

    $nrsId = 'tampil_sn_nrs_transfer_' . $transfer;
    $bpsId = 'tampil_sn_bps_transfer_' . $transfer;
    $nipsId = 'tampil_sn_nips_transfer_' . $transfer;
    $flaccId = 'tampil_sn_flacc_transfer_' . $transfer;
    $vasId = 'tampil_sn_vas_transfer_' . $transfer;

    $nrsInputId = 'sn_nrs_transfer_' . $transfer;
    $vasInputId = 'sn_vas_transfer_' . $transfer;

    /*
    |--------------------------------------------------------------------------
    | NAME UNIK PER TRANSFER
    |--------------------------------------------------------------------------
    */

    $nyeriName = 'sn_nyeri_' . $transfer;
    $onsetName = 'sn_onset_' . $transfer;
    $metodeName = 'sn_metode_' . $transfer;

    $skalaName = 'sn_skala_' . $transfer;

    $nrsName = 'sn_nrs_' . $transfer;

    $bpsName1 = 'sn_bps_1_' . $transfer;
    $bpsName2 = 'sn_bps_2_' . $transfer;
    $bpsName3 = 'sn_bps_3_' . $transfer;

    $nipsName1 = 'sn_nips_1_' . $transfer;
    $nipsName2 = 'sn_nips_2_' . $transfer;
    $nipsName3 = 'sn_nips_3_' . $transfer;
    $nipsName4 = 'sn_nips_4_' . $transfer;
    $nipsName5 = 'sn_nips_5_' . $transfer;
    $nipsName6 = 'sn_nips_6_' . $transfer;

    $flaccName1 = 'sn_flacc_1_' . $transfer;
    $flaccName2 = 'sn_flacc_2_' . $transfer;
    $flaccName3 = 'sn_flacc_3_' . $transfer;
    $flaccName4 = 'sn_flacc_4_' . $transfer;
    $flaccName5 = 'sn_flacc_5_' . $transfer;

    $vasName = 'sn_vas_' . $transfer;

    $pencetusName = 'sn_pencetus_' . $transfer;
    $gambaranName = 'sn_gambaran_' . $transfer;
    $durasiName = 'sn_durasi_' . $transfer;
    $lokasiName = 'sn_lokasi_' . $transfer;

@endphp


<div
    class="form-group form-skrining-nyeri-transfer"
    id="{{ $formId }}"
    data-transfer="{{ $transfer }}"
>


    {{-- ==========================================================
        BARIS 1
    =========================================================== --}}

    <div class="row mb-3 align-items-center">

        {{-- NYERI --}}

        <div class="col-md-4">

            <div class="row align-items-center">

                <label class="col-md-4 col-form-label fw-bold">
                    Nyeri
                </label>

                <div class="col-md-8">

                    <div class="d-flex gap-4">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="{{ $nyeriName }}"
                                value="1"
                            >

                            <label class="form-check-label">
                                Ya
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="{{ $nyeriName }}"
                                value="0"
                                checked
                            >

                            <label class="form-check-label">
                                Tidak
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ONSET --}}

        <div class="col-md-8">

            <div class="row align-items-center">

                <label class="col-md-2 col-form-label fw-bold">
                    Onset
                </label>

                <div class="col-md-10">

                    <div class="d-flex gap-4">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="{{ $onsetName }}"
                                value="1"
                            >

                            <label class="form-check-label">
                                Akut
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="{{ $onsetName }}"
                                value="2"
                            >

                            <label class="form-check-label">
                                Kronis
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        BARIS 2
    =========================================================== --}}

    <div class="row mb-3 align-items-center">

        {{-- SKALA --}}

        <div class="col-md-4">

            <div class="row align-items-center">

                <label class="col-md-4 col-form-label fw-bold">
                    Skala Nyeri
                </label>

                <div class="col-md-8">

                    <input
                        type="number"
                        class="form-control"
                        name="{{ $skalaName }}"
                        placeholder="Otomatis terisi"
                        value="0"
                        readonly
                    >

                </div>

            </div>

        </div>


        {{-- METODE --}}

        <div class="col-md-8">

            <div class="row align-items-center">

                <label class="col-md-2 col-form-label fw-bold">
                    Metode
                </label>

                <div class="col-md-10">

                    <div class="d-flex gap-4">

                        @if(in_array('vas', $metodeNyeri))

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="{{ $metodeName }}"
                                    value="5"
                                >

                                <label class="form-check-label">
                                    VAS
                                </label>

                            </div>

                        @endif


                        @if(in_array('nrs', $metodeNyeri))

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="{{ $metodeName }}"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    NRS
                                </label>

                            </div>

                        @endif


                        @if(in_array('nips', $metodeNyeri))

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="{{ $metodeName }}"
                                    value="3"
                                >

                                <label class="form-check-label">
                                    NIPS
                                </label>

                            </div>

                        @endif


                        @if(in_array('flacc', $metodeNyeri))

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="{{ $metodeName }}"
                                    value="4"
                                >

                                <label class="form-check-label">
                                    FLACC
                                </label>

                            </div>

                        @endif


                        @if(in_array('bps', $metodeNyeri))

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="{{ $metodeName }}"
                                    value="2"
                                >

                                <label class="form-check-label">
                                    BPS
                                </label>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        METODE NRS
    =========================================================== --}}

    @if(in_array('nrs', $metodeNyeri))

        <div
            class="row mb-2"
            id="{{ $nrsId }}"
            hidden
        >

            <div class="col-md-12">

                <img
                    src="{{ asset('images/erm/skrining_nyeri_nrs.png') }}"
                    alt="Indikator Penilaian Nyeri"
                    class="img-fluid mb-3"
                    style="width: 25rem;"
                >


                <div class="form-group">

                    <label class="form-label">
                        Geser untuk memilih Skor Nyeri
                    </label>

                    <input
                        type="range"
                        class="form-range"
                        min="0"
                        max="10"
                        step="1"
                        value="0"
                        name="{{ $nrsName }}"
                        id="{{ $nrsInputId }}"
                    >

                </div>


                <div class="d-flex align-items-center gap-3">

                    <span>
                        Interpretasi Skor Nyeri NRS :
                    </span>

                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">

                        <li>
                            0 = tidak nyeri
                        </li>

                        <li>
                            1-3 = nyeri ringan
                        </li>

                        <li>
                            4-6 = nyeri sedang
                        </li>

                        <li>
                            7-10 = nyeri berat
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        METODE BPS
    =========================================================== --}}

    @if(in_array('bps', $metodeNyeri))

        <div
            class="row mb-2"
            id="{{ $bpsId }}"
            hidden
        >

            <div class="col-md-12">

                <div class="table-responsive">

                    <table class="table table-bordered table-display">

                        <thead class="text-uppercase">

                            <tr class="table-light">

                                <th class="text-center">
                                    Indikator Penilaian
                                </th>

                                <th class="text-center">
                                    Keterangan Skor
                                </th>

                                <th class="text-center">
                                    Skor
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <th>
                                    Ekspresi Wajah
                                </th>

                                <td>

                                    <h6>
                                        1 = rileks
                                    </h6>

                                    <h6>
                                        2 = sebagian tegang
                                        (misal: dahi mengkerut)
                                    </h6>

                                    <h6>
                                        3 = tegang penuh
                                        (misal: kelopak mata menutup rapat)
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $bpsName1 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="1"
                                        max="3"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Ekspresi Wajah
                                </th>

                                <td>

                                    <h6>
                                        1 = rileks
                                    </h6>

                                    <h6>
                                        2 = sebagian tegang
                                        (misal: dahi mengkerut)
                                    </h6>

                                    <h6>
                                        3 = tegang penuh
                                        (misal: kelopak mata menutup rapat)
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $bpsName2 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="1"
                                        max="3"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Ekspresi Wajah
                                </th>

                                <td>

                                    <h6>
                                        1 = rileks
                                    </h6>

                                    <h6>
                                        2 = sebagian tegang
                                        (misal: dahi mengkerut)
                                    </h6>

                                    <h6>
                                        3 = tegang penuh
                                        (misal: kelopak mata menutup rapat)
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $bpsName3 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="1"
                                        max="3"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="3">

                                    <div class="d-flex align-items-center gap-3">

                                        <span>
                                            Interpretasi Skor BPS :
                                        </span>

                                        <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">

                                            <li>
                                                3 = tidak nyeri
                                            </li>

                                            <li>
                                                4-6 = nyeri ringan
                                            </li>

                                            <li>
                                                7-9 = nyeri sedang
                                            </li>

                                            <li>
                                                10-12 = nyeri berat
                                            </li>

                                        </ul>

                                    </div>

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        METODE NIPS
    =========================================================== --}}

    @if(in_array('nips', $metodeNyeri))

        <div
            class="row mb-2"
            id="{{ $nipsId }}"
            hidden
        >

            <div class="col-md-12">

                <div class="table-responsive">

                    <table class="table table-bordered table-display">

                        <thead class="text-uppercase">

                            <tr class="table-light">

                                <th class="text-center">
                                    Indikator Penilaian
                                </th>

                                <th class="text-center">
                                    Keterangan Skor
                                </th>

                                <th class="text-center">
                                    Skor
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <th>
                                    Ekspresi Wajah
                                </th>

                                <td>

                                    <h6>
                                        0 = Relaksasi
                                    </h6>

                                    <h6>
                                        1 = Meringis
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName1 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="1"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Tangisan
                                </th>

                                <td>

                                    <h6>
                                        0 = Tidak menangis
                                    </h6>

                                    <h6>
                                        1 = Meringis
                                    </h6>

                                    <h6>
                                        2 = Menangis kuat
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName2 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Gerakan Lengan
                                </th>

                                <td>

                                    <h6>
                                        0 = Relaksasi
                                    </h6>

                                    <h6>
                                        1 = Fleksi / ekstensi
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName3 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="1"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Gerakan Tungkai
                                </th>

                                <td>

                                    <h6>
                                        0 = relaksasi
                                    </h6>

                                    <h6>
                                        1 = Fleksi / ekstensi
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName4 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="1"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Status Terjaga
                                </th>

                                <td>

                                    <h6>
                                        0 = Tidur / bangun
                                    </h6>

                                    <h6>
                                        1 = Rewel
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName5 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="1"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Pola Nafas
                                </th>

                                <td>

                                    <h6>
                                        0 = Relaksasi
                                    </h6>

                                    <h6>
                                        1 = Perubahan pola nafas
                                    </h6>

                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $nipsName6 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="1"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="3">

                                    <div class="d-flex align-items-center gap-3">

                                        <span>
                                            Keterangan :
                                        </span>

                                        <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">

                                            <li>
                                                > 3 = Nyeri
                                            </li>

                                            <li>
                                                ≤ 3 = Tidak Nyeri
                                            </li>

                                        </ul>

                                    </div>

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        METODE FLACC
    =========================================================== --}}

    @if(in_array('flacc', $metodeNyeri))

        <div
            class="row mb-2"
            id="{{ $flaccId }}"
            hidden
        >

            <div class="col-md-12">

                <div class="table-responsive">

                    <table class="table table-bordered table-display">

                        <thead class="text-uppercase">

                            <tr class="table-light">

                                <th class="text-center">
                                    Indikator
                                </th>

                                <th class="text-center">
                                    0
                                </th>

                                <th class="text-center">
                                    1
                                </th>

                                <th class="text-center">
                                    2
                                </th>

                                <th class="text-center">
                                    Skor
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <th>
                                    Wajah
                                </th>

                                <td>
                                    Tersenyum / tidak ada ekspresi khusus
                                </td>

                                <td>
                                    Terkadang meringis / menarik diri
                                </td>

                                <td>
                                    Sering menggetarkan dagu dan mengatupkan rahang
                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $flaccName1 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Kaki
                                </th>

                                <td>
                                    Gerakan normal / relaksasi
                                </td>

                                <td>
                                    Tidak tenang
                                </td>

                                <td>
                                    Kaki dibuat menendang / menarik diri
                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $flaccName2 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Aktivitas
                                </th>

                                <td>
                                    Tidur, posisi normal mudah bergerak
                                </td>

                                <td>
                                    Gerakan menggeliat, berguling, kaku
                                </td>

                                <td>
                                    Melengkungkan punggung / kaku / menghentak
                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $flaccName3 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Menangis
                                </th>

                                <td>
                                    Tidak menangis (bangun / tidur)
                                </td>

                                <td>
                                    Mengerang, merengek-rengek
                                </td>

                                <td>
                                    Menangis terus-menerus, terisak, menjerit
                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $flaccName4 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Bersuara
                                </th>

                                <td>
                                    Bersuara normal, tenang
                                </td>

                                <td>
                                    Tenang bila dipeluk, digendong, atau diajak bicara
                                </td>

                                <td>
                                    Sulit untuk ditenangkan
                                </td>

                                <td class="text-center">

                                    <input
                                        type="number"
                                        name="{{ $flaccName5 }}"
                                        class="form-control form-control-sm mx-auto"
                                        min="0"
                                        max="2"
                                        style="width: 100px"
                                    >

                                </td>

                            </tr>

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="5">

                                    Interpretasi :
                                    Skor total dari lima parameter di atas
                                    menentukan tingkat keparahan nyeri
                                    dengan skala 0 - 10.
                                    Nilai 10 menunjukan tingkat nyeri yang hebat.

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        METODE VAS
    =========================================================== --}}

    @if(in_array('vas', $metodeNyeri))

        <div
            class="row mb-2"
            id="{{ $vasId }}"
            hidden
        >

            <div class="col-md-12">

                <img
                    src="{{ asset('images/erm/skrining_nyeri_vas.png') }}"
                    alt="Indikator Penilaian Nyeri"
                    class="img-fluid mb-3"
                    style="width: 25rem;"
                >


                <div class="form-group">

                    <label class="form-label">
                        Geser untuk memilih Skor Nyeri
                    </label>

                    <input
                        type="range"
                        class="form-range"
                        min="0"
                        max="10"
                        step="1"
                        value="0"
                        name="{{ $vasName }}"
                        id="{{ $vasInputId }}"
                    >

                </div>


                <div class="d-flex align-items-center gap-3">

                    <span>
                        Interpretasi Skor Nyeri VAS :
                    </span>

                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">

                        <li>
                            0 = tidak nyeri
                        </li>

                        <li>
                            1-3 = nyeri ringan
                        </li>

                        <li>
                            4-6 = nyeri sedang
                        </li>

                        <li>
                            7-10 = nyeri berat
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        PENCETUS
    =========================================================== --}}

    <div class="row align-items-center">

        <label class="col-md-2 col-form-label fw-bold">
            Pencetus
        </label>

        <div class="col-md-10">

            <input
                type="text"
                class="form-control form-control-sm"
                name="{{ $pencetusName }}"
                placeholder="[ Pencetus ]"
            >

        </div>

    </div>


    {{-- ==========================================================
        GAMBARAN
    =========================================================== --}}

    <div class="row align-items-center">

        <label class="col-md-2 col-form-label fw-bold">
            Gambaran
        </label>

        <div class="col-md-10">

            <input
                type="text"
                class="form-control form-control-sm"
                name="{{ $gambaranName }}"
                placeholder="[ Gambaran ]"
            >

        </div>

    </div>


    {{-- ==========================================================
        DURASI
    =========================================================== --}}

    <div class="row align-items-center">

        <label class="col-md-2 col-form-label fw-bold">
            Durasi
        </label>

        <div class="col-md-10">

            <input
                type="text"
                class="form-control form-control-sm"
                name="{{ $durasiName }}"
                placeholder="[ Durasi ]"
            >

        </div>

    </div>


    {{-- ==========================================================
        LOKASI
    =========================================================== --}}

    <div class="row align-items-center">

        <label class="col-md-2 col-form-label fw-bold">
            Lokasi
        </label>

        <div class="col-md-10">

            <input
                type="text"
                class="form-control form-control-sm"
                name="{{ $lokasiName }}"
                placeholder="[ Lokasi ]"
            >

        </div>

    </div>


    {{-- ==========================================================
        BUTTON
    =========================================================== --}}

    <button
        type="button"
        class="btn btn-primary float-end mt-2 btn-save-sub-pengkajian btn-save-skrining-nyeri-transfer"
    >
        <i class="ri-save-line me-1"></i>
        Simpan Skrining Nyeri
    </button>

</div>


<script>

(function () {

    'use strict';


    // ==============================================================
    // CONFIG
    // ==============================================================

    const transfer = @json($transfer);

    const formId = @json($formId);

    const $form = $('#' + formId);


    // ==============================================================
    // NAME FIELD
    // ==============================================================

    const names = {

        nyeri: @json($nyeriName),

        onset: @json($onsetName),

        metode: @json($metodeName),

        skala: @json($skalaName),

        nrs: @json($nrsName),

        bps1: @json($bpsName1),
        bps2: @json($bpsName2),
        bps3: @json($bpsName3),

        nips1: @json($nipsName1),
        nips2: @json($nipsName2),
        nips3: @json($nipsName3),
        nips4: @json($nipsName4),
        nips5: @json($nipsName5),
        nips6: @json($nipsName6),

        flacc1: @json($flaccName1),
        flacc2: @json($flaccName2),
        flacc3: @json($flaccName3),
        flacc4: @json($flaccName4),
        flacc5: @json($flaccName5),

        vas: @json($vasName),

        pencetus: @json($pencetusName),
        gambaran: @json($gambaranName),
        durasi: @json($durasiName),
        lokasi: @json($lokasiName)

    };


    // ==============================================================
    // VALIDASI
    // ==============================================================

    if (!$form.length) {

        console.warn(
            'Form Skrining Nyeri Transfer tidak ditemukan:',
            formId
        );

        return;

    }


    // ==============================================================
    // METODE MAP
    // ==============================================================

    const metodeMap = {

        1: '#{{ $nrsId }}',

        2: '#{{ $bpsId }}',

        3: '#{{ $nipsId }}',

        4: '#{{ $flaccId }}',

        5: '#{{ $vasId }}'

    };


    // ==============================================================
    // HIDE SEMUA METODE
    // ==============================================================

    function hideSemuaMetode() {

        $.each(
            metodeMap,
            function (key, selector) {

                $form
                    .find(selector)
                    .prop('hidden', true);

            }
        );

    }


    // ==============================================================
    // RESET SEMUA SKOR
    // ==============================================================

    function resetSemuaSkor() {

        $form
            .find('#{{ $nrsInputId }}, #{{ $vasInputId }}')
            .val(0);


        $form
            .find(
                '#{{ $bpsId }} input[type="number"], ' +
                '#{{ $nipsId }} input[type="number"], ' +
                '#{{ $flaccId }} input[type="number"]'
            )
            .val('');


        $form
            .find(
                'input[name="' + names.skala + '"]'
            )
            .val(0);

    }


    // ==============================================================
    // HITUNG SKOR
    // ==============================================================

    function hitungSkorNyeri(metode) {

        let total = 0;


        switch (String(metode)) {


            // ------------------------------------------------------
            // NRS
            // ------------------------------------------------------

            case '1':

                total = FormHelper.setValidNumber(
                    $form,
                    names.nrs
                );

                break;


            // ------------------------------------------------------
            // BPS
            // ------------------------------------------------------

            case '2':

                [
                    names.bps1,
                    names.bps2,
                    names.bps3

                ].forEach(function (name) {

                    total += FormHelper.setValidNumber(
                        $form,
                        name
                    );

                });

                break;


            // ------------------------------------------------------
            // NIPS
            // ------------------------------------------------------

            case '3':

                [
                    names.nips1,
                    names.nips2,
                    names.nips3,
                    names.nips4,
                    names.nips5,
                    names.nips6

                ].forEach(function (name) {

                    total += FormHelper.setValidNumber(
                        $form,
                        name
                    );

                });

                break;


            // ------------------------------------------------------
            // FLACC
            // ------------------------------------------------------

            case '4':

                [
                    names.flacc1,
                    names.flacc2,
                    names.flacc3,
                    names.flacc4,
                    names.flacc5

                ].forEach(function (name) {

                    total += FormHelper.setValidNumber(
                        $form,
                        name
                    );

                });

                break;


            // ------------------------------------------------------
            // VAS
            // ------------------------------------------------------

            case '5':

                total = FormHelper.setValidNumber(
                    $form,
                    names.vas
                );

                break;

        }


        $form
            .find(
                'input[name="' + names.skala + '"]'
            )
            .val(total);

    }


    // ==============================================================
    // SINGLE CHECKBOX
    // KHUSUS DALAM FORM INI
    // ==============================================================

    $form.on(
        'change',
        'input[type="checkbox"]',
        function () {

            const $this = $(this);

            if (!$this.is(':checked')) {
                return;
            }


            const name = $this.attr('name');


            /*
            |--------------------------------------------------------------------------
            | Hanya checkbox dengan name yang sama
            | di dalam form transfer ini yang dimatikan.
            |--------------------------------------------------------------------------
            */

            $form
                .find(
                    'input[type="checkbox"][name="' + name + '"]'
                )
                .not($this)
                .prop('checked', false);

        }
    );


    // ==============================================================
    // PILIH METODE
    // ==============================================================

    $form.on(
        'change',
        'input[name="{{ $metodeName }}"]',
        function () {

            const $this = $(this);

            const metode = $this.val();


            if ($this.is(':checked')) {


                // Pastikan hanya satu metode
                $form
                    .find(
                        'input[name="{{ $metodeName }}"]'
                    )
                    .not($this)
                    .prop('checked', false);


                // Reset skor
                resetSemuaSkor();


                // Hide semua
                hideSemuaMetode();


                // Tampilkan metode
                if (metodeMap[metode]) {

                    $form
                        .find(metodeMap[metode])
                        .prop('hidden', false);

                }

            }

            else {

                hideSemuaMetode();

                resetSemuaSkor();

            }

        }
    );


    // ==============================================================
    // NRS
    // ==============================================================

    $form.on(
        'input',
        '#{{ $nrsInputId }}',
        function () {

            hitungSkorNyeri('1');

        }
    );


    // ==============================================================
    // BPS
    // ==============================================================

    $form.on(
        'input',
        '#{{ $bpsId }} input[type="number"]',
        function () {

            hitungSkorNyeri('2');

        }
    );


    // ==============================================================
    // NIPS
    // ==============================================================

    $form.on(
        'input',
        '#{{ $nipsId }} input[type="number"]',
        function () {

            hitungSkorNyeri('3');

        }
    );


    // ==============================================================
    // FLACC
    // ==============================================================

    $form.on(
        'input',
        '#{{ $flaccId }} input[type="number"]',
        function () {

            hitungSkorNyeri('4');

        }
    );


    // ==============================================================
    // VAS
    // ==============================================================

    $form.on(
        'input',
        '#{{ $vasInputId }}',
        function () {

            hitungSkorNyeri('5');

        }
    );


    // ==============================================================
    // GET DATA
    // ==============================================================

    function getSkriningNyeriTransfer() {

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/skrining/nyeri-transfer/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            data: {

                transfer: transfer

            },


            success: function (res) {

                const nyeri = res.data;


                // --------------------------------------------------
                // BELUM ADA DATA
                // --------------------------------------------------

                if (!nyeri) {

                    hideSemuaMetode();

                    return;

                }


                // --------------------------------------------------
                // DATA DASAR
                // --------------------------------------------------

                FormHelper.setSingleCheckbox(
                    $form,
                    names.nyeri,
                    nyeri.NYERI
                );


                FormHelper.setSingleCheckbox(
                    $form,
                    names.onset,
                    nyeri.ONSET
                );


                FormHelper.setSingleCheckbox(
                    $form,
                    names.metode,
                    nyeri.METODE
                );


                FormHelper.setValue(
                    $form,
                    names.skala,
                    nyeri.SKALA
                );


                FormHelper.setValue(
                    $form,
                    names.pencetus,
                    nyeri.PENCETUS
                );


                FormHelper.setValue(
                    $form,
                    names.gambaran,
                    nyeri.GAMBARAN
                );


                FormHelper.setValue(
                    $form,
                    names.durasi,
                    nyeri.DURASI
                );


                FormHelper.setValue(
                    $form,
                    names.lokasi,
                    nyeri.LOKASI
                );


                // --------------------------------------------------
                // HIDE SEMUA METODE
                // --------------------------------------------------

                hideSemuaMetode();


                // --------------------------------------------------
                // TAMPILKAN METODE
                // --------------------------------------------------

                if (
                    nyeri.METODE &&
                    metodeMap[nyeri.METODE]
                ) {

                    $form
                        .find(metodeMap[nyeri.METODE])
                        .prop('hidden', false);

                }


                // --------------------------------------------------
                // NRS
                // --------------------------------------------------

                if (Number(nyeri.METODE) === 1) {

                    FormHelper.setValue(
                        $form,
                        names.nrs,
                        nyeri.SKALA
                    );

                }


                // --------------------------------------------------
                // BPS
                // --------------------------------------------------

                else if (Number(nyeri.METODE) === 2) {

                    FormHelper.setValue(
                        $form,
                        names.bps1,
                        nyeri.SKOR1
                    );

                    FormHelper.setValue(
                        $form,
                        names.bps2,
                        nyeri.SKOR2
                    );

                    FormHelper.setValue(
                        $form,
                        names.bps3,
                        nyeri.SKOR3
                    );

                }


                // --------------------------------------------------
                // NIPS
                // --------------------------------------------------

                else if (Number(nyeri.METODE) === 3) {

                    FormHelper.setValue(
                        $form,
                        names.nips1,
                        nyeri.SKOR1
                    );

                    FormHelper.setValue(
                        $form,
                        names.nips2,
                        nyeri.SKOR2
                    );

                    FormHelper.setValue(
                        $form,
                        names.nips3,
                        nyeri.SKOR3
                    );

                    FormHelper.setValue(
                        $form,
                        names.nips4,
                        nyeri.SKOR4
                    );

                    FormHelper.setValue(
                        $form,
                        names.nips5,
                        nyeri.SKOR5
                    );

                    FormHelper.setValue(
                        $form,
                        names.nips6,
                        nyeri.SKOR6
                    );

                }


                // --------------------------------------------------
                // FLACC
                // --------------------------------------------------

                else if (Number(nyeri.METODE) === 4) {

                    FormHelper.setValue(
                        $form,
                        names.flacc1,
                        nyeri.SKOR1
                    );

                    FormHelper.setValue(
                        $form,
                        names.flacc2,
                        nyeri.SKOR2
                    );

                    FormHelper.setValue(
                        $form,
                        names.flacc3,
                        nyeri.SKOR3
                    );

                    FormHelper.setValue(
                        $form,
                        names.flacc4,
                        nyeri.SKOR4
                    );

                    FormHelper.setValue(
                        $form,
                        names.flacc5,
                        nyeri.SKOR5
                    );

                }


                // --------------------------------------------------
                // VAS
                // --------------------------------------------------

                else if (Number(nyeri.METODE) === 5) {

                    FormHelper.setValue(
                        $form,
                        names.vas,
                        nyeri.SKALA
                    );

                }

            },


            error: function (
                xhr,
                status,
                error
            ) {

                console.error(
                    'Error Get Skrining Nyeri Transfer:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Skrining Nyeri Transfer.';


                if (xhr.responseJSON?.message) {

                    message =
                        xhr.responseJSON.message;

                }


                console.warn(message);

            }

        });

    }


    // ==============================================================
    // SIMPAN
    // ==============================================================

    $form.on(
        'click',
        '.btn-save-skrining-nyeri-transfer',
        function () {

            const $button = $(this);

            const $currentForm = $button.closest(
                '.form-skrining-nyeri-transfer'
            );

            const transfer = Number(
                $currentForm.attr('data-transfer')
            );


            console.log(
                'SIMPAN SKRINING NYERI TRANSFER',
                {
                    transfer: transfer,
                    form: $currentForm.attr('id')
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Buat data dari form
            |--------------------------------------------------------------------------
            */

            const data = getFormDataByName(
                $currentForm,
                {
                    NOKUNJ: kunjungan,
                    transfer: transfer
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Karena NAME di Blade sudah unik berdasarkan transfer,
            | kita mapping kembali ke NAME yang dipahami controller.
            |--------------------------------------------------------------------------
            */

            const dataTransfer = {

                NOKUNJ: kunjungan,

                transfer: transfer,

                sn_nyeri:
                    $currentForm
                        .find(
                            'input[name="{{ $nyeriName }}"]:checked'
                        )
                        .val() ?? null,

                sn_onset:
                    $currentForm
                        .find(
                            'input[name="{{ $onsetName }}"]:checked'
                        )
                        .val() ?? null,

                sn_skala:
                    $currentForm
                        .find(
                            'input[name="{{ $skalaName }}"]'
                        )
                        .val() ?? null,

                sn_metode:
                    $currentForm
                        .find(
                            'input[name="{{ $metodeName }}"]:checked'
                        )
                        .val() ?? null,

                sn_nrs:
                    $currentForm
                        .find(
                            'input[name="{{ $nrsName }}"]'
                        )
                        .val() ?? null,

                sn_bps_1:
                    $currentForm
                        .find(
                            'input[name="{{ $bpsName1 }}"]'
                        )
                        .val() ?? null,

                sn_bps_2:
                    $currentForm
                        .find(
                            'input[name="{{ $bpsName2 }}"]'
                        )
                        .val() ?? null,

                sn_bps_3:
                    $currentForm
                        .find(
                            'input[name="{{ $bpsName3 }}"]'
                        )
                        .val() ?? null,

                sn_nips_1:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName1 }}"]'
                        )
                        .val() ?? null,

                sn_nips_2:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName2 }}"]'
                        )
                        .val() ?? null,

                sn_nips_3:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName3 }}"]'
                        )
                        .val() ?? null,

                sn_nips_4:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName4 }}"]'
                        )
                        .val() ?? null,

                sn_nips_5:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName5 }}"]'
                        )
                        .val() ?? null,

                sn_nips_6:
                    $currentForm
                        .find(
                            'input[name="{{ $nipsName6 }}"]'
                        )
                        .val() ?? null,

                sn_flacc_1:
                    $currentForm
                        .find(
                            'input[name="{{ $flaccName1 }}"]'
                        )
                        .val() ?? null,

                sn_flacc_2:
                    $currentForm
                        .find(
                            'input[name="{{ $flaccName2 }}"]'
                        )
                        .val() ?? null,

                sn_flacc_3:
                    $currentForm
                        .find(
                            'input[name="{{ $flaccName3 }}"]'
                        )
                        .val() ?? null,

                sn_flacc_4:
                    $currentForm
                        .find(
                            'input[name="{{ $flaccName4 }}"]'
                        )
                        .val() ?? null,

                sn_flacc_5:
                    $currentForm
                        .find(
                            'input[name="{{ $flaccName5 }}"]'
                        )
                        .val() ?? null,

                sn_vas:
                    $currentForm
                        .find(
                            'input[name="{{ $vasName }}"]'
                        )
                        .val() ?? null,

                sn_pencetus:
                    $currentForm
                        .find(
                            'input[name="{{ $pencetusName }}"]'
                        )
                        .val() ?? null,

                sn_gambaran:
                    $currentForm
                        .find(
                            'input[name="{{ $gambaranName }}"]'
                        )
                        .val() ?? null,

                sn_durasi:
                    $currentForm
                        .find(
                            'input[name="{{ $durasiName }}"]'
                        )
                        .val() ?? null,

                sn_lokasi:
                    $currentForm
                        .find(
                            'input[name="{{ $lokasiName }}"]'
                        )
                        .val() ?? null

            };


            console.log(
                'DATA YANG DIKIRIM:',
                dataTransfer
            );


            // ======================================================
            // AJAX
            // ======================================================

            $.ajax({

                url:
                    `/api/v2/emr/pengkajian/skrining/nyeri-transfer/${kunjungan}/simpan`,

                type: 'POST',

                data: dataTransfer,

                headers: {

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content')

                },


                beforeSend: function () {

                    $button
                        .prop('disabled', true)
                        .html(
                            '<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...'
                        );

                },


                success: function (res) {

                    Swal.fire({

                        position: 'top-end',

                        icon: 'success',

                        title:
                            res.message ||
                            'Data Skrining berhasil disimpan',

                        showConfirmButton: false,

                        timer: 1000,

                        toast: true

                    });

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

                    else if (
                        xhr.responseJSON?.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    iziToast.error({

                        title:
                            'Validasi Gagal!',

                        message:
                            message,

                        position:
                            'topRight'

                    });

                },


                complete: function () {

                    $button
                        .prop('disabled', false)
                        .html(
                            '<i class="ri-save-line me-1"></i> Simpan Skrining Nyeri'
                        );

                }

            });

        }
    );


    // ==============================================================
    // INITIAL
    // ==============================================================

    hideSemuaMetode();

    getSkriningNyeriTransfer();

})();

</script>
