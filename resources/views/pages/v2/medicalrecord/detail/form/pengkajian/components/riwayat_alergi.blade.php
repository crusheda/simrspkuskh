<div class="form-group">
    <h6>Riwayat Alergi</h6>
    <div class="row g-2 mb-2 align-items-center">
        <div class="col-md-3">
            <select class="form-select" name="ra_jenis"></select>
        </div>
        <div class="col-md-7">
            <textarea
                class="form-control"
                name="ra_deskripsi"
                placeholder="Masukkan Alergi"
                rows="1"
            ></textarea>
        </div>
        <div class="col-md-2">
            <div class="btn-group w-100">
                <button
                    type="button"
                    class="btn btn-info btn-sm btn-save-sub-pengkajian"
                    id="btnTambahAlergi"
                    onclick="tambahRiwayatAlergi()"
                >
                    <i class="ri-add-box-line"></i>
                </button>
                <button
                    type="button"
                    class="btn btn-subtle-warning btn-sm"
                    id="btnRefreshAlergi"
                    onclick="getRiwayatAlergi()"
                >
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table align-middle mb-1">
            <colgroup>
                <col style="width: 1%;">
                <col style="width: 20%;">
                <col>
                <col style="width: 1%;">
            </colgroup>
            <thead>
                <tr class="table-info">
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tblAlergiBody">
                <tr>
                    <td colspan="4" class="text-center">Tidak ada riwayat alergi</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        getRiwayatAlergi();
    })

    function getRiwayatAlergi() {
        const $button = $('#btnRefreshAlergi');

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblAlergiBody").html(`<tr><td colspan="4" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                let opt = '';

                // PUSH RIWAYAT ALERGI
                if (res.riw_alergi.length > 0) {
                    $.each(res.riw_alergi, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.JENIS_ALERGI}</td>
                            <td>${v.DESKRIPSI}</td>
                            <td class="text-center">
                                <button class="btn btn-subtle-danger waves-effect waves-light btn-icon btn-sm" onclick="hapusRiwayatAlergi(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada riwayat alergi</td>
                    </tr>
                    `;
                }
                $("#tblAlergiBody").html(html);

                // PUSH REFERENSI RIWAYAT ALERGI
                if (res.ref_riw_alergi.length > 0) {
                    opt += '<option value="" hidden>Pilih Jenis Alergi</option>';
                    res.ref_riw_alergi.forEach(item => {
                        opt += `<option value="${item.ID}">${item.DESKRIPSI}</option>`;
                    });
                }
                $("[name='ra_jenis']").empty().html(opt);
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

    function tambahRiwayatAlergi() {
        const $button = $('#btnTambahAlergi');
        let jenis = $("[name='ra_jenis']").val();
        let deskripsi = $("[name='ra_deskripsi']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'jenis': jenis,
                'deskripsi': deskripsi
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
                $("[name='ra_jenis']").val('');
                $("[name='ra_deskripsi']").val('');
                getRiwayatAlergi();
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusRiwayatAlergi(id){
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/hapus/${id}`,
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
                getRiwayatAlergi();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('&nbsp;');
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
