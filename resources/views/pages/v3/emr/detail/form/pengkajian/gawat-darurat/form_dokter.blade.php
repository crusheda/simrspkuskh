<div class="form-wrapper position-relative" id="form_gawat_darurat_dokter">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL MEDIS <b class="text-danger">RAWAT DARURAT</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.ats',
                    [
                        'section' => '#gd_dokter',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.primary_survey',
                    [
                        'section' => '#gd_dokter',
                        'page' => 'dokter',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-success mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.secondary_survey',
                                [
                                    'section' => '#gd_dokter',
                                    'kunjungan' => $kunjungan ?? $list['kunjungan'],
                                ]
                            )
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-info mb-0">
                    <div class="mb-3">
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                    </div>
                    <div>
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-danger mb-0">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.perencanaan_terapi',
                    [
                        'section' => '#gd_dokter',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.hasil_lapor_dpjp',
                    [
                        'section' => '#gd_dokter',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                    ]
                )
            </div>
            <div class="col-md-12">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#gd_dokter'])
            </div>
            <div class="col-md-12">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.cara_pulang',
                    [
                        'section' => '#gd_dokter',
                        'kunjungan' => $kunjungan ?? $list['kunjungan'],
                        // 'list' => $list,
                    ]
                )
            </div>
        </div>
    </div>

    @include(
        'pages.v2.medicalrecord.detail.form.finalisasi',
        [
            'jenis' => 'gawat_darurat',
            'role' => 'dokter',
            'sub' => 'DOKTER',
            'formKey' => 'gd_dokter',
            'kunjungan' => $kunjungan ?? $list['kunjungan'],
        ]
    )

</div>

<script>
    var $sectionGdD = $('#gd_dokter');
    $(document).ready(function() {

    })

    // function getDataPengkajianGdD() {

    //     if (!$sectionGdD.length) {
    //         console.warn('Section Pengkajian Medis IGD tidak ditemukan.');
    //         return;
    //     }

    //     const $form = $sectionGdD.find('.form-wrapper').first();

    //     if (!$form.length) {
    //         console.warn('Form Pengkajian Medis IGD tidak ditemukan.');
    //         return;
    //     }

    //     // ============================================================
    //     // AJAX
    //     // ============================================================

    //     $.ajax({
    //         url: `/api/v2/emr/form/pengkajian/gd/dr/${kunjungan}`,
    //         type: "GET",
    //         dataType: "json",

    //         beforeSend: function () {
    //             console.log('Mengambil data Pengkajian Medis IGD...');
    //         },

    //         success: function (response) {
    //             // ====================================================
    //             // VALIDASI RESPONSE
    //             // ====================================================

    //             if (!response || response.status !== true) {
    //                 console.warn(
    //                     response?.message || 'Data Pengkajian Medis IGD tidak ditemukan.'
    //                 );
    //                 return;
    //             }

    //             const data = response.data || {};
    //             const triage = data.triage || {};

    //             // ====================================================
    //             // 13. SECONDARY SURVEY - KELUHAN UTAMA
    //             // ====================================================

    //             const keluhanUtama =
    //                 data.keluhan_utama || {};

    //             FormHelper.setValue(
    //                 $form,
    //                 'ku',
    //                 keluhanUtama.DESKRIPSI
    //             );


    //             // ====================================================
    //             // 14. ANAMNESIS / RIWAYAT PENYAKIT SEKARANG
    //             // ====================================================

    //             const anamnesis =
    //                 data.anamnesis || {};

    //             FormHelper.setValue(
    //                 $form,
    //                 'rps',
    //                 anamnesis.DESKRIPSI
    //             );

    //             // ====================================================
    //             // 15. RIWAYAT PENYAKIT DAHULU
    //             // ====================================================

    //             const rpp =
    //                 data.rpp || {};

    //             FormHelper.setValue(
    //                 $form,
    //                 'rpd',
    //                 rpp.DESKRIPSI
    //             );


    //             // ====================================================
    //             // 16. PEMERIKSAAN FISIK
    //             // ====================================================

    //             const pemeriksaanFisik =
    //                 data.pemeriksaan_fisik || {};

    //             FormHelper.setValue(
    //                 $form,
    //                 'pf',
    //                 pemeriksaanFisik.DESKRIPSI
    //             );


    //             // ====================================================
    //             // 19. PERENCANAAN TERAPI
    //             // ====================================================
    //             //
    //             // Field ini BELUM ada di JSON yang Anda kirim.
    //             // Jadi hanya akan diisi jika controller nanti
    //             // mengembalikan data tersebut.
    //             //
    //             if (data.perencanaan_terapi) {

    //                 FormHelper.setValue(
    //                 $form,
    //                     'pt',
    //                     data.perencanaan_terapi.DESKRIPSI
    //                 );
    //             }


    //             // ====================================================
    //             // 20. HASIL LAPOR DPJP
    //             // ====================================================
    //             //
    //             // Field ini juga belum ada di JSON saat ini.
    //             //
    //             if (data.hasil_lapor_dpjp) {

    //                 FormHelper.setValue(
    //                 $form,
    //                     'hld',
    //                     data.hasil_lapor_dpjp.DESKRIPSI
    //                 );
    //             }


    //             // ====================================================
    //             // 21. TINDAK LANJUT ASUHAN
    //             // ====================================================
    //             //
    //             // Belum tersedia pada JSON saat ini.
    //             //
    //             if (data.tindak_lanjut_asuhan) {

    //                 FormHelper.setValue(
    //                 $form,
    //                     'tla_ck',
    //                     data.tindak_lanjut_asuhan.CARA
    //                 );

    //                 FormHelper.setValue(
    //                 $form,
    //                     'tla_kk',
    //                     data.tindak_lanjut_asuhan.KEADAAN
    //                 );
    //             }

    //         },

    //         error: function (xhr, status, error) {

    //             console.error(
    //                 'Error getDataPengkajianGdD:',
    //                 xhr.responseText || error
    //             );

    //             let message = 'Gagal mengambil data Pengkajian Medis IGD.';

    //             if (xhr.responseJSON?.message) {
    //                 message = xhr.responseJSON.message;
    //             }

    //             console.warn(message);
    //         },

    //         complete: function () {
    //             console.log('Selesai getDataPengkajianGdD');
    //         }
    //     });
    // }

    // function saveDataPengkajianGdD(btn) {
    //     const $button = $(btn);
    //     const $sectionGdD = $('#gd_dokter');

    //     const data = getFormDataByName($sectionGdD, {
    //         NOKUNJ: $sectionGdD.data('kunjungan')
    //     });

    //     $.ajax({
    //         url: '/api/v2/emr/form/pengkajian/gd/dr/simpan',
    //         type: 'POST',
    //         data: data,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         beforeSend: function () {
    //             $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan Formulir...');
    //         },
    //         success: function (res) {
    //             Swal.fire({
    //                 position: "top-end",
    //                 icon: "success",
    //                 title: res.message || "Data berhasil disimpan",
    //                 showConfirmButton: false,
    //                 timer: 1500,
    //                 backdrop: `
    //                     rgba(0,0,123,0.4)
    //                     url("/images/nyan-cat.gif")
    //                     left top
    //                     no-repeat
    //                 `
    //             });
    //             getDataPengkajianGdD();
    //         },
    //         error: function (xhr) {
    //             let message = 'Data gagal disimpan.';
    //             if (xhr.status === 422 && xhr.responseJSON?.errors) {
    //                 message = Object.values(xhr.responseJSON.errors)
    //                     .flat()
    //                     .join('&nbsp;');
    //             } else if (xhr.responseJSON?.message) {
    //                 message = xhr.responseJSON.message;
    //             }
    //             iziToast.error({
    //                 title: 'Validasi Gagal!',
    //                 message: message,
    //                 position: 'topRight'
    //             });
    //         },
    //         complete: function () {
    //             $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
    //         }
    //     });
    // }
</script>
