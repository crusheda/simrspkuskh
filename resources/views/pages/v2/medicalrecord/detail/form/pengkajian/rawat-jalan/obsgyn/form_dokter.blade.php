<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL RAWAT JALAN OBSGYN</center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center mb-3" id="anamnesis_diperoleh">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Anamnesis</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1" checked="">
                                <label class="form-check-label">
                                    Autoanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2">
                                <label class="form-check-label">
                                    Alloanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="anamnesis_oleh" id="anamnesis_oleh" placeholder="Oleh.....">
                        </div>
                    </div>
                    <div class="row align-items-start" id="anamnesis">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Keluhan Utama</label>
                            <textarea class="form-control" name="keluhan_utama" id="keluhan_utama" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Sekarang</label>
                            <textarea class="form-control" name="rps" id="rps" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Dahulu</label>
                            <textarea class="form-control" name="rpd" id="rpd" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="card card-body border border-dashed border-primary">
                                <h6>Riwayat Obstetri</h6>
                                <div class="row g-2 mb-2">
                                    {{-- Tahun --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Tahun</label>
                                        <input type="number" class="form-control form-control-sm" name="obstetri_tahun" id="obstetri_tahun" min="1900" max="2100" placeholder="Tahun">
                                    </div>
                                    {{-- Usia Kehamilan --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Usia Kehamilan</label>
                                        <select class="form-select form-select-sm" name="obstetri_usia_kehamilan" id="obstetri_usia_kehamilan">
                                            <option value="">Pilih</option>
                                            @foreach ($list['usia_kehamilan'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Jenis Persalinan --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Persalinan</label>
                                        <select class="form-select form-select-sm" name="obstetri_jenis_persalinan" id="obstetri_jenis_persalinan">
                                            <option value="">Pilih</option>
                                            @foreach ($list['jenis_persalinan'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Penyulit --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Penyulit</label>
                                        <select class="form-select form-select-sm" name="obstetri_penyulit" id="obstetri_penyulit">
                                            <option value="">Pilih</option>
                                            @foreach ($list['penyulit'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Jenis Kelamin --}}
                                    <div class="col-md-2">
                                        <label class="form-label">JK</label>
                                        <select class="form-select form-select-sm" name="obstetri_jenis_kelamin" id="obstetri_jenis_kelamin">
                                            <option value="">Pilih</option>
                                            @foreach ($list['jenis_kelamin'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Berat Badan --}}
                                    <div class="col-md-2">
                                        <label class="form-label">BB (gram)</label>
                                        <input type="number" class="form-control form-control-sm" name="obstetri_berat_badan" id="obstetri_berat_badan" placeholder="BB">
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    {{-- Penolong --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Penolong</label>
                                        <select class="form-select form-select-sm" name="obstetri_penolong" id="obstetri_penolong">
                                            <option value="">Pilih</option>
                                            @foreach ($list['penolong'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Keterangan Penolong --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Ket. Penolong</label>
                                        <input type="text" class="form-control form-control-sm" name="obstetri_keterangan_penolong" id="obstetri_keterangan_penolong" placeholder="Keterangan">
                                    </div>
                                    {{-- Tempat --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Tempat</label>
                                        <select class="form-select form-select-sm" name="obstetri_tempat" id="obstetri_tempat">
                                            <option value="">Pilih</option>
                                            @foreach ($list['tempat'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Keterangan Tempat --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Ket. Tempat</label>
                                        <input type="text" class="form-control form-control-sm" name="obstetri_keterangan_tempat" id="obstetri_keterangan_tempat" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    {{-- Keadaan Saat Ini --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Keadaan Saat Ini</label>
                                        <select class="form-select form-select-sm" name="obstetri_keadaan_saat_ini" id="obstetri_keadaan_saat_ini">
                                            <option value="">Pilih</option>
                                            @foreach ($list['keadaan_sat_ini'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Tombol --}}
                                    <div class="col-md-8 d-flex align-items-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm" id="btnTambahObstetri" onclick="tambahRiwayatObstetri()">
                                                <i class="ri-add-box-line"></i>
                                                Tambah
                                            </button>
                                            <button type="button" class="btn btn-subtle-warning btn-sm" id="btnRefreshObstetri" onclick="getRiwayatObstetri()">
                                                <i class="ri-refresh-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tabel --}}
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered table-sm align-middle mb-1">
                                        <thead>
                                            <tr class="table-success">
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Usia Kehamilan</th>
                                                <th>Persalinan</th>
                                                <th>Penyulit</th>
                                                <th>Penolong</th>
                                                <th>Tempat</th>
                                                <th>JK</th>
                                                <th>BB</th>
                                                <th>Keadaan Saat Ini</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblObstetriBody">
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    Tidak ada data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ====================================================== -->
                        <!-- RIWAYAT KB DAN MENSTRUASI -->
                        <!-- ====================================================== -->
                        <div class="col-md-12 mb-1">
                            <div class="card card-body border border-dashed border-primary">
                                <label class="form-label fw-bold"> Riwayat KB dan Menstruasi </label>
                                <!-- ========================== -->
                                <!-- RIWAYAT KB -->
                                <!-- ========================== -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"> Riwayat KB </label>

                                    <div class="row">
                                        <!-- Suntik -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_suntik" id="kb_suntik"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_suntik"> Suntik </label>
                                            </div>
                                        </div>

                                        <!-- IUD -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_iud" id="kb_iud"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_iud"> IUD </label>
                                            </div>
                                        </div>

                                        <!-- Pil -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_pil" id="kb_pil"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_pil"> Pil </label>
                                            </div>
                                        </div>

                                        <!-- Kondom -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_kondom" id="kb_kondom"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_kondom"> Kondom </label>
                                            </div>
                                        </div>

                                        <!-- Kalender -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_kalender"
                                                    id="kb_kalender" value="1" />
                                                <label class="form-check-label" for="kb_kalender"> Kalender </label>
                                            </div>
                                        </div>

                                        <!-- MOW -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_mow" id="kb_mow"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_mow"> MOW </label>
                                            </div>
                                        </div>

                                        <!-- MOP -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_mop" id="kb_mop"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_mop"> MOP </label>
                                            </div>
                                        </div>

                                        <!-- Implan -->
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" name="kb_implan" id="kb_implan"
                                                    value="1" />
                                                <label class="form-check-label" for="kb_implan"> Implan </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keluhan KB -->
                                    <div class="mt-2">
                                        <label class="form-label"> Keluhan </label>
                                        <textarea class="form-control" name="kb_keluhan" id="kb_keluhan" rows="2"
                                            placeholder="Keluhan terkait penggunaan KB..."></textarea>
                                    </div>
                                </div>

                                <hr class="my-3" />

                                <!-- ========================== -->
                                <!-- RIWAYAT MENSTRUASI -->
                                <!-- ========================== -->
                                <div>
                                    <label class="form-label fw-semibold"> Riwayat Menstruasi </label>

                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0"> Menarche / Teratur </label>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="menstruasi_teratur"
                                                    id="menstruasi_teratur_ya" value="1" />
                                                <label class="form-check-label" for="menstruasi_teratur_ya">
                                                    Ya
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="menstruasi_teratur"
                                                    id="menstruasi_teratur_tidak" value="0" />
                                                <label class="form-check-label" for="menstruasi_teratur_tidak">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keluhan menstruasi -->
                                    <div class="mt-2">
                                        <label class="form-label"> Keluhan </label>

                                        <textarea class="form-control" name="menstruasi_keluhan" id="menstruasi_keluhan" rows="2"
                                            placeholder="Keluhan menstruasi..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 d-flex flex-column">
                            <h6>Riwayat Alergi</h6>
                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-3">
                                    <select class="form-select form-select" name="ra_jenis">
                                        <option value="" hidden>Pilih Jenis Alergi</option>
                                        @if ($list['jenis_alergi'])
                                            @foreach ($list['jenis_alergi'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <textarea class="form-control form-control" name="ra_deskripsi" placeholder="Masukkan Alergi" rows="1"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-info btn-sm" id="btnTambahAlergi" onclick="tambahRiwayatAlergi()" >
                                            <i class="ri-add-box-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-subtle-warning btn-sm" id="btnRefreshAlergi" onclick="getRiwayatAlergi()" >
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table align-middle mb-1">
                                    <colgroup>
                                        <col style="width: 1%;">
                                        <col style="width: 20%;">
                                        <col>
                                        <col style="width: 1%;">
                                    </colgroup>
                                    <thead>
                                        <tr class="table-success">
                                            <th>No</th>
                                            <th>Jenis</th>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblAlergiBody">
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 d-flex flex-column">
                            <h6>Riwayat Penggunaan Obat</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="rpo_nama_obat" id="rpo_nama_obat" placeholder="Masukkan Nama Obat">
                                </div>
                                {{-- <div class="col-md-6">
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
                                </div> --}}
                                <div class="col-md-3 d-grid">
                                    <div class="btn-group">
                                        <button class="btn btn-success" id="btnTambahObat" onclick="tambahPenggunaanObat()">
                                            <i class="ri-add-box-line"></i>
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
                                        <tr class="table-info">
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            {{-- <th>Dosis</th>
                                            <th>Frekuensi (Keterangan)</th>
                                            <th>Rute Pemberian</th>
                                            <th>Lama Penggunaan</th> --}}
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblObatBody">
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_fisik">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Fisik</label>
                            <textarea class="form-control" name="pfisik" id="pfisik" rows="16">Palpasi Leopold :
Leopold I   :
Leopold II  :
Leopold III :
Leopold IV  :

AUSKULTASI
DJJ         :    x/m Regular / Irregular

PEMERIKSAAN LAIN : Panggul / Osborn

EXTREMITAS :

REFLEK PATELA : (   /   )
Uodema        : (   /   )

*Keterangan: hapus pilihan yang tidak sesuai.</textarea>
                        </div>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_penunjang">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Penunjang</label>
                            <div class="card card-body border border-dashed border-info mb-0">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0">Pemeriksaan Penunjang Laboratorium</h6>

                                        <button class="btn btn-subtle-warning btn-sm" id="btnRefreshLab" onclick="getRiwayatLab()">
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
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
                                            <tbody id="tblLabBody">
                                                <tr>
                                                    <td colspan="7" class="text-center">Belum ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0">Pemeriksaan Penunjang Radiologi</h6>

                                        <button class="btn btn-subtle-warning btn-sm" id="btnRefreshRad" onclick="getRiwayatRad()">
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-display">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Tindakan</th>
                                                    <th>Klinis</th>
                                                    <th>Kesan</th>
                                                    <th>Usul</th>
                                                    <th>Hasil</th>
                                                    <th>BTK</th>
                                                    <th>Dokter</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblRadBody">
                                                <tr>
                                                    <td colspan="8" class="text-center">Belum ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-md-12 mb-3">
                            <div class="card card-body border border-dashed border-danger mb-0">
                                <div class="form-group">
                                    <h6>Diagnosis</h6>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="flex-grow-1">
                                            <textarea
                                                class="form-control form-control-sm"
                                                name="diag_detail"
                                                placeholder="Masukkan Diagnosa"
                                                rows="1"
                                            ></textarea>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check mb-0">
                                                <input
                                                    class="form-check-input check-primary"
                                                    type="checkbox"
                                                    name="diag_utama"
                                                    value="1"
                                                    id="diag_utama"
                                                >
                                                <label class="form-check-label" for="diag_utama">
                                                    Diagnosis Utama
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="btn-group">
                                                <button
                                                    type="button"
                                                    class="btn btn-success"
                                                    id="btnTambahDiagnosis"
                                                    onclick="tambahDiagnosis()"
                                                >
                                                    <i class="ri-add-box-line"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-subtle-warning"
                                                    id="btnRefreshDiagnosis"
                                                    onclick="getDiagnosis()"
                                                >
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table align-middle mb-1">
                                            <thead>
                                                <colgroup>
                                                    <col style="width: 1%;">
                                                    <col>
                                                    <col style="width: 5%;">
                                                    <col style="width: 1%;">
                                                </colgroup>
                                                <tr class="table_info">
                                                    <th>No</th>
                                                    <th>Diagnosis</th>
                                                    <th>Utama</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblDiagnosisBody">
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="col-md-12" id="displayttv"></div>
                            <label class="form-label fw-bold">Terapi / Tindakan</label>
                            <textarea class="form-control" name="terapi_tind" id="terapi_tind" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-success mb-1">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Tindak Lanjut :</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="1">
                                <label class="form-check-label">
                                    MRS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="2">
                                <label class="form-check-label">
                                    Pulang
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body card-header border border-dashed border-primary" id="pri">
                        <div class="card-header fw-bold">
                            Perencanaan Rawat Inap
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Ruang Perawatan</label>
                                        <select class="form-control" name="pri_ruang" id="pri_ruang">
                                            <option value="">Pilih Jenis Ruang Perawatan</option>
                                            @foreach ($list['jenis_ruang'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Perawatan</label>
                                        <select class="form-control" name="pri_perawatan" id="pri_perawatan">
                                            <option value="">Pilih Jenis Perawatan</option>
                                            @foreach ($list['jenis_perawatan'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <div class="input-group">
                                        <input type="text" name="pri_tgl" id="pri_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Indikasi</label>
                                    <textarea class="form-control" name="pri_indikasi" id="pri_indikasi" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <textarea class="form-control" name="pri_ket" id="pri_ket" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">DPJP</label>
                                    <input type="text" class="form-control" value="{{ $list['pasien']->NAMADOKTER ?? '' }}" placeholder="Nama DPJP" readonly>
                                    <input type="hidden" name="pri_dpjp" value="{{ $list['pasien']->ID ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Dirujuk Ke</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_gizi" value="1">
                                        <label class="form-check-label">
                                            Ahli Gizi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_rehab" value="2">
                                        <label class="form-check-label">
                                            Rehabilitasi Medik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_sp" value="3">
                                        <label class="form-check-label">
                                            Klinik Spesialis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_lain" value="4">
                                        <label class="form-check-label">
                                            Lainnya
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="rujuk_lainnya" id="rujuk_lainnya" placeholder="Sebutkan....">
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
        <button class="btn btn-danger" onclick="saveDataPengkajianRJOd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>

    $(document).ready(function () {

        const $inputObat = $('[name="rpo_nama_obat"]');

        if (!$inputObat.length ) {
            return;
        }

        // Sembunyikan textarea saat pertama kali
        $('#pri').hide();
        $('#rujuk_lainnya').hide();

        // Perencanaan Rawat Inap
        $('input[name="tl"]').change(function () {
            if ($('#tl_mrs').is(':checked')) {
                $('#pri').slideDown();
            } else {
                $('#pri').slideUp();
            }
        });

        // Dirujuk Ke
        $('input[name="rujuk"]').change(function () {
            if ($('#rujuk_lain').is(':checked')) {
                $('#rujuk_lainnya').slideDown();
            } else {
                $('#rujuk_lainnya').slideUp().val('');
            }
        });

        // FLATPICKR DATE
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        $("#pri_tgl").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                mode: 'single',
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: [today]
            }
        );

        //Auto Nama Obat
        new autoComplete({
            selector: '[name="rpo_nama_obat"]',
            placeHolder: 'Masukkan Nama Obat',
            threshold: 2,
            debounce: 300,

            data: {
                src: async function (query) {
                    try {
                        return await $.ajax({
                            url: "/api/v2/emr/pengkajian/riwayat_pemberian_obat/obat",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                q: query
                            }
                        });
                    } catch (error) {
                        console.error('Gagal mengambil data obat:', error);
                        return [];
                    }
                },

                keys: ['nama'],
                cache: false
            },

            resultsList: {
                maxResults: 15,
                noResults: true
            },

            resultItem: {
                highlight: true,

                element: function (item, data) {
                    const obat = data.value;

                    // .text() lebih aman daripada memasukkan data database ke HTML langsung
                    $(item)
                        .empty()
                        .append(
                            $('<strong>').text(obat.nama),
                            $('<small>')
                                .addClass('d-block text-muted')
                                .text(`Kategori: ${obat.kategori ?? '-'}  |  Satuan: ${obat.ket_satuan ?? '-'}${obat.satuan ? ' ('+obat.satuan+')' : '-'}`)
                        );
                }
            },

            events: {
                input: {
                    selection: function (event) {
                        const obat = event.detail.selection.value;

                        $inputObat.val(obat.nama);
                        // $inputBarangId.val(obat.id);
                    }
                }
            }
        });
        loadDataPengkajianRJOd();
        getPenggunaanObat();
        getRiwayatAlergi();
        getRiwayatLab();
        getRiwayatRad();
        getDiagnosis();
        getRiwayatObstetri();
    });

    function formatAngkaBulat(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        return Number(value).toLocaleString('id-ID', {
            maximumFractionDigits: 0
        });
    }

    function formatSuhu(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        return Number(value).toLocaleString('id-ID', {
            maximumFractionDigits: 2
        });
    }

    function displayTTV(data) {

        let html = `
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>
                        <i class="ri-heart-pulse-line me-1"></i>
                        Tanda-Tanda Vital
                    </strong>
                </div>

                <div class="card-body">
                    <div class="row">

                        <!-- Keadaan Umum -->
                        <div class="col-md-4 mb-3">
                            <div class="small text-muted">Keadaan Umum</div>
                            <div class="fw-bold">
                                ${data.ku || '-'}
                            </div>
                        </div>

                        <!-- Kesadaran -->
                        <div class="col-md-4 mb-3">
                            <div class="small text-muted">Tingkat Kesadaran</div>
                            <div class="fw-bold">
                                ${getKesadaranText(data.kesadaran)}
                            </div>
                        </div>

                        <!-- GCS -->
                        <div class="col-md-4 mb-3">
                            <div class="small text-muted">GCS</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.gcs)}
                            </div>
                        </div>

                        <!-- E -->
                        <div class="col-md-2 mb-3">
                            <div class="small text-muted">Eye (E)</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.eye)}
                            </div>
                        </div>

                        <!-- M -->
                        <div class="col-md-2 mb-3">
                            <div class="small text-muted">Motorik (M)</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.motorik)}
                            </div>
                        </div>

                        <!-- V -->
                        <div class="col-md-2 mb-3">
                            <div class="small text-muted">Verbal (V)</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.verbal)}
                            </div>
                        </div>

                        <!-- Tekanan Darah -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">Tekanan Darah</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.td_up)}
                                /
                                ${formatAngkaBulat(data.td_down)}
                                mmHg
                            </div>
                        </div>

                        <!-- SpO2 -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">SpO2</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.spo2)} %
                            </div>
                        </div>

                        <!-- Nafas -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">Frekuensi Nafas</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.nafas)} X/menit
                            </div>
                        </div>

                        <!-- Nadi -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">Nadi</div>
                            <div class="fw-bold">
                                ${formatAngkaBulat(data.nadi)} X/menit
                            </div>
                        </div>

                        <!-- Suhu -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">Suhu</div>
                            <div class="fw-bold">
                                ${formatSuhu(data.suhu)} °C
                            </div>
                        </div>

                        <!-- Alat Bantu Nafas -->
                        <div class="col-md-3 mb-3">
                            <div class="small text-muted">Alat Bantu Nafas</div>
                            <div class="fw-bold">
                                ${getABNText(data.abn)}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        `;

        $('#displayttv').html(html);
    }

    function getABNText(value) {
        if (value == 1) {
            return 'Ya';
        }
        if (value == 2) {
            return 'Tidak';
        }
        return '-';
    }

    function getKesadaranText(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }
        return $('#kesadaran option[value="' + value + '"]').text() || '-';
    }

    function loadDataPengkajianRJOd() {
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjo/dr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJOd(res);
                // Tampilkan TTV
                displayTTV(res);
            }
        });
    }

    function isiFormPengkajianRJOd(data){

        $("input[name=anam][value='"+data.anam+"']")
            .prop("checked",true)
            .trigger("change");

        $("#anamnesis_oleh").val(data.anamnesis_oleh);

        $("#keluhan_utama").val(data.keluhan_utama);

        $("#rps").val(data.rps);

        $("#rpd").val(data.rpd);

        if (data.pfisik) {
            $("#pfisik").val(data.pfisik);
        }

        $("#terapi_tind").val(data.terapi_tind);

        $('input[name="tl"][value="' + data.tl + '"]').prop('checked', true).trigger('change');
        $('input[name="rujuk"][value="' + data.rujuk + '"]').prop('checked', true).trigger('change');
        $('#rujuk_lainnya').val(data.rujuk_lainnya);

        $('#pri_ruang').val(data.pri_ruang);
        $('#pri_perawatan').val(data.pri_perawatan);
        $('#pri_indikasi').val(data.pri_indikasi);
        $('#pri_ket').val(data.pri_ket);
        $('#pri_dpjp').val(data.pri_dpjp);

        $('#kb_suntik').prop('checked', data.kb_suntik == 1);
        $('#kb_iud').prop('checked', data.kb_iud == 1);
        $('#kb_pil').prop('checked', data.kb_pil == 1);
        $('#kb_kondom').prop('checked', data.kb_kondom == 1);
        $('#kb_kalender').prop('checked', data.kb_kalender == 1);
        $('#kb_mow').prop('checked', data.kb_mow == 1);
        $('#kb_mop').prop('checked', data.kb_mop == 1);
        $('#kb_implan').prop('checked', data.kb_implan == 1);

        $('#kb_keluhan').val(data.kb_keluhan || '');

        $('input[name="menstruasi_teratur"]').prop('checked', false);

        if (data.menstruasi_teratur !== null && data.menstruasi_teratur !== '') {
            $(`input[name="menstruasi_teratur"][value="${data.menstruasi_teratur}"]`)
                .prop('checked', true);
        }

        $('#menstruasi_keluhan').val(data.menstruasi_keluhan || '');

    }

    function saveDataPengkajianRJOd(btn) {
        const $button = $(btn);
        const $section = $('#rjo_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjo/dr/simpan',
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

    // ADD ON ---------------------------------------------------------------------------------------------------------------------------------------------------
    function getRiwayatObstetri() {
        const $button = $('#btnRefreshObstetri');
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button
                    .prop('disabled', true)
                    .html('<i class="ri-refresh-line ri-spin"></i>');
                $('#tblObstetriBody').html(`
                    <tr>
                        <td colspan="11" class="text-center">
                            <i class="ri-refresh-line ri-spin me-1"></i>
                            Memproses data...
                        </td>
                    </tr>
                `);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${v.TAHUN ?? '-'}</td>
                                <td>${v.USIA_KEHAMILAN_DESC ?? '-'}</td>
                                <td>${v.JENIS_PERSALINAN_DESC ?? '-'}</td>
                                <td>${v.PENYULIT_DESC ?? '-'}</td>
                                <td>
                                    ${v.PENOLONG_DESC ?? '-'}
                                    ${v.KETERANGAN_PENOLONG
                                        ? '<br><small>' + v.KETERANGAN_PENOLONG + '</small>'
                                        : ''}
                                </td>
                                <td>
                                    ${v.TEMPAT_DESC ?? '-'}
                                    ${v.KETERANGAN_TEMPAT
                                        ? '<br><small>' + v.KETERANGAN_TEMPAT + '</small>'
                                        : ''}
                                </td>
                                <td>${v.JENIS_KELAMIN_DESC ?? '-'}</td>
                                <td>${v.BERAT_BADAN ?? '-'} gr</td>
                                <td>${v.KEADAAN_SAAT_INI_DESC ?? '-'}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRiwayatObstetri(${v.ID})"> <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="11" class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                    `;
                }
                $('#tblObstetriBody').html(html);
            },
            error: function (xhr) {
                let message = 'Data gagal dimuat.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            },
            complete: function () {
                $button
                    .prop('disabled', false)
                    .html('<i class="ri-refresh-line"></i>');
            }
        });
    }

    function tambahRiwayatObstetri() {
        const $button = $('#btnTambahObstetri');
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        const data = {
            tahun: $('#obstetri_tahun').val(),
            usia_kehamilan:
                $('#obstetri_usia_kehamilan').val(),
            jenis_persalinan:
                $('#obstetri_jenis_persalinan').val(),
            penyulit:
                $('#obstetri_penyulit').val(),
            penolong:
                $('#obstetri_penolong').val(),
            keterangan_penolong:
                $('#obstetri_keterangan_penolong').val(),
            tempat:
                $('#obstetri_tempat').val(),
            keterangan_tempat:
                $('#obstetri_keterangan_tempat').val(),
            jenis_kelamin:
                $('#obstetri_jenis_kelamin').val(),
            berat_badan:
                $('#obstetri_berat_badan').val(),
            keadaan_saat_ini:
                $('#obstetri_keadaan_saat_ini').val()
        };
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $button
                    .prop('disabled', true)
                    .html('<i class="ri-refresh-line ri-spin"></i>');
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message:
                        res.message ||
                        'Data riwayat obstetri berhasil disimpan.',
                    position: 'topRight'
                });
                // Reset input
                $('#obstetri_tahun').val('');
                $('#obstetri_usia_kehamilan').val('');
                $('#obstetri_jenis_persalinan').val('');
                $('#obstetri_penyulit').val('');
                $('#obstetri_penolong').val('');
                $('#obstetri_keterangan_penolong').val('');
                $('#obstetri_tempat').val('');
                $('#obstetri_keterangan_tempat').val('');
                $('#obstetri_jenis_kelamin').val('');
                $('#obstetri_berat_badan').val('');
                $('#obstetri_keadaan_saat_ini').val('');
                // Refresh tabel
                getRiwayatObstetri();
            },

            error: function (xhr) {
                let message = 'Data gagal disimpan.';
                if (xhr.status === 422 &&
                    xhr.responseJSON?.errors) {
                    message = Object.values(
                        xhr.responseJSON.errors
                    )
                    .flat()
                    .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            },
            complete: function () {
                $button
                    .prop('disabled', false)
                    .html(`
                        <i class="ri-add-box-line"></i>
                        Tambah
                    `);
            }
        });
    }
    function hapusRiwayatObstetri(id) {
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}/hapus/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message:
                        res.message ||
                        'Data riwayat obstetri berhasil dihapus.',
                    position: 'topRight'
                });
                getRiwayatObstetri();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    }

    function getRiwayatAlergi() {
        const $button = $('#btnRefreshAlergi');
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblAlergiBody").html(`<tr><td colspan="4" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.JENIS_ALERGI}</td>
                            <td>${v.DESKRIPSI}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="hapusRiwayatAlergi(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    `;
                }
                $("#tblAlergiBody").html(html);
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
    };

    function tambahRiwayatAlergi() {
        const $button = $('#btnTambahAlergi');
        let jenis = $("[name='ra_jenis']").val();
        let deskripsi = $("[name='ra_deskripsi']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'jenis': jenis,
                'deskripsi': deskripsi
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                $("[name='ra_jenis']").val('');
                $("[name='ra_deskripsi']").val('');
                getRiwayatAlergi();
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusRiwayatAlergi(id){
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/hapus/${id}`,
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
                getRiwayatAlergi();
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
    };

    function getPenggunaanObat() {
        const $button = $('#btnRefreshObat');
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblObatBody").html(`<tr><td colspan="3" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.OBAT}</td>
                            {{-- <td>${v.DOSIS}</td>
                            <td>${v.FREKUENSI_NAMA} ${v.FREKUENSI_KETERANGAN ? `(${v.FREKUENSI_KETERANGAN})` : ''}</td>
                            <td>${v.RUTE_NAMA}</td>
                            <td>${v.LAMA_PENGGUNAAN}</td> --}}
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
                        <td colspan="3" class="text-center">Tidak ada data</td>
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
    };

    function tambahPenggunaanObat() {
        const $button = $('#btnTambahObat');
        let nama_obat = $("input[name='rpo_nama_obat']").val();
        // let dosis = $("input[name='rpo_dosis']").val();
        // let frekuensi = $("select[name='rpo_frekuensi']").val();
        // let rute = $("select[name='rpo_rute']").val();
        // let lama = $("input[name='rpo_lama']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'nama_obat': nama_obat
                // 'dosis': dosis,
                // 'frekuensi': frekuensi,
                // 'rute': rute,
                // 'lama': lama
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                $("[name='rpo_nama_obat']").val('');
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusPenggunaanObat(id){
        const kunjungan = $('#rjo_dokter').data('kunjungan');
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
    };

    function getRiwayatLab() {
        const $button = $('#btnRefreshLab');
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/lab/${kunjungan}`,
            type: 'GET',

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblLabBody").html(`
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="ri-refresh-line ri-spin me-1"></i>
                            Memuat data...
                        </td>
                    </tr>
                `);
            },

            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${v.NAMATINDAKAN ?? ''}</td>
                                <td>${v.PARAMETER ?? ''}</td>
                                <td>${v.HASIL ?? ''}</td>
                                <td>${v.SATUAN ?? ''}</td>
                                <td>${v.NILAI_RUJUKAN ?? ''}</td>
                                <td>${v.KETERANGAN ?? ''}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center">
                                Tidak ada hasil laboratorium
                            </td>
                        </tr>
                    `;
                }
                $("#tblLabBody").html(html);
            },

            error: function (xhr) {
                $("#tblLabBody").html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Gagal memuat data.
                        </td>
                    </tr>
                `);
                console.error(xhr);
            },

            complete: function () {
                $button.prop('disabled', false)
                    .html('<i class="ri-refresh-line"></i>');
            }

        });
    };

    function getRiwayatRad() {
        const $button = $('#btnRefreshRad');
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/rad/${kunjungan}`,
            type: 'GET',

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblRadBody").html(`
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="ri-refresh-line ri-spin me-1"></i>
                            Memuat data...
                        </td>
                    </tr>
                `);
            },

            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${v.NAMATINDAKAN ?? ''}</td>
                                <td>${v.KLINIS ?? ''}</td>
                                <td>${v.KESAN ?? ''}</td>
                                <td>${v.USUL ?? ''}</td>
                                <td>${v.HASIL ?? ''}</td>
                                <td>${v.BTK ?? ''}</td>
                                <td>${v.DOKTER ?? ''}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center">
                                Tidak ada hasil laboratorium
                            </td>
                        </tr>
                    `;
                }
                $("#tblRadBody").html(html);
            },

            error: function (xhr) {
                $("#tblRadBody").html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Gagal memuat data.
                        </td>
                    </tr>
                `);
                console.error(xhr);
            },

            complete: function () {
                $button.prop('disabled', false)
                    .html('<i class="ri-refresh-line"></i>');
            }

        });
    };

    function getDiagnosis() {
        const $button = $('#btnRefreshDiagnosis');
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblDiagnosisBody").html(`<tr><td colspan="4" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.DIAGNOSA}</td>
                            <td>${v.UTAMA}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="hapusDiagnosis(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    `;
                }
                $("#tblDiagnosisBody").html(html);
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
    };

    function tambahDiagnosis() {
        const $button = $('#btnTambahDiagnosis');
        let utama = $("[name='diag_utama']").prop("checked") ? 1 : 0;
        let diagnosa = $("[name='diag_detail']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'utama': utama,
                'diagnosa': diagnosa
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                $("[name='diag_detail']").val('');
                $("[name='diag_utama']").prop('checked', false);

                getDiagnosis();
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusDiagnosis(id){
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/hapus/${id}`,
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
                getDiagnosis();
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
    };
</script>
