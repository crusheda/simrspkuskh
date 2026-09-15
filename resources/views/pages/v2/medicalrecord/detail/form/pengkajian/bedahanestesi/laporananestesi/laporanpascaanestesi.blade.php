<div class="form-wrapper" id="form_laporan_pascaanestesi">
    <h1 class="display-6 mb-1 mt-2 fs-23 fw-medium text-center">
        Instruksi <b class="text-success">PASCA ANESTESI</b>
    </h1>
    <div class="form-content mt-3">
        <div class="row">
            {{-- ==========================================================
                INSTRUKSI PASCA ANESTESI
            =========================================================== --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Rawat penderita di R.R. - Posisi</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_posisi" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">Oksigen</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_oksigen" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">L/mnt.nasal./E</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <label class="form-label flex-shrink-0 mb-0">Awasi Respirasi / nadi / tensi tiap</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_tensitiap" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">mnt</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Bila tensi turun di bawah</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_tensibawah" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">mmHg, berikan</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_tensiberi" placeholder="...">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Bila muntah, berikan</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_muntahberi" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">Bila kesakitan berikan</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_sakitberi" placeholder="...">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Bila masih kesakitan, Extra</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_extra" placeholder="...">
                    </div>
                </div>
                <label class="form-label mb-3">Infus :</label>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">transfusi</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_transfusi" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">boto, tetesan</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_tetesan" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">/ mnt</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Cairan</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_cairan" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">cc / 24 jam terdiri atas :</label>
                    </div>
                </div>
            </div>

            {{-- ==========================================================
                UNTUK 24 JAM PERTAMA
            =========================================================== --}}
            <div class="col-md-6">
                <label class="form-label mb-3">Untuk 24 jam pertama</label>
                <div class="form-group mb-2">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">1 .</label>
                        <textarea class="form-control" name="ipa_ujp_1" rows="1"></textarea>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">2 .</label>
                        <textarea class="form-control" name="ipa_ujp_2" rows="1"></textarea>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">3 .</label>
                        <textarea class="form-control" name="ipa_ujp_3" rows="1"></textarea>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">4 .</label>
                        <textarea class="form-control" name="ipa_ujp_4" rows="1"></textarea>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <label class="form-label flex-shrink-0 mb-0">Tetesan ,</label>
                        <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_ujp_tetesan" placeholder="...">
                        <label class="form-label flex-shrink-0 mb-0">mnt</label>
                    </div>
                </div>
            </div>

            {{-- ==========================================================
                SESUDAH SADAR / OBAT
            =========================================================== --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label mb-3">Sesudah sadar</label>
                    <div class="form-group mb-3">
                        <div class="d-flex gap-2 align-items-center w-100">
                            <label class="form-label flex-shrink-0 mb-0">Resep</label>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_ss_resep" placeholder="...">
                            <label class="form-label flex-shrink-0 mb-0">nd</label>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_ss_nd" placeholder="...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <label class="form-label flex-shrink-0 mb-0">Tensi</label>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="ipa_ss_tensi" placeholder="...">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="ipa_ss_ranap" value="1">
                                <label class="form-check-label">Rawat Bangsal</label>
                            </div>
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="ipa_ss_rr" value="1">
                                <label class="form-check-label">Tetap di RR dulu</label>
                            </div>
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="ipa_ss_icu" value="1">
                                <label class="form-check-label">ICU</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label mb-3">Obat-obat</label>
                    <div class="form-group mb-2">
                        <div class="d-flex gap-2 align-items-center w-100">
                            <label class="form-label flex-shrink-0 mb-0">1 .</label>
                            <input type="text" class="form-control form-control-sm" name="ipa_obt_1">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="d-flex gap-2 align-items-center w-100">
                            <label class="form-label flex-shrink-0 mb-0">2 .</label>
                            <input type="text" class="form-control form-control-sm" name="ipa_obt_2">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex gap-2 align-items-center w-100">
                            <label class="form-label flex-shrink-0 mb-0">3 .</label>
                            <input type="text" class="form-control form-control-sm" name="ipa_obt_3">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==========================================================
                GRAFIK MONITORING PASCA ANESTESI
            =========================================================== --}}
            <div class="col-md-12">
                <div class="card shadow-lg mb-0">
                    <div class="card-header py-2">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <h5 class="card-title mb-0">Grafik Monitoring Pasca Anestesi</h5>
                            <div class="d-flex align-items-center gap-2">
                                <span id="monitoringPascaAnestesiCursorPosition" class="text-muted small d-none d-md-inline">Arahkan cursor ke diagram</span>
                                <button type="button" class="btn btn-sm btn-subtle-warning" id="btnRefreshMonitoringPascaAnestesi">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2 pb-4">
                        <div id="monitoringPascaAnestesiChart" style="min-height:500px;" class="p-2"></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="d-flex align-items-center gap-2 w-100">
                                        <label class="form-label flex-shrink-0 mb-0">Penderita sadar pada jam </label>
                                        <input type="time" class="form-control form-control-sm flex-grow-1" name="ipa_sadarjam" placeholder="...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="d-flex align-items-center gap-2 w-100">
                                        <label class="form-label flex-shrink-0 mb-0">Dipindahkan dari RR pada jam </label>
                                        <input type="time" class="form-control form-control-sm flex-grow-1" name="ipa_pindahjam" placeholder="...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Penyulit yang timbul selama perawatan di R.R.</label>
                                    <textarea class="form-control" name="ipa_penyulit" rows="2" placeholder=". . ."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================================
        MODAL MONITORING PASCA ANESTESI
    =========================================================== --}}
    <div class="modal fade" id="modalPilihIndikatorPascaAnestesi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Monitoring Pasca Anestesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            Waktu
                            <span class="badge bg-primary-subtle text-primary p-1 ms-1" id="pascaAnestesiMonitoringMenit">Menit Ke - 0</span>
                        </label>
                        <input type="text" class="form-control" id="pascaAnestesiMonitoringWaktu" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai</label>
                        <input type="number" class="form-control" id="pascaAnestesiMonitoringNilai" min="0" max="300" step="1">
                        <small class="text-muted">Nilai bebas 0–300</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Indikator</label>
                        <select class="form-select" id="pascaAnestesiMonitoringIndikator">
                            <option value="">Pilih indikator</option>
                            <option value="tensi_rendah">Tensi Rendah</option>
                            <option value="tensi_tinggi">Tensi Tinggi</option>
                            <option value="nadi">Nadi</option>
                            <option value="resp_sr">Resp SR</option>
                            <option value="resp_ar">Resp AR</option>
                            <option value="resp_cr">Resp CR</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="pascaAnestesiMonitoringKeterangan" rows="2" placeholder="Keterangan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnSimpanMonitoringPascaAnestesi">
                        <i class="ri-save-line me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include(
        'pages.v2.medicalrecord.detail.form.finalisasi',
        [
            'jenis' => 'non-cppt',
            'formId' => 'form_laporan_pascaanestesi',
            'formKey' => 'lap_pasca_anestesi',
            'sub' => 'PENATA-ANESTESI',
            'kunjungan' => $kunjungan ?? $list['kunjungan'],
        ]
    )

</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_laporan_pascaanestesi');
    if (!$form.length) {
        return;
    }

    /* ==========================================================
        URL
    =========================================================== */
    const BASE_URL = `/api/v2/emr/form/pengkajian/bedans/laporanpascaanestesi/${kunjungan}`;
    const URL_FORM_GET = BASE_URL;
    const URL_FORM_SAVE = `${BASE_URL}/simpan`;
    const URL_MONITORING_GET = `${BASE_URL}/monitoring`;
    const URL_MONITORING_SAVE = `${BASE_URL}/monitoring/simpan`;
    const URL_MONITORING_DELETE = `${BASE_URL}/monitoring/hapus`;
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ==========================================================
        STATE
    =========================================================== */
    let isFormLoading = false;
    let isFormSaving = false;
    let isMonitoringLoading = false;
    let isMonitoringSaving = false;
    let monitoringChart = null;
    let monitoringData = [];

    let selectedMonitoring = {
        id: null,
        waktu: null,
        nilai: null,
        indikator: null,
        keterangan: null
    };

    /* ==========================================================
        INDIKATOR MONITORING
    =========================================================== */
    const indikatorMonitoring = {
        tensi_rendah: {
            label: 'Tensi Rendah'
        },
        tensi_tinggi: {
            label: 'Tensi Tinggi'
        },
        nadi: {
            label: 'Nadi'
        },
        resp_sr: {
            label: 'Resp SR'
        },
        resp_ar: {
            label: 'Resp AR'
        },
        resp_cr: {
            label: 'Resp CR'
        }
    };

    /* ==========================================================
        MAPPING FORM
    =========================================================== */
    const mappingForm = {
        IPA_POSISI: 'ipa_posisi',
        IPA_OKSIGEN: 'ipa_oksigen',
        IPA_TENSITIAP: 'ipa_tensitiap',
        IPA_TENSIBAWAH: 'ipa_tensibawah',
        IPA_TENSIBERI: 'ipa_tensiberi',
        IPA_MUNTAHBERI: 'ipa_muntahberi',
        IPA_SAKITBERI: 'ipa_sakitberi',
        IPA_EXTRA: 'ipa_extra',
        IPA_TRANSFUSI: 'ipa_transfusi',
        IPA_TETESAN: 'ipa_tetesan',
        IPA_CAIRAN: 'ipa_cairan',
        IPA_UJP_1: 'ipa_ujp_1',
        IPA_UJP_2: 'ipa_ujp_2',
        IPA_UJP_3: 'ipa_ujp_3',
        IPA_UJP_4: 'ipa_ujp_4',
        IPA_UJP_TETESAN: 'ipa_ujp_tetesan',
        IPA_SS_RESEP: 'ipa_ss_resep',
        IPA_SS_ND: 'ipa_ss_nd',
        IPA_SS_TENSI: 'ipa_ss_tensi',
        IPA_SS_RANAP: 'ipa_ss_ranap',
        IPA_SS_RR: 'ipa_ss_rr',
        IPA_SS_ICU: 'ipa_ss_icu',
        IPA_OBT_1: 'ipa_obt_1',
        IPA_OBT_2: 'ipa_obt_2',
        IPA_OBT_3: 'ipa_obt_3',
        IPA_SADARJAM: 'ipa_sadarjam',
        IPA_PINDAHJAM: 'ipa_pindahjam',
        IPA_PENYULIT: 'ipa_penyulit'
    };

    /* ==========================================================
        GET FORM
    =========================================================== */
    function getForm() {
        if (isFormLoading) {
            return;
        }

        isFormLoading = true;

        $.ajax({
            url: URL_FORM_GET,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                const data = res?.data;

                if (!data) {
                    return;
                }

                Object.keys(mappingForm).forEach(function (key) {
                    const fieldName = mappingForm[key];
                    const value = data[fieldName];

                    const $input = $form.find(`[name="${fieldName}"], #${fieldName}`).first();

                    if (!$input.length) {
                        return;
                    }

                    /*
                     * Jangan menggunakan hasValue untuk checkbox
                     * yang nilainya bisa 0.
                     */
                    if ($input.is(':checkbox')) {
                        if ($input.hasClass('single-checkbox')) {
                            FormHelper.setSingleCheckbox(
                                $form,
                                fieldName,
                                value
                            );
                        } else {
                            FormHelper.setCheckbox(
                                $form,
                                fieldName,
                                value
                            );
                        }

                        return;
                    }

                    // if (!FormHelper.hasValue(value)) {
                    //     return;
                    // }

                    FormHelper.setValue(
                        $form,
                        fieldName,
                        value
                    );
                });

                FormHelper.updateDependentInputs($form);
            },
            error: function (xhr) {
                console.error(
                    'GET Form Pasca Anestesi:',
                    xhr.responseText
                );
            },
            complete: function () {
                isFormLoading = false;
            }
        });
    }

    /* ==========================================================
        SAVE FORM
    =========================================================== */
    function simpanForm() {
        if (
            isFormLoading ||
            isFormSaving
        ) {
            return;
        }

        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );

        isFormSaving = true;

        $.ajax({
            url: URL_FORM_SAVE,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function (res) {
                console.log(
                    'Form Pasca Anestesi tersimpan:',
                    res
                );
            },
            error: function (xhr) {
                console.error(
                    'POST Form Pasca Anestesi:',
                    xhr.responseText
                );

                let message = 'Data gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('<br>');
                } else if (
                    xhr.responseJSON?.message
                ) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Gagal',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function () {
                isFormSaving = false;
            }
        });
    }

    /* ==========================================================
        GET MONITORING
    =========================================================== */
    function getMonitoring() {
        if (isMonitoringLoading) {
            return;
        }

        isMonitoringLoading = true;

        $.ajax({
            url: URL_MONITORING_GET,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                monitoringData = Array.isArray(res?.data)
                    ? res.data
                    : [];

                renderMonitoringChart();
            },
            error: function (xhr) {
                console.error(
                    'GET Monitoring Pasca Anestesi:',
                    xhr.responseText
                );
            },
            complete: function () {
                isMonitoringLoading = false;
            }
        });
    }

    /* ==========================================================
        SLOT MENIT
        Sama seperti grafik anestesi
    =========================================================== */
    function generateMinuteSlots() {
        const result = [];

        for (
            let minute = 0;
            minute <= 300;
            minute += 5
        ) {
            result.push(minute);
        }

        return result;
    }

    /* ==========================================================
        MINUTE -> TIME
    =========================================================== */
    function minuteToDuration(minute) {
        minute = Number(minute);

        if (
            Number.isNaN(minute) ||
            minute < 0
        ) {
            minute = 0;
        }

        const totalSeconds = Math.round(minute * 60);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return (
            String(hours).padStart(2, '0') +
            ':' +
            String(minutes).padStart(2, '0') +
            ':' +
            String(seconds).padStart(2, '0')
        );
    }

    /* ==========================================================
        TIME -> MINUTE
    =========================================================== */
    function durationToMinute(value) {
        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return null;
        }

        const stringValue = String(value).trim();

        if (
            stringValue.includes(':')
        ) {
            const parts = stringValue.split(':');
            const hours = Number(parts[0]) || 0;
            const minutes = Number(parts[1]) || 0;
            const seconds = Number(parts[2]) || 0;

            return (
                hours * 60 +
                minutes +
                seconds / 60
            );
        }

        const numericValue = Number(stringValue);

        return Number.isNaN(
            numericValue
        )
            ? null
            : numericValue;
    }

    /* ==========================================================
        FIND MONITORING
    =========================================================== */
    function findMonitoring(
        minute,
        indikator
    ) {
        return monitoringData.find(
            function (item) {
                const itemMinute = durationToMinute(
                    item.waktu
                );

                if (
                    itemMinute === null
                ) {
                    return false;
                }

                return (
                    Math.round(itemMinute) === Number(minute) &&
                    item.indikator === indikator
                );
            }
        );
    }

    /* ==========================================================
        FIND EXISTING POINT
    =========================================================== */
    function findNearestExistingPoint(
        minute,
        nilai
    ) {
        let nearest = null;
        let nearestDistance = Infinity;

        monitoringData.forEach(
            function (item) {
                const itemMinute = durationToMinute(
                    item.waktu
                );

                if (
                    itemMinute === null ||
                    Math.round(itemMinute) !== Number(minute)
                ) {
                    return;
                }

                const itemNilai = Number(item.nilai);

                const distance = Math.abs(
                    itemNilai - nilai
                );

                if (
                    distance < nearestDistance
                ) {
                    nearestDistance = distance;
                    nearest = item;
                }
            }
        );

        if (
            nearest &&
            nearestDistance <= 20
        ) {
            return nearest;
        }

        return null;
    }

    /* ==========================================================
        FORMAT NILAI
    =========================================================== */
    function formatNilai(value) {
        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return '-';
        }

        return Number(value);
    }

    /* ==========================================================
        RENDER GRAFIK
    =========================================================== */
    function renderMonitoringChart() {
        const minutes = generateMinuteSlots();

        const indikatorKeys = Object.keys(
            indikatorMonitoring
        );

        const series = indikatorKeys.map(
            function (indikator) {
                return {
                    name: indikatorMonitoring[indikator].label,
                    data: minutes.map(
                        function (minute) {
                            const item = findMonitoring(
                                minute,
                                indikator
                            );

                            return item
                                ? Number(item.nilai)
                                : null;
                        }
                    )
                };
            }
        );

        const options = {
            chart: {
                type: 'line',
                height: 500,
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                animations: {
                    enabled: true,
                    speed: 250
                },
                events: {
                    click: function (
                        event,
                        chartContext,
                        config
                    ) {
                        handleChartClick(
                            event,
                            chartContext,
                            config
                        );
                    },
                    mouseMove: function (
                        event,
                        chartContext
                    ) {
                        handleChartMouseMove(
                            event,
                            chartContext
                        );
                    },
                    mouseLeave: function () {
                        $(
                            '#monitoringPascaAnestesiCursorPosition'
                        ).text(
                            'Arahkan cursor ke diagram'
                        );
                    }
                }
            },
            series: series,
            stroke: {
                width: 0,
                curve: 'straight'
            },
            markers: {
                size: 6,
                strokeWidth: 2,
                hover: {
                    size: 9
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -8,
                style: {
                    fontSize: '10px',
                    fontWeight: 500
                },
                background: {
                    enabled: true,
                    borderRadius: 2,
                    borderWidth: 1,
                    opacity: 0.8
                },
                formatter: function (value) {
                    if (
                        value === null ||
                        value === undefined
                    ) {
                        return '';
                    }

                    return value;
                }
            },
            xaxis: {
                type: 'category',
                categories: minutes.map(String),
                tickPlacement: 'on',
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: true
                },
                title: {
                    text: 'Menit (Kelipatan 5)',
                    offsetY: 0
                },
                labels: {
                    rotate: -45,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: false,
                    formatter: function (value) {
                        return value;
                    },
                    style: {
                        fontSize: '10px'
                    }
                },
                crosshairs: {
                    show: true,
                    width: 1,
                    position: 'back',
                    stroke: {
                        width: 1,
                        dashArray: 3
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 300,
                tickAmount: 15,
                forceNiceScale: false,
                decimalsInFloat: 0,
                title: {
                    text: 'Nilai'
                },
                labels: {
                    formatter: function (value) {
                        return Math.round(value);
                    }
                }
            },
            grid: {
                show: true,
                borderColor: undefined,
                strokeDashArray: 0,
                position: 'back',
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 10,
                    right: 20,
                    bottom: 0,
                    left: 10
                }
            },
            tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                followCursor: false,
                x: {
                    show: true
                },
                custom: function ({
                    seriesIndex,
                    dataPointIndex
                }) {
                    const indikatorKey =
                        indikatorKeys[seriesIndex];

                    const nama =
                        indikatorMonitoring[indikatorKey].label;

                    const menit =
                        minutes[dataPointIndex];

                    const waktu =
                        minuteToDuration(menit);

                    const item =
                        findMonitoring(
                            menit,
                            indikatorKey
                        );

                    if (!item) {
                        return `
                            <div class="p-2">
                                <div>
                                    <strong>${menit} menit</strong>
                                </div>
                                <div class="text-muted">
                                    ${waktu}
                                </div>
                                <div>${nama}</div>
                                <div class="mt-1">
                                    Belum ada data
                                </div>
                            </div>
                        `;
                    }

                    const keterangan =
                        item.keterangan
                            ? `
                                <div class="mt-1">
                                    <small>${item.keterangan}</small>
                                </div>
                            `
                            : '';

                    return `
                        <div class="p-2">
                            <div>
                                <strong>${menit} menit</strong>
                            </div>
                            <div class="text-muted">
                                ${waktu}
                            </div>
                            <div>${nama}</div>
                            <div class="mt-1">
                                Nilai:
                                <strong>
                                    ${formatNilai(item.nilai)}
                                </strong>
                            </div>
                            ${keterangan}
                            <div class="mt-1 text-muted">
                                Klik titik untuk mengubah
                            </div>
                        </div>
                    `;
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'center'
            },
            responsive: [
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 450
                        },
                        xaxis: {
                            labels: {
                                rotate: -90,
                                fontSize: '9px'
                            }
                        }
                    }
                }
            ]
        };

        if (monitoringChart) {
            monitoringChart.destroy();
        }

        monitoringChart = new ApexCharts(
            document.querySelector(
                '#monitoringPascaAnestesiChart'
            ),
            options
        );

        monitoringChart.render();
    }

    /* ==========================================================
        POSISI CURSOR DI GRAFIK
    =========================================================== */
    function getCursorChartPosition(
        event,
        chartContext
    ) {
        if (!monitoringChart) {
            return null;
        }

        const globals = chartContext.w.globals;

        const rect =
            chartContext.el.getBoundingClientRect();

        const cursorX =
            event.clientX - rect.left;

        const cursorY =
            event.clientY - rect.top;

        const gridX = globals.translateX;
        const gridY = globals.translateY;
        const gridWidth = globals.gridWidth;
        const gridHeight = globals.gridHeight;

        if (
            cursorX < gridX ||
            cursorX > gridX + gridWidth ||
            cursorY < gridY ||
            cursorY > gridY + gridHeight
        ) {
            return null;
        }

        const minutes =
            generateMinuteSlots();

        const ratioX =
            (cursorX - gridX) / gridWidth;

        let minuteIndex =
            Math.round(
                ratioX * (minutes.length - 1)
            );

        minuteIndex =
            Math.max(
                0,
                Math.min(
                    minutes.length - 1,
                    minuteIndex
                )
            );

        const minute =
            minutes[minuteIndex];

        const ratioY =
            (cursorY - gridY) / gridHeight;

        let nilai =
            300 - (ratioY * 300);

        nilai =
            Math.round(nilai);

        nilai =
            Math.max(
                0,
                Math.min(
                    300,
                    nilai
                )
            );

        return {
            minute: minute,
            nilai: nilai
        };
    }

    /* ==========================================================
        MOUSE MOVE
    =========================================================== */
    function handleChartMouseMove(
        event,
        chartContext
    ) {
        const position =
            getCursorChartPosition(
                event,
                chartContext
            );

        const $position =
            $('#monitoringPascaAnestesiCursorPosition');

        if (!position) {
            $position.text(
                'Arahkan cursor ke diagram'
            );

            return;
        }

        $position.html(
            `X : <strong>${position.minute} menit</strong>` +
            ` &nbsp;&nbsp; ` +
            `Y : <strong>${position.nilai}</strong>`
        );
    }

    /* ==========================================================
        CLICK GRAFIK
    =========================================================== */
    function handleChartClick(
        event,
        chartContext,
        config
    ) {
        if (!monitoringChart) {
            return;
        }

        const position =
            getCursorChartPosition(
                event,
                chartContext
            );

        if (!position) {
            return;
        }

        const minute =
            position.minute;

        const nilai =
            position.nilai;

        const waktu =
            minuteToDuration(minute);

        const existingItem =
            findNearestExistingPoint(
                minute,
                nilai
            );

        selectedMonitoring = {
            id:
                existingItem
                    ? existingItem.id
                    : null,
            waktu:
                existingItem
                    ? existingItem.waktu
                    : waktu,
            nilai:
                existingItem
                    ? Number(existingItem.nilai)
                    : nilai,
            indikator:
                existingItem
                    ? existingItem.indikator
                    : null,
            keterangan:
                existingItem
                    ? existingItem.keterangan
                    : null
        };

        $('#pascaAnestesiMonitoringMenit').text(
            `Menit Ke - ${minute}`
        );

        $('#pascaAnestesiMonitoringWaktu').val(
            selectedMonitoring.waktu
        );

        $('#pascaAnestesiMonitoringNilai').val(
            selectedMonitoring.nilai
        );

        $('#pascaAnestesiMonitoringIndikator').val(
            selectedMonitoring.indikator || ''
        );

        $('#pascaAnestesiMonitoringKeterangan').val(
            selectedMonitoring.keterangan || ''
        );

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById(
                    'modalPilihIndikatorPascaAnestesi'
                )
            );

        modal.show();
    }

    /* ==========================================================
        RESET MODAL
    =========================================================== */
    function resetMonitoringModal() {
        selectedMonitoring = {
            id: null,
            waktu: null,
            nilai: null,
            indikator: null,
            keterangan: null
        };

        $('#pascaAnestesiMonitoringMenit').text(
            'Menit Ke - 0'
        );

        $('#pascaAnestesiMonitoringWaktu').val('');
        $('#pascaAnestesiMonitoringNilai').val('');
        $('#pascaAnestesiMonitoringIndikator').val('');
        $('#pascaAnestesiMonitoringKeterangan').val('');
    }

    /* ==========================================================
        SAVE MONITORING
    =========================================================== */
    function simpanMonitoring() {
        if (
            isMonitoringSaving ||
            !selectedMonitoring.waktu
        ) {
            return;
        }

        const waktu =
            selectedMonitoring.waktu;

        const nilai =
            Number(
                $('#pascaAnestesiMonitoringNilai').val()
            );

        const indikator =
            $('#pascaAnestesiMonitoringIndikator').val();

        const keterangan =
            $('#pascaAnestesiMonitoringKeterangan').val();

        if (
            Number.isNaN(nilai) ||
            nilai < 0 ||
            nilai > 300
        ) {
            iziToast.warning({
                title: 'Perhatian',
                message:
                    'Nilai harus berada antara 0 sampai 300.',
                position: 'topRight'
            });

            return;
        }

        if (!indikator) {
            iziToast.warning({
                title: 'Perhatian',
                message:
                    'Silakan pilih indikator.',
                position: 'topRight'
            });

            return;
        }

        isMonitoringSaving = true;

        $.ajax({
            url: URL_MONITORING_SAVE,
            type: 'POST',
            data: {
                id:
                    selectedMonitoring.id,
                NOKUNJ:
                    kunjungan,
                waktu:
                    waktu,
                nilai:
                    nilai,
                indikator:
                    indikator,
                keterangan:
                    keterangan
            },
            headers: {
                'X-CSRF-TOKEN':
                    CSRF_TOKEN
            },
            success: function (res) {
                const modal =
                    bootstrap.Modal.getInstance(
                        document.getElementById(
                            'modalPilihIndikatorPascaAnestesi'
                        )
                    );

                if (modal) {
                    modal.hide();
                }

                resetMonitoringModal();
                getMonitoring();

                iziToast.success({
                    title: 'Berhasil',
                    message:
                        'Monitoring pasca anestesi berhasil disimpan.',
                    position: 'topRight'
                });
            },
            error: function (xhr) {
                console.error(
                    'POST Monitoring Pasca Anestesi:',
                    xhr.responseText
                );

                let message =
                    'Monitoring gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message =
                        Object
                            .values(
                                xhr.responseJSON.errors
                            )
                            .flat()
                            .join('<br>');
                } else if (
                    xhr.responseJSON?.message
                ) {
                    message =
                        xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Gagal',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function () {
                isMonitoringSaving = false;
            }
        });
    }

    async function hapusMonitoringPascaAnestesi(id) {
        if (!id) {
            showToast(
                'Data monitoring tidak ditemukan.',
                'warning'
            );
            return;
        }

        const konfirmasi = confirm(
            'Apakah Anda yakin ingin menghapus data monitoring pasca anestesi ini?'
        );

        if (!konfirmasi) {
            return;
        }

        try {
            const response = await $.ajax({
                url: `${URL_MONITORING_DELETE}/${id}`,
                type: 'DELETE',
                data: {
                    _token:
                        $('meta[name="csrf-token"]').attr('content')
                }
            });

            if (response.success) {
                $('#modalPilihIndikatorPascaAnestesi').modal('hide');

                await getMonitoring();

                showToast(
                    response.message ||
                    'Data monitoring berhasil dihapus.',
                    'success'
                );
            } else {
                showToast(
                    response.message ||
                    'Data monitoring gagal dihapus.',
                    'error'
                );
            }
        } catch (error) {
            console.error(
                'hapusMonitoringPascaAnestesi:',
                error
            );

            showToast(
                error?.responseJSON?.message ||
                'Terjadi kesalahan saat menghapus data monitoring.',
                'error'
            );
        }
    }

    /* ==========================================================
        REFRESH MONITORING
    =========================================================== */
    $form.on(
        'click',
        '#btnRefreshMonitoringPascaAnestesi',
        function () {
            const $button =
                $(this);

            if (isMonitoringLoading) {
                return;
            }

            $button.prop(
                'disabled',
                true
            );

            $button.find('i')
                .removeClass(
                    'ri-refresh-line'
                )
                .addClass(
                    'ri-loader-4-line'
                );

            $.ajax({
                url:
                    URL_MONITORING_GET,
                type:
                    'GET',
                dataType:
                    'json',
                success:
                    function (res) {
                        monitoringData =
                            Array.isArray(
                                res?.data
                            )
                                ? res.data
                                : [];

                        renderMonitoringChart();

                        iziToast.success({
                            title: 'Berhasil',
                            message:
                                'Grafik monitoring pasca anestesi berhasil diperbarui.',
                            position: 'topRight'
                        });
                    },
                error:
                    function (xhr) {
                        console.error(
                            'Refresh Monitoring Pasca Anestesi:',
                            xhr.responseText
                        );

                        iziToast.error({
                            title: 'Gagal',
                            message:
                                'Grafik monitoring gagal diperbarui.',
                            position: 'topRight'
                        });
                    },
                complete:
                    function () {
                        $button.prop(
                            'disabled',
                            false
                        );

                        $button.find('i')
                            .removeClass(
                                'ri-loader-4-line'
                            )
                            .addClass(
                                'ri-refresh-line'
                            );
                    }
            });
        }
    );

    /* ==========================================================
        SAVE MONITORING BUTTON
    =========================================================== */
    $form.on(
        'click',
        '#btnSimpanMonitoringPascaAnestesi',
        function () {
            simpanMonitoring();
        }
    );

    /* ==========================================================
        MODAL CLOSED
    =========================================================== */
    $form.on(
        'hidden.bs.modal',
        '#modalPilihIndikatorPascaAnestesi',
        function () {
            resetMonitoringModal();
        }
    );

    /* ==========================================================
        AUTO SAVE INPUT
        BLUR = INPUT / TEXTAREA
    =========================================================== */
    $form.on(
        'blur',
        'input, textarea',
        function () {
            if (isFormLoading) {
                return;
            }

            simpanForm();
        }
    );

    /* ==========================================================
        AUTO SAVE CHANGE
        SELECT / CHECKBOX / RADIO
    =========================================================== */
    $form.on(
        'change',
        'select, input[type="checkbox"], input[type="radio"]',
        function () {
            if (isFormLoading) {
                return;
            }

            FormHelper.updateDependentInputs(
                $form
            );

            simpanForm();
        }
    );

    /* ==========================================================
        INIT
    =========================================================== */
    $(function () {
        getForm();
        getMonitoring();
    });
})();
</script>
