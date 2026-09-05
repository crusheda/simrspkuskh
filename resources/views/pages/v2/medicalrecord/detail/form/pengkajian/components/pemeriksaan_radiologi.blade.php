<div class="form-group">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0">Pemeriksaan Penunjang Radiologi</h6>
        <button class="btn btn-subtle-warning btn-icon btn-sm" id="btnRefreshRad" onclick="getRiwayatRad()">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-display mb-0">
            <colgroup>
                <col style="width: 1%;">
                <col>
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 20%;">
            </colgroup>
            <thead>
                <tr class="table-light">
                    <th>No</th>
                    <th>Nama Tindakan</th>
                    <th>Klinis</th>
                    <th>Kesan</th>
                    <th>Usul</th>
                    <th>Hasil</th>
                    <th>BTK</th>
                    <th>Dokter</th>
                </tr>
            </thead>
            <tbody id="tblRadBody">
                <tr>
                    <td colspan="8" class="text-center">Tidak ada hasil radiologi</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        getRiwayatRad();
    })

    function getRiwayatRad() {
        const $button = $('#btnRefreshRad');

        $.ajax({
            url: `/api/v2/emr/pengkajian/radiologi/${kunjungan}`,
            type: 'GET',

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblRadBody").html(`
                    <tr>
                        <td colspan="8" class="text-center">
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
                                <td>${v.KLINIS ?? ''}</td>
                                <td>${v.KESAN ?? ''}</td>
                                <td>${v.USUL ?? ''}</td>
                                <td>${v.HASIL ?? ''}</td>
                                <td>${v.BTK ?? ''}</td>
                                <td>${v.DOKTER ?? ''}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="8" class="text-center">
                                Tidak ada hasil radiologi
                            </td>
                        </tr>
                    `;
                }
                $("#tblRadBody").html(html);
            },

            error: function (xhr) {
                $("#tblRadBody").html(`
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
