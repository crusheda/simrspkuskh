<div class="form-wrapper">
    <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN KEPERAWATAN</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold"> Keluhan Utama </label>
                    <input class="form-control form-control" type="text" name="anm_ku" id="anm_ku">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Keadaan Umum</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="ku" id="ku">
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Tingkat Kesadaran</label>
                    <select class="form-control" name="kesadaran" id="kesadaran">
                        <option value="">Pilih Tingkat Kesadaran</option>
                        @foreach ($list['kesadaran'] as $item)
                            <option value="{{ $item->ID }}">
                                {{ $item->DESKRIPSI }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">E (Eye)</label>
                    <input type="number" step="1" class="form-control form-control-sm" name="eye" id="eye" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">M (Motorik)</label>
                    <input type="number" step="1" class="form-control form-control-sm" name="motorik" id="motorik" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">V (Verbal)</label>
                    <input type="number" step="1" class="form-control form-control-sm" name="verbal" id="verbal" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">GCS</label>
                    <input type="number" step="1" class="form-control form-control-sm" name="gcs" id="gcs" value="0" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Tekanan Darah</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="td_up" id="td_up">
                        <div class="input-group-text"> / </div>
                        <input type="text" class="form-control" name="td_down" id="td_down">
                        <div class="input-group-text"> mmHg </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">SpO2</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="spo2" id="spo2">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Nafas</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="input-group input-group-sm flex-grow-1">
                            <input type="text" class="form-control" name="nafas" id="nafas">
                            <span class="input-group-text">X/menit</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Suhu</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="suhu" id="suhu">
                        <div class="input-group-text">°C</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Nadi</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="nadi" id="nadi">
                        <div class="input-group-text">X/menit</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Alat Bantu Nafas</label>
                    <div class="input-group input-group-sm mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="abn" value="1">
                                    <label class="form-check-label">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="abn" value="2" checked>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-primary mb-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <h5 class="border-bottom pb-2 text-bold">
                                <strong>PSIKOLOGI - SOSIAL - EKONOMI - SPIRITUAL - FUNGSIONAL </strong>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row">
                            <label class="form-label fw-bold">Status Psikologi</label>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="tak" id="tak">
                                    <label class="form-check-label"> Tidak ada kelainan </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="marah" id="marah">
                                    <label class="form-check-label"> Marah </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="cemas" id="cemas">
                                    <label class="form-check-label"> Cemas </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="takut" id="takut">
                                    <label class="form-check-label"> Takut </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="sedih" id="sedih">
                                    <label class="form-check-label"> Sedih </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="bundir" id="bundir">
                                    <label class="form-check-label"> Kecenderungan bunuh diri </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" name="psel" id="psel">
                                    <label class="form-check-label"> Lainnya </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="pse_lain" name="pse_lain" placeholder="Sebutkan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="row">
                            <label class="form-label fw-bold">Status Mental</label>
                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="sm" id="sm_1" value="1">
                                    <label class="form-check-label">
                                        Sadar dan orientasi baik
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="sm" id="sm_2" value="2">
                                    <label class="form-check-label">
                                        Ada masalah perilaku
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="sm_2_detail" name="perilaku" placeholder="Ada masalah perilaku">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="sm" id="sm_3" value="3">
                                    <label class="form-check-label">
                                        Perilaku kekerasan yang dialami pasien sebelumnya
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="sm_3_detail" name="kekerasan" placeholder="Perilaku kekerasan yang dialami pasien sebelumnya">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 mb-1">
                        <label class="form-label fw-bold">Hubungan Sosial</label>
                        <div class="row align-items-center">
                            <label class="col-md-3 col-form-label">Hubungan Pasien dan anggota keluarga</label>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hub" id="hub_baik" value="1">
                                    <label class="form-check-label">
                                        Baik
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hub" id="hub_tidak" value="0">
                                    <label class="form-check-label">
                                        Tidak baik
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Pasien tinggal di</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tinggal" id="tinggal_rumah" value="1">
                                    <label class="form-check-label">
                                        Rumah
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tinggal" id="tinggal_panti" value="2">
                                    <label class="form-check-label">
                                        Panti
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tinggal" id="tinggal_lain" value="3">
                                    <label class="form-check-label">
                                        Lainnya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input type="text" class="form-control" id="tinggal_lain_detail" name="tinggal_lain" placeholder="Sebutkan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 mb-1">
                        <label class="form-label fw-bold">Hubungan Spiritual</label>
                        <div class="row mb-1 align-items-center">
                            <label class="col-md-2 col-form-label">Agama</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="agama" name="agama" value="{{ $list['pasien']->AGAMA ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Kebiasaan beribadah teratur</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kbt" id="kbt_ya" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kbt" id="kbt_tidak" value="0">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Nilai-nilai Kepercayaan</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nk" id="nk_tidak" value="0">
                                    <label class="form-check-label">
                                        Tidak ada
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nk" id="nk_ada" value="1">
                                    <label class="form-check-label">
                                        Ada
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input type="text" class="form-control" id="nk_lain" name="nk_lain" placeholder="Sebutkan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row mb-1 align-items-center">
                            <label class="col-md-2 col-form-label">Pengambil keputusan dalam keluarga</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="pk" name="pk">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 mb-1">
                        <label class="form-label fw-bold">Ekonomi</label>
                        <div class="row mb-1 align-items-center">
                            <label class="col-md-2 col-form-label">Pekerjaan</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="kerja" name="kerja" value="{{ $list['pasien']->PEKERJAAN ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Penghasilan per bulan</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_1" value="1">
                                    <label class="form-check-label">
                                        < Rp. 5.000.000
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_2" value="2">
                                    <label class="form-check-label">
                                        Rp. 5.000.000 - Rp. 10.000.000
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_3" value="3">
                                    <label class="form-check-label">
                                        > Rp. 10.000.000
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-primary">

                <label class="form-label fw-bold">
                    Status Fungsional
                </label>

                <!-- Alat Bantu -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Alat Bantu Mobilitas
                    </label>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="alat_bantu_fungsional"
                                    id="tanpa_alat_bantu"
                                    value="tanpa">
                                <label class="form-check-label" for="tanpa_alat_bantu">
                                    Tanpa Alat Bantu
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="alat_bantu_fungsional"
                                    id="tongkat"
                                    value="tongkat">
                                <label class="form-check-label" for="tongkat">
                                    Tongkat
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="alat_bantu_fungsional"
                                    id="kursi_roda"
                                    value="kursi_roda">
                                <label class="form-check-label" for="kursi_roda">
                                    Kursi Roda
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="alat_bantu_fungsional"
                                    id="brankard"
                                    value="brankard">
                                <label class="form-check-label" for="brankard">
                                    Brankard
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="alat_bantu_fungsional"
                                    id="walker"
                                    value="walker">
                                <label class="form-check-label" for="walker">
                                    Walker
                                </label>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <input type="text"
                                class="form-control"
                                name="alat_bantu"
                                id="alat_bantu"
                                placeholder="Alat bantu lainnya...">
                        </div>

                    </div>
                </div>

                <hr>

                <!-- Cacat Tubuh -->
                <div>
                    <label class="form-label fw-semibold">
                        Cacat Tubuh
                    </label>

                    <div class="row mb-2">

                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="cacat_tubuh"
                                    id="cacat_tubuh_tidak"
                                    value="0">
                                <label class="form-check-label" for="cacat_tubuh_tidak">
                                    Tidak
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary"
                                    type="radio"
                                    name="cacat_tubuh"
                                    id="cacat_tubuh_ya"
                                    value="1">
                                <label class="form-check-label" for="cacat_tubuh_ya">
                                    Ya
                                </label>
                            </div>
                        </div>

                    </div>

                    <textarea class="form-control"
                            name="ket_cacat_tubuh"
                            id="ket_cacat_tubuh"
                            rows="2"
                            placeholder="Keterangan cacat tubuh..."></textarea>
                </div>

            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-primary mb-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <h5 class="border-bottom pb-2 text-bold">
                                <strong>Skrining Gizi Awal </strong>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row">
                            <!-- FORM -->
                            <div class="col-md-9">
                                <!-- Pertanyaan 1 -->
                                <div class="mb-4">
                                    <label class="fw-bold"> Adakah perubahan berat badan signifikan dalam 3 bulan terakhir? </label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_turun" id="bb_turun1" value="1">
                                            <label class="form-check-label">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_turun" id="bb_turun2" value="0" checked>
                                            <label class="form-check-label">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4" id="perubahan_berat">
                                    <label class="fw-bold"> Jumlah perubahan berat badan </label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_ubah" id="bb_ubah1" value="0" checked>
                                            <label class="form-check-label">0,5 - 5 kg (1)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_ubah" id="bb_ubah2" value="1">
                                            <label class="form-check-label">> 5 - 10 kg (2)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_ubah" id="bb_ubah3" value="2">
                                            <label class="form-check-label">> 10 - 15 kg (3)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="bb_ubah" id="bb_ubah4" value="3">
                                            <label class="form-check-label">> 15 kg (4)</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pertanyaan 2 -->
                                <div class="mb-4">
                                    <label class="fw-bold"> Intake makanan kurang karena tidak ada nafsu makan? </label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="nafsu_makan" value="0">
                                            <label class="form-check-label">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="nafsu_makan" value="0" checked>
                                            <label class="form-check-label">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kondisi Khusus -->
                                <div class="mb-3">
                                    <label class="fw-bold">Kondisi Khusus</label>
                                    <textarea class="form-control"name="kondisi_khusus"rows="3"></textarea>
                                </div>
                            </div>

                            <!-- HASIL -->
                            <div class="col-md-3">
                                <div class="form-content text-center">
                                    <h4 id="total_gizi"class="display-2 fw-bold">0</h4>
                                    <label id="status_gizi"class="text-success fw-bold">Risiko Ringan</label><br>
                                    <small id="evaluasi_gizi" class="text-muted"> Monitoring &amp; evaluasi setelah 7 hari perawatan</small>
                                    <input type="hidden" name="skor_gizi" id="skor_gizi" value="0">
                                    <input type="hidden" name="status_skor" id="status_skor" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-body border border-dashed border-success mb-3">
                <h6 class="mb-3">SKRINING NYERI</h6>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <div class="row align-items-center">
                            <label class="col-md-4 col-form-label fw-bold">Nyeri</label>
                            <div class="col-md-8">
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="1">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="0" checked>
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label fw-bold">Onset</label>
                            <div class="col-md-10">
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_onset" value="1">
                                        <label class="form-check-label" for="onsetAkut">Akut</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox"name="sn_onset" value="2">
                                        <label class="form-check-label" for="onsetKronis">Kronis</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <div class="row align-items-center">
                            <label class="col-md-4 col-form-label fw-bold">Skala Nyeri</label>
                            <div class="col-md-8">
                                <input type="number" step="1" class="form-control" name="sn_skala" placeholder="Otomatis terisi" value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label fw-bold">Metode</label>
                            <div class="col-md-10">
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="1">
                                        <label class="form-check-label">NRS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="5">
                                        <label class="form-check-label">VAS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="2">
                                        <label class="form-check-label">BPS</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div id="tampil_sn_nrs" hidden>
                        <img src="{{ asset('images/erm/skrining_nyeri_nrs.png') }}"
                            alt="Indikator Penilaian Nyeri"
                            class="img-fluid mb-3"
                            style="width: 25rem;">
                        <div class="form-group">
                            <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                            <input type="range" class="form-range" min="0" max="10" step="1" value="0" id="sn_nrs">
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span>Interpretasi Skor Nyeri VAS :</span>
                            <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                <li>0 = tidak nyeri</li>
                                <li>1-3 = nyeri ringan</li>
                                <li>4-6 = nyeri sedang</li>
                                <li>7-10 = nyeri berat</li>
                            </ul>
                        </div>
                    </div>
                    <div id="tampil_sn_bps" hidden>
                        <div class="table-responsive">
                            <table class="table table-bordered table-display">
                                <thead class="text-uppercase">
                                    <tr class="table-light">
                                        <th class="text-center">Indikator Penilaian</th>
                                        <th class="text-center">Keterangan Skor</th>
                                        <th class="text-center">Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>1 = rileks</h6>
                                            <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                            <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>1 = rileks</h6>
                                            <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                            <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>1 = rileks</h6>
                                            <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                            <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3">
                                            <div class="d-flex align-items-center gap-3">
                                                <span>Interpretasi Skor BPS :</span>
                                                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                                    <li>3 = tidak nyeri</li>
                                                    <li>4-6 = nyeri ringan</li>
                                                    <li>7-9 = nyeri sedang</li>
                                                    <li>10-12 = nyeri berat</li>
                                                </ul>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="tampil_sn_nips" hidden>
                        <div class="table-responsive">
                            <table class="table table-bordered table-display">
                                <thead class="text-uppercase">
                                    <tr class="table-light">
                                        <th class="text-center">Indikator Penilaian</th>
                                        <th class="text-center">Keterangan Skor</th>
                                        <th class="text-center">Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>0 = Relaksasi</h6>
                                            <h6>1 = Meringis</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_1" class="form-control form-control-sm mx-auto"  min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Tangisan</th>
                                        <td>
                                            <h6>0 = Tidak menangis</h6>
                                            <h6>1 = Meringis</h6>
                                            <h6>2 = Menangis kuat</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Gerakan Lengan</th>
                                        <td>
                                            <h6>0 = Relaksasi</h6>
                                            <h6>1 = Fleksi / ekstensi</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_3" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Gerakan Tungkai</th>
                                        <td>
                                            <h6>0 = relaksasi</h6>
                                            <h6>1 = Fleksi / ekstensi</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_4" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Status Terjaga</th>
                                        <td>
                                            <h6>0 = Tidur / bangun</h6>
                                            <h6>1 = Rewel</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_5" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Pola Nafas</th>
                                        <td>
                                            <h6>0 = Relaksasi</h6>
                                            <h6>1 = Perubahan pola nafas</h6>
                                        </td>
                                        <td class="text-center"><input type="number" step="1" name="sn_nips_6" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3">
                                            <div class="d-flex align-items-center gap-3">
                                                <span>Keterangan :</span>
                                                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                                    <li>> 3 = Nyeri</li>
                                                    <li>≤ 3 = Tidak Nyeri</li>
                                                </ul>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="tampil_sn_flacc" hidden>
                        <div class="table-responsive">
                            <table class="table table-bordered table-display">
                                <thead class="text-uppercase">
                                    <tr class="table-light">
                                        <th class="text-center">Indikator</th>
                                        <th class="text-center">0</th>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Wajah</th>
                                        <td>Tersenyum / tidak ada ekspresi khusus</td>
                                        <td>Terkadang meringis / menarik diri</td>
                                        <td>Sering menggetarkan dagu dan mengatupkan rahang</td>
                                        <td class="text-center"><input type="number" step="1" name="sn_flacc_1" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Kaki</th>
                                        <td>Gerakan normal / relaksasi</td>
                                        <td>Tidak tenang</td>
                                        <td>Kaki dibuat menendang / menarik diri</td>
                                        <td class="text-center"><input type="number" step="1" name="sn_flacc_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Aktivitas</th>
                                        <td>Tidur, posisi normal mudah bergerak</td>
                                        <td>Gerakan menggeliat, berguling, kaku</td>
                                        <td>Melengkungkan punggung / kaku / menghentak</td>
                                        <td class="text-center"><input type="number" step="1" name="sn_flacc_3" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Menangis</th>
                                        <td>Tidak menangis (bangung / tidur)</td>
                                        <td>Mengerang, merengek-rengek</td>
                                        <td>Menangis terus-menerus, terisak, menjerit</td>
                                        <td class="text-center"><input type="number" step="1" name="sn_flacc_4" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Bersuara</th>
                                        <td>Bersuara normal, tenang</td>
                                        <td>Tenang bila dipeluk, digendong, atau diajak bicara</td>
                                        <td>Sulit untuk ditenangkan</td>
                                        <td class="text-center"><input type="number" step="1" name="sn_flacc_5" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5">Interpretasi : Skor total dari lima parameter di atas menentukan tingkat keparahan nyeri dengan skala 0 - 10. Nilai 10 menunjukan tingkat nyei yang hebat.</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="tampil_sn_vas" hidden>
                        <img src="{{ asset('images/erm/skrining_nyeri_vas.png') }}"
                            alt="Indikator Penilaian Nyeri"
                            class="img-fluid mb-3"
                            style="width: 25rem;">
                        <div class="form-group">
                            <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                            <input type="range" class="form-range" min="0" max="10" step="1" value="0" id="sn_vas">
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span>Interpretasi Skor Nyeri VAS :</span>
                            <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                <li>0 = tidak nyeri</li>
                                <li>1-3 = nyeri ringan</li>
                                <li>4-6 = nyeri sedang</li>
                                <li>7-10 = nyeri berat</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Baris 3 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Pencetus</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_pencetus" id="sn_pencetus" placeholder="[ Pencetus ]">
                    </div>
                </div>

                <!-- Baris 4 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Gambaran</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_gambaran" id="sn_gambaran" placeholder="[ Gambaran ]">
                    </div>
                </div>

                <!-- Baris 5 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Durasi</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_durasi" id="sn_durasi" placeholder="[ Durasi ]">
                    </div>
                </div>

                <!-- Baris 6 -->
                <div class="row align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Lokasi</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_lokasi" id="sn_lokasi" placeholder="[ Lokasi ]">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-warning mb-1">
                <h6>SKRINING RESIKO JATUH</h6>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input check-primary" type="checkbox" id="srj_gag">
                        <label class="form-check-label ms-1">
                            <small class="mb-2 fw-bold">Penilaian Resiko Jatuh (Get Up and Go)</small>
                        </label>
                    </div>
                </div>
                <div class="row" id="tampil_srj_gag" hidden>
                    <div class="col-md-12 mb-1">
                        <div class="row">
                            <!-- Cara Berjalan -->
                            <div class="col-md-9">
                                <label class="form-label fw-bold">Cara Berjalan Pasien (Salah Satu atau Lebih)</label>

                                <div>a. Tidak seimbang / menyopang / limbung</div>
                                <div>b. Jalan dengan menggunakan alat bantu (Tongkat, kursi roda, dibantu orang lain)</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="cara_berjalan" id="cara_ya" value="1">
                                    <label class="form-check-label" for="cara_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="cara_berjalan" id="cara_tidak" value="0" checked>
                                    <label class="form-check-label" for="cara_tidak">Tidak</label>
                                </div>
                            </div>

                            <hr>

                            <!-- Faktor Risiko -->
                            <div class="col-md-9">

                                <label class="form-label fw-bold">Faktor Risiko</label>

                                <div><b>A.</b> Umur ≥ 60 tahun, anak &lt; 3 tahun</div>

                                <div class="mt-2">
                                    <b>B.</b> Diagnosis penyakit / situasi / keluhan yang mungkin menyebabkan pasien berisiko jatuh:
                                </div>

                                <ol class="mb-2">
                                    <li>Vertigo / Pusing</li>
                                    <li>Parkinson</li>
                                    <li>Gangguan penglihatan yang belum terkoreksi (Glaukoma, Katarak, Gangguan Lapangan Penglihatan)</li>
                                    <li>Riwayat tirah baring lama yang akan dipindahkan untuk pemeriksaan penunjang</li>
                                    <li>Pasien yang mendapat sedasi</li>
                                </ol>

                                <div><b>C.</b> Lingkungan :</div>

                                <ol>
                                    <li>Area-area yang berisiko pasien jatuh (Tangga, penerangan kurang, jalan menurun/menanjak)</li>
                                    <li>
                                        Poli yang dituju:
                                        <ol>
                                            <li>Rehabilitasi Medik</li>
                                            <li>Radiologi</li>
                                            <li>Radioterapi</li>
                                        </ol>
                                    </li>
                                </ol>

                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="faktor_risiko" id="risiko_ya" value="1">
                                    <label class="form-check-label" for="risiko_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="faktor_risiko" id="risiko_tidak" value="0" checked>
                                    <label class="form-check-label" for="risiko_tidak">Tidak</label>
                                </div>
                            </div>

                            <hr>

                            <!-- Obat -->
                            <div class="col-md-9">

                                <label class="form-label fw-bold">
                                    Menanyakan Obat-obatan yang diminum pasien saat ini, yang mungkin menyebabkan risiko jatuh adalah:
                                </label>

                                <ul class="mb-0">
                                    <li>Narkotik</li>
                                    <li>Anti hipertensi</li>
                                    <li>Diuretik</li>
                                    <li>Obat penyakit jantung</li>
                                    <li>Pengencer darah</li>
                                    <li>Tetes mata yang menyebabkan mudriasis</li>
                                    <li>Anti histamine dengan dosis yang tinggi</li>
                                    <li>Obat anti diabetes/insulin</li>
                                </ul>

                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="kon_obat" id="obat_ya" value="1">
                                    <label class="form-check-label" for="obat_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="kon_obat" id="obat_tidak" value="0" checked>
                                    <label class="form-check-label" for="obat_tidak">Tidak</label>
                                </div>
                            </div>
                            <!-- HASIL -->
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <div class="form-content text-center">
                                    <h4 id="total_skor_gtg" class="display-2 fw-bold text-dark">0</h4>
                                    <label id="status_skor_gtg" class="text-success fw-bold"> Tidak Beresiko Jatuh</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-primary">
                <label class="form-label fw-bold">
                    Diagnosis Keperawatan
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
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">
                        Rencana Asuhan Keperawatan
                    </label>
                    <textarea class="form-control" name="rencana_asuhan_keperawatan" id="rencana_asuhan_keperawatan" rows="3" placeholder="Tuliskan rencana asuhan keperawatan..."></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJOp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjo_perawat');
        // Sembunyikan textarea saat pertama kali
        $('#pse_lain').hide();
        $('#sm_2_detail').hide();
        $('#sm_3_detail').hide();
        $('#tinggal_lain_detail').hide();
        $('#nk_lain').hide();
        $('#terapi_oral').hide();
        $('#terapi_iv').hide();
        $('#perubahan_berat').hide();

        // Status Psikologi
        $('#pse_7').change(function () {
            if ($(this).is(':checked')) {
                $('#pse_lain').show();
            } else {
                $('#pse_lain').hide();
                $('#pse_lain').val('');
            }
        });

        // Status Mental
        $('input[name="sm"]').change(function () {
            if ($('#sm_2').is(':checked')) {
                $('#sm_2_detail').slideDown();
            } else {
                $('#sm_2_detail').slideUp().val('');
            }
        });

        $('input[name="sm"]').change(function () {
            if ($('#sm_3').is(':checked')) {
                $('#sm_3_detail').slideDown();
            } else {
                $('#sm_3_detail').slideUp().val('');
            }
        });

        // Sosial Tinggal
        $('input[name="tinggal"]').change(function () {
            if ($('#tinggal_lain').is(':checked')) {
                $('#tinggal_lain_detail').slideDown();
            } else {
                $('#tinggal_lain_detail').slideUp().val('');
            }
        });

        // Nilai Kepercayaan
        $('input[name="nk"]').change(function () {
            if ($('#nk_ada').is(':checked')) {
                $('#nk_lain').slideDown();
            } else {
                $('#nk_lain').slideUp().val('');
            }
        });

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

        //Skor Resiko Jatuh
        $(function () {

            hitungSkor();

            $(".skor").change(function () {
                hitungSkor();
            });

        });

        //Skor Skrining Gizi Awal
        $(function () {

            hitungGizi();

            $(".gizi").change(function () {
                hitungGizi();
            });

        });

        // Saat pilihan berubah
        $('input[name="bb_turun"]').on('change', function () {
            togglePerubahanBerat();
        });

        function togglePerubahanBerat() {
            if ($('input[name="bb_turun"]:checked').val() == '1') {
                // Ya
                $('#perubahan_berat').slideDown();
            } else {
                $("#perubahan_berat").slideUp();

                $("input[name='bb_ubah'][value='0']").prop("checked", true);
            }
        }

        // START SKRINING NYERI ------------------------------------------------------------------------------
        const metodeMap = {
            1: '#tampil_sn_nrs',
            2: '#tampil_sn_bps',
            3: '#tampil_sn_nips',
            4: '#tampil_sn_flacc',
            5: '#tampil_sn_vas'
        };

        // ==========================================
        // PILIH METODE
        // ==========================================
        $section.on('change', 'input[name="sn_metode"]', function () {

            const $this = $(this);
            const metode = $this.val();

            // ==========================================
            // JIKA METODE DIPILIH
            // ==========================================
            if ($this.is(':checked')) {

                // Pastikan hanya satu metode yang aktif
                $section
                    .find('input[name="sn_metode"]')
                    .not($this)
                    .prop('checked', false);

                // Reset skor metode sebelumnya
                resetSemuaSkor();

                // Hide semua metode
                $.each(metodeMap, function (key, selector) {
                    $(selector).prop('hidden', true);
                });

                // Tampilkan metode yang dipilih
                if (metodeMap[metode]) {
                    $(metodeMap[metode]).prop('hidden', false);
                }

            } else {

                // ==========================================
                // JIKA METODE DIBATALKAN
                // ==========================================

                // Hide semua
                $.each(metodeMap, function (key, selector) {
                    $(selector).prop('hidden', true);
                });

                // Reset semua skor dan sn_skala kembali 0
                resetSemuaSkor();
            }
        });

        // ==========================================
        // KONDISI AWAL
        // ==========================================
        $.each(metodeMap, function (key, selector) {
            $(selector).prop('hidden', true);
        });

        resetSemuaSkor();

        // ==========================================
        // NRS
        // ==========================================
        $('#sn_nrs').on('input', function () {
            hitungSkorNyeri('1');
        });

        // ==========================================
        // BPS
        // ==========================================
        $('#tampil_sn_bps input[type="number"]').on('input', function () {
            hitungSkorNyeri('2');
        });

        // ==========================================
        // NIPS
        // ==========================================
        $('#tampil_sn_nips input[type="number"]').on('input', function () {
            hitungSkorNyeri('3');
        });

        // ==========================================
        // FLACC
        // ==========================================
        $('#tampil_sn_flacc input[type="number"]').on('input', function () {
            hitungSkorNyeri('4');
        });

        // ==========================================
        // VAS
        // ==========================================
        $('#sn_vas').on('input', function () {
            hitungSkorNyeri('5');
        });
        // END SKRINING NYERI ------------------------------------------------------------------------------

        $section.on('change input', '[data-hd-required]', function () {
            hitungSkorHumptyDumpty($section);
        });

        $section.on('change', '#srj_hd', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Humpty Dumpty
                $section.find('#tampil_srj_hd').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_hd').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetHumptyDumpty($section);
            }
        });

        $section.on('change', '#srj_gag', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Morse
                $section.find('#tampil_srj_gag').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_gag').prop('hidden', true);
                // Kembalikan semua nilai ke default
                $('input[name="cara_berjalan"][value="0"]')
                    .prop('checked', true).trigger("change");
                $('input[name="faktor_risiko"][value="0"]')
                    .prop('checked', true).trigger("change");
                $('input[name="kon_obat"][value="0"]')
                    .prop('checked', true).trigger("change");
            }
        });

        loadDataPengkajianRJOp();
        hitungSkorHumptyDumpty($section);

    });

    function loadDataPengkajianRJOp() {
        const kunjungan = $('#rjo_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjo/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJOp(res);
            }
        });
    }

    function isiFormPengkajianRJOp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================

        $('#anm_ku').val(data.anm_ku);
        $('#ku').val(data.ku);
        $('#kesadaran').val(data.kesadaran);
        $('#eye').val(Number(data.eye).toFixed(0));
        $('#motorik').val(Number(data.motorik).toFixed(0));
        $('#verbal').val(Number(data.verbal).toFixed(0));
        $('#gcs').val(Number(data.gcs).toFixed(0));

        $('#td_up').val(parseInt(data.td_up, 10) || 0);
        $('#td_down').val(parseInt(data.td_down, 10) || 0);
        $('#spo2').val(parseInt(data.spo2, 10) || 0);
        $('#nafas').val(parseInt(data.nafas, 10) || 0);
        $('#suhu').val(data.suhu);
        $('#nadi').val(parseInt(data.nadi, 10) || 0);
        $('#abn').val(data.abn);


        // ======================================================
        // KONDISI SOSIAL
        // ======================================================

        // Status Psikologi (Checkbox)
        $('#tak').prop('checked', data.tak == 1);
        $('#marah').prop('checked', data.marah == 1);
        $('#cemas').prop('checked', data.cemas == 1);
        $('#takut').prop('checked', data.takut == 1);
        $('#sedih').prop('checked', data.sedih == 1);
        $('#bundir').prop('checked', data.bundir == 1);

        $('#pse_lain').val(data.pse_lain).slideDown();


        // Status Mental
        $('input[name="sm"][value="' + data.sm + '"]').prop('checked', true);

        $('#perilaku').val(data.perilaku);
        $('#kekerasan').val(data.kekerasan);


        // Hubungan Sosial
        $('input[name="hub"][value="' + data.hub + '"]').prop('checked', true);

        $('input[name="tinggal"][value="' + data.tinggal + '"]').prop('checked', true);

        $('#tinggal_lain').val(data.tinggal_lain);


        // Spiritual
        $('input[name="kbt"][value="' + data.kbt + '"]').prop('checked', true);

        $('input[name="nk"][value="' + data.nk + '"]').prop('checked', true);

        $('#nk_lain').val(data.nk_lain);

        $('#pk').val(data.pk);


        // Ekonomi
        $('input[name="hasil"][value="' + data.hasil + '"]')
            .prop('checked', true);

        //SKRINING NYERI
        $('input[name="sn_nyeri"]').prop('checked', false);
        $('input[name="sn_nyeri"][value="' + data.sn_nyeri + '"]')
            .prop('checked', true);

        $('input[name="sn_onset"][value="' + data.sn_onset + '"]')
            .prop('checked', true);

        $('#sn_skala').val(data.sn_skala);
        $('input[name="sn_metode"][value="' + data.sn_metode + '"]')
            .prop('checked', true);
        $('#sn_pencetus').val(data.sn_pencetus);
        $('#sn_gambaran').val(data.sn_gambaran);
        $('#sn_durasi').val(data.sn_durasi);
        $('#sn_lokasi').val(data.sn_lokasi);

        $('input[name="bb_turun"][value="' + data.bb_turun + '"]')
            .prop('checked', true).trigger("change");
        $('input[name="bb_ubah"][value="' + data.bb_ubah + '"]')
            .prop('checked', true).trigger("change");
        $('input[name="nafsu_makan"][value="' + data.nafsu_makan + '"]')
            .prop('checked', true).trigger("change");

        $('#kondisi_khusus').val(data.kondisi_khusus);

        $('#skor_gizi').val(data.skor_gizi);
        $('#status_skor').val(data.status_skor);

        $('input[name="alat_bantu_fungsional"][value="' + data.alat_bantu_fungsional + '"]')
            .prop('checked', true);

        $('#alat_bantu').val(data.alat_bantu);

        $('input[name="cacat_tubuh"][value="' + data.cacat_tubuh + '"]')
            .prop('checked', true);

        $('#ket_cacat_tubuh').val(data.ket_cacat_tubuh);

        // DIAGNOSIS KEPERAWATAN
        for (let i = 1; i <= 11; i++) {

            $('#diag_keperawatan_' + i).prop(
                'checked',
                data['diag_keperawatan_' + i] == 1
            );
        }

        // RENCANA ASUHAN KEPERAWATAN
        $('#rencana_asuhan_keperawatan').val(
            data.rencana_asuhan_keperawatan || ''
        );

        // ==========================================
        // HUMPTY DUMPTY
        // ==========================================
        const hd = data.humpty_dumpty;

        if (hd) {
            $('#srj_hd').prop('checked', true);
            $('#tampil_srj_hd').prop('hidden', false);

            $('#rj_usia').val(hd.UMUR).trigger("change");
            $('#rj_jk').val(hd.JENIS_KELAMIN).trigger("change");
            $('#rj_hd_1').val(hd.DIAGNOSA).trigger("change");
            $('#rj_hd_2').val(hd.GANGGUAN_KONGNITIF).trigger("change");
            $('#rj_hd_3').val(hd.FAKTOR_LINGKUNGAN).trigger("change");
            $('#rj_hd_4').val(hd.RESPON).trigger("change");
            $('#rj_hd_5').val(hd.PENGGUNAAN_OBAT).trigger("change");
        }

        // ==========================================
        // MORSE
        // ==========================================
        const gag = data.cara_berjalan;

        if (gag) {
            $('#srj_gag').prop('checked', true);
            $('#tampil_srj_gag').prop('hidden', false);
            $('input[name="cara_berjalan"][value="' + data.cara_berjalan + '"]')
                .prop('checked', true).trigger("change");
            $('input[name="faktor_risiko"][value="' + data.faktor_risiko + '"]')
                .prop('checked', true).trigger("change");
            $('input[name="kon_obat"][value="' + data.kon_obat + '"]')
                .prop('checked', true).trigger("change");
        }

    }

    function saveDataPengkajianRJOp(btn) {
        const $button = $(btn);
        const $section = $('#rjo_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjo/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                // $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (response) {
                // alert(response.message || 'Data berhasil disimpan.');
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

    $(document).on('input', '#eye, #motorik, #verbal', function () {
        let eye = parseInt($('#eye').val()) || 0;
        let motorik = parseInt($('#motorik').val()) || 0;
        let verbal = parseInt($('#verbal').val()) || 0;

        $('#gcs').val(eye + motorik + verbal);
    });

    function hitungSkor(){

        let total = 0;

        total += parseInt($("input[name='cara_berjalan']:checked").val()) || 0;
        total += parseInt($("input[name='faktor_risiko']:checked").val()) || 0;
        total += parseInt($("input[name='kon_obat']:checked").val()) || 0;

        $("#total_skor_gtg").text(total);

        let status = "";
        let warna  = "";

        switch(total){
            case 0:
                status = "Tidak Beresiko Jatuh";
                warna  = "text-success";
                break;

            case 1:
                status = "Risiko Jatuh Rendah";
                warna  = "text-primary";
                break;

            case 2:
                status = "Risiko Jatuh Sedang";
                warna  = "text-warning";
                break;

            case 3:
                status = "Risiko Jatuh Tinggi";
                warna  = "text-danger";
                break;
        }

        $("#status_skor_gtg")
            .removeClass("text-success text-primary text-warning text-danger")
            .addClass(warna)
            .text(status);
    }

    $(".skor").on("change", hitungSkor);

    function hitungGizi(){

        let skor = 0;

        skor += Number($("input[name='bb_turun']:checked").val() || 0);
        skor += Number($("input[name='bb_ubah']:checked").val() || 0);
        skor += Number($("input[name='nafsu_makan']:checked").val() || 0);

        $("#total_gizi").text(skor);
        $("#skor_gizi").val(skor);

        let status = "";
        let evaluasi = "";
        let warna = "";
        let statusSkor = 0;

        if(skor <= 1){

            status = "Risiko Ringan";
            evaluasi = "Monitoring & evaluasi setelah 7 hari perawatan";
            warna = "text-success";
            statusSkor = 0;

        }else if(skor <= 3){

            status = "Risiko Sedang";
            evaluasi = "Monitoring & evaluasi setelah 3 hari perawatan";
            warna = "text-warning";
            statusSkor = 1;

        }else{

            status = "Risiko Tinggi";
            evaluasi = "Segera konsultasi ke Ahli Gizi";
            warna = "text-danger";
            statusSkor = 1;

        }

        $("#status_skor").val(statusSkor);

        $("#status_gizi")
            .removeClass("text-success text-warning text-danger")
            .addClass(warna)
            .text(status);

        $("#evaluasi_gizi").text(evaluasi);

    }

    // HITUNG SKOR SKRINING NYERI BERDASARKAN METODE
    function getValidNumber($input) {

        const value = parseFloat($input.val());
        const min = parseFloat($input.attr('min'));
        const max = parseFloat($input.attr('max'));

        // Kosong
        if (isNaN(value)) {
            return 0;
        }

        // Di luar range
        if (value < min || value > max) {
            $input.val('');
            return 0;
        }

        return value;
    }
    function hitungSkorNyeri(metode) {

        let total = 0;

        switch (metode) {

            // NRS
            case '1':
                total = getValidNumber($('#sn_nrs'));
                break;

            // BPS
            case '2':
                $('#tampil_sn_bps input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // NIPS
            case '3':
                $('#tampil_sn_nips input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // FLACC
            case '4':
                $('#tampil_sn_flacc input[type="number"]').each(function () {
                    total += getValidNumber($(this));
                });
                break;

            // VAS
            case '5':
                total = getValidNumber($('#sn_vas'));
                break;
        }

        $('input[name="sn_skala"]').val(total);
    }

    function resetSemuaSkor() {

        // Reset range
        $('#sn_nrs, #sn_vas').val(0);

        // Reset semua input skor
        $('#tampil_sn_bps input[type="number"], ' +
        '#tampil_sn_nips input[type="number"], ' +
        '#tampil_sn_flacc input[type="number"]'
        ).val('');

        // Kembali ke nilai default
        $('input[name="sn_skala"]').val(0);
    }

    // RESIKO JATUH HUMPTY DUMPTY
    function hitungSkorHumptyDumpty($section) {
        const $requiredFields = $section.find('[data-hd-required]');
        const $scoreFields = $section.find('[data-hd-score]');

        // Selector selalu dibatasi di dalam #gd_perawat
        const $scoreBox = $section.find('#skor_rj_hd');
        const $nilai = $section.find('#nilai_rj_hd');
        const $kategori = $section.find('#kategori_rj_hd');
        const $keterangan = $section.find('#keterangan_rj_hd');

        const semuaTerisi = $requiredFields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '';
        });

        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        let skor = 0;

        $scoreFields.each(function () {
            skor += Number($(this).val());
        });

        const isHighRisk = skor >= 12;
        const kategori = isHighRisk
            ? 'High Risk Dumpty'
            : 'Low Humpty Dumpty';

        $nilai.text(skor);
        $section.find('input[name="skor_rj_hd"]').val(skor);

        $kategori
            .text(kategori)
            .removeClass('text-success text-danger')
            .addClass(isHighRisk ? 'text-danger' : 'text-success');

        $keterangan.text(
            isHighRisk
                ? 'Lakukan pencegahan risiko jatuh sesuai prosedur.'
                : 'Monitoring dan evaluasi risiko jatuh sesuai prosedur.'
        );

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-danger')
            .addClass(isHighRisk ? 'alert-danger' : 'alert-success');

        $scoreBox.prop('hidden', false);
    }

    function resetHumptyDumpty($section) {

        // Reset semua pilihan
        $section.find('[data-hd-required]').val('');

        // Reset nilai skor
        $section.find('[name="skor_rj_hd"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_rj_hd').text(0);

        // Sembunyikan hasil skor
        $section.find('#skor_rj_hd').prop('hidden', true);

        // Kembalikan tampilan kategori
        $section.find('#kategori_rj_hd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $section.find('#keterangan_rj_hd').text('');

        // Kembalikan alert ke default
        $section.find('#skor_rj_hd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }
</script>
