<div class="form-group" id="form_skrining_strong_kid">
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input class="form-check-input check-primary" type="checkbox" id="sg_sk">
            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">Assesmen Nutrisi Pasien Anak (STRONG KID)</small>
            </label>
        </div>
    </div>
    <div class="row" id="tampil_sg_sk" hidden>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <h6>Apakah pasien tampak kurus</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga1" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga1" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Apakah terjadi mengalami penurunan berat badan selama 1 bulan terakhir</h6>
                <ul>
                    <li>Berdasarkan penilaian obyektif data BB atau penilaian subyektif orang tua pasien</li>
                    <li>Untuk bayi < 1 tahun, BB tidak baik selama 3 bulan terakhir</li>
                </ul>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga2" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga2" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Apakah terdapat salah satu dari kondisi berikut</h6>
                <ul>
                    <li>Diare > 5 kali /hari dan atau muntah > 3 kali /hari dalam seminggu terakhir</li>
                    <li>Asupan makanan berkurang selama seminggu terakhir</li>
                </ul>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga3" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga3" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <h6>Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien beresiko mengalami malnutrisi</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga4" value="0">
                        <label class="form-check-label">
                            Tidak
                        </label>
                    </div>
                    <div class="form-check mb-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="sga4" value="1">
                        <label class="form-check-label">
                            Ya
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <input type="number" class="form-control" name="skor_sga" value="0" hidden>
            <div id="skor_sga" hidden>
                <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                    <div class="me-4 flex-shrink-0">
                        <h1 class="display-1 fw-bold mb-0" id="nilai_sga">0</h1>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">Skor Skrining Gizi</h5>
                        <div class="fw-bold text-success" id="kategori_sga"></div>
                        <small class="text-muted" id="keterangan_sga"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-save-sub-pengkajian" onclick="simpanSkriningStrongKid(this)">
                <i class="ri-save-line me-1"></i> Simpan Skrining Gizi
            </button>
        </div>
        <hr class="mt-3">
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on('change', 'input[name="sga1"], input[name="sga2"], input[name="sga3"], input[name="sga4"]', function () {
            hitungSkorStrongKid($section);
        });
        $section.on('change', '#sg_sk', function () {
            if ($(this).is(':checked')) {
                // Tampilkan STRONG KID
                $section.find('#tampil_sg_sk').prop('hidden', false);
            } else {
                // Sembunyikan STRONG KID
                $section.find('#tampil_sg_sk').prop('hidden', true);
                // Reset data STRONG KID
                resetSkriningStrongKid($section);
            }
        });

        hitungSkorStrongKid($section);

        getSkriningStrongKid();
    })

    function hitungSkorStrongKid($section) {
        const fieldNames = ['sga1', 'sga2', 'sga3', 'sga4'];
        const $scoreBox = $section.find('#skor_sga');

        const nilai = fieldNames.map(function (name) {
            return $section.find('input[name="' + name + '"]:checked').val();
        });

        // Skor hanya tampil jika keempat pertanyaan sudah dijawab
        if (nilai.some(function (value) {
            return value === undefined;
        })) {
            $scoreBox.prop('hidden', true);
            return;
        }

        const skor = nilai.reduce(function (total, value) {
            return total + Number(value);
        }, 0);

        $section.find('input[name="skor_sga"]').val(skor);

        let kategori;
        let alertClass;
        let textClass;
        let keterangan;

        if (skor >= 3) {
            kategori = 'Risiko Tinggi';
            alertClass = 'alert-danger';
            textClass = 'text-danger';
            keterangan = 'Perlu asesmen dan tindak lanjut gizi lebih lanjut.';
        } else if (skor === 2) {
            kategori = 'Risiko Sedang';
            alertClass = 'alert-warning';
            textClass = 'text-warning';
            keterangan = 'Lakukan monitoring status gizi sesuai prosedur.';
        } else {
            // Skor 0 atau 1
            kategori = 'Tidak Berisiko';
            alertClass = 'alert-success';
            textClass = 'text-success';
            keterangan = 'Monitoring dan evaluasi berkala.';
        }

        $section.find('#nilai_sga').text(skor);

        $section.find('#kategori_sga')
            .text(kategori)
            .removeClass('text-success text-warning text-danger')
            .addClass(textClass);

        $section.find('#keterangan_sga').text(keterangan);

        $scoreBox.find('.alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass(alertClass);

        $scoreBox.prop('hidden', false);
    }

    function resetSkriningStrongKid($section) {
        // Reset checkbox
        $section.find('input[name="sga1"]').prop('checked', false);
        $section.find('input[name="sga2"]').prop('checked', false);
        $section.find('input[name="sga3"]').prop('checked', false);
        $section.find('input[name="sga4"]').prop('checked', false);

        // Reset skor
        $section.find('[name="skor_sga"]').val(0);

        // Reset tampilan skor
        $section.find('#nilai_sga').text(0);

        // Reset kategori
        $section.find('#kategori_sga')
            .text('')
            .removeClass('text-success text-warning text-danger');

        // Reset keterangan
        $section.find('#keterangan_sga').text('');

        // Sembunyikan hasil skor
        $section.find('#skor_sga').prop('hidden', true);

        // Reset alert
        $section.find('#skor_sga .alert')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-success');
    }

    function getSkriningStrongKid() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/gizi/strongkid/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const strongKid = res.data;

                if (strongKid) {
                    $section.find('#sg_sk').prop('checked', true);
                    $section.find('#tampil_sg_sk').prop('hidden', false);

                    FormHelper.setSingleCheckbox($section,
                        'sga1',
                        strongKid.TAMPAK_KURUS
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sga2',
                        strongKid.PENURUNAN_BERAT_BADAN
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sga3',
                        strongKid.DIARE_INTAKE_MAKANAN
                    );

                    FormHelper.setSingleCheckbox($section,
                        'sga4',
                        strongKid.RESIKO_MALNUTRISI
                    );

                    FormHelper.setValue($section,
                        'skor_sga',
                        strongKid.SKOR
                    );
                } else {
                    $section.find('#sg_sk').prop('checked', false);
                    $section.find('#tampil_sg_sk').prop('hidden', true);
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

    function simpanSkriningStrongKid(btn) {
        const $buttonSkriningStrongKid = $(btn);
        const $sectionSkriningStrongKid = $('#form_skrining_strong_kid');

        const data = getFormDataByName($sectionSkriningStrongKid, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/gizi/strongkid/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $buttonSkriningStrongKid.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
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
                $buttonSkriningStrongKid.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Skrining Gizi');
            }
        });
    }
</script>
