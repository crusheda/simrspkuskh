<div class="form-group" id="form_skrining_epfra">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="srj_epfra">
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">Penilaian Edmonson Psychiatric Fall Risk Assessment ( EPFRA )</small>
            </label>
        </div>
    </div>
    <div class="row" id="tampil_srj_epfra" hidden>
        <div class="col-md-6 mb-3">
            <label class="form-label">Usia Pasien</label>
            <div class="input-group input-group-sm flex-grow-1">
                <select class="form-select form-select-sm" name="rj_epfra_usia" data-epfra-required data-epfra-score>
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
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_1" data-epfra-required data-epfra-score>
                    <option value="" data-score="0" selected>Pilih</option>
                    <option value="1" data-score="4">Sadar penuh dan orientasi waktu baik</option>
                    <option value="2" data-score="13">Agitasi / Cemas</option>
                    <option value="3" data-score="12">Sering bingung</option>
                    <option value="4" data-score="14">Bingung dan disorientasi</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Eliminasi</label>
                <select class="form-select form-select-sm" name="rj_epfra_2" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="8">Mandiri untuk BAB dan BAK</option>
                    <option value="2" data-score="12">Memakai Kateter / Ostomy</option>
                    <option value="3" data-score="10">BAB dan BAK dengan bantuan</option>
                    <option value="4" data-score="12">Gangguan eliminasi (inkontinensia, banyak BAK di malam hari, sering BAB danBAK)</option>
                    <option value="5" data-score="12">Inkontinensia tetapi bisa ambulasi mandiri</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Medikasi</label>
                <select class="form-select form-select-sm" name="rj_epfra_3" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="10">Tidak ada pengobatan yang diberikan</option>
                    <option value="2" data-score="10">Obat-obatan jantung</option>
                    <option value="3" data-score="8">Obat psikiatri termasuk benzodiazepin dan anti depresan</option>
                    <option value="4" data-score="12">Meningkatnya dosis obat yang dikonsumsi / ditambahkan dalam 24 jam terakhir</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_4" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="10">Bipolar / gangguan scizo affective</option>
                    <option value="2" data-score="8">Penyalahgunaan zat terlarang dan alkohol</option>
                    <option value="3" data-score="10">Gangguan depresi mayor</option>
                    <option value="4" data-score="12">Dimensia / Delirium</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_5" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="7">Ambulasi mandiri dan langkah stabil atau pasien imobil</option>
                    <option value="2" data-score="8">Penggunaan alat bantu yang tepat (tongkat, walker, tripod, dll)</option>
                    <option value="3" data-score="10">Vertigo / Hipotensi Ortostatik / Kelemahan</option>
                    <option value="4" data-score="8">Langkah tidak stabil, butuh bantuan dan menyadari kemampuannya</option>
                    <option value="5" data-score="15">Langkah tidak stabil, butuh bantuan dan tidak menyadari ketidakmampuannya</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_6" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="12">Hanya sedikit mendapatkan asupan makanan / minum dalam 24 jam terakhir</option>
                    <option value="2" data-score="0">Nafsu makan baik</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_7" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="8">Tidak ada gangguan tidur</option>
                    <option value="2" data-score="12">Ada gangguan tidur yang dilaporkan keluarga pasien / staf</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Status Mental</label>
                <select class="form-select form-select-sm" name="rj_epfra_8" data-epfra-required data-epfra-score>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="8">Tidak ada riwayat jatuh</option>
                    <option value="2" data-score="14">Ada riwayat jatuh dalam 3 bulan terakhir</option>
                </select>
            </div>
            <input type="number" class="form-control" name="skor_rj_epfra" value="0" hidden>
            <div id="skor_rj_epfra" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_rj_epfra">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Risiko Jatuh</h5>
                        <div class="fw-bold text-success" id="kategori_rj_epfra"></div>
                        <small class="text-muted" id="keterangan_rj_epfra"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningEPFRA(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Jatuh
            </button>
        </div>
        <hr class="mt-3">
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on('change', '[data-epfra-required]', function () {
            hitungSkorEPFRA($section);
        });
        $section.on('change', '#srj_epfra', function () {
            if ($(this).is(':checked')) {
                $section.find('#tampil_srj_epfra') .prop('hidden', false);
            } else {
                $section.find('#tampil_srj_epfra') .prop('hidden', true);
                resetEPFRA($section);
            }
        });

        hitungSkorEPFRA($section);

        getSkriningEPFRA();
    })

    function hitungSkorEPFRA($section) {

        const $fields = $section.find('[data-epfra-required]');
        const $scoreBox = $section.find('#skor_rj_epfra');

        // Semua field harus sudah dipilih
        const semuaTerisi = $fields.toArray().every(function (field) {
            return $.trim($(field).val()) !== '' && $(field).val() !== '0';
        });

        // Jika belum lengkap, sembunyikan hasil
        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);

            // Reset skor
            $section.find('input[name="skor_rj_epfra"]').val(0);
            $section.find('#nilai_rj_epfra').text(0);

            return;
        }

        // Hitung skor dari data-score option yang dipilih
        let skor = 0;

        $fields.each(function () {

            const score = parseInt(
                $(this).find('option:selected').attr('data-score')
            ) || 0;

            skor += score;
        });

        // Simpan skor
        $section
            .find('input[name="skor_rj_epfra"]')
            .val(skor);

        // ==========================================
        // KATEGORI
        // Sesuaikan batas dengan SOP EPFRAS Anda
        // ==========================================

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 50) {

            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Lakukan pencegahan risiko jatuh risiko tinggi.';

        } else if (skor >= 30) {

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

        // ==========================================
        // TAMPILKAN HASIL
        // ==========================================

        $section
            .find('#nilai_rj_epfra')
            .text(skor);

        $section
            .find('#kategori_rj_epfra')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section
            .find('#keterangan_rj_epfra')
            .text(keterangan);

        $scoreBox
            .find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetEPFRA($section) {

        // Reset semua pilihan EPFRA
        $section.find('[data-epfra-required]').each(function () {
            $(this).val('');
        });

        // Reset skor hidden
        $section.find('[name="skor_rj_epfra"]').val(0);

        // Reset nilai skor
        $section.find('#nilai_rj_epfra').text(0);

        // Reset kategori
        $section.find('#kategori_rj_epfra')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_rj_epfra').text('');

        // Sembunyikan kotak hasil
        $section.find('#skor_rj_epfra').prop('hidden', true);

        // Kembalikan warna alert ke default
        $section.find('#skor_rj_epfra .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningEPFRA() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/epfra/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const epfra = res.data;

                if (epfra) {
                    $section.find('#srj_epfra').prop('checked', true);
                    $section.find('#tampil_srj_epfra').prop('hidden', false);
                    FormHelper.setValue($section,'rj_epfra_usia', epfra.USIA);

                    FormHelper.setValue($section,
                        'rj_epfra_1',
                        epfra.STATUS_MENTAL
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_2',
                        epfra.ELIMINASI
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_3',
                        epfra.MEDIKASI
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_4',
                        epfra.DIAGNOSIS
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_5',
                        epfra.AMBULASI
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_6',
                        epfra.NUTRISI
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_7',
                        epfra.GANGGUAN_TIDUR
                    );

                    FormHelper.setValue($section,
                        'rj_epfra_8',
                        epfra.RIWAYAT_JATUH
                    );
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

    function simpanSkriningEPFRA(btn) {
        const $buttonSkriningEPFRA = $(btn);
        const $sectionSkriningEPFRA = $('#form_skrining_epfra');

        const data = getFormDataByName($sectionSkriningEPFRA, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/resikojatuh/epfra/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $buttonSkriningEPFRA.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },
            success: function (res) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Data Skrining berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1000,
                    toast: true
                });
                getSkriningEPFRA();
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
                $buttonSkriningEPFRA.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Skrining Jatuh');
            }
        });
    }
</script>
