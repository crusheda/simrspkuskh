<div class="card table-card border shadow-none">
    <div class="card-header pb-0 pt-2">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#formlayanankfr" type="button"
                    role="tab" aria-controls="formlayanankfr" aria-selected="true">Form Layanan KFR</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#formjadwalpelayanan" type="button"
                    role="tab" aria-controls="formjadwalpelayanan" aria-selected="true">Jadwal Pelayanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#riwayatkfr" type="button" role="tab"
                    aria-controls="riwayatkfr" aria-selected="false">Riwayat</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="formlayanankfr" role="tabpanel" aria-labelledby="home-tab">
                <div id="allformkfr">
                    <div class="row" id="formBaruKfr">
                        <div class="col-md-12 d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Daftar Konsul</h5>
                        </div>
                        <div class="table-responsive border-top">
                            <table class="table mb-0 table-hover table-display">
                                <thead>
                                    <tr>
                                        <th>Konsul</th>
                                        <th>Alasan</th>
                                        <th>Ruang Tujuan</th>
                                        <th>Oleh</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-konsul">
                                    <tr>
                                        <td colspan="8" class="text-center">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Contoh panggil saat halaman siap
    $(document).ready(function () {
        tampilKonsul("{{ $list['KUNJUNGAN'] }}");
    });
    function tampilKonsul(nomor) {
        $('#tbody-konsul').html('<tr><td colspan="8" class="text-center">Memuat data...</td></tr>');

        $.ajax({
            url: `/api/emr/konsul/${nomor}`,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data) {
                    $('#tbody-konsul').html(`
                        <tr>
                            <td>
                                <span><strong>No.Konsul : ${data.NOMOR}</strong></span><br>
                                <span><strong>No.Kunjungan : ${data.KUNJUNGAN}</strong></span><br>
                                <span><small>Tanggal : ${formatTanggal(data.TANGGAL)}</small></span>
                                <span><small>Dokter Asal : ${data.DOKTER_ASAL}</small></span>
                            </td>
                            <td>
                                <span><strong>Alasan :</strong></span><br>
                                <span>${data.ALASAN}</span><br>
                                <span><strong>Permintaan Tindakan :</strong></span><br>
                                <span>${data.PERMINTAAN_TINDAKAN}</span>
                            </td>
                            <td>${data.TUJUAN}</td>
                            <td>${data.OLEH}</td>
                        </tr>
                    `);
                } else {
                    $('#tbody-konsul').html('<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>');
                }
            },
            error: function () {
                $('#tbody-konsul').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function formatTanggal(dateTime) {
        const date = new Date(dateTime);
        return date.toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

</script>
