@php
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI COMPONENT
    |--------------------------------------------------------------------------
    |
    | Contoh pemanggilan:
    |
    | @include(
    |     'pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_anatomi',
    |     [
    |         'section' => '#ri_dokter',
    |         'metodePemeriksaan' => ['mata', 'perut']
    |     ]
    | )
    |
    */
    $section = $section ?? null;
    $semuaMetode = [
        'mata',
        'tenggorokan',
        'leher',
        'dada',
        'perut',
        'status_lokalis',
    ];
    $metodePemeriksaan = $metodePemeriksaan ?? $semuaMetode;
    $metodePemeriksaan = collect($metodePemeriksaan)
        ->map(fn ($item) => strtolower(trim($item)))
        ->filter(fn ($item) => in_array($item, $semuaMetode))
        ->unique()
        ->values()
        ->toArray();
    $tampilkan = function ($metode) use ($metodePemeriksaan) {
        return in_array($metode, $metodePemeriksaan);
    };
    $pemeriksaanAktif = collect($semuaMetode)
        ->filter(fn ($metode) => in_array($metode, $metodePemeriksaan))
        ->values()
        ->toArray();
    $hrSebelum = function ($metode) use ($pemeriksaanAktif) {
        $index = array_search($metode, $pemeriksaanAktif);
        return $index !== false && $index > 0;
    };
@endphp

<div class="pemeriksaan-anatomi-component" data-pemeriksaan-anatomi data-section="{{ $section }}">
    {{-- ==========================================================
         PEMERIKSAAN MATA
         ========================================================== --}}
    @if ($tampilkan('mata'))
        @if ($hrSebelum('mata'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="mata">
            {{-- Mata : Anemis & Ikterik --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center">
                        <label class="form-label fw-bold mb-0 flex-grow-1">
                            Mata
                        </label>
                        <div style="width: 320px;">
                            <div class="d-flex align-items-center mb-3">
                                <label class="form-label mb-0 flex-shrink-0" style="width: 120px;">
                                    Anemis
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_anemis" value="0">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_anemis" value="1">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <label class="form-label mb-0 flex-shrink-0" style="width: 120px;">
                                    Ikterus
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_ikterus" value="0">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_ikterus" value="1">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <label class="form-label mb-0 flex-shrink-0" style="width: 120px;">
                                    Udem Palpebrae
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_upal" value="0">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="pf_upal" value="1">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 flex-grow-1">
                            Pupil
                        </label>
                        <div class="d-flex align-items-center gap-3" style="width: 250px;">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_pupil" value="1" checked>
                                <label class="form-check-label">Isokor</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_pupil" value="2">
                                <label class="form-check-label">Anisokor</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 flex-grow-1">
                            Diameter Pupil
                        </label>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="number" class="form-control" name="pf_dia_up" value="0">
                            <div class="input-group-text">mm /</div>
                            <input type="number" class="form-control" name="pf_dia_down" value="0">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 flex-grow-1">
                            Ada Kelainan Mata ?
                        </label>
                        <textarea
                            class="form-control form-control-sm"
                            name="pf_kelainan_mata"
                            rows="1"
                            style="width: 250px;"
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==========================================================
         PEMERIKSAAN TENGGOROKAN
         ========================================================== --}}
    @if ($tampilkan('tenggorokan'))
        @if ($hrSebelum('tenggorokan'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="tenggorokan">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Mulut</label>
                        <textarea class="form-control" name="pf_mulut" rows="1"></textarea>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==========================================================
         PEMERIKSAAN LEHER
         ========================================================== --}}
    @if ($tampilkan('leher'))
        @if ($hrSebelum('leher'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="leher">
            {{-- JVP --}}
            <div class="col-md-12 mt-0">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Jugular Venous Pressure (JVP)</label>
                        <input type="text" class="form-control form-control-sm" name="pf_jvp">
                    </div>
                </div>
            </div>
            {{-- Pembesaran Kelenjar Limfe --}}
            <div class="col-md-12 mt-0">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Pembesaran Kelenjar Limfe</label>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_pkl_lain" name="pf_pkl" value="0" checked>
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_pkl_lain" name="pf_pkl" value="1">
                            <label class="form-check-label">Ada</label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_pkl_lain" id="pf_pkl_lain" disabled>
                    </div>
                </div>
            </div>
            {{-- Kaku Duduk --}}
            <div class="col mt-0">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Kaku Duduk</label>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_kd_lain" name="pf_kd" value="0" checked>
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_kd_lain" name="pf_kd" value="1">
                            <label class="form-check-label">Ada</label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_kd_lain" id="pf_kd_lain" disabled>
                    </div>
                </div>
            </div>
            {{-- Kelainan Leher --}}
            <div class="col-md-3 mt-0">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold mb-0 flex-shrink-0">Ada Kelainan Leher?</label>
                        <select class="form-select form-select-sm" name="pf_kelainan_leher">
                            <option value="0">Tidak Diperiksa</option>
                            <option value="1">Tidak Ada</option>
                            <option value="2">Ada</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==========================================================
         PEMERIKSAAN DADA
         ========================================================== --}}
    @if ($tampilkan('dada'))
        @if ($hrSebelum('dada'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="dada">
            {{-- Thoraks --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Thoraks</label>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_thoraks_lain" name="pf_thoraks" value="1" checked>
                            <label class="form-check-label">Simetris</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_thoraks_lain" name="pf_thoraks" value="2">
                            <label class="form-check-label">Asimetris</label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_thoraks_lain" id="pf_thoraks_lain" disabled>
                    </div>
                </div>
            </div>
            {{-- Cor --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Cor S1/S2 Irama</label>
                        <input type="text" class="form-control form-control-sm" name="pf_cor">
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_cor_cb" value="1">
                            <label class="form-check-label">Reguler</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_cor_cb" value="2">
                            <label class="form-check-label">Ireguler</label>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Murmur --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Murmur</label>
                        <input type="text" class="form-control form-control-sm" name="pf_murmur">
                    </div>
                </div>
            </div>
            {{-- Murmur Lain-lain --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Lain-lain</label>
                        <input type="text" class="form-control form-control-sm" name="pf_murmur_lain">
                    </div>
                </div>
            </div>
            {{-- Pulmo --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold flex-shrink-0">Pulmo</label>
                        <input type="text" class="form-control form-control-sm" name="pf_pulmo">
                    </div>
                </div>
                {{-- Ronchi --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Ronchi</label>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_ronchi_lain" name="pf_ronchi" value="1">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_ronchi_lain" name="pf_ronchi" value="2">
                            <label class="form-check-label">Ada</label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_ronchi_lain" id="pf_ronchi_lain" disabled>
                    </div>
                </div>
                {{-- Wheezing --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Wheezing</label>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_wheezing_lain" name="pf_wheezing" value="1">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                        <div class="form-check flex-shrink-0 m-0">
                            <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_wheezing_lain" name="pf_wheezing" value="2">
                            <label class="form-check-label">Ada</label>
                        </div>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_wheezing_lain" id="pf_wheezing_lain" disabled>
                    </div>
                </div>
                {{-- Kelainan Dada --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-bold mb-0 flex-shrink-0">Ada Kelainan Dada?</label>
                        <select class="form-select form-select-sm" name="pf_kelainan_dada">
                            <option value="0">Tidak Diperiksa</option>
                            <option value="1">Tidak Ada</option>
                            <option value="2">Ada</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==========================================================
         PEMERIKSAAN PERUT
         ========================================================== --}}
    @if ($tampilkan('perut'))
        @if ($hrSebelum('perut'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="perut">
            {{-- Distended --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Abdomen : Distended</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_distended" value="0">
                                <label class="form-check-label">Tidak</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_distended" value="1">
                                <label class="form-check-label">Ada</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Meteorismus --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Meteorismus</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_meteor" value="0">
                                <label class="form-check-label">Tidak</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_meteor" value="1">
                                <label class="form-check-label">Ada</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Asites --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Asites</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_asites" value="0">
                                <label class="form-check-label">Tidak</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_asites" value="1">
                                <label class="form-check-label">Ada</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Peristaltik + Nyeri Tekan --}}
            <div class="col-md-6">
                {{-- Peristaltik --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Peristaltik</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_peristal_normal">
                                <label class="form-check-label">Normal</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_peristal_meningkat">
                                <label class="form-check-label">Meningkat</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_peristal_menurun">
                                <label class="form-check-label">Menurun</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_peristal_tidak">
                                <label class="form-check-label">Tidak Ada</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Nyeri Tekan --}}
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Nyeri Tekan</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_nyeri_tekan_lain" name="pf_nyeri_tekan" value="0">
                                <label class="form-check-label">Tidak</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                <div class="form-check flex-shrink-0 mb-0">
                                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_nyeri_tekan_lain" name="pf_nyeri_tekan" value="1">
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_nyeri_tekan_lain" id="pf_nyeri_tekan_lain" placeholder="Lokasi ?" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Hepar --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Hepar</label>
                        <input type="text" class="form-control form-control-sm" name="pf_hepar">
                    </div>
                </div>
            </div>
            {{-- Lien --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Lien</label>
                        <input type="text" class="form-control form-control-sm" name="pf_lien">
                    </div>
                </div>
            </div>
            {{-- Extremitas --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Extremitas</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_extremitas_hangat">
                                <label class="form-check-label">Hangat</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="pf_extremitas_dingin">
                                <label class="form-check-label">Dingin</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Udem --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Udem</label>
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <div class="form-check m-0">
                                <input class="form-check-input single-checkbox" type="checkbox" data-target="#pf_udem_lain" name="pf_udem" value="0">
                                <label class="form-check-label">Tidak</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                <div class="form-check flex-shrink-0 mb-0">
                                    <input class="form-check-input single-checkbox buka-lainnya" type="checkbox" data-target="#pf_udem_lain" name="pf_udem" value="1">
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_udem_lain" id="pf_udem_lain" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Lain-lain --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label fw-bold flex-shrink-0">Lain-lain</label>
                        <input type="text" class="form-control form-control-sm" name="pf_dada_lain">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($tampilkan('status_lokalis'))
        @if ($hrSebelum('status_lokalis'))
            <hr class="pemeriksaan-anatomi-divider mt-2">
        @endif
        <div class="row" data-pemeriksaan="status_lokalis">
            <div class="form-group mb-3">
                <label class="form-label fw-bold flex-shrink-0">Status Lokalis</label>
                <textarea class="form-control" name="status_lokalis" rows="3"></textarea>
            </div>
        </div>
    @endif
</div>

{{-- ==============================================================
     JAVASCRIPT COMPONENT
     ============================================================== --}}
<script>
    $(function () {
        const $component = $('[data-pemeriksaan-anatomi]').last();
        if (!$component.length) {
            return;
        }

        /**
         * ==========================================================
         * TOGGLE INPUT LAINNYA
         * ==========================================================
         *
         * Pilihan yang mempunyai class:
         *
         *     .buka-lainnya
         *
         * akan membuka input yang ditunjuk oleh:
         *
         *     data-target="#id_input"
         *
         */
        function toggleInputLainnya($checkbox) {
            if (!$checkbox || !$checkbox.length) {
                return;
            }
            const target = $checkbox.data('target');
            if (!target) {
                return;
            }
            const $input = $component.find(target);
            if (!$input.length) {
                return;
            }

            // Cari checkbox yang sedang terpilih
            const $selected = $component
                .find(`[data-target="${target}"]:checked`);

            /*
             * Jika checkbox yang dipilih mempunyai
             * class .buka-lainnya
             */
            if ($selected.hasClass('buka-lainnya')) {
                $input.prop('disabled', false);
            } else {
                $input
                    .prop('disabled', true)
                    .val('');
            }
        }

        /**
         * ==========================================================
         * EVENT CHANGE
         * ==========================================================
         */
        $component.on(
            'change.pemeriksaanAnatomi',
            '.single-checkbox[data-target]',
            function () {
                toggleInputLainnya($(this));
            }
        );

        /**
         * ==========================================================
         * INITIALIZE INPUT LAINNYA
         * ==========================================================
         *
         * Fungsi ini penting ketika data di-load melalui AJAX.
         *
         * Misalnya:
         *
         * pf_kd = 1
         *
         * lalu checkbox value 1 di-check menggunakan:
         *
         * $('input[name="pf_kd"][value="1"]')
         *     .prop('checked', true);
         *
         * Setelah semua data AJAX selesai di-set:
         *
         * initInputLainnya();
         *
         * maka input pf_kd_lain otomatis dibuka.
         */
        function initInputLainnya() {
            $component
                .find('.single-checkbox[data-target]')
                .each(function () {
                    toggleInputLainnya($(this));
                });
        }

        /**
         * Jalankan initial state ketika component pertama kali
         * ditampilkan.
         */
        initInputLainnya();

        /**
         * ==========================================================
         * PUBLIC FUNCTION
         * ==========================================================
         *
         * Bisa dipanggil dari function AJAX Anda:
         *
         * initPemeriksaanAnatomiLainnya();
         *
         */
        window.initPemeriksaanAnatomiLainnya = function () {
            initInputLainnya();
        };
    });
</script>
