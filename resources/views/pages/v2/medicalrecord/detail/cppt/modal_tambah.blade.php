
<div class="card border border-info text-info-emphasis border-dashed rounded-3 shadow-none mb-3 mt-2">
    <div class="card-header bg-info-subtle p-0" data-bs-toggle="tooltip" title="Buka / Sembunyikan Form Tambah CPPT">
        <button type="button" class="btn w-100 text-start d-flex align-items-center justify-content-between px-3 py-3 shadow-none" data-bs-toggle="collapse" data-bs-target="#collapseTambahCPPT" aria-expanded="true" aria-controls="collapseTambahCPPT">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-duotone ph-plus-circle text-primary fs-20"></i>
                <strong>Tambah Catatan CPPT</strong>
            </div>
            <i class="ph ph-caret-down"></i>
        </button>
    </div>
    <div class="collapse show" id="collapseTambahCPPT">
        <div class="card-body">
            <div class="row g-3">
                {{-- TANGGAL --}}
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="cppt_tanggal">
                </div>
                {{-- JAM --}}
                <div class="col-md-2">
                    <label class="form-label">Jam</label>
                    <input type="time" class="form-control" id="cppt_jam">
                </div>
                {{-- PPA --}}
                <div class="col-md-5 position-relative">
                    <label class="form-label">
                        PPA
                        <span class="badge bg-primary-subtle text-primary ms-1">
                            Default: User Login
                        </span>
                    </label>
                    <input type="text" class="form-control" id="cppt_ppa" placeholder="Cari nama atau NIP PPA..." autocomplete="off">
                    <input type="hidden" id="cppt_ppa_id">
                    <div id="cppt_ppa_autocomplete" class="list-group position-absolute start-0 end-0 shadow-sm bg-body" style="z-index:1050;display:none;"></div>
                </div>
                {{-- METODE CPPT --}}
                <div class="col-md-2">
                    <label class="form-label">Metode</label>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        {{-- SBAR --}}
                        <div class="form-check">
                            <input class="form-check-input cppt-format"
                                type="checkbox"
                                id="cppt_format_sbar"
                                value="SBAR">
                            <label class="form-check-label" for="cppt_format_sbar">
                                SBAR
                            </label>
                        </div>
                        {{-- TBAK --}}
                        <div class="form-check">
                            <input class="form-check-input cppt-format"
                                type="checkbox"
                                id="cppt_format_tbak"
                                value="TBAK">
                            <label class="form-check-label" for="cppt_format_tbak">
                                TBAK
                            </label>
                        </div>
                    </div>
                </div>
                {{-- S --}}
                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold">S <small class="text-muted">(Subjective)</small></label>
                    <textarea class="form-control" id="cppt_s" rows="4" placeholder="Keluhan atau kondisi yang dirasakan pasien..."></textarea>
                </div> --}}
                {{-- O --}}
                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold">O <small class="text-muted">(Objective)</small></label>
                    <textarea class="form-control" id="cppt_o" rows="4" placeholder="Hasil pemeriksaan objektif..."></textarea>
                </div> --}}
                {{-- A --}}
                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold">A <small class="text-muted">(Assessment)</small></label>
                    <textarea class="form-control" id="cppt_a" rows="4" placeholder="Assessment atau diagnosis pasien..."></textarea>
                </div> --}}
                {{-- P --}}
                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold">P <small class="text-muted">(Planning)</small></label>
                    <textarea class="form-control" id="cppt_p" rows="4" placeholder="Rencana terapi atau tindak lanjut..."></textarea>
                </div> --}}
                {{-- I --}}
                {{-- <div class="col-12">
                    <label class="form-label fw-semibold">I <small class="text-muted">(Instruction)</small></label>
                    <textarea class="form-control" id="cppt_i" rows="4" placeholder="Instruksi untuk tindak lanjut pasien..."></textarea>
                </div> --}}
                {{-- ========================================================= --}}
                {{-- MODE CPPT BIASA --}}
                {{-- ========================================================= --}}
                <div id="cppt_mode_biasa" class="row g-3">

                    {{-- S --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            S <small class="text-muted">(Subjective)</small>
                        </label>
                        <textarea class="form-control" id="cppt_s" rows="4" placeholder="Keluhan atau kondisi yang dirasakan pasien..."></textarea>
                    </div>

                    {{-- O --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            O <small class="text-muted">(Objective)</small>
                        </label>
                        <textarea class="form-control" id="cppt_o" rows="4" placeholder="Hasil pemeriksaan objektif..."></textarea>
                    </div>

                    {{-- A --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            A <small class="text-muted">(Assessment)</small>
                        </label>
                        <textarea class="form-control" id="cppt_a" rows="4" placeholder="Assessment atau diagnosis pasien..."></textarea>
                    </div>

                    {{-- P --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            P <small class="text-muted">(Planning)</small>
                        </label>
                        <textarea class="form-control" id="cppt_p" rows="4" placeholder="Rencana terapi atau tindak lanjut..."></textarea>
                    </div>

                    {{-- I --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            I <small class="text-muted">(Instruction)</small>
                        </label>
                        <textarea class="form-control" id="cppt_i" rows="4" placeholder="Instruksi untuk tindak lanjut pasien..."></textarea>
                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- MODE CPPT SBAR --}}
                {{-- ========================================================= --}}
                <div id="cppt_mode_sbar" class="row g-3" style="display: none;">

                    {{-- SITUATION --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Situation <small class="text-muted">(Situasi)</small>
                        </label>
                        <textarea class="form-control" id="cppt_sbar_situation" rows="4" placeholder="Situasi atau kondisi pasien saat ini..."></textarea>
                    </div>

                    {{-- BACKGROUND --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Background <small class="text-muted">(Latar Belakang)</small>
                        </label>
                        <textarea class="form-control" id="cppt_sbar_background" rows="4" placeholder="Latar belakang atau riwayat yang relevan..."></textarea>
                    </div>

                    {{-- ASSESSMENT --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Assessment <small class="text-muted">(Penilaian)</small>
                        </label>
                        <textarea class="form-control" id="cppt_sbar_assessment" rows="4" placeholder="Penilaian atau hasil analisis kondisi pasien..."></textarea>
                    </div>

                    {{-- RECOMMENDATION --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Recommendation <small class="text-muted">(Rekomendasi)</small>
                        </label>
                        <textarea class="form-control" id="cppt_sbar_recommendation" rows="4" placeholder="Rekomendasi atau tindak lanjut..."></textarea>
                    </div>

                    {{-- DOKTER SBAR --}}
                    <div class="col-md-12 position-relative">
                        <label class="form-label">
                            Dokter SBAR
                        </label>

                        <input
                            type="text" class="form-control" id="cppt_dokter_sbar" placeholder="Cari nama atau NIP dokter..."
                            autocomplete="off">

                        <input
                            type="hidden" id="cppt_dokter_sbar_id">

                        <div id="cppt_dokter_sbar_autocomplete" class="list-group position-absolute start-0 end-0 shadow-sm bg-body"
                            style="z-index:1050;display:none;"></div>
                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- MODE CPPT TBAK --}}
                {{-- ========================================================= --}}
                <div id="cppt_mode_tbak" class="row g-3" style="display: none;">

                    {{-- TULIS --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Tulis
                        </label>

                        <textarea class="form-control" id="cppt_tulis" rows="4" placeholder="Tuliskan informasi yang akan disampaikan..."></textarea>
                    </div>

                    {{-- BACA --}}
                    <div class="col-md-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="cppt_baca"
                                value="1"
                            >
                            <label
                                class="form-check-label"
                                for="cppt_baca"
                            >
                                Baca
                            </label>
                        </div>
                    </div>

                    {{-- KONFIRMASI --}}
                    <div class="col-md-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="cppt_konfirmasi"
                                value="1"
                            >
                            <label
                                class="form-check-label"
                                for="cppt_konfirmasi"
                            >
                                Konfirmasi
                            </label>
                        </div>
                    </div>

                    {{-- DOKTER TBAK --}}
                    <div class="col-md-6 position-relative">
                        <label class="form-label">
                            Dokter TBAK
                        </label>

                        <input
                            type="text" class="form-control" id="cppt_dokter_tbak" placeholder="Cari nama atau NIP dokter..."
                            autocomplete="off">

                        <input
                            type="hidden" id="cppt_dokter_tbak_id">

                        <div id="cppt_dokter_tbak_autocomplete" class="list-group position-absolute start-0 end-0 shadow-sm bg-body"
                            style="z-index:1050;display:none;"></div>
                    </div>

                </div>
                {{-- BUTTON --}}
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" id="btn-batal-cppt" data-bs-toggle="collapse" data-bs-target="#collapseTambahCPPT" aria-expanded="true" aria-controls="collapseTambahCPPT">
                            <i class="ph ph-x me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-simpan-cppt">
                            <i class="ph ph-floppy-disk me-1"></i> Tambah CPPT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setModeCPPT(mode) {

        $('#cppt_mode_biasa').hide();
        $('#cppt_mode_sbar').hide();
        $('#cppt_mode_tbak').hide();

        if (mode === 'SBAR') {

            $('#cppt_mode_sbar').show();

        } else if (mode === 'TBAK') {

            $('#cppt_mode_tbak').show();

        } else {

            $('#cppt_mode_biasa').show();

        }
    }


    $('.cppt-format').on('change', function () {

        const id = $(this).attr('id');

        if (id === 'cppt_format_sbar' && $(this).is(':checked')) {

            // SBAR dipilih → TBAK dimatikan
            $('#cppt_format_tbak').prop('checked', false);

            setModeCPPT('SBAR');

        } else if (id === 'cppt_format_tbak' && $(this).is(':checked')) {

            // TBAK dipilih → SBAR dimatikan
            $('#cppt_format_sbar').prop('checked', false);

            setModeCPPT('TBAK');

        } else {

            // Tidak ada yang dicentang → CPPT biasa
            if (
                !$('#cppt_format_sbar').is(':checked') &&
                !$('#cppt_format_tbak').is(':checked')
            ) {
                setModeCPPT('BIASA');
            }
        }

    });

    function initAutocompletePPA() {

        const $input = $('#cppt_ppa');
        const $container = $('#cppt_ppa_autocomplete');

        // Cegah event terpasang berulang
        $input.off('.autocompletePPA');
        $container.off('.autocompletePPA');

        /*
        |--------------------------------------------------------------------------
        | INPUT / SEARCH
        |--------------------------------------------------------------------------
        */

        $input.on('input.autocompletePPA', function () {

            const keyword = $(this).val().trim().toLowerCase();

            // User mengetik ulang → pilihan sebelumnya batal
            ppaSelected = false;

            $('#cppt_ppa_id').val('');
            $input.removeData('nip');

            $container.empty();

            if (keyword.length < 2) {
                $container.hide();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER PPA
            |--------------------------------------------------------------------------
            */

            const hasil = dataPPA.filter(item => {

                const nama = String(item.NAMA || '').toLowerCase();
                const nip  = String(item.NIP || '').toLowerCase();

                return nama.includes(keyword) ||
                    nip.includes(keyword);

            }).slice(0, 10);

            /*
            |--------------------------------------------------------------------------
            | TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

            if (!hasil.length) {

                $container.html(`
                    <div class="list-group-item text-muted">
                        <i class="ph ph-magnifying-glass me-1"></i>
                        PPA tidak ditemukan
                    </div>
                `).show();

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN HASIL
            |--------------------------------------------------------------------------
            */

            hasil.forEach(item => {

                $container.append(`
                    <button
                        type="button"
                        class="list-group-item list-group-item-action cppt-ppa-item text-start bg-body"
                        data-id="${item.ID ?? ''}"
                        data-nip="${item.NIP ?? ''}"
                        data-nama="${item.NAMA ?? ''}">

                        <div class="fw-semibold">
                            ${item.NAMA ?? '-'}
                        </div>

                        <small class="text-muted">
                            NIP: ${item.NIP ?? '-'}
                        </small>

                    </button>
                `);

            });

            $container.show();
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH PPA
        |--------------------------------------------------------------------------
        */

        $container.on(
            'click.autocompletePPA',
            '.cppt-ppa-item',
            function () {

                const id   = $(this).data('id');
                const nip  = $(this).data('nip');
                const nama = $(this).data('nama');

                $('#cppt_ppa_id').val(id);
                $('#cppt_ppa').val(nama);
                $('#cppt_ppa').data('nip', nip);

                ppaSelected = true;

                $container
                    .hide()
                    .empty();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | BLUR
        |--------------------------------------------------------------------------
        */

        $input.on('blur.autocompletePPA', function () {

            setTimeout(function () {

                $container.hide();

                if (
                    !ppaSelected ||
                    !$('#cppt_ppa_id').val()
                ) {

                    $input.val('');
                    $('#cppt_ppa_id').val('');
                    $input.removeData('nip');

                    ppaSelected = false;
                }

            }, 200);
        });
    }

    function initAutocompleteDokter() {

        initAutocompleteDokterField({
            input: '#cppt_dokter_sbar',
            hidden: '#cppt_dokter_sbar_id',
            container: '#cppt_dokter_sbar_autocomplete'
        });

        initAutocompleteDokterField({
            input: '#cppt_dokter_tbak',
            hidden: '#cppt_dokter_tbak_id',
            container: '#cppt_dokter_tbak_autocomplete'
        });
    }


    function initAutocompleteDokterField(config) {

        const $input = $(config.input);
        const $hidden = $(config.hidden);
        const $container = $(config.container);

        // Cegah event duplicate
        $input.off('.autocompleteDokter');
        $container.off('.autocompleteDokter');

        /*
        |--------------------------------------------------------------------------
        | INPUT / SEARCH
        |--------------------------------------------------------------------------
        */

        $input.on('input.autocompleteDokter', function () {

            const keyword = $(this)
                .val()
                .trim()
                .toLowerCase();

            // User mengetik ulang → ID dokter dibatalkan
            $hidden.val('');
            $input.removeData('nip');

            $container.empty();

            if (keyword.length < 2) {
                $container.hide();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER DOKTER
            |--------------------------------------------------------------------------
            */

            const hasil = dataDokter.filter(item => {

                const nama = String(item.NAMA || '')
                    .toLowerCase();

                const nip = String(item.NIP || '')
                    .toLowerCase();

                return nama.includes(keyword) ||
                    nip.includes(keyword);

            }).slice(0, 10);

            /*
            |--------------------------------------------------------------------------
            | TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

            if (!hasil.length) {

                $container.html(`
                    <div class="list-group-item text-muted">
                        <i class="ph ph-magnifying-glass me-1"></i>
                        Dokter tidak ditemukan
                    </div>
                `).show();

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

            hasil.forEach(item => {

                $container.append(`
                    <button
                        type="button"
                        class="list-group-item list-group-item-action cppt-dokter-item text-start bg-body"
                        data-id="${item.ID ?? ''}"
                        data-nip="${item.NIP ?? ''}"
                        data-nama="${item.NAMA ?? ''}">

                        <div class="fw-semibold">
                            ${item.NAMA ?? '-'}
                        </div>

                        <small class="text-muted">
                            NIP: ${item.NIP ?? '-'}
                        </small>

                    </button>
                `);

            });

            $container.show();
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH DOKTER
        |--------------------------------------------------------------------------
        */

        $container.on(
            'click.autocompleteDokter',
            '.cppt-dokter-item',
            function () {

                const id = $(this).data('id');
                const nip = $(this).data('nip');
                const nama = $(this).data('nama');

                // Nama dokter
                $input.val(nama);

                // ID dokter
                $hidden.val(id);

                // NIP
                $input.data('nip', nip);

                // Tutup autocomplete
                $container
                    .hide()
                    .empty();

                console.log('Dokter dipilih:', {
                    id: id,
                    nip: nip,
                    nama: nama
                });
            }
        );

        /*
        |--------------------------------------------------------------------------
        | BLUR
        |--------------------------------------------------------------------------
        */

        $input.on('blur.autocompleteDokter', function () {

            setTimeout(function () {

                $container.hide();

                // Kalau ID kosong berarti belum memilih dari autocomplete
                if (!$hidden.val()) {

                    $input.val('');
                    $hidden.val('');
                    $input.removeData('nip');
                }

            }, 200);
        });
    }

    $('#btn-simpan-cppt').on('click', function () {

        const $btn = $(this);

        /*
        |--------------------------------------------------------------------------
        | AMBIL MODE CPPT
        |--------------------------------------------------------------------------
        */

        let mode = 'BIASA';

        if ($('#cppt_format_sbar').is(':checked')) {
            mode = 'SBAR';
        } else if ($('#cppt_format_tbak').is(':checked')) {
            mode = 'TBAK';
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PPA
        |--------------------------------------------------------------------------
        */

        const ppaId = $('#cppt_ppa_id').val();

        if (!ppaId) {

            iziToast.warning({
                title: 'Perhatian!',
                message: 'Silakan pilih PPA terlebih dahulu.',
                position: 'topRight'
            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA UMUM
        |--------------------------------------------------------------------------
        */

        const tanggal = $('#cppt_tanggal').val();
        const jam = $('#cppt_jam').val();


        if (!tanggal || !jam) {

            iziToast.warning({
                title: 'Perhatian!',
                message: 'Tanggal dan jam harus diisi.',
                position: 'topRight'
            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DOKTER SBAR / TBAK
        |--------------------------------------------------------------------------
        */

        let dokterId = null;

        if (mode === 'SBAR') {
            dokterId = $('#cppt_dokter_sbar_id').val() || null;
        }

        if (mode === 'TBAK') {
            dokterId = $('#cppt_dokter_tbak_id').val() || null;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA YANG DIKIRIM
        |--------------------------------------------------------------------------
        */

        let data = {

            _token: $('meta[name="csrf-token"]').attr('content'),

            tanggal: tanggal,
            jam: jam,

            ppa_id: ppaId,

            mode: mode,

            dokter_id: dokterId,


            /*
            | CPPT Biasa / SBAR
            */
            s: $('#cppt_s').val() || '',
            o: $('#cppt_o').val() || '',
            a: $('#cppt_a').val() || '',
            p: $('#cppt_p').val() || '',
            i: $('#cppt_i').val() || '',


            /*
            | TBAK
            */
            tulis: $('#cppt_tulis').val() || '',

            baca: $('#cppt_baca').is(':checked') ? 1 : 0,

            konfirmasi: $('#cppt_konfirmasi').is(':checked') ? 1 : 0
        };


        /*
        |--------------------------------------------------------------------------
        | MODE SBAR
        |--------------------------------------------------------------------------
        |
        | Input SBAR kita masukkan ke field yang sama:
        |
        | Situation      -> s
        | Background     -> o
        | Assessment     -> a
        | Recommendation -> p
        |
        */

        if (mode === 'SBAR') {

            data.s = $('#cppt_sbar_situation').val() || '';
            data.o = $('#cppt_sbar_background').val() || '';
            data.a = $('#cppt_sbar_assessment').val() || '';
            data.p = $('#cppt_sbar_recommendation').val() || '';
            data.i = '';
        }


        /*
        |--------------------------------------------------------------------------
        | MODE TBAK
        |--------------------------------------------------------------------------
        */

        if (mode === 'TBAK') {

            data.s = '';
            data.o = '';
            data.a = '';
            data.p = '';
            data.i = '';
        }


        /*
        |--------------------------------------------------------------------------
        | AJAX SIMPAN
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: '/api/v2/emr/cppt/' + currentKunjunganCppt,

            type: 'POST',

            data: data,

            dataType: 'json',

            beforeSend: function () {

                $btn
                    .prop('disabled', true)
                    .html(`
                        <span class="spinner-border spinner-border-sm me-1"
                            role="status"></span>
                        Menyimpan...
                    `);
            },

            success: function (res) {

                iziToast.success({
                    title: 'Berhasil!',
                    message: res.message || 'CPPT berhasil ditambahkan.',
                    position: 'topRight'
                });


                /*
                |--------------------------------------------------------------------------
                | RESET FORM
                |--------------------------------------------------------------------------
                */

                resetFormCPPT();


                // Set tanggal & jam otomatis saat ini
                const now = new Date();

                const tahun = now.getFullYear();
                const bulan = String(now.getMonth() + 1).padStart(2, '0');
                const tanggal = String(now.getDate()).padStart(2, '0');

                const jam = String(now.getHours()).padStart(2, '0');
                const menit = String(now.getMinutes()).padStart(2, '0');

                $('#cppt_tanggal').val(`${tahun}-${bulan}-${tanggal}`);
                $('#cppt_jam').val(`${jam}:${menit}`);


                /*
                |--------------------------------------------------------------------------
                | REFRESH RIWAYAT CPPT
                |--------------------------------------------------------------------------
                */

                showModalCppt(currentKunjunganCppt);
            },

            error: function (xhr) {

                let message = 'Data CPPT gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');

                } else if (xhr.responseJSON?.message) {

                    message = xhr.responseJSON.message;
                }


                iziToast.error({
                    title: 'Proses Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {

                $btn
                    .prop('disabled', false)
                    .html(`
                        <i class="ph ph-floppy-disk me-1"></i>
                        Tambah CPPT
                    `);
            }
        });

    });


</script>
