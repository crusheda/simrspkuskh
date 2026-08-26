<div class="col-md-12 mb-1">
    <div class="card card-body border border-dashed border-primary">
        <h6>Riwayat Obstetri</h6>
        <div class="row g-2 mb-2">
            {{-- Tahun --}}
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <input type="number" class="form-control form-control-sm" name="obstetri_tahun" id="obstetri_tahun" placeholder="Tahun">
            </div>
            {{-- Usia Kehamilan --}}
            <div class="col-md-2">
                <label class="form-label">Usia Kehamilan</label>
                <select class="form-select form-select-sm" name="obstetri_usia_kehamilan" id="obstetri_usia_kehamilan">
                    <option value="">Pilih</option>
                    @foreach ($list['usia_kehamilan'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Jenis Persalinan --}}
            <div class="col-md-2">
                <label class="form-label">Persalinan</label>
                <select class="form-select form-select-sm" name="obstetri_jenis_persalinan" id="obstetri_jenis_persalinan">
                    <option value="">Pilih</option>
                    @foreach ($list['jenis_persalinan'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Penyulit --}}
            <div class="col-md-2">
                <label class="form-label">Penyulit</label>
                <select class="form-select form-select-sm" name="obstetri_penyulit" id="obstetri_penyulit">
                    <option value="">Pilih</option>
                    @foreach ($list['penyulit'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Jenis Kelamin --}}
            <div class="col-md-2">
                <label class="form-label">JK</label>
                <select class="form-select form-select-sm" name="obstetri_jenis_kelamin" id="obstetri_jenis_kelamin">
                    <option value="">Pilih</option>
                    @foreach ($list['jenis_kelamin'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Berat Badan --}}
            <div class="col-md-2">
                <label class="form-label">BB (gram)</label>
                <input type="number" class="form-control form-control-sm" name="obstetri_berat_badan" id="obstetri_berat_badan" placeholder="BB">
            </div>
        </div>
        <div class="row g-2 mb-2">
            {{-- Penolong --}}
            <div class="col-md-3">
                <label class="form-label">Penolong</label>
                <select class="form-select form-select-sm" name="obstetri_penolong" id="obstetri_penolong">
                    <option value="">Pilih</option>
                    @foreach ($list['penolong'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Keterangan Penolong --}}
            <div class="col-md-3">
                <label class="form-label">Ket. Penolong</label>
                <input type="text" class="form-control form-control-sm" name="obstetri_keterangan_penolong" id="obstetri_keterangan_penolong" placeholder="Keterangan">
            </div>
            {{-- Tempat --}}
            <div class="col-md-3">
                <label class="form-label">Tempat</label>
                <select class="form-select form-select-sm" name="obstetri_tempat" id="obstetri_tempat">
                    <option value="">Pilih</option>
                    @foreach ($list['tempat'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Keterangan Tempat --}}
            <div class="col-md-3">
                <label class="form-label">Ket. Tempat</label>
                <input type="text" class="form-control form-control-sm" name="obstetri_keterangan_tempat" id="obstetri_keterangan_tempat" placeholder="Keterangan">
            </div>
        </div>
        <div class="row g-2 mb-2">
            {{-- Keadaan Saat Ini --}}
            <div class="col-md-4">
                <label class="form-label">Keadaan Saat Ini</label>
                <select class="form-select form-select-sm" name="obstetri_keadaan_saat_ini" id="obstetri_keadaan_saat_ini">
                    <option value="">Pilih</option>
                    @foreach ($list['keadaan_sat_ini'] as $item)
                        <option value="{{ $item->ID }}">
                            {{ $item->DESKRIPSI }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Tombol --}}
            <div class="col-md-8 d-flex align-items-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm" id="btnTambahObstetri" onclick="tambahRiwayatObstetri()">
                        <i class="ri-add-box-line"></i>
                        Tambah
                    </button>
                    <button type="button" class="btn btn-subtle-warning btn-sm" id="btnRefreshObstetri" onclick="getRiwayatObstetri()">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- Tabel --}}
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-sm align-middle mb-1">
                <thead>
                    <tr class="table-success">
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Usia Kehamilan</th>
                        <th>Persalinan</th>
                        <th>Penyulit</th>
                        <th>Penolong</th>
                        <th>Tempat</th>
                        <th>JK</th>
                        <th>BB</th>
                        <th>Keadaan Saat Ini</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tblObstetriBody">
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

<script>
    function getRiwayatObstetri() {
        const $button = $('#btnRefreshObstetri');
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button
                    .prop('disabled', true)
                    .html('<i class="ri-refresh-line ri-spin"></i>');
                $('#tblObstetriBody').html(`
                    <tr>
                        <td colspan="11" class="text-center">
                            <i class="ri-refresh-line ri-spin me-1"></i>
                            Memproses data...
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
                                <td>${v.TAHUN ?? '-'}</td>
                                <td>${v.USIA_KEHAMILAN_DESC ?? '-'}</td>
                                <td>${v.JENIS_PERSALINAN_DESC ?? '-'}</td>
                                <td>${v.PENYULIT_DESC ?? '-'}</td>
                                <td>
                                    ${v.PENOLONG_DESC ?? '-'}
                                    ${v.KETERANGAN_PENOLONG
                                        ? '<br><small>' + v.KETERANGAN_PENOLONG + '</small>'
                                        : ''}
                                </td>
                                <td>
                                    ${v.TEMPAT_DESC ?? '-'}
                                    ${v.KETERANGAN_TEMPAT
                                        ? '<br><small>' + v.KETERANGAN_TEMPAT + '</small>'
                                        : ''}
                                </td>
                                <td>${v.JENIS_KELAMIN_DESC ?? '-'}</td>
                                <td>${v.BERAT_BADAN ?? '-'} gr</td>
                                <td>${v.KEADAAN_SAAT_INI_DESC ?? '-'}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRiwayatObstetri(${v.ID})"> <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="11" class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                    `;
                }
                $('#tblObstetriBody').html(html);
            },
            error: function (xhr) {
                let message = 'Data gagal dimuat.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.log(message);
            },
            complete: function () {
                $button
                    .prop('disabled', false)
                    .html('<i class="ri-refresh-line"></i>');
            }
        });
    }

    function tambahRiwayatObstetri() {
        const $button = $('#btnTambahObstetri');
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        const data = {
            tahun: $('#obstetri_tahun').val(),
            usia_kehamilan:
                $('#obstetri_usia_kehamilan').val(),
            jenis_persalinan:
                $('#obstetri_jenis_persalinan').val(),
            penyulit:
                $('#obstetri_penyulit').val(),
            penolong:
                $('#obstetri_penolong').val(),
            keterangan_penolong:
                $('#obstetri_keterangan_penolong').val(),
            tempat:
                $('#obstetri_tempat').val(),
            keterangan_tempat:
                $('#obstetri_keterangan_tempat').val(),
            jenis_kelamin:
                $('#obstetri_jenis_kelamin').val(),
            berat_badan:
                $('#obstetri_berat_badan').val(),
            keadaan_saat_ini:
                $('#obstetri_keadaan_saat_ini').val()
        };
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $button
                    .prop('disabled', true)
                    .html('<i class="ri-refresh-line ri-spin"></i>');
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message:
                        res.message ||
                        'Data riwayat obstetri berhasil disimpan.',
                    position: 'topRight'
                });
                // Reset input
                $('#obstetri_tahun').val('');
                $('#obstetri_usia_kehamilan').val('');
                $('#obstetri_jenis_persalinan').val('');
                $('#obstetri_penyulit').val('');
                $('#obstetri_penolong').val('');
                $('#obstetri_keterangan_penolong').val('');
                $('#obstetri_tempat').val('');
                $('#obstetri_keterangan_tempat').val('');
                $('#obstetri_jenis_kelamin').val('');
                $('#obstetri_berat_badan').val('');
                $('#obstetri_keadaan_saat_ini').val('');
                // Refresh tabel
                getRiwayatObstetri();
            },

            error: function (xhr) {
                let message = 'Data gagal disimpan.';
                if (xhr.status === 422 &&
                    xhr.responseJSON?.errors) {
                    message = Object.values(
                        xhr.responseJSON.errors
                    )
                    .flat()
                    .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.log(message);
            },
            complete: function () {
                $button
                    .prop('disabled', false)
                    .html(`
                        <i class="ri-add-box-line"></i>
                        Tambah
                    `);
            }
        });
    }
    function hapusRiwayatObstetri(id) {
        const kunjungan = $('#rjo_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_obstetri/${kunjungan}/hapus/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message:
                        res.message ||
                        'Data riwayat obstetri berhasil dihapus.',
                    position: 'topRight'
                });
                getRiwayatObstetri();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                console.log(message);
            }
        });
    }
</script>
