<h4 class="text-danger">Tanda Vital</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <div class="form-group mb-3">
            <label class="form-label">Keadaan Umum</label>
                <textarea class="form-control" name="tv_keu" rows="1"></textarea>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">GCS (<i>Glasgow Coma Scale</i>)</label>
            <div class="d-flex align-items-center column-gap-3 row-gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label">Eye</label>
                    <input type="number" class="form-control form-control-sm" name="tv_gcs_e" min="1" max="4" style="width: 70px; flex: 0 0 60px;" placeholder="">
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label">Verbal</label>
                    <input type="number" class="form-control form-control-sm" name="tv_gcs_v" min="1" max="5" style="width: 70px; flex: 0 0 60px;" placeholder="">
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label">Move</label>
                    <input type="number" class="form-control form-control-sm" name="tv_gcs_m" min="1" max="6" style="width: 70px; flex: 0 0 60px;" placeholder="">
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <label class="form-check-label">Total</label>
                    <input type="number" class="form-control form-control-sm" name="tv_gcs_t" style="width: 70px; flex: 0 0 60px;" placeholder="" readonly>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Tekanan Darah (mmHg)</label>
            <div class="input-group">
                <input type="number" class="form-control" name="tv_td_up">
                <div class="input-group-text"> / </div>
                <input type="number" class="form-control" name="tv_td_down">
                <div class="input-group-text"> mmHg </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Frekuensi Nadi</label>
            <div class="d-flex align-items-center gap-3">
                <div class="input-group flex-grow-1">
                    <input type="number" class="form-control" name="tv_nadi">
                    <span class="input-group-text">X/menit</span>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tv_nadi_cb" value="1" checked="">
                    <label class="form-check-label">
                        Reguler
                    </label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tv_nadi_cb" value="2">
                    <label class="form-check-label">
                        Ireguler
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Frekuensi Nafas</label>
            <div class="d-flex align-items-center gap-3">
                <div class="input-group flex-grow-1">
                    <input type="number" class="form-control" name="fr">
                    <span class="input-group-text">X/menit</span>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tv_nafas_cb" value="1" checked="">
                    <label class="form-check-label">
                        Simetris
                    </label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input single-checkbox" type="checkbox" name="tv_nafas_cb" value="2">
                    <label class="form-check-label">
                        Asimetris
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Suhu</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="tv_suhu">
                        <div class="input-group-text">°C</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">SpO2</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="tv_spo2">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">BB</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="tv_bb" step="0.1" min="0">
                        <div class="input-group-text">Kg</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">TB</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="tv_tb" step="0.1" min="0">
                        <div class="input-group-text">Cm</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="gizi_imt" hidden>
            <div
                id="hasil_imt"
                class="alert alert-danger d-flex align-items-center d-none"
                role="alert"
            >
                <i class="ri-spam-line me-1"></i>
                <span id="hasil_imt_text"></span>
            </div>
        </div>
    </div>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        $section.on(
            'input',
            '[name="tv_bb"], [name="tv_tb"]',
            function () {
                hitungIMT();
            }
        );
    })

    function hitungIMT() {

        // RUMUS IMT = BB / (TB²)

        // KLASIFIKASI METODE IMT:
        //     BERAT BADAN KURANG (UNDERWEIGHT) = < 18,5
        //     BERAT BADAN NORMAL = 18,5 - 22,9
        //     KELEBIHAN BERAT BADAN (OVERWEIGHT) DENGAN RISIKO = 23 - 24,9
        //     OBESITAS I = 25 - 29,9
        //     OBESITAS II = >= 30

        const bb = parseFloat($('[name="tv_bb"]').val());
        const tbCm = parseFloat($('[name="tv_tb"]').val());

        const $hasil = $('#hasil_imt');
        const $text = $('#hasil_imt_text');

        // Jika BB atau TB belum diisi
        if (
            isNaN(bb) ||
            isNaN(tbCm) ||
            bb <= 0 ||
            tbCm <= 0
        ) {
            // Sembunyikan
            $hasil.addClass('d-none');
            $text.text('');
            return;
        }

        // Konversi TB dari cm ke meter
        const tbMeter = tbCm / 100;

        // Rumus IMT
        const imt = bb / (tbMeter * tbMeter);

        $section.find('input[name="sgd1"]:checked').val(imt);

        let kategori = '';
        let alertClass = '';
        let icon = '';

        // Klasifikasi IMT
        if (imt < 18.5) {

            kategori = 'Berat Badan Kurang (Underweight)';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';

        } else if (imt >= 18.5 && imt <= 22.9) {

            kategori = 'Berat Badan Normal';
            alertClass = 'alert-success';
            icon = 'ri-checkbox-circle-line';

        } else if (imt >= 23 && imt <= 24.9) {

            kategori = 'Kelebihan Berat Badan (Overweight) dengan Risiko';
            alertClass = 'alert-warning';
            icon = 'ri-alert-line';

        } else if (imt >= 25 && imt <= 29.9) {

            kategori = 'Obesitas I';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';

        } else {

            kategori = 'Obesitas II';
            alertClass = 'alert-danger';
            icon = 'ri-spam-line';

        }

        // Hapus class alert sebelumnya
        $hasil.removeClass(
            'alert-success alert-danger alert-warning alert-info'
        );

        // Tambahkan class sesuai kategori
        $hasil.addClass(alertClass);

        // Update icon + hasil IMT
        $hasil.html(`
            <i class="${icon} me-1"></i>
            IMT&nbsp;:&nbsp;<strong>${imt.toFixed(2)}</strong>
            &nbsp;—&nbsp;
            ${kategori}
        `);

        // Tampilkan hasil
        $hasil.removeClass('d-none');
    }
</script>
