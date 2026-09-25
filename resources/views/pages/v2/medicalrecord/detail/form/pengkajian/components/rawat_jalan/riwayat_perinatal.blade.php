<div class="row" id="riwayat_perinatal">
    <div class="col-md-12 mb-2">
        <h6 class="mb-3">RIWAYAT PERINATAL</h6>
        <!-- Lama Hamil -->
        <div class="row mb-1 align-items-center">
            <label class="col-md-3 col-form-label">Lama Hamil</label>
            <div class="col-md-3">
                <input type="number" class="form-control form-control-sm" id="rp_lama_hamil" name="rp_lama_hamil" placeholder="0">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="rp_satuan" name="rp_satuan">
                    <option value="MINGGU">Minggu</option>
                    <option value="BULAN">Bulan</option>
                </select>
            </div>
        </div>
        <!-- Komplikasi Kehamilan -->
        <div class="row mb-1">
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
    <div class="col-md-12 mb-1">
        <h6 class="mb-3">RIWAYAT TUMBUH KEMBANG</h6>
        <div class="row">
            <!-- KOLOM KIRI -->
            <div class="col-md-6">
                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">Lingkar Kepala Saat Lahir</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="lk_lahir" id="lk_lahir">
                    </div>
                    <div class="col-md-4">cm</div>
                </div>

                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">Berat Badan Saat Lahir</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="bb_lahir" id="bb_lahir">
                    </div>
                    <div class="col-md-4">gram</div>
                </div>

                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">Tinggi Badan Saat Lahir</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="tb_lahir" id="tb_lahir">
                    </div>
                    <div class="col-md-4">cm</div>
                </div>

                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">ASI Sampai Umur</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="asi_sampai" id="asi_sampai">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="asi_satuan" id="asi_satuan">
                            <option value="BULAN">Bulan</option>
                            <option value="TAHUN">Tahun</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">Susu Formula Mulai</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="sufor_mulai" id="sufor_mulai">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="sufor_satuan" id="sufor_satuan">
                            <option value="BULAN">Bulan</option>
                            <option value="TAHUN">Tahun</option>
                        </select>
                    </div>
                </div>

                <div class="row align-items-center">
                    <label class="col-md-4 col-form-label">Makanan Tambahan</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" name="mpasi_mulai" id="mpasi_mulai">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="mpasi_satuan" id="mpasi_satuan">
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

                <div class="row mb-1 align-items-center">
                    <label class="col-md-4 col-form-label">{{ $label }}</label>
                    <div class="col-md-5">
                        <input type="number" class="form-control form-control-sm" name="{{ $name }}" id="{{ $name }}">
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
        {{-- <div class="mb-3">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_imunisasi')
        </div> --}}
    </div>
</div>

<script>
    (function () {
        'use strict';

        const $section = $(@json($section));
        const $form = $section.find('#riwayat_perinatal');

        let isRiwayatPerinatalLoading = false;
        let isRiwayatPerinatalSaving = false;

        // ==========================================================
        // GET DATA
        // ==========================================================
        function getRiwayatPerinatal() {

            if (!$form.length) {
                console.warn('Form Riwayat Perinatal tidak ditemukan.');
                return;
            }

            isRiwayatPerinatalLoading = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/rj/riwayatperinatal/${kunjungan}`,
                type: 'GET',
                dataType: 'json',

                success: function (res) {

                    const perinatal = res.data?.perinatal;
                    const tumbuhKembang = res.data?.tumbuh_kembang;

                    if (perinatal) {

                        $form.find('[name="rp_lama_hamil"]')
                            .val(perinatal.LAMA_HAMIL ?? '');

                        $form.find('[name="rp_satuan"]')
                            .val(perinatal.SATUAN ?? 'MINGGU');

                        $form.find(
                            `[name="rp_komplikasi"][value="${perinatal.KOMPLIKASI ?? 0}"]`
                        ).prop('checked', true);

                        $form.find('[name="rp_komplikasi_ket"]')
                            .val(perinatal.KOMPLIKASI_KET ?? '');

                        $form.find(
                            `[name="rp_persalinan"][value="${perinatal.PERSALINAN ?? 1}"]`
                        ).prop('checked', true);

                        $form.find(
                            `[name="rp_penyulit"][value="${perinatal.PENYULIT ?? 0}"]`
                        ).prop('checked', true);

                        $form.find('[name="rp_penyulit_ket"]')
                            .val(perinatal.PENYULIT_KET ?? '');

                        // tampilkan keterangan jika Ya
                        $form.find('#rp_komplikasi_ket').toggle(
                            Number(perinatal.KOMPLIKASI) === 1
                        );

                        $form.find('#rp_penyulit_ket').toggle(
                            Number(perinatal.PENYULIT) === 1
                        );
                    }


                    if (tumbuhKembang) {

                        $form.find('[name="lk_lahir"]')
                            .val(tumbuhKembang.LK_LAHIR ?? '');

                        $form.find('[name="bb_lahir"]')
                            .val(tumbuhKembang.BB_LAHIR ?? '');

                        $form.find('[name="tb_lahir"]')
                            .val(tumbuhKembang.TB_LAHIR ?? '');


                        $form.find('[name="asi_sampai"]')
                            .val(tumbuhKembang.ASI_SAMPAI ?? '');

                        $form.find('[name="asi_satuan"]')
                            .val(tumbuhKembang.ASI_SATUAN ?? 'BULAN');


                        $form.find('[name="sufor_mulai"]')
                            .val(tumbuhKembang.SUFOR_MULAI ?? '');

                        $form.find('[name="sufor_satuan"]')
                            .val(tumbuhKembang.SUFOR_SATUAN ?? 'BULAN');


                        $form.find('[name="mpasi_mulai"]')
                            .val(tumbuhKembang.MPASI_MULAI ?? '');

                        $form.find('[name="mpasi_satuan"]')
                            .val(tumbuhKembang.MPASI_SATUAN ?? 'BULAN');


                        // Milestone
                        $form.find('[name="tengkurap"]')
                            .val(tumbuhKembang.TENGKURAP ?? '');

                        $form.find('[name="duduk"]')
                            .val(tumbuhKembang.DUDUK ?? '');

                        $form.find('[name="merangkak"]')
                            .val(tumbuhKembang.MERANGKAK ?? '');

                        $form.find('[name="berdiri"]')
                            .val(tumbuhKembang.BERDIRI ?? '');

                        $form.find('[name="berjalan"]')
                            .val(tumbuhKembang.BERJALAN ?? '');


                        // Neonatus
                        $form.find(
                            `[name="neonatus"][value="${tumbuhKembang.NEONATUS ?? 0}"]`
                        ).prop('checked', true);

                        $form.find('[name="neonatus_ket"]')
                            .val(tumbuhKembang.NEONATUS_KET ?? '');

                        $form.find('[name="neonatus_ket"]').toggle(
                            Number(tumbuhKembang.NEONATUS) === 1
                        );


                        // Keluhan
                        $form.find('[name="keluhan_tumbuh_kembang"]')
                            .val(tumbuhKembang.KELUHAN ?? '');
                    }
                },

                error: function (xhr, status, error) {

                    console.error(
                        'Error Riwayat Perinatal:',
                        xhr.responseText || error
                    );

                    let message =
                        'Gagal mengambil data Riwayat Perinatal.';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    console.warn(message);
                },

                complete: function () {
                    isRiwayatPerinatalLoading = false;
                }
            });
        }

        // ==========================================================
        // SIMPAN DATA
        // ==========================================================
        function simpanRiwayatPerinatal() {

            if (
                !$form.length ||
                isRiwayatPerinatalLoading ||
                isRiwayatPerinatalSaving
            ) {
                return;
            }

            const data = getFormDataByName($form, {
                NOKUNJ: kunjungan
            });

            isRiwayatPerinatalSaving = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/rj/riwayatperinatal/${kunjungan}/simpan`,
                type: 'POST',
                data: data,

                headers: {
                    'X-CSRF-TOKEN': $(
                        'meta[name="csrf-token"]'
                    ).attr('content')
                },

                success: function (res) {

                },

                error: function (xhr) {

                    let message =
                        'Data Riwayat Perinatal gagal disimpan.';

                    if (
                        xhr.status === 422 &&
                        xhr.responseJSON?.errors
                    ) {
                        message = Object
                            .values(xhr.responseJSON.errors)
                            .flat()
                            .join('<br>');
                    }
                    else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    iziToast.error({
                        title: 'Validasi Gagal!',
                        message: message,
                        position: 'topRight'
                    });
                },

                complete: function () {
                    isRiwayatPerinatalSaving = false;
                }
            });
        }

        // ==========================================================
        // AUTO SAVE
        // ==========================================================
        $(function () {

            if (!$form.length) {
                return;
            }

            getRiwayatPerinatal();

            $form.on(
                'blur',
                'textarea,input',
                function () {

                    if (isRiwayatPerinatalLoading) {
                        return;
                    }

                    simpanRiwayatPerinatal();
                }
            );

            $form.on(
                'change',
                'select,input[type="checkbox"],input[type="radio"]',
                function () {

                    if (isRiwayatPerinatalLoading) {
                        return;
                    }

                    simpanRiwayatPerinatal();
                }
            );
        });

    })();
</script>