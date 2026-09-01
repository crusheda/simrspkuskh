<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">JIWA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
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
                        @foreach ($list['tingkat_kesadaran'] as $item)
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
                                            <input class="form-check-input gizi" type="radio" name="nafsu_makan" id="nafsu_makan1" value="1">
                                            <label class="form-check-label">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input gizi" type="radio" name="nafsu_makan" id="nafsu_makan2" value="0" checked>
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
                                    <input class="form-check-input" type="radio" name="edu_1" id="edu_1_ya" value="1" checked>
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
                                    <input class="form-check-input" type="radio" name="edu_2" id="edu_2_ya" value="1" checked>
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
                                    <input class="form-check-input" type="radio" name="edu_3" id="edu_3_ya" value="0" checked>
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
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_1" id="kb_edu_1" checked>
                                    <label class="form-check-label">Kondisi kesehatan dan diagnosa pasti dan penatalaksanaannya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_2" id="kb_edu_2">
                                    <label class="form-check-label">Teknik rehabilitasi</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_3" id="kb_edu_3" checked>
                                    <label class="form-check-label">Hak dan Kewajiban Pasien</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_4" id="kb_edu_4" checked>
                                    <label class="form-check-label">Proses pemberian informed consent</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_5" id="kb_edu_5" checked>
                                    <label class="form-check-label">Cuci tangan dengan benar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_6" id="kb_edu_6" checked>
                                    <label class="form-check-label">Edukasi perencanaan pulang</label>
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_7" id="kb_edu_7" checked>
                                    <label class="form-check-label">Penggunaan obat secara efektif dan efek samping interaksinya</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_8" id="kb_edu_8">
                                    <label class="form-check-label">Manajemen Nyeri</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_9" id="kb_edu_9" checked>
                                    <label class="form-check-label">Hak untuk berpartisipasi pada pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_10" id="kb_edu_10">
                                    <label class="form-check-label">Penundaan pelayanan</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_11" id="kb_edu_11" checked>
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
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="kb_edu_13" id="kb_edu_13" checked>
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
            <div class="card card-body border border-dashed border-primary">
                <label class="form-label fw-bold">
                    Masalah Keperawatan
                </label>
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_1" id="diag_jiwa_1">

                            <label class="form-check-label" for="diag_jiwa_1">
                                Ansietas
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_2" id="diag_jiwa_2">
                            <label class="form-check-label" for="diag_jiwa_2">
                                Defisit Pengetahuan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_3" id="diag_jiwa_3">
                            <label class="form-check-label" for="diag_jiwa_3">
                                Risiko Perilaku Kekerasan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_4" id="diag_jiwa_4">
                            <label class="form-check-label" for="diag_jiwa_4">
                                Defisit Perawatan Diri
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_5" id="diag_jiwa_5">
                            <label class="form-check-label" for="diag_jiwa_5">
                                Harga Diri Rendah
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_6" id="diag_jiwa_6">
                            <label class="form-check-label" for="diag_jiwa_6">
                                Isolasi Sosial
                            </label>
                        </div>
                    </div>
                    <!-- Kolom Tengah -->
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_7" id="diag_jiwa_7">
                            <label class="form-check-label" for="diag_jiwa_7">
                                Keputusasaan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_8" id="diag_jiwa_8">
                            <label class="form-check-label" for="diag_jiwa_8">
                                Koping Tidak Efektif
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_9" id="diag_jiwa_9">
                            <label class="form-check-label" for="diag_jiwa_9">
                                Waham
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_10" id="diag_jiwa_10">
                            <label class="form-check-label" for="diag_jiwa_10">
                                Perilaku Kekerasan
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_11" id="diag_jiwa_11">
                            <label class="form-check-label" for="diag_jiwa_11">
                                Gangguan Persepsi Sensori
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <label class="form-input-label">Diagnosa Keperawatan Lainnya</label>
                            <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-body border border-dashed border-primary">
                <div class="row">
                    <label class="form-label fw-bold">
                        Perencanaan dan Tindakan
                    </label>
                    <div class="col-md-6 border-end">
                        <label class="form-label fw-semibold">
                            Mandiri
                        </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_1" id="tin_jiwa_1" checked>
                            <label class="form-check-label" for="tin_jiwa_1">
                                Ajarkan teknik relaksasi
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_2" id="tin_jiwa_2" checked>
                            <label class="form-check-label" for="tin_jiwa_2">
                                Bina Hubungan Saling Percaya
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_3" id="tin_jiwa_3" checked>
                            <label class="form-check-label" for="tin_jiwa_3">
                                Diskusikan dengan pasien / keluarga
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_4" id="tin_jiwa_4" checked>
                            <label class="form-check-label" for="tin_jiwa_4">
                                Latih Strategi Pelaksanaan
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row align-items-center mb-2">
                            <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6" checked>
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
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJJp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjj_perawat');
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

        //Skor Skrining Gizi Awal
        $(function () {

            hitungStrongKid();

            $(".gizi").change(function () {
                hitungStrongKid();
            });

        });

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

        loadDataPengkajianRJJp();

    });

    function loadDataPengkajianRJJp() {
        const kunjungan = $('#rjj_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjj/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJJp(res);
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

    function isiFormPengkajianRJJp(data){

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

        setRadioIfExists('cara_berjalan', data.cara_berjalan);
        setRadioIfExists('faktor_risiko', data.faktor_risiko);
        setRadioIfExists('kon_obat', data.kon_obat);

        setRadioIfExists('bb_turun', data.bb_turun);
        setRadioIfExists('bb_ubah', data.bb_ubah);
        setRadioIfExists('nafsu_makan', data.nafsu_makan);

        setValIfExists('#kondisi_khusus', data.kondisi_khusus);

        setValIfExists('#skor_gizi', data.skor_gizi);
        setValIfExists('#status_skor', data.status_skor);

        //STRONG KID
        $("input[name='sga1'][value='"+data.sga1+"']")
            .prop('checked', true).trigger("change");
        $("input[name='sga2'][value='"+data.sga2+"']")
            .prop('checked', true).trigger("change");
        $("input[name='sga3'][value='"+data.sga3+"']")
            .prop('checked', true).trigger("change");
        $("input[name='sga4'][value='"+data.sga4+"']")
            .prop('checked', true).trigger("change");
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

        //EDUKASI PASIEN KELUARGA
        // Edukasi awal
        setRadioIfExists('edu_1', data.edu_1);
        setRadioIfExists('edu_2', data.edu_2);
        setRadioIfExists('edu_3', data.edu_3);


        // Kebutuhan Edukasi
        setCheckedIfExists('#kb_edu_1', data.edukasi_diagnosa);
        setCheckedIfExists('#kb_edu_2', data.edukasi_rehab_medik);
        setCheckedIfExists('#kb_edu_3', data.edukasi_hkp);
        setCheckedIfExists('#kb_edu_4', data.edukasi_informed_consent);
        setCheckedIfExists('#kb_edu_5', data.edukasi_cuci_tangan);
        setCheckedIfExists('#kb_edu_6', data.edukasi_perencanaan_pulang);
        setCheckedIfExists('#kb_edu_7', data.edukasi_obat);
        setCheckedIfExists('#kb_edu_8', data.edukasi_nyeri);
        setCheckedIfExists('#kb_edu_9', data.edukasi_hak_partisipasi);
        setCheckedIfExists('#kb_edu_10', data.edukasi_penundaan);
        setCheckedIfExists('#kb_edu_11', data.edukasi_bahaya_merokok);
        setCheckedIfExists('#kb_edu_12', data.status_lain);
        setCheckedIfExists('#kb_edu_13', data.edukasi_nutrisi);
        setCheckedIfExists('#kb_edu_14', data.edukasi_penggunaan_alat);
        setCheckedIfExists('#kb_edu_15', data.edukasi_prosedure);
        setCheckedIfExists('#kb_edu_16', data.edukasi_keterlambatan);
        setCheckedIfExists('#kb_edu_17', data.edukasi_rujukan);
        // Lainnya
        setCheckedIfExists('#kb_edu_12', data.status_lain);
        setValIfExists('#kb_edu_lain', data.kb_edu_lain);

        // Masalah Keperawatan Jiwa
        for (let i = 1; i <= 11; i++) {
            $('#diag_jiwa_' + i).prop(
                'checked',
                data['diag_jiwa_' + i] == 1
            );
        }

        // Tindakan
        for (let i = 1; i <= 6; i++) {
            $('#tin_jiwa_' + i).prop(
                'checked',
                data['tin_jiwa_' + i] == 1
            );
        }
        setValIfExists('#diag_lain', data.diag_lain);
        setCheckedIfExists('#tin_6', data.tin_6);
        setCheckedIfExists('#tin_7', data.tin_7);


        // Detail terapi
        setValIfExists('#terapi_oral', data.terapi_oral);
        setValIfExists('#terapi_iv', data.terapi_iv);

    }

    function saveDataPengkajianRJJp(btn) {
        const $button = $(btn);
        const $section = $('#rjj_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjj/pr/simpan',
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

    //Strong Kid
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
