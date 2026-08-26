<div class="form-group" id="form_skrining_must">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="sg_must">
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">Assesmen Nutrisi Pasien Dewasa (Mainutrition Screening Tool / MUST)</small>
            </label>
        </div>
    </div>
    <div class="row" id="tampil_sg_must" hidden>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <h6>Adakah perubahan berat badan signifikan dalam 3 bualn terakhir</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Jumlah perubahan berat badan</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="1">
                        <label class="form-check-label">
                            0,5 - 5 kg
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="2">
                        <label class="form-check-label">
                            > 5 - 10 kg
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="3">
                        <label class="form-check-label">
                            > 10 - 15 kg
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd1_c" value="4">
                        <label class="form-check-label">
                            > 15 kg
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Intake makanan kurang karena tidak ada nafsu makan</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd2" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sgd2" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Kondisi Khusus</h6>
                <input type="text" class="form-control form-control-sm" name="sgd3">
            </div>
        </div>
        <div class="col-md-6">
            <input type="number" class="form-control" name="skor_sgd" value="0" hidden>
            <div id="skor_sgd" class="mb-3" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_sgd">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Skrining Gizi</h5>
                        <div class="fw-bold text-success" id="kategori_sgd">Risiko Ringan</div>
                        <small class="text-muted" id="keterangan_sgd"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningMust(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Gizi
            </button>
        </div>
        <hr class="mt-3">
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on('change input', '[name="sgd1"], [name="sgd1_c"], [name="sgd2"]', function () {
            hitungSkorMUST($section);
        });
        $section.on('change', '#sg_must', function () {
            if ($(this).is(':checked')) {
                // Tampilkan MUST
                $section.find('#tampil_sg_must').prop('hidden', false);
            } else {
                // Sembunyikan MUST
                $section.find('#tampil_sg_must').prop('hidden', true);
                // Reset data MUST
                resetSkriningMUST($section);
            }
        });

        hitungSkorMUST($section);

        getSkriningMust();
    })

    function hitungSkorMUST($section) {
        const sgd1 = $section.find('input[name="sgd1"]:checked').val();
        const sgd1c = $section.find('input[name="sgd1_c"]:checked').val();
        const sgd2 = $section.find('input[name="sgd2"]:checked').val();
        const sgd3 = $.trim($section.find('[name="sgd3"]').val());

        const $scoreBox = $section.find('#skor_sgd');

        // Jumlah perubahan berat badan hanya wajib bila sgd1 = Ya
        const jumlahBeratBadanWajib = sgd1 === '1';

        const semuaTerisi =
            sgd1 !== undefined &&
            sgd2 !== undefined &&
            // sgd3 !== '' &&
            (!jumlahBeratBadanWajib || sgd1c !== undefined);

        if (!semuaTerisi) {
            $scoreBox.prop('hidden', true);
            return;
        }

        // Jika tidak ada perubahan berat badan, sgd1_c tidak dihitung.
        const skor =
            Number(sgd1) +
            Number(sgd2) +
            (jumlahBeratBadanWajib ? Number(sgd1c) : 0);

        // Simpan skor ke input agar ikut terkirim saat AJAX
        $section.find('input[name="skor_sgd"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 4) {
            kategori = 'Risiko Berat';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Perlu tindak lanjut skrining gizi sesuai prosedur.';
        } else if (skor >= 2) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan monitoring dan evaluasi status gizi.';
        } else {
            kategori = 'Risiko Ringan';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi berkala.';
        }

        $section.find('#nilai_sgd').text(skor);

        $section.find('#kategori_sgd')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_sgd').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningMUST($section) {

        // Reset checkbox
        $section.find('input[name="sgd1"]').prop('checked', false);
        $section.find('input[name="sgd1_c"]').prop('checked', false);
        $section.find('input[name="sgd2"]').prop('checked', false);

        // Reset input
        $section.find('[name="sgd3"]').val('');

        // Reset skor
        $section.find('[name="skor_sgd"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_sgd').text(0);

        // Reset kategori
        $section.find('#kategori_sgd')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_sgd').text('');

        // Sembunyikan hasil skor
        $section.find('#skor_sgd').prop('hidden', true);

        // Reset alert
        $section.find('#skor_sgd .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningMust() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/gizi/must/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const must = res.data;

                if (must) {
                    $section.find('#sg_must').prop('checked', true);
                    $section.find('#tampil_sg_must').prop('hidden', false);

                    FormHelper.setSingleCheckbox($section,
                        'sgd1',
                        must.BERAT_BADAN_SIGNIFIKAN
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sgd1_c',
                        must.PERUBAHAN_BERAT_BADAN
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sgd2',
                        must.INTAKE_MAKANAN
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sgd3',
                        must.KONDISI_KHUSUS
                    );

                    FormHelper.setValue($section,
                        'skor_sgd',
                        must.SKOR
                    );
                } else {
                    $section.find('#sg_must').prop('checked', false);
                    $section.find('#tampil_sg_must').prop('hidden', true);
                }
            },
            error: function (xhr, status, error) {
                let message = 'Gagal mengambil data Pengkajian Medis.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.warn(message);
            },
            complete: function () {
            }
        });
    }

    function simpanSkriningMust(btn) {
        const $buttonSkriningEPFRA = $(btn);
        const $sectionSkriningEPFRA = $('#form_skrining_must');

        const data = getFormDataByName($sectionSkriningEPFRA, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/gizi/must/${kunjungan}/simpan`,
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
