@php
    $metodeNyeri = $metodeNyeri ?? [
        'nrs',
        'bps',
        'nips',
        'flacc',
        'vas'
    ];
@endphp
<div class="form-group" id="form_skrining_nyeri">
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <div class="row align-items-center">
                <label class="col-md-4 col-form-label fw-bold">Nyeri</label>
                <div class="col-md-8">
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_nyeri" value="0" checked>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row align-items-center">
                <label class="col-md-2 col-form-label fw-bold">Onset</label>
                <div class="col-md-10">
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input single-checkbox" type="checkbox" name="sn_onset" value="1">
                            <label class="form-check-label" for="onsetAkut">Akut</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input single-checkbox" type="checkbox"name="sn_onset" value="2">
                            <label class="form-check-label" for="onsetKronis">Kronis</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <div class="row align-items-center">
                <label class="col-md-4 col-form-label fw-bold">Skala Nyeri</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" name="sn_skala" placeholder="Otomatis terisi" value="0" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row align-items-center">
                <label class="col-md-2 col-form-label fw-bold">Metode</label>
                <div class="col-md-10">
                    <div class="d-flex gap-4">
                        @if(in_array('nrs', $metodeNyeri))
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="1">
                                <label class="form-check-label">NRS</label>
                            </div>
                        @endif
                        @if(in_array('bps', $metodeNyeri))
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="2">
                                <label class="form-check-label">BPS</label>
                            </div>
                        @endif
                        @if(in_array('nips', $metodeNyeri))
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="3">
                                <label class="form-check-label">NIPS</label>
                            </div>
                        @endif
                        @if(in_array('flacc', $metodeNyeri))
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="4">
                                <label class="form-check-label">FLACC</label>
                            </div>
                        @endif
                        @if(in_array('vas', $metodeNyeri))
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="sn_metode" value="5">
                                <label class="form-check-label">VAS</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">

        @if(in_array('nrs', $metodeNyeri))
        <div id="tampil_sn_nrs" hidden>
            <img src="{{ asset('images/erm/skrining_nyeri_nrs.png') }}"
                alt="Indikator Penilaian Nyeri"
                class="img-fluid mb-3"
                style="width: 25rem;">
            <div class="form-group">
                <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                <input type="range" class="form-range" min="0" max="10" step="1" value="0" name="sn_nrs" id="sn_nrs">
            </div>
            <div class="d-flex align-items-center gap-3">
                <span>Interpretasi Skor Nyeri VAS :</span>
                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                    <li>0 = tidak nyeri</li>
                    <li>1-3 = nyeri ringan</li>
                    <li>4-6 = nyeri sedang</li>
                    <li>7-10 = nyeri berat</li>
                </ul>
            </div>
        </div>
        @endif

        @if(in_array('bps', $metodeNyeri))
        <div id="tampil_sn_bps" hidden>
            <div class="table-responsive">
                <table class="table table-bordered table-display">
                    <thead class="text-uppercase">
                        <tr class="table-light">
                            <th class="text-center">Indikator Penilaian</th>
                            <th class="text-center">Keterangan Skor</th>
                            <th class="text-center">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Ekspresi Wajah</th>
                            <td>
                                <h6>1 = rileks</h6>
                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_bps_1" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Ekspresi Wajah</th>
                            <td>
                                <h6>1 = rileks</h6>
                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_bps_2" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Ekspresi Wajah</th>
                            <td>
                                <h6>1 = rileks</h6>
                                <h6>2 = sebagian tegang (misal: dahi mengkerut)</h6>
                                <h6>3 = tegang penuh (misal: kelopak mata menutup rapat)</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_bps_3" class="form-control form-control-sm mx-auto" min="1" max="3" style="width: 100px"></td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3">
                                <div class="d-flex align-items-center gap-3">
                                    <span>Interpretasi Skor BPS :</span>
                                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                        <li>3 = tidak nyeri</li>
                                        <li>4-6 = nyeri ringan</li>
                                        <li>7-9 = nyeri sedang</li>
                                        <li>10-12 = nyeri berat</li>
                                    </ul>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        @if(in_array('nips', $metodeNyeri))
        <div id="tampil_sn_nips" hidden>
            <div class="table-responsive">
                <table class="table table-bordered table-display">
                    <thead class="text-uppercase">
                        <tr class="table-light">
                            <th class="text-center">Indikator Penilaian</th>
                            <th class="text-center">Keterangan Skor</th>
                            <th class="text-center">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Ekspresi Wajah</th>
                            <td>
                                <h6>0 = Relaksasi</h6>
                                <h6>1 = Meringis</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_1" class="form-control form-control-sm mx-auto"  min="0" max="1" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Tangisan</th>
                            <td>
                                <h6>0 = Tidak menangis</h6>
                                <h6>1 = Meringis</h6>
                                <h6>2 = Menangis kuat</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Gerakan Lengan</th>
                            <td>
                                <h6>0 = Relaksasi</h6>
                                <h6>1 = Fleksi / ekstensi</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_3" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Gerakan Tungkai</th>
                            <td>
                                <h6>0 = relaksasi</h6>
                                <h6>1 = Fleksi / ekstensi</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_4" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Status Terjaga</th>
                            <td>
                                <h6>0 = Tidur / bangun</h6>
                                <h6>1 = Rewel</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_5" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Pola Nafas</th>
                            <td>
                                <h6>0 = Relaksasi</h6>
                                <h6>1 = Perubahan pola nafas</h6>
                            </td>
                            <td class="text-center"><input type="number" name="sn_nips_6" class="form-control form-control-sm mx-auto" min="0" max="1" style="width: 100px"></td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3">
                                <div class="d-flex align-items-center gap-3">
                                    <span>Keterangan :</span>
                                    <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                                        <li>> 3 = Nyeri</li>
                                        <li>≤ 3 = Tidak Nyeri</li>
                                    </ul>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        @if(in_array('flacc', $metodeNyeri))
        <div id="tampil_sn_flacc" hidden>
            <div class="table-responsive">
                <table class="table table-bordered table-display">
                    <thead class="text-uppercase">
                        <tr class="table-light">
                            <th class="text-center">Indikator</th>
                            <th class="text-center">0</th>
                            <th class="text-center">1</th>
                            <th class="text-center">2</th>
                            <th class="text-center">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Wajah</th>
                            <td>Tersenyum / tidak ada ekspresi khusus</td>
                            <td>Terkadang meringis / menarik diri</td>
                            <td>Sering menggetarkan dagu dan mengatupkan rahang</td>
                            <td class="text-center"><input type="number" name="sn_flacc_1" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Kaki</th>
                            <td>Gerakan normal / relaksasi</td>
                            <td>Tidak tenang</td>
                            <td>Kaki dibuat menendang / menarik diri</td>
                            <td class="text-center"><input type="number" name="sn_flacc_2" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Aktivitas</th>
                            <td>Tidur, posisi normal mudah bergerak</td>
                            <td>Gerakan menggeliat, berguling, kaku</td>
                            <td>Melengkungkan punggung / kaku / menghentak</td>
                            <td class="text-center"><input type="number" name="sn_flacc_3" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Menangis</th>
                            <td>Tidak menangis (bangung / tidur)</td>
                            <td>Mengerang, merengek-rengek</td>
                            <td>Menangis terus-menerus, terisak, menjerit</td>
                            <td class="text-center"><input type="number" name="sn_flacc_4" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                        <tr>
                            <th>Bersuara</th>
                            <td>Bersuara normal, tenang</td>
                            <td>Tenang bila dipeluk, digendong, atau diajak bicara</td>
                            <td>Sulit untuk ditenangkan</td>
                            <td class="text-center"><input type="number" name="sn_flacc_5" class="form-control form-control-sm mx-auto" min="0" max="2" style="width: 100px"></td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5">Interpretasi : Skor total dari lima parameter di atas menentukan tingkat keparahan nyeri dengan skala 0 - 10. Nilai 10 menunjukan tingkat nyei yang hebat.</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        @if(in_array('vas', $metodeNyeri))
        <div id="tampil_sn_vas" hidden>
            <img src="{{ asset('images/erm/skrining_nyeri_vas.png') }}"
                alt="Indikator Penilaian Nyeri"
                class="img-fluid mb-3"
                style="width: 25rem;">
            <div class="form-group">
                <label class="form-label">Geser untuk memilih Skor Nyeri</label>
                <input type="range" class="form-range" min="0" max="10" step="1" value="0" name="sn_vas" id="sn_vas">
            </div>
            <div class="d-flex align-items-center gap-3">
                <span>Interpretasi Skor Nyeri VAS :</span>
                <ul class="d-flex gap-3 mb-0 ps-0 list-unstyled">
                    <li>0 = tidak nyeri</li>
                    <li>1-3 = nyeri ringan</li>
                    <li>4-6 = nyeri sedang</li>
                    <li>7-10 = nyeri berat</li>
                </ul>
            </div>
        </div>
        @endif

    </div>
    <div class="row align-items-center">
        <label class="col-md-2 col-form-label fw-bold">Pencetus</label>
        <div class="col-md-10">
            <input type="text" class="form-control form-control-sm" name="sn_pencetus" placeholder="[ Pencetus ]">
        </div>
    </div>
    <div class="row align-items-center">
        <label class="col-md-2 col-form-label fw-bold">Gambaran</label>
        <div class="col-md-10">
            <input type="text" class="form-control form-control-sm" name="sn_gambaran" placeholder="[ Gambaran ]">
        </div>
    </div>
    <div class="row align-items-center">
        <label class="col-md-2 col-form-label fw-bold">Durasi</label>
        <div class="col-md-10">
            <input type="text" class="form-control form-control-sm" name="sn_durasi" placeholder="[ Durasi ]">
        </div>
    </div>
    <div class="row align-items-center">
        <label class="col-md-2 col-form-label fw-bold">Lokasi</label>
        <div class="col-md-10">
            <input type="text" class="form-control form-control-sm" name="sn_lokasi" placeholder="[ Lokasi ]">
        </div>
    </div>

    <button class="btn btn-primary float-end mt-2 btn-save-sub-pengkajian" onclick="simpanSkriningNyeri(this)">
        <i class="ri-save-line me-1"></i> Simpan Skrining Nyeri
    </button>
</div>

<script>

    var $section = $(@json($section));

    $(document).ready(function() {
        const metodeMap = {
            1: '#tampil_sn_nrs',
            2: '#tampil_sn_bps',
            3: '#tampil_sn_nips',
            4: '#tampil_sn_flacc',
            5: '#tampil_sn_vas'
        };

        // ==========================================
        // PILIH METODE
        // ==========================================
        $section.on('change', 'input[name="sn_metode"]', function () {

            const $this = $(this);
            const metode = $this.val();

            // ==========================================
            // JIKA METODE DIPILIH
            // ==========================================
            if ($this.is(':checked')) {

                // Pastikan hanya satu metode yang aktif
                $section
                    .find('input[name="sn_metode"]')
                    .not($this)
                    .prop('checked', false);

                // Reset skor metode sebelumnya
                resetSemuaSkor();

                // Hide semua metode
                $.each(metodeMap, function (key, selector) {
                    $section.find(selector).prop('hidden', true);
                });

                // Tampilkan metode yang dipilih
                if (metodeMap[metode]) {
                    $section.find(metodeMap[metode]).prop('hidden', false);
                }

            } else {

                // ==========================================
                // JIKA METODE DIBATALKAN
                // ==========================================

                // Hide semua
                $.each(metodeMap, function (key, selector) {
                    $(selector).prop('hidden', true);
                });

                // Reset semua skor dan sn_skala kembali 0
                resetSemuaSkor();
            }
        });

        // ==========================================
        // KONDISI AWAL
        // ==========================================
        $.each(metodeMap, function (key, selector) {
            $(selector).prop('hidden', true);
        });

        resetSemuaSkor();

        // ==========================================
        // NRS
        // ==========================================
        $section.on('input', '#sn_nrs', function () {
            hitungSkorNyeri('1');
        });

        // ==========================================
        // BPS
        // ==========================================
        $section.on(
            'input',
            '#tampil_sn_bps input[type="number"]',
            function () {
                hitungSkorNyeri('2');
            }
        );

        // ==========================================
        // NIPS
        // ==========================================
        $section.on(
            'input',
            '#tampil_sn_nips input[type="number"]',
            function () {
                hitungSkorNyeri('3');
            }
        );

        // ==========================================
        // FLACC
        // ==========================================
        $section.on(
            'input',
            '#tampil_sn_flacc input[type="number"]',
            function () {
                hitungSkorNyeri('4');
            }
        );

        // ==========================================
        // VAS
        // ==========================================
        $section.on('input', '#sn_vas', function () {
            hitungSkorNyeri('5');
        });

        getSkriningNyeri();
    })

    function hitungSkorNyeri(metode) {

        let total = 0;

        switch (metode) {

            // NRS
            case '1':
                total = FormHelper.setValidNumber($section, 'sn_nrs');
                break;

            // BPS
            case '2':
                $section
                    .find('#tampil_sn_bps input[type="number"]')
                    .each(function () {

                        total += FormHelper.setValidNumber(
                            $section,
                            $(this).attr('name')
                        );

                    });
                break;

            // NIPS
            case '3':
                $section
                    .find('#tampil_sn_nips input[type="number"]')
                    .each(function () {

                        total += FormHelper.setValidNumber(
                            $section,
                            $(this).attr('name')
                        );

                    });
                break;

            // FLACC
            case '4':
                $section
                    .find('#tampil_sn_flacc input[type="number"]')
                    .each(function () {

                        total += FormHelper.setValidNumber(
                            $section,
                            $(this).attr('name')
                        );

                    });
                break;

            // VAS
            case '5':
                total = FormHelper.setValidNumber($section, 'sn_vas');
                break;
        }

        $section
            .find('input[name="sn_skala"]')
            .val(total);
    }

    function resetSemuaSkor() {

        // Reset NRS dan VAS
        $section
            .find('#sn_nrs, #sn_vas')
            .val(0);

        // Reset BPS, NIPS, FLACC
        $section
            .find(
                '#tampil_sn_bps input[type="number"], ' +
                '#tampil_sn_nips input[type="number"], ' +
                '#tampil_sn_flacc input[type="number"]'
            )
            .val('');

        // Reset skala
        $section
            .find('input[name="sn_skala"]')
            .val(0);
    }

    function getSkriningNyeri() {
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/nyeri/${kunjungan}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (res) {

                const nyeri = res.data;
                // ==========================================
                // PENILAIAN NYERI
                // ==========================================
                const metodeMapSkriningNyeri = {
                    1: '#tampil_sn_nrs',
                    2: '#tampil_sn_bps',
                    3: '#tampil_sn_nips',
                    4: '#tampil_sn_flacc',
                    5: '#tampil_sn_vas'
                };

                if (nyeri) {
                    FormHelper.setSingleCheckbox($section,'sn_nyeri', nyeri.NYERI);
                    FormHelper.setSingleCheckbox($section,'sn_onset', nyeri.ONSET);
                    FormHelper.setSingleCheckbox($section,'sn_metode', nyeri.METODE);
                    FormHelper.setValue($section,'sn_pencetus', nyeri.PENCETUS);
                    FormHelper.setValue($section,'sn_gambaran', nyeri.GAMBARAN);
                    FormHelper.setValue($section,'sn_durasi', nyeri.DURASI);
                    FormHelper.setValue($section,'sn_lokasi', nyeri.LOKASI);

                    // $(metodeMapSkriningNyeri[metode]).prop('hidden', false);
                    FormHelper.setValue($section,'sn_skala', nyeri.SKALA);

                    if (nyeri.METODE === 1 || nyeri.METODE === 5) {
                        FormHelper.setValue($section, 'sn_nrs', nyeri.SKALA);
                    } else if (nyeri.METODE === 2) {
                        FormHelper.setValue($section, 'sn_bps_1', nyeri.SKOR1);
                        FormHelper.setValue($section, 'sn_bps_2', nyeri.SKOR2);
                        FormHelper.setValue($section, 'sn_bps_3', nyeri.SKOR3);
                    } else if (nyeri.METODE === 3) {
                        FormHelper.setValue($section, 'sn_nips_1', nyeri.SKOR1);
                        FormHelper.setValue($section, 'sn_nips_2', nyeri.SKOR2);
                        FormHelper.setValue($section, 'sn_nips_3', nyeri.SKOR3);
                        FormHelper.setValue($section, 'sn_nips_4', nyeri.SKOR4);
                        FormHelper.setValue($section, 'sn_nips_5', nyeri.SKOR5);
                        FormHelper.setValue($section, 'sn_nips_6', nyeri.SKOR6);
                    } else if (nyeri.METODE === 4) {
                        FormHelper.setValue($section, 'sn_flacc_1', nyeri.SKOR1);
                        FormHelper.setValue($section, 'sn_flacc_2', nyeri.SKOR2);
                        FormHelper.setValue($section, 'sn_flacc_3', nyeri.SKOR3);
                        FormHelper.setValue($section, 'sn_flacc_4', nyeri.SKOR4);
                        FormHelper.setValue($section, 'sn_flacc_5', nyeri.SKOR5);
                    } else if (nyeri.METODE === 5) {
                        FormHelper.setValue($section, 'sn_vas', nyeri.SKALA);
                    } else {}
                }
            },
            error: function (xhr, status, error) {
                console.error(
                    'Error getDataPengkajianGdD:',
                    xhr.responseText || error
                );
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

    function simpanSkriningNyeri(btn) {
        const $buttonSkriningNyeri = $(btn);
        const $sectionSkriningNyeri = $('#form_skrining_nyeri');

        const data = getFormDataByName($sectionSkriningNyeri, {
            NOKUNJ: kunjungan
        });
        $.ajax({
            url: `/api/v2/emr/pengkajian/skrining/nyeri/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $buttonSkriningNyeri.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
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
                getSkriningNyeri();
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
                $buttonSkriningNyeri.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Skrining Nyeri');
            }
        });
    }
</script>
