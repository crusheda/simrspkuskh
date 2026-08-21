<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Anamnesis</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1">
                            <label class="form-check-label">
                                Autoanamnesis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2" checked>
                            <label class="form-check-label">
                                Alloanamnesis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" name="anamnesis_oleh" id="anamnesis_oleh" placeholder="Oleh.....">
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row">
                            {{-- ================= STATUS OBSTETRI ================= --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-1">
                                    STATUS OBSTETRI
                                </h6>

                                {{-- Umur Ibu --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Umur Ibu</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="number" class="form-control form-control-sm" name="so_umur_ibu" style="max-width: 80px;">
                                            <span>TH</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Riwayat Obstetri --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Riwayat Obstetri</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <span>G</span>
                                            <input type="number" class="form-control form-control-sm" name="so_g" style="max-width: 70px;">
                                            <span>P</span>
                                            <input type="number" class="form-control form-control-sm" name="so_p" style="max-width: 70px;">
                                            <span>A</span>
                                            <input type="number" class="form-control form-control-sm" name="so_a" style="max-width: 70px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Umur Kehamilan --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Umur Kehamilan</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="number" class="form-control form-control-sm" name="so_umur_kehamilan" style="max-width: 80px;">
                                            <span>MG</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Komplikasi --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Komplikasi selama kehamilan</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_komplikasi" value="0">
                                                <label class="form-check-label">Tidak Ada</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_komplikasi" value="1">
                                                <label class="form-check-label">Ada</label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="so_komplikasi_ket" placeholder="Keterangan..." style="max-width: 220px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Golongan Darah Ibu --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Golongan Darah Ibu</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="A">
                                                <label class="form-check-label">A</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="B">
                                                <label class="form-check-label">B</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="O">
                                                <label class="form-check-label">O</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="AB">
                                                <label class="form-check-label">AB</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_rh_ibu" value="+">
                                                <label class="form-check-label">RH (+)</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_rh_ibu" value="-">
                                                <label class="form-check-label">RH (-)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Golongan Darah Ayah --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Golongan Darah Ayah</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="A">
                                                <label class="form-check-label">A</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="B">
                                                <label class="form-check-label">B</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="O">
                                                <label class="form-check-label">O</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="AB">
                                                <label class="form-check-label">AB</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_rh_ayah" value="+">
                                                <label class="form-check-label">RH (+)</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="so_rh_ayah" value="-">
                                                <label class="form-check-label">RH (-)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- KK Pecah Jam --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">KK Pecah Jam</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="time" class="form-control form-control-sm" name="so_kk_pecah_jam" style="max-width: 130px;">
                                            <span>WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= STATUS NEONATUS ================= --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-1">
                                    STATUS NEONATUS
                                </h6>

                                {{-- Bayi Lahir --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Bayi lahir tanggal</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="date" class="form-control form-control-sm" name="sn_tanggal_lahir" style="max-width: 145px;">
                                            <span>Jam</span>
                                            <input type="time" class="form-control form-control-sm" name="sn_jam_lahir" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Jenis Kelamin</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-3">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="sn_jenis_kelamin" value="L">
                                                <label class="form-check-label">Laki-laki</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="sn_jenis_kelamin" value="P">
                                                <label class="form-check-label">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BB --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">BB Lahir</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="number" class="form-control form-control-sm" name="sn_bb_lahir" style="max-width: 100px;">
                                            <span>gram</span>

                                            <span class="ms-2">LK :</span>
                                            <input type="number" class="form-control form-control-sm" name="sn_lk" style="max-width: 80px;">
                                            <span>cm</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- PB --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">PB Lahir</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="number" class="form-control form-control-sm" name="sn_pb_lahir" style="max-width: 100px;">
                                            <span>cm</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Resusitasi --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Resusitasi</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="checkbox" name="sn_resusitasi_intubasi" value="1">
                                                <label class="form-check-label">
                                                    Intubasi Intra Trachea
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="checkbox" name="sn_resusitasi_pompa" value="1">
                                                <label class="form-check-label">
                                                    Pompa Udara Berulang
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Berulang --}}
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="text" class="form-control form-control-sm" name="sn_berulang">
                                        </div>
                                    </div>
                                </div>

                                {{-- Jenis Partus --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Jenis Partus</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3">
                                            <span>:</span>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="sn_jenis_partus" value="SC">
                                                <label class="form-check-label">SC</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="sn_jenis_partus" value="V">
                                                <label class="form-check-label">Vacuum</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="sn_jenis_partus" value="S">
                                                <label class="form-check-label">Spontan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Indikasi --}}
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Indikasi</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-1">
                                            <span>:</span>
                                            <input type="text" class="form-control form-control-sm" name="sn_indikasi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ========================================================= --}}
                    {{-- PENILAIAN AWAL BAYI BARU LAHIR / APGAR SCORE --}}
                    {{-- ========================================================= --}}
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
                        <div class="mb-2">
                            <label class="fw-bold">
                                PENILAIAN APGAR SCORE :
                            </label>
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
                        {{-- BAYI TIDAK BUGAR --}}
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="apgar_status_bayi" id="apgar_bayi_tidak_bugar" value="tidak_bugar">
                            <label class="form-check-label" for="apgar_bayi_tidak_bugar">
                                Bayi Tidak Bugar
                            </label>
                        </div>
                        {{-- RESUSITASI --}}
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="fw-bold">Resusitasi</label>
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
                        {{-- TANGGAL / JAM / BB SEKARANG --}}
                        <div class="row align-items-center mt-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0">Tanggal :</label>
                                    <input type="date" class="form-control form-control-sm" name="apgar_tanggal" style="max-width: 150px;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0">Jam :</label>
                                    <input type="time" class="form-control form-control-sm" name="apgar_jam" style="max-width: 110px;">
                                    <span>WIB</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0">BB Sekarang :</label>
                                    <input type="number" class="form-control form-control-sm" name="apgar_bb_sekarang" style="max-width: 120px;">
                                    <span>gram</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Keluhan Utama</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Sekarang</h6>
                            <textarea class="form-control" name="rps" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Dahulu</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Keluarga</h6>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_h">
                                <label class="form-check-label"> Hipertensi </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_d">
                                <label class="form-check-label"> Diabetes Melitus </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_p">
                                <label class="form-check-label"> Penyakit Jantung </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_a">
                                <label class="form-check-label"> Asma </label>
                            </div>
                            <textarea class="form-control" name="rpk_lain" rows="1" placeholder="Lainnya..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Tanda Vital</h4>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <h6>Keadaan Umum</h6>
                            <div class="form-group">
                                <textarea class="form-control" name="keu" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <h6 class="mb-2">Jalan Nafas ( <b class="text-warning">A</b> )</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                <label class="form-check-label"> Paten </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                <label class="form-check-label"> Obstruksi Parsial </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                <label class="form-check-label"> Obstruksi Total </label>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <label class="form-check-label fw-bold text-dark me-2">Jalan Nafas ( <b class="text-warning">A</b> )</label>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                    <label class="form-check-label"> Paten </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                    <label class="form-check-label"> Obstruksi Parsial </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                    <label class="form-check-label"> Obstruksi Total </label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Pernafasan ( <b class="text-warning">B</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nafas</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group flex-grow-1">
                                    <input type="number" class="form-control" name="fr">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="1" checked="">
                                    <label class="form-check-label">
                                        Simetris
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="2">
                                    <label class="form-check-label">
                                        Asimetris
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Sirkulasi ( <b class="text-warning">C</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="td_up">
                                <div class="input-group-text"> / </div>
                                <input type="number" class="form-control" name="td_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nadi</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group input-group-sm flex-grow-1">
                                    <input type="number" class="form-control" name="nadi">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_nadi" value="1" checked="">
                                    <label class="form-check-label">
                                        Reguler
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_nadi" value="2">
                                    <label class="form-check-label">
                                        Ireguler
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Suhu</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="suhu">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SpO2</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="spo2">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Neorologi ( <b class="text-warning">D</b> )</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Tingkat Kesadaran</label>
                                    <select class="form-control" name="tks">
                                        <option value="">Pilih</option>
                                        @if ($list['tingkat_kesadaran'])
                                            @foreach ($list['tingkat_kesadaran'] as $item)
                                                <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <h6>Alat Bantu Nafas</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="2">
                                                    <label class="form-check-label"> Ya </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="0" checked="">
                                                    <label class="form-check-label"> Tidak </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6>Kulit</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="1" checked="">
                                                    <label class="form-check-label"> Normal </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="2">
                                                    <label class="form-check-label"> Jaundice </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="3">
                                                    <label class="form-check-label"> Akral Dingin </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="4">
                                                    <label class="form-check-label"> Sianotik </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="5">
                                                    <label class="form-check-label"> Berkeringat </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <h4 class="mb-3 text-danger">Pemeriksaan Fisik</h4>
                <div class="mb-3">
                    @include(
                        'pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_anatomi',
                        [
                            'section' => '#riD_dokter',
                            'metodePemeriksaan' => [
                                'mata',
                                'tenggorokan',
                                'leher',
                                'dada',
                                'perut'
                            ]
                        ]
                    )
                </div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">
                    Riwayat Imunisasi
                </label>
                <div class="d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="imunisasi" value="1">
                        <label class="form-check-label">
                            Imunisasi Dasar Lengkap
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="imunisasi" value="2">
                        <label class="form-check-label">
                            Imunisasi Dasar Tidak Lengkap
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="imunisasi" value="3">
                        <label class="form-check-label">
                            Tidak Imunisasi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="imunisasi" value="4">
                        <label class="form-check-label">
                            Lain-lain
                        </label>
                    </div>
                </div>
                <div class="mt-2">
                    <input type="text" class="form-control" name="imunisasi_lain" placeholder="Jelaskan..." style="display:none;">
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                </div>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <!-- PERENCANAAN / PROGRAM KERJA -->
            <div class="col-md-12">
                <!-- Judul -->
                <h4 class="text-danger">PERENCANAAN / PROGRAM KERJA</h4>

                <!-- a. Edukasi -->
                <div class="row">
                    <div class="col-md-2 fw-bold">
                        a. Edukasi :
                    </div>

                    <div class="col-md-10">
                        <div class="d-flex flex-wrap gap-3">

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="edukasi[]"
                                    value="tidak"
                                    id="edukasi_tidak">
                                <label class="form-check-label" for="edukasi_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="edukasi[]"
                                    value="hasil_pemeriksaan"
                                    id="edukasi_hasil">
                                <label class="form-check-label" for="edukasi_hasil">
                                    Hasil pemeriksaan
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="edukasi[]"
                                    value="diagnosa"
                                    id="edukasi_diagnosa">
                                <label class="form-check-label" for="edukasi_diagnosa">
                                    Diagnosa
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="edukasi[]"
                                    value="rencana_penatalaksanaan"
                                    id="edukasi_rencana">
                                <label class="form-check-label" for="edukasi_rencana">
                                    Rencana penatalaksanaan penyakit
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="edukasi[]"
                                    value="tindakan_tujuan_terapi"
                                    id="edukasi_terapi">
                                <label class="form-check-label" for="edukasi_terapi">
                                    Tindakan dan tujuan terapi
                                </label>
                            </div>

                            <div class="d-flex align-items-center">
                                <span class="me-2">....................</span>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="edukasi_keterangan"
                                    style="width: 250px;">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- b. Rencana Pemeriksaan Penunjang -->
                <div class="row">
                    <div class="col-md-12 fw-bold">
                        b. Rencana Pemeriksaan Penunjang :
                    </div>

                    <div class="col-md-12 pb-3">
                        <textarea class="form-control"
                                name="rencana_pemeriksaan_penunjang"
                                rows="3"></textarea>
                    </div>
                </div>

                <!-- c. Terapi / Tindakan -->
                <div class="row">
                    <div class="col-md-12 fw-bold">
                        c. Terapi / Tindakan :
                    </div>

                    <div class="col-md-12 pb-3">
                        <textarea class="form-control"
                                name="terapi_tindakan"
                                rows="6"></textarea>
                    </div>
                </div>

                <!-- Rekonsiliasi Obat -->
                <div class="row">
                    <div class="col-md-12">
                        <span>
                            Sudah dilakukan rekonsiliasi terhadap obat yang sedang digunakan saat ini :
                        </span>

                        <div class="form-check form-check-inline ms-2">
                            <input class="form-check-input"
                                type="radio"
                                name="rekonsiliasi_obat"
                                value="ya"
                                id="rekonsiliasi_ya">
                            <label class="form-check-label" for="rekonsiliasi_ya">
                                Ya
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                type="radio"
                                name="rekonsiliasi_obat"
                                value="tidak"
                                id="rekonsiliasi_tidak">
                            <label class="form-check-label" for="rekonsiliasi_tidak">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>


                <!-- KRITERIA PULANG -->
                <h4 class="text-danger">KRITERIA PULANG</h4>

                <!-- Perkiraan Lama Rawat -->
                <div class="row">
                    <div class="col-md-3 fw-bold">
                        Perkiraan Lama Rawat :
                    </div>

                    <div class="col-md-9">

                        <!-- Sudah bisa ditetapkan -->
                        <div class="d-flex align-items-center flex-wrap mb-2">
                            <div class="form-check me-2">
                                <input class="form-check-input"
                                    type="radio"
                                    name="perkiraan_lama_rawat"
                                    value="sudah_bisa"
                                    id="lama_rawat_sudah">
                                <label class="form-check-label" for="lama_rawat_sudah">
                                    Sudah bisa ditetapkan :
                                </label>
                            </div>

                            <input type="number"
                                class="form-control form-control-sm mx-1"
                                name="lama_rawat_hari"
                                style="width: 80px;">

                            <span>hari, rencana tanggal :</span>

                            <input type="date"
                                class="form-control form-control-sm mx-1"
                                name="rencana_tanggal_pulang"
                                style="width: 180px;">
                        </div>

                        <!-- Belum bisa ditetapkan -->
                        <div class="d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input"
                                    type="radio"
                                    name="perkiraan_lama_rawat"
                                    value="belum_bisa"
                                    id="lama_rawat_belum">
                                <label class="form-check-label" for="lama_rawat_belum">
                                    Belum bisa ditetapkan, karena :
                                </label>
                            </div>

                            <input type="text"
                                class="form-control form-control-sm ms-2"
                                name="alasan_lama_rawat"
                                style="max-width: 400px;">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
