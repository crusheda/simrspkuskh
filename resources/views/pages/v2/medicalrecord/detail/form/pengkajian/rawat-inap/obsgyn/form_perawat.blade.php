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
                                    Ya, Diagnosa
                                </label>
                            </div>
                            <input type="text" class="form-control form-control-sm mx-2" name="pernah_dirawat_diagnosa" style="max-width: 250px;">
                            <label class="form-label mb-0 me-2">
                                Kapan
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
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_suntik" id="kb_suntik" value="1" />
                                        <label class="form-check-label" for="kb_suntik"> Suntik </label>
                                    </div>
                                </div>

                                <!-- IUD -->
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_iud" id="kb_iud" value="1" />
                                        <label class="form-check-label" for="kb_iud"> IUD </label>
                                    </div>
                                </div>

                                <!-- Pil -->
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_pil" id="kb_pil" value="1" />
                                        <label class="form-check-label" for="kb_pil"> Pil </label>
                                    </div>
                                </div>

                                <!-- Kondom -->
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_kondom" id="kb_kondom" value="1" />
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
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_mow" id="kb_mow" value="1" />
                                        <label class="form-check-label" for="kb_mow"> MOW </label>
                                    </div>
                                </div>

                                <!-- MOP -->
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_mop" id="kb_mop" value="1" />
                                        <label class="form-check-label" for="kb_mop"> MOP </label>
                                    </div>
                                </div>

                                <!-- Implan -->
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_implan" id="kb_implan" value="1" />
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
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                            [
                                'section' => '#rio_perawat',
                                'page' => 'perawat',
                                // 'editableFields' => [
                                //     'tv_keu',
                                //     'tv_gcs_e',
                                //     'tv_gcs_v',
                                //     'tv_gcs_m',
                                //     'tv_bb',
                                //     'tv_tb',
                                // ],
                            ]
                        )
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Pemeriksaan Fisik</label>

                        <!-- MATA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Mata</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mata" value="1">
                                    <label class="form-check-label">
                                        Pandangan Kabur
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mata" value="2">
                                        <label class="form-check-label">
                                            Lainnya
                                        </label>
                                    </div>
                                    <input type="text" class="form-control" name="mata_keterangan" id="mata_keterangan" placeholder="Keterangan...">
                                </div>
                            </div>
                        </div>

                        <!-- SKLERA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Sklera</label>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="1">
                                    <label class="form-check-label">
                                        Ikterik
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="2">
                                    <label class="form-check-label">
                                        An Ikterik
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="3">
                                    <label class="form-check-label">
                                        Anemis
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="4">
                                    <label class="form-check-label">
                                        Tak Anemis
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- KEPALA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Kepala</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kepala" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kepala" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="kepala_keterangan" id="kepala_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- TELINGA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Telinga</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="telinga" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="telinga" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="telinga_keterangan" id="telinga_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- HIDUNG -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Hidung</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hidung" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hidung" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="hidung_keterangan" id="hidung_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- TENGGOROKAN -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Tenggorokan</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tenggorokan" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tenggorokan" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="tenggorokan_keterangan" id="tenggorokan_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- LEHER -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Leher</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leher" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leher" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="leher_keterangan" id="leher_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- DADA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Dada</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dada" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dada" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="dada_keterangan" id="dada_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- JANTUNG -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Jantung</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jantung" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jantung" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="jantung_keterangan" id="jantung_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- PARU-PARU -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Paru-paru</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="paru" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="paru" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="paru_keterangan" id="paru_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- ABDOMEN -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Abdomen</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abdomen" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abdomen" value="2">
                                    </div>
                                    <input type="text" class="form-control" name="abdomen_keterangan" id="abdomen_keterangan" placeholder="Keterangan kelainan...">
                                </div>
                            </div>
                        </div>

                        <!-- ANGGOTA GERAK ATAS -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Anggota gerak atas</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_atas" value="1">
                                    <label class="form-check-label">
                                        Oedema
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_atas" value="2">
                                    <label class="form-check-label">
                                        Tak Oedema
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- ANGGOTA GERAK BAWAH -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Anggota gerak bawah</label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_bawah" value="1">
                                    <label class="form-check-label">
                                        Oedema
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_bawah" value="2">
                                    <label class="form-check-label">
                                        Tak Oedema
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Pemeriksaan Khusus</label>
                        <!-- DADA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Dada</label>
                            </div>
                            <!-- Mammae -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mammae" value="1">
                                        <label class="form-check-label">
                                            Mammae simetris
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mammae" value="2">
                                        <label class="form-check-label">
                                            Asimetris
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Areola -->
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="areola_hiperpigmentasi" value="1">
                                    <label class="form-check-label">
                                        Areola hiperpigmentasi
                                    </label>
                                </div>
                            </div>

                            <!-- Puting -->
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="puting_susu" value="1">
                                        <label class="form-check-label">
                                            Puting susu menonjol
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="puting_susu" value="2">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOSTRUM -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2"></div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0">Kolostrum</label>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kolostrum" value="1">
                                        <label class="form-check-label">
                                            (+)
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kolostrum" value="2">
                                        <label class="form-check-label">
                                            (-)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ABDOMEN - INSPEKSI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Abdomen</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span class="fw-normal">Inspeksi :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="abdomen_luka_bekas_op" value="1">
                                        <label class="form-check-label">
                                            Luka bekas OP
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="abdomen_linea_alba" value="1">
                                        <label class="form-check-label">
                                            Linea Alba
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="abdomen_linea_nigra" value="1">
                                        <label class="form-check-label">
                                            Linea Nigra
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="abdomen_striae_livida" value="1">
                                        <label class="form-check-label">
                                            Striae Livida
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="abdomen_striae_albican" value="1">
                                        <label class="form-check-label">
                                            Striae Albican
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- ABDOMEN - PALPASI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Palpasi</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>Leopold I :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_1" value="1">
                                        <label class="form-check-label">
                                            TFU
                                        </label>
                                    </div>

                                    <input type="text" class="form-control" name="leopold_1_tfu" id="leopold_1_tfu" placeholder="TFU" style="width: 100px;">

                                    <span>cm</span>

                                </div>
                            </div>
                        </div>


                        <!-- LEOPOLD II -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2"></div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>Leopold II :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_2" value="1">
                                        <label class="form-check-label">
                                            Punggung Kanan
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_2" value="2">
                                        <label class="form-check-label">
                                            Punggung Kiri
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- LEOPOLD III -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2"></div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>Leopold III :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_3" value="1">
                                        <label class="form-check-label">
                                            Kepala
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_3" value="2">
                                        <label class="form-check-label">
                                            Bokong
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- LEOPOLD IV -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2"></div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>Leopold IV :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_4" value="1">
                                        <label class="form-check-label">
                                            Sudah masuk PAP
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_4" value="2">
                                        <label class="form-check-label">
                                            Belum masuk PAP
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- AUSKULTASI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Auskultasi</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>DJJ :</span>

                                    <input type="text" class="form-control" name="djj" id="djj" placeholder="DJJ" style="width: 100px;">

                                    <span>X/menit</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="djj_kondisi" value="1">
                                        <label class="form-check-label">
                                            Teratur
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="djj_kondisi" value="2">
                                        <label class="form-check-label">
                                            Tidak teratur
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- HIS/KONTRAKSI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">HIS/Kontraksi</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>.......... X/menit</span>

                                    <label class="form-label mb-0">
                                        Durasi
                                    </label>

                                    <input type="text" class="form-control" name="his_durasi" id="his_durasi" placeholder="Durasi" style="width: 100px;">

                                    <span>detik</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="1">
                                        <label class="form-check-label">
                                            Kuat
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="2">
                                        <label class="form-check-label">
                                            Sedang
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="3">
                                        <label class="form-check-label">
                                            Lemah
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- ANOGENITAL - INSPEKSI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Anogenital</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">

                                    <span>Inspeksi :</span>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="anogenital_pengeluaran" value="1">
                                        <label class="form-check-label">
                                            Pengeluaran per Vagina
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="anogenital_darah" value="1">
                                        <label class="form-check-label">
                                            Darah
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="anogenital_lendir" value="1">
                                        <label class="form-check-label">
                                            Lendir
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="anogenital_air_ketuban" value="1">
                                        <label class="form-check-label">
                                            Air Ketuban
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input check-primary"
                                                type="checkbox"
                                                name="anogenital_lainnya"
                                                value="1">
                                            <label class="form-check-label">
                                                Lainnya
                                            </label>
                                        </div>

                                        <input type="text"
                                            class="form-control" name="anogenital_lainnya_keterangan"
                                            id="anogenital_lainnya_keterangan"
                                            placeholder="Keterangan..."
                                            style="width: 200px;">
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- VAGINA TOUCHER -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Vagina Toucher</label>
                            </div>

                            <div class="col-md-10">
                                <input type="text"
                                    class="form-control"
                                    name="vagina_toucher"
                                    id="vagina_toucher"
                                    placeholder="Hasil pemeriksaan vagina toucher...">
                            </div>
                        </div>


                        <!-- LAIN-LAIN -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Lain-lain</label>
                            </div>

                            <div class="col-md-10">
                                <input type="text"
                                    class="form-control"
                                    name="pemeriksaan_lain_lain"
                                    id="pemeriksaan_lain_lain"
                                    placeholder="Lain-lain...">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
