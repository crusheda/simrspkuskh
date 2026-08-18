<div class="form-group" id="form_skrining_humpty_dumpty">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="srj_hd">
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">Penilaian Resiko Jatuh Anak ( Skala Humpty Dumpty)</small>
            </label>
        </div>
    </div>
    <div class="row" id="tampil_srj_hd" hidden>
        <div class="col-md-6 mb-3">
            <div class="input-group input-group-sm flex-grow-1">
                <span class="input-group-text">Usia</span>
                <select class="form-select form-select-sm" name="rj_usia" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    @if ($list['usia'])
                        @foreach ($list['usia'] as $item)
                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="input-group input-group-sm flex-grow-1">
                <span class="input-group-text">Jenis Kelamin</span>
                <select class="form-select form-select-sm" name="rj_jk" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    @if ($list['jk'])
                        @foreach ($list['jk'] as $item)
                            <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Diagnosa</label>
                <select class="form-select form-select-sm" name="rj_hd_1" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    <option value="4">Kelainan neurogis (meningitis) enchepalitis kejang dan atau gelisah</option>
                    <option value="3">Gangguan perilaku / spikiatri</option>
                    <option value="2">Perubahan oksigenasi (diagnosis, respiratorik, asthma, syncope, dehidrasi, anemia, anoresia)</option>
                    <option value="1">Diagnosis lainnya</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Gangguan Kognitif</label>
                <select class="form-select form-select-sm" name="rj_hd_2" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    <option value="3">Belum punya kontrol diri / gelisah</option>
                    <option value="2">Lupa akan kondisi sakitnya / kadang gelisah</option>
                    <option value="1">Orientasi terhadap kemampuan diri</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Faktor Lingkungan</label>
                <select class="form-select form-select-sm" name="rj_hd_3" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    <option value="4">Riwayat jatuh / pasien ditempatkan di tempat tidur dewasa</option>
                    <option value="3">Pasien menggunakan alat bantu, ditempatkan dikursi / pangkuan</option>
                    <option value="2">Pasien diletakkan di tempat tidur bayi / khusus anak</option>
                    <option value="1">Area di luar rumah sakit</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Respon Terhadap Tindakan Operasi (Anastesi Sedasi)</label>
                <select class="form-select form-select-sm" name="rj_hd_4" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    <option value="3">Dalam 24 jam</option>
                    <option value="2">Dalam 48 jam</option>
                    <option value="1">48 jam / tidak menjalani operasi</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Penggunaan Obat</label>
                <select class="form-select form-select-sm" name="rj_hd_5" data-hd-required data-hd-score>
                    <option value="">Pilih</option>
                    <option value="3">Penggunaan multiple sedative (obat), hypnosi, barbiturate, antidrepesan, pencahar, diuretic, narcose</option>
                    <option value="2">Penggunaan obat salah satu diatas</option>
                    <option value="1">Obat lain / tidak menggunakan obat salah satu diatas</option>
                </select>
            </div>
            <input type="number" class="form-control" name="skor_rj_hd" value="0" hidden>
            <div id="skor_rj_hd" class="mb-3" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_hd">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                        <div class="fw-bold text-success" id="kategori_rj_hd"></div>
                        <small class="text-muted" id="keterangan_rj_hd"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningHumptyDumpty(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Jatuh
            </button>
        </div>
        <hr class="mt-3">
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on('change input', '[data-hd-required]', function () {
            hitungSkorHumptyDumpty($section);
        });
        $section.on('change', '#srj_hd', function () {
            if ($(this).is(':checked')) {
                // Tampilkan Humpty Dumpty
                $section.find('#tampil_srj_hd').prop('hidden', false);
            } else {
                // Sembunyikan
                $section.find('#tampil_srj_hd').prop('hidden', true);
                // Kembalikan semua nilai ke default
                resetHumptyDumpty($section);
            }
        });

        hitungSkorHumptyDumpty($section);

        getSkriningHumptyDumpty();
    })

    function hitungSkorHumptyDumpty($section) {
        const $requiredFields = $section.find('[data-hd-required]');
        const $scoreFields = $section.find('[data-hd-score]');

        // Selector selalu dibatasi di dalam #gd_perawat
        const $scoreBox = $section.find('#skor_rj_hd');
        const $nilai = $section.find('#nilai_rj_hd');
        const $kategori = $section.find('#kategori_rj_hd');
        const $keterangan = $section.find('#keterangan_rj_hd');

        const semuaTerisi = $requiredFields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '';
        });

        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        let skor = 0;

        $scoreFields.each(function () {
            skor += Number($(this).val());
        });

        const isHighRisk = skor >= 12;
        const kategori = isHighRisk
            ? 'High Risk Dumpty'
            : 'Low Humpty Dumpty';

        $nilai.text(skor);
        $section.find('input[name="skor_rj_hd"]').val(skor);

        $kategori
            .text(kategori)
            .removeClass('text-success text-danger')
            .addClass(isHighRisk ? 'text-danger' : 'text-success');

        $keterangan.text(
            isHighRisk
                ? 'Lakukan pencegahan risiko jatuh sesuai prosedur.'
                : 'Monitoring dan evaluasi risiko jatuh sesuai prosedur.'
        );

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-danger')
            .addClass(isHighRisk ? 'alert-danger' : 'alert-success');

        $scoreBox.prop('hidden', false);
    }

    function resetHumptyDumpty($section) {

        // Reset semua pilihan
        $section.find('[data-hd-required]').val('');

        // Reset nilai skor
        $section.find('[name="skor_rj_hd"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_rj_hd').text(0);

        // Sembunyikan hasil skor
        $section.find('#skor_rj_hd').prop('hidden', true);

        // Kembalikan tampilan kategori
        $section.find('#kategori_rj_hd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        $section.find('#keterangan_rj_hd').text('');

        // Kembalikan alert ke default
        $section.find('#skor_rj_hd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningHumptyDumpty() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/hd/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const hd = res.data;

                if (hd) {
                    $('#srj_hd').prop('checked', true);
                    $('#tampil_srj_hd').prop('hidden', false);
                    FormHelper.setValue($section,'rj_usia', hd.UMUR);
                    FormHelper.setValue($section,'rj_jk', hd.JENIS_KELAMIN);

                    FormHelper.setValue($section,'rj_hd_1', hd.DIAGNOSA);
                    FormHelper.setValue($section,'rj_hd_2', hd.GANGGUAN_KONGNITIF);
                    FormHelper.setValue($section,'rj_hd_3', hd.FAKTOR_LINGKUNGAN);
                    FormHelper.setValue($section,'rj_hd_4', hd.RESPON);
                    FormHelper.setValue($section,'rj_hd_5', hd.PENGGUNAAN_OBAT);
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

    function simpanSkriningHumptyDumpty(btn) {
        const $buttonSkriningHumptyDumpty = $(btn);
        const $sectionSkriningHumptyDumpty = $('#form_skrining_humpty_dumpty');

        const data = getFormDataByName($sectionSkriningHumptyDumpty, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/hd/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $buttonSkriningHumptyDumpty.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
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
                getSkriningHumptyDumpty();
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
                $buttonSkriningHumptyDumpty.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Skrining Jatuh');
            }
        });
    }
</script>
