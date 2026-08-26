<div class="form-group">
    <h6>Diagnosis</h6>
    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="flex-grow-1">
            <textarea
                class="form-control"
                name="diag_detail"
                placeholder="Masukkan Diagnosa"
                rows="1"
            ></textarea>
        </div>
        <div class="flex-shrink-0">
            <div class="form-check mb-0">
                <input
                    class="form-check-input check-primary"
                    type="checkbox"
                    name="diag_utama"
                    value="1"
                    id="diag_utama"
                >
                <label class="form-check-label" for="diag_utama">
                    Diagnosis Utama
                </label>
            </div>
        </div>
        <div class="flex-shrink-0">
            <div class="btn-group">
                <button
                    type="button"
                    class="btn btn-success"
                    id="btnTambahDiagnosis"
                    onclick="tambahDiagnosis()"
                >
                    <i class="ri-add-box-line"></i>
                </button>
                <button
                    type="button"
                    class="btn btn-subtle-warning"
                    id="btnRefreshDiagnosis"
                    onclick="getDiagnosis()"
                >
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table align-middle mb-1">
            <thead>
                <colgroup>
                    <col style="width: 1%;">
                    <col>
                    <col style="width: 20%;">
                    <col style="width: 5%;">
                    <col style="width: 1%;">
                </colgroup>
                <tr>
                    <th>No</th>
                    <th>Diagnosis</th>
                    <th>Kode</th>
                    <th>Utama</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tblDiagnosisBody">
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        getDiagnosis();
    })

    function getDiagnosis() {
        const $button = $('#btnRefreshDiagnosis');

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblDiagnosisBody").html(`<tr><td colspan="5" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.DIAGNOSA}</td>
                            <td>${v.KODE_DIAGNOSA ? v.KODE_DIAGNOSA : '-'}${v.NAMA_DIAGNOSA ? ' - '+v.NAMA_DIAGNOSA : ''}</td>
                            <td>${v.UTAMA}</td>
                            <td class="text-center">
                                <button class="btn btn-subtle-danger waves-effect waves-light btn-icon btn-sm" onclick="hapusDiagnosis(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    `;
                }
                $("#tblDiagnosisBody").html(html);
            },
            error: function (xhr) {
                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.log(message);
            },
            complete: function () {
                $button.prop('disabled', false).html('<i class="ri-refresh-line"></i>');
            }
        });
    };

    function tambahDiagnosis() {
        const $button = $('#btnTambahDiagnosis');
        let utama = $("[name='diag_utama']").prop("checked") ? 1 : 0;
        let diagnosa = $("[name='diag_detail']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'utama': utama,
                'diagnosa': diagnosa
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                $("[name='diag_detail']").val('');
                $("[name='diag_utama']").prop('checked', false);

                getDiagnosis();
            },
            error: function (xhr) {
                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusDiagnosis(id){
        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/hapus/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil dihapus.',
                    position: 'topRight'
                });
                getDiagnosis();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                iziToast.error({
                    title: 'Proses Hapus Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },
        });
    };
</script>
