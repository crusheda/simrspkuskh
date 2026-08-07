<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL RAWAT JALAN ANAK</center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS KEPERAWATAN</center></h1>
    <div class="form-content">
        <div class="row">
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
                    <input type="number" class="form-control form-control-sm" name="eye" id="eye" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">M (Motorik)</label>
                    <input type="number" class="form-control form-control-sm" name="motorik" id="motorik" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">V (Verbal)</label>
                    <input type="number" class="form-control form-control-sm" name="verbal" id="verbal" value="0">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">GCS</label>
                    <input type="number" class="form-control form-control-sm" name="gcs" id="gcs" value="0" readonly>
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
            <div class="card card-body border border-dashed border-primary mb-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <h5 class="border-bottom pb-2 text-bold">
                                <strong>Skrining Gizi Awal Status Nutrisi (Strong Kid)</strong>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row">
                            <!-- Tampak Kurus -->
                            <div class="col-md-9">
                                <label class="form-label fw-bold">
                                    Apakah pasien tampak kurus?
                                </label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga1" id="sga1_ya" value="1">
                                    <label class="form-check-label" for="sga1_ya">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga1" id="sga1_tidak" value="0" checked>
                                    <label class="form-check-label" for="sga1_tidak">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <!-- Penurunan Berat Badan -->
                            <div class="col-md-9">
                                <label class="form-label fw-bold">
                                    Apakah terjadi penurunan berat badan selama 1 bulan terakhir?
                                </label>
                                <ul class="mb-0">
                                    <li>
                                        Berdasarkan penilaian obyektif data BB atau penilaian subyektif orang tua pasien
                                    </li>
                                    <li>
                                        Untuk bayi &lt; 1 tahun, BB tidak baik selama 3 bulan terakhir
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga2" id="sga2_ya" value="1">
                                    <label class="form-check-label" for="sga2_ya">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga2" id="sga2_tidak" value="0" checked>
                                    <label class="form-check-label" for="sga2_tidak">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <!-- Diare / Intake -->
                            <div class="col-md-9">
                                <label class="form-label fw-bold">
                                    Apakah terdapat salah satu kondisi berikut?
                                </label>
                                <ul class="mb-0">
                                    <li>
                                        Diare &gt; 5 kali/hari dan atau muntah &gt; 3 kali/hari dalam seminggu terakhir
                                    </li>
                                    <li>
                                        Asupan makanan berkurang selama seminggu terakhir
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga3" id="sga3_ya" value="1">
                                    <label class="form-check-label" for="sga3_ya">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga3" id="sga3_tidak" value="0" checked>
                                    <label class="form-check-label" for="sga3_tidak">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <!-- Resiko Malnutrisi -->
                            <div class="col-md-9">
                                <label class="form-label fw-bold">
                                    Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien beresiko mengalami malnutrisi?
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga4" id="sga4_ya" value="1">
                                    <label class="form-check-label" for="sga4_ya">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input strong-kid" type="radio" name="sga4" id="sga4_tidak" value="0" checked>
                                    <label class="form-check-label" for="sga4_tidak">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <!-- HASIL -->
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <div class="form-content text-center">
                                    <h4 id="total_skor_sk" class="display-2 fw-bold text-dark">0</h4>
                                    <label id="status_skor_sk" class="text-success fw-bold"> Tidak Beresiko Malnutrisi</label>
                                    <input type="hidden" name="skor_sga" id="skor_sga_input" value="0">
                                    <input type="hidden" name="status_sga" id="status_sga_input" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-warning mb-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <h5 class="border-bottom pb-2 text-bold">
                                <strong>Skrining Resiko Jatuh (Get Up and Go) </strong>
                            </h5>
                        </div>
                    </div>
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
                                    <h4 id="total_skor_jatuh" class="display-2 fw-bold text-dark skor">0</h4>
                                    <label id="status_skor_jatuh" class="text-success fw-bold skor"> Tidak Beresiko Jatuh</label>
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
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" id="sn_nyeri" value="1">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" id="sn_nyeri" value="0" checked>
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
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_onset" id="sn_onset" value="1">
                                        <label class="form-check-label" for="onsetAkut">Akut</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox"name="sn_onset" id="sn_onset" value="2">
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
                                <input type="number" class="form-control" name="sn_skala" id="sn_skala" placeholder="Otomatis terisi" value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label fw-bold">Metode</label>
                            <div class="col-md-10">
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" id="sn_metode" value="3">
                                        <label class="form-check-label">NIPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" id="sn_metode" value="4">
                                        <label class="form-check-label">FLACC</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
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
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto"  min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Tangisan</th>
                                        <td>
                                            <h6>0 = Tidak menangis</h6>
                                            <h6>1 = Meringis</h6>
                                            <h6>2 = Menangis kuat</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Gerakan Lengan</th>
                                        <td>
                                            <h6>0 = Relaksasi</h6>
                                            <h6>1 = Fleksi / ekstensi</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Gerakan Tungkai</th>
                                        <td>
                                            <h6>0 = relaksasi</h6>
                                            <h6>1 = Fleksi / ekstensi</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Status Terjaga</th>
                                        <td>
                                            <h6>0 = Tidur / bangun</h6>
                                            <h6>1 = Rewel</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Pola Nafas</th>
                                        <td>
                                            <h6>0 = Relaksasi</h6>
                                            <h6>1 = Perubahan pola nafas</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
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
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Kaki</th>
                                        <td>Gerakan normal / relaksasi</td>
                                        <td>Tidak tenang</td>
                                        <td>Kaki dibuat menendang / menarik diri</td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Aktivitas</th>
                                        <td>Tidur, posisi normal mudah bergerak</td>
                                        <td>Gerakan menggeliat, berguling, kaku</td>
                                        <td>Melengkungkan punggung / kaku / menghentak</td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Menangis</th>
                                        <td>Tidak menangis (bangung / tidur)</td>
                                        <td>Mengerang, merengek-rengek</td>
                                        <td>Menangis terus-menerus, terisak, menjerit</td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Bersuara</th>
                                        <td>Bersuara normal, tenang</td>
                                        <td>Tenang bila dipeluk, digendong, atau diajak bicara</td>
                                        <td>Sulit untuk ditenangkan</td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
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
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJAp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rja_perawat');

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

        const metodeMap = {
            3: '#tampil_sn_nips',
            4: '#tampil_sn_flacc'
        };

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
        // END SKRINING NYERI ------------------------------------------------------------------------------

        //Skor Resiko Jatuh
        $(function () {

            hitungSkor();

            $(".skor").change(function () {
                hitungSkor();
            });

        });

        //Skor Skrining Gizi Awal
        $(function () {

            hitungStrongKid();

            $(".gizi").change(function () {
                hitungStrongKid();
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
        loadDataPengkajianRJAp();

    });

    function loadDataPengkajianRJAp() {
        const kunjungan = $('#rja_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rja/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJAp(res);
            }
        });
    }

    function isiFormPengkajianRJAp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================

        $('#ku').val(data.ku);
        $('#kesadaran').val(data.kesadaran);
        $('#eye').val(data.eye);
        $('#motorik').val(data.motorik);
        $('#verbal').val(data.verbal);
        $('#gcs').val(data.gcs);

        $('#td_up').val(data.td_up);
        $('#td_down').val(data.td_down);
        $('#spo2').val(data.spo2);
        $('#nafas').val(data.nafas);
        $('#suhu').val(data.suhu);
        $('#nadi').val(data.nadi);
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
        $('#hasil').val(data.hasil);

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

        $('input[name="cara_berjalan"][value="' + data.cara_berjalan + '"]')
            .prop('checked', true).trigger("change");
        $('input[name="faktor_risiko"][value="' + data.faktor_risiko + '"]')
            .prop('checked', true).trigger("change");
        $('input[name="kon_obat"][value="' + data.kon_obat + '"]')
            .prop('checked', true).trigger("change");


        $("input[name='sga1'][value='"+data.sga1+"']")
            .prop('checked', true);

        $("input[name='sga2'][value='"+data.sga2+"']")
            .prop('checked', true);

        $("input[name='sga3'][value='"+data.sga3+"']")
            .prop('checked', true);

        $("input[name='sga4'][value='"+data.sga4+"']")
            .prop('checked', true);


        $('#skor_sga_input').val(data.skor_sga);
        $('#status_sga_input').val(data.status_sga);


        $('#total_skor_sk').text(data.skor_sga);

        if(data.status_sga == 1){
            $('#status_skor_sk')
                .text('Beresiko Malnutrisi')
                .removeClass('text-success')
                .addClass('text-danger');
        }else{
            $('#status_skor_sk')
                .text('Tidak Beresiko Malnutrisi')
                .removeClass('text-danger')
                .addClass('text-success');
        }

        $('#skor_sga').removeAttr('hidden');

        // Detail terapi
        $('#terapi_oral').val(data.terapi_oral);
        $('#terapi_iv').val(data.terapi_iv);

    }

    function saveDataPengkajianRJAp(btn) {
        const $button = $(btn);
        const $section = $('#rja_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rja/pr/simpan',
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

        $("#total_skor_jatuh").text(total);

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

        $("#status_skor_jatuh")
            .removeClass("text-success text-primary text-warning text-danger")
            .addClass(warna)
            .text(status);
    }

    $(".skor").on("change", hitungSkor);

    function hitungStrongKid()
    {
        let skor = 0;

        // Pertanyaan 1-3
        if ($("input[name='sga1']:checked").val() == "1") {
            skor += 1;
        }

        if ($("input[name='sga2']:checked").val() == "1") {
            skor += 1;
        }

        if ($("input[name='sga3']:checked").val() == "1") {
            skor += 1;
        }

        // Pertanyaan 4 bobot 2
        if ($("input[name='sga4']:checked").val() == "1") {
            skor += 2;
        }


        let status = 0;
        let text = "Tidak Beresiko Malnutrisi";


        if(skor >= 2){
            status = 1;
            text = "Beresiko Malnutrisi";
        }


        // tampilkan
        $('#total_skor_sk').text(skor);
        $('#status_skor_sk').text(text);


        // simpan hidden
        $('#skor_sga_input').val(skor);
        $('#status_sga_input').val(status);


        // tampilkan box hasil
        $('#skor_sga').removeAttr('hidden');
    }


    // trigger ketika pilih
    $('.strong-kid').on('change', function(){

        hitungStrongKid();

    });


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

        // Reset semua input skor
        $('#tampil_sn_bps input[type="number"], ' +
        '#tampil_sn_nips input[type="number"], ' +
        '#tampil_sn_flacc input[type="number"]'
        ).val('');

        // Kembali ke nilai default
        $('input[name="sn_skala"]').val(0);
    }
</script>
