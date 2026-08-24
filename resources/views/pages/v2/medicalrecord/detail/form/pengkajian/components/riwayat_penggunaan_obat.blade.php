<div class="form-group">
    <h6>Riwayat Penggunaan Obat</h6>
    <div class="row g-2 mb-2 align-items-center">
        <div class="col">
            <input
                type="text"
                class="form-control"
                name="rpo_nama_obat"
                id="rpo_nama_obat"
                placeholder="Masukkan Nama Obat"
            >
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <button
                    type="button"
                    class="btn btn-success btn-save-sub-pengkajian"
                    id="btnTambahObat"
                    onclick="tambahPenggunaanObat()"
                >
                    <i class="ri-add-box-line"></i>
                </button>
                <button
                    type="button"
                    class="btn btn-subtle-warning"
                    id="btnRefreshObat"
                    onclick="getPenggunaanObat()"
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
                <col>
                <col style="width: 1%;">
            </colgroup>
            <thead>
                <tr class="table-success">
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tblObatBody">
                <tr>
                    <td colspan="7" class="text-center">Tidak ada riwayat penggunaan obat</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        const $inputObat = $('[name="rpo_nama_obat"]');

        if (!$inputObat.length ) {
            return;
        }

        new autoComplete({
            selector: '[name="rpo_nama_obat"]',
            placeHolder: 'Masukkan Nama Obat',
            threshold: 2,
            debounce: 300,

            data: {
                src: async function (query) {
                    try {
                        return await $.ajax({
                            url: "/api/v2/emr/pengkajian/riwayat_pemberian_obat/obat",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                q: query
                            }
                        });
                    } catch (error) {
                        console.error('Gagal mengambil data obat:', error);
                        return [];
                    }
                },

                keys: ['nama'],
                cache: false
            },

            resultsList: {
                maxResults: 15,
                noResults: true
            },

            resultItem: {
                highlight: true,

                element: function (item, data) {
                    const obat = data.value;

                    // .text() lebih aman daripada memasukkan data database ke HTML langsung
                    $(item)
                        .empty()
                        .append(
                            $('<strong>').text(obat.nama),
                            $('<small>')
                                .addClass('d-block text-muted')
                                .text(`Kategori: ${obat.kategori ?? '-'}  |  Satuan: ${obat.ket_satuan ?? '-'}${obat.satuan ? ' ('+obat.satuan+')' : '-'}`)
                        );
                }
            },

            events: {
                input: {
                    selection: function (event) {
                        const obat = event.detail.selection.value;

                        $inputObat.val(obat.nama);
                        // $inputBarangId.val(obat.id);
                    }
                }
            }
        });

        getPenggunaanObat();
    })

    function getPenggunaanObat() {
        const $button = $('#btnRefreshObat');
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblObatBody").html(`<tr><td colspan="7" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.OBAT}</td>

                                {{-- <td>${v.DOSIS}</td>
                                <td>${v.FREKUENSI_NAMA} ${v.FREKUENSI_KETERANGAN ? `(${v.FREKUENSI_KETERANGAN})` : ''}</td>
                                <td>${v.RUTE_NAMA}</td>
                                <td>${v.LAMA_PENGGUNAAN}</td> --}}

                            <td class="text-center">
                                <button class="btn btn-subtle-danger waves-effect waves-light btn-icon btn-sm" onclick="hapusPenggunaanObat(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada riwayat penggunaan obat</td>
                    </tr>
                    `;
                }
                $("#tblObatBody").html(html);
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
                alert(message);
            },
            complete: function () {
                $button.prop('disabled', false).html('<i class="ri-refresh-line"></i>');
            }
        });
    }

    function tambahPenggunaanObat() {
        const $button = $('#btnTambahObat');
        let nama_obat = $("input[name='rpo_nama_obat']").val();
        let dosis = $("input[name='rpo_dosis']").val();
        let frekuensi = $("select[name='rpo_frekuensi']").val();
        let rute = $("select[name='rpo_rute']").val();
        let lama = $("input[name='rpo_lama']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'nama_obat': nama_obat,
                'dosis': dosis,
                'frekuensi': frekuensi,
                'rute': rute,
                'lama': lama
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
                getPenggunaanObat();
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
    }

    function hapusPenggunaanObat(id){
        $.ajax({
            url: `/api/v2/emr/pengkajian/riwayat_pemberian_obat/${kunjungan}/hapus/${id}`,
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
                getPenggunaanObat();
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
    }
</script>
