<div class="form-group" id="form_barthel_index">

    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="form-check mb-0 flex-shrink-0">
            <input
                class="form-check-input check-primary"
                type="checkbox"
                id="barthel_index">

            <label class="form-check-label ms-1">
                <small class="mb-2 fw-bold">
                    Barthel Index
                </small>
            </label>
        </div>
    </div>

    <div class="row" id="tampil_barthel_index" hidden>

        <div class="col-md-12">

            @foreach ($list['jenisBarthel'] as $jenis => $pertanyaan)

                <div class="form-group mb-3">

                    <label class="form-label fw-bold">
                        {{ $loop->iteration }}.
                        {{ $pertanyaan }}
                    </label>

                    <select
                        class="form-select form-select-sm barthel-item"
                        name="barthel[{{ $jenis }}]"
                        data-jenis="{{ $jenis }}"
                    >
                        <option value="">
                            -- Pilih kondisi pasien --
                        </option>

                        @foreach ($list['referensiBarthel'][$jenis] ?? [] as $ref)
                            <option
                                value="{{ $ref->ID }}"
                                data-score="{{ $ref->ID - 1 }}"
                            >
                                {{ $ref->DESKRIPSI }}
                            </option>
                        @endforeach

                    </select>

                </div>

            @endforeach

        </div>

        {{-- HASIL --}}
        <div class="col-md-6">

            <input
                type="number"
                name="skor_barthel"
                id="skor_barthel_input"
                value="0"
                hidden
            >

            <div id="hasil_barthel" hidden>

                <div class="alert alert-success mb-3">

                    <div class="d-flex align-items-center">

                        <div class="me-4">
                            <h1
                                class="display-1 fw-bold mb-0"
                                id="nilai_barthel"
                            >
                                0
                            </h1>
                        </div>

                        <div>

                            <h5 class="mb-1 fw-bold">
                                Skor Barthel Index
                            </h5>

                            <div
                                class="fw-bold"
                                id="kategori_barthel"
                            >
                                -
                            </div>

                            <small
                                class="text-muted"
                                id="keterangan_barthel"
                            ></small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-12">

            <button
                type="button"
                class="btn btn-primary btn-save-sub-pengkajian"
                id="btn_simpan_barthel"
                onclick="simpanBarthelIndex(this)"
                disabled
            >
                <i class="ri-save-line me-1"></i>
                Simpan Barthel Index
            </button>

        </div>

        <hr class="mt-3">

    </div>

</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | BARTHEL INDEX
    |--------------------------------------------------------------------------
    */

    var $sectionBarthel = $('#form_barthel_index');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT READY
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {


        /*
        |--------------------------------------------------------------------------
        | CHECKBOX UTAMA BARTHEL
        |--------------------------------------------------------------------------
        */

        $sectionBarthel.on(
            'change',
            '#barthel_index',
            function () {

                if ($(this).is(':checked')) {

                    // Tampilkan form Barthel
                    $sectionBarthel
                        .find('#tampil_barthel_index')
                        .prop('hidden', false);

                } else {

                    // Sembunyikan form
                    $sectionBarthel
                        .find('#tampil_barthel_index')
                        .prop('hidden', true);

                    // Reset tampilan form
                    resetBarthelIndex();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | DROPDOWN BARTHEL BERUBAH
        |--------------------------------------------------------------------------
        */

        $sectionBarthel.on(
            'change',
            '.barthel-item',
            function () {

                // Hitung skor
                hitungBarthelIndex();

                // Validasi apakah semua sudah dipilih
                validasiBarthelIndex();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD DATA BARTHEL SAAT HALAMAN DIBUKA
        |--------------------------------------------------------------------------
        */

        getBarthelIndex();

    });



    /*
    |--------------------------------------------------------------------------
    | HITUNG SKOR BARTHEL INDEX
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | ID 1 = skor 0
    | ID 2 = skor 1
    | ID 3 = skor 2
    | ID 4 = skor 3
    |
    | Jadi:
    |
    | skor = ID - 1
    |
    */

    function hitungBarthelIndex()
    {

        let total = 0;

        let terisi = 0;

        let totalItem =
            $sectionBarthel.find('.barthel-item').length;


        /*
        |--------------------------------------------------------------------------
        | LOOP SEMUA PERTANYAAN
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('.barthel-item')
            .each(function () {

                const value = $(this).val();


                /*
                |--------------------------------------------------------------------------
                | JIKA SUDAH DIPILIH
                |--------------------------------------------------------------------------
                */

                if (value !== '') {

                    terisi++;


                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL SCORE DARI OPTION
                    |--------------------------------------------------------------------------
                    |
                    | Contoh:
                    |
                    | <option value="3" data-score="2">
                    |
                    | maka score = 2
                    |
                    */

                    const score = Number(
                        $(this)
                            .find('option:selected')
                            .attr('data-score')
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | TAMBAHKAN KE TOTAL
                    |--------------------------------------------------------------------------
                    */

                    if (!isNaN(score)) {

                        total += score;

                    }

                }

            });


        /*
        |--------------------------------------------------------------------------
        | SIMPAN TOTAL KE HIDDEN INPUT
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#skor_barthel_input')
            .val(total);


        /*
        |--------------------------------------------------------------------------
        | BELUM ADA PILIHAN
        |--------------------------------------------------------------------------
        */

        if (terisi === 0) {

            $sectionBarthel
                .find('#hasil_barthel')
                .prop('hidden', true);

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HASIL
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#hasil_barthel')
            .prop('hidden', false);


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN ANGKA
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#nilai_barthel')
            .text(total);


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN KATEGORI
        |--------------------------------------------------------------------------
        |
        | Rentang berdasarkan skor 0 - 20.
        |
        */

        let kategori = '';

        let keterangan = '';


        if (total <= 4) {

            kategori =
                'Ketergantungan total';

            keterangan =
                'Pasien sangat bergantung pada bantuan orang lain.';

        }
        else if (total <= 8) {

            kategori =
                'Ketergantungan berat';

            keterangan =
                'Pasien membutuhkan bantuan dalam sebagian besar aktivitas.';

        }
        else if (total <= 12) {

            kategori =
                'Ketergantungan sedang';

            keterangan =
                'Pasien membutuhkan bantuan pada beberapa aktivitas.';

        }
        else if (total <= 16) {

            kategori =
                'Ketergantungan ringan';

            keterangan =
                'Pasien relatif mandiri namun masih membutuhkan bantuan tertentu.';

        }
        else {

            kategori =
                'Mandiri';

            keterangan =
                'Pasien relatif mandiri dalam aktivitas sehari-hari.';

        }


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN KATEGORI
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#kategori_barthel')
            .text(kategori);


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN KETERANGAN
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#keterangan_barthel')
            .text(keterangan);

    }



    /*
    |--------------------------------------------------------------------------
    | VALIDASI BARTHEL
    |--------------------------------------------------------------------------
    |
    | Semua 10 pertanyaan harus dipilih.
    |
    */

    function validasiBarthelIndex()
    {

        let lengkap = true;

        let jumlahTerisi = 0;

        let jumlahPertanyaan =
            $sectionBarthel.find('.barthel-item').length;


        /*
        |--------------------------------------------------------------------------
        | CEK SATU PER SATU
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('.barthel-item')
            .each(function () {

                if ($(this).val() === '') {

                    lengkap = false;

                } else {

                    jumlahTerisi++;

                }

            });


        /*
        |--------------------------------------------------------------------------
        | BUTTON SIMPAN
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#btn_simpan_barthel')
            .prop(
                'disabled',
                !lengkap
            );


        return lengkap;

    }



    /*
    |--------------------------------------------------------------------------
    | GET DATA BARTHEL INDEX
    |--------------------------------------------------------------------------
    */

    function getBarthelIndex()
    {
        $.ajax({

            url: `/api/v2/emr/pengkajian/rajal/barthel/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                // console.log('DATA BARTHEL DARI DATABASE:', res);

                const barthel = res.data;

                /*
                |--------------------------------------------------------------------------
                | BELUM ADA DATA
                |--------------------------------------------------------------------------
                */

                if (!barthel) {

                    $sectionBarthel
                        .find('#barthel_index')
                        .prop('checked', false);

                    $sectionBarthel
                        .find('#tampil_barthel_index')
                        .prop('hidden', true);

                    resetBarthelIndex();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | DATA ADA
                |--------------------------------------------------------------------------
                */

                // console.log('Barthel:', barthel);


                // Centang checkbox utama
                $sectionBarthel
                    .find('#barthel_index')
                    .prop('checked', true);


                // Tampilkan form
                $sectionBarthel
                    .find('#tampil_barthel_index')
                    .prop('hidden', false);


                /*
                |--------------------------------------------------------------------------
                | MAPPING DATABASE
                |--------------------------------------------------------------------------
                */

                const mapping = {

                    232: 'KENDALI_RANGSANG_DEFEKASI',

                    233: 'KENDALI_RANGSANG_KEMIH',

                    234: 'BERSIH_DIRI',

                    235: 'PENGGUNAAN_JAMBAN',

                    236: 'MAKAN',

                    237: 'PERUBAHAN_SIKAP',

                    238: 'PINDAH_JALAN',

                    239: 'PAKAI_BAJU',

                    240: 'NAIK_TURUN_TANGGA',

                    241: 'MANDI'

                };


                /*
                |--------------------------------------------------------------------------
                | ISI SELECT SESUAI DATA DATABASE
                |--------------------------------------------------------------------------
                */

                Object.keys(mapping).forEach(function (jenis) {

                    const column = mapping[jenis];

                    // Nilai dari database
                    const value = barthel[column];

                    // console.log(
                    //     'JENIS:',
                    //     jenis,
                    //     '| COLUMN:',
                    //     column,
                    //     '| VALUE DATABASE:',
                    //     value
                    // );


                    const $select = $sectionBarthel.find(
                        `.barthel-item[data-jenis="${jenis}"]`
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Jika nilai database NULL
                    |--------------------------------------------------------------------------
                    */

                    if (
                        value === null ||
                        value === undefined ||
                        value === ''
                    ) {

                        $select.val('');

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Paksa menjadi string
                    |--------------------------------------------------------------------------
                    */

                    const valueString = String(value);


                    /*
                    |--------------------------------------------------------------------------
                    | Cari OPTION berdasarkan VALUE
                    |--------------------------------------------------------------------------
                    */

                    const $option = $select
                        .find('option')
                        .filter(function () {

                            return String($(this).val()) === valueString;

                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Jika OPTION ditemukan
                    |--------------------------------------------------------------------------
                    */

                    if ($option.length) {

                        $select.val(valueString);

                        // console.log(
                        //     'SELECT BERHASIL:',
                        //     jenis,
                        //     '=>',
                        //     valueString,
                        //     '=>',
                        //     $option.text().trim()
                        // );

                    } else {

                        // console.warn(
                        //     'OPTION TIDAK DITEMUKAN:',
                        //     {
                        //         jenis: jenis,
                        //         column: column,
                        //         valueDatabase: value,
                        //         options: $select
                        //             .find('option')
                        //             .map(function () {
                        //                 return $(this).val();
                        //             })
                        //             .get()
                        //     }
                        // );

                        $select.val('');

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | HITUNG ULANG SKOR
                |--------------------------------------------------------------------------
                */

                hitungBarthelIndex();


                /*
                |--------------------------------------------------------------------------
                | VALIDASI
                |--------------------------------------------------------------------------
                */

                validasiBarthelIndex();

            },


            error: function (xhr) {

                // console.error(
                //     'ERROR GET BARTHEL:',
                //     xhr
                // );

                let message =
                    'Gagal mengambil data Barthel Index.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                // console.warn(message);

            }

        });
    }



    /*
    |--------------------------------------------------------------------------
    | SIMPAN BARTHEL INDEX
    |--------------------------------------------------------------------------
    */

    function simpanBarthelIndex(btn)
    {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI SEBELUM SIMPAN
        |--------------------------------------------------------------------------
        */

        if (!validasiBarthelIndex()) {

            Swal.fire({

                icon: 'warning',

                title: 'Data belum lengkap',

                text:
                    'Silakan lengkapi seluruh penilaian Barthel Index terlebih dahulu.',

                confirmButtonText: 'OK'

            });


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        const $button =
            $(btn);


        /*
        |--------------------------------------------------------------------------
        | AMBIL FORM DATA
        |--------------------------------------------------------------------------
        */

        const data =
            getFormDataByName(

                $sectionBarthel,

                {
                    NOKUNJ:
                        kunjungan
                }

            );


        /*
        |--------------------------------------------------------------------------
        | AJAX SIMPAN
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/rajal/barthel/${kunjungan}/simpan`,

            type: 'POST',

            data: data,


            /*
            |--------------------------------------------------------------------------
            | CSRF
            |--------------------------------------------------------------------------
            */

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            },


            /*
            |--------------------------------------------------------------------------
            | BEFORE SEND
            |--------------------------------------------------------------------------
            */

            beforeSend: function () {

                $button
                    .prop(
                        'disabled',
                        true
                    )
                    .html(
                        '<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...'
                    );

            },


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            success: function (res) {

                Swal.fire({

                    position: 'top-end',

                    icon: 'success',

                    title:
                        res.message ||
                        'Barthel Index berhasil disimpan',

                    showConfirmButton: false,

                    timer: 1000,

                    toast: true

                });


                /*
                |--------------------------------------------------------------------------
                | LOAD ULANG DATA
                |--------------------------------------------------------------------------
                |
                | Supaya data yang tersimpan langsung
                | disinkronkan kembali dengan database.
                |
                */

                getBarthelIndex();

            },


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            error: function (xhr) {

                let message =
                    'Data Barthel Index gagal disimpan.';


                /*
                |--------------------------------------------------------------------------
                | VALIDATION ERROR
                |--------------------------------------------------------------------------
                */

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message =
                        Object
                            .values(
                                xhr.responseJSON.errors
                            )
                            .flat()
                            .join('<br>');

                }


                /*
                |--------------------------------------------------------------------------
                | ERROR DARI SERVER
                |--------------------------------------------------------------------------
                */

                else if (
                    xhr.responseJSON?.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN ERROR
                |--------------------------------------------------------------------------
                */

                iziToast.error({

                    title:
                        'Gagal!',

                    message:
                        message,

                    position:
                        'topRight'

                });

            },


            /*
            |--------------------------------------------------------------------------
            | COMPLETE
            |--------------------------------------------------------------------------
            */

            complete: function () {

                /*
                |--------------------------------------------------------------------------
                | Setelah selesai, cek lagi validasi
                |--------------------------------------------------------------------------
                */

                $button
                    .prop(
                        'disabled',
                        !validasiBarthelIndex()
                    )
                    .html(
                        '<i class="ri-save-line me-1"></i> Simpan Barthel Index'
                    );

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | RESET BARTHEL INDEX
    |--------------------------------------------------------------------------
    */

    function resetBarthelIndex()
    {

        /*
        |--------------------------------------------------------------------------
        | RESET SEMUA DROPDOWN
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('.barthel-item')
            .val('');


        /*
        |--------------------------------------------------------------------------
        | RESET SKOR
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#skor_barthel_input')
            .val(0);


        $sectionBarthel
            .find('#nilai_barthel')
            .text(0);


        /*
        |--------------------------------------------------------------------------
        | RESET KATEGORI
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#kategori_barthel')
            .text('-');


        /*
        |--------------------------------------------------------------------------
        | RESET KETERANGAN
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#keterangan_barthel')
            .text('');


        /*
        |--------------------------------------------------------------------------
        | SEMBUNYIKAN HASIL
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#hasil_barthel')
            .prop(
                'hidden',
                true
            );


        /*
        |--------------------------------------------------------------------------
        | DISABLE BUTTON
        |--------------------------------------------------------------------------
        */

        $sectionBarthel
            .find('#btn_simpan_barthel')
            .prop(
                'disabled',
                true
            );

    }

</script>
