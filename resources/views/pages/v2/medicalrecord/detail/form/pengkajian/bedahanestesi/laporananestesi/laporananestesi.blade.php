<div class="form-wrapper" id="form_laporan_anestesi">
    <h1 class="display-6 mb-1 mt-2 fs-23 fw-medium text-center">
        LAPORAN <b class="text-success">ANESTESI</b>
    </h1>

    <div class="form-content mt-3">
        <div class="row">
            {{-- IDENTITAS / PEMERIKSAAN AWAL --}}
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">BB</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="la_bb">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">EKG</label>
                    <input type="text" class="form-control" name="la_ekg">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">AL</label>
                    <input type="text" class="form-control" name="la_al">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Lain-lain</label>
                    <input type="text" class="form-control" name="la_lain">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">ASA</label>
                    <select class="form-select" name="la_asa">
                        <option value="">Pilih</option>
                        <option value="1">ASA I</option>
                        <option value="2">ASA II</option>
                        <option value="3">ASA III</option>
                        <option value="4">ASA IV</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">Tensi</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="la_tensi_sis">
                        <span class="input-group-text">/</span>
                        <input type="number" class="form-control" name="la_tensi_dia">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">Hb</label>
                    <input type="text" class="form-control" name="la_hb">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label">Ht</label>
                    <input type="text" class="form-control" name="la_ht">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Gol. Darah</label>
                    <input type="text" class="form-control" name="la_gd">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Puasa</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="la_puasa">
                        <span class="input-group-text">Jam</span>
                    </div>
                </div>
            </div>

            {{-- PREMEDIKASI --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Premedikasi</label>
                    <textarea class="form-control" name="la_prem" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Jam / Rute</label>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <textarea class="form-control" name="la_prem_jam" rows="1" style="max-width:150px;"></textarea>
                        <div class="form-check m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="la_prem_rute" value="1">
                            <label class="form-check-label">IV</label>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="la_prem_rute" value="2">
                            <label class="form-check-label">IM</label>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="la_prem_rute" value="3">
                            <label class="form-check-label">Oral</label>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input single-checkbox" type="checkbox" name="la_prem_rute" value="4">
                            <label class="form-check-label">Rektal</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DIAGNOSA --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Diagnosa Preoperatif</label>
                    <textarea class="form-control" name="la_diag_pre" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Diagnosa Postoperatif</label>
                    <textarea class="form-control" name="la_diag_post" rows="1"></textarea>
                </div>
            </div>

            {{-- OPERASI --}}
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Nama / Macam Operasi</label>
                    <textarea class="form-control" name="la_nama_operasi" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Ahli Bedah</label>
                    <textarea class="form-control" name="la_nama_ahli_bedah" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Ahli Anestesi</label>
                    <textarea class="form-control" name="la_nama_ahli_anestesi" rows="1"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Perawat / Bidan Tanggal</label>
                    <textarea class="form-control" name="la_nama_perawat_bidan" rows="1"></textarea>
                </div>
            </div>

            {{-- WAKTU ANESTESI & OPERASI --}}
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0">Anestesi</label>
                        </div>
                        <div class="col">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Mulai</span>
                                <input type="time" class="form-control" name="la_anes_mulai">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Selesai</span>
                                <input type="time" class="form-control" name="la_anes_selesai">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0">Operasi</label>
                        </div>
                        <div class="col">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Mulai</span>
                                <input type="time" class="form-control" name="la_op_mulai">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Selesai</span>
                                <input type="time" class="form-control" name="la_op_selesai">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==========================================================
                MONITORING ANESTESI
            =========================================================== --}}
            <div class="col-md-12">
                <div class="card shadow-sm">

                    <div class="card-header py-2">
                        <div class="d-flex align-items-center justify-content-between gap-2">

                            <h5 class="card-title mb-0">
                                Grafik Anestesi
                            </h5>

                            <div class="d-flex align-items-center gap-2">

                                <span
                                    id="monitoringCursorPosition"
                                    class="text-muted small d-none d-md-inline"
                                >
                                    Arahkan cursor ke diagram
                                </span>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-subtle-warning"
                                    id="btnRefreshMonitoringAnestesi"
                                >
                                    <i class="ri-refresh-line"></i>
                                </button>

                            </div>
                        </div>
                    </div>


                    <div class="card-body p-2 pb-4">

                        {{-- ==================================================
                            GRAFIK MONITORING ANESTESI
                            JANGAN DIUBAH LOGIC-NYA
                        =================================================== --}}
                        <div
                            id="monitoringAnestesiChart"
                            style="min-height:500px;"
                            class="p-2"
                        ></div>


                        <hr>


                        {{-- ==================================================
                            GRAFIK ZAT ANESTESI
                        =================================================== --}}
                        <div class="d-flex align-items-center justify-content-between p-2">

                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    Zat Anestesi
                                </h6>

                                <small class="text-muted">
                                    Klik titik pada baris untuk memasukkan zat anestesi
                                </small>
                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-subtle-warning"
                                id="btnRefreshZatAnestesi"
                            >
                                <i class="ri-refresh-line"></i>
                            </button>

                        </div>

                        <div
                            id="monitoringZatAnestesiChart"
                            style="min-height:250px;"
                            class="p-2"
                        ></div>


                        <hr>


                        {{-- ==================================================
                            GRAFIK TEMPERATUR
                        =================================================== --}}
                        <div class="d-flex align-items-center justify-content-between p-2">

                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    Temperatur
                                </h6>

                                <small class="text-muted">
                                    Oral &amp; Rectal — interval 15 menit
                                </small>
                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-subtle-warning"
                                id="btnRefreshTemperatur"
                            >
                                <i class="ri-refresh-line"></i>
                            </button>

                        </div>

                        <div
                            id="monitoringTempChart"
                            style="min-height:250px;"
                            class="p-2"
                        ></div>


                        <hr>


                        {{-- ==================================================
                            GRAFIK CAIRAN
                        =================================================== --}}
                        <div class="d-flex align-items-center justify-content-between p-2">

                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    Cairan
                                </h6>

                                <small class="text-muted">
                                    Masuk &amp; Keluar — interval 15 menit
                                </small>
                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-subtle-warning"
                                id="btnRefreshCairan"
                            >
                                <i class="ri-refresh-line"></i>
                            </button>

                        </div>

                        <div
                            id="monitoringCairanChart"
                            style="min-height:250px;"
                            class="p-2"
                        ></div>


                        <hr>


                        {{-- ==================================================
                            BAGIAN FORM YANG SUDAH ADA
                        =================================================== --}}
                        <div class="col-md-12">

                            <div class="p-2">
                                <div class="form-group mb-3">

                                    <label class="form-labelflex-shrink-0 mb-2">
                                        Teknik :
                                    </label>

                                    <div class="d-flex gap-2 align-items-center">

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_iv"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                IV
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                SC
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                To and Fro
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                Circle
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group mb-3">

                                    <label class="form-labelflex-shrink-0 mb-2">
                                        Obat Anestesi :
                                    </label>

                                    <div class="d-flex gap-2 align-items-center">

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                IV
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                SC
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                To and Fro
                                            </label>
                                        </div>

                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input check-primary"
                                                type="checkbox"
                                                name="la_teknik_"
                                            >
                                            <label class="form-check-label mb-0 flex-shrink-0">
                                                Circle
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL MONITORING ANESTESI --}}
<div class="modal fade" id="modalPilihIndikatorAnestesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Monitoring Anestesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">
                        Waktu
                        <span class="badge bg-primary-subtle text-primary p-1 ms-1" id="anestesiMonitoringMenit">-</span>
                    </label>
                    <input type="text" class="form-control" id="anestesiMonitoringWaktu" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nilai</label>
                    <input type="number" class="form-control" id="anestesiMonitoringNilai" min="0" max="300" step="1">
                    <small class="text-muted">Nilai bebas 0–300</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Indikator</label>
                    <select class="form-select" id="anestesiMonitoringIndikator">
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
                    <textarea class="form-control" id="anestesiMonitoringKeterangan" rows="2" placeholder="Keterangan..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanMonitoringAnestesi">
                    <i class="ri-save-line me-1"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==========================================================
    MODAL ZAT ANESTESI
=========================================================== --}}
<div
    class="modal fade"
    id="modalZatAnestesi"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header py-2">

                <h5 class="modal-title">
                    Zat Anestesi
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Menit
                        <span
                            class="badge bg-primary-subtle text-primary ms-1"
                            id="zatAnestesiMenitBadge"
                        >
                            Menit 0
                        </span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="zatAnestesiWaktu"
                        readonly
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Baris
                    </label>

                    <select
                        class="form-select"
                        id="zatAnestesiBaris"
                    >
                        <option value="">Pilih baris</option>
                        <option value="1">Baris 1</option>
                        <option value="2">Baris 2</option>
                        <option value="3">Baris 3</option>
                        <option value="4">Baris 4</option>
                        <option value="5">Baris 5</option>
                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Zat Anestesi
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="zatAnestesiNama"
                        placeholder="Contoh: Propofol"
                    >

                </div>


                <div>

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        class="form-control"
                        id="zatAnestesiKeterangan"
                        rows="2"
                        placeholder="Keterangan..."
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer py-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="btnSimpanZatAnestesi"
                >
                    <i class="ri-save-line me-1"></i>
                    Simpan
                </button>

            </div>

        </div>

    </div>
</div>

{{-- ==========================================================
    MODAL TEMPERATUR
=========================================================== --}}
<div
    class="modal fade"
    id="modalTemperaturAnestesi"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header py-2">

                <h5 class="modal-title">
                    Temperatur
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">

                        Menit

                        <span
                            class="badge bg-primary-subtle text-primary ms-1"
                            id="temperaturMenitBadge"
                        >
                            Menit 0
                        </span>

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="temperaturWaktu"
                        readonly
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Jenis
                    </label>

                    <select
                        class="form-select"
                        id="temperaturJenis"
                    >
                        <option value="">
                            Pilih
                        </option>

                        <option value="oral">
                            Oral
                        </option>

                        <option value="rectal">
                            Rectal
                        </option>
                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Temperatur
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            class="form-control"
                            id="temperaturNilai"
                            min="0"
                            max="300"
                            step="0.1"
                        >

                        <span class="input-group-text">
                            °C
                        </span>

                    </div>

                </div>


                <div>

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        class="form-control"
                        id="temperaturKeterangan"
                        rows="2"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer py-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="btnSimpanTemperatur"
                >
                    <i class="ri-save-line me-1"></i>
                    Simpan
                </button>

            </div>

        </div>

    </div>
</div>

{{-- ==========================================================
    MODAL CAIRAN
=========================================================== --}}
<div
    class="modal fade"
    id="modalCairanAnestesi"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header py-2">

                <h5 class="modal-title">
                    Cairan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">

                        Menit

                        <span
                            class="badge bg-primary-subtle text-primary ms-1"
                            id="cairanMenitBadge"
                        >
                            Menit 0
                        </span>

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="cairanWaktu"
                        readonly
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Jenis
                    </label>

                    <select
                        class="form-select"
                        id="cairanJenis"
                    >

                        <option value="">
                            Pilih
                        </option>

                        <option value="masuk">
                            Masuk
                        </option>

                        <option value="keluar">
                            Keluar
                        </option>

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Jumlah
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            class="form-control"
                            id="cairanNilai"
                            min="0"
                            max="300"
                            step="1"
                        >

                        <span class="input-group-text">
                            ml
                        </span>

                    </div>

                </div>


                <div>

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        class="form-control"
                        id="cairanKeterangan"
                        rows="2"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer py-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="btnSimpanCairan"
                >
                    <i class="ri-save-line me-1"></i>
                    Simpan
                </button>

            </div>

        </div>

    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_laporan_anestesi');
    if (!$form.length) return;

    const URL_FORM_GET = `/api/v2/emr/form/pengkajian/bedans/laporananestesi/${kunjungan}`;
    const URL_FORM_SAVE = `/api/v2/emr/form/pengkajian/bedans/laporananestesi/${kunjungan}/simpan`;
    const URL_MONITORING_GET = `/api/v2/emr/form/pengkajian/bedans/laporananestesi/${kunjungan}/monitoring`;
    const URL_MONITORING_SAVE = `/api/v2/emr/form/pengkajian/bedans/laporananestesi/${kunjungan}/monitoring/simpan`;
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

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

    const indikatorMonitoring = {
        tensi_rendah: { label: 'Tensi Rendah' },
        tensi_tinggi: { label: 'Tensi Tinggi' },
        nadi: { label: 'Nadi' },
        resp_sr: { label: 'Resp SR' },
        resp_ar: { label: 'Resp AR' },
        resp_cr: { label: 'Resp CR' }
    };

    const mappingForm = {
        LA_BB: 'la_bb',
        LA_EKG: 'la_ekg',
        LA_AL: 'la_al',
        LA_LAIN: 'la_lain',
        LA_ASA: 'la_asa',
        LA_TENSI_SIS: 'la_tensi_sis',
        LA_TENSI_DIA: 'la_tensi_dia',
        LA_HB: 'la_hb',
        LA_HT: 'la_ht',
        LA_GD: 'la_gd',
        LA_PUASA: 'la_puasa',
        LA_PREM: 'la_prem',
        LA_PREM_JAM: 'la_prem_jam',
        LA_PREM_RUTE: 'la_prem_rute',
        LA_DIAG_PRE: 'la_diag_pre',
        LA_DIAG_POST: 'la_diag_post',
        LA_NAMA_OPERASI: 'la_nama_operasi',
        LA_NAMA_AHLI_BEDAH: 'la_nama_ahli_bedah',
        LA_NAMA_AHLI_ANESTESI: 'la_nama_ahli_anestesi',
        LA_NAMA_PERAWAT_BIDAN: 'la_nama_perawat_bidan',
        LA_ANES_MULAI: 'la_anes_mulai',
        LA_ANES_SELESAI: 'la_anes_selesai',
        LA_OP_MULAI: 'la_op_mulai',
        LA_OP_SELESAI: 'la_op_selesai'
    };

    function getForm() {
        if (isFormLoading) return;
        isFormLoading = true;

        $.ajax({
            url: URL_FORM_GET,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                const data = res?.data;
                if (!data) return;

                Object.keys(mappingForm).forEach(function (key) {
                    if (FormHelper.hasValue(data[key])) {
                        FormHelper.setValue($form, mappingForm[key], data[key]);
                    }
                });
            },
            error: function (xhr) {
                console.error('GET Form Anestesi:', xhr.responseText);
            },
            complete: function () {
                isFormLoading = false;
            }
        });
    }

    function simpanForm() {
        if (isFormLoading || isFormSaving) return;

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isFormSaving = true;

        $.ajax({
            url: URL_FORM_SAVE,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function (res) {
                console.log('Form Laporan Anestesi tersimpan', res);
            },
            error: function (xhr) {
                console.error('POST Form Anestesi:', xhr.responseText);

                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
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

    function getMonitoring() {
        if (isMonitoringLoading) return;

        isMonitoringLoading = true;

        $.ajax({
            url: URL_MONITORING_GET,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                monitoringData = Array.isArray(res?.data) ? res.data : [];
                renderMonitoringChart();
            },
            error: function (xhr) {
                console.error('GET Monitoring Anestesi:', xhr.responseText);
            },
            complete: function () {
                isMonitoringLoading = false;
            }
        });
    }

    function generateMinuteSlots() {
        const result = [];

        for (let minute = 0; minute <= 300; minute += 5) {
            result.push(minute);
        }

        return result;
    }

    function minuteToDuration(minute) {
        minute = Number(minute);

        if (Number.isNaN(minute) || minute < 0) {
            minute = 0;
        }

        const totalSeconds = Math.round(minute * 60);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }

    function durationToMinute(value) {
        if (value === null || value === undefined || value === '') {
            return null;
        }

        const stringValue = String(value).trim();

        if (stringValue.includes(':')) {
            const parts = stringValue.split(':');
            const hours = Number(parts[0]) || 0;
            const minutes = Number(parts[1]) || 0;
            const seconds = Number(parts[2]) || 0;

            return (hours * 60) + minutes + (seconds / 60);
        }

        const numericValue = Number(stringValue);

        return Number.isNaN(numericValue) ? null : numericValue;
    }

    function findMonitoring(minute, indikator) {
        return monitoringData.find(function (item) {
            const itemMinute = durationToMinute(item.waktu);

            if (itemMinute === null) {
                return false;
            }

            return Math.round(itemMinute) === Number(minute) &&
                item.indikator === indikator;
        });
    }

    function formatNilai(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        return Number(value);
    }

    function renderMonitoringChart() {
        const minutes = generateMinuteSlots();
        const indikatorKeys = Object.keys(indikatorMonitoring);

        const series = indikatorKeys.map(function (indikator) {
            return {
                name: indikatorMonitoring[indikator].label,
                data: minutes.map(function (minute) {
                    const item = findMonitoring(minute, indikator);
                    return item ? Number(item.nilai) : null;
                })
            };
        });

        const options = {
            chart: {
                type: 'line',
                height: 500,
                parentHeightOffset: 0,
                toolbar: { show: false },
                zoom: { enabled: false },
                animations: {
                    enabled: true,
                    speed: 250
                },
                events: {
                    click: function (event, chartContext, config) {
                        handleChartClick(event, chartContext, config);
                    },
                    mouseMove: function (event, chartContext) {
                        handleChartMouseMove(event, chartContext);
                    },
                    mouseLeave: function () {
                        $('#monitoringCursorPosition').text('Arahkan cursor ke diagram');
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
                    if (value === null || value === undefined) return '';
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
                custom: function ({ seriesIndex, dataPointIndex }) {
                    const indikatorKey = indikatorKeys[seriesIndex];
                    const nama = indikatorMonitoring[indikatorKey].label;
                    const menit = minutes[dataPointIndex];
                    const waktu = minuteToDuration(menit);
                    const item = findMonitoring(menit, indikatorKey);

                    if (!item) {
                        return `
                            <div class="p-2">
                                <div><strong>${menit} menit</strong></div>
                                <div class="text-muted">${waktu}</div>
                                <div>${nama}</div>
                                <div class="mt-1">Belum ada data</div>
                            </div>
                        `;
                    }

                    const keterangan = item.keterangan ? `
                        <div class="mt-1">
                            <small>${item.keterangan}</small>
                        </div>
                    ` : '';

                    return `
                        <div class="p-2">
                            <div><strong>${menit} menit</strong></div>
                            <div class="text-muted">${waktu}</div>
                            <div>${nama}</div>
                            <div class="mt-1">
                                Nilai:
                                <strong>${formatNilai(item.nilai)}</strong>
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
            document.querySelector('#monitoringAnestesiChart'),
            options
        );

        monitoringChart.render();
    }

    function findNearestExistingPoint(minute, nilai) {
        let nearest = null;
        let nearestDistance = Infinity;

        monitoringData.forEach(function (item) {
            const itemMinute = durationToMinute(item.waktu);

            if (
                itemMinute === null ||
                Math.round(itemMinute) !== Number(minute)
            ) {
                return;
            }

            const itemNilai = Number(item.nilai);
            const distance = Math.abs(itemNilai - nilai);

            if (distance < nearestDistance) {
                nearestDistance = distance;
                nearest = item;
            }
        });

        if (nearest && nearestDistance <= 20) {
            return nearest;
        }

        return null;
    }

    function getCursorChartPosition(event, chartContext) {
        if (!monitoringChart) {
            return null;
        }

        const globals = chartContext.w.globals;
        const rect = chartContext.el.getBoundingClientRect();
        const cursorX = event.clientX - rect.left;
        const cursorY = event.clientY - rect.top;
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

        const minutes = generateMinuteSlots();
        const ratioX = (cursorX - gridX) / gridWidth;

        let minuteIndex = Math.round(
            ratioX * (minutes.length - 1)
        );

        minuteIndex = Math.max(
            0,
            Math.min(minutes.length - 1, minuteIndex)
        );

        const minute = minutes[minuteIndex];
        const ratioY = (cursorY - gridY) / gridHeight;

        let nilai = 300 - (ratioY * 300);

        nilai = Math.round(nilai);
        nilai = Math.max(0, Math.min(300, nilai));

        return {
            minute: minute,
            nilai: nilai
        };
    }

    function handleChartMouseMove(event, chartContext) {
        const position = getCursorChartPosition(event, chartContext);
        const $position = $('#monitoringCursorPosition');

        if (!position) {
            $position.text('Arahkan cursor ke diagram');
            return;
        }

        $position.html(
            `X : <strong>${position.minute} menit</strong>` +
            ` &nbsp;&nbsp; ` +
            `Y : <strong>${position.nilai}</strong>`
        );
    }

    function handleChartClick(event, chartContext, config) {
        if (!monitoringChart) {
            return;
        }

        const position = getCursorChartPosition(event, chartContext);

        if (!position) {
            return;
        }

        const minute = position.minute;
        const nilai = position.nilai;
        const waktu = minuteToDuration(minute);

        $('#anestesiMonitoringMenit').text(
            `Menit Ke - ${position.minute}`
        );

        const existingItem = findNearestExistingPoint(minute, nilai);

        selectedMonitoring = {
            id: existingItem ? existingItem.id : null,
            waktu: existingItem ? existingItem.waktu : waktu,
            nilai: existingItem ? Number(existingItem.nilai) : nilai,
            indikator: existingItem ? existingItem.indikator : null,
            keterangan: existingItem ? existingItem.keterangan : null
        };

        $('#anestesiMonitoringWaktu').val(
            selectedMonitoring.waktu
        );

        $('#anestesiMonitoringNilai').val(
            selectedMonitoring.nilai
        );

        $('#anestesiMonitoringIndikator').val(
            selectedMonitoring.indikator || ''
        );

        $('#anestesiMonitoringKeterangan').val(
            selectedMonitoring.keterangan || ''
        );

        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalPilihIndikatorAnestesi')
        );

        modal.show();
    }

    function resetMonitoringModal() {
        selectedMonitoring = {
            id: null,
            waktu: null,
            nilai: null,
            indikator: null,
            keterangan: null
        };

        $('#anestesiMonitoringMenit').text('Menit Ke - 0');
        $('#anestesiMonitoringWaktu').val('');
        $('#anestesiMonitoringNilai').val('');
        $('#anestesiMonitoringIndikator').val('');
        $('#anestesiMonitoringKeterangan').val('');
    }

    function simpanMonitoring() {
        if (
            isMonitoringSaving ||
            !selectedMonitoring.waktu
        ) {
            return;
        }

        const waktu = selectedMonitoring.waktu;

        const nilai = Number(
            $('#anestesiMonitoringNilai').val()
        );

        const indikator = $('#anestesiMonitoringIndikator').val();
        const keterangan = $('#anestesiMonitoringKeterangan').val();

        if (
            Number.isNaN(nilai) ||
            nilai < 0 ||
            nilai > 300
        ) {
            iziToast.warning({
                title: 'Perhatian',
                message: 'Nilai harus berada antara 0 sampai 300.',
                position: 'topRight'
            });

            return;
        }

        if (!indikator) {
            iziToast.warning({
                title: 'Perhatian',
                message: 'Silakan pilih indikator.',
                position: 'topRight'
            });

            return;
        }

        isMonitoringSaving = true;

        $.ajax({
            url: URL_MONITORING_SAVE,
            type: 'POST',
            data: {
                waktu: waktu,
                nilai: nilai,
                indikator: indikator,
                keterangan: keterangan
            },
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function (res) {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById(
                        'modalPilihIndikatorAnestesi'
                    )
                );

                if (modal) {
                    modal.hide();
                }

                resetMonitoringModal();

                // Hanya reload data dan grafik monitoring.
                getMonitoring();

                iziToast.success({
                    title: 'Berhasil',
                    message: 'Monitoring anestesi berhasil disimpan.',
                    position: 'topRight'
                });
            },
            error: function (xhr) {
                console.error(
                    'POST Monitoring Anestesi:',
                    xhr.responseText
                );

                let message = 'Monitoring gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
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

    // Tombol refresh hanya mengambil data monitoring dan render ulang ApexCharts.
    $form.on(
        'click',
        '#btnRefreshMonitoringAnestesi',
        function () {
            const $button = $(this);

            if (isMonitoringLoading) {
                return;
            }

            $button.prop('disabled', true);
            $button.find('i')
                .removeClass('ri-refresh-line')
                .addClass('ri-loader-4-line');

            $.ajax({
                url: URL_MONITORING_GET,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    monitoringData = Array.isArray(res?.data)
                        ? res.data
                        : [];

                    renderMonitoringChart();

                    iziToast.success({
                        title: 'Berhasil',
                        message: 'Grafik monitoring berhasil diperbarui.',
                        position: 'topRight'
                    });
                },
                error: function (xhr) {
                    console.error(
                        'Refresh Monitoring Anestesi:',
                        xhr.responseText
                    );

                    iziToast.error({
                        title: 'Gagal',
                        message: 'Grafik monitoring gagal diperbarui.',
                        position: 'topRight'
                    });
                },
                complete: function () {
                    $button.prop('disabled', false);
                    $button.find('i')
                        .removeClass('ri-loader-4-line')
                        .addClass('ri-refresh-line');
                }
            });
        }
    );

    $(document).on(
        'click',
        '#btnSimpanMonitoringAnestesi',
        function () {
            simpanMonitoring();
        }
    );

    $(document).on(
        'hidden.bs.modal',
        '#modalPilihIndikatorAnestesi',
        function () {
            resetMonitoringModal();
        }
    );

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

    $form.on(
        'change',
        'select, input[type="checkbox"], input[type="radio"]',
        function () {
            if (isFormLoading) {
                return;
            }

            simpanForm();
        }
    );

    $(function () {
        getForm();
        getMonitoring();
    });
})();
</script>
<script>
(function () {

    'use strict';


    // ==========================================================
    // ELEMENT
    // ==========================================================

    const $form =
        $('#form_laporan_anestesi');

    if (!$form.length) {
        return;
    }


    // ==========================================================
    // URL
    //
    // TERPISAH DARI MONITORING ANESTESI LAMA
    // ==========================================================

    const BASE_URL =
        `/api/v2/emr/form/pengkajian/bedans/laporananestesi/${kunjungan}`;

    const URL_DETAIL_GET =
        `${BASE_URL}/monitoring-detail`;

    const URL_DETAIL_SAVE =
        `${BASE_URL}/monitoring-detail/simpan`;

    const URL_DETAIL_DELETE =
        `${BASE_URL}/monitoring-detail/hapus`;


    const CSRF_TOKEN =
        $('meta[name="csrf-token"]').attr('content');


    // ==========================================================
    // STATE
    // ==========================================================

    let detailLoading = false;
    let detailSaving = false;


    let zatAnestesiChart = null;
    let temperaturChart = null;
    let cairanChart = null;


    let zatAnestesiData = [];
    let temperaturData = [];
    let cairanData = [];


    // ==========================================================
    // SELECTED DATA
    // ==========================================================

    let selectedZat = {
        id: null,
        menit: null,
        baris: null,
        zat: '',
        keterangan: ''
    };


    let selectedTemperatur = {
        id: null,
        menit: null,
        jenis: null,
        nilai: null,
        keterangan: ''
    };


    let selectedCairan = {
        id: null,
        menit: null,
        jenis: null,
        nilai: null,
        keterangan: ''
    };


    // ==========================================================
    // TIME HELPER
    // ==========================================================

    function minuteToDuration(minute) {

        minute =
            Number(minute) || 0;

        const totalSeconds =
            Math.round(minute * 60);

        const hours =
            Math.floor(totalSeconds / 3600);

        const minutes =
            Math.floor(
                (totalSeconds % 3600) / 60
            );

        const seconds =
            totalSeconds % 60;


        return (
            String(hours).padStart(2, '0') +
            ':' +
            String(minutes).padStart(2, '0') +
            ':' +
            String(seconds).padStart(2, '0')
        );
    }


    function durationToMinute(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return null;
        }


        const stringValue =
            String(value).trim();


        if (stringValue.includes(':')) {

            const parts =
                stringValue.split(':');


            const hours =
                Number(parts[0]) || 0;

            const minutes =
                Number(parts[1]) || 0;

            const seconds =
                Number(parts[2]) || 0;


            return (
                hours * 60 +
                minutes +
                seconds / 60
            );
        }


        const number =
            Number(stringValue);


        return Number.isNaN(number)
            ? null
            : number;
    }


    // ==========================================================
    // SLOT MENIT
    // ==========================================================

    function generateMinutes(step) {

        const result = [];

        for (
            let minute = 0;
            minute <= 300;
            minute += step
        ) {

            result.push(minute);
        }

        return result;
    }


    // ==========================================================
    // LOAD DATA
    // ==========================================================

    function getMonitoringDetail() {

        if (detailLoading) {
            return;
        }


        detailLoading = true;


        $.ajax({

            url: URL_DETAIL_GET,

            type: 'GET',

            dataType: 'json',


            success: function (res) {

                const data =
                    res?.data || {};


                zatAnestesiData =
                    Array.isArray(
                        data.zat_anestesi
                    )
                        ? data.zat_anestesi
                        : [];


                temperaturData =
                    Array.isArray(
                        data.temperatur
                    )
                        ? data.temperatur
                        : [];


                cairanData =
                    Array.isArray(
                        data.cairan
                    )
                        ? data.cairan
                        : [];


                renderZatAnestesiChart();

                renderTemperaturChart();

                renderCairanChart();
            },


            error: function (xhr) {

                console.error(
                    'GET Monitoring Detail:',
                    xhr.responseText
                );

            },


            complete: function () {

                detailLoading = false;
            }

        });
    }


    // ==========================================================
    // RENDER ZAT ANESTESI
    // ==========================================================

    function renderZatAnestesiChart() {

        const minutes =
            generateMinutes(5);


        /*
         * 5 BARIS
         *
         * Baris 1 = y 250
         * Baris 2 = y 200
         * Baris 3 = y 150
         * Baris 4 = y 100
         * Baris 5 = y 50
         *
         * Jadi tetap terlihat seperti 5 baris.
         */

        const rowY = {

            1: 250,
            2: 200,
            3: 150,
            4: 100,
            5: 50
        };


        const rowLabel = {

            1: 'Baris 1',
            2: 'Baris 2',
            3: 'Baris 3',
            4: 'Baris 4',
            5: 'Baris 5'
        };


        const series = [];


        for (
            let row = 1;
            row <= 5;
            row++
        ) {

            series.push({

                name:
                    rowLabel[row],

                data:
                    minutes.map(
                        function (minute) {

                            const item =
                                zatAnestesiData.find(
                                    function (data) {

                                        return (
                                            Number(data.baris) === row &&
                                            Math.round(
                                                durationToMinute(
                                                    data.waktu
                                                )
                                            ) === minute
                                        );
                                    }
                                );


                            return item
                                ? rowY[row]
                                : null;
                        }
                    )
            });
        }


        const options = {

            chart: {

                type: 'line',

                height: 250,

                toolbar: {
                    show: false
                },

                zoom: {
                    enabled: false
                },

                events: {

                    click:
                        function (
                            event,
                            chartContext
                        ) {

                            handleZatChartClick(
                                event,
                                chartContext
                            );
                        }
                }
            },


            series: series,


            stroke: {

                width: 0
            },


            markers: {

                size: 7,

                strokeWidth: 2,

                hover: {
                    size: 10
                }
            },


            dataLabels: {

                enabled: true,

                offsetY: -10,


                formatter:
                    function (
                        value,
                        {
                            seriesIndex,
                            dataPointIndex
                        }
                    ) {

                        if (
                            value === null ||
                            value === undefined
                        ) {
                            return '';
                        }


                        const row =
                            seriesIndex + 1;


                        const minute =
                            minutes[
                                dataPointIndex
                            ];


                        const item =
                            zatAnestesiData.find(
                                function (data) {

                                    return (
                                        Number(data.baris) === row &&
                                        Math.round(
                                            durationToMinute(
                                                data.waktu
                                            )
                                        ) === minute
                                    );
                                }
                            );


                        return item?.zat || '';
                    },


                style: {

                    fontSize: '10px',

                    fontWeight: 500
                }
            },


            xaxis: {

                type: 'category',

                categories:
                    minutes.map(String),

                tickPlacement: 'on',


                title: {

                    text:
                        'Menit (Kelipatan 5)'
                },


                labels: {

                    rotate: -45,

                    style: {
                        fontSize: '9px'
                    }
                }
            },


            yaxis: {

                min: 0,

                max: 300,

                tickAmount: 6,

                forceNiceScale: false,


                labels: {

                    formatter:
                        function (value) {

                            const row =
                                Object.keys(rowY)
                                    .find(
                                        function (key) {

                                            return (
                                                Number(
                                                    rowY[key]
                                                ) ===
                                                Number(value)
                                            );
                                        }
                                    );


                            return row
                                ? rowLabel[row]
                                : '';
                        }
                }
            },


            grid: {

                show: true,


                xaxis: {

                    lines: {
                        show: true
                    }
                },


                yaxis: {

                    lines: {
                        show: true
                    }
                }
            },


            tooltip: {

                shared: false,

                intersect: true,


                custom:
                    function ({
                        seriesIndex,
                        dataPointIndex
                    }) {

                        const row =
                            seriesIndex + 1;


                        const minute =
                            minutes[
                                dataPointIndex
                            ];


                        const item =
                            zatAnestesiData.find(
                                function (data) {

                                    return (
                                        Number(data.baris) === row &&
                                        Math.round(
                                            durationToMinute(
                                                data.waktu
                                            )
                                        ) === minute
                                    );
                                }
                            );


                        if (!item) {

                            return `
                                <div class="p-2">

                                    <strong>
                                        ${minute} menit
                                    </strong>

                                    <div class="text-muted">
                                        ${rowLabel[row]}
                                    </div>

                                    <div class="mt-1">
                                        Klik untuk memasukkan
                                        zat anestesi
                                    </div>

                                </div>
                            `;
                        }


                        return `
                            <div class="p-2">

                                <strong>
                                    ${item.zat}
                                </strong>

                                <div class="text-muted">
                                    ${minute} menit
                                    (${minuteToDuration(minute)})
                                </div>

                                <div>
                                    ${rowLabel[row]}
                                </div>

                                ${
                                    item.keterangan
                                        ? `
                                            <div class="mt-1">
                                                ${item.keterangan}
                                            </div>
                                          `
                                        : ''
                                }

                            </div>
                        `;
                    }
            },


            legend: {

                show: false
            }
        };


        if (zatAnestesiChart) {

            zatAnestesiChart.destroy();
        }


        zatAnestesiChart =
            new ApexCharts(

                document.querySelector(
                    '#monitoringZatAnestesiChart'
                ),

                options
            );


        zatAnestesiChart.render();
    }


    // ==========================================================
    // CLICK ZAT
    // ==========================================================

    function handleZatChartClick(
        event,
        chartContext
    ) {

        const globals =
            chartContext.w.globals;


        const rect =
            chartContext.el.getBoundingClientRect();


        const cursorX =
            event.clientX - rect.left;

        const cursorY =
            event.clientY - rect.top;


        const gridX =
            globals.translateX;

        const gridY =
            globals.translateY;

        const gridWidth =
            globals.gridWidth;

        const gridHeight =
            globals.gridHeight;


        if (
            cursorX < gridX ||
            cursorX > gridX + gridWidth ||
            cursorY < gridY ||
            cursorY > gridY + gridHeight
        ) {
            return;
        }


        const minutes =
            generateMinutes(5);


        const ratioX =
            (cursorX - gridX) /
            gridWidth;


        let minuteIndex =
            Math.round(
                ratioX *
                (minutes.length - 1)
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


        /*
         * Tentukan baris berdasarkan posisi Y.
         */

        const rowY = {

            1: 250,
            2: 200,
            3: 150,
            4: 100,
            5: 50
        };


        let nearestRow =
            1;

        let nearestDistance =
            Infinity;


        Object.keys(rowY).forEach(
            function (row) {

                const pixelY =
                    gridY +
                    (
                        1 -
                        (
                            rowY[row] /
                            300
                        )
                    ) *
                    gridHeight;


                const distance =
                    Math.abs(
                        cursorY - pixelY
                    );


                if (
                    distance <
                    nearestDistance
                ) {

                    nearestDistance =
                        distance;

                    nearestRow =
                        Number(row);
                }
            }
        );


        openZatModal(
            minute,
            nearestRow
        );
    }


    // ==========================================================
    // OPEN MODAL ZAT
    // ==========================================================

    function openZatModal(
        minute,
        row
    ) {

        const existing =
            zatAnestesiData.find(
                function (item) {

                    return (
                        Number(item.baris) === row &&
                        Math.round(
                            durationToMinute(
                                item.waktu
                            )
                        ) === minute
                    );
                }
            );


        selectedZat = {

            id:
                existing?.id || null,

            menit:
                minute,

            baris:
                row,

            zat:
                existing?.zat || '',

            keterangan:
                existing?.keterangan || ''
        };


        $('#zatAnestesiMenitBadge')
            .text(
                `Menit ${minute}`
            );


        $('#zatAnestesiWaktu')
            .val(
                minuteToDuration(minute)
            );


        $('#zatAnestesiBaris')
            .val(row);


        $('#zatAnestesiNama')
            .val(
                selectedZat.zat
            );


        $('#zatAnestesiKeterangan')
            .val(
                selectedZat.keterangan
            );


        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById(
                    'modalZatAnestesi'
                )
            )
            .show();
    }


    // ==========================================================
    // SIMPAN ZAT
    // ==========================================================

    function simpanZatAnestesi() {

        const baris =
            Number(
                $('#zatAnestesiBaris').val()
            );


        const zat =
            $.trim(
                $('#zatAnestesiNama').val()
            );


        const keterangan =
            $('#zatAnestesiKeterangan').val();


        if (
            !baris ||
            baris < 1 ||
            baris > 5
        ) {

            iziToast.warning({

                title: 'Perhatian',

                message:
                    'Silakan pilih baris.',

                position: 'topRight'
            });

            return;
        }


        if (!zat) {

            iziToast.warning({

                title: 'Perhatian',

                message:
                    'Nama zat anestesi wajib diisi.',

                position: 'topRight'
            });

            return;
        }


        saveDetail({

            id:
                selectedZat.id,

            jenis_data:
                'zat_anestesi',

            waktu:
                minuteToDuration(
                    selectedZat.menit
                ),

            baris:
                baris,

            zat:
                zat,

            nilai:
                null,

            jenis:
                null,

            keterangan:
                keterangan

        });
    }


    // ==========================================================
    // RENDER TEMPERATUR
    // ==========================================================

    function renderTemperaturChart() {

        const minutes =
            generateMinutes(15);


        const jenis = [

            {
                key: 'oral',
                label: 'Oral'
            },

            {
                key: 'rectal',
                label: 'Rectal'
            }
        ];


        const series =
            jenis.map(
                function (item) {

                    return {

                        name:
                            item.label,

                        data:
                            minutes.map(
                                function (minute) {

                                    const data =
                                        temperaturData.find(
                                            function (row) {

                                                return (
                                                    row.jenis === item.key &&
                                                    Math.round(
                                                        durationToMinute(
                                                            row.waktu
                                                        )
                                                    ) === minute
                                                );
                                            }
                                        );


                                    return data
                                        ? Number(data.nilai)
                                        : null;
                                }
                            )
                    };
                }
            );


        const options = {

            chart: {

                type: 'line',

                height: 250,

                toolbar: {
                    show: false
                },

                zoom: {
                    enabled: false
                },

                events: {

                    click:
                        function (
                            event,
                            chartContext
                        ) {

                            handleTemperaturChartClick(
                                event,
                                chartContext
                            );
                        }
                }
            },


            series: series,


            stroke: {

                width: 2,

                curve: 'straight'
            },


            markers: {

                size: 6,

                hover: {
                    size: 9
                }
            },


            dataLabels: {

                enabled: true,

                formatter:
                    function (value) {

                        if (
                            value === null ||
                            value === undefined
                        ) {
                            return '';
                        }

                        return value;
                    },

                style: {

                    fontSize: '9px'
                }
            },


            xaxis: {

                type: 'category',

                categories:
                    minutes.map(String),

                tickPlacement: 'on',

                title: {

                    text:
                        'Menit (Kelipatan 15)'
                },

                labels: {

                    rotate: -45,

                    style: {
                        fontSize: '9px'
                    }
                }
            },


            yaxis: {

                min: 0,

                max: 50,

                tickAmount: 10,

                forceNiceScale: false,

                title: {

                    text:
                        'Temperatur (°C)'
                },

                labels: {

                    formatter:
                        function (value) {

                            return Math.round(value);
                        }
                }
            },


            grid: {

                show: true,

                xaxis: {
                    lines: {
                        show: true
                    }
                },

                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },


            tooltip: {

                shared: false,

                intersect: true,


                custom:
                    function ({
                        seriesIndex,
                        dataPointIndex
                    }) {

                        const jenis =
                            seriesIndex === 0
                                ? 'oral'
                                : 'rectal';


                        const label =
                            seriesIndex === 0
                                ? 'Oral'
                                : 'Rectal';


                        const minute =
                            minutes[
                                dataPointIndex
                            ];


                        const item =
                            temperaturData.find(
                                function (row) {

                                    return (
                                        row.jenis === jenis &&
                                        Math.round(
                                            durationToMinute(
                                                row.waktu
                                            )
                                        ) === minute
                                    );
                                }
                            );


                        return `

                            <div class="p-2">

                                <strong>
                                    ${label}
                                </strong>

                                <div class="text-muted">
                                    ${minute} menit
                                    (${minuteToDuration(minute)})
                                </div>

                                <div class="mt-1">

                                    ${
                                        item
                                            ? `${item.nilai} °C`
                                            : 'Belum ada data'
                                    }

                                </div>

                            </div>

                        `;
                    }
            },


            legend: {

                show: true,

                position: 'top',

                horizontalAlign: 'center'
            }
        };


        if (temperaturChart) {

            temperaturChart.destroy();
        }


        temperaturChart =
            new ApexCharts(

                document.querySelector(
                    '#monitoringTempChart'
                ),

                options
            );


        temperaturChart.render();
    }


    // ==========================================================
    // CLICK TEMPERATUR
    // ==========================================================

    function handleTemperaturChartClick(
        event,
        chartContext
    ) {

        const globals =
            chartContext.w.globals;


        const rect =
            chartContext.el.getBoundingClientRect();


        const cursorX =
            event.clientX - rect.left;


        const gridX =
            globals.translateX;


        const gridWidth =
            globals.gridWidth;


        if (
            cursorX < gridX ||
            cursorX > gridX + gridWidth
        ) {
            return;
        }


        const minutes =
            generateMinutes(15);


        const ratioX =
            (cursorX - gridX) /
            gridWidth;


        let minuteIndex =
            Math.round(
                ratioX *
                (minutes.length - 1)
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


        /*
         * Kalau klik dekat titik yang sudah ada,
         * buka data existing.
         *
         * Kalau tidak ada,
         * otomatis gunakan Oral.
         */

        const existing =
            findTemperaturByMinute(
                minute
            );


        openTemperaturModal(
            minute,
            existing?.jenis || 'oral',
            existing
        );
    }


    function findTemperaturByMinute(
        minute
    ) {

        return temperaturData.find(
            function (item) {

                return (
                    Math.round(
                        durationToMinute(
                            item.waktu
                        )
                    ) === minute
                );
            }
        );
    }


    // ==========================================================
    // OPEN MODAL TEMPERATUR
    // ==========================================================

    function openTemperaturModal(
        minute,
        jenis,
        existing = null
    ) {

        selectedTemperatur = {

            id:
                existing?.id || null,

            menit:
                minute,

            jenis:
                existing?.jenis || jenis,

            nilai:
                existing?.nilai ?? '',

            keterangan:
                existing?.keterangan || ''
        };


        $('#temperaturMenitBadge')
            .text(
                `Menit ${minute}`
            );


        $('#temperaturWaktu')
            .val(
                minuteToDuration(minute)
            );


        $('#temperaturJenis')
            .val(
                selectedTemperatur.jenis
            );


        $('#temperaturNilai')
            .val(
                selectedTemperatur.nilai
            );


        $('#temperaturKeterangan')
            .val(
                selectedTemperatur.keterangan
            );


        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById(
                    'modalTemperaturAnestesi'
                )
            )
            .show();
    }


    // ==========================================================
    // SIMPAN TEMPERATUR
    // ==========================================================

    function simpanTemperatur() {

        const jenis =
            $('#temperaturJenis').val();


        const nilai =
            Number(
                $('#temperaturNilai').val()
            );


        const keterangan =
            $('#temperaturKeterangan').val();


        if (!jenis) {

            iziToast.warning({

                title:
                    'Perhatian',

                message:
                    'Silakan pilih jenis temperatur.',

                position:
                    'topRight'
            });

            return;
        }


        if (
            Number.isNaN(nilai) ||
            nilai < 0 ||
            nilai > 300
        ) {

            iziToast.warning({

                title:
                    'Perhatian',

                message:
                    'Temperatur harus berada antara 0–300.',

                position:
                    'topRight'
            });

            return;
        }


        saveDetail({

            id:
                selectedTemperatur.id,

            jenis_data:
                'temperatur',

            waktu:
                minuteToDuration(
                    selectedTemperatur.menit
                ),

            baris:
                null,

            zat:
                null,

            nilai:
                nilai,

            jenis:
                jenis,

            keterangan:
                keterangan
        });
    }


    // ==========================================================
    // RENDER CAIRAN
    // ==========================================================

    function renderCairanChart() {

        const minutes =
            generateMinutes(15);


        const jenis = [

            {
                key: 'masuk',
                label: 'Masuk'
            },

            {
                key: 'keluar',
                label: 'Keluar'
            }
        ];


        const series =
            jenis.map(
                function (item) {

                    return {

                        name:
                            item.label,

                        data:
                            minutes.map(
                                function (minute) {

                                    const data =
                                        cairanData.find(
                                            function (row) {

                                                return (
                                                    row.jenis === item.key &&
                                                    Math.round(
                                                        durationToMinute(
                                                            row.waktu
                                                        )
                                                    ) === minute
                                                );
                                            }
                                        );


                                    return data
                                        ? Number(data.nilai)
                                        : null;
                                }
                            )
                    };
                }
            );


        const options = {

            chart: {

                type: 'line',

                height: 250,

                toolbar: {
                    show: false
                },

                zoom: {
                    enabled: false
                },

                events: {

                    click:
                        function (
                            event,
                            chartContext
                        ) {

                            handleCairanChartClick(
                                event,
                                chartContext
                            );
                        }
                }
            },


            series: series,


            stroke: {

                width: 2,

                curve: 'straight'
            },


            markers: {

                size: 6,

                hover: {
                    size: 9
                }
            },


            dataLabels: {

                enabled: true,

                formatter:
                    function (value) {

                        if (
                            value === null ||
                            value === undefined
                        ) {
                            return '';
                        }

                        return value;
                    },

                style: {

                    fontSize: '9px'
                }
            },


            xaxis: {

                type: 'category',

                categories:
                    minutes.map(String),

                tickPlacement: 'on',

                title: {

                    text:
                        'Menit (Kelipatan 15)'
                },

                labels: {

                    rotate: -45,

                    style: {
                        fontSize: '9px'
                    }
                }
            },


            yaxis: {

                min: 0,

                max: 300,

                tickAmount: 10,

                forceNiceScale: false,

                title: {

                    text:
                        'Cairan (ml)'
                },

                labels: {

                    formatter:
                        function (value) {

                            return Math.round(value);
                        }
                }
            },


            grid: {

                show: true,

                xaxis: {

                    lines: {
                        show: true
                    }
                },

                yaxis: {

                    lines: {
                        show: true
                    }
                }
            },


            tooltip: {

                shared: false,

                intersect: true,


                custom:
                    function ({
                        seriesIndex,
                        dataPointIndex
                    }) {

                        const jenis =
                            seriesIndex === 0
                                ? 'masuk'
                                : 'keluar';


                        const label =
                            seriesIndex === 0
                                ? 'Masuk'
                                : 'Keluar';


                        const minute =
                            minutes[
                                dataPointIndex
                            ];


                        const item =
                            cairanData.find(
                                function (row) {

                                    return (
                                        row.jenis === jenis &&
                                        Math.round(
                                            durationToMinute(
                                                row.waktu
                                            )
                                        ) === minute
                                    );
                                }
                            );


                        return `

                            <div class="p-2">

                                <strong>
                                    Cairan ${label}
                                </strong>

                                <div class="text-muted">
                                    ${minute} menit
                                    (${minuteToDuration(minute)})
                                </div>

                                <div class="mt-1">

                                    ${
                                        item
                                            ? `${item.nilai} ml`
                                            : 'Belum ada data'
                                    }

                                </div>

                            </div>

                        `;
                    }
            },


            legend: {

                show: true,

                position: 'top',

                horizontalAlign: 'center'
            }
        };


        if (cairanChart) {

            cairanChart.destroy();
        }


        cairanChart =
            new ApexCharts(

                document.querySelector(
                    '#monitoringCairanChart'
                ),

                options
            );


        cairanChart.render();
    }


    // ==========================================================
    // CLICK CAIRAN
    // ==========================================================

    function handleCairanChartClick(
        event,
        chartContext
    ) {

        const globals =
            chartContext.w.globals;


        const rect =
            chartContext.el.getBoundingClientRect();


        const cursorX =
            event.clientX - rect.left;


        const gridX =
            globals.translateX;


        const gridWidth =
            globals.gridWidth;


        if (
            cursorX < gridX ||
            cursorX > gridX + gridWidth
        ) {
            return;
        }


        const minutes =
            generateMinutes(15);


        const ratioX =
            (cursorX - gridX) /
            gridWidth;


        let minuteIndex =
            Math.round(
                ratioX *
                (minutes.length - 1)
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


        const existing =
            cairanData.find(
                function (item) {

                    return (
                        Math.round(
                            durationToMinute(
                                item.waktu
                            )
                        ) === minute
                    );
                }
            );


        openCairanModal(
            minute,
            existing?.jenis || 'masuk',
            existing
        );
    }


    // ==========================================================
    // OPEN MODAL CAIRAN
    // ==========================================================

    function openCairanModal(
        minute,
        jenis,
        existing = null
    ) {

        selectedCairan = {

            id:
                existing?.id || null,

            menit:
                minute,

            jenis:
                existing?.jenis || jenis,

            nilai:
                existing?.nilai ?? '',

            keterangan:
                existing?.keterangan || ''
        };


        $('#cairanMenitBadge')
            .text(
                `Menit ${minute}`
            );


        $('#cairanWaktu')
            .val(
                minuteToDuration(minute)
            );


        $('#cairanJenis')
            .val(
                selectedCairan.jenis
            );


        $('#cairanNilai')
            .val(
                selectedCairan.nilai
            );


        $('#cairanKeterangan')
            .val(
                selectedCairan.keterangan
            );


        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById(
                    'modalCairanAnestesi'
                )
            )
            .show();
    }


    // ==========================================================
    // SIMPAN CAIRAN
    // ==========================================================

    function simpanCairan() {

        const jenis =
            $('#cairanJenis').val();


        const nilai =
            Number(
                $('#cairanNilai').val()
            );


        const keterangan =
            $('#cairanKeterangan').val();


        if (!jenis) {

            iziToast.warning({

                title:
                    'Perhatian',

                message:
                    'Silakan pilih jenis cairan.',

                position:
                    'topRight'
            });

            return;
        }


        if (
            Number.isNaN(nilai) ||
            nilai < 0 ||
            nilai > 300
        ) {

            iziToast.warning({

                title:
                    'Perhatian',

                message:
                    'Jumlah cairan harus berada antara 0–300 ml.',

                position:
                    'topRight'
            });

            return;
        }


        saveDetail({

            id:
                selectedCairan.id,

            jenis_data:
                'cairan',

            waktu:
                minuteToDuration(
                    selectedCairan.menit
                ),

            baris:
                null,

            zat:
                null,

            nilai:
                nilai,

            jenis:
                jenis,

            keterangan:
                keterangan
        });
    }


    // ==========================================================
    // SAVE DETAIL
    // ==========================================================

    function saveDetail(data) {

        if (detailSaving) {
            return;
        }


        detailSaving = true;


        $.ajax({

            url:
                URL_DETAIL_SAVE,

            type:
                'POST',

            data:
                data,

            headers: {

                'X-CSRF-TOKEN':
                    CSRF_TOKEN
            },


            success:
                function (res) {

                    $('.modal.show')
                        .each(
                            function () {

                                const modal =
                                    bootstrap.Modal
                                        .getInstance(
                                            this
                                        );

                                if (modal) {
                                    modal.hide();
                                }
                            }
                        );


                    getMonitoringDetail();


                    iziToast.success({

                        title:
                            'Berhasil',

                        message:
                            'Data monitoring berhasil disimpan.',

                        position:
                            'topRight'
                    });
                },


            error:
                function (xhr) {

                    console.error(
                        'POST Monitoring Detail:',
                        xhr.responseText
                    );


                    let message =
                        'Data gagal disimpan.';


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

                        title:
                            'Gagal',

                        message:
                            message,

                        position:
                            'topRight'
                    });
                },


            complete:
                function () {

                    detailSaving = false;
                }
        });
    }


    // ==========================================================
    // RESET MODAL
    // ==========================================================

    function resetZatModal() {

        selectedZat = {

            id: null,

            menit: null,

            baris: null,

            zat: '',

            keterangan: ''
        };


        $('#zatAnestesiMenitBadge')
            .text('Menit 0');

        $('#zatAnestesiWaktu')
            .val('');

        $('#zatAnestesiBaris')
            .val('');

        $('#zatAnestesiNama')
            .val('');

        $('#zatAnestesiKeterangan')
            .val('');
    }


    function resetTemperaturModal() {

        selectedTemperatur = {

            id: null,

            menit: null,

            jenis: null,

            nilai: null,

            keterangan: ''
        };


        $('#temperaturMenitBadge')
            .text('Menit 0');

        $('#temperaturWaktu')
            .val('');

        $('#temperaturJenis')
            .val('');

        $('#temperaturNilai')
            .val('');

        $('#temperaturKeterangan')
            .val('');
    }


    function resetCairanModal() {

        selectedCairan = {

            id: null,

            menit: null,

            jenis: null,

            nilai: null,

            keterangan: ''
        };


        $('#cairanMenitBadge')
            .text('Menit 0');

        $('#cairanWaktu')
            .val('');

        $('#cairanJenis')
            .val('');

        $('#cairanNilai')
            .val('');

        $('#cairanKeterangan')
            .val('');
    }


    // ==========================================================
    // EVENT
    // ==========================================================

    $(document).on(
        'click',
        '#btnSimpanZatAnestesi',
        function () {

            simpanZatAnestesi();
        }
    );


    $(document).on(
        'click',
        '#btnSimpanTemperatur',
        function () {

            simpanTemperatur();
        }
    );


    $(document).on(
        'click',
        '#btnSimpanCairan',
        function () {

            simpanCairan();
        }
    );


    // ==========================================================
    // REFRESH ZAT
    // ==========================================================

    $(document).on(
        'click',
        '#btnRefreshZatAnestesi',
        function () {

            getMonitoringDetail();
        }
    );


    // ==========================================================
    // REFRESH TEMPERATUR
    // ==========================================================

    $(document).on(
        'click',
        '#btnRefreshTemperatur',
        function () {

            getMonitoringDetail();
        }
    );


    // ==========================================================
    // REFRESH CAIRAN
    // ==========================================================

    $(document).on(
        'click',
        '#btnRefreshCairan',
        function () {

            getMonitoringDetail();
        }
    );


    // ==========================================================
    // MODAL CLOSED
    // ==========================================================

    $(document).on(
        'hidden.bs.modal',
        '#modalZatAnestesi',
        function () {

            resetZatModal();
        }
    );


    $(document).on(
        'hidden.bs.modal',
        '#modalTemperaturAnestesi',
        function () {

            resetTemperaturModal();
        }
    );


    $(document).on(
        'hidden.bs.modal',
        '#modalCairanAnestesi',
        function () {

            resetCairanModal();
        }
    );


    // ==========================================================
    // INITIAL LOAD
    // ==========================================================

    $(function () {

        getMonitoringDetail();

    });

})();
</script>
