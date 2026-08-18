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
                                    <input class="form-check-input skor" type="radio" name="cara_berjalan" id="cara_ya" value="1" checked>
                                    <label class="form-check-label" for="cara_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="cara_berjalan" id="cara_tidak" value="0" >
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
                                    <input class="form-check-input skor" type="radio" name="faktor_risiko" id="risiko_ya" value="1" checked>
                                    <label class="form-check-label" for="risiko_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="faktor_risiko" id="risiko_tidak" value="0">
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
                                    <input class="form-check-input skor" type="radio" name="kon_obat" id="obat_ya" value="1" checked>
                                    <label class="form-check-label" for="obat_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="kon_obat" id="obat_tidak" value="0">
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
                                <input type="number" class="form-control" name="sn_skala" placeholder="Otomatis terisi" value="0" readonly>
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
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="2">
                                        <label class="form-check-label">BPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="5">
                                        <label class="form-check-label">VAS</label>
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
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>1 = rileks</h6>
                                            <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                            <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                                    </tr>
                                    <tr>
                                        <th>Ekspresi Wajah</th>
                                        <td>
                                            <h6>1 = rileks</h6>
                                            <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                            <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                                        </td>
                                        <td class="text-center"><input type="number" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
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
        <!-- ========================================================= -->
        <!-- ASSESMEN SINDROM GERIATRI -->
        <!-- ========================================================= -->

        <div class="col-md-12 mb-3">

            <div class="card card-body border border-dashed border-primary">

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
        setValIfExists('#ku', data.ku);
        setValIfExists('#kesadaran', data.kesadaran);

        setNumberIfExists('#eye', data.eye);
        setNumberIfExists('#motorik', data.motorik);
        setNumberIfExists('#verbal', data.verbal);
        setNumberIfExists('#gcs', data.gcs);

        setNumberIfExists('#td_up', data.td_up);
        setNumberIfExists('#td_down', data.td_down);
        setNumberIfExists('#spo2', data.spo2);
        setNumberIfExists('#nafas', data.nafas);

        setSuhuIfExists('#suhu', data.suhu);

        setNumberIfExists('#nadi', data.nadi);
        setRadioIfExists('abn', data.abn);



        // ======================================================
        // KONDISI SOSIAL
        // ======================================================

        // Status Psikologi (Checkbox)
        setCheckedIfExists('#tak', data.tak);
        setCheckedIfExists('#marah', data.marah);
        setCheckedIfExists('#cemas', data.cemas);
        setCheckedIfExists('#takut', data.takut);
        setCheckedIfExists('#sedih', data.sedih);
        setCheckedIfExists('#bundir', data.bundir);

        setValIfExists('#pse_lain', data.pse_lain);

        if (data.pse_lain !== null && data.pse_lain !== undefined && data.pse_lain !== '') {
            $('#pse_lain').slideDown();
        }

        // Status Mental
        setRadioIfExists('sm', data.sm);

        setValIfExists('#perilaku', data.perilaku);
        setValIfExists('#kekerasan', data.kekerasan);

        // Hubungan Sosial
        setRadioIfExists('hub', data.hub);
        setRadioIfExists('tinggal', data.tinggal);
        setValIfExists('#tinggal_lain', data.tinggal_lain);

        // Spiritual
        setRadioIfExists('kbt', data.kbt);
        setRadioIfExists('nk', data.nk);
        setValIfExists('#nk_lain', data.nk_lain);
        setValIfExists('#pk', data.pk);


        // Ekonomi
        setRadioIfExists('hasil', data.hasil);

        //SKRINING NYERI
        setRadioIfExists('sn_nyeri', data.sn_nyeri);

        setRadioIfExists('sn_onset', data.sn_onset);

        setValIfExists('#sn_skala', data.sn_skala);

        setRadioIfExists('sn_metode', data.sn_metode);

        setValIfExists('#sn_pencetus', data.sn_pencetus);
        setValIfExists('#sn_gambaran', data.sn_gambaran);
        setValIfExists('#sn_durasi', data.sn_durasi);
        setValIfExists('#sn_lokasi', data.sn_lokasi);

        // ======================================================
        // SKRINING LAINNYA
        // ======================================================

        setRadioIfExists('cara_berjalan', data.cara_berjalan);
        setRadioIfExists('faktor_risiko', data.faktor_risiko);
        setRadioIfExists('kon_obat', data.kon_obat);

        setRadioIfExists('bb_turun', data.bb_turun);
        setRadioIfExists('bb_ubah', data.bb_ubah);
        setRadioIfExists('nafsu_makan', data.nafsu_makan);

        setValIfExists('#kondisi_khusus', data.kondisi_khusus);

        setValIfExists('#skor_gizi', data.skor_gizi);
        setValIfExists('#status_skor', data.status_skor);

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
</script>
