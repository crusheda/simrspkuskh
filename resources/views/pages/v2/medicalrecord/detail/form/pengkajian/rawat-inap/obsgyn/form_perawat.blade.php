<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Bidan</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-2">
                <h4 class="text-danger">Riwayat Pasien</h4>
                <div class="row mb-2">
                    <h6>Sumber Data</h6>
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
                    <div class="col-md-6 mb-1">
                        <input type="text" class="form-control" name="anamnesis_oleh" id="anamnesis_oleh" placeholder="Oleh.....">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h6>Rujukan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input class="form-check-input single-checkbox" type="checkbox" name="rujukan" value="0" id="rujukan_tidak">
                                <label class="form-check-label">
                                    Tidak
                                </label>
                            </div>
                            <div class="col-md-6 mb-2">
                                <input class="form-check-input single-checkbox" type="checkbox" name="rujukan" value="1" id="rujukan_ya">
                                <label class="form-check-label" for="rujukan_ya">
                                    Ya
                                </label>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <h6>Asal Rujukan</h6>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox" name="rujukan_rs" value="1"id="rujukan_rs">
                                                <label class="form-check-label">
                                                    RS
                                                </label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="rujukan_rs_ket">
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox" name="rujukan_dokter" value="1" id="rujukan_dokter">
                                                <label class="form-check-label">
                                                    Dokter
                                                </label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="rujukan_dokter_ket">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox" name="rujukan_puskesmas" value="1" id="rujukan_puskesmas">
                                                <label class="form-check-label">
                                                    Puskesmas
                                                </label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="rujukan_puskesmas_ket">
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox" name="rujukan_bidan" value="1" id="rujukan_bidan">
                                                <label class="form-check-label">
                                                    Bidan
                                                </label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="rujukan_bidan_ket">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6>Diagnosis Dirujuk</h6>
                                <input type="text" class="form-control form-control-sm" name="diagnosis_dirujuk_ket">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- CARA MASUK --}}
                        <h6 class="mb-2">
                            Cara masuk ke Rawat Inap
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="cara_masuk" value="1" id="cara_masuk_kursi">
                                <label class="form-check-label" for="cara_masuk_kursi">
                                    Kursi Roda
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm d-inline-block ms-3" name="cara_masuk_kursi_ket" style="max-width: 250px;">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input single-checkbox" type="checkbox" name="cara_masuk" value="2" id="cara_masuk_brankard">
                            <label class="form-check-label" for="cara_masuk_brankard">
                                Brankard
                            </label>
                        </div>
                        {{-- PERNAH DIRAWAT --}}
                        <h6 class="mb-2">
                            Pernah dirawat di RS PKU Muhammadiyah Sukoharjo
                        </h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pernah_dirawat" value="0" id="pernah_dirawat_tidak">
                            <label class="form-check-label" for="pernah_dirawat_tidak">
                                Tidak
                            </label>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pernah_dirawat" value="1" id="pernah_dirawat_ya">
                                <label class="form-check-label" for="pernah_dirawat_ya">
                                    Ya, Diagnosa :
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm mx-2" name="pernah_dirawat_diagnosa" style="max-width: 250px;">
                            <label class="form-label mb-0 me-2">
                                Kapan :
                            </label>
                            <input type="date" class="form-control form-control-sm" name="pernah_dirawat_kapan" style="max-width: 160px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <h4 class="text-danger">Anamnesis</h4>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h6>Keluhan Utama</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div>
                            <h6>Riwayat Menstruasi</h6>
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
                                <h6>Keluhan Menstruasi</h6>

                                <textarea class="form-control" name="menstruasi_keluhan" id="menstruasi_keluhan" rows="2"
                                    placeholder="Keluhan menstruasi..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card card-body border border-dashed border-primary">
                            <h6>Status Pernikahan</h6>
                            <div class="row g-2 mb-2">
                                {{-- Tahun --}}
                                <div class="col-md-2">
                                    <label class="form-label">Tahun</label>
                                    <input type="number" class="form-control form-control-sm" name="nikah_tahun" id="nikah_tahun" min="1" max="100" placeholder="Tahun">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Ket. Tempat</label>
                                    <input type="text" class="form-control form-control-sm" name="nikah_ket" id="nikah_ket" placeholder="Keterangan">
                                </div>
                                <div class="col-md-7 d-flex align-items-end">
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
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-sm align-middle mb-1">
                                    <thead>
                                        <tr class="table-success">
                                            <th>No</th>
                                            <th>Lama Menikah (Tahun)</th>
                                            <th>Keterangan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblNikahBody">
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
                    <div class="col-md-12">
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
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Dahulu</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Pada Kehamilan Sekarang</h6>
                            <textarea class="form-control" name="rps" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Keluarga</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Riwayat Gynekologi</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
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
                                <h6>Keluhan</h6>
                                <textarea class="form-control" name="kb_keluhan" id="kb_keluhan" rows="2"
                                    placeholder="Keluhan terkait penggunaan KB..."></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-6 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_alergi')
                    </div>
                    <div class="col-md-6 mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                    <div class="col-md-12 mb-3">
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
                </div>
            </div>
        </div>
    </div>
</div>
