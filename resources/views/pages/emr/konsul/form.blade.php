<div class="card table-card border shadow-none">
    <div class="card-header pb-0 pt-2">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#jawabkonsul" type="button"
                    role="tab" aria-controls="jawabkonsul" aria-selected="false">Konsul Masuk</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#riwayatkonsul" type="button"
                    role="tab" aria-controls="riwayatkonsul" aria-selected="true">Daftar Konsul</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content" id="myTabContent">

            <!-- Tab Riwayat Konsul -->
            <div class="tab-pane fade" id="riwayatkonsul" role="tabpanel" aria-labelledby="riwayat-tab">
                <div class="row">
                    <!-- Kolom Kiri: Riwayat Konsul -->
                    <div class="col-md-7">
                        <div class="card border-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <strong>Riwayat Konsul</strong>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor</th>
                                                <th>Tanggal</th>
                                                <th>Alasan</th>
                                                <th>Konsul Yg. Diminta</th>
                                                <th>Tujuan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-konsul">
                                            <tr>
                                                <td colspan="" class="text-center">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Jawaban Konsul -->
                    <div class="col-md-5">
                        <div class="card shadow-sm border-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <strong>Jawaban Konsul</strong>
                            </div>
                            <div class="card-body bg-light px-4 py-4">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Jawaban:</label>
                                    <textarea class="form-control rounded" rows="3" readonly>jawab</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Anjuran:</label>
                                    <textarea class="form-control rounded" rows="3" readonly>gini</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Tanggal & Waktu:</label>
                                    <input type="text" class="form-control rounded" value="07/08/2025 19:58:23" readonly>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold">Dokter yg Menjawab:</label>
                                    <input type="text" class="form-control rounded" value="2008338 - dr. Bahtiar Mahdi Cahya Kusuma, Sp. N" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>

            <!-- Tab Daftar Konsul (default) -->
            <div class="tab-pane fade active show" id="jawabkonsul" role="tabpanel" aria-labelledby="home-tab">
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
                if (data && Object.keys(data).length > 0) {
                    $('#tbody-konsul').html(`
                        <tr>
                            <td>
                                <span><strong>${data.NOMOR}</strong></span><br>
                                <span><small>Dokter Asal : ${data.NAMADOKTER}</small></span>
                            </td>
                            <td>${formatTanggal(data.TANGGAL)}</td>
                            <td>${data.ALASAN}</td>
                            <td>${data.PERMINTAAN_TINDAKAN}</td>
                            <td>${data.NAMARUANGAN}</td>
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
        if (!dateTime) return '-';
        const date = new Date(dateTime);
        return isNaN(date.getTime())
            ? '-'
            : date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
    }
</script>
