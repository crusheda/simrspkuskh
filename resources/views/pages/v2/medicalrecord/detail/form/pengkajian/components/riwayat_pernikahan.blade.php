<div class="col-md-12 ">
    <div class="card card-body border border-dashed border-primary">
        <div class="form-group">
            <h6>Riwayat Pernikahan</h6>
            <div class="row g-2 mb-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label">Lama Pernikahan (Tahun)</label>
                    <input type="number" class="form-control form-control-sm" name="nikah_tahun" id="nikah_tahun" min="1" max="100" placeholder="Tahun">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ket. Tempat</label>
                    <input type="text" class="form-control form-control-sm" name="nikah_ket" id="nikah_ket" placeholder="Keterangan">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" id="btnTambahNikah" onclick="tambahRiwayatNikah()">
                            <i class="ri-add-box-line"></i>
                            Tambah
                        </button>
                        <button type="button" class="btn btn-subtle-warning btn-sm" id="btnRefreshNikah" onclick="getRiwayatNikah()">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table align-middle mb-1">
                    <thead>
                        <tr class="table-success">
                            <th>No</th>
                            <th>Lama Menikah (Tahun)</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tblNikahBody">
                        <tr>
                            <td colspan="11" class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getRiwayatNikah();
    })

    function getRiwayatNikah() {
        const $button = $('#btnRefreshNikah');

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_nikah/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblNikahBody").html(`<tr><td colspan="4" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                let opt = '';

                // PUSH RIWAYAT NIKAH
                if (res.riw_nikah.length > 0) {
                    $.each(res.riw_nikah, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.LAMA_NIKAH}</td>
                            <td>${v.KETERANGAN}</td>
                            <td class="text-center">
                                <button class="btn btn-subtle-danger waves-effect waves-light btn-icon btn-sm" onclick="hapusRiwayatNikah(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada riwayat nikah</td>
                    </tr>
                    `;
                }
                $("#tblNikahBody").html(html);
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

    function tambahRiwayatNikah() {
        const $button = $('#btnTambahNikah');
        let tahun = $("[name='nikah_tahun']").val();
        let ket = $("[name='nikah_ket']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_nikah/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'tahun': tahun,
                'ket': ket
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
                getRiwayatNikah();
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

    function hapusRiwayatNikah(id){
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_nikah/${kunjungan}/hapus/${id}`,
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
                getRiwayatNikah();
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
