<div class="form-wrapper">
    <div class="form-content">
        <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter IGD</a>)</center></h1>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="row row-cols-1 row-cols-md-5 g-3">
                    <div class="col">
                        <div class="alert alert-danger">
                            <h6>ATS 1 (Merah) - Segera</h6>
                            <ul class="mb-0">
                                <li>Obstruksi jalan nafas</li>
                                <li>Henti nafas / henti jantung</li>
                                <li>Distres nafas berat</li>
                                <li>Gangguan hemodinamik berat</li>
                                <li>GCS < 8</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-warning">
                            <h6>ATS 2 (Kuning) - ≤ 10 Menit</h6>
                            <ul class="mb-0">
                                <li>Resiko obstruksi jalan nafas</li>
                                <li>Distres nafas sedang</li>
                                <li>Gangguan hemodinamik sedang</li>
                                <li>GCS 9 - 12</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-warning">
                            <h6>ATS 3 (Kuning) - ≤ 30 Menit</h6>
                            <ul class="mb-0">
                                <li>Resiko obstruksi jalan nafas</li>
                                <li>Distres nafas sedang</li>
                                <li>Gangguan hemodinamik sedang</li>
                                <li>GCS 9 - 12</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-success">
                            <h6>ATS 4 (Hijau) - ≤ 60 Menit</h6>
                            <ul class="mb-0">
                                <li>Resiko obstruksi jalan nafas</li>
                                <li>Distres nafas sedang</li>
                                <li>Gangguan hemodinamik sedang</li>
                                <li>GCS 9 - 12</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-success">
                            <h6>ATS 5 (Hijau) - ≤ 120 Menit</h6>
                            <ul class="mb-0">
                                <li>Resiko obstruksi jalan nafas</li>
                                <li>Distres nafas sedang</li>
                                <li>Gangguan hemodinamik sedang</li>
                                <li>GCS 9 - 12</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Kriteria ATS (<i>Australasian Triage Scale</i>)</h6>
                    <input type="text" class="form-control" name="ats" placeholder="Masukkan Kriteria ATS">
                    {{-- <input type="text" class="form-control" name="ats" placeholder="Masukkan Kriteria ATS"> --}}
                    {{-- <select class="form-control" id="ats">
                        <option value="">Pilih Kriteria ATS</option>
                        <option value="1">ATS 1 (Merah) - Segera</option>
                        <option value="2">ATS 2 (Kuning) - ≤ 10 Menit</option>
                        <option value="3">ATS 3 (Kuning) - ≤ 10 Menit</option>
                        <option value="4">ATS 4 (Hijau) - ≤ 30 Menit</option>
                        <option value="5">ATS 5 (Hijau) - ≤ 120 Menit</option>
                    </select> --}}
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Resiko penularan infeksi</h6>
                    <div class="row">
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="1">
                                <label class="form-check-label"> Batuk > 2 minggu dengan demam dan sesak nafas </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="2">
                                <label class="form-check-label"> Rujukan dengan suspek (konfirmasi) airbone disease </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="3">
                                <label class="form-check-label"> Tidak berisiko penularan airbone disease </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="4">
                                <label class="form-check-label"> B - 20 </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6>Anamnesis</h6>
                <div class="form-group">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1">
                        <label class="form-check-label"> Autoanamnesis (tanya jawab langsung dengan pasien) </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2">
                        <label class="form-check-label"> Alloanamnesis (tanya jawab dengan keluarga atau orang lain) </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-warning mb-3">
                    <div class="row">
                        <h5>I. Pengkajian <b class="text-warning">Primary Survey</b></h5>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Keadaan Umum</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="keu" placeholder="">
                                    {{-- <div class="form-check form-check-inline mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="1">
                                        <label class="form-check-label"> Baik </label>
                                    </div>
                                    <div class="form-check form-check-inline mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="2">
                                        <label class="form-check-label"> Sedang </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="3">
                                        <label class="form-check-label"> Lemah </label>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="mb-2">
                                <h6>Jalan Nafas (<i>Airways</i>)</h6>
                                <div class="form-group">
                                    <div class="form-check form-check-inline mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                        <label class="form-check-label"> Palen </label>
                                    </div>
                                    <div class="form-check form-check-inline mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                        <label class="form-check-label"> Obstruksi Parsial </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                        <label class="form-check-label"> Obstruksi Total </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Sirkulasi ( C )</h6>
                                <div class="form-group">
                                    <label class="form-label">Frekuensi Nadi</label>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control" name="nadi">
                                        <div class="input-group-text">X/menit, Reguler / Ireguler</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tekanan Darah (Sistolik / Diastolik) mmHg</label>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control" name="td_up">
                                        <div class="input-group-text"> / </div>
                                        <input type="text" class="form-control" name="td_down">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Suhu</label>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control" name="suhu">
                                        <div class="input-group-text">°C</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">SpO2</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" name="spo2">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <h6>Tingkat Kesadaran</h6>
                                <select class="form-control" name="tks">
                                    <option value="">Pilih</option>
                                    @if ($list['tingkat_kesadaran'])
                                        @foreach ($list['tingkat_kesadaran'] as $item)
                                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <h6>Pernafasan (Breathing)</h6>
                                <div class="form-group">
                                    <label class="form-label">Frekuensi Nafas</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="input-group input-group-sm flex-grow-1">
                                            <input type="text" class="form-control" name="fr">
                                            <span class="input-group-text">X/menit</span>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="1">
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
                            <div class="">
                                <h6>Neorologi ( D )</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <label class="form-label mb-0">Pupil</label>

                                                <div class="form-check m-0">
                                                    <input class="form-check-input single-checkbox" type="checkbox" name="pupil" value="1">
                                                    <label class="form-check-label">Isokor</label>
                                                </div>

                                                <div class="form-check m-0">
                                                    <input class="form-check-input single-checkbox" type="checkbox" name="pupil" value="2">
                                                    <label class="form-check-label">Anisokor</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="form-label">Diameter Pupil</label>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" class="form-control" name="dia_up">
                                                <div class="input-group-text"> mm / </div>
                                                <input type="text" class="form-control" name="dia_down">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="form-label">RC (Refleks Cahaya)</label>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" class="form-control" name="rc_up">
                                                <div class="input-group-text"> / </div>
                                                <input type="text" class="form-control" name="rc_down">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">VAS (Visual Analog Scale)</label>
                                            <input type="text" class="form-control" name="vas">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>GCS</h6>
                                        <div class="form-group mb-2">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <label class="form-check-label flex-shrink-0">Eye</label>
                                                <input type="text" class="form-control form-control-sm flex-grow-1" name="gcs_e" placeholder="">
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <label class="form-check-label flex-shrink-0">Verbal</label>
                                                <input type="text" class="form-control form-control-sm flex-grow-1" name="gcs_v" placeholder="">
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <label class="form-check-label flex-shrink-0">Move</label>
                                                <input type="text" class="form-control form-control-sm flex-grow-1" name="gcs_m" placeholder="">
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <label class="form-check-label flex-shrink-0">Total</label>
                                                <input type="text" class="form-control form-control-sm flex-grow-1" name="gcs_t" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Alat Bantu Nafas</h6>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline mb-2">
                                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="2">
                                            <label class="form-check-label"> Ya </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-2">
                                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="0">
                                            <label class="form-check-label"> Tidak </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Kulit</h6>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline mb-2">
                                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="1">
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
                        <hr class="mt-3">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h6 class="mb-0">Status Reproduksi</h6>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="sr" value="1" checked="">
                                    <label class="form-check-label" for="sr2">
                                        Tidak
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="sr" value="2">
                                    <label class="form-check-label" for="sr1">
                                        Kasus Obstetri Ginekologi
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="1">
                                            </div>
                                            <label class="form-label mb-0 me-2">
                                                HPHT
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="sr_hpht">
                                            </div>
                                            <label class="form-label mb-0 ms-2 me-2">
                                                Siklus
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="sr_siklus">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="2">
                                            </div>
                                            <label class="form-label mb-0 me-2" for="kb">
                                                KB
                                            </label>
                                            <input type="text" class="form-control" name="sr_kb">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="4">
                                            </div>
                                            <label class="form-label mb-0 me-2">
                                                Hamil
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Gravida</span>
                                                <input type="text" class="form-control" name="sr_grv">
                                                <span class="input-group-text">Paritas</span>
                                                <input type="text" class="form-control" name="sr_prt">
                                                <span class="input-group-text">Abortus</span>
                                                <input type="text" class="form-control" name="sr_abr">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="3">
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
                <div class="card card-body border border-dashed border-success mb-0">
                    <div class="row">
                        <h5>II. Pengkajian <b class="text-success">Secondary Survey</b></h5>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Keluhan Utama</h6>
                                <textarea class="form-control" name="ku" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Riwayat Penyakit Sekarang</h6>
                                <textarea class="form-control" name="rps" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Riwayat Penyakit Dahulu</h6>
                                <textarea class="form-control" name="rpd" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Pemeriksaan Fisik</h6>
                                <textarea class="form-control" name="pf" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h6 class="flex-shrink-0 mb-0">Riwayat Alergi</h6>
                                    <select class="form-select form-select-sm flex-grow-1" name="ra_cb">
                                        <option value="">Pilih Jenis Alergi</option>
                                        @if ($list['riwayat_alergi'])
                                            @foreach ($list['riwayat_alergi'] as $item)
                                                <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <textarea class="form-control" name="ra" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h6>Riwayat Penggunaan Obat</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="rpo_nama_obat" placeholder="Masukkan Nama Obat">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="rpo_dosis" placeholder="Masukkan Dosis">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="rpo_frekuensi">
                                        @foreach ($list['frekuensi_obat'] as $item)
                                            <option value="{{ $item->ID }}" {{ $item->FREKUENSI == '-' ? 'selected' : '' }}>
                                                {{ $item->FREKUENSI }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="rpo_rute">
                                        @foreach ($list['rute_obat'] as $item)
                                            <option value="{{ $item->ID }}" {{ $item->DESKRIPSI == '-' ? 'selected' : '' }}>
                                                {{ $item->DESKRIPSI }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="rpo_lama" placeholder="Tuliskan Lama Penggunaan Obat">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <div class="btn-group">
                                        <button class="btn btn-success" id="btnTambahObat" onclick="tambahPenggunaanObat()">
                                            <i class="ri-add-box-line me-1"></i> Tambah Obat
                                        </button>
                                        <button class="btn btn-subtle-warning" id="btnRefreshObat" onclick="getPenggunaanObat()">
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table align-middle mb-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Dosis</th>
                                            <th>Frekuensi (Keterangan)</th>
                                            <th>Rute Pemberian</th>
                                            <th>Lama Penggunaan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblObatBody">
                                        {{-- <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-info mb-0">
                    <div class="mb-3">
                        <h6>Pemeriksaan Penunjang Laboratorium</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-display">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pemeriksaan</th>
                                        <th>Parameter</th>
                                        <th>Hasil</th>
                                        <th>Satuan</th>
                                        <th>Nilai Normal</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>xxx</td>
                                        <td>aaa</td>
                                        <td>bbb</td>
                                        <td>ccc</td>
                                        <td>ddd</td>
                                        <td>eee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h6>Pemeriksaan Penunjang Radiologi</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-display mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pemeriksaan</th>
                                        <th>Parameter</th>
                                        <th>Hasil</th>
                                        <th>Satuan</th>
                                        <th>Nilai Normal</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>xxx</td>
                                        <td>aaa</td>
                                        <td>bbb</td>
                                        <td>ccc</td>
                                        <td>ddd</td>
                                        <td>eee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Diagnosa Kerja</h6>
                    <textarea class="form-control" name="dk" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Perencanaan Terapi</h6>
                    <textarea class="form-control" name="pt" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <h6>Hasil Lapor DPJP</h6>
                    <textarea class="form-control" name="hld" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <h6>Tindak Lanjut Asuhan</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Cara Keluar</label>
                            <select class="form-control" name="tla_ck">
                                <option value="">Pilih</option>
                                @if ($list['cara_keluar'])
                                    @foreach ($list['cara_keluar'] as $item)
                                        <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Keadaan Keluar</label>
                            <select class="form-control" name="tla_kk">
                                <option value="">Pilih</option>
                                @if ($list['keadaan_keluar'])
                                    @foreach ($list['keadaan_keluar'] as $item)
                                        <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">Diagnosa Akhir</label>
                            <input type="text" class="form-control" name="tla_da">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        {{-- <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button> --}}
        <button class="btn btn-danger" onclick="saveDataPengkajianGdD(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        getDataPengkajianGdD();
        getPenggunaanObat();
    })

    function getDataPengkajianGdD() {
        $kunjungan = $('#gd_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/form/pengkajian/gd/dr/${$kunjungan}`,
            type: 'GET',
            beforeSend: function () {

            },
            success: function (res) {
                // KRITERIA ATS
                $('input[name="ats"]').val(res.data.triage?.KRITERIA);

                // RISIKO PENULARAN INFEKSI
                $(`input[name="rpi"][value="${res.data.triage?.RISIKO_PENULARAN_INFEKSI}"]`).prop('checked', true);

                // ANAMNESIS
                let anam = null;
                if (res.data.anamnesis_diperoleh?.AUTOANAMNESIS == 1) { anam = 1; }
                else if (res.data.anamnesis_diperoleh?.ALLOANAMNESIS == 1) { anam = 2; }
                if (anam !== null) { $(`input[name="anam"][value="${anam}"]`).prop('checked', true); }
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

            }
        });
    }

    function saveDataPengkajianGdD(btn) {
        const $button = $(btn);
        const $section = $('#gd_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/gd/dr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan Formulir...');
            },

            success: function (response) {
                alert(response.message || 'Data berhasil disimpan.');
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
                $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    }

    // ADD ON ---------------------------------------------------------------------------------------------------------------------------------------------------
    function getPenggunaanObat() {
        const $button = $('#btnRefreshObat');
        const kunjungan = $('#gd_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblObatBody").html(`<tr><td colspan="7" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.OBAT}</td>
                            <td>${v.DOSIS}</td>
                            <td>${v.FREKUENSI_NAMA} ${v.FREKUENSI_KETERANGAN ? `(${v.FREKUENSI_KETERANGAN})` : ''}</td>
                            <td>${v.RUTE_NAMA}</td>
                            <td>${v.LAMA_PENGGUNAAN}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="hapusPenggunaanObat(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                    `;
                }
                $("#tblObatBody").html(html);
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
                $button.prop('disabled', false).html('<i class="ri-refresh-line"></i>');
            }
        });
    }

    function tambahPenggunaanObat() {
        const $button = $('#btnTambahObat');
        let nama_obat = $("input[name='rpo_nama_obat']").val();
        let dosis = $("input[name='rpo_dosis']").val();
        let frekuensi = $("select[name='rpo_frekuensi']").val();
        let rute = $("select[name='rpo_rute']").val();
        let lama = $("input[name='rpo_lama']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'nama_obat': nama_obat,
                'dosis': dosis,
                'frekuensi': frekuensi,
                'rute': rute,
                'lama': lama
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                getPenggunaanObat();
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line me-1"></i> Tambah Obat');
            }
        });
    }

    function hapusPenggunaanObat(id){
        const kunjungan = $('#gd_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}/hapus/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil dihapus.',
                    position: 'topRight'
                });
                getPenggunaanObat();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            }
        });
    }
</script>
