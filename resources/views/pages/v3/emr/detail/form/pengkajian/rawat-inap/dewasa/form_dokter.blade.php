<div class="form-wrapper position-relative" id="form_ranap_dewasa_dokter">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL MEDIS <b class="text-danger">RAWAT INAP</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content position-relative">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                    [
                        'section' => '#riD_dokter',
                        'anak' => 'false',
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#riD_dokter',
                        'page' => 'dokter',
                        'editableFields' => [
                            // 'tv_keu',
                            // 'tv_gcs_e',
                            // 'tv_gcs_v',
                            // 'tv_gcs_m',
                            // 'tv_td_up',
                            // 'tv_td_down',
                            // 'tv_nadi',
                            // 'tv_nadi_cb',
                            // 'tv_nafas',
                            // 'tv_nafas_cb',
                            // 'tv_suhu',
                            // 'tv_spo2',
                            'tv_bb',
                            'tv_tb',
                        ],
                    ]
                )
            </div>
            <div class="col-md-12">
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
                                'perut',
                                'status_lokalis'
                            ]
                        ]
                    )
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
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.tata_laksana_terapi',['section' => '#riD_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.target_terapi',['section' => '#riD_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rencana_konsultasi',['section' => '#riD_dokter'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kriteria_pulang',['section' => '#riD_dokter'])
            </div>
        </div>
    </div>

    {{-- DESKRIPSI RULE --}}
    {{-- required → satu field/group harus terisi --}}
    {{-- any      → salah satu dari beberapa field harus terisi --}}
    {{-- all      → semua field dalam group harus terisi --}}

    @include('pages.v2.medicalrecord.detail.form.finalisasi', [
        'jenis' => 'ranap_dewasa',
        'role' => 'dokter',
        'formKey' => 'rid_dokter',
        'form' => 'pengkajian-ranap-dewasa',
        'sub' => 'DOKTER',
        'kunjungan' => $list['kunjungan'],

        'validasiFinalisasi' => [
            [
                'selector' => 'input[name="anam1"], input[name="anam2"]',
                'label' => 'Anamnesis diperoleh',
                'rule' => 'any',
            ],
            [
                'selector' => 'textarea[name="ku"]',
                'label' => 'Keluhan Utama',
            ],
            [
                'selector' => 'textarea[name="rps"]',
                'label' => 'Riwayat Penyakit Sekarang',
            ],
            [
                'selector' => 'textarea[name="rpd"]',
                'label' => 'Riwayat Penyakit Dahulu',
            ],
        ],
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
