<div class="form-wrapper" id="form_khusus_kecanduanobat">
    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium"><center>PENGKAJIAN <b class="text-primary">PASIEN KECANDUAN OBAT TERLARANG ATAU ALKOHOL</b></center></h1>
    <div class="form-content">

        <!-- ================================================= -->
        <!-- HUBUNGAN DENGAN ORANG TERDEKAT -->
        <!-- ================================================= -->
        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-5 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Hubungan pasien dengan orang terdekat
                </label>
            </div>

            <div class="col-12 col-md-7">
                <div class="d-flex flex-wrap align-items-center gap-4">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_hubungan_orang_terdekat" value="Tidak ada masalah" id="pn_hubungan_tidak_ada_masalah">
                        <label class="form-check-label" for="pn_hubungan_tidak_ada_masalah">
                            Tidak ada masalah
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_hubungan_orang_terdekat" value="Bermasalah/lain-lain" id="pn_hubungan_bermasalah">
                        <label class="form-check-label" for="pn_hubungan_bermasalah">
                            Bermasalah/lain-lain
                        </label>
                    </div>

                </div>
            </div>
        </div>


        <!-- ================================================= -->
        <!-- SEJAK KAPAN MULAI BERMASALAH -->
        <!-- ================================================= -->
        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-5 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Sejak kapan pasien mulai bermasalah
                </label>
            </div>

            <div class="col-12 col-md-7">

                <input type="text" class="form-control form-control-sm w-100" name="pn_mulai_bermasalah" id="pn_mulai_bermasalah" placeholder="SD/SMP/SMA/S1/Lain-lain">

            </div>
        </div>


        <!-- ================================================= -->
        <!-- JENIS MASALAH -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Jenis masalah yang dilakukan
                </label>
            </div>

            <div class="col-12 col-md-9">

                <div class="row g-2">

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Bolos sekolah/kerja" id="pn_jenis_bolos">
                            <label class="form-check-label" for="pn_jenis_bolos">
                                Bolos sekolah/kerja
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Putus sekolah, kelas" id="pn_jenis_putus_sekolah">
                            <label class="form-check-label" for="pn_jenis_putus_sekolah">
                                Putus sekolah, kelas
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Perokok berat" id="pn_jenis_perokok">
                            <label class="form-check-label" for="pn_jenis_perokok">
                                Perokok berat, ..... batang sehari
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Tawuran antar kelompok" id="pn_jenis_tawuran">
                            <label class="form-check-label" for="pn_jenis_tawuran">
                                Tawuran antar kelompok
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Dunia malam" id="pn_jenis_dunia_malam">
                            <label class="form-check-label" for="pn_jenis_dunia_malam">
                                Dunia malam
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Minuman beralkohol" id="pn_jenis_alkohol">
                            <label class="form-check-label" for="pn_jenis_alkohol">
                                Minuman beralkohol
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="NAPZA" id="pn_jenis_napza">
                            <label class="form-check-label" for="pn_jenis_napza">
                                NAPZA
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_masalah[]" value="Sex bebas" id="pn_jenis_sex_bebas">
                            <label class="form-check-label" for="pn_jenis_sex_bebas">
                                Sex bebas
                            </label>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- PENYEBAB PASIEN BERMASALAH -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Penyebab pasien bermasalah
                </label>
            </div>

            <div class="col-12 col-md-9">

                <div class="row g-2">

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Sering disakiti" id="pn_penyebab_disakiti">
                            <label class="form-check-label" for="pn_penyebab_disakiti">
                                Sering disakiti
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Sering mengalami kegagalan" id="pn_penyebab_kegagalan">
                            <label class="form-check-label" for="pn_penyebab_kegagalan">
                                Sering mengalami kegagalan
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Tidak diterima dilingkungan" id="pn_penyebab_lingkungan">
                            <label class="form-check-label" for="pn_penyebab_lingkungan">
                                Tidak diterima dilingkungan
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Ketergantungan narkoba" id="pn_penyebab_narkoba">
                            <label class="form-check-label" for="pn_penyebab_narkoba">
                                Ketergantungan narkoba
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Keluarga dengan manajemen kacau" id="pn_penyebab_manajemen">
                            <label class="form-check-label" for="pn_penyebab_manajemen">
                                Keluarga dengan manajemen kacau
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Keluarga dengan konflik tinggi" id="pn_penyebab_konflik">
                            <label class="form-check-label" for="pn_penyebab_konflik">
                                Keluarga dengan konflik tinggi
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Keluarga yang orangtuanya otoriter" id="pn_penyebab_otoriter">
                            <label class="form-check-label" for="pn_penyebab_otoriter">
                                Keluarga yang orangtuanya otoriter
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_penyebab_masalah[]" value="Keluarga yang berfeksionis" id="pn_penyebab_perfeksionis">
                            <label class="form-check-label" for="pn_penyebab_perfeksionis">
                                Keluarga yang berfeksionis
                            </label>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- PENGALAMAN TIDAK MENYENANGKAN -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Sejak kapan pengalaman yang tidak menyenangkan dirasakan
                </label>
            </div>

            <div class="col-12 col-md-6">

                <div class="d-flex flex-column gap-2">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_pengalaman_tidak_menyenangkan" value="SD/SMP/SMA/S1" id="pn_pengalaman_pendidikan">
                        <label class="form-check-label" for="pn_pengalaman_pendidikan">
                            SD/SMP/SMA/S1
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_pengalaman_tidak_menyenangkan" value="Setelah berkeluarga" id="pn_pengalaman_keluarga">
                        <label class="form-check-label" for="pn_pengalaman_keluarga">
                            Setelah berkeluarga
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_pengalaman_tidak_menyenangkan" value="Setelah bekerja" id="pn_pengalaman_bekerja">
                        <label class="form-check-label" for="pn_pengalaman_bekerja">
                            Setelah bekerja
                        </label>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- PERASAAN YANG TIMBUL -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Perasaan apa yang timbul saat itu
                </label>
            </div>

            <div class="col-12 col-md-8">

                <div class="d-flex flex-wrap align-items-center gap-4">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_perasaan" value="Tidak masalah" id="pn_perasaan_tidak_masalah">
                        <label class="form-check-label" for="pn_perasaan_tidak_masalah">
                            Tidak masalah
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_perasaan" value="Stres berat" id="pn_perasaan_stres">
                        <label class="form-check-label" for="pn_perasaan_stres">
                            Stres berat
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input single-checkbox" name="pn_perasaan" value="Marah" id="pn_perasaan_marah">
                        <label class="form-check-label" for="pn_perasaan_marah">
                            Marah
                        </label>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- TINDAKAN PENCEGAHAN -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Tindakan pencegahan yang dilakukan
                </label>
            </div>

            <div class="col-12 col-md-8">

                <div class="row g-2">

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_tindakan_pencegahan[]" value="Tidak dilakukan" id="pn_pencegahan_tidak">
                            <label class="form-check-label" for="pn_pencegahan_tidak">
                                Tidak dilakukan
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_tindakan_pencegahan[]" value="Konsultasi dokter ahli/psikologi" id="pn_pencegahan_konsultasi">
                            <label class="form-check-label" for="pn_pencegahan_konsultasi">
                                Konsultasi dokter ahli/psikologi
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_tindakan_pencegahan[]" value="Mendekatkan diri keTuhan" id="pn_pencegahan_tuhan">
                            <label class="form-check-label" for="pn_pencegahan_tuhan">
                                Mendekatkan diri ke Tuhan
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_tindakan_pencegahan[]" value="Bercerita ke orang tua/orang terdekat" id="pn_pencegahan_bercerita">
                            <label class="form-check-label" for="pn_pencegahan_bercerita">
                                Bercerita ke orang tua/orang terdekat
                            </label>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- JENIS ZAT YANG DIGUNAKAN -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Jenis Zat yang digunakan
                </label>
            </div>

            <div class="col-12 col-md-8">

                <div class="d-flex flex-column gap-2">

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_zat[]" value="Narkoba" id="pn_zat_narkoba">
                            <label class="form-check-label" for="pn_zat_narkoba">
                                Narkoba, Sebutkan
                            </label>
                        </div>

                        <input type="text" class="form-control form-control-sm" name="pn_zat_narkoba_keterangan" id="pn_zat_narkoba_keterangan"
                            placeholder="Sebutkan"
                            style="max-width: 300px;">

                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_zat[]" value="Psikotropika" id="pn_zat_psikotropika">
                            <label class="form-check-label" for="pn_zat_psikotropika">
                                Psikotropika, Sebutkan
                            </label>
                        </div>

                        <input type="text" class="form-control form-control-sm" name="pn_zat_psikotropika_keterangan" id="pn_zat_psikotropika_keterangan"
                            placeholder="Sebutkan"
                            style="max-width: 300px;">

                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="pn_jenis_zat[]" value="Zat adiktif lainnya" id="pn_zat_adiktif">
                            <label class="form-check-label" for="pn_zat_adiktif">
                                Zat adiktif lainnya, Sebutkan
                            </label>
                        </div>

                        <input type="text" class="form-control form-control-sm" name="pn_zat_adiktif_keterangan" id="pn_zat_adiktif_keterangan"
                            placeholder="Sebutkan"
                            style="max-width: 300px;">

                    </div>

                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- CARA PENGGUNAAN ZAT -->
        <!-- ================================================= -->
        <div class="row align-items-center mb-3">

            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label class="form-label fw-semibold mb-1">
                    Cara penggunaan zat
                </label>
            </div>

            <div class="col-12 col-md-8">

                <input type="text" class="form-control form-control-sm w-100" name="pn_cara_penggunaan_zat" id="pn_cara_penggunaan_zat" placeholder="Dihisap / Dihirup / Disuntik / Diminum">

            </div>
        </div>


        <!-- ================================================= -->
        <!-- REAKSI AKIBAT NAPZA / ALKOHOL -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">
                    Reaksi yang timbul / akibat mengkonsumsi NAPZA / minuman beralkohol
                </label>
            </div>

            <div class="col-12">

                <!-- Kecanduan -->
                <div class="row align-items-center mb-2">

                    <div class="col-12 col-sm-4">
                        <label class="form-label mb-1">
                            a. Kecanduan
                        </label>
                    </div>

                    <div class="col-12 col-sm-8">

                        <div class="d-flex flex-wrap gap-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_kecanduan" value="Tidak" id="pn_kecanduan_tidak">
                                <label class="form-check-label" for="pn_kecanduan_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_kecanduan" value="Ya" id="pn_kecanduan_ya">
                                <label class="form-check-label" for="pn_kecanduan_ya">
                                    Ya
                                </label>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Intoksikasi -->
                <div class="row align-items-center mb-2">

                    <div class="col-12 col-sm-4">
                        <label class="form-label mb-1">
                            b. Intoksikasi
                        </label>
                    </div>

                    <div class="col-12 col-sm-8">

                        <div class="d-flex flex-wrap gap-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_intoksikasi" value="Tidak" id="pn_intoksikasi_tidak">
                                <label class="form-check-label" for="pn_intoksikasi_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_intoksikasi" value="Ya" id="pn_intoksikasi_ya">
                                <label class="form-check-label" for="pn_intoksikasi_ya">
                                    Ya
                                </label>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Overdosis -->
                <div class="row align-items-center mb-2">

                    <div class="col-12 col-sm-4">
                        <label class="form-label mb-1">
                            c. Overdosis (OD)
                        </label>
                    </div>

                    <div class="col-12 col-sm-8">

                        <div class="d-flex flex-wrap gap-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_overdosis" value="Tidak" id="pn_overdosis_tidak">
                                <label class="form-check-label" for="pn_overdosis_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_overdosis" value="Ya" id="pn_overdosis_ya">
                                <label class="form-check-label" for="pn_overdosis_ya">
                                    Ya
                                </label>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>


        <!-- ================================================= -->
        <!-- REHABILITASI -->
        <!-- ================================================= -->
        <div class="row align-items-start mb-3">

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">
                    Apakah pernah menjalani rehabilitasi
                </label>
            </div>

            <div class="col-12">

                <!-- Rehabilitasi psikososial -->
                <div class="row align-items-center mb-2">

                    <div class="col-12 col-sm-5">
                        <label class="form-label mb-1">
                            a. Rehabilitasi psikososial
                        </label>
                    </div>

                    <div class="col-12 col-sm-7">

                        <div class="d-flex flex-wrap gap-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_rehabilitasi_psikososial" value="Tidak" id="pn_rehab_psikososial_tidak">
                                <label class="form-check-label" for="pn_rehab_psikososial_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_rehabilitasi_psikososial" value="Ya" id="pn_rehab_psikososial_ya">
                                <label class="form-check-label" for="pn_rehab_psikososial_ya">
                                    Ya
                                </label>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Rehabilitasi kejiwaan -->
                <div class="row align-items-center mb-2">

                    <div class="col-12 col-sm-5">
                        <label class="form-label mb-1">
                            b. Rehabilitasi kejiwaan
                        </label>
                    </div>

                    <div class="col-12 col-sm-7">

                        <div class="d-flex flex-wrap gap-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_rehabilitasi_kejiwaan" value="Tidak" id="pn_rehab_kejiwaan_tidak">
                                <label class="form-check-label" for="pn_rehab_kejiwaan_tidak">
                                    Tidak
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input single-checkbox" name="pn_rehabilitasi_kejiwaan" value="Ya" id="pn_rehab_kejiwaan_ya">
                                <label class="form-check-label" for="pn_rehab_kejiwaan_ya">
                                    Ya
                                </label>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_khusus_kecanduanobat');

    let isDataLoading = false;
    let isDataSaving = false;

    $(document).ready(function() {
        // Hanya memperbolehkan checkbox dipilih salah satu saja
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            $('input.single-checkbox[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
        $('.single-checkbox-bos').on('change', function () {
            // Jika checkbox di-uncheck, langsung kembalikan ke checked
            if (!this.checked) {
                this.checked = true;
                return;
            }
            // Uncheck pilihan lain dengan name yang sama
            $('input.single-checkbox-bos[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
    })

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getData() {

        if (!$form.length) {
            console.warn('Form Data tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/kecanduanobat/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }


                // ==========================================================
                // HELPER PARSE JSON
                // ==========================================================
                function parseJsonValue(value) {

                    if (value === null || value === undefined || value === '') {
                        return null;
                    }

                    if (typeof value === 'object') {
                        return value;
                    }

                    if (typeof value === 'string') {

                        try {
                            return JSON.parse(value);
                        } catch (e) {
                            return value;
                        }
                    }

                    return value;
                }


                // ==========================================================
                // HUBUNGAN ORANG TERDEKAT
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_hubungan_orang_terdekat"]')
                    .prop('checked', false);

                if (tlt.HUBUNGAN_ORANG_TERDEKAT !== null) {

                    let values = parseJsonValue(
                        tlt.HUBUNGAN_ORANG_TERDEKAT
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_hubungan_orang_terdekat"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // SEJAK KAPAN BERMASALAH
                // VARCHAR
                // ==========================================================

                $form
                    .find('#pn_mulai_bermasalah')
                    .val(
                        tlt.SEJAK_KAPAN_BERMASALAH ?? ''
                    );


                // ==========================================================
                // JENIS MASALAH
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_jenis_masalah[]"]')
                    .prop('checked', false);

                if (tlt.JENIS_MASALAH) {

                    let values = tlt.JENIS_MASALAH;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {

                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }

                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pn_jenis_masalah[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // PENYEBAB BERMASALAH
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_penyebab_masalah[]"]')
                    .prop('checked', false);

                if (tlt.PENYEBAB_BERMASALAH) {

                    let values = tlt.PENYEBAB_BERMASALAH;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {

                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }

                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pn_penyebab_masalah[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // PENGALAMAN TIDAK MENYENANGKAN
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_pengalaman_tidak_menyenangkan"]')
                    .prop('checked', false);

                if (tlt.SEJAK_KAPAN_PENGALAMAN_TIDAK_MENYENANGKAN !== null) {

                    let values = parseJsonValue(
                        tlt.SEJAK_KAPAN_PENGALAMAN_TIDAK_MENYENANGKAN
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find(
                            'input[name="pn_pengalaman_tidak_menyenangkan"]'
                        )
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // PERASAAN YANG TIMBUL
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_perasaan"]')
                    .prop('checked', false);

                if (tlt.PERASAAN_YANG_TIMBUL !== null) {

                    let values = parseJsonValue(
                        tlt.PERASAAN_YANG_TIMBUL
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_perasaan"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // TINDAKAN PENCEGAHAN
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_tindakan_pencegahan[]"]')
                    .prop('checked', false);

                if (tlt.TINDAKAN_PENCEGAHAN) {

                    let values = tlt.TINDAKAN_PENCEGAHAN;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {

                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }

                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pn_tindakan_pencegahan[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // JENIS ZAT DIGUNAKAN
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_jenis_zat[]"]')
                    .prop('checked', false);

                if (tlt.JENIS_ZAT_DIGUNAKAN) {

                    let values = tlt.JENIS_ZAT_DIGUNAKAN;

                    if (!Array.isArray(values)) {

                        if (typeof values === 'string') {

                            try {
                                values = JSON.parse(values);
                            } catch (e) {
                                values = [values];
                            }

                        } else {
                            values = [values];
                        }
                    }

                    $form
                        .find('input[name="pn_jenis_zat[]"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // KETERANGAN ZAT
                // VARCHAR
                // ==========================================================

                $form
                    .find('#pn_zat_narkoba_keterangan')
                    .val(
                        tlt.KETERANGAN_NARKOBA ?? ''
                    );

                $form
                    .find('#pn_zat_psikotropika_keterangan')
                    .val(
                        tlt.KETERANGAN_PSIKOTROPIKA ?? ''
                    );

                $form
                    .find('#pn_zat_adiktif_keterangan')
                    .val(
                        tlt.KETERANGAN_ZAT_ADIKTIF ?? ''
                    );


                // ==========================================================
                // CARA PENGGUNAAN ZAT
                // VARCHAR
                // ==========================================================

                $form
                    .find('#pn_cara_penggunaan_zat')
                    .val(
                        tlt.CARA_PENGGUNAAN_ZAT ?? ''
                    );


                // ==========================================================
                // REAKSI KECANDUAN
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_kecanduan"]')
                    .prop('checked', false);

                if (tlt.REAKSI_KECANDUAN !== null) {

                    let values = parseJsonValue(
                        tlt.REAKSI_KECANDUAN
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_kecanduan"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // REAKSI INTOKSIKASI
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_intoksikasi"]')
                    .prop('checked', false);

                if (tlt.REAKSI_INTOKSIKASI !== null) {

                    let values = parseJsonValue(
                        tlt.REAKSI_INTOKSIKASI
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_intoksikasi"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // REAKSI OVERDOSIS
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_overdosis"]')
                    .prop('checked', false);

                if (tlt.REAKSI_OVERDOSIS !== null) {

                    let values = parseJsonValue(
                        tlt.REAKSI_OVERDOSIS
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_overdosis"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // REHABILITASI PSIKOSOSIAL
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_rehabilitasi_psikososial"]')
                    .prop('checked', false);

                if (tlt.REHABILITASI_PSIKOSOSIAL !== null) {

                    let values = parseJsonValue(
                        tlt.REHABILITASI_PSIKOSOSIAL
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find(
                            'input[name="pn_rehabilitasi_psikososial"]'
                        )
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }


                // ==========================================================
                // REHABILITASI KEJIWAAN
                // JSON
                // ==========================================================

                $form
                    .find('input[name="pn_rehabilitasi_kejiwaan"]')
                    .prop('checked', false);

                if (tlt.REHABILITASI_KEJIWAAN !== null) {

                    let values = parseJsonValue(
                        tlt.REHABILITASI_KEJIWAAN
                    );

                    if (!Array.isArray(values)) {
                        values = [values];
                    }

                    $form
                        .find('input[name="pn_rehabilitasi_kejiwaan"]')
                        .each(function () {

                            $(this).prop(
                                'checked',
                                values.includes($(this).val())
                            );

                        });
                }

            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isDataLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanData() {

        if (
            !$form.length ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDataSaving = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/kecanduanobat/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isDataSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        getData();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );
    });

})();
</script>
