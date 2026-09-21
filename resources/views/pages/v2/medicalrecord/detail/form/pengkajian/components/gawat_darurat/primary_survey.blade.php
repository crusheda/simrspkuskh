@php
    $page = $page ?? 'dokter';
    $section = $section ?? '#gd_dokter';
    $kunjungan = $kunjungan ?? null;
@endphp

<div id="form_gd_primary_survey">

    {{-- ==========================================================
        PRIMARY SURVEY - DOKTER
        ========================================================== --}}
    @if ($page === 'dokter')

        <div class="card card-body border border-dashed border-warning mb-3">

            <h5>
                I. Primary <b class="text-warning">Survey</b>
            </h5>

            <div class="row">

                {{-- KEADAAN UMUM --}}
                <div class="col-md-12 mb-3">

                    <div class="form-group">

                        <h6>Keadaan Umum</h6>

                        <div class="form-group">

                            <textarea
                                class="form-control"
                                name="keu"
                                rows="1"
                            ></textarea>

                        </div>

                    </div>

                </div>


                {{-- JALAN NAFAS --}}
                <div class="col-md-6 mb-3">

                    <div class="form-group">

                        <h6 class="mb-2">
                            Jalan Nafas
                            (
                            <b class="text-warning">A</b>
                            )
                        </h6>

                        <div class="form-check mb-2">

                            <input
                                class="form-check-input check-primary single-checkbox"
                                type="checkbox"
                                name="jn"
                                value="1"
                            >

                            <label class="form-check-label">
                                Paten
                            </label>

                        </div>

                        <div class="form-check mb-2">

                            <input
                                class="form-check-input check-primary single-checkbox"
                                type="checkbox"
                                name="jn"
                                value="2"
                            >

                            <label class="form-check-label">
                                Obstruksi Parsial
                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input check-primary single-checkbox"
                                type="checkbox"
                                name="jn"
                                value="3"
                            >

                            <label class="form-check-label">
                                Obstruksi Total
                            </label>

                        </div>

                    </div>

                </div>


                {{-- PERNAFASAN --}}
                <div class="col-md-6 mb-3">

                    <h6>
                        Pernafasan
                        (
                        <b class="text-warning">B</b>
                        )
                    </h6>

                    <div class="form-group">

                        <label class="form-label">
                            Frekuensi Nafas
                        </label>

                        <div class="d-flex align-items-center gap-3">

                            <div class="input-group flex-grow-1">

                                <input
                                    type="number"
                                    class="form-control"
                                    name="fr"
                                >

                                <span class="input-group-text">
                                    X/menit
                                </span>

                            </div>

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input single-checkbox"
                                    type="checkbox"
                                    name="fr_cb"
                                    value="1"
                                    checked
                                >

                                <label class="form-check-label">
                                    Simetris
                                </label>

                            </div>

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input single-checkbox"
                                    type="checkbox"
                                    name="fr_cb"
                                    value="2"
                                >

                                <label class="form-check-label">
                                    Asimetris
                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- SIRKULASI --}}
                <div class="col-md-6 mb-3">

                    <h6>
                        Sirkulasi
                        (
                        <b class="text-warning">C</b>
                        )
                    </h6>

                    {{-- TEKANAN DARAH --}}
                    <div class="form-group mb-3">

                        <label class="form-label">
                            Tekanan Darah (mmHg)
                        </label>

                        <div class="input-group mb-2">

                            <input
                                type="number"
                                class="form-control"
                                name="td_up"
                            >

                            <div class="input-group-text">
                                /
                            </div>

                            <input
                                type="number"
                                class="form-control"
                                name="td_down"
                            >

                            <div class="input-group-text">
                                mmHg
                            </div>

                        </div>

                    </div>


                    {{-- NADI --}}
                    <div class="form-group mb-3">

                        <label class="form-label">
                            Frekuensi Nadi
                        </label>

                        <div class="d-flex align-items-center gap-3">

                            <div class="input-group flex-grow-1">

                                <input
                                    type="number"
                                    class="form-control"
                                    name="nadi"
                                >

                                <span class="input-group-text">
                                    X/menit
                                </span>

                            </div>

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input single-checkbox"
                                    type="checkbox"
                                    name="fr_nadi"
                                    value="1"
                                    checked
                                >

                                <label class="form-check-label">
                                    Reguler
                                </label>

                            </div>

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input single-checkbox"
                                    type="checkbox"
                                    name="fr_nadi"
                                    value="2"
                                >

                                <label class="form-check-label">
                                    Ireguler
                                </label>

                            </div>

                        </div>

                    </div>


                    {{-- SUHU --}}
                    <div class="form-group mb-3">

                        <label class="form-label">
                            Suhu
                        </label>

                        <div class="input-group mb-2">

                            <input
                                type="number"
                                class="form-control"
                                name="suhu"
                            >

                            <div class="input-group-text">
                                °C
                            </div>

                        </div>

                    </div>


                    {{-- SPO2 --}}
                    <div class="form-group">

                        <label class="form-label">
                            SpO2
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                name="spo2"
                            >

                            <div class="input-group-text">
                                %
                            </div>

                        </div>

                    </div>

                </div>


                {{-- NEUROLOGI --}}
                <div class="col-md-6">

                    <h6>
                        Neorologi
                        (
                        <b class="text-warning">D</b>
                        )
                    </h6>

                    <div class="row">

                        {{-- TINGKAT KESADARAN --}}
                        <div class="col-md-12">

                            <div class="form-group mb-3">

                                <label class="form-label">
                                    Tingkat Kesadaran
                                </label>

                                <select
                                    class="form-control"
                                    name="tks"
                                >

                                    <option value="">
                                        Pilih
                                    </option>

                                    @if ($list['tingkat_kesadaran'] ?? false)

                                        @foreach ($list['tingkat_kesadaran'] as $item)

                                            <option value="{{ $item->ID }}">
                                                {{ $item->DESKRIPSI }}
                                            </option>

                                        @endforeach

                                    @endif

                                </select>

                            </div>

                        </div>


                        {{-- PUPIL --}}
                        <div class="col-md-12">

                            <div class="d-flex align-items-center column-gap-5 row-gap-3 flex-wrap mb-3">

                                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                    <label class="form-label mb-0">
                                        Pupil
                                    </label>

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="pupil"
                                            value="1"
                                            checked
                                        >

                                        <label class="form-check-label">
                                            Isokor
                                        </label>

                                    </div>

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="pupil"
                                            value="2"
                                        >

                                        <label class="form-check-label">
                                            Anisokor
                                        </label>

                                    </div>

                                </div>


                                {{-- DIAMETER PUPIL --}}
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                    <label class="form-label mb-0">
                                        Diameter Pupil
                                    </label>

                                    <div
                                        class="input-group input-group-sm"
                                        style="width: 250px;"
                                    >

                                        <input
                                            type="number"
                                            class="form-control"
                                            name="dia_up"
                                        >

                                        <div class="input-group-text">
                                            mm /
                                        </div>

                                        <input
                                            type="number"
                                            class="form-control"
                                            name="dia_down"
                                        >

                                        <div class="input-group-text">
                                            mm
                                        </div>

                                    </div>

                                </div>


                                {{-- REFLEKS CAHAYA --}}
                                <div class="d-flex align-items-center gap-3">

                                    <label class="form-label mb-0 flex-shrink-0">
                                        RC (Refleks Cahaya)
                                    </label>

                                    <div class="d-flex align-items-center gap-2">

                                        <input
                                            class="form-control"
                                            type="text"
                                            name="rc_up"
                                            placeholder="..."
                                            maxlength="1"
                                            style="width:60px;"
                                        >

                                    </div>

                                    <span class="text-danger fw-bold">
                                        /
                                    </span>

                                    <div class="d-flex align-items-center gap-2">

                                        <input
                                            class="form-control"
                                            type="text"
                                            name="rc_down"
                                            placeholder="..."
                                            maxlength="1"
                                            style="width:60px;"
                                        >

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- GCS --}}
                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="form-label">
                                    GCS
                                    (<i>Glasgow Coma Scale</i>)
                                </label>

                                <div class="d-flex align-items-center column-gap-3 row-gap-3 flex-wrap mb-3">

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                        <label class="form-check-label">
                                            Eye
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            name="gcs_e"
                                            min="1"
                                            max="4"
                                            style="width: 70px; flex: 0 0 60px;"
                                        >

                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                        <label class="form-check-label">
                                            Verbal
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            name="gcs_v"
                                            min="1"
                                            max="5"
                                            style="width: 70px; flex: 0 0 60px;"
                                        >

                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                        <label class="form-check-label">
                                            Move
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            name="gcs_m"
                                            min="1"
                                            max="6"
                                            style="width: 70px; flex: 0 0 60px;"
                                        >

                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                        <label class="form-check-label">
                                            Total
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            name="gcs_t"
                                            style="width: 70px; flex: 0 0 60px;"
                                            readonly
                                        >

                                    </div>

                                </div>

                            </div>


                            {{-- VAS --}}
                            <div class="form-group mb-3">

                                <label class="form-label">
                                    VAS
                                    (<i>Visual Analog Scale</i>)
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="vas"
                                >

                            </div>


                            <div class="row">

                                {{-- ALAT BANTU NAFAS --}}
                                <div class="col-md-4">

                                    <div class="mb-3">

                                        <h6>
                                            Alat Bantu Nafas
                                        </h6>

                                        <div class="form-group">

                                            <div class="form-check form-check-inline mb-2">

                                                <input
                                                    class="form-check-input check-primary single-checkbox"
                                                    type="checkbox"
                                                    name="abn"
                                                    value="2"
                                                >

                                                <label class="form-check-label">
                                                    Ya
                                                </label>

                                            </div>

                                            <div class="form-check form-check-inline mb-2">

                                                <input
                                                    class="form-check-input check-primary single-checkbox"
                                                    type="checkbox"
                                                    name="abn"
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


                                {{-- KULIT --}}
                                <div class="col-md-8">

                                    <div class="mb-3">

                                        <h6>
                                            Kulit
                                        </h6>

                                        <div class="form-group">

                                            @foreach([
                                                1 => 'Normal',
                                                2 => 'Jaundice',
                                                3 => 'Akral Dingin',
                                                4 => 'Sianotik',
                                                5 => 'Berkeringat'
                                            ] as $value => $label)

                                                <div class="form-check form-check-inline mb-2">

                                                    <input
                                                        class="form-check-input check-primary single-checkbox"
                                                        type="checkbox"
                                                        name="kulit"
                                                        value="{{ $value }}"
                                                        @if ($value === 1) checked @endif
                                                    >

                                                    <label class="form-check-label">
                                                        {{ $label }}
                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- STATUS REPRODUKSI --}}
                <hr class="mt-3">

                <div class="col-md-12">

                    <div class="d-flex align-items-center gap-3">

                        <h6 class="mb-0">
                            Status Reproduksi
                        </h6>

                        <div class="form-check m-0">

                            <input
                                class="form-check-input single-checkbox"
                                type="checkbox"
                                name="sr"
                                value="1"
                                checked
                            >

                            <label class="form-check-label">
                                Tidak
                            </label>

                        </div>

                        <div class="form-check m-0">

                            <input
                                class="form-check-input single-checkbox"
                                type="checkbox"
                                name="sr"
                                value="2"
                            >

                            <label class="form-check-label">
                                Kasus Obstetri Ginekologi
                            </label>

                        </div>

                    </div>


                    <div
                        class="form-group mt-3"
                        id="tampil_sr_ya"
                        hidden
                    >

                        <div class="row">

                            <div class="col-md-6">

                                <div class="d-flex align-items-center gap-2 mb-2">

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="sr_cb"
                                            value="2"
                                        >

                                    </div>

                                    <label class="form-label mb-0 me-2">
                                        HPHT
                                    </label>

                                    <div class="input-group input-group-sm">

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sr_hpht"
                                        >

                                    </div>

                                    <label class="form-label mb-0 ms-2 me-2">
                                        Siklus
                                    </label>

                                    <div class="input-group input-group-sm">

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sr_siklus"
                                        >

                                    </div>

                                </div>


                                <div class="d-flex align-items-center gap-2 mb-2">

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="sr_cb"
                                            value="3"
                                        >

                                    </div>

                                    <label class="form-label mb-0 me-2">
                                        KB
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="sr_kb"
                                    >

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="d-flex align-items-center gap-2 mb-3">

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="sr_cb"
                                            value="1"
                                        >

                                    </div>

                                    <label class="form-label mb-0 me-2">
                                        Hamil
                                    </label>

                                    <div class="input-group input-group-sm">

                                        <span class="input-group-text">
                                            Gravida
                                        </span>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sr_grv"
                                        >

                                        <span class="input-group-text">
                                            Paritas
                                        </span>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sr_prt"
                                        >

                                        <span class="input-group-text">
                                            Abortus
                                        </span>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sr_abr"
                                        >

                                    </div>

                                </div>


                                <div class="d-flex align-items-center gap-2">

                                    <div class="form-check m-0">

                                        <input
                                            class="form-check-input single-checkbox"
                                            type="checkbox"
                                            name="sr_cb"
                                            value="0"
                                        >

                                    </div>

                                    <label class="form-label mb-0">
                                        Tidak Hamil
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @else


    {{-- ==========================================================
        PRIMARY SURVEY - PERAWAT
        ========================================================== --}}

        <div class="col-md-12 mb-3">

            <div class="card card-body border border-dashed border-warning">

                <h6 class="fs-16">
                    Tanda Vital
                </h6>

                <div class="row">

                    {{-- KEADAAN UMUM --}}
                    <div class="col-md-12 mb-3">

                        <div class="form-group">

                            <h6>
                                Keadaan Umum
                            </h6>

                            <input
                                type="text"
                                class="form-control"
                                name="p_keu"
                            >

                        </div>

                    </div>

                    <div class="col-md-6 mb-3">

                        {{-- JALAN NAFAS --}}
                        <div class="form-group">

                            <h6 class="mb-2">
                                Jalan Nafas
                                (
                                <b class="text-warning">A</b>
                                )
                            </h6>

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary single-checkbox"
                                    type="checkbox"
                                    name="p_jn"
                                    value="1"
                                >

                                <label class="form-check-label">
                                    Paten
                                </label>

                            </div>

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input check-primary single-checkbox"
                                    type="checkbox"
                                    name="p_jn"
                                    value="2"
                                >

                                <label class="form-check-label">
                                    Obstruksi Parsial
                                </label>

                            </div>

                            <div class="form-check">

                                <input
                                    class="form-check-input check-primary single-checkbox"
                                    type="checkbox"
                                    name="p_jn"
                                    value="3"
                                >

                                <label class="form-check-label">
                                    Obstruksi Total
                                </label>

                            </div>

                        </div>

                    </div>


                    {{-- PERNAFASAN --}}
                    <div class="col-md-6 mb-3">

                        <h6>
                            Pernafasan
                            (
                            <b class="text-warning">B</b>
                            )
                        </h6>

                        <div class="form-group">

                            <label class="form-label">
                                Frekuensi Nafas
                            </label>

                            <div class="d-flex align-items-center gap-3">

                                <div class="input-group flex-grow-1">

                                    <input
                                        type="number"
                                        class="form-control"
                                        name="p_fr"
                                    >

                                    <span class="input-group-text">
                                        X/menit
                                    </span>

                                </div>

                                <div class="form-check m-0">

                                    <input
                                        class="form-check-input single-checkbox"
                                        type="checkbox"
                                        name="p_fr_cb"
                                        value="1"
                                        checked
                                    >

                                    <label class="form-check-label">
                                        Simetris
                                    </label>

                                </div>

                                <div class="form-check m-0">

                                    <input
                                        class="form-check-input single-checkbox"
                                        type="checkbox"
                                        name="p_fr_cb"
                                        value="2"
                                    >

                                    <label class="form-check-label">
                                        Asimetris
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SIRKULASI --}}
                    <div class="col-md-6 mb-3">

                        <h6>
                            Sirkulasi
                            (
                            <b class="text-warning">C</b>
                            )
                        </h6>


                        {{-- TEKANAN DARAH --}}
                        <div class="form-group mb-3">

                            <label class="form-label">
                                Tekanan Darah (mmHg)
                            </label>

                            <div class="input-group mb-2">

                                <input
                                    type="number"
                                    class="form-control"
                                    name="p_td_up"
                                >

                                <div class="input-group-text">
                                    /
                                </div>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="p_td_down"
                                >

                                <div class="input-group-text">
                                    mmHg
                                </div>

                            </div>

                        </div>


                        {{-- NADI --}}
                        <div class="form-group mb-3">

                            <label class="form-label">
                                Frekuensi Nadi
                            </label>

                            <div class="d-flex align-items-center gap-3">

                                <div class="input-group flex-grow-1">

                                    <input
                                        type="number"
                                        class="form-control"
                                        name="p_nadi"
                                    >

                                    <span class="input-group-text">
                                        X/menit
                                    </span>

                                </div>

                                <div class="form-check m-0">

                                    <input
                                        class="form-check-input single-checkbox"
                                        type="checkbox"
                                        name="p_fr_nadi"
                                        value="1"
                                        checked
                                    >

                                    <label class="form-check-label">
                                        Reguler
                                    </label>

                                </div>

                                <div class="form-check m-0">

                                    <input
                                        class="form-check-input single-checkbox"
                                        type="checkbox"
                                        name="p_fr_nadi"
                                        value="2"
                                    >

                                    <label class="form-check-label">
                                        Ireguler
                                    </label>

                                </div>

                            </div>

                        </div>


                        {{-- SUHU --}}
                        <div class="form-group mb-3">

                            <label class="form-label">
                                Suhu
                            </label>

                            <div class="input-group mb-2">

                                <input
                                    type="number"
                                    class="form-control"
                                    name="p_suhu"
                                >

                                <div class="input-group-text">
                                    °C
                                </div>

                            </div>

                        </div>


                        {{-- SPO2 --}}
                        <div class="form-group">

                            <label class="form-label">
                                SpO2
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    class="form-control"
                                    name="p_spo2"
                                >

                                <div class="input-group-text">
                                    %
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- NEUROLOGI --}}
                    <div class="col-md-6 mb-3">

                        <h6>
                            Neorologi
                            (
                            <b class="text-warning">D</b>
                            )
                        </h6>

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Tingkat Kesadaran
                                    </label>

                                    <select
                                        class="form-control"
                                        name="p_tks"
                                    >

                                        <option value="">
                                            Pilih
                                        </option>

                                        @if ($list['tingkat_kesadaran'] ?? false)

                                            @foreach ($list['tingkat_kesadaran'] as $item)

                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>

                                            @endforeach

                                        @endif

                                    </select>

                                </div>

                            </div>


                            {{-- GCS --}}
                            <div class="col-md-12">

                                <div class="form-group">

                                    <label class="form-label">
                                        GCS
                                        (<i>Glasgow Coma Scale</i>)
                                    </label>

                                    <div class="d-flex align-items-center column-gap-3 row-gap-3 flex-wrap mb-3">

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                            <label class="form-check-label">
                                                Eye
                                            </label>

                                            <input
                                                type="number"
                                                class="form-control form-control-sm"
                                                name="p_gcs_e"
                                                min="1"
                                                max="4"
                                                style="width: 70px; flex: 0 0 60px;"
                                            >

                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                            <label class="form-check-label">
                                                Verbal
                                            </label>

                                            <input
                                                type="number"
                                                class="form-control form-control-sm"
                                                name="p_gcs_v"
                                                min="1"
                                                max="5"
                                                style="width: 70px; flex: 0 0 60px;"
                                            >

                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                            <label class="form-check-label">
                                                Move
                                            </label>

                                            <input
                                                type="number"
                                                class="form-control form-control-sm"
                                                name="p_gcs_m"
                                                min="1"
                                                max="6"
                                                style="width: 70px; flex: 0 0 60px;"
                                            >

                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                            <label class="form-check-label">
                                                Total
                                            </label>

                                            <input
                                                type="number"
                                                class="form-control form-control-sm"
                                                name="p_gcs_t"
                                                style="width: 70px; flex: 0 0 60px;"
                                                readonly
                                            >

                                        </div>

                                    </div>

                                </div>


                                {{-- VAS --}}
                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        VAS
                                        (<i>Visual Analog Scale</i>)
                                    </label>

                                    <input
                                        type="number"
                                        class="form-control"
                                        name="p_vas"
                                    >

                                </div>


                                <div class="row">

                                    {{-- ALAT BANTU NAFAS --}}
                                    <div class="col-md-4">

                                        <div class="mb-3">

                                            <h6>
                                                Alat Bantu Nafas
                                            </h6>

                                            <div class="form-group">

                                                <div class="form-check form-check-inline mb-2">

                                                    <input
                                                        class="form-check-input check-primary single-checkbox"
                                                        type="checkbox"
                                                        name="p_abn"
                                                        value="2"
                                                    >

                                                    <label class="form-check-label">
                                                        Ya
                                                    </label>

                                                </div>

                                                <div class="form-check form-check-inline mb-2">

                                                    <input
                                                        class="form-check-input check-primary single-checkbox"
                                                        type="checkbox"
                                                        name="p_abn"
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


                                    {{-- KULIT --}}
                                    <div class="col-md-8">

                                        <div class="mb-3">

                                            <h6>
                                                Kulit
                                            </h6>

                                            <div class="form-group">

                                                @foreach([
                                                    1 => 'Normal',
                                                    2 => 'Jaundice',
                                                    3 => 'Akral Dingin',
                                                    4 => 'Sianotik',
                                                    5 => 'Berkeringat'
                                                ] as $value => $label)

                                                    <div class="form-check form-check-inline mb-2">

                                                        <input
                                                            class="form-check-input check-primary single-checkbox"
                                                            type="checkbox"
                                                            name="p_kulit"
                                                            value="{{ $value }}"
                                                            @if ($value === 1) checked @endif
                                                        >

                                                        <label class="form-check-label">
                                                            {{ $label }}
                                                        </label>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>


                                    {{-- SKALA NYERI --}}
                                    <div class="col-md-3 mb-2">

                                        <div class="form-group">

                                            <h6>
                                                Skala Nyeri
                                            </h6>

                                            <div class="input-group">

                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="p_sn"
                                                >

                                            </div>

                                        </div>

                                    </div>


                                    {{-- METODE UKUR --}}
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <h6>
                                                Metode Ukur
                                            </h6>

                                            <input
                                                type="text"
                                                class="form-control"
                                                name="p_mu"
                                            >

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>

<script>
(function () {

    const $section = $('{{ $section }}');

    if (!$section.length) {
        return;
    }

    const kunjungan = @json($kunjungan);
    const page = @json($page);

    const $form = $section.find('#form_gd_primary_survey').first();

    if (!$form.length) {
        return;
    }

    let isDataLoading = false;
    let isDataSaving = false;


    // ==========================================================
    // URL
    // ==========================================================

    const urlGet =
        `/api/v2/emr/pengkajian/gd/ps/${kunjungan}`;

    const urlSave =
        `/api/v2/emr/pengkajian/gd/ps/${kunjungan}/simpan`;


    // ==========================================================
    // GCS
    // ==========================================================

    $form.on(
        'input',
        'input[name="gcs_e"], input[name="gcs_v"], input[name="gcs_m"], input[name="p_gcs_e"], input[name="p_gcs_v"], input[name="p_gcs_m"]',
        function () {

            const prefix = page === 'perawat'
                ? 'p_gcs'
                : 'gcs';

            FormHelper.hitungGCS(
                $section,
                prefix
            );

        }
    );


    // ==========================================================
    // REFLEKS CAHAYA - DOKTER
    // ==========================================================

    if (page === 'dokter') {

        $form.on(
            'input',
            'input[name^="rc_"]',
            function () {

                this.value =
                    this.value
                        .replace(/[^+-]/g, '')
                        .charAt(0);

            }
        );

    }


    // ==========================================================
    // STATUS REPRODUKSI - DOKTER
    // ==========================================================

    if (page === 'dokter') {

        $form.on(
            'change',
            '[name="sr"]',
            function () {

                const nilai =
                    $form
                        .find('[name="sr"]:checked')
                        .val();

                if (nilai === '2') {

                    $form
                        .find('#tampil_sr_ya')
                        .prop('hidden', false);

                } else {

                    $form
                        .find('#tampil_sr_ya')
                        .prop('hidden', true);

                    resetStatusReproduksi();

                }

            }
        );

    }


    function resetStatusReproduksi() {

        $form
            .find('input[name="sr_cb"]')
            .prop('checked', false)
            .prop('disabled', false);

        $form
            .find('input[name="sr_hpht"]')
            .val('');

        $form
            .find('input[name="sr_siklus"]')
            .val('');

        $form
            .find('input[name="sr_kb"]')
            .val('');

        $form
            .find('input[name="sr_grv"]')
            .val('');

        $form
            .find('input[name="sr_prt"]')
            .val('');

        $form
            .find('input[name="sr_abr"]')
            .val('');
    }


    // ==========================================================
    // GET DATA
    // ==========================================================

    function getDataPrimarySurvey() {

        if (!kunjungan) {
            return;
        }

        isDataLoading = true;

        $.ajax({

            url: urlGet,

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                if (
                    !response ||
                    response.status !== true
                ) {
                    return;
                }

                const data =
                    response.data || {};

                const tandaVital =
                    data.tanda_vital || {};


                // ==================================================
                // DOKTER
                // ==================================================

                if (page === 'dokter') {

                    FormHelper.setValue(
                        $form,
                        'keu',
                        tandaVital.KEADAAN_UMUM
                    );

                    FormHelper.setValue(
                        $form,
                        'tks',
                        tandaVital.TINGKAT_KESADARAN
                    );

                    FormHelper.setValue(
                        $form,
                        'fr',
                        tandaVital.FREKUENSI_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'fr_cb',
                        tandaVital.FREKUENSI_NAFAS_CB
                    );

                    FormHelper.setValue(
                        $form,
                        'nadi',
                        tandaVital.FREKUENSI_NADI
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'fr_nadi',
                        tandaVital.FREKUENSI_NADI_CB
                    );

                    FormHelper.setValue(
                        $form,
                        'td_up',
                        tandaVital.SISTOLIK
                    );

                    FormHelper.setValue(
                        $form,
                        'td_down',
                        tandaVital.DISTOLIK
                    );

                    FormHelper.setValue(
                        $form,
                        'suhu',
                        tandaVital.SUHU
                    );

                    FormHelper.setValue(
                        $form,
                        'spo2',
                        tandaVital.SATURASI_O2
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'pupil',
                        tandaVital.PUPIL
                    );

                    FormHelper.setValue(
                        $form,
                        'dia_up',
                        tandaVital.DIAMETER_PUPIL_UP
                    );

                    FormHelper.setValue(
                        $form,
                        'dia_down',
                        tandaVital.DIAMETER_PUPIL_DOWN
                    );

                    FormHelper.setValue(
                        $form,
                        'rc_up',
                        tandaVital.RC_UP
                    );

                    FormHelper.setValue(
                        $form,
                        'rc_down',
                        tandaVital.RC_DOWN
                    );

                    FormHelper.setValue(
                        $form,
                        'gcs_e',
                        tandaVital.EYE
                    );

                    FormHelper.setValue(
                        $form,
                        'gcs_v',
                        tandaVital.VERBAL
                    );

                    FormHelper.setValue(
                        $form,
                        'gcs_m',
                        tandaVital.MOTORIK
                    );

                    FormHelper.setValue(
                        $form,
                        'gcs_t',
                        tandaVital.GCS
                    );

                    FormHelper.setValue(
                        $form,
                        'vas',
                        tandaVital.VAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'jn',
                        tandaVital.JALAN_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'abn',
                        tandaVital.ALAT_BANTU_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'kulit',
                        tandaVital.KULIT
                    );


                    // STATUS REPRODUKSI

                    const reproduksi =
                        data.status_reproduksi || {};

                    FormHelper.setSingleCheckbox(
                        $form,
                        'sr',
                        reproduksi.KASUS_OBSTETRI_GINEKOLOGI
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'sr_cb',
                        reproduksi.STATUS_REPRODUKSI
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_hpht',
                        reproduksi.HPHT
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_siklus',
                        reproduksi.SIKLUS
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_kb',
                        reproduksi.KB
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_grv',
                        reproduksi.HAMIL_GRAVIDA
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_prt',
                        reproduksi.HAMIL_PARITAS
                    );

                    FormHelper.setValue(
                        $form,
                        'sr_abr',
                        reproduksi.HAMIL_ABORTUS
                    );


                    const sr =
                        $form
                            .find('[name="sr"]:checked')
                            .val();

                    $form
                        .find('#tampil_sr_ya')
                        .prop(
                            'hidden',
                            sr !== '2'
                        );

                }


                // ==================================================
                // PERAWAT
                // ==================================================

                else {

                    FormHelper.setValue(
                        $form,
                        'p_keu',
                        tandaVital.KEADAAN_UMUM
                    );

                    FormHelper.setValue(
                        $form,
                        'p_tks',
                        tandaVital.TINGKAT_KESADARAN
                    );

                    FormHelper.setValue(
                        $form,
                        'p_fr',
                        tandaVital.FREKUENSI_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_fr_cb',
                        tandaVital.FREKUENSI_NAFAS_CB
                    );

                    FormHelper.setValue(
                        $form,
                        'p_nadi',
                        tandaVital.FREKUENSI_NADI
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_fr_nadi',
                        tandaVital.FREKUENSI_NADI_CB
                    );

                    FormHelper.setValue(
                        $form,
                        'p_td_up',
                        tandaVital.SISTOLIK
                    );

                    FormHelper.setValue(
                        $form,
                        'p_td_down',
                        tandaVital.DISTOLIK
                    );

                    FormHelper.setValue(
                        $form,
                        'p_suhu',
                        tandaVital.SUHU
                    );

                    FormHelper.setValue(
                        $form,
                        'p_spo2',
                        tandaVital.SATURASI_O2
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_e',
                        tandaVital.EYE
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_v',
                        tandaVital.VERBAL
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_m',
                        tandaVital.MOTORIK
                    );

                    FormHelper.setValue(
                        $form,
                        'p_gcs_t',
                        tandaVital.GCS
                    );

                    FormHelper.setValue(
                        $form,
                        'p_vas',
                        tandaVital.VAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_jn',
                        tandaVital.JALAN_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_abn',
                        tandaVital.ALAT_BANTU_NAFAS
                    );

                    FormHelper.setSingleCheckbox(
                        $form,
                        'p_kulit',
                        tandaVital.KULIT
                    );

                    // Field perawat yang belum ada di
                    // medicalrecord.tanda_vital pada mapping lama
                    // tetap tidak dipaksakan.
                    //
                    // p_sn dan p_mu tidak dimasukkan ke tabel
                    // medicalrecord.tanda_vital karena mapping
                    // controller lama memang tidak menyimpannya.

                }

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil Primary Survey:',
                    xhr.responseText
                );

            },

            complete: function () {

                isDataLoading = false;

            }

        });

    }


    // ==========================================================
    // SAVE
    // ==========================================================

    function simpanPrimarySurvey() {

        if (
            isDataLoading ||
            isDataSaving ||
            !kunjungan
        ) {
            return;
        }

        isDataSaving = true;


        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan,
                    page: page
                }
            );


        $.ajax({

            url: urlSave,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            success: function (response) {

                if (
                    response &&
                    response.status === true
                ) {
                    return;
                }

                if (
                    typeof iziToast !== 'undefined'
                ) {

                    iziToast.error({

                        title: 'Gagal',

                        position: 'topRight',

                        message:
                            response?.message ||
                            'Primary Survey gagal disimpan.'

                    });

                }

            },


            error: function (xhr) {

                if (
                    typeof iziToast !== 'undefined'
                ) {

                    iziToast.error({

                        title: 'Gagal',

                        position: 'topRight',

                        message:
                            xhr.responseJSON?.message ||
                            'Primary Survey gagal disimpan.'

                    });

                }

            },


            complete: function () {

                isDataSaving = false;

            }

        });

    }


    // ==========================================================
    // AUTOSAVE CHECKBOX / RADIO / SELECT
    // ==========================================================

    $form.on(
        'change',
        'input[type="checkbox"], input[type="radio"], select',
        function () {

            if (isDataLoading) {
                return;
            }

            simpanPrimarySurvey();

        }
    );


    // ==========================================================
    // AUTOSAVE INPUT / TEXTAREA
    // TANPA TIMER / DEBOUNCE
    // ==========================================================

    $form.on(
        'blur',
        'input:not([type="checkbox"]):not([type="radio"]):not([readonly]), textarea',
        function () {

            if (isDataLoading) {
                return;
            }

            simpanPrimarySurvey();

        }
    );


    // ==========================================================
    // INIT
    // ==========================================================

    getDataPrimarySurvey();

})();
</script>
