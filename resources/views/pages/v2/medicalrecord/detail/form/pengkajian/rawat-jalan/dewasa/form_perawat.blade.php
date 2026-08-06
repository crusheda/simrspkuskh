<div class="form-wrapper">
    <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN KEPERAWATAN</center></h1>
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
                                        <label class="form-check-label">Ya</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="sn_nyeri" id="nyeriTidak" value="0" checked>
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
                                        <input class="form-check-input" type="radio" name="sn_onset" id="onsetAkut" value="1">
                                        <label class="form-check-label" for="onsetAkut">Akut</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"name="sn_onset" id="onsetKronis" value="2">
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
                                        <input class="form-check-input" type="radio" name="sn_metode" value="1">
                                        <label class="form-check-label">NRS</label>
                                    </div>
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="2">
                                        <label class="form-check-label">BPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="3">
                                        <label class="form-check-label">NIPS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="4">
                                        <label class="form-check-label">FLACC</label>
                                    </div> --}}
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sn_metode" value="5">
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
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_1" id="kb_edu_1">
                                    <label class="form-check-label">Kondisi kesehatan dan diagnosa pasti dan penatalaksanaannya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_2" id="kb_edu_2">
                                    <label class="form-check-label">Teknik rehabilitasi</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_3" id="kb_edu_3">
                                    <label class="form-check-label">Hak dan Kewajiban Pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_4" id="kb_edu_4">
                                    <label class="form-check-label">Proses pemberian informed consent</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_5" id="kb_edu_5">
                                    <label class="form-check-label">Cuci tangan dengan benar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_6" id="kb_edu_6">
                                    <label class="form-check-label">Edukasi perencanaan pulang</label>
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_7" id="kb_edu_7">
                                    <label class="form-check-label">Penggunaan obat secara efektif dan efek samping interaksinya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_8" id="kb_edu_8">
                                    <label class="form-check-label">Manajemen Nyeri</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_9" id="kb_edu_9">
                                    <label class="form-check-label">Hak untuk berpartisipasi pada pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_10" id="kb_edu_10">
                                    <label class="form-check-label">Penundaan pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_11" id="kb_edu_11">
                                    <label class="form-check-label">Bahaya merokok</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_12" id="kb_edu_12">
                                    <label class="form-check-label">Lain-lainnya</label>
                                </div>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_13" id="kb_edu_13">
                                    <label class="form-check-label">Diet dan Nutrisi</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_14" id="kb_edu_14">
                                    <label class="form-check-label">Penggunaan alat medis yang aman</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_15" id="kb_edu_15">
                                    <label class="form-check-label">Prosedur pemeriksaan penunjang</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_16" id="kb_edu_16">
                                    <label class="form-check-label">Keterlambatan pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_17" id="kb_edu_17">
                                    <label class="form-check-label">Edukasi rujukan pasien</label>
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
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_1" id="diag_1">
                                    <label class="form-check-label">Bersihkan jalan nafas tidak efektif</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_2" id="diag_2">
                                    <label class="form-check-label">Pola nafas tidak efektif</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_3" id="diag_3">
                                    <label class="form-check-label">Perfusi perifer tidak efektif</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_4" id="diag_4">
                                    <label class="form-check-label">Diare</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_5" id="diag_5">
                                    <label class="form-check-label">Nyeri Akut</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_6" id="diag_6">
                                    <label class="form-check-label">Nausea</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_7" id="diag_7">
                                    <label class="form-check-label">Hipertermi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_8" id="diag_8">
                                    <label class="form-check-label">Ansietas</label>
                                </div>

                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_9" id="diag_9">
                                    <label class="form-check-label">Gangguan integritas kulit / jaringan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_10" id="diag_10">
                                    <label class="form-check-label">Gangguan eliminasi urin</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_11" id="diag_11">
                                    <label class="form-check-label">Intoleransi aktifitas</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_12" id="diag_12">
                                    <label class="form-check-label">Gangguan mobilitas fisik</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_13" id="diag_13">
                                    <label class="form-check-label">Gangguan pertukaran gas</label>
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
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_1" id="tin_1">
                                    <label class="form-check-label" for="tin_1">Ajarkan teknik relaksasi dan nafas dalam</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_2" id="tin_2">
                                    <label class="form-check-label" for="tin_2">Pertahankan body alignment dan posisi yang nyaman</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_3" id="tin_3">
                                    <label class="form-check-label" for="tin_3">Tenangkan Pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_4" id="tin_4">
                                    <label class="form-check-label" for="tin_4">Berikan Pendidikan Kesehatan ke pasien dan keluarga</label>
                                </div>

                            </div>

                            <!-- Kolaborasi -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">Kolaborasi</label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_5" id="tin_5">
                                    <label class="form-check-label" for="tin_5">Rawat Luka</label>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6">
                                            <label class="form-check-label">Oral</label>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="terapi_oral" id="terapi_oral">
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_7" id="tin_7">
                                            <label class="form-check-label">IV/SC/IM</label>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="terapi_iv" id="terapi_iv">
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
        <button class="btn btn-success" onclick="saveDataPengkajianRJDp(this)">
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
                $("#perubahan_berat").slideUp();

                $("input[name='bb_ubah'][value='0']").prop("checked", true);
            }
        }
        loadDataPengkajianRJDp();

    });

    function loadDataPengkajianRJDp() {
        const kunjungan = $('#rjd_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjd/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJDp(res);
            }
        });
    }

    function isiFormPengkajianRJDp(data){

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
            .prop('checked', true);
        $('input[name="faktor_risiko"][value="' + data.faktor_risiko + '"]')
            .prop('checked', true);
        $('input[name="kon_obat"][value="' + data.kon_obat + '"]')
            .prop('checked', true);

        $('input[name="bb_turun"][value="' + data.bb_turun + '"]')
            .prop('checked', true).trigger("change");
        $('input[name="bb_ubah"][value="' + data.bb_ubah + '"]')
            .prop('checked', true);
        $('input[name="nafsu_makan"][value="' + data.nafsu_makan + '"]')
            .prop('checked', true);

        $('#kondisi_khusus').val(data.kondisi_khusus);

        $('#skor_gizi').val(data.skor_gizi);
        $('#status_skor').val(data.status_skor);

        //EDUKASI PASIEN KELUARGA
        // Edukasi awal
        $("input[name='edu_1'][value='" + data.edu_1 + "']")
            .prop('checked', true);

        $("input[name='edu_2'][value='" + data.edu_2 + "']")
            .prop('checked', true);

        $("input[name='edu_3'][value='" + data.edu_3 + "']")
            .prop('checked', true);


        // Kebutuhan Edukasi
        $('#kb_edu_1').prop('checked', Number(data.edukasi_diagnosa) === 1);
        $('#kb_edu_2').prop('checked', Number(data.edukasi_rehab_medik) === 1);
        $('#kb_edu_3').prop('checked', Number(data.edukasi_hkp) === 1);
        $('#kb_edu_4').prop('checked', Number(data.edukasi_informed_consent) === 1);
        $('#kb_edu_5').prop('checked', Number(data.edukasi_cuci_tangan) === 1);
        $('#kb_edu_6').prop('checked', Number(data.edukasi_perencanaan_pulang) === 1);
        $('#kb_edu_7').prop('checked', Number(data.edukasi_obat) === 1);
        $('#kb_edu_8').prop('checked', Number(data.edukasi_nyeri) === 1);
        $('#kb_edu_9').prop('checked', Number(data.edukasi_hak_partisipasi) === 1);
        $('#kb_edu_10').prop('checked', Number(data.edukasi_penundaan) === 1);
        $('#kb_edu_11').prop('checked', Number(data.edukasi_bahaya_merokok) === 1);
        $('#kb_edu_13').prop('checked', Number(data.edukasi_nutrisi) === 1);
        $('#kb_edu_14').prop('checked', Number(data.edukasi_penggunaan_alat) === 1);
        $('#kb_edu_15').prop('checked', Number(data.edukasi_prosedure) === 1);
        $('#kb_edu_16').prop('checked', Number(data.edukasi_keterlambatan) === 1);
        $('#kb_edu_17').prop('checked', Number(data.edukasi_rujukan) === 1);
        // Lainnya
        $('#kb_edu_12').prop('checked', Number(data.status_lain) === 1);
        $('#kb_edu_lain').val(data.kb_edu_lain);

        //MASALAH KEPERAWATAN
        $('#diag_1').prop('checked', data.diag_1 == 1);
        $('#diag_2').prop('checked', data.diag_2 == 1);
        $('#diag_3').prop('checked', data.diag_3 == 1);
        $('#diag_4').prop('checked', data.diag_4 == 1);
        $('#diag_5').prop('checked', data.diag_5 == 1);
        $('#diag_6').prop('checked', data.diag_6 == 1);
        $('#diag_7').prop('checked', data.diag_7 == 1);
        $('#diag_8').prop('checked', data.diag_8 == 1);

        $('#diag_9').prop('checked', data.diag_9 == 1);
        $('#diag_10').prop('checked', data.diag_10 == 1);
        $('#diag_11').prop('checked', data.diag_11 == 1);
        $('#diag_12').prop('checked', data.diag_12 == 1);
        $('#diag_13').prop('checked', data.diag_13 == 1);


        // Tindakan
        $('#tin_1').prop('checked', data.tin_1 == 1);
        $('#tin_2').prop('checked', data.tin_2 == 1);
        $('#tin_3').prop('checked', data.tin_3 == 1);
        $('#tin_4').prop('checked', data.tin_4 == 1);
        $('#tin_5').prop('checked', data.tin_5 == 1);

        $('#tin_6').prop('checked', data.tin_6 == 1).trigger("change");;
        $('#tin_7').prop('checked', data.tin_7 == 1).trigger("change");;


        // Detail terapi
        $('#terapi_oral').val(data.terapi_oral);
        $('#terapi_iv').val(data.terapi_iv);

    }

    function saveDataPengkajianRJDp(btn) {
        const $button = $(btn);
        const $section = $('#rjd_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjd/pr/simpan',
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
</script>
