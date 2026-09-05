<div class="form-group">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0">Pemeriksaan Penunjang Laboratorium</h6>
        <button class="btn btn-subtle-warning btn-icon btn-sm" id="btnRefreshLab" onclick="getRiwayatLab()">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-display mb-0">
            <colgroup>
                <col style="width: 1%;">
                <col style="width: 20%;">
                <col>
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 10%;">
                <col style="width: 20%;">
            </colgroup>
            <thead>
                <tr class="table-light">
                    <th>No</th>
                    <th>Jenis Pemeriksaan</th>
                    <th>Parameter</th>
                    <th>Hasil</th>
                    <th>Satuan</th>
                    <th>Nilai Normal</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="tblLabBody">
                <tr>
                    <td colspan="7" class="text-center">Tidak ada hasil laboratorium</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        getRiwayatLab();
    })

    function getRiwayatLab() {
        const $button = $('#btnRefreshLab');

        $.ajax({
            url: `/api/v2/emr/pengkajian/laborat/${kunjungan}`,
            type: 'GET',

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblLabBody").html(`
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="ri-refresh-line ri-spin me-1"></i>
                            Memuat data...
                        </td>
                    </tr>
                `);
            },

            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${v.NAMATINDAKAN ?? ''}</td>
                                <td>${v.PARAMETER ?? ''}</td>
                                <td>${v.HASIL ?? ''}</td>
                                <td>${v.SATUAN ?? ''}</td>
                                <td>${v.NILAI_RUJUKAN ?? ''}</td>
                                <td>${v.KETERANGAN ?? ''}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center">
                                Tidak ada hasil laboratorium
                            </td>
                        </tr>
                    `;
                }
                $("#tblLabBody").html(html);
            },

            error: function (xhr) {
                $("#tblLabBody").html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Gagal memuat data.
                        </td>
                    </tr>
                `);
                console.error(xhr);
            },

            complete: function () {
                $button.prop('disabled', false)
                    .html('<i class="ri-refresh-line"></i>');
            }

        });
    };
</script>
