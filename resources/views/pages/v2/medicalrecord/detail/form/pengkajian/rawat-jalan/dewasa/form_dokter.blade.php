<div class="form-wrapper" id="form_rajal_dewasa_dokter">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL MEDIS <b class="">RAWAT JALAN</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                                [
                                    'section' => '#rjd_dokter',
                                    'anak' => 'false',
                                ]
                            )
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-12 mb-2">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                                [
                                    'section' => '#rjd_dokter',
                                    'page' => 'dokter',
                                    'idRuangan' => $list['dataKunjungan']->IDRUANGAN ?? $list['dataKunjungan']['IDRUANGAN'] ?? null,
                                    'editableFields' => [
                                        'tv_keu',
                                        'tv_gcs_e',
                                        'tv_gcs_v',
                                        'tv_gcs_m',
                                        'tv_td_up',
                                        'tv_td_down',
                                        'tv_nadi',
                                        'tv_nadi_cb',
                                        'tv_nafas',
                                        'tv_nafas_cb',
                                        'tv_suhu',
                                        'tv_spo2',
                                        'tv_bb',
                                        'tv_tb',
                                        'tv_visus_od',
                                        'tv_visus_os',
                                        'tv_tio_od',
                                        'tv_tio_os',
                                    ],
                                ]
                            )
                        </div>
                        <div class="col-md-12 mb-3">
                            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.pemeriksaan_fisik_rajal',['section' => '#rjd_dokter'])
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
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                            <div class="mb-3">
                                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-12 mb-3">
                            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.tolok_ukur_terapi',['section' => '#rjd_dokter'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.materi_edukasi',['section' => '#rjd_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#rjd_dokter'])
            </div>
        </div>
    </div>
    @include('pages.v2.medicalrecord.detail.form.finalisasi', [
        'jenis' => 'rajal_dewasa',
        'formKey' => 'rjd_dokter',
        'form' => 'pengkajian-rajal-dewasa',
        'sub' => 'DOKTER',
        'kunjungan' => $list['kunjungan'],
    ])
</div>

<script>
    // const dataKunjungan = @json($list['dataKunjungan']);
    // console.log(dataKunjungan.IDRUANGAN);
</script>

{{-- CONTOH VALUE DARI JSON - dataKunjungan --}}
{{--
    "NOMOR": "1020201022607290001",
    "NOPEN": "2607290001",
    "RUANGAN": "102020102",
    "MASUK": "2026-07-29 03:24:58",
    "KELUAR": "2026-08-06 23:25:00",
    "RUANG_KAMAR_TIDUR": 0,
    "REF": null,
    "DITERIMA_OLEH": 7,
    "BARU": 0,
    "TITIPAN": 0,
    "TITIPAN_KELAS": 0,
    "STATUS": 2,
    "FINAL_HASIL": 0,
    "FINAL_HASIL_OLEH": 0,
    "FINAL_HASIL_TANGGAL": null,
    "DPJP": 32,
    "OTOMATIS": 0,
    "PEMOHON_REVISI": null,
    "DETAIL_REVISI": null,
    "NORM": 37804,
    "TGLDAFTAR": "2026-07-29 03:24:38",
    "NOSEP": "",
    "TGLSEP": "2025-09-01 06:25:11",
    "NOBPJS": "0002697964929",
    "IDRUANGAN": "102020102",                                               <<<<< ------ CONTOH DIPANGGIL = dataKunjungan.IDRUANGAN
    "NAMARUANGAN": "Rawat Darurat",
    "NIKPASIEN": "3311041702000005",
    "NAMALENGKAPPASIEN": "MUHAMMAD ARIZAL YUSUF HERMAWAN",
    "PANGGILANPASIEN": "MUH",
    "TGLLAHIRPASIEN": "2000-02-17 00:00:00",
    "JKPASIEN": "LAKI-LAKI",
    "NAMAPASIEN": "MUHAMMAD ARIZAL YUSUF HERMAWAN, SDR",
    "ALAMATPASIEN": "JL BAWEAN 17 SUKOHARJO RT. 3 RW. 1 Kel/Desa. SUKOHARJO Kec. SUKOHARJO Kab/Kota. SUKOHARJO Prov. JAWA TENGAH 57512",
    "NAMADOKTER": "dr. FITRIANA DARMASTUTI",
    "UMURPASIEN": "26 Th/ 5 bl/ 12 hr",
    "TLPASIEN": "SUKOHARJO"
--}}
