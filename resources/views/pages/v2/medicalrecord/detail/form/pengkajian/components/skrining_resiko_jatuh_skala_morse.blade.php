<div class="form-group" id="form_skrining_skala_morse">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="srj_sm">
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">Penilaian Resiko Jatuh Dewasa ( Skala Morse)</small>
            </label>
        </div>
    </div>
    <div class="row" id="tampil_srj_sm" hidden>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Riwayat Jatuh (Baru Saja / 3 Bulan Terakhir)</label>
                <select class="form-select" name="rj_sm_1" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Tidak</option> {{-- 0 --}}
                    <option value="2">Ya</option> {{-- 25 --}}
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Diagnosa Lain / Diagnosa Sekunder</label>
                <select class="form-select" name="rj_sm_2" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Tidak</option> {{-- 0 --}}
                    <option value="2">Ya</option> {{-- 15 --}}
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Menggunakan Alat Bantu</label>
                <select class="form-select" name="rj_sm_3" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Tidak ada, bed rest</option> {{-- 0 --}}
                    <option value="2">Tongkat, alat penopang, walker</option> {{-- 15 --}}
                    <option value="3">Furnitur</option> {{-- 30 --}}
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Penggunaan Obat Yang Bisa Mempengaruhi Keseimbangan (Heparin, Diuretik, Anti Hipertensi, Anestesi, Anti Depresan dll)</label>
                <select class="form-select" name="rj_sm_4" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Tidak</option> {{-- 0 --}}
                    <option value="2">Ya</option> {{-- 20 --}}
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Gaya Berjalan</label>
                <select class="form-select" name="rj_sm_5" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Lemah</option> {{-- 10 --}}
                    <option value="2">Terganggu</option> {{-- 20 --}}
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Kesadaran</label>
                <select class="form-select" name="rj_sm_6" data-sm-required>
                    <option value="">Pilih</option>
                    <option value="1">Baik</option> {{-- 0 --}}
                    <option value="2">Lupa / pelupa</option> {{-- 15 --}}
                </select>
            </div>
            <input type="number" class="form-control" name="skor_rj_sm" value="0" hidden>
            <div id="skor_rj_sm" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_sm">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                        <div class="fw-bold text-success" id="kategori_rj_sm"></div>
                        <small class="text-muted" id="keterangan_rj_sm"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningSkalaMorse(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Jatuh
            </button>
        </div>
        <hr class="mt-3">
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on('change', '[data-sm-required]', function () {
            hitungSkorMorse($section);
        });
        $section.on('change', '#srj_sm', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Morse
                $section.find('#tampil_srj_sm').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_sm').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetMorse($section);
            }
        });

        hitungSkorMorse($section);

        getSkriningSkalaMorse();
    })

    function hitungSkorMorse($section) {
        const $fields = $section.find('[data-sm-required]');
        const $scoreBox = $section.find('#skor_rj_sm');

        const semuaTerisi = $fields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '';
        });

        // Hasil hanya muncul jika seluruh penilaian Morse sudah diisi
        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        // Mapping: name field => value option => skor Morse
        const scoreMap = {
            rj_sm_1: { 1: 0, 2: 25 },         // Riwayat jatuh
            rj_sm_2: { 1: 0, 2: 15 },         // Diagnosa sekunder
            rj_sm_3: { 1: 0, 2: 15, 3: 30 },  // Alat bantu
            rj_sm_4: { 1: 0, 2: 20 },         // Obat
            rj_sm_5: { 1: 10, 2: 20 },        // Gaya berjalan
            rj_sm_6: { 1: 0, 2: 15 }          // Kesadaran
        };

        let skor = 0;

        $fields.each(function () {
            const name = this.name;
            const value = $(this).val();

            skor += scoreMap[name][value];
        });

        $section.find('input[name="skor_rj_sm"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 45) {
            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko tinggi.';
        } else if (skor >= 25) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko sedang.';
        } else {
            kategori = 'Risiko Rendah';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi sesuai prosedur.';
        }

        $section.find('#nilai_rj_sm').text(skor);

        $section.find('#kategori_rj_sm')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_rj_sm').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetMorse($section) {

        // Reset semua pilihan
        $section.find('[data-sm-required]').val('');

        // Reset nilai skor
        $section.find('[name="skor_rj_sm"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_rj_sm').text(0);

        // Sembunyikan hasil skor
        $section.find('#skor_rj_sm').prop('hidden', true);

        // Kembalikan tampilan kategori
        $section.find('#kategori_rj_sm')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $section.find('#keterangan_rj_sm').text('');

        // Kembalikan alert ke default
        $section.find('#skor_rj_sm .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningSkalaMorse() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/sm/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const morse = res.data;

                if (morse) {
                    $('#srj_sm').prop('checked', true);
                    $('#tampil_srj_sm').prop('hidden', false);
                    FormHelper.setValue($section,'rj_sm_1', morse.RIWAYAT_JATUH);
                    FormHelper.setValue($section,'rj_sm_2', morse.DIAGNOSIS);
                    FormHelper.setValue($section,'rj_sm_3', morse.ALAT_BANTU);
                    FormHelper.setValue($section,'rj_sm_4', morse.HEPARIN);
                    FormHelper.setValue($section,'rj_sm_5', morse.GAYA_BERJALAN);
                    FormHelper.setValue($section,'rj_sm_6', morse.KESADARAN);
                }
            },
            error: function (xhr, status, error) {
                let message = 'Gagal mengambil data Pengkajian Medis IGD.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.warn(message);
            },
            complete: function () {
            }
        });
    }

    function simpanSkriningSkalaMorse(btn) {
        const $buttonSkriningSkalaMorse = $(btn);
        const $sectionSkriningSkalaMorse = $('#form_skrining_skala_morse');

        const data = getFormDataByName($sectionSkriningSkalaMorse, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/sm/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $buttonSkriningSkalaMorse.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },
            success: function (res) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: res.message || "Data Skrining berhasil disimpan",
                    showConfirmButton: false,
                    timer: 1500,
                    backdrop: `
                        rgba(0,0,123,0.4)
                        url("/images/nyan-cat.gif")
                        left top
                        no-repeat
                    `
                });
                getSkriningSkalaMorse();
            },
            error: function (xhr) {
                let message = 'Data gagal disimpan.';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('&nbsp;');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function () {
                $buttonSkriningSkalaMorse.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Skrining Jatuh');
            }
        });
    }
</script>
