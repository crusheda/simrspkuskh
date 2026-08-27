<div class="row" id="form_status_obstetri_neonatus">
    {{-- ================= STATUS OBSTETRI ================= --}}
    <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-1">
            STATUS OBSTETRI
        </h6>

        {{-- Umur Ibu --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Umur Ibu</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="number" class="form-control form-control-sm" name="so_umur_ibu" style="max-width: 80px;">
                    <span>TH</span>
                </div>
            </div>
        </div>

        {{-- Riwayat Obstetri --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Riwayat Obstetri</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <span>G</span>
                    <input type="number" class="form-control form-control-sm" name="so_g" style="max-width: 70px;">
                    <span>P</span>
                    <input type="number" class="form-control form-control-sm" name="so_p" style="max-width: 70px;">
                    <span>A</span>
                    <input type="number" class="form-control form-control-sm" name="so_a" style="max-width: 70px;">
                </div>
            </div>
        </div>

        {{-- Umur Kehamilan --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Umur Kehamilan</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="number" class="form-control form-control-sm" name="so_umur_kehamilan" style="max-width: 80px;">
                    <span>MG</span>
                </div>
            </div>
        </div>

        {{-- Komplikasi --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Komplikasi selama kehamilan</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center flex-wrap gap-2"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_komplikasi" value="0">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_komplikasi" value="1">
                        <label class="form-check-label">Ada</label>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="so_komplikasi_ket" placeholder="Keterangan..." style="max-width: 220px;">
                </div>
            </div>
        </div>

        {{-- Golongan Darah Ibu --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Golongan Darah Ibu</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center flex-wrap gap-2"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="A">
                        <label class="form-check-label">A</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="B">
                        <label class="form-check-label">B</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="O">
                        <label class="form-check-label">O</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ibu" value="AB">
                        <label class="form-check-label">AB</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_rh_ibu" value="+">
                        <label class="form-check-label">RH (+)</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_rh_ibu" value="-">
                        <label class="form-check-label">RH (-)</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Golongan Darah Ayah --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Golongan Darah Ayah</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center flex-wrap gap-2"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="A">
                        <label class="form-check-label">A</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="B">
                        <label class="form-check-label">B</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="O">
                        <label class="form-check-label">O</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_darah_ayah" value="AB">
                        <label class="form-check-label">AB</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_rh_ayah" value="+">
                        <label class="form-check-label">RH (+)</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_rh_ayah" value="-">
                        <label class="form-check-label">RH (-)</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="so_gol_ayah_tidak" value="1">
                        <label class="form-check-label">Tidak Tahu</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- KK Pecah Jam --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">KK Pecah Jam</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="time" class="form-control form-control-sm" name="so_kk_pecah_jam" style="max-width: 130px;">
                    <span>WIB</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= STATUS NEONATUS ================= --}}
    <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-1">
            STATUS NEONATUS
        </h6>

        {{-- Bayi Lahir --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Bayi lahir tanggal</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="date" class="form-control form-control-sm" name="sn_tanggal_lahir" style="max-width: 145px;">
                    <span>Jam</span>
                    <input type="time" class="form-control form-control-sm" name="sn_jam_lahir" style="max-width: 110px;">
                </div>
            </div>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Jenis Kelamin</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="sn_jenis_kelamin" value="L">
                        <label class="form-check-label">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="sn_jenis_kelamin" value="P">
                        <label class="form-check-label">Perempuan</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- BB --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">BB Lahir</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="number" class="form-control form-control-sm" name="sn_bb_lahir" style="max-width: 100px;">
                    <span>gram</span>
                </div>
            </div>
        </div>

        {{-- PB --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">PB Lahir</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="number" class="form-control form-control-sm" name="sn_pb_lahir" style="max-width: 100px;">
                    <span>cm</span>
                </div>
            </div>
        </div>
        <div class="row g-2 mb-2">
            <!-- LK -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label mb-0">LK</label>
                    <input type="number"
                        class="form-control form-control-sm"
                        name="sn_lk"
                        step="0.1">
                    <span>cm</span>
                </div>
            </div>

            <!-- LD -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label mb-0">LD</label>
                    <input type="number"
                        class="form-control form-control-sm"
                        name="sn_ld"
                        step="0.1">
                    <span>cm</span>
                </div>
            </div>

            <!-- LP -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label mb-0">LP</label>
                    <input type="number"
                        class="form-control form-control-sm"
                        name="sn_lp"
                        step="0.1">
                    <span>cm</span>
                </div>
            </div>

            <!-- LILA -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label mb-0">LILA</label>
                    <input type="number"
                        class="form-control form-control-sm"
                        name="sn_lila"
                        step="0.1">
                    <span>cm</span>
                </div>
            </div>
        </div>
        {{-- Resusitasi --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Resusitasi</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center flex-wrap gap-2"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="checkbox" name="sn_resusitasi_intubasi" value="1">
                        <label class="form-check-label">
                            Intubasi Intra Trachea
                        </label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="checkbox" name="sn_resusitasi_pompa" value="1">
                        <label class="form-check-label">
                            Pompa Udara Berulang
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Berulang --}}
        <div class="row align-items-center mb-2">
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1">
                    <input type="text" class="form-control form-control-sm" name="sn_berulang">
                </div>
            </div>
        </div>

        {{-- Jenis Partus --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Jenis Partus</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center flex-wrap gap-3"> 
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="sn_jenis_partus" value="SC">
                        <label class="form-check-label">SC</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="sn_jenis_partus" value="V">
                        <label class="form-check-label">Vacuum</label>
                    </div>
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="radio" name="sn_jenis_partus" value="S">
                        <label class="form-check-label">Spontan</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Indikasi --}}
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label mb-0">Indikasi</label>
            </div>
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-1"> 
                    <input type="text" class="form-control form-control-sm" name="sn_indikasi">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {

    'use strict';


    // ==============================================================
    // STATE
    // ==============================================================

    let isStatusObstetriNeonatusDirty = false;


    // ==============================================================
    // GET DATA
    // ==============================================================

    function getStatusObstetriNeonatus() {

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/status_obstetri_neonatus/${kunjungan}`,

            type: 'GET',

            dataType: 'json',


            success: function (res) {

                const $form =
                    $('#form_status_obstetri_neonatus');


                // ==================================================
                // STATUS OBSTETRI
                // ==================================================

                const obstetri =
                    res.obstetri;


                if (obstetri) {

                    // Text / Number
                    $form.find('[name="so_umur_ibu"]')
                        .val(obstetri.so_umur_ibu ?? '');

                    $form.find('[name="so_g"]')
                        .val(obstetri.so_g ?? '');

                    $form.find('[name="so_p"]')
                        .val(obstetri.so_p ?? '');

                    $form.find('[name="so_a"]')
                        .val(obstetri.so_a ?? '');

                    $form.find('[name="so_umur_kehamilan"]')
                        .val(obstetri.so_umur_kehamilan ?? '');


                    // Komplikasi
                    $form
                        .find(
                            `[name="so_komplikasi"][value="${obstetri.so_komplikasi}"]`
                        )
                        .prop('checked', true);

                    $form.find('[name="so_komplikasi_ket"]')
                        .val(obstetri.so_komplikasi_ket ?? '');


                    // Golongan darah ibu
                    $form
                        .find(
                            `[name="so_gol_darah_ibu"][value="${obstetri.so_gol_darah_ibu}"]`
                        )
                        .prop('checked', true);

                    $form
                        .find(
                            `[name="so_rh_ibu"][value="${obstetri.so_rh_ibu}"]`
                        )
                        .prop('checked', true);


                    // Golongan darah ayah
                    $form
                        .find(
                            `[name="so_gol_darah_ayah"][value="${obstetri.so_gol_darah_ayah}"]`
                        )
                        .prop('checked', true);

                    $form
                        .find(
                            `[name="so_rh_ayah"][value="${obstetri.so_rh_ayah}"]`
                        )
                        .prop('checked', true);


                    // Ayah tidak tahu
                    $form
                        .find(
                            `[name="so_gol_ayah_tidak"][value="${obstetri.so_gol_ayah_tidak}"]`
                        )
                        .prop('checked', true);


                    // KK pecah
                    $form
                        .find('[name="so_kk_pecah_jam"]')
                        .val(obstetri.so_kk_pecah_jam ?? '');

                }


                // ==================================================
                // STATUS NEONATUS
                // ==================================================

                const neonatus =
                    res.neonatus;


                if (neonatus) {

                    $form.find('[name="sn_tanggal_lahir"]')
                        .val(neonatus.sn_tanggal_lahir ?? '');

                    $form.find('[name="sn_jam_lahir"]')
                        .val(neonatus.sn_jam_lahir ?? '');


                    // Jenis kelamin
                    $form
                        .find(
                            `[name="sn_jenis_kelamin"][value="${neonatus.sn_jenis_kelamin}"]`
                        )
                        .prop('checked', true);


                    // Berat / panjang badan
                    $form.find('[name="sn_bb_lahir"]')
                        .val(neonatus.sn_bb_lahir ?? '');

                    $form.find('[name="sn_pb_lahir"]')
                        .val(neonatus.sn_pb_lahir ?? '');


                    // Lingkar
                    $form.find('[name="sn_lk"]')
                        .val(neonatus.sn_lk ?? '');

                    $form.find('[name="sn_ld"]')
                        .val(neonatus.sn_ld ?? '');

                    $form.find('[name="sn_lp"]')
                        .val(neonatus.sn_lp ?? '');

                    $form.find('[name="sn_lila"]')
                        .val(neonatus.sn_lila ?? '');


                    // Resusitasi
                    $form
                        .find('[name="sn_resusitasi_intubasi"]')
                        .prop(
                            'checked',
                            neonatus.sn_resusitasi_intubasi == 1
                        );

                    $form
                        .find('[name="sn_resusitasi_pompa"]')
                        .prop(
                            'checked',
                            neonatus.sn_resusitasi_pompa == 1
                        );


                    // Berulang
                    $form.find('[name="sn_berulang"]')
                        .val(neonatus.sn_berulang ?? '');


                    // Jenis partus
                    $form
                        .find(
                            `[name="sn_jenis_partus"][value="${neonatus.sn_jenis_partus}"]`
                        )
                        .prop('checked', true);


                    // Indikasi
                    $form.find('[name="sn_indikasi"]')
                        .val(neonatus.sn_indikasi ?? '');

                }


                // GET bukan perubahan user
                isStatusObstetriNeonatusDirty = false;

            },


            error: function (xhr, status, error) {

                console.error(
                    'Error Status Obstetri dan Neonatus:',
                    xhr.responseText || error
                );

            }

        });

    }


    // ==============================================================
    // SIMPAN
    // ==============================================================

    function simpanStatusObstetriNeonatus() {

        const $form =
            $('#form_status_obstetri_neonatus');


        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/status_obstetri_neonatus/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            success: function (res) {

                console.log(
                    'Status Obstetri dan Neonatus berhasil disimpan.',
                    res
                );

            },


            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    message =
                        Object.values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('&nbsp;');

                }

                else if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                iziToast.error({

                    title: 'Validasi Gagal!',

                    message: message,

                    position: 'topRight'

                });

            }

        });

    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================

    $(function () {

        const $form =
            $('#form_status_obstetri_neonatus');


        // ==========================================================
        // LOAD DATA
        // ==========================================================

        getStatusObstetriNeonatus();


        // ==========================================================
        // INPUT
        // ==========================================================

        $form.on(
            'input',
            'input[type="text"], input[type="number"], input[type="date"], input[type="time"]',
            function () {

                isStatusObstetriNeonatusDirty = true;

            }
        );


        // ==========================================================
        // BLUR
        // ==========================================================

        $form.on(
            'blur',
            'input[type="text"], input[type="number"], input[type="date"], input[type="time"]',
            function () {

                if (!isStatusObstetriNeonatusDirty) {
                    return;
                }


                simpanStatusObstetriNeonatus();


                isStatusObstetriNeonatusDirty = false;

            }
        );


        // ==========================================================
        // RADIO / CHECKBOX
        // ==========================================================

        $form.on(
            'change',
            'input[type="radio"], input[type="checkbox"]',
            function (e) {

                // Jangan save ketika GET sedang mengisi form
                if (!e.originalEvent) {
                    return;
                }


                simpanStatusObstetriNeonatus();

            }
        );

    });

})();
</script>