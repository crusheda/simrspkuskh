<div class="row align-items-center" id="form_pengkajian_ulang_skala_morse_dewasa">
    {{-- ==========================================================
        HEADER
    =========================================================== --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <h4 class="mb-1">
                Pengkajian Ulang Resiko Jatuh Skala Morse
            </h4>
            <h6 class="fw-light mb-0">
                (<a class="text-warning">Pasien Dewasa</a>)
            </h6>
        </div>
    </div>

    {{-- ==========================================================
        FORM INPUT
    =========================================================== --}}
    <div class="col-md-12">
        <div class="row">

            {{-- RIWAYAT JATUH --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Riwayat Jatuh
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_riwayat_jatuh"
                        data-label="Riwayat Jatuh"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Tidak
                        </option>
                        <option value="25">
                            Ya
                        </option>
                    </select>
                </div>
            </div>

            {{-- DIAGNOSIS SEKUNDER --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Diagnosis Sekunder
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_diagnosis_sekunder"
                        data-label="Diagnosis Sekunder"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Tidak
                        </option>
                        <option value="15">
                            Ya
                        </option>
                    </select>
                </div>
            </div>

            {{-- ALAT BANTU JALAN --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Alat Bantu Jalan
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_alat_bantu_jalan"
                        data-label="Alat Bantu Jalan"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Tidak ada / Bed rest / Dibantu perawat
                        </option>
                        <option value="15">
                            Kruk / Tongkat / Walker
                        </option>
                        <option value="30">
                            Berpegangan pada furniture
                        </option>
                    </select>
                </div>
            </div>

            {{-- TERAPI IV / HEPARIN LOCK --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        {{-- Terapi IV / Heparin Lock --}}
                        Menggunakan Infus Heparin / Pengencer Darah
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_terapi_iv"
                        data-label="Terapi IV / Heparin Lock"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Tidak
                        </option>
                        <option value="20">
                            Ya
                        </option>
                    </select>
                </div>
            </div>

            {{-- GAYA BERJALAN --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Gaya Berjalan
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_gaya_berjalan"
                        data-label="Gaya Berjalan"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Normal / Bed rest / Immobilisasi
                        </option>
                        <option value="10">
                            Lemah
                        </option>
                        <option value="20">
                            Terganggu
                        </option>
                    </select>
                </div>
            </div>

            {{-- STATUS MENTAL --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Status Mental
                    </label>
                    <select
                        class="form-select morse-dewasa-input"
                        name="mfsd_status_mental"
                        data-label="Status Mental"
                    >
                        <option value="">Pilih</option>
                        <option value="0">
                            Menyadari kemampuan sendiri
                        </option>
                        <option value="15">
                            Lupa keterbatasan / Overestimate kemampuan
                        </option>
                    </select>
                </div>
            </div>

        </div>

        {{-- ==================================================
            TOTAL SKOR REALTIME
        =================================================== --}}
        <div class="row mt-2">
            <div class="col-md-8 mb-3">
                <div
                    class="alert alert-light border mb-0 d-flex align-items-center"
                    id="morse_dewasa_score_info"
                >
                    <div>
                        <div class="text-muted small">
                            <b>Total Skor</b> Skala Morse
                        </div>
                        <div
                            class="fs-3 fw-bold"
                            id="morse_dewasa_total_score"
                        >
                            0
                        </div>
                    </div>

                    <div class="ms-4">
                        <div class="text-muted small">
                            Kategori Risiko
                        </div>
                        <div
                            class="fs-3 fw-bold"
                            id="morse_dewasa_risk_category"
                        >
                            -
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="col-md-4 d-flex align-items-center justify-content-center btn-group mb-3">
                <button
                    type="button"
                    class="btn btn-subtle-primary"
                    id="btnSimpanMorseDewasa"
                >
                    <i class="ri-add-box-line me-1"></i>
                    Submit
                </button>

                <a
                    href="javascript:void(0)"
                    class="btn btn-subtle-success waves-effect waves-light"
                    id="btn-table-pengkajian-ulang-morse-dewasa"
                    role="button"
                    aria-expanded="true"
                >
                    <i class="ri-table-view"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ==========================================================
        TABEL RIWAYAT
    =========================================================== --}}
    <div
        class="col-md-12"
        id="table-pengkajian-ulang-morse-dewasa"
    >
        <div class="table-responsive">
            <table
                class="table table-hover table-border-bottom align-middle mb-0"
                id="tableMorseDewasa"
            >
                <thead class="table-light">
                    <tr>
                        {{-- AKSI --}}
                        <th
                            class="text-center"
                            style="width: 80px;"
                        >
                            Aksi
                        </th>

                        {{-- TANGGAL --}}
                        <th
                            class="text-center"
                            style="min-width: 150px;"
                        >
                            Tanggal & Jam
                        </th>

                        {{-- PARAMETER --}}
                        <th
                            class="text-center"
                            style="min-width: 500px;"
                        >
                            Parameter
                        </th>

                        {{-- RISIKO --}}
                        <th
                            class="text-center"
                            style="min-width: 150px;"
                        >
                            Skor Risiko
                        </th>
                    </tr>
                </thead>

                <tbody id="morseDewasaTableBody">
                    <tr>
                        <td
                            colspan="4"
                            class="text-center text-muted py-4"
                        >
                            Belum ada data pengkajian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ==========================================================
    // VARIABLE
    // ==========================================================
    const $section = $(@json($section));

    const $form = $section.find(
        '#form_pengkajian_ulang_skala_morse_dewasa'
    );

    const $btnSimpan = $section.find(
        '#btnSimpanMorseDewasa'
    );

    const $tableBody = $section.find(
        '#morseDewasaTableBody'
    );

    const $totalScore = $section.find(
        '#morse_dewasa_total_score'
    );

    const $riskCategory = $section.find(
        '#morse_dewasa_risk_category'
    );

    let isLoading = false;
    let isSaving = false;

    // ==========================================================
    // HELPER
    // ==========================================================
    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ==========================================================
    // HITUNG TOTAL SKOR
    // ==========================================================
    function hitungTotalSkorMorseDewasa() {
        let total = 0;
        let jumlahTerisi = 0;
        const totalParameter = $form.find('.morse-dewasa-input').length;

        $form
            .find('.morse-dewasa-input')
            .each(function () {
                const value = $(this).val();

                if (value !== null && value !== '') {
                    jumlahTerisi++;

                    const score = parseInt(value, 10);

                    if (!isNaN(score)) {
                        total += score;
                    }
                }
            });

        updateKategoriRisikoMorseDewasa(
            total,
            jumlahTerisi === totalParameter
        );

        return total;
    }

    // ==========================================================
    // UPDATE RISIKO
    // ==========================================================
    function updateKategoriRisikoMorseDewasa(total, semuaTerisi) {
        // ------------------------------------------------------
        // TOTAL SKOR SELALU DITAMPILKAN
        // ------------------------------------------------------
        $totalScore.text(total);

        $riskCategory
            .removeClass(
                'text-success text-danger text-warning'
            );

        // ------------------------------------------------------
        // JIKA BELUM SEMUA PARAMETER TERISI
        // ------------------------------------------------------
        if (!semuaTerisi) {
            $riskCategory
                .text('-')
                .addClass('text-warning');

            return;
        }

        // ------------------------------------------------------
        // KATEGORI RISIKO
        // ------------------------------------------------------
        if (total >= 0 && total <= 24) {
            $riskCategory
                .text('Risiko Rendah (RR)')
                .addClass('text-success');
        }
        else if (total >= 25 && total <= 44) {
            $riskCategory
                .text('Risiko Sedang (RS)')
                .addClass('text-warning');
        }
        else if (total >= 45) {
            $riskCategory
                .text('Risiko Tinggi (RT)')
                .addClass('text-danger');
        }
        else {
            $riskCategory
                .text('-')
                .addClass('text-warning');
        }
    }

    // ==========================================================
    // RESET FORM
    // ==========================================================
    function resetFormMorseDewasa() {
        $form
            .find('.morse-dewasa-input')
            .val('');

        $form
            .find('.morse-dewasa-input')
            .removeClass('is-invalid');

        hitungTotalSkorMorseDewasa();
    }

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getMorseDewasa() {
        if (!$form.length) {
            return;
        }

        isLoading = true;

        $tableBody.html(`
            <tr>
                <td
                    colspan="4"
                    class="text-center py-4"
                >
                    <div class="spinner-border spinner-border-sm me-2"></div>
                    Memuat data...
                </td>
            </tr>
        `);

        $.ajax({
            url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/morse/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {
                const data = res.data || [];

                renderMorseDewasaTable(data);
            },

            error: function (xhr) {
                console.error(
                    'Error get Skala Morse Dewasa:',
                    xhr.responseText
                );

                $tableBody.html(`
                    <tr>
                        <td
                            colspan="4"
                            class="text-center text-danger py-4"
                        >
                            Gagal mengambil data pengkajian.
                        </td>
                    </tr>
                `);
            },

            complete: function () {
                isLoading = false;
            }
        });
    }

    // ==========================================================
    // RENDER TABLE
    // ==========================================================
    function renderMorseDewasaTable(data) {
        if (!Array.isArray(data) || data.length === 0) {
            $tableBody.html(`
                <tr>
                    <td
                        colspan="4"
                        class="text-center text-muted py-4"
                    >
                        Belum ada data pengkajian.
                    </td>
                </tr>
            `);

            return;
        }

        let html = '';

        data.forEach(function (item) {

            const skor = Number(
                item.SKOR || 0
            );

            // ======================================================
            // KATEGORI RISIKO
            // ======================================================
            let riskText = '-';
            let riskClass = 'text-muted';

            if (skor >= 0 && skor <= 24) {
                riskText = 'Risiko Rendah (RR)';
                riskClass = 'text-success';
            }
            else if (skor >= 25 && skor <= 44) {
                riskText = 'Risiko Sedang (RS)';
                riskClass = 'text-warning';
            }
            else if (skor >= 45) {
                riskText = 'Risiko Tinggi (RT)';
                riskClass = 'text-danger';
            }

            // ======================================================
            // PARAMETER
            // ======================================================
            const parameterHtml = `
                <ul>
                    <li>
                        <strong>Riwayat Jatuh :</strong>
                        ${escapeHtml(
                            item.RIWAYAT_JATUH_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.RIWAYAT_JATUH ?? 0
                            )})
                        </span>
                    </li>

                    <li>
                        <strong>Diagnosis Sekunder :</strong>
                        ${escapeHtml(
                            item.DIAGNOSIS_SEKUNDER_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.DIAGNOSIS_SEKUNDER ?? 0
                            )})
                        </span>
                    </li>

                    <li>
                        <strong>Alat Bantu Jalan :</strong>
                        ${escapeHtml(
                            item.ALAT_BANTU_JALAN_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.ALAT_BANTU_JALAN ?? 0
                            )})
                        </span>
                    </li>

                    <li>
                        <strong>Terapi IV / Heparin Lock :</strong>
                        ${escapeHtml(
                            item.TERAPI_IV_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.TERAPI_IV ?? 0
                            )})
                        </span>
                    </li>

                    <li>
                        <strong>Gaya Berjalan :</strong>
                        ${escapeHtml(
                            item.GAYA_BERJALAN_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.GAYA_BERJALAN ?? 0
                            )})
                        </span>
                    </li>

                    <li>
                        <strong>Status Mental :</strong>
                        ${escapeHtml(
                            item.STATUS_MENTAL_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.STATUS_MENTAL ?? 0
                            )})
                        </span>
                    </li>
                </ul>
            `;

            // ======================================================
            // ROW
            // ======================================================
            html += `
                <tr>

                    {{-- AKSI --}}
                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-sm btn-subtle-danger btn-hapus-morse-dewasa"
                            data-id="${escapeHtml(item.ID)}"
                            title="Hapus Pengkajian"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>

                    {{-- TANGGAL & JAM --}}
                    <td class="text-center">
                        ${escapeHtml(
                            item.TANGGAL_FORMATTED ||
                            item.TANGGAL ||
                            '-'
                        )}

                        <br>

                        <small>
                            Oleh&nbsp;
                            ${escapeHtml(
                                item.NAMA_USER ||
                                item.OLEH ||
                                '-'
                            )}
                        </small>
                    </td>

                    {{-- PARAMETER --}}
                    <td class="parameter-morse-dewasa">
                        ${parameterHtml}
                    </td>

                    {{-- RISIKO --}}
                    <td class="text-center">
                        <span class="fw-bold ${riskClass}">
                            ${escapeHtml(riskText)}
                        </span>

                        <br><br>

                        Total Skor:

                        <div class="fw-bold fs-4">
                            ${escapeHtml(skor)}
                        </div>
                    </td>

                </tr>
            `;
        });

        $tableBody.html(html);
    }

    // ==========================================================
    // SIMPAN
    // ==========================================================
    function simpanMorseDewasa() {
        if (isLoading || isSaving) {
            return;
        }

        // ------------------------------------------------------
        // CEK SEMUA PARAMETER
        // ------------------------------------------------------
        let valid = true;

        $form
            .find('.morse-dewasa-input')
            .each(function () {

                const value = $(this).val();

                if (
                    value === null ||
                    value === ''
                ) {
                    valid = false;

                    $(this)
                        .addClass('is-invalid');
                }
                else {
                    $(this)
                        .removeClass('is-invalid');
                }
            });

        if (!valid) {
            iziToast.warning({
                title: 'Data Belum Lengkap',
                message:
                    'Silakan lengkapi seluruh parameter pengkajian terlebih dahulu.',
                position: 'topRight'
            });

            return;
        }

        // ------------------------------------------------------
        // HITUNG SKOR
        // ------------------------------------------------------
        const totalSkor =
            hitungTotalSkorMorseDewasa();

        if (
            totalSkor < 0 ||
            totalSkor > 125
        ) {
            iziToast.warning({
                title: 'Skor Tidak Valid',
                message:
                    'Total skor Skala Morse tidak valid.',
                position: 'topRight'
            });

            return;
        }

        // ------------------------------------------------------
        // DATA
        // ------------------------------------------------------
        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan,
                SKOR: totalSkor
            }
        );

        // ------------------------------------------------------
        // LOADING
        // ------------------------------------------------------
        isSaving = true;

        $btnSimpan
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                ></span>
                Menyimpan...
            `);

        // ------------------------------------------------------
        // AJAX
        // ------------------------------------------------------
        $.ajax({
            url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/morse/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },

            success: function (res) {

                iziToast.success({
                    title: 'Berhasil',
                    message:
                        'Pengkajian ulang Skala Morse berhasil disimpan.',
                    position: 'topRight'
                });

                resetFormMorseDewasa();

                getMorseDewasa();
            },

            error: function (xhr) {

                console.error(
                    'Error simpan Skala Morse Dewasa:',
                    xhr.responseText
                );

                let message =
                    'Data pengkajian gagal disimpan.';

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
                }
                else if (
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

                isSaving = false;

                $btnSimpan
                    .prop('disabled', false)
                    .html(`
                        <i class="ri-save-line me-1"></i>
                        Simpan Pengkajian
                    `);
            }
        });
    }

    // ==========================================================
    // HAPUS
    // STATUS = 0
    // ==========================================================
    function hapusMorseDewasa(id) {

        if (!id) {
            return;
        }

        Swal.fire({
            title: 'Hapus Pengkajian?',
            text:
                'Data tidak akan benar-benar dihapus dari database.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/morse/${kunjungan}/hapus/${id}`,
                type: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content')
                },

                success: function () {

                    iziToast.success({
                        title: 'Berhasil',
                        message:
                            'Pengkajian berhasil dihapus.',
                        position: 'topRight'
                    });

                    getMorseDewasa();
                },

                error: function (xhr) {

                    console.error(
                        'Error hapus Skala Morse Dewasa:',
                        xhr.responseText
                    );

                    iziToast.error({
                        title: 'Gagal',
                        message:
                            xhr.responseJSON?.message ||
                            'Data gagal dihapus.',
                        position: 'topRight'
                    });
                }
            });
        });
    }

    // ==========================================================
    // EVENT REALTIME SCORE
    // ==========================================================
    $form.on(
        'change',
        '.morse-dewasa-input',
        function () {

            $(this)
                .removeClass('is-invalid');

            hitungTotalSkorMorseDewasa();
        }
    );

    // ==========================================================
    // TOMBOL SIMPAN
    // ==========================================================
    $btnSimpan.on(
        'click',
        function () {
            simpanMorseDewasa();
        }
    );

    // ==========================================================
    // TOMBOL HAPUS
    // ==========================================================
    $tableBody.on(
        'click',
        '.btn-hapus-morse-dewasa',
        function () {

            const id =
                $(this).data('id');

            hapusMorseDewasa(id);
        }
    );

    // ==========================================================
    // INIT
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        hitungTotalSkorMorseDewasa();
        getMorseDewasa();

        $('#btn-table-pengkajian-ulang-morse-dewasa').on('click', function (e) {
            e.preventDefault();
            $('#table-pengkajian-ulang-morse-dewasa').prop(
                'hidden',
                !$('#table-pengkajian-ulang-morse-dewasa').prop('hidden')
            );
        });
    });

})();
</script>
