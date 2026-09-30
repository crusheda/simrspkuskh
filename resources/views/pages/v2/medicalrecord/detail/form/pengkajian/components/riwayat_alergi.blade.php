<div class="form-group">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Riwayat Alergi</h6>

        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input
                    class="form-check-input"
                    type="radio"
                    name="ra_status"
                    id="raAda"
                    value="1"
                >
                <label class="form-check-label" for="raAda">
                    Ada
                </label>
            </div>

            <div class="form-check mb-0">
                <input
                    class="form-check-input"
                    type="radio"
                    name="ra_status"
                    id="raTidakAda"
                    value="0"
                    checked
                >
                <label class="form-check-label" for="raTidakAda">
                    Tidak Ada
                </label>
            </div>
        </div>
    </div>

    <div id="containerRiwayatAlergi">

        <div class="row g-2 mb-2 align-items-center">

            <div class="col-md-3">
                <select class="form-select" name="ra_jenis" id="riwy_alergi_jenis"></select>
            </div>

            <div class="col-md-7">
                <textarea
                    class="form-control"
                    name="ra_deskripsi"
                    id="riwy_alergi_deskripsi"
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
            <table class="table table-bordered align-middle mb-1">
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
                        <td colspan="4" class="text-center">
                            Tidak ada riwayat alergi
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {

        // $('#raTidakAda').prop('checked', true);

        // setStatusAlergi(false);

        $('input[name="ra_status"]').on('change', function () {

            if ($(this).val() === '0') {
                konfirmasiTidakAdaAlergi();
            } else {
                $('#raAda').prop('checked', true);
                toggleRiwayatAlergi();
            }

        });

        getRiwayatAlergi();
    });

    function toggleRiwayatAlergi() {

        const ada = $('#raAda').is(':checked');

        // $('#containerRiwayatAlergi').toggle(ada);
        // $('#containerRiwayatAlergi').prop('hidden', !ada);

        if (!ada) {
            $('[name="ra_jenis"]').val('');
            $('[name="ra_deskripsi"]').val('');
            setStatusAlergi(false);
        } else {
            setStatusAlergi(true);
        }
    }

    function setStatusAlergi(ada) {
        $('#riwy_alergi_jenis, #riwy_alergi_deskripsi, #btnTambahAlergi, #btnRefreshAlergi').prop('disabled', !ada);
    }

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

                const adaAlergi =
                    Array.isArray(res.riw_alergi) &&
                    res.riw_alergi.length > 0;

                // PUSH RIWAYAT ALERGI
                if (adaAlergi) {

                    $('#raAda').prop('checked', true);
                    $('#raTidakAda').prop('checked', false);
                    setStatusAlergi(true);

                    $.each(res.riw_alergi, function (i, v) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${v.JENIS_ALERGI}</td>
                                <td>${v.DESKRIPSI}</td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-subtle-danger waves-effect waves-light btn-icon btn-sm"
                                        onclick="hapusRiwayatAlergi(${v.ID})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                } else {

                    $('#raTidakAda').prop('checked', true);
                    $('#raAda').prop('checked', false);
                    setStatusAlergi(false);

                    html = `
                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada riwayat alergi
                            </td>
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
                $button.html('<i class="ri-refresh-line"></i>');
            }
        });
    };

    function tambahRiwayatAlergi() {

        if (!$('#raAda').is(':checked')) {
            iziToast.warning({
                title: 'Perhatian!',
                message: 'Silakan pilih "Ada" terlebih dahulu untuk mengisi riwayat alergi.',
                position: 'topRight'
            });
            return;
        }

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
                $button.html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusRiwayatAlergi(id) {

        Swal.fire({
            title: 'Hapus Riwayat Alergi?',
            text: 'Data riwayat alergi ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger ms-2',
                cancelButton: 'btn btn-subtle-secondary'
            },
            buttonsStyling: false

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/hapus/${id}`,
                type: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function () {

                    $('#tblAlergiBody').css('opacity', '0.5');

                },

                success: function (res) {

                    iziToast.success({
                        title: 'Proses Berhasil!',
                        message:
                            res.message ||
                            'Data riwayat alergi berhasil dihapus.',
                        position: 'topRight'
                    });

                    getRiwayatAlergi();

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

                complete: function () {

                    $('#tblAlergiBody').css('opacity', '');

                }
            });

        });
    }

    function konfirmasiTidakAdaAlergi() {

        const adaData =
            $('#tblAlergiBody tr').find('button[onclick^="hapusRiwayatAlergi"]').length > 0;

        if (!adaData) {
            $('#raTidakAda').prop('checked', true);
            toggleRiwayatAlergi();
            return;
        }

        Swal.fire({
            title: 'Hapus Riwayat Alergi?',
            text: 'Masih terdapat riwayat alergi. Jika memilih "Ya", seluruh riwayat alergi pasien pada kunjungan ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger ms-2',
                cancelButton: 'btn btn-subtle-secondary'
            },
            buttonsStyling: false
        }).then((result) => {

            if (result.isConfirmed) {

                hapusSemuaRiwayatAlergi();

            } else {

                $('#raAda').prop('checked', true);
                toggleRiwayatAlergi();

            }

        });
    }

    function hapusSemuaRiwayatAlergi() {

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_alergi/${kunjungan}/hapus`,
            type: 'DELETE',

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {

                // $('#raAda, #raTidakAda').prop('disabled', true);
                $('#containerRiwayatAlergi').css('opacity', '0.5');

            },

            success: function (res) {

                iziToast.success({
                    title: 'Proses Berhasil!',
                    message:
                        res.message ||
                        'Seluruh riwayat alergi berhasil dihapus.',
                    position: 'topRight'
                });

                $('#raTidakAda').prop('checked', true);

                getRiwayatAlergi();

            },

            error: function (xhr) {

                let message = 'Riwayat alergi gagal dihapus.';

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

                // Kembalikan ke kondisi awal
                $('#raAda').prop('checked', true);
                toggleRiwayatAlergi();

            },

            complete: function () {

                // $('#raAda, #raTidakAda').prop('disabled', false);
                $('#containerRiwayatAlergi').css('opacity', '');

            }
        });
    }
</script>
