<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">ANAK</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold"> Keluhan Utama </label>
                    <input class="form-control form-control" type="text" name="anm_ku" id="anm_ku">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rja_perawat',
                        'page' => 'perawat',
                        // 'editableFields' => [
                        //     'tv_keu',
                        //     'tv_gcs_e',
                        //     'tv_gcs_v',
                        //     'tv_gcs_m',
                        //     'tv_bb',
                        //     'tv_tb',
                        // ],
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rja_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_strong_kid', ['section' => '#rja_perawat'])
            </div>
             <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rja_perawat',
                        'metodeNyeri' => ['nrs', 'vas', 'flacc']
                    ]
                )
            </div>
            <div class="col-md-12 mb-1">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rja_perawat'])
            </div>
            <div class="col-md-12">
                <div class="card card-body border border-dashed border-primary">
                    <h6 class="mb-3">RIWAYAT PERINATAL</h6>
                    <!-- Lama Hamil -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label">Lama Hamil</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control" id="rp_lama_hamil" name="rp_lama_hamil" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="rp_satuan" name="rp_satuan">
                                <option value="MINGGU">Minggu</option>
                                <option value="BULAN">Bulan</option>
                            </select>
                        </div>
                    </div>
                    <!-- Komplikasi Kehamilan -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            Komplikasi Kehamilan
                        </label>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rp_komplikasi" id="rp_komplikasi_tidak" value="0" checked>
                                <label class="form-check-label">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rp_komplikasi" id="rp_komplikasi_ya" value="1">

                                <label class="form-check-label">
                                    Ya
                                </label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="rp_komplikasi_ket" name="rp_komplikasi_ket" placeholder="Jelaskan..." style="display:none;">
                        </div>

                    </div>

                    <!-- Riwayat Persalinan -->
                    <div class="row mb-1">

                        <label class="col-md-3 col-form-label">
                            Riwayat Persalinan
                        </label>
                        <div class="col-md-9">
                            <div class="d-flex flex-wrap gap-4">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rp_persalinan" value="1" checked>
                                    <label class="form-check-label">
                                        Spontan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rp_persalinan" value="2">
                                    <label class="form-check-label">
                                        Sectio
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rp_persalinan" value="3">
                                    <label class="form-check-label">
                                        Vacuum Ekstraksi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rp_persalinan" value="4">
                                    <label class="form-check-label">
                                        Forceps Ekstraksi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Penyulit Persalinan -->
                    <div class="row">
                        <label class="col-md-3 col-form-label">
                            Penyulit Persalinan
                        </label>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rp_penyulit" id="rp_penyulit_tidak" value="0" checked>
                                <label class="form-check-label">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rp_penyulit" id="rp_penyulit_ya" value="1">
                                <label class="form-check-label">
                                    Ya
                                </label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="rp_penyulit_ket" name="rp_penyulit_ket" placeholder="Jelaskan..." style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <div class="card card-body border border-dashed border-primary">
                    <h6 class="mb-3">RIWAYAT TUMBUH KEMBANG</h6>
                    <div class="row">
                        <!-- KOLOM KIRI -->
                        <div class="col-md-6">
                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">Lingkar Kepala Saat Lahir</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="lk_lahir" id="lk_lahir">
                                </div>
                                <div class="col-md-4">cm</div>
                            </div>

                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">Berat Badan Saat Lahir</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="bb_lahir" id="bb_lahir">
                                </div>
                                <div class="col-md-4">gram</div>
                            </div>

                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">Tinggi Badan Saat Lahir</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="tb_lahir" id="tb_lahir">
                                </div>
                                <div class="col-md-4">cm</div>
                            </div>

                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">ASI Sampai Umur</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="asi_sampai" id="asi_sampai">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="asi_satuan" id="asi_satuan">
                                        <option value="BULAN">Bulan</option>
                                        <option value="TAHUN">Tahun</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">Susu Formula Mulai</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="sufor_mulai" id="sufor_mulai">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="sufor_satuan" id="sufor_satuan">
                                        <option value="BULAN">Bulan</option>
                                        <option value="TAHUN">Tahun</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <label class="col-md-4 col-form-label">Makanan Tambahan</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="mpasi_mulai" id="mpasi_mulai">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="mpasi_satuan" id="mpasi_satuan">
                                        <option value="BULAN">Bulan</option>
                                        <option value="TAHUN">Tahun</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="col-md-6">

                            @php
                            $milestone = [
                                'tengkurap' => 'Tengkurap',
                                'duduk' => 'Duduk',
                                'merangkak' => 'Merangkak',
                                'berdiri' => 'Berdiri',
                                'berjalan' => 'Berjalan'
                            ];
                            @endphp

                            @foreach($milestone as $name=>$label)

                            <div class="row mb-2 align-items-center">
                                <label class="col-md-4 col-form-label">{{ $label }}</label>
                                <div class="col-md-5">
                                    <input type="number" class="form-control" name="{{ $name }}" id="{{ $name }}">
                                </div>
                                <div class="col-md-3">
                                    Bulan
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <!-- Masalah Neonatus -->
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            Masalah Neonatus
                        </div>
                        <div class="col-md-6 mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="neonatus" value="0" checked>
                                <label class="form-check-label">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="neonatus" value="1">
                                <label class="form-check-label">
                                    Ya
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <input type="text" class="form-control" name="neonatus_ket" placeholder="Contoh : Jaundice, RDS, PJB, Kelainan Kongenital" style="display:none;">
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">
                            Keluhan Tumbuh Kembang Sekarang
                        </label>
                        <textarea class="form-control" rows="3" name="keluhan_tumbuh_kembang"></textarea>
                    </div>
                    <hr>
                    <!-- Imunisasi -->
                    <label class="form-label fw-bold">
                        Riwayat Imunisasi
                    </label>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="imunisasi" value="1">
                            <label class="form-check-label">
                                Imunisasi Dasar Lengkap
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="imunisasi" value="2">
                            <label class="form-check-label">
                                Imunisasi Dasar Tidak Lengkap
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="imunisasi" value="3">
                            <label class="form-check-label">
                                Tidak Imunisasi
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="imunisasi" value="4">
                            <label class="form-check-label">
                                Lain-lain
                            </label>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="text" class="form-control" name="imunisasi_lain" placeholder="Jelaskan..." style="display:none;">
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rja_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-warning mb-1">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="form-group">
                                <h5 class="border-bottom pb-2 text-bold">
                                    <strong>Masalah Keperawatan </strong>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_1" id="diag_1">
                                        <label class="form-check-label">Bersihkan jalan nafas tidak efektif</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_2" id="diag_2">
                                        <label class="form-check-label">Pola nafas tidak efektif</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_3" id="diag_3">
                                        <label class="form-check-label">Perfusi perifer tidak efektif</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_4" id="diag_4">
                                        <label class="form-check-label">Diare</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_5" id="diag_5">
                                        <label class="form-check-label">Nyeri Akut</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_6" id="diag_6">
                                        <label class="form-check-label">Nausea</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_7" id="diag_7">
                                        <label class="form-check-label">Hipertermi</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_8" id="diag_8">
                                        <label class="form-check-label">Ansietas</label>
                                    </div>

                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_9" id="diag_9">
                                        <label class="form-check-label">Gangguan integritas kulit / jaringan</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_10" id="diag_10">
                                        <label class="form-check-label">Gangguan eliminasi urin</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_11" id="diag_11">
                                        <label class="form-check-label">Intoleransi aktifitas</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_12" id="diag_12">
                                        <label class="form-check-label">Gangguan mobilitas fisik</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_13" id="diag_13">
                                        <label class="form-check-label">Gangguan pertukaran gas</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <label class="form-input-label">Masalah Keperawatan Lainnya</label>
                                        <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Perencanaan dan Tindakan -->
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <label class="form-label fw-bold">Perencanaan dan Tindakan</label>

                                <!-- Mandiri -->
                                <div class="col-md-6 border-end">

                                    <label class="form-label fw-semibold">Mandiri</label>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_1" id="tin_1">
                                        <label class="form-check-label" for="tin_1">Ajarkan teknik relaksasi dan nafas dalam</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_2" id="tin_2">
                                        <label class="form-check-label" for="tin_2">Pertahankan body alignment dan posisi yang nyaman</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_3" id="tin_3" checked>
                                        <label class="form-check-label" for="tin_3">Tenangkan Pasien</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_4" id="tin_4" checked>
                                        <label class="form-check-label" for="tin_4">Berikan Pendidikan Kesehatan ke pasien dan keluarga</label>
                                    </div>

                                </div>

                                <!-- Kolaborasi -->
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">Kolaborasi</label>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_5" id="tin_5">
                                        <label class="form-check-label" for="tin_5">Rawat Luka</label>
                                    </div>

                                    <div class="row align-items-center mb-2">
                                        <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6" checked>
                                                <label class="form-check-label">Oral</label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="terapi_oral" id="terapi_oral">
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_7" id="tin_7">
                                                <label class="form-check-label">IV/SC/IM</label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="terapi_iv" id="terapi_iv">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJAp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rja_perawat');

        // Sembunyikan textarea saat pertama kali
        $('#terapi_oral').hide();
        $('#terapi_iv').hide();

        //Terapi Oral
        $('#tin_6').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_oral').show();
            } else {
                $('#terapi_oral').hide();
                $('#terapi_oral').val('');
            }
        });

        //Terapi Iv
        $('#tin_7').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_iv').show();
            } else {
                $('#terapi_iv').hide();
                $('#terapi_iv').val('');
            }
        });
        loadDataPengkajianRJAp();

    });

    $(document).on('change','input[name="rp_komplikasi"]',function(){
        if($(this).val() == "1"){
            $("#rp_komplikasi_ket").show().focus();
        }else{
            $("#rp_komplikasi_ket").hide().val("");
        }
    });

    $(document).on('change','input[name="rp_penyulit"]',function(){
        if($(this).val() == "1"){
            $("#rp_penyulit_ket").show().focus();
        }else{
            $("#rp_penyulit_ket").hide().val("");
        }
    });

    $(document).on('change','input[name="neonatus"]',function(){
        if($(this).val()==1){
            $("input[name='neonatus_ket']").show().focus();
        }else{
            $("input[name='neonatus_ket']").hide().val('');
        }
    });

    $(document).on('change','input[name="imunisasi"]',function(){
        if($(this).val()==4){
            $("input[name='imunisasi_lain']").show().focus();
        }else{
            $("input[name='imunisasi_lain']").hide().val('');
        }
    });

    function loadDataPengkajianRJAp() {
        const kunjungan = $('#rja_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rja/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJAp(res);
            }
        });
    }

    function setValIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(value);
        }
    }

    function setNumberIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            }));
        }
    }

    function setSuhuIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 2
            }));
        }
    }

    function setCheckedIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector)
                .prop('checked', Number(value) === 1)
                .trigger('change');
        }
    }

    function setRadioIfExists(name, value) {
        if (value !== null && value !== undefined && value !== '') {
            $('input[name="' + name + '"][value="' + value + '"]')
                .prop('checked', true)
                .trigger('change');
        }
    }

    function isiFormPengkajianRJAp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================
        setValIfExists('#ku', data.ku);

        //Riwayat Perinatal
        setValIfExists('#rp_lama_hamil', data.rp_lama_hamil);
        setValIfExists('#rp_satuan', data.rp_satuan);

        setRadioIfExists('rp_komplikasi', data.rp_komplikasi);

        setValIfExists('#rp_komplikasi_ket', data.rp_komplikasi_ket);

        setRadioIfExists('rp_persalinan', data.rp_persalinan);

        setRadioIfExists('rp_penyulit', data.rp_penyulit);

        setValIfExists('#rp_penyulit_ket', data.rp_penyulit_ket);

        // Riwayat Tumbuh Kembang
        setValIfExists('#lk_lahir', data.lk_lahir);
        setValIfExists('#bb_lahir', data.bb_lahir);
        setValIfExists('#tb_lahir', data.tb_lahir);

        setValIfExists('#asi_sampai', data.asi_sampai);
        setValIfExists('#asi_satuan', data.asi_satuan);

        setValIfExists('#sufor_mulai', data.sufor_mulai);
        setValIfExists('#sufor_satuan', data.sufor_satuan);

        setValIfExists('#mpasi_mulai', data.mpasi_mulai);
        setValIfExists('#mpasi_satuan', data.mpasi_satuan);

        setValIfExists('#tengkurap', data.tengkurap);
        setValIfExists('#duduk', data.duduk);
        setValIfExists('#merangkak', data.merangkak);
        setValIfExists('#berdiri', data.berdiri);
        setValIfExists('#berjalan', data.berjalan);

        // =============================
        // Neonatus
        // =============================

        setRadioIfExists('neonatus', data.neonatus);
        setValIfExists('neonatus_ket', data.neonatus_ket);
        if (data.neonatus_ket !== null && data.neonatus_ket !== undefined && data.neonatus_ket !== '') {
            $('#neonatus_ket').slideDown();
        }

        // =============================
        // Imunisasi
        // =============================

        setRadioIfExists('imunisasi', data.imunisasi);
        setValIfExists('imunisasi_lain', data.imunisasi_lain);
        if (data.imunisasi_lain !== null && data.imunisasi_lain !== undefined && data.imunisasi_lain !== '') {
            $('#imunisasi_lain').slideDown();
        }

        setValIfExists(
            'textarea[name="keluhan_tumbuh_kembang"]',
            data.keluhan_tumbuh_kembang
        );

        // ======================================================
        // MASALAH KEPERAWATAN
        // ======================================================

        setCheckedIfExists('#diag_1', data.diag_1);
        setCheckedIfExists('#diag_2', data.diag_2);
        setCheckedIfExists('#diag_3', data.diag_3);
        setCheckedIfExists('#diag_4', data.diag_4);
        setCheckedIfExists('#diag_5', data.diag_5);
        setCheckedIfExists('#diag_6', data.diag_6);
        setCheckedIfExists('#diag_7', data.diag_7);
        setCheckedIfExists('#diag_8', data.diag_8);
        setCheckedIfExists('#diag_9', data.diag_9);
        setCheckedIfExists('#diag_10', data.diag_10);
        setCheckedIfExists('#diag_11', data.diag_11);
        setCheckedIfExists('#diag_12', data.diag_12);
        setCheckedIfExists('#diag_13', data.diag_13);

        setValIfExists('#diag_lain', data.diag_lain);


        // ======================================================
        // TINDAKAN
        // ======================================================

        setCheckedIfExists('#tin_1', data.tin_1);
        setCheckedIfExists('#tin_2', data.tin_2);
        setCheckedIfExists('#tin_3', data.tin_3);
        setCheckedIfExists('#tin_4', data.tin_4);
        setCheckedIfExists('#tin_5', data.tin_5);

        setCheckedIfExists('#tin_6', data.tin_6);
        setCheckedIfExists('#tin_7', data.tin_7);


        // ======================================================
        // DETAIL TERAPI
        // ======================================================

        setValIfExists('#terapi_oral', data.terapi_oral);
        setValIfExists('#terapi_iv', data.terapi_iv);

    }

    function saveDataPengkajianRJAp(btn) {
        const $button = $(btn);
        const $section = $('#rja_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rja/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                // $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (response) {
                // alert(response.message || 'Data berhasil disimpan.');
                iziToast.success({
                    title: 'Pesan Berhasil!',
                    message: 'Data berhasil disimpan.',
                    position: 'topRight'
                });
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
                // $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    };

</script>
