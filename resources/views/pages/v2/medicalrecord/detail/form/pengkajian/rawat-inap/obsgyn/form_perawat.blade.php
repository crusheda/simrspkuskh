<div class="form-wrapper" data-rio-form>
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="text-danger">RAWAT INAP</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Bidan</a>)</center></h1>
    <div class="form-content">
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
                            <h6>Riwayat KB</h6>

                            <div class="row">
                                <!-- Suntik -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_suntik" id="kb_suntik" value="1" />
                                        <label class="form-check-label" for="kb_suntik"> Suntik </label>
                                    </div>
                                </div>

                                <!-- IUD -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_iud" id="kb_iud" value="1" />
                                        <label class="form-check-label" for="kb_iud"> IUD </label>
                                    </div>
                                </div>

                                <!-- Pil -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_pil" id="kb_pil" value="1" />
                                        <label class="form-check-label" for="kb_pil"> Pil </label>
                                    </div>
                                </div>

                                <!-- Kondom -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_kondom" id="kb_kondom" value="1" />
                                        <label class="form-check-label" for="kb_kondom"> Kondom </label>
                                    </div>
                                </div>

                                <!-- Kalender -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_kalender"
                                            id="kb_kalender" value="1" />
                                        <label class="form-check-label" for="kb_kalender"> Kalender </label>
                                    </div>
                                </div>

                                <!-- MOW -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_mow" id="kb_mow" value="1" />
                                        <label class="form-check-label" for="kb_mow"> MOW </label>
                                    </div>
                                </div>

                                <!-- MOP -->
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" name="kb_mop" id="kb_mop" value="1" />
                                        <label class="form-check-label" for="kb_mop"> MOP </label>
                                    </div>
                                </div>

                                <!-- Implan -->
                                <div class="col mb-2">
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
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_menstruasi_kb')
                    </div>
                    <div class="col-md-12 mb-1">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_pernikahan')
                    </div>
                    <div class="col-md-12 mb-1">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
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
                    <div class="col-md-12 mb-3">
                        <h4 class="text-danger">Pemeriksaan Fisik</h4>

                        <!-- MATA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Mata</label>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mata" value="1">
                                    <label class="form-check-label">
                                        Pandangan Kabur
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="mata" value="2">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="1">
                                    <label class="form-check-label">
                                        Ikterik
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="2">
                                    <label class="form-check-label">
                                        An Ikterik
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="sklera" value="3">
                                    <label class="form-check-label">
                                        Anemis
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kepala" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="telinga" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="hidung" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="tenggorokan" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leher" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dada" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jantung" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="paru" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abdomen" value="1">
                                    <label class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_atas" value="1">
                                    <label class="form-check-label">
                                        Oedema
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anggota_gerak_bawah" value="1">
                                    <label class="form-check-label">
                                        Oedema
                                    </label>
                                </div>
                            </div>

                            <div class="col">
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
                        <h4 class="text-danger">Pemeriksaan Khusus</h4>
                        <!-- DADA -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Dada</label>
                            </div>
                            <!-- Mammae -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dada" value="1">
                                        <label class="form-check-label">
                                            Mammae simetris / Asimetris
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Areola -->
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary" type="checkbox" name="dada" value="2">
                                    <label class="form-check-label">
                                        Areola hiperpigmentasi
                                    </label>
                                </div>
                            </div>

                            <!-- Puting -->
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary checkbox" type="checkbox" name="dada" value="3">
                                        <label class="form-check-label">
                                            Puting susu menonjol / Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOSTRUM -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2"></div>

                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2">

                                    <div class="form-check">
                                        <input class="form-check-input check-primary checkbox" type="checkbox" name="dada" value="4">
                                        <label class="form-check-label">
                                            Kolostrum (+) / (-)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input check-primary" type="checkbox" name="dada" value="5">
                                    </div>
                                    <input type="text" class="form-control" name="kolostrum_keterangan" id="kolostrum_keterangan" placeholder="Lainnya">
                                </div>
                            </div>
                        </div>


                        <!-- ABDOMEN - INSPEKSI -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Abdomen Inspeksi</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">
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
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center flex-wrap gap-3">
                                    <span>Leopold I :</span>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            TFU
                                        </label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="leopold_1_tfu" id="leopold_1_tfu" placeholder="TFU" style="width: 100px;">
                                    <span>cm</span>

                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
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
                            <div class="col-md-4 mb-2">
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
                            <div class="col-md-6 mb-2">
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

                                    <input type="text" class="form-control" name="his" id="his" placeholder="HIS" style="width: 100px;">
                                    <span>X/menit, Durasi</span>

                                    <input type="text" class="form-control" name="his_durasi" id="his_durasi" placeholder="Durasi" style="width: 100px;">

                                    <span>detik,</span>

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
                                <label class="form-label mb-0">Anogenital Inspeksi</label>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-3">
                                    <span>Pengeluaran per Vagina</span>

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
                                            <input class="form-check-input check-primary" type="checkbox" name="anogenital_lainnya" value="1">
                                            <label class="form-check-label">
                                                Lainnya
                                            </label>
                                        </div>

                                        <input type="text" class="form-control" name="anogenital_lainnya_keterangan" id="anogenital_lainnya_keterangan" placeholder="Keterangan...">
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- VAGINA TAUCHER -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Vagina Taucher</label>
                            </div>

                            <div class="col-md-10">
                                <input type="text" class="form-control" name="vagina_taucher" id="vagina_taucher" placeholder="Hasil pemeriksaan vagina taucher...">
                            </div>
                        </div>


                        <!-- LAIN-LAIN -->
                        <div class="row align-items-center mb-2">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Lain-lain</label>
                            </div>

                            <div class="col-md-10">
                                <input type="text" class="form-control" name="pemeriksaan_lain_lain" id="pemeriksaan_lain_lain" placeholder="Lain-lain...">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                </div>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_penunjang',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rio_perawat',
                        'metodeNyeri' => ['nrs', 'bps', 'nips', 'flacc', 'vas']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rio_perawat'])
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group mb-2">
                        <h5 class="mb-0 text-success">
                            <strong>Masalah Keperawatan</strong>
                        </h5>
                    </div>

                    <!-- Kolom Kiri -->
                    <div class="col">

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_1" id="diag_1">
                            <label class="form-check-label">Nyeri</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_2" id="diag_2">
                            <label class="form-check-label">Resiko Pendarahan</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_3" id="diag_3">
                            <label class="form-check-label">Resiko Infeksi</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_4" id="diag_4">
                            <label class="form-check-label">Resiko Syok</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_5" id="diag_5">
                            <label class="form-check-label">Retensi Urin</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_6" id="diag_6">
                            <label class="form-check-label">Gangguan Nutrisi</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_7" id="diag_7">
                            <label class="form-check-label">Bersihkan Jalan Nafas Tidak Efektif</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_8" id="diag_8">
                            <label class="form-check-label">Cemas</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_9" id="diag_9">
                            <label class="form-check-label">Diare</label>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col">

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_10" id="diag_10">
                            <label class="form-check-label">Gangguan Integritas Jaringan</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_11" id="diag_11">
                            <label class="form-check-label">Gangguan Komunikasi Verbal</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_12" id="diag_12">
                            <label class="form-check-label">Gangguan Mobilitas Fisik</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_13" id="diag_13">
                            <label class="form-check-label">Gangguan Pola Tidur</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_14" id="diag_14">
                            <label class="form-check-label">Hipertermi</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_15" id="diag_15">
                            <label class="form-check-label">Hipotermi</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_16" id="diag_16">
                            <label class="form-check-label">Kurang Perawatan Diri : ADL</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_17" id="diag_17">
                            <label class="form-check-label">Resiko Jatuh</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.discharge_planning',['section' => '#rio_perawat'])
            </div>
        </div>
    </div>
</div>

