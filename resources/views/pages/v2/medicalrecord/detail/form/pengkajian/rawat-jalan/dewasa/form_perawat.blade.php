<div class="form-wrapper">
    <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN KEPERAWATAN</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Keluhan</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="keluhan">
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Tekanan Darah</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="td_up">
                        <div class="input-group-text"> / </div>
                        <input type="text" class="form-control" name="td_down">
                        <div class="input-group-text"> mmHg </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Nadi</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="nadi">
                        <div class="input-group-text">X/menit, Reguler / Ireguler</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">Nafas</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="input-group input-group-sm flex-grow-1">
                            <input type="text" class="form-control" name="nafas">
                            <span class="input-group-text">X/menit</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Suhu</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="suhu">
                        <div class="input-group-text">°C</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold">SpO2</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="spo2">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Berat Badan</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="bb">
                        <div class="input-group-text">Kg</div>
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
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_1">
                                    <label class="form-check-label" for="checkPrimary"> Tidak ada kelainan </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_2">
                                    <label class="form-check-label" for="checkPrimary"> Marah </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_3">
                                    <label class="form-check-label" for="checkPrimary"> Cemas </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_4">
                                    <label class="form-check-label" for="checkPrimary"> Takut </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_5">
                                    <label class="form-check-label" for="checkPrimary"> Sedih </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_6">
                                    <label class="form-check-label" for="checkPrimary"> Kecenderungan bunuh diri </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="pse_7">
                                    <label class="form-check-label" for="checkPrimary"> Lainnya </label>
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
                                    <input class="form-check-input" type="radio" name="sm" id="sm_1" value="0">
                                    <label class="form-check-label">
                                        Sadar dan orientasi baik
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="sm" id="sm_2" value="1">
                                    <label class="form-check-label">
                                        Ada masalah perilaku
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="sm_2_detail" name="sm_2_detail" placeholder="Ada masalah perilaku">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="sm" id="sm_3" value="2">
                                    <label class="form-check-label">
                                        Perilaku kekerasan yang dialami pasien sebelumnya
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="sm_3_detail" name="sm_3_detail" placeholder="Perilaku kekerasan yang dialami pasien sebelumnya">
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
                                    <input class="form-check-input" type="radio" name="hub" id="hub_baik" value="0">
                                    <label class="form-check-label">
                                        Baik
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hub" id="hub_tidak" value="1">
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
                                    <input class="form-check-input" type="radio" name="tinggal" id="tinggal_rumah" value="0">
                                    <label class="form-check-label">
                                        Rumah
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tinggal" id="tinggal_panti" value="1">
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
                                    <input type="text" class="form-control" id="tinggal_lain_detail" name="tinggal" placeholder="Sebutkan">
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
                                <input type="text" class="form-control" id="agama" name="agama" placeholder="Agama otomatis">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Kebiasaan beribadah teratur</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kbt" id="kbt_ya" value="0">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kbt" id="kbt_tidak" value="1">
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
                                    <input type="text" class="form-control" id="nk_lain" name="nk" placeholder="Sebutkan">
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
                                <input type="text" class="form-control" id="kerja" name="kerja" placeholder="Pekerjaan otomatis">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label">Penghasilan per bulan</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_1" value="0">
                                    <label class="form-check-label">
                                        < Rp. 5.000.000
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_2" value="1">
                                    <label class="form-check-label">
                                        Rp. 5.000.000 - Rp. 10.000.000
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hasil" id="hasil_3" value="2">
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
        <div class="col-md-12">
            <div class="card card-body border border-dashed border-success mb-3">
                <h6 class="mb-3">SKRINING NYERI</h6>

                <!-- Baris 1 -->
                <div class="row mb-3 align-items-center">

                    <div class="col-md-4">
                        <div class="row align-items-center">
                            <label class="col-md-4 col-form-label fw-bold">Nyeri</label>

                            <div class="col-md-8">
                                <div class="d-flex gap-4">

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_nyeri" id="nyeriYa" value="1">
                                        <label class="form-check-label" for="nyeriYa">Ya</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="sn_nyeri" id="nyeriTidak" value="0" checked>
                                        <label class="form-check-label" for="nyeriTidak">Tidak</label>
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
                                        <input class="form-check-input" type="radio" name="sn_onset" id="onsetAkut" value="Akut">
                                        <label class="form-check-label" for="onsetAkut">Akut</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"name="sn_onset" id="onsetKronis" value="Kronis">
                                        <label class="form-check-label" for="onsetKronis">Kronis</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris 2 -->
                <div class="row mb-3 align-items-center">

                    <div class="col-md-4">
                        <div class="row align-items-center">
                            <label class="col-md-4 col-form-label fw-bold">Skala Nyeri</label>

                            <div class="col-md-8">
                                <select class="form-select" name="sn_skala">
                                    @for($i=0;$i<=10;$i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <label class="col-md-2 col-form-label fw-bold">Metode</label>
                            <div class="col-md-10">
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="NRS">
                                        <label class="form-check-label">NRS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="BPS">
                                        <label class="form-check-label">BPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="NIPS">
                                        <label class="form-check-label">NIPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="FLACC">
                                        <label class="form-check-label">FLACC</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="VAS">
                                        <label class="form-check-label">VAS</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris 3 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Pencetus</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_pencetus" placeholder="[ Pencetus ]">
                    </div>
                </div>

                <!-- Baris 4 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Gambaran</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_gambaran" placeholder="[ Gambaran ]">
                    </div>
                </div>

                <!-- Baris 5 -->
                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Durasi</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_durasi" placeholder="[ Durasi ]">
                    </div>
                </div>

                <!-- Baris 6 -->
                <div class="row align-items-center">
                    <label class="col-md-2 col-form-label fw-bold">Lokasi</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="sn_lokasi" placeholder="[ Lokasi ]">
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
                                    <li>Obat anti doabetes/insulin</li>
                                </ul>

                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="obat" id="obat_ya" value="1">
                                    <label class="form-check-label" for="obat_ya">Ya</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skor" type="radio" name="obat" id="obat_tidak" value="0" checked>
                                    <label class="form-check-label" for="obat_tidak">Tidak</label>
                                </div>
                            </div>
                            <!-- HASIL -->
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <div class="form-content text-center">
                                    <h4 id="total_skor" class="display-2 fw-bold text-dark">0</h4>
                                    <label id="status_skor" class="text-success fw-bold"> Tidak Beresiko Jatuh</label>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-success mb-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <h5 class="border-bottom pb-2 text-bold">
                                <strong>Edukasi </strong>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <div class="row align-items-center">
                            <label class="col-md-5 col-form-label">Kesediaan pasien/keluarga menerima informasi</label>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_1" id="edu_1_ya" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_1" id="edu_1_tidak" value="0">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <label class="col-md-5 col-form-label">Terdapat hambatan dalam edukasi</label>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_2" id="edu_2_ya" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_2" id="edu_2_ya" value="0">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <label class="col-md-5 col-form-label">Dibutuhkan penerjemah</label>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_3" id="edu_3_ya" value="1">
                                    <label class="form-check-label">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edu_3" id="edu_3_ya" value="0">
                                    <label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-label fw-bold">Kebutuhan Edukasi</label>
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_1">
                                    <label class="form-check-label" for="kb_edu_1">Kondisi kesehatan dan diagnosa pasti dan penatalaksanaannya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_2">
                                    <label class="form-check-label" for="kb_edu_2">Teknik rehabilitasi</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_3">
                                    <label class="form-check-label" for="kb_edu_3">Hak dan Kewajiban Pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_4">
                                    <label class="form-check-label" for="kb_edu_4">Proses pemberian informed consent</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_5">
                                    <label class="form-check-label" for="kb_edu_5">Cuci tangan dengan benar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_6">
                                    <label class="form-check-label" for="kb_edu_6">Edukasi perencanaan pulang</label>
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_7">
                                    <label class="form-check-label" for="kb_edu_7">Penggunaan obat secara efektif dan efek samping interaksinya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_8">
                                    <label class="form-check-label" for="kb_edu_8">Manajemen Nyeri</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_9">
                                    <label class="form-check-label" for="kb_edu_9">Hak untuk berpartisipasi pada pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_10">
                                    <label class="form-check-label" for="kb_edu_10">Penundaan pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_11">
                                    <label class="form-check-label" for="kb_edu_11">Bahaya merokok</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_12">
                                    <label class="form-check-label" for="kb_edu_12">Lain-lainnya</label>
                                </div>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_13">
                                    <label class="form-check-label" for="kb_edu_13">Diet dan Nutrisi</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_14">
                                    <label class="form-check-label" for="kb_edu_14">Penggunaan alat medis yang aman</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_15">
                                    <label class="form-check-label" for="kb_edu_15">Prosedur pemeriksaan penunjang</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_16">
                                    <label class="form-check-label" for="kb_edu_16">Keterlambatan pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="kb_edu_17">
                                    <label class="form-check-label" for="kb_edu_17">Edukasi rujukan pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="text" class="form-control" id="kb_edu_lain" name="kb_edu_lain" placeholder="Sebutkan">
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
                                <strong>Masalah Keperawatan </strong>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <label class="form-label fw-bold">Diagnosa Keperawatan</label>

                            <!-- Kolom Kiri -->
                            <div class="col-md-6">

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_1">
                                    <label class="form-check-label" for="diag_1">Bersihkan jalan nafas tidak efektif</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_2">
                                    <label class="form-check-label" for="diag_2">Pola nafas tidak efektif</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_3">
                                    <label class="form-check-label" for="diag_3">Perfusi perifer tidak efektif</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_4">
                                    <label class="form-check-label" for="diag_4">Diare</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_5">
                                    <label class="form-check-label" for="diag_5">Nyeri Akut</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_6">
                                    <label class="form-check-label" for="diag_6">Nausea</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_7">
                                    <label class="form-check-label" for="diag_7">Hipertermi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_8">
                                    <label class="form-check-label" for="diag_8">Ansietas</label>
                                </div>

                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_9">
                                    <label class="form-check-label" for="diag_9">Gangguan integritas kulit / jaringan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_10">
                                    <label class="form-check-label" for="diag_10">Gangguan eliminasi urin</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_11">
                                    <label class="form-check-label" for="diag_11">Intoleransi aktifitas</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_12">
                                    <label class="form-check-label" for="diag_12">Gangguan mobilitas fisik</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="diag_13">
                                    <label class="form-check-label" for="diag_13">Gangguan pertukaran gas</label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Perencanaan dan Tindakan -->
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <label class="form-label fw-bold">Perencanaan dan Tindakan</label>

                            <!-- Mandiri -->
                            <div class="col-md-6 border-end">

                                <label class="form-label fw-semibold">Mandiri</label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="tin_1">
                                    <label class="form-check-label" for="tin_1">Ajarkan teknik relaksasi dan nafas dalam</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="tin_2">
                                    <label class="form-check-label" for="tin_2">Pertahankan body alignment dan posisi yang nyaman</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="tin_3">
                                    <label class="form-check-label" for="tin_3">Tenangkan Pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="tin_4">
                                    <label class="form-check-label" for="tin_4">Berikan Pendidikan Kesehatan ke pasien dan keluarga</label>
                                </div>

                            </div>

                            <!-- Kolaborasi -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">Kolaborasi</label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" id="tin_5">
                                    <label class="form-check-label" for="tin_5">Rawat Luka</label>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input check-primary" type="checkbox" id="tin_6">
                                            <label class="form-check-label">Oral</label>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="terapi_oral">
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input check-primary" type="checkbox" id="tin_7">
                                            <label class="form-check-label">IV/SC/IM</label>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="terapi_iv">
                                    </div>
                                </div>

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
        <button class="btn btn-success">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {

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
                // Tidak
                $('#perubahan_berat').slideUp();

                // Hapus pilihan sebelumnya
                $('input[name="bb_ubah"]').prop('checked', false);

                // Hapus pilihan sebelumnya
                $('input[name="bb_turun"]').val() == '0';
            }
        }

    });

    function hitungSkor(){

        let total = 0;

        total += parseInt($("input[name='cara_berjalan']:checked").val()) || 0;
        total += parseInt($("input[name='faktor_risiko']:checked").val()) || 0;
        total += parseInt($("input[name='obat']:checked").val()) || 0;

        $("#total_skor").text(total);

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

        $("#status_skor")
            .removeClass("text-success text-primary text-warning text-danger")
            .addClass(warna)
            .text(status);
    }

    $(".skor").on("change", hitungSkor);

    function hitungGizi(){

        let skor = 0;

        skor += Number($("input[name='bb_turun']:checked").val());
        skor += Number($("input[name='bb_ubah']:checked").val());
        skor += Number($("input[name='nafsu_makan']:checked").val());

        $("#total_gizi").text(skor);

        let status = "";
        let evaluasi = "";
        let warna = "";

        if(skor <= 1){

            status = "Risiko Ringan";
            evaluasi = "Monitoring & evaluasi setelah 7 hari perawatan";
            warna = "text-success";

        }else if(skor <= 3){

            status = "Risiko Sedang";
            evaluasi = "Monitoring & evaluasi setelah 3 hari perawatan";
            warna = "text-warning";

        }else{

            status = "Risiko Tinggi";
            evaluasi = "Segera konsultasi ke Ahli Gizi";
            warna = "text-danger";

        }

        $("#status_gizi")
            .removeClass("text-success text-warning text-danger")
            .addClass(warna)
            .text(status);

        $("#evaluasi_gizi").text(evaluasi);

    }
</script>
