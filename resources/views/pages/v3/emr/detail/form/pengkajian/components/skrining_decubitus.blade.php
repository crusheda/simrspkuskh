<div class="form-group" id="form_skrining_decubitus">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="s_decu">
            <label class="form-check-label ms-1" for="s_decu">
                <small class="mb-2 fw-bold">Penilaian Dekubitus</small>
            </label>
        </div>
    </div>

    <div class="row" id="tampil_s_decu" hidden>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kondisi Fisik Umum</label>
                <select class="form-select" name="decu_1" data-decu-required>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="4">Baik</option>
                    <option value="2" data-score="3">Lumayan</option>
                    <option value="3" data-score="2">Buruk</option>
                    <option value="4" data-score="1">Sangat Buruk</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Kesadaran</label>
                <select class="form-select" name="decu_2" data-decu-required>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="4">Kompos Mentis</option>
                    <option value="2" data-score="3">Apatis</option>
                    <option value="3" data-score="2">Konfus/Soporis</option>
                    <option value="4" data-score="1">Stupor/Koma</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Aktivitas</label>
                <select class="form-select" name="decu_3" data-decu-required>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="4">Dapat Berpindah</option>
                    <option value="2" data-score="3">Berjalan Dengan Bantuan</option>
                    <option value="3" data-score="2">Terbatas di Kursi</option>
                    <option value="4" data-score="1">Terbatas di Tempat Tidur</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Mobilitas</label>
                <select class="form-select" name="decu_4" data-decu-required>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="4">Bergerak Bebas</option>
                    <option value="2" data-score="3">Sedikit Terbatas</option>
                    <option value="3" data-score="2">Sangat Terbatas</option>
                    <option value="4" data-score="1">Tidak Bisa Bergerak</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Inkontinensia</label>
                <select class="form-select" name="decu_5" data-decu-required>
                    <option value="" data-score="0">Pilih</option>
                    <option value="1" data-score="4">Tidak Ngompol</option>
                    <option value="2" data-score="3">Kadang-kadang</option>
                    <option value="3" data-score="2">Sering Inkontinensia Urin</option>
                    <option value="4" data-score="1">Sering Inkontinensia Alvi dan Urin</option>
                </select>
            </div>

            <input type="number" class="form-control" name="skor_s_decu" value="0" hidden>

            <div id="skor_s_decu" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_s_decu">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Decubitus</h5>
                        <div class="fw-bold text-success" id="kategori_s_decu"></div>
                        <small class="text-muted" id="keterangan_s_decu"></small>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningDecubitus(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Decubitus
            </button>
        </div> --}}
    </div>
</div>

<script>
    var $section = $(@json($section));

    $(document).ready(function() {
        initSkriningDecubitus($section);
    });

    function initSkriningDecubitus($section) {
        $section.data('decu-initializing', true);

        $section.on('change', '[data-decu-required]', function() {
            hitungSkorDecubitus($section);

            if (!$section.data('decu-initializing')) {
                simpanSkriningDecubitus(null, $section);
            }
        });

        $section.on('change', '#s_decu', function() {
            const aktif = $(this).is(':checked');

            $section.find('#tampil_s_decu').prop('hidden', !aktif);

            if (!aktif) {
                resetDecubitus($section);

                if (!$section.data('decu-initializing')) {
                    simpanSkriningDecubitus(null, $section);
                }
            }
        });

        getSkriningDecubitus($section);
    }

    function hitungSkorDecubitus($section) {
        const $fields = $section.find('[data-decu-required]');
        const $scoreBox = $section.find('#skor_s_decu');

        let skor = 0;
        let adaNilai = false;

        $fields.each(function() {
            const $selected = $(this).find('option:selected');
            const value = $(this).val();

            if (value !== '') {
                adaNilai = true;
                skor += Number($selected.attr('data-score')) || 0;
            }
        });

        $section.find('input[name="skor_s_decu"]').val(skor);
        $section.find('#nilai_s_decu').text(skor);

        if (!adaNilai) {
            $scoreBox.prop('hidden', true);
            $section.find('#kategori_s_decu').text('');
            $section.find('#keterangan_s_decu').text('');
            return 0;
        }

        let kategori = '';
        let keterangan = '';
        let alertClass = 'alert-success';
        let textClass = 'text-success';

        if (skor <= 11) {
            kategori = 'Peningkatan Risiko';
            keterangan = 'Risiko 50x lebih besar terjadinya ulkus decubitus.';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
        } else if (skor <= 13) {
            kategori = 'Risiko Sedang';
            keterangan = 'Pasien memiliki risiko sedang terjadinya ulkus decubitus.';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
        } else if (skor === 14) {
            kategori = 'Risiko Tinggi';
            keterangan = 'Risiko tinggi terjadinya ulkus decubitus.';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
        } else {
            kategori = 'Risiko Kecil';
            keterangan = 'Risiko kecil terjadinya ulkus decubitus.';
            alertClass = 'alert-success';
            textClass = 'text-success';
        }

        $section.find('#kategori_s_decu')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_s_decu').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);

        return skor;
    }

    function resetDecubitus($section) {
        $section.find('[data-decu-required]').val('');
        $section.find('[name="skor_s_decu"]').val(0);
        $section.find('#nilai_s_decu').text(0);
        $section.find('#kategori_s_decu').text('').removeClass('text-success text-warning text-danger');
        $section.find('#keterangan_s_decu').text('');
        $section.find('#skor_s_decu').prop('hidden', true);
        $section.find('#skor_s_decu .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningDecubitus($section) {
        $section.data('decu-initializing', true);

        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/dekubitus/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                const decubitus = res.data;

                if (!decubitus) {
                    $section.find('#s_decu').prop('checked', false);
                    $section.find('#tampil_s_decu').prop('hidden', true);
                    resetDecubitus($section);
                    return;
                }

                $section.find('#s_decu').prop('checked', true);
                $section.find('#tampil_s_decu').prop('hidden', false);

                FormHelper.setValue($section, 'decu_1', decubitus.KONDISI_FISIK);
                FormHelper.setValue($section, 'decu_2', decubitus.KESADARAN);
                FormHelper.setValue($section, 'decu_3', decubitus.AKTIVITAS);
                FormHelper.setValue($section, 'decu_4', decubitus.MOBILITAS);
                FormHelper.setValue($section, 'decu_5', decubitus.INKONTINENSIA);

                hitungSkorDecubitus($section);
            },
            error: function(xhr) {
                let message = 'Gagal mengambil data Skrining Decubitus.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },
            complete: function() {
                $section.data('decu-initializing', false);
            }
        });
    }

    function simpanSkriningDecubitus(btn, section = null) {
        const $sectionSkriningDecubitus = section || $(btn).closest('#form_skrining_decubitus');

        if (!$sectionSkriningDecubitus.length) {
            console.warn('Form Skrining Decubitus tidak ditemukan.');
            return;
        }

        const $button = btn ? $(btn) : $sectionSkriningDecubitus.find('.btn-save-sub-pengkajian');

        hitungSkorDecubitus($sectionSkriningDecubitus);

        const data = getFormDataByName($sectionSkriningDecubitus, {
            NOKUNJ: kunjungan
        });

        if (btn) {
            $button.prop('disabled', true)
                .html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
        }

        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/dekubitus/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Data Skrining berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1000,
                    toast: true
                });
            },
            error: function(xhr) {
                let message = 'Data Skrining Decubitus gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                if (btn) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: message,
                        position: 'topRight'
                    });
                } else {
                    console.warn(message);
                }
            },
            complete: function() {
                if (btn) {
                    $button.prop('disabled', false)
                        .html('<i class="ri-save-line me-1"></i> Simpan Skrining Decubitus');
                }
            }
        });
    }
</script>
