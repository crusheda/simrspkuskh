<div class="row align-items-center" id="form_pengkajian_ulang_humpty_dumpty">
    {{-- ==========================================================
        HEADER
    =========================================================== --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <h4 class="mb-1">
                Pengkajian Ulang Resiko Jatuh Humpty Dumpty Scale
            </h4>
            <h6 class="fw-light mb-0">
                (<a class="text-warning">Pasien Pediatri</a>)
            </h6>
        </div>
    </div>
    {{-- ==========================================================
        FORM INPUT
    =========================================================== --}}
    <div class="col-md-12">
        <div class="row">
            {{-- USIA --}}
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Usia
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_usia"
                        data-label="Usia"
                    >
                        <option value="">Pilih</option>
                        <option value="4">
                            &lt; 3 tahun
                        </option>
                        <option value="3">
                            3 - 7 tahun
                        </option>
                        <option value="2">
                            7 - 13 tahun
                        </option>
                        <option value="1">
                            ≥ 13 tahun
                        </option>
                    </select>
                </div>
            </div>
            {{-- JENIS KELAMIN --}}
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Jenis Kelamin
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_jk"
                        data-label="Jenis Kelamin"
                    >
                        <option value="">Pilih</option>
                        <option value="2">
                            Laki-laki
                        </option>
                        <option value="1">
                            Perempuan
                        </option>
                    </select>
                </div>
            </div>
            {{-- DIAGNOSA --}}
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Diagnosa
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_diagnosa"
                        data-label="Diagnosa"
                    >
                        <option value="">Pilih</option>
                        <option value="4">
                            Diagnosa neurologi
                        </option>
                        <option value="3">
                            Perubahan oksigenasi
                        </option>
                        <option value="2">
                            Gangguan perilaku/psikiatri
                        </option>
                        <option value="1">
                            Diagnosis lainnya
                        </option>
                    </select>
                </div>
            </div>
            {{-- GANGGUAN KOGNITIF --}}
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Gangguan Kognitif
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_gangguan_kognitif"
                        data-label="Gangguan Kognitif"
                    >
                        <option value="">Pilih</option>
                        <option value="3">
                            Tidak menyadari keterbatasan dirinya
                        </option>
                        <option value="2">
                            Lupa akan adanya keterbatasan
                        </option>
                        <option value="1">
                            Orientasi baik terhadap diri sendiri
                        </option>
                    </select>
                </div>
            </div>
            {{-- FAKTOR LINGKUNGAN --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Faktor Lingkungan
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_faktor_lingkungan"
                        data-label="Faktor Lingkungan"
                    >
                        <option value="">Pilih</option>
                        <option value="4">
                            Riwayat jatuh / bayi diletakkan di tempat tidur dewasa
                        </option>
                        <option value="3">
                            Pasien menggunakan alat bantu / bayi diletakkan dalam tempat tidur bayi / perabot rumah
                        </option>
                        <option value="2">
                            Pasien diletakkan di tempat tidur
                        </option>
                        <option value="1">
                            Area di luar rumah sakit
                        </option>
                    </select>
                </div>
            </div>
            {{-- PEMBEDAHAN / SEDASI / ANESTESI --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Pembedahan / Sedasi / Anestesi
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_pembedahan_sedasi_anestesi"
                        data-label="Pembedahan / Sedasi / Anestesi"
                    >
                        <option value="">Pilih</option>
                        <option value="3">
                            Dalam 24 jam
                        </option>
                        <option value="2">
                            Dalam 48 jam
                        </option>
                        <option value="1">
                            &gt; 48 jam atau tidak menjalani pembedahan / sedasi / anestesi
                        </option>
                    </select>
                </div>
            </div>
            {{-- MEDIKAMENTOSA --}}
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">
                        Penggunaan Medikamentosa
                    </label>
                    <select
                        class="form-select humpty-input"
                        name="purj_penggunaan_medikamentosa"
                        data-label="Penggunaan Medikamentosa"
                    >
                        <option value="">Pilih</option>
                        <option value="3">
                            Penggunaan multipel : sedatif, obat hipnosis, barbiturat,
                            fenitoin, antidepresan, pencahar, diuretik, narkose
                        </option>
                        <option value="2">
                            Penggunaan salah satu obat di atas
                        </option>
                        <option value="1">
                            Penggunaan medikasi lainnya / tidak ada medikasi
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
                    id="humpty_score_info"
                >
                    <div>
                        <div class="text-muted small">
                            <b>Total Skor</b> Humpty Dumpty
                        </div>
                        <div
                            class="fs-3 fw-bold"
                            id="humpty_total_score"
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
                            id="humpty_risk_category"
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
                    id="btnSimpanHumptyDumpty"
                >
                    <i class="ri-add-box-line me-1"></i>
                    Submit
                </button>
                <a class="btn btn-subtle-success waves-effect waves-light" data-bs-toggle="collapse" href="#collapse-table-pengkajian-ulang-humpty-dumpty" role="button" aria-expanded="true" aria-controls="collapse-table-pengkajian-ulang-humpty-dumpty"><i class="ri-table-view"></i></a>
                {{-- <button
                    type="button"
                    class="btn btn-subtle-warning"
                    onclick="getHumptyDumpty()"
                    id="btn-refresh-table-pengkajian-ulang-humpty-dumpty"
                >
                    <i class="ri-refresh-line"></i>
                </button> --}}
            </div>
        </div>
    </div>
    {{-- ==========================================================
        TABEL RIWAYAT
    =========================================================== --}}
    <div class="col-md-12 multi-collapse collapse show" id="collapse-table-pengkajian-ulang-humpty-dumpty">
        <div class="table-responsive">
            <table
                class="table table-hover table-border-bottom align-middle mb-0"
                id="tableHumptyDumpty"
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
                <tbody id="humptyDumptyTableBody">
                    <tr>
                        <td
                            colspan="6"
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
        '#form_pengkajian_ulang_humpty_dumpty'
    );
    const $btnSimpan = $section.find(
        '#btnSimpanHumptyDumpty'
    );
    const $tableBody = $section.find(
        '#humptyDumptyTableBody'
    );
    const $totalScore = $section.find(
        '#humpty_total_score'
    );
    const $riskCategory = $section.find(
        '#humpty_risk_category'
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
    function hitungTotalSkor() {
        let total = 0;
        $form
            .find('.humpty-input')
            .each(function () {
                const value = parseInt(
                    $(this).val(),
                    10
                );
                if (!isNaN(value)) {
                    total += value;
                }
            });
        updateRisk(total);
        return total;
    }
    // ==========================================================
    // UPDATE RISIKO
    // ==========================================================
    function updateRisk(total) {
        $totalScore.text(total);
        $riskCategory
            .removeClass(
                'text-success text-danger text-warning'
            );
        if (total >= 7 && total <= 11) {
            $riskCategory
                .text('Risiko Rendah (RR)')
                .addClass('text-success');
        }
        else if (total >= 12) {
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
    function resetForm() {
        $form
            .find('.humpty-input')
            .val('');
        hitungTotalSkor();
    }
    // ==========================================================
    // GET DATA
    // ==========================================================
    function getHumptyDumpty() {
        if (!$form.length) {
            return;
        }
        isLoading = true;
        $tableBody.html(`
            <tr>
                <td
                    colspan="14"
                    class="text-center py-4"
                >
                    <div class="spinner-border spinner-border-sm me-2"></div>
                    Memuat data...
                </td>
            </tr>
        `);
        $.ajax({
            url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/humptydumpty/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $('#btn-refresh-table-pengkajian-ulang-humpty-dumpty').prop('disabled',true);
                $('#btn-refresh-table-pengkajian-ulang-humpty-dumpty').find('i').addClass('ri-spin');
            },
            success: function (res) {
                const data = res.data || [];
                renderTable(data);
            },
            error: function (xhr) {
                console.error(
                    'Error get Humpty Dumpty:',
                    xhr.responseText
                );
                $tableBody.html(`
                    <tr>
                        <td
                            colspan="14"
                            class="text-center text-danger py-4"
                        >
                            Gagal mengambil data pengkajian.
                        </td>
                    </tr>
                `);
            },
            complete: function () {
                isLoading = false;
                $('#btn-refresh-table-pengkajian-ulang-humpty-dumpty').find('i').removeClass('ri-spin');
                $('#btn-refresh-table-pengkajian-ulang-humpty-dumpty').prop('disabled',false);
            }
        });
    }
    // ==========================================================
    // RENDER TABLE
    // ==========================================================
    function renderTable(data) {
        if (!Array.isArray(data) || data.length === 0) {
            $tableBody.html(`
                <tr>
                    <td
                        colspan="6"
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
            const skor = Number(item.SKOR || 0);
            // ======================================================
            // KATEGORI RISIKO
            // ======================================================
            let riskText = '-';
            let riskClass = 'text-muted';
            if (skor >= 7 && skor <= 11) {
                riskText = 'Risiko Rendah (RR)';
                riskClass = 'text-success';
            }
            else if (skor >= 12) {
                riskText = 'Risiko Tinggi (RT)';
                riskClass = 'text-danger';
            }
            // ======================================================
            // PARAMETER
            // ======================================================
            const parameterHtml = `
                <ul>
                    <li>
                        <strong>Usia :</strong>
                        ${escapeHtml(item.USIA_LABEL || '-')}
                        <span class="text-muted">
                            (Skor ${escapeHtml(item.USIA ?? 0)})
                        </span>
                    </li>
                    <li>
                        <strong>Jenis Kelamin :</strong>
                        ${escapeHtml(item.JENIS_KELAMIN_LABEL || '-')}
                        <span class="text-muted">
                            (Skor ${escapeHtml(item.JENIS_KELAMIN ?? 0)})
                        </span>
                    </li>
                    <li>
                        <strong>Diagnosa :</strong>
                        ${escapeHtml(item.DIAGNOSA_LABEL || '-')}
                        <span class="text-muted">
                            (Skor ${escapeHtml(item.DIAGNOSA ?? 0)})
                        </span>
                    </li>
                    <li>
                        <strong>Gangguan Kognitif :</strong>
                        ${escapeHtml(item.GANGGUAN_KOGNITIF_LABEL || '-')}
                        <span class="text-muted">
                            (Skor ${escapeHtml(item.GANGGUAN_KOGNITIF ?? 0)})
                        </span>
                    </li>
                    <li>
                        <strong>Faktor Lingkungan :</strong>
                        ${escapeHtml(item.FAKTOR_LINGKUNGAN_LABEL || '-')}
                        <span class="text-muted">
                            (Skor ${escapeHtml(item.FAKTOR_LINGKUNGAN ?? 0)})
                        </span>
                    </li>
                    <li>
                        <strong>Pembedahan / Sedasi / Anestesi :</strong>
                        ${escapeHtml(
                            item.PEMBEDAHAN_SEDASI_ANESTESI_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.PEMBEDAHAN_SEDASI_ANESTESI ?? 0
                            )})
                        </span>
                    </li>
                    <li>
                        <strong>Penggunaan Medikamentosa :</strong>
                        ${escapeHtml(
                            item.PENGGUNAAN_MEDIKAMENTOSA_LABEL || '-'
                        )}
                        <span class="text-muted">
                            (Skor ${escapeHtml(
                                item.PENGGUNAAN_MEDIKAMENTOSA ?? 0
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
                            class="btn btn-sm btn-subtle-danger btn-hapus-humpty"
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
                        <small>Oleh&nbsp;
                        ${escapeHtml(
                            item.NAMA_USER ||
                            item.OLEH ||
                            '-'
                        )}
                        </small>
                    </td>
                    {{-- PARAMETER --}}
                    <td class="parameter-humpty">
                        ${parameterHtml}
                    </td>
                    {{-- RISIKO --}}
                    <td class="text-center">
                        <span class="fw-bold ${riskClass}">
                            ${escapeHtml(riskText)}
                        </span>
                        <br><br>
                        Total Skor:
                        <div
                            class="fw-bold fs-4"
                        >
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
    function simpanHumptyDumpty() {
        if (isLoading || isSaving) {
            return;
        }
        // ------------------------------------------------------
        // CEK SEMUA PARAMETER
        // ------------------------------------------------------
        let valid = true;
        $form
            .find('.humpty-input')
            .each(function () {
                const value = $(this).val();
                if (
                    value === null ||
                    value === ''
                ) {
                    valid = false;
                    $(this).addClass('is-invalid');
                }
                else {
                    $(this).removeClass('is-invalid');
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
        const totalSkor = hitungTotalSkor();
        if (totalSkor < 7) {
            iziToast.warning({
                title: 'Skor Tidak Valid',
                message:
                    'Total skor Humpty Dumpty belum memenuhi nilai minimum.',
                position: 'topRight'
            });
            return;
        }
        // ------------------------------------------------------
        // DATA
        // ------------------------------------------------------
        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan,
            SKOR: totalSkor
        });
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
            url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/humptydumpty/${kunjungan}/simpan`,
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
                        'Pengkajian ulang Humpty Dumpty berhasil disimpan.',
                    position: 'topRight'
                });
                resetForm();
                getHumptyDumpty();
            },
            error: function (xhr) {
                console.error(
                    'Error simpan Humpty Dumpty:',
                    xhr.responseText
                );
                let message =
                    'Data pengkajian gagal disimpan.';
                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (
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
    function hapusHumptyDumpty(id) {
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
                url: `/api/v2/emr/pengkajian/pengkajianulang/resikojatuh/humptydumpty/${kunjungan}/hapus/${id}`,
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
                    getHumptyDumpty();
                },
                error: function (xhr) {
                    console.error(
                        'Error hapus Humpty Dumpty:',
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
        '.humpty-input',
        function () {
            $(this).removeClass('is-invalid');
            hitungTotalSkor();
        }
    );
    // ==========================================================
    // TOMBOL SIMPAN
    // ==========================================================
    $btnSimpan.on(
        'click',
        function () {
            simpanHumptyDumpty();
        }
    );
    // ==========================================================
    // TOMBOL HAPUS
    // ==========================================================
    $tableBody.on(
        'click',
        '.btn-hapus-humpty',
        function () {
            const id =
                $(this).data('id');
            hapusHumptyDumpty(id);
        }
    );
    // ==========================================================
    // INIT
    // ==========================================================
    $(function () {
        if (!$form.length) {
            return;
        }
        hitungTotalSkor();
        getHumptyDumpty();
    });
})();
</script>
