<div class="row align-items-center" id="form_masalah_keperawatan">
    <div class="col-md-12 mb-1">
        <div class="form-group mb-2">
            <h5 class="mb-0 text-success">
                <strong>Daftar Masalah Keperawatan</strong>
            </h5>
        </div>
    </div>

    <div class="col-md-12">
        @php
            $diagnosaKeperawatan = [
                'Bersihan jalan nafas tidak efektif',
                'Gangguan pertukaran gas',
                'Gangguan ventilasi spontan',
                'Pola nyeri tidak efektif',
                'Gangguan sirkulasi spontan',
                'Penurunan curah jantung',
                'Perfusi perifer tidak efektif',
                'Termoregulasi tidak efektif',
                'Resiko perfusi Gastrointestinal tidak efektif',
                'Resiko perdarahan',
                'Defisit nutrisi',
                'Diare',
                'Ketidakstabilan kadar glukosa darah',
                'Resiko ketidakseimbangan cairan',
                'Resiko ketidakseimbangan elektrolit',
                'Resiko syok',
                'Disfungsi motilitas gastrointestinal',
                'Gangguan eliminasi urine',
                'Konstipasi',
                'Retensi urine',
                'Gangguan mobilitas fisik',
                'Gangguan pola tidur',
                'Intoleransi aktivitas',
                'Gangguan menelan',
                'Gangguan rasa nyaman',
                'Nausea',
                'Nyeri akut',
                'Nyeri kronis',
                'Ansietas',
                'Gangguan persepsi sensori',
                'Defisit perawatan diri',
                'Defisit pengetahuan',
                'Gangguan interaksi sosial',
                'Gangguan komunikasi verbal',
                'Gangguan integritas kulit/jaringan',
                'Hipertermia',
                'Hipotermia',
                'Perlambatan pemulihan pasca bedah',
                'Resiko alergi',
                'Resiko cidera',
                'Resiko infeksi',
                'Hipervolemia',
                'Hipovolemia',
                'Berat badan lebih',
            ];

            $jumlahKolom = 3;
            $perKolom = ceil(count($diagnosaKeperawatan) / $jumlahKolom);
            $diagnosaColumns = array_chunk($diagnosaKeperawatan, $perKolom);
        @endphp

        <div class="row">
            @foreach ($diagnosaColumns as $columnIndex => $column)
                <div class="col-md-4">
                    @foreach ($column as $index => $label)
                        @php
                            $nomor = ($columnIndex * $perKolom) + $index + 1;
                            $name = "dmk_{$nomor}";
                        @endphp

                        <div class="form-group mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check mb-0">
                                    <input
                                        class="form-check-input check-primary"
                                        type="checkbox"
                                        name="{{ $name }}"
                                        id="{{ $name }}"
                                        value="1"
                                    >
                                </div>

                                <label
                                    class="form-label mb-0"
                                    for="{{ $name }}"
                                >
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_masalah_keperawatan');

    if (!$form.length) {
        console.warn('Form Daftar Masalah Keperawatan tidak ditemukan.');
        return;
    }

    let isLoading = false;
    let isSaving = false;
    let savePending = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getMasalahKeperawatan() {
        isLoading = true;

        const kolom = [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF',
            'GANGGUAN_PERTUKARAN_GAS',
            'GANGGUAN_VENTILASI_SPONTAN',
            'POLA_NYERI_TIDAK_EFEKTIF',
            'GANGGUAN_SIRKULASI_SPONTAN',
            'PENURUNAN_CURAH_JANTUNG',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF',
            'TERMOREGULASI_TIDAK_EFEKTIF',
            'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF',
            'RESIKO_PERDARAHAN',
            'DEFISIT_NUTRISI',
            'DIARE',
            'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH',
            'RESIKO_KETIDAKSEIMBANGAN_CAIRAN',
            'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT',
            'RESIKO_SYOK',
            'DISFUNGSI_MOTILITAS_GASTROINTESTINAL',
            'GANGGUAN_ELIMINASI_URINE',
            'KONSTIPASI',
            'RETENSI_URINE',
            'GANGGUAN_MOBILITAS_FISIK',
            'GANGGUAN_POLA_TIDUR',
            'INTOLERANSI_AKTIVITAS',
            'GANGGUAN_MENELAN',
            'GANGGUAN_RASA_NYAMAN',
            'NAUSEA',
            'NYERI_AKUT',
            'NYERI_KRONIS',
            'ANSIETAS',
            'GANGGUAN_PERSEPSI_SENSORI',
            'DEFISIT_PERAWATAN_DIRI',
            'DEFISIT_PENGETAHUAN',
            'GANGGUAN_INTERAKSI_SOSIAL',
            'GANGGUAN_KOMUNIKASI_VERBAL',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN',
            'HIPERTERMI',
            'HIPOTERMI',
            'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH',
            'RESIKO_ALERGI',
            'RESIKO_CIDERA',
            'RESIKO_INFEKSI',
            'HIPERVOLEMIA',
            'HIPOVOLEMIA',
            'BERAT_BADAN_LEBIH'
        ];

        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/masalahkeperawatan/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {
                const data = res?.data;

                if (!data) {
                    return;
                }

                kolom.forEach((field, index) => {
                    const name = `dmk_${index + 1}`;
                    const value = FormHelper.hasValue(data[field])
                        ? data[field]
                        : 0;

                    FormHelper.setCheckbox(
                        $form,
                        name,
                        value
                    );
                });
            },

            error: function (xhr, status, error) {
                console.error(
                    'Error Daftar Masalah Keperawatan:',
                    xhr.responseText || error
                );
            },

            complete: function () {
                isLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanMasalahKeperawatan() {

        if (isLoading) {
            return;
        }

        // Jika masih ada request yang berjalan,
        // tandai bahwa setelah selesai harus save lagi.
        if (isSaving) {
            savePending = true;
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isSaving = true;
        savePending = false;

        $.ajax({
            url: `/api/v2/emr/pengkajian/ri/masalahkeperawatan/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (res) {
                if (!res?.status) {
                    console.warn(
                        'Masalah Keperawatan gagal disimpan:',
                        res?.message
                    );
                }
            },

            error: function (xhr) {
                let message = 'Data Daftar Masalah Keperawatan gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isSaving = false;

                // Jika user melakukan perubahan lagi ketika
                // request sebelumnya masih berjalan,
                // simpan kondisi checkbox terbaru.
                if (savePending) {
                    simpanMasalahKeperawatan();
                }
            }
        });
    }

    // ==========================================================
    // INIT
    // ==========================================================
    getMasalahKeperawatan();

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $form.on(
        'change',
        'input[type="checkbox"]',
        function () {
            simpanMasalahKeperawatan();
        }
    );

})();
</script>
