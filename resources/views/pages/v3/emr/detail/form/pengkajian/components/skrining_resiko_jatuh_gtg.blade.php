<div class="form-group" id="form_skrining_getup_and_go">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input
                class="form-check-input check-primary"
                type="checkbox"
                id="srj_gtg"
            >
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">
                    Penilaian Resiko Jatuh Get Up and Go
                </small>
            </label>
        </div>
    </div>

    <div class="row" id="tampil_srj_gtg" hidden>

        <div class="col-md-12 mb-3">
            <div class="row">

                <div class="col-md-12 mb-1">
                    <div class="form-group">
                        <h5 class="border-bottom pb-2 text-bold">
                            <strong>
                                Skrining Resiko Jatuh (Get Up and Go)
                            </strong>
                        </h5>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- CARA BERJALAN --}}
                {{-- ========================================= --}}
                <div class="col-md-9">
                    <label class="form-label fw-bold">
                        Cara Berjalan Pasien (Salah Satu atau Lebih)
                    </label>

                    <div>
                        a. Tidak seimbang / menyopang / limbung
                    </div>

                    <div>
                        b. Jalan dengan menggunakan alat bantu
                        (Tongkat, kursi roda, dibantu orang lain)
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label d-block">&nbsp;</label>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input single-checkbox gtg-cara-berjalan"
                            type="checkbox"
                            name="gtg_cara_berjalan"
                            id="gtg_cara_berjalan_ya"
                            value="1"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_cara_berjalan_ya"
                        >
                            Ya
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input single-checkbox gtg-cara-berjalan"
                            type="checkbox"
                            name="gtg_cara_berjalan"
                            id="gtg_cara_berjalan_tidak"
                            value="0"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_cara_berjalan_tidak"
                        >
                            Tidak
                        </label>
                    </div>
                </div>

                <hr>

                {{-- ========================================= --}}
                {{-- FAKTOR RISIKO --}}
                {{-- ========================================= --}}
                <div class="col-md-9">

                    <label class="form-label fw-bold">
                        Faktor Risiko
                    </label>

                    <div>
                        <b>A.</b> Umur ≥ 60 tahun, anak &lt; 3 tahun
                    </div>

                    <div class="mt-2">
                        <b>B.</b>
                        Diagnosis penyakit / situasi / keluhan yang
                        mungkin menyebabkan pasien berisiko jatuh:
                    </div>

                    <ol class="mb-2">
                        <li>Vertigo / Pusing</li>
                        <li>Parkinson</li>
                        <li>
                            Gangguan penglihatan yang belum terkoreksi
                            (Glaukoma, Katarak, Gangguan Lapangan Penglihatan)
                        </li>
                        <li>
                            Riwayat tirah baring lama yang akan dipindahkan
                            untuk pemeriksaan penunjang
                        </li>
                        <li>Pasien yang mendapat sedasi</li>
                    </ol>

                    <div>
                        <b>C.</b> Lingkungan :
                    </div>

                    <ol>
                        <li>
                            Area-area yang berisiko pasien jatuh
                            (Tangga, penerangan kurang,
                            jalan menurun/menanjak)
                        </li>
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
                        <input
                            class="form-check-input single-checkbox gtg-faktor-resiko"
                            type="checkbox"
                            name="gtg_faktor_resiko"
                            id="gtg_faktor_resiko_ya"
                            value="1"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_faktor_resiko_ya"
                        >
                            Ya
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input single-checkbox gtg-faktor-resiko"
                            type="checkbox"
                            name="gtg_faktor_resiko"
                            id="gtg_faktor_resiko_tidak"
                            value="0"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_faktor_resiko_tidak"
                        >
                            Tidak
                        </label>
                    </div>
                </div>

                <hr>

                {{-- ========================================= --}}
                {{-- OBAT --}}
                {{-- ========================================= --}}
                <div class="col-md-9">

                    <label class="form-label fw-bold">
                        Menanyakan Obat-obatan yang diminum pasien
                        saat ini, yang mungkin menyebabkan risiko
                        jatuh adalah:
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
                        <input
                            class="form-check-input single-checkbox gtg-obat"
                            type="checkbox"
                            name="gtg_obat"
                            id="gtg_obat_ya"
                            value="1"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_obat_ya"
                        >
                            Ya
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input single-checkbox gtg-obat"
                            type="checkbox"
                            name="gtg_obat"
                            id="gtg_obat_tidak"
                            value="0"
                        >
                        <label
                            class="form-check-label"
                            for="gtg_obat_tidak"
                        >
                            Tidak
                        </label>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- HASIL --}}
                {{-- ========================================= --}}
                <div class="col-md-12">
                    <div
                        id="skor_gtg"
                        class="mb-3 mt-2"
                        hidden
                    >
                        <div class="alert alert-success mb-0 d-inline-flex align-items-center">

                            <div class="me-4 flex-shrink-0">
                                <h1
                                    class="display-1 fw-bold mb-0"
                                    id="nilai_gtg"
                                >
                                    0
                                </h1>
                            </div>

                            <div>
                                <h5 class="mb-1 fw-bold">
                                    Skor Risiko Jatuh
                                </h5>

                                <div
                                    class="fw-bold text-success"
                                    id="kategori_gtg"
                                ></div>

                                <small
                                    class="text-muted"
                                    id="keterangan_gtg"
                                ></small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Hidden total score --}}
                <input
                    type="number"
                    class="form-control"
                    name="skor_gtg"
                    value="0"
                    hidden
                >

                {{-- ========================================= --}}
                {{-- BUTTON --}}
                {{-- ========================================= --}}
                <div class="col-md-12">
                    <button
                        type="button"
                        class="btn btn-primary btn-save-sub-pengkajian"
                        onclick="simpanSkriningGetUpAndGo(this)"
                    >
                        <i class="ri-save-line me-1"></i>
                        Simpan Skrining Jatuh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var $sectionGetUpAndGo = $('#form_skrining_getup_and_go');

    $(document).ready(function () {

        // ==========================================
        // CHECKBOX UTAMA
        // ==========================================
        $sectionGetUpAndGo.on('change', '#srj_gtg', function () {

            if ($(this).is(':checked')) {

                $sectionGetUpAndGo
                    .find('#tampil_srj_gtg')
                    .prop('hidden', false);

            } else {

                $sectionGetUpAndGo
                    .find('#tampil_srj_gtg')
                    .prop('hidden', true);

                resetGetUpAndGo();
            }
        });


        // ==========================================
        // SINGLE CHECKBOX
        // ==========================================
        $sectionGetUpAndGo.on(
            'change',
            '.single-checkbox',
            function () {

                const name = $(this).attr('name');

                if ($(this).is(':checked')) {

                    $sectionGetUpAndGo
                        .find(`input[name="${name}"]`)
                        .not(this)
                        .prop('checked', false);

                }

                hitungSkorGetUpAndGo();
            }
        );


        // ==========================================
        // HITUNG AWAL
        // ==========================================
        hitungSkorGetUpAndGo();


        // ==========================================
        // GET DATA
        // ==========================================
        getSkriningGetUpAndGo();

    });


    // =====================================================
    // HITUNG SKOR GET UP AND GO
    // =====================================================
    function hitungSkorGetUpAndGo() {

        const caraBerjalan = $sectionGetUpAndGo
            .find('input[name="gtg_cara_berjalan"]:checked')
            .val();

        const faktorResiko = $sectionGetUpAndGo
            .find('input[name="gtg_faktor_resiko"]:checked')
            .val();

        const obat = $sectionGetUpAndGo
            .find('input[name="gtg_obat"]:checked')
            .val();


        // Belum lengkap
        if (
            caraBerjalan === undefined ||
            faktorResiko === undefined ||
            obat === undefined
        ) {

            $sectionGetUpAndGo
                .find('#skor_gtg')
                .prop('hidden', true);

            return;
        }


        // ==========================================
        // HITUNG
        // ==========================================
        const skor =
            Number(caraBerjalan) +
            Number(faktorResiko) +
            Number(obat);


        const isRisk = skor > 0;


        const kategori = isRisk
            ? 'Berisiko Jatuh'
            : 'Tidak Berisiko Jatuh';


        // ==========================================
        // TAMPILKAN NILAI
        // ==========================================
        $sectionGetUpAndGo
            .find('#nilai_gtg')
            .text(skor);


        $sectionGetUpAndGo
            .find('input[name="skor_gtg"]')
            .val(skor);


        // ==========================================
        // KATEGORI
        // ==========================================
        $sectionGetUpAndGo
            .find('#kategori_gtg')
            .text(kategori)
            .removeClass('text-success text-danger')
            .addClass(
                isRisk
                    ? 'text-danger'
                    : 'text-success'
            );


        // ==========================================
        // KETERANGAN
        // ==========================================
        $sectionGetUpAndGo
            .find('#keterangan_gtg')
            .text(
                isRisk
                    ? 'Lakukan pencegahan risiko jatuh sesuai prosedur.'
                    : 'Monitoring dan evaluasi risiko jatuh sesuai prosedur.'
            );


        // ==========================================
        // ALERT
        // ==========================================
        $sectionGetUpAndGo
            .find('#skor_gtg .alert')
            .removeClass(
                'alert-success alert-danger'
            )
            .addClass(
                isRisk
                    ? 'alert-danger'
                    : 'alert-success'
            );


        $sectionGetUpAndGo
            .find('#skor_gtg')
            .prop('hidden', false);
    }


    // =====================================================
    // RESET
    // =====================================================
    function resetGetUpAndGo() {

        $sectionGetUpAndGo
            .find('.single-checkbox')
            .prop('checked', false);


        $sectionGetUpAndGo
            .find('input[name="skor_gtg"]')
            .val(0);


        $sectionGetUpAndGo
            .find('#nilai_gtg')
            .text(0);


        $sectionGetUpAndGo
            .find('#skor_gtg')
            .prop('hidden', true);


        $sectionGetUpAndGo
            .find('#kategori_gtg')
            .text('')
            .removeClass(
                'text-success text-warning text-danger'
            );


        $sectionGetUpAndGo
            .find('#keterangan_gtg')
            .text('');


        $sectionGetUpAndGo
            .find('#skor_gtg .alert')
            .removeClass(
                'alert-success alert-warning alert-danger'
            )
            .addClass('alert-success');
    }


    // =====================================================
    // GET DATA
    // =====================================================
    function getSkriningGetUpAndGo() {

        $.ajax({

            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/gtg/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const gtg = res.data;

                if (gtg) {

                    // Aktifkan checkbox utama
                    $sectionGetUpAndGo
                        .find('#srj_gtg')
                        .prop('checked', true);


                    // Tampilkan form
                    $sectionGetUpAndGo
                        .find('#tampil_srj_gtg')
                        .prop('hidden', false);


                    // ==================================
                    // CARA BERJALAN
                    // ==================================
                    FormHelper.setSingleCheckbox(
                        $sectionGetUpAndGo,
                        'gtg_cara_berjalan',
                        gtg.CARA_BERJALAN_PASIEN
                    );


                    // ==================================
                    // FAKTOR RISIKO
                    // ==================================
                    FormHelper.setSingleCheckbox(
                        $sectionGetUpAndGo,
                        'gtg_faktor_resiko',
                        gtg.FAKTOR_RESIKO
                    );


                    // ==================================
                    // OBAT
                    // ==================================
                    FormHelper.setSingleCheckbox(
                        $sectionGetUpAndGo,
                        'gtg_obat',
                        gtg.OBAT_YANG_DIMINUM
                    );


                    // Hitung ulang
                    hitungSkorGetUpAndGo();
                }

            },

            error: function (xhr) {

                let message =
                    'Gagal mengambil data Skrining Get Up and Go.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            }

        });
    }


    // =====================================================
    // SIMPAN
    // =====================================================
    function simpanSkriningGetUpAndGo(btn) {

        const $button = $(btn);


        const data = getFormDataByName(
            $sectionGetUpAndGo,
            {
                NOKUNJ: kunjungan
            }
        );


        $.ajax({

            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/gtg/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            beforeSend: function () {

                $button
                    .prop('disabled', true)
                    .html(
                        '<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...'
                    );

            },


            success: function (res) {

                Swal.fire({

                    position: 'top-end',

                    icon: 'success',

                    title:
                        res.message ||
                        'Data Skrining berhasil disimpan',

                    showConfirmButton: false,

                    timer: 1000,

                    toast: true

                });


                getSkriningGetUpAndGo();

            },


            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message =
                        Object.values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('&nbsp;');

                } else if (
                    xhr.responseJSON?.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                iziToast.error({

                    title: 'Validasi Gagal!',

                    message: message,

                    position: 'topRight'

                });

            },


            complete: function () {

                $button
                    .prop('disabled', false)
                    .html(
                        '<i class="ri-save-line me-1"></i> Simpan Skrining Jatuh'
                    );

            }

        });
    }

</script>
