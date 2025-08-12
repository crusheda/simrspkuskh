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
                                    <textarea id="input-jawaban" class="form-control rounded" rows="3" readonly></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Anjuran:</label>
                                    <textarea id="input-anjuran" class="form-control rounded" rows="3" readonly></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Tanggal & Waktu:</label>
                                    <input id="input-tgljawab" type="text" class="form-control rounded" readonly>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold">Dokter yg Menjawab:</label>
                                    <input id="input-dokterjawab" type="text" class="form-control rounded" readonly>
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
                                <th>Asal Konsul</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Konsul Yg. Diminta</th>
                                <th>Ruang Tujuan</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-konsulmasuk">
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
        konsulMasuk("{{ $list['KUNJUNGAN'] }}");

        $(document).on('click', '.baris-konsul', function () {
            const nomor = $(this).data('nomor');
            if (nomor) {
                $('.baris-konsul').removeClass('table-active');
                $(this).addClass('table-active');

                tampilJawabanKonsul(nomor);
                $('button[data-bs-target="#riwayatkonsul"]').tab('show');
            }
        });
    });

    function tampilKonsul(nomor) {
        $('#tbody-konsul').html('<tr><td colspan="8" class="text-center">Memuat data...</td></tr>');

        $.ajax({
            url: `/api/emr/konsul/${nomor}`,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (Array.isArray(data) && data.length > 0) {
                    let html = '';
                    data.forEach(function(item) {
                        html += `
                            <tr class="baris-konsul" data-nomor="${item.NOMOR}">
                                <td>
                                    <span><strong>${item.NOMOR}</strong></span><br>
                                    <span><small>Dokter Asal : ${item.NAMADOKTER}</small></span>
                                </td>
                                <td>${formatTanggal(item.TANGGAL)}</td>
                                <td>${item.ALASAN}</td>
                                <td>${item.PERMINTAAN_TINDAKAN}</td>
                                <td>${item.NAMARUANGAN}</td>
                            </tr>
                        `;
                    });
                    $('#tbody-konsul').html(html);
                } else {
                    $('#tbody-konsul').html('<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>');
                }
            },
            error: function () {
                $('#tbody-konsul').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function konsulMasuk(nomor) {
        $('#tbody-konsulmasuk').html('<tr><td colspan="8" class="text-center">Memuat data...</td></tr>');

        $.ajax({
            url: `/api/emr/konsul/masuk/${nomor}`,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (Array.isArray(data) && data.length > 0) {
                    let html = '';
                    data.forEach(function(item) {
                        html += `
                            <tr>
                                <td>
                                    <h4 class="mb-1 text-primary"><b data-bs-toggle="tooltip" data-bs-placement="bottom">${item.NAMARUANGAN}</b></h4>
                                    <span><strong>${item.NOMOR}</strong></span><br>
                                    <span><small>Dokter Asal : ${item.NAMADOKTER}</small></span>
                                </td>
                                <td>${formatTanggal(item.TANGGAL)}</td>
                                <td>${item.ALASAN}</td>
                                <td>${item.PERMINTAAN_TINDAKAN}</td>
                                <td>${item.TUJUAN}</td>
                            </tr>
                        `;
                    });
                    $('#tbody-konsulmasuk').html(html);
                } else {
                    $('#tbody-konsulmasuk').html('<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>');
                }
            },
            error: function () {
                $('#tbody-konsulmasuk').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function tampilJawabanKonsul(nomor) {
        $.ajax({
            url: `/api/emr/konsul/jawaban/${nomor}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#input-jawaban').val(data.JAWABAN || '-');
                    $('#input-anjuran').val(data.ANJURAN || '-');
                    $('#input-tgljawab').val(formatTanggal(data.TANGGAL_JAWABAN) || '-');
                    $('#input-dokterjawab').val(`${data.KODE_DOKTER || ''} - ${data.JAWABDOKTER || ''}`);
                } else {
                    kosongkanJawabanKonsul();
                }
            },
            error: function() {
                kosongkanJawabanKonsul();
            }
        });
    }

    function kosongkanJawabanKonsul() {
        $('#input-jawaban').val('-');
        $('#input-anjuran').val('-');
        $('#input-tgljawab').val('-');
        $('#input-dokterjawab').val('-');
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
